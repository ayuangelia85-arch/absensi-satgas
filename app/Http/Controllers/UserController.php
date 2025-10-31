<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil absensi hari ini, bisa null kalau belum absen
        $absensiHariIni = Absensi::where('user_id', $user->id)
                            ->whereDate('tanggal', now()->toDateString())
                            ->first();

        return view('dashboard.user', compact('user', 'absensiHariIni'));
    }

    public function masuk(Request $request)
    {
        $user = auth()->user();

        // Cek kalau hari ini belum absen
        $absensi = Absensi::firstOrCreate(
            ['user_id' => $user->id, 'tanggal' => now()->toDateString()],
            ['jam_masuk' => now()]
        );

        return redirect()->route('user.dashboard')->with('success', 'Absensi masuk berhasil!');
    }

    public function keluar(Request $request)
    {
        $user = auth()->user();

        $absensi = Absensi::where('user_id', $user->id)
                        ->whereDate('tanggal', now()->toDateString())
                        ->first();

        if ($absensi && !$absensi->jam_keluar) {
            $absensi->update(['jam_keluar' => now()]);
        }

        return redirect()->route('user.dashboard')->with('success', 'Check Out berhasil!');
    }
}
