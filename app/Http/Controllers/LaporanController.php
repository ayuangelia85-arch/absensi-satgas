<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Absensi::with('user');

        if ($request->start_date) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $laporan = $query->get();

        return view('laporan.index', compact('laporan'));
    }


    public function exportPdf(Request $request)
    {
        $query = Absensi::with('user');

        if ($request->start_date) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $laporan = $query->get();

        $pdf = Pdf::loadView('laporan.pdf', [
        'laporan'    => $laporan,
        'start_date' => $request->start_date,
        'end_date'   => $request->end_date]);
        return $pdf->download('laporan_absensi.pdf');
    }


    public function exportExcel(Request $request)
    {
        return Excel::download(
            new AbsensiExport($request->start_date, $request->end_date),
            'laporan_absensi.xlsx'
        );
    }
}
