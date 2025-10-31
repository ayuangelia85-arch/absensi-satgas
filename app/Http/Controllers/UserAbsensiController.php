<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class UserAbsensiController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
            ->first();

        return view('dashboard.user', compact('user', 'absensiHariIni'));
    }

    public function store()
    {
        $user = auth()->user();

        $cek = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
            ->first();

        if ($cek) {
            return back()->with('error', 'Kamu sudah melakukan Check-In hari ini!');
        }

        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'jam_masuk' => now('Asia/Jakarta')->toTimeString(),
            'status' => 'hadir',
            'keterangan' => null,
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
}
