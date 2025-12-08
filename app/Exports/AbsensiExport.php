<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return Absensi::select(
                    'user_id',
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
                ->whereMonth('tanggal', $this->bulan)
                ->whereYear('tanggal', $this->tahun)
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->with('user')
                ->orderByDesc('total_jam')
                ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama User',
            'Bulan',
            'Total Jam',
            'Keterangan'
        ];
    }

    public function map($row): array
    {
        static $no = 1;
        $status = $row->total_jam >= 12 ? 'Terpenuhi' : 'Tidak Terpenuhi';

        return [
            $no++,
            $row->user->name ?? '-',
            $this->bulan . '/' . $this->tahun,
            number_format($row->total_jam, 2),
            $status,
        ];
    }
}
