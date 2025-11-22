<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard admin: tampilkan semua data absensi (paginate biar ringan)
    public function index()
    {
        $absensi = Absensi::with('user')->latest()->paginate(10);
        return view('dashboard.admin', compact('absensi'));
    }
    

    // Halaman absensi (kalau mau ditampilkan terpisah dari dashboard)
    public function absensi()
    {
        $absensi = Absensi::with('user')->get();
        return view('absensi.index', compact('absensi'));
    }

    // Tambah user baru

        public function create()
    {
        return view('dashboard.add_user');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nim_nip' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'status' => 'required',
            'role' => 'required',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'nim_nip' => $request->nim_nip,
            'email' => $request->email,
            'status' => $request->status,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User baru berhasil ditambahkan!');

    }

        public function indexUser()
    {
        $users = User::all();
        return view('dashboard.user_list', compact('users'));
    }

    public function edit($id)
{
    $user = User::findOrFail($id);
    return view('dashboard.edit_user', compact('user'));
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim_nip' => 'required|string|max:50',
            'email' => 'required|email',
            'status' => 'required|string',
            'role' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all());

        return redirect()->route('admin.user.index')->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus.');
    }


    // 🔹 Update keterangan absensi (izin, sakit, alpa, dll)
    public function updateKeterangan(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'nullable|string|max:50',
        ]);

        $absensi = Absensi::findOrFail($id);
        $absensi->update([
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Keterangan absensi berhasil diperbarui!');
    }

    // 🔹 Hapus data absensi (opsional)
    public function delete($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return redirect()->back()->with('success', 'Data absensi berhasil dihapus!');
    }
}
