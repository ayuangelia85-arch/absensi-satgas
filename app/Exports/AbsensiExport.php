<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil semua data absensi beserta relasi user
        return Absensi::with('user')->get()->map(function ($item) {
            return [
                'NIM/NIP'     => $item->user->nim_nip ?? '-',
                'Nama'        => $item->user->name ?? '-',
                'Tanggal'     => $item->tanggal ?? '-',
                'Jam Masuk'   => $item->jam_masuk ?? '-',
                'Jam Keluar'  => $item->jam_keluar ?? '-',
                'Status'      => $item->status ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['NIM/NIP', 'Nama', 'Tanggal', 'Jam Masuk', 'Jam Keluar', 'Status'];
    }
}
