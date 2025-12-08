<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
 
    public function index(Request $request)
    {
        // default = bulan & tahun sekarang
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        // mulai query: hitung total jam per user selama bulan/tahun itu
        // gunakan TIMEDIFF & TIME_TO_SEC agar akurat
        $rekap = Absensi::select('user_id',
                DB::raw("
                    SUM(
                        CASE
                            WHEN jam_masuk IS NOT NULL AND jam_keluar IS NOT NULL
                            THEN TIME_TO_SEC(TIMEDIFF(jam_keluar, jam_masuk))
                            ELSE 0
                        END
                    ) / 3600 AS total_jam
                ")
            )
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->with('user')
            ->orderByDesc('total_jam')
            ->get();

        // untuk mengisi dropdown tahun di view — ambil rentang tahun dari absensi (atau buat array)
        $tahunList = Absensi::selectRaw('YEAR(tanggal) as y')->distinct()->orderByDesc('y')->pluck('y')->toArray();
        if (empty($tahunList)) {
            $tahunList = [now()->year];
        }

        return view('laporan.index', [
            'rekap' => $rekap,
            'tahunList' => $tahunList,
            'selectedBulan' => (int)$bulan,
            'selectedTahun' => (int)$tahun,
            'targetJam' => 12, // target 12 jam per bulan
        ]);
    }

    public function exportPdf(Request $request)
    {
        // pastikan timezone sesuai app
        date_default_timezone_set('Asia/Jakarta');

        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $rekap = Absensi::select('user_id',
                DB::raw("
                    SUM(
                        CASE
                            WHEN jam_masuk IS NOT NULL AND jam_keluar IS NOT NULL
                            THEN TIME_TO_SEC(TIMEDIFF(jam_keluar, jam_masuk))
                            ELSE 0
                        END
                    ) / 3600 AS total_jam
                ")
            )
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('user_id')
            ->with('user')
            ->orderByDesc('total_jam')
            ->get();

        $data = [
            'rekap' => $rekap,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'targetJam' => 12,
            // tambah formatted datetime di sini
            'dicetak' => now()->locale('id')->translatedFormat('d F Y, H:i') . " WIB",
        ];

        $pdf = Pdf::loadView('laporan.pdf', $data);
        return $pdf->download("rekap_{$tahun}_{$bulan}.pdf");
    }


    public function exportExcel(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        // export class akan menerima bulan & tahun
        return Excel::download(new AbsensiExport($bulan, $tahun), "rekap_{$tahun}_{$bulan}.xlsx");
    }

}
