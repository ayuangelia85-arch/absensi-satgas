<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        $campusLat = -6.234943;
        $campusLng = 106.747206;
        $radius = 800; // meter

        // hitung jarak user ke kampus
        $distance = $this->distance(
            $request->latitude,
            $request->longitude,
            $campusLat,
            $campusLng
        );

        if ($distance > $radius) {
            return back()->with(
                'error',
                'Absensi gagal! Kamu berada di luar area Kampus Budi Luhur.'
            );
        }

        // cek sudah absen hari ini
        $cek = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
            ->first();

        if ($cek) {
            return back()->with('error', 'Kamu sudah melakukan Check-In hari ini!');
        }

        // simpan absensi
        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'jam_masuk' => now('Asia/Jakarta')->toTimeString(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'keterangan' => 'hadir',
        ]);

        return back()->with('success', 'Berhasil Check-In di area kampus!');
    }

    private function distance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
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

    

    public function history(Request $request)
    {
        $user = auth()->user();

        // ambil bulan & tahun dari request (default: bulan ini)
        $bulan = $request->bulan ?? now('Asia/Jakarta')->month;
        $tahun = $request->tahun ?? now('Asia/Jakarta')->year;

        // ambil data absensi per bulan
        $absensis = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'desc')
            ->get();

        // hitung total jam kerja
        $totalJam = 0;
        foreach ($absensis as $absen) {
            if ($absen->jam_masuk && $absen->jam_keluar) {
                $masuk = strtotime($absen->jam_masuk);
                $keluar = strtotime($absen->jam_keluar);
                $totalJam += ($keluar - $masuk) / 3600;
            }
        }

        return view('dashboard.absensi-history', compact(
            'absensis',
            'bulan',
            'tahun',
            'totalJam'
        ));
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
        $absensi = Absensi::findOrFail($id);

        return view('absensi.location', compact('absensi'));
    }

      public function profil()
    {
        $user = auth()->user();
        return view('dashboard.profil', compact('user'));
    }


}
