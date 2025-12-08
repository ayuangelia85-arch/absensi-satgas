<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard admin: tampilkan semua data absensi (paginate biar ringan)
    public function index(Request $request)
    {
        $query = Absensi::with('user');

        if ($request->start_date) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $absensi = $query->orderBy('tanggal', 'desc')->get();
        $users = User::all(); // <-- WAJIB supaya dropdown di form tersedia

        return view('dashboard.admin', compact('absensi', 'users'));
    }



    

    // Halaman absensi (kalau mau ditampilkan terpisah dari dashboard)
    public function absensi()
    {
        $absensi = Absensi::with('user')->orderBy('tanggal', 'desc')->get();
        return view('absensi.index', compact('absensi'));
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

       public function indexUser()
    {
        $users = User::all();
        return view('dashboard.user_list', compact('users'));
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable',
            'jam_keluar' => 'nullable',
            'keterangan' => 'nullable|string'
        ]);

        Absensi::create([
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Absensi manual berhasil ditambahkan!');
    }

        public function profil()
    {
        $user = auth()->user();
        return view('dashboard.profile', compact('user'));
    }

    public function editProfil()
    {
        $user = auth()->user();
        return view('dashboard.edit-profile', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'nim_nip' => 'required|string|max:100',
            'email' => 'required|email',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:5'
        ]);

        // update basic
        $user->name = $request->name;
        $user->nim_nip = $request->nim_nip;
        $user->email = $request->email;

        // update password bila diisi
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        // upload foto
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profile'), $filename);

            // hapus foto lama kalau ada
            if ($user->photo && file_exists(public_path('profile/' . $user->photo))) {
                unlink(public_path('profile/' . $user->photo));
            }

            $user->photo = $filename;
        }

        $user->save();

        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui!');
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
    
}
