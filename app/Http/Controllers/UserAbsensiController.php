<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class UserAbsensiController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil absensi hari ini
        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
            ->first();

        // Ambil total jam kerja bulan ini
        $absensiBulanIni = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', now('Asia/Jakarta')->month)
            ->get();

        $totalJam = 0;
        foreach ($absensiBulanIni as $absen) {
            if ($absen->jam_masuk && $absen->jam_keluar) {
                $masuk = strtotime($absen->jam_masuk);
                $keluar = strtotime($absen->jam_keluar);
                $totalJam += ($keluar - $masuk) / 3600; // hitung jam
            }
        }

        return view('dashboard.user', compact('user', 'absensiHariIni', 'totalJam'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Validasi koordinat
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // Cek apakah sudah absen hari ini
        $cek = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
            ->first();

        if ($cek) {
            return back()->with('error', 'Kamu sudah melakukan Check-In hari ini!');
        }

        // Simpan absensi
        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'jam_masuk' => now('Asia/Jakarta')->toTimeString(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'keterangan' => 'hadir',
        ]);

        return back()->with('success', 'Berhasil Check-In!');
    }

    public function keluar()
    {
        $user = auth()->user();

        $absensi = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
            ->first();

        if (!$absensi) {
            return back()->with('error', 'Kamu belum Check-In!');
        }

        if ($absensi->jam_keluar) {
            return back()->with('error', 'Kamu sudah Check-Out!');
        }

        $absensi->update([
            'jam_keluar' => now('Asia/Jakarta')->toTimeString(),
        ]);

        return back()->with('success', 'Berhasil Check-Out!');
    }

    public function showLocation($id)
    {
        $absensi = Absensi::where('id', $id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

        return view('absensi.location', compact('absensi'));
    }


}
