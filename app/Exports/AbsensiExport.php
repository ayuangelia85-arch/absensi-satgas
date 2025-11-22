<?php

namespace App\Exports;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiExport implements FromCollection, WithHeadings
{
    protected $start_date;
    protected $end_date;

    public function __construct($start_date = null, $end_date = null)
    {
        $this->start_date = $start_date;
        $this->end_date   = $end_date;
    }

    /**
     * Ambil data absensi berdasarkan filter tanggal.
     */
    protected function queryData()
    {
        $query = Absensi::with('user');

        if ($this->start_date) {
            $query->whereDate('tanggal', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $query->whereDate('tanggal', '<=', $this->end_date);
        }

        return $query->get();
    }

    /**
     * Format rentang periode untuk header Excel.
     */
    protected function formatPeriode()
    {
        $start = $this->start_date
            ? Carbon::parse($this->start_date)->translatedFormat('d F Y')
            : '-';

        $end = $this->end_date
            ? Carbon::parse($this->end_date)->translatedFormat('d F Y')
            : '-';

        return "$start s/d $end";
    }

    /**
     * Buat header informasi (periode & waktu cetak).
     */
    protected function infoRows(): Collection
    {
        return collect([
            [
                'No'          => '',
                'Nama'        => 'Periode:',
                'Tanggal'     => $this->formatPeriode(),
                'Jam Masuk'   => '',
                'Jam Keluar'  => '',
                'Total Waktu' => '',
                'Keterangan'  => '',
            ],
            [
                'No'          => '',
                'Nama'        => 'Dicetak pada:',
                'Tanggal'     => Carbon::now()
                    ->setTimezone('Asia/Jakarta')
                    ->translatedFormat('d F Y, H:i') . ' WIB',
                'Jam Masuk'   => '',
                'Jam Keluar'  => '',
                'Total Waktu' => '',
                'Keterangan'  => '',
            ]
        ]);
    }

    /**
     * Hitung total waktu absensi dalam format HH:MM:SS.
     */
    protected function hitungTotalWaktu($item)
    {
        if (!$item->jam_masuk || !$item->jam_keluar) {
            return '-';
        }

        $masuk  = Carbon::parse($item->jam_masuk);
        $keluar = Carbon::parse($item->jam_keluar);
        $diff   = $keluar->diff($masuk);

        return sprintf('%02d:%02d:%02d',
            $diff->h + ($diff->d * 24),
            $diff->i,
            $diff->s
        );
    }

    /**
     * Mapping data tiap row absensi.
     */
    protected function mapRow($item, $index)
    {
        return [
            'No'          => $index + 1,
            'Nama'        => $item->user->name ?? '-',
            'Tanggal'     => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
            'Jam Masuk'   => $item->jam_masuk ?? '-',
            'Jam Keluar'  => $item->jam_keluar ?? '-',
            'Total Waktu' => $this->hitungTotalWaktu($item),
            'Keterangan'  => ucfirst($item->keterangan ?? '-'),
        ];
    }

    /**
     * Data final yang dikirim ke Excel.
     */
    public function collection()
    {
        $data = $this->queryData();

        // Mapping data absensi
        $rows = $data->map(function ($item, $index) {
            return $this->mapRow($item, $index);
        });

        // Gabungkan: info header + data utama
        return $this->infoRows()->merge($rows);
    }

    /**
     * Header tabel utama.
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Total Waktu',
            'Keterangan',
        ];
    }
}
