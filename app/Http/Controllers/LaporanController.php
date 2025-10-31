<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = Absensi::with('user')->get();
        return view('laporan.index', compact('laporan'));
    }

    public function exportPdf()
    {
        $laporan = Absensi::with('user')->get();
        $pdf = Pdf::loadView('laporan.pdf', compact('laporan'));
        return $pdf->download('laporan_absensi.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new AbsensiExport, 'laporan_absensi.xlsx');
    }
}
