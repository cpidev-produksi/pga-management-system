<?php

namespace App\Services;

use App\Models\Plant;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Model;

/**
 * QrCodeService
 * --------------
 * Mengelola pembuatan & verifikasi payload QR Code ber-signature.
 *
 * FORMAT PAYLOAD:  {PLANT_CODE}.{VISITOR_UUID}.{SIGNATURE}
 *   - PLANT_CODE   : kode plant (mis. "SLT"), agar scanner langsung tahu plant.
 *   - VISITOR_UUID : uuid visitor.
 *   - SIGNATURE    : HMAC-SHA256 atas "{PLANT_CODE}.{VISITOR_UUID}" memakai APP_KEY,
 *                    dipotong 24 karakter hex. Mencegah QR dipalsukan / diubah plant-nya.
 *
 * Delimiter "." aman karena uuid (hex+strip), kode plant (alfanumerik),
 * dan signature (hex) tidak mengandung titik.
 */
class QrCodeService
{
    /** Panjang signature (karakter hex). 24 hex = 96 bit, cukup untuk use-case ini. */
    private const SIG_LENGTH = 24;

    /**
     * Buat payload QR ber-signature untuk seorang visitor.
     */
    public function generatePayload(Visitor $visitor): string
    {
        $plant = $visitor->plant; // relasi belongsTo Plant via plant_uuid

        if (! $plant) {
            throw new \RuntimeException('Visitor belum terhubung ke plant manapun, QR tidak bisa dibuat.');
        }

        $base = $plant->code . '.' . $visitor->uuid;

        return $base . '.' . $this->sign($base);
    }

    /**
     * Verifikasi payload hasil scan.
     *
     * @return array{plant: Plant, visitor: Visitor}
     *
     * @throws InvalidQrException bila format/tanda tangan/plant/visitor tidak valid.
     */
    public function verify(string $payload): array
    {
        $payload = trim($payload);

        // 1. Pecah & validasi format.
        $parts = explode('.', $payload);
        if (count($parts) !== 3) {
            throw new InvalidQrException('Format QR tidak dikenali.');
        }

        [$plantCode, $visitorUuid, $signature] = $parts;

        // 2. Verifikasi signature (anti-palsu).
        $expected = $this->sign($plantCode . '.' . $visitorUuid);
        if (! hash_equals($expected, $signature)) {
            throw new InvalidQrException('QR Code tidak valid atau telah dimodifikasi.');
        }

        // 3. Resolusi plant.
        $plant = Plant::where('code', $plantCode)->first();
        if (! $plant) {
            throw new InvalidQrException('Plant pada QR tidak ditemukan.');
        }

        // 4. Resolusi visitor (lewati global scope agar bisa memberi pesan presisi),
        //    pastikan visitor benar-benar milik plant pada QR.
        $visitor = Visitor::withoutGlobalScope('plant')
            ->where('uuid', $visitorUuid)
            ->where('plant_uuid', $plant->uuid)
            ->first();

        if (! $visitor) {
            throw new InvalidQrException('Data reservasi tidak ditemukan untuk QR ini.');
        }

        return ['plant' => $plant, 'visitor' => $visitor];
    }

    /**
     * Hitung signature HMAC atas data tertentu.
     */
    private function sign(string $data): string
    {
        $key = config('app.key');

        return substr(hash_hmac('sha256', $data, $key), 0, self::SIG_LENGTH);
    }
}
