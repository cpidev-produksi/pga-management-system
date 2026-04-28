<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Models\Visitor;
use App\Models\User;
use Carbon\Carbon;
use App\Exports\VisitorsExport;    
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;   
use Illuminate\Support\Facades\Auth;   


class DataVisitorController extends Controller
{

    public function index(Request $request)
    {
        $query = Visitor::query();

        // --- FILTER RENTANG TANGGAL (RANGE) ---
        if ($request->filled('start_date') && $request->filled('end_date')) {
            // Mengambil data dari jam 00:00 start_date sampai 23:59 end_date
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('visit_datetime', [$start, $end]);
        } 
        elseif ($request->filled('start_date')) {
            // Hanya ada tanggal awal
            $query->whereDate('visit_datetime', '>=', $request->start_date);
        }
        elseif ($request->filled('end_date')) {
             // Hanya ada tanggal akhir
            $query->whereDate('visit_datetime', '<=', $request->end_date);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('identity_number', 'like', "%{$search}%");
            });
        }
        
        $visitors = $query->latest()->paginate($request->per_page ?? 10);

        return view('data-visitor.visitors', [
            'visitors' => $visitors,
            'request' => $request 
        ]);
    }

    public function exportExcel(Request $request)
    {
        // Penamaan file agar mencerminkan rentang waktu
        $prefix = 'visitors';
        if($request->filled('start_date') && $request->filled('end_date')){
            $prefix .= '-from-' . $request->start_date . '-to-' . $request->end_date;
        } else {
            $prefix .= '-' . Carbon::now()->format('Y-m-d');
        }
        
        return Excel::download(new VisitorsExport($request), "$prefix.xlsx");
    }

    public function exportPdf($uuid)
    {
        // Ambil data visitor
        $visitor = Visitor::where('uuid', $uuid)->firstOrFail();

        // Load View PDF (pastikan file resources/views/pdf/visitor_detail.blade.php sudah dibuat)
        $pdf = Pdf::loadView('pdf.visitor_detail', compact('visitor'));
        
        // Atur ukuran kertas
        $pdf->setPaper('A4', 'portrait');

        // Nama file saat didownload
        $fileName = 'Visitor-' . str_replace(' ', '-', $visitor->name) . '.pdf';

        return $pdf->download($fileName);
    }

    public function show($uuid)
    {
        // Mengambil data visitor berdasarkan UUID
        // firstOrFail akan otomatis return 404 jika data tidak ditemukan
        $visitor = Visitor::where('uuid', $uuid)->firstOrFail();

        // Arahkan ke view detail (pastikan path folder view sesuai)
        return view('data-visitor.show', compact('visitor'));
    }

    public function checkout($uuid)
    {
        $visitor = Visitor::where('uuid', $uuid)->firstOrFail();

        // Cek apakah sudah checkout sebelumnya
        if ($visitor->checkout_at) {
            return back()->with('error', 'Pengunjung ini sudah melakukan checkout sebelumnya.');
        }

        // Update waktu checkout
        $visitor->update([
            'checkout_at' => Carbon::now(),
            'checkout_by' => Auth::user()->uuid,
        ]);

        return back()->with('success', 'Berhasil melakukan checkout pengunjung: ' . $visitor->name);
    }

}