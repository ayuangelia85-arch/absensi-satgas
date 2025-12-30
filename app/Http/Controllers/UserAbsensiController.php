<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserAbsensiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = now('Asia/Jakarta')->toDateString();

        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        // Status untuk kontrol UI
        $status = [
            'sudah_checkin'  => false,
            'sudah_kegiatan' => false,
            'sudah_checkout' => false,
        ];

        if ($absensiHariIni) {
            $status['sudah_checkin'] = true;

            if ($absensiHariIni->kegiatan) {
                $status['sudah_kegiatan'] = true;
            }

            if ($absensiHariIni->jam_keluar) {
                $status['sudah_checkout'] = true;
            }
        }

        // Total jam bulan ini (punya kamu sudah benar)
        $absensiBulanIni = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', now('Asia/Jakarta')->month)
            ->get();

        $totalJam = 0;
        foreach ($absensiBulanIni as $absen) {
            if ($absen->jam_masuk && $absen->jam_keluar) {
                $totalJam += (strtotime($absen->jam_keluar) - strtotime($absen->jam_masuk)) / 3600;
            }
        }

        return view('dashboard.user', compact(
            'user',
            'absensiHariIni',
            'status',
            'totalJam'
        ));
    }


    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $campusLat = -6.234943;
        $campusLng = 106.747206;
        $radius = 800;

        $distance = $this->distance($request->latitude, $request->longitude, $campusLat, $campusLng);
        if ($distance > $radius) {
            return back()->with('error', 'Absensi gagal! Kamu berada di luar area Kampus.');
        }

        $cek = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
            ->first();

        if ($cek) {
            return back()->with('error', 'Kamu sudah Check-In hari ini!');
        }

        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'jam_masuk' => now('Asia/Jakarta')->toTimeString(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'kegiatan' => null,
            'keterangan' => 'hadir',
        ]);

        return redirect()->route('user.absensi')->with('success', 'Berhasil Check-In!');
    }

    private function distance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2)**2 + cos(deg2rad($lat1))*cos(deg2rad($lat2))*sin($dLon/2)**2;
        $c = 2*atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }

    public function simpanKegiatan(Request $request, $id)
    {
        $request->validate([
            'kegiatan' => 'required|string'
        ]);

        $absensi = Absensi::findOrFail($id);

        $absensi->kegiatan = $request->kegiatan;
        $absensi->save();

        return redirect()->back()->with('success', 'Kegiatan berhasil disimpan');
    }

    public function keluar()
    {
        $user = auth()->user();

        $absensi = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
            ->first();

        if (!$absensi) return redirect()->route('user.absensi')->with('error', 'Belum Check-In!');
        if ($absensi->jam_keluar) return redirect()->route('user.absensi')->with('error', 'Sudah Check-Out!');
        if (!$absensi->kegiatan) return redirect()->route('user.absensi')->with('error', 'Isi kegiatan terlebih dahulu!');

        $absensi->update(['jam_keluar' => now('Asia/Jakarta')->toTimeString()]);

        return redirect()->route('user.absensi')->with('success', 'Berhasil Check-Out!');
    }

    public function history(Request $request)
    {
        $user = auth()->user();
        $bulan = $request->bulan ?? now('Asia/Jakarta')->month;
        $tahun = $request->tahun ?? now('Asia/Jakarta')->year;

        $absensis = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalJam = 0;
        foreach ($absensis as $absen) {
            if ($absen->jam_masuk && $absen->jam_keluar) {
                $totalJam += (strtotime($absen->jam_keluar) - strtotime($absen->jam_masuk)) / 3600;
            }
        }

        return view('dashboard.absensi-history', compact('absensis','bulan','tahun','totalJam'));
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
