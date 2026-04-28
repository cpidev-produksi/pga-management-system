<?php

namespace App\Exports;

use App\Models\Visitor;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell; // Tambahan untuk start baris
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VisitorsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    protected $request;
    private $rowNumber = 0;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Tentukan posisi awal Header Table.
     * Kita mulai di A2 agar A1 bisa dipakai untuk Judul Besar.
     */
    public function startCell(): string
    {
        return 'A2';
    }

    public function query()
    {
        $query = Visitor::query();

        // LOGIKA FILTER TANGGAL (RANGE)
        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $start = Carbon::parse($this->request->start_date)->startOfDay();
            $end = Carbon::parse($this->request->end_date)->endOfDay();
            $query->whereBetween('visit_datetime', [$start, $end]);
        } elseif ($this->request->filled('start_date')) {
            $query->whereDate('visit_datetime', '>=', $this->request->start_date);
        } elseif ($this->request->filled('end_date')) {
            $query->whereDate('visit_datetime', '<=', $this->request->end_date);
        }

        // Filter Status
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        // Filter Search
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('identity_number', 'like', "%{$search}%");
            });
        }
        
        $query->orderBy('visit_datetime', 'asc');

        return $query;
    }

    public function map($visitor): array
    {
        $this->rowNumber++;

        // Formatting Vehicles (JSON)
        $vehiclesString = '-';
        if (!empty($visitor->vehicles) && is_array($visitor->vehicles)) {
            $vehiclesString = collect($visitor->vehicles)
                ->map(fn($v) => ($v['type'] ?? '') . ' (' . ($v['number'] ?? '') . ')')
                ->implode(", \n");
        }

        // Formatting Group Members Lengkap
        $membersString = '-';
        if (!empty($visitor->group_members) && is_array($visitor->group_members)) {
            $membersString = collect($visitor->group_members)
                ->map(function($m) {
                    if (is_array($m)) {
                        $name = $m['name'] ?? '-';
                        $id = $m['id'] ?? '-';
                        $age = $m['age'] ?? '-';
                        $phone = $m['phone'] ?? '-';
                        return "Nama: $name\n   (ID: $id, Usia: $age thn, HP: $phone)";
                    }
                    return $m;
                })
                ->implode("\n\n");
        }

        return [
            $this->rowNumber,
            $visitor->visit_datetime->format('d-m-Y H:i'),
            $visitor->visit_type,
            $visitor->identity_number,
            $visitor->name,
            $visitor->age,
            $visitor->phone_number,
            $visitor->company_name,
            $visitor->address,
            $visitor->phone_number_emrg,
            $visitor->email,
            $visitor->internet ? 'Ya' : 'Tidak',
            $visitor->is_production ? 'Ya' : 'Tidak',
            $visitor->intended_employee,
            $visitor->purpose,
            $visitor->purpose_note,
            $visitor->special_category,
            $visitor->special_needs,
            $visitor->group_type,
            $vehiclesString,
            $membersString,
            $visitor->status == 1 ? 'Sudah Scan' : 'Belum Scan',
            $visitor->scanner->name ?? '-',
            $visitor->checkouter->name ?? '-',
            $visitor->checkout_at ? $visitor->checkout_at->format('d-m-Y H:i') : 'Belum',
            $visitor->created_at->format('d-m-Y H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Waktu Kunjungan',
            'Tipe Kunjungan',
            'No Identitas',
            'Nama Pengunjung',
            'Usia',
            'No Telepon',
            'Nama Perusahaan',
            'Alamat',
            'No Darurat',
            'Email',
            'Butuh Internet',
            'Masuk Produksi?',
            'Karyawan yang Dituju',
            'Tujuan',
            'Catatan Tujuan',
            'Kategori Khusus',
            'Kebutuhan Khusus',
            'Tipe Grup',
            'Kendaraan',
            'Anggota Rombongan',
            'Status',
            'Check-in Oleh',
            'Checkout Oleh',
            'Waktu Checkout',
            'Dibuat Pada',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // --- 1. SET JUDUL UTAMA (Baris 1) ---
                $event->sheet->setCellValue('A1', 'Data Pengunjung PT. Charoen Pokphand Indonesia Plant Ngoro - Mojokerto');
                $event->sheet->mergeCells('A1:Y1'); // Merge dari kolom A sampai W
                
                // Style Judul Utama
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['argb' => '000000'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Set Tinggi Baris Judul agar lega
                $event->sheet->getRowDimension('1')->setRowHeight(30);


                // --- 2. DEFINISI RANGE DATA (Karena geser ke bawah) ---
                // Header Tabel ada di baris 2
                // Data mulai baris 3
                // Total baris = rowNumber (jumlah data) + 2 (Judul + Header)
                $lastRow = $this->rowNumber + 2;
                
                $tableRange = 'A2:Z' . $lastRow;  // Seluruh Tabel (Header + Data)
                $headerRange = 'A2:Z2';           // Baris Header saja
                $dataRange = 'A3:Z' . $lastRow;   // Baris Data saja

                // --- 3. STYLE GLOBAL & ALIGNMENT ---
                $event->sheet->getStyle($tableRange)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
                
                // Wrap Text untuk kolom panjang (Kendaraan, Rombongan, Catatan)
                // Kolom S(19), T(20), O(15) -- Baris mulai dari 3 sampai akhir
                $event->sheet->getStyle('S3:T'.$lastRow)->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('O3:O'.$lastRow)->getAlignment()->setWrapText(true);


                // --- 4. STYLE BORDER TABEL ---
                $event->sheet->getStyle($tableRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);


                // --- 5. STYLE HEADER TABEL (Baris 2) ---
                $event->sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['argb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => '4CAF50'], // Warna Hijau
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'outline' => ['borderStyle' => Border::BORDER_THICK],
                        'bottom' => ['borderStyle' => Border::BORDER_DOUBLE],
                    ],
                ]);


                // --- 6. ALIGNMENT CENTER UNTUK KOLOM TERTENTU (Mulai baris 3) ---
                $columnsToCenter = ['A', 'B', 'F', 'L', 'U', 'W'];
                foreach($columnsToCenter as $col) {
                    $event->sheet->getStyle($col.'3:'.$col.$lastRow)
                        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}