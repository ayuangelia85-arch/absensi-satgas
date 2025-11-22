<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        public function dashboard()
    {
        $user = auth()->user();
        $totalJam = Absensi::where('user_id', $user->id)
                    ->whereMonth('tanggal', now()->month)
                    ->sum('durasi_jam');

        $absensiBulanIni = Absensi::where('user_id', $user->id)
                            ->whereMonth('tanggal', now()->month)
                            ->get();

        return view('dashboard.user', compact('user', 'absensiBulanIni', 'totalJam'));
    }

        public function update(Request $request, $id)
    {
        // validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'nim_nip' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'status' => 'required|string',
            'role' => 'required|string',
        ]);

        // ambil user berdasarkan id
        $user = User::findOrFail($id);

        // update data user
        $user->update([
            'name' => $request->name,
            'nim_nip' => $request->nim_nip,
            'email' => $request->email,
            'status' => $request->status,
            'role' => $request->role,
        ]);

        // redirect balik ke daftar user
        return redirect()->route('admin.user.index')->with('success', 'Data user berhasil diperbarui!');
    }

        public function profil()
    {
        $user = auth()->user();
        return view('dashboard.profil', compact('user'));
    }


}
