<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Visitor;
use Illuminate\Support\Facades\Log;

// --- IMPORT LANGSUNG ENGINE (Bypass SimpleQrCode Wrapper) ---
use BaconQrCode\Renderer\ImageRenderer;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
// -----------------------------------------------------------

class ReservationSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $visitor;
    public $qrCodeImage;
    public $isUpdate;

    public function __construct(Visitor $visitor, $isUpdate = false)
    {
        $this->visitor = $visitor;
        $this->isUpdate = $isUpdate;
        $this->qrCodeImage = $this->generateQrWithLogoDirect($visitor->uuid);
    }

    private function generateQrWithLogoDirect($data)
    {
        try {
            // --- STEP 1: Generate QR Raw String (Format PNG) ---
            // Kita gunakan Facade karena dia menghandle driver GD secara internal
            // Error Correction 'H' (High) WAJIB dipakai jika ingin menempel logo
            // agar data QR tidak rusak tertutup gambar.
            $qrRaw = QrCode::format('png')
                        ->size(300)
                        ->margin(1)
                        ->errorCorrection('H') 
                        ->generate($data);

            // --- STEP 2: Proses Tempel Logo (Logika GD Manual) ---
            $logoPath = public_path('assets/img/logo-cpi.png');

            // Jika logo fisik tidak ada, langsung return QR polos
            if (!file_exists($logoPath)) {
                return 'data:image/png;base64,' . base64_encode($qrRaw);
            }

            // --- Manipulasi Gambar GD Manual ---
            // Membaca string raw dari QrCode di atas menjadi Image Resource GD
            $qrImage = imagecreatefromstring($qrRaw);
            
            // Deteksi Tipe Logo
            $imageInfo = getimagesize($logoPath);
            $mimeType = $imageInfo['mime'] ?? null;
            $logoImage = null;

            if ($mimeType === 'image/png') {
                $logoImage = imagecreatefrompng($logoPath);
            } elseif ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg') {
                $logoImage = imagecreatefromjpeg($logoPath);
            }

            if (!$logoImage) {
                return 'data:image/png;base64,' . base64_encode($qrRaw);
            }

            // Hitung Ukuran
            $qrWidth = imagesx($qrImage);
            $qrHeight = imagesy($qrImage);
            $logoWidth = imagesx($logoImage);
            $logoHeight = imagesy($logoImage);

            // Resize Logo ke 20-22% dari ukuran QR
            // Jangan terlalu besar agar QR tetap terbaca
            $logoNewWidth = $qrWidth * 0.22;
            $scale = $logoNewWidth / $logoWidth;
            $logoNewHeight = $logoHeight * $scale;

            $x = ($qrWidth - $logoNewWidth) / 2;
            $y = ($qrHeight - $logoNewHeight) / 2;

            // Handle Transparansi untuk Logo PNG
            if ($mimeType === 'image/png') {
                imagecolortransparent($logoImage, imagecolorallocatealpha($logoImage, 0, 0, 0, 127));
                imagealphablending($logoImage, false);
                imagesavealpha($logoImage, true);
            }

            // Tempel Logo ke Tengah QR
            imagecopyresampled(
                $qrImage, $logoImage,
                (int)$x, (int)$y,
                0, 0,
                (int)$logoNewWidth, (int)$logoNewHeight,
                $logoWidth, $logoHeight
            );

            // Output Base64
            ob_start();
            imagepng($qrImage);
            $mergedImage = ob_get_contents();
            ob_end_clean();

            // Bersihkan memori
            imagedestroy($qrImage);
            imagedestroy($logoImage);

            return 'data:image/png;base64,' . base64_encode($mergedImage);

        } catch (\Exception $e) {
            Log::error('QR Fatal Error: ' . $e->getMessage());
            // Fallback ke QuickChart jika server gagal generate
            return 'https://quickchart.io/qr?text=' . urlencode($data) . '&size=300';
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Visitor Pass - PT. Charoen Pokphand Indonesia',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation_success',
        );
    }
}