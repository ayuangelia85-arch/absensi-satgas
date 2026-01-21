<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\UserCreatedMail;
use Carbon\Carbon;


class AdminController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
            'per_page'   => 'nullable|integer|in:25,50,100'
        ]);

        $perPage = $request->get('per_page', 25);
        
        $query = Absensi::with('user:id,name');

        $query->when($request->start_date, function ($q) use ($request) {
            return $q->whereDate('tanggal', '>=', $request->start_date);
        });

        $query->when($request->end_date, function ($q) use ($request) {
            return $q->whereDate('tanggal', '<=', $request->end_date);
        });

        $absensi = $query->latest('tanggal')
                        ->paginate($perPage)
                        ->withQueryString();

        $users = User::select('id', 'name')->orderBy('name')->get();

        return view('dashboard.admin', compact('absensi', 'users'));
    }

    public function absensi(Request $request)
    {
        $perPage = $request->get('per_page', 25);

        $absensi = Absensi::with('user')
                    ->orderBy('tanggal', 'desc')
                    ->paginate($perPage)
                    ->withQueryString();

        return view('absensi.index', compact('absensi'));
    }

   public function updateAbsensi(Request $request, $id)
    {
        $request->validate([
            'jam_masuk'  => 'nullable|date_format:h:i A',
            'jam_keluar' => 'nullable|date_format:h:i A',
        ]);

        $absensi = Absensi::findOrFail($id);

        if ($absensi->keterangan !== 'hadir') {
            return back()->with('error', 'Data izin/sakit/alpa tidak memiliki jam masuk dan keluar.');
        }

        $jamMasuk = $absensi->jam_masuk;
        if ($request->filled('jam_masuk')) {
            $jamMasuk = Carbon::createFromFormat('h:i A', $request->jam_masuk)
                                ->format('H:i');
        }

        $jamKeluar = $absensi->jam_keluar;
        if ($request->filled('jam_keluar')) {

            $jamKeluar = Carbon::createFromFormat('h:i A', $request->jam_keluar)
                                ->format('H:i');

            if ($jamKeluar > '16:00') {
                return back()->with('error', 'Jam keluar maksimal pukul 04:00 PM.');
            }

            if ($jamMasuk && $jamKeluar < $jamMasuk) {
                return back()->with('error', 'Jam keluar tidak boleh lebih awal dari jam masuk.');
            }
        }

        $absensi->jam_masuk  = $jamMasuk;
        $absensi->jam_keluar = $jamKeluar;

        if ($jamMasuk && $jamKeluar) {
            $awal  = Carbon::createFromFormat('H:i', $jamMasuk);
            $akhir = Carbon::createFromFormat('H:i', $jamKeluar);

            $menit = $awal->diffInMinutes($akhir);
            $jam   = floor($menit / 60);
            $sisa  = $menit % 60;

            $absensi->durasi_jam = "{$jam} Jam {$sisa} Menit";
        }

        $absensi->save();

        return back()->with('success', 'Data absensi berhasil diperbarui oleh admin.');
    }
    

     public function indexUser()

    {

        $users = User::all();

        return view('dashboard.user_list', compact('users'));

    }
  public function storeManual(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'tanggal'    => 'required|date',
            'jam_masuk'  => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'kegiatan'   => 'nullable|string|max:2000',
            'keterangan' => 'required|in:hadir,izin,sakit,alpa'
        ]);

        $exists = Absensi::where('user_id', $request->user_id)
                        ->whereDate('tanggal', $request->tanggal)
                        ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'User tersebut sudah memiliki data absensi pada tanggal ini.');
        }

        $jamMasuk = null;
        $jamKeluar = null;
        $durasi = null;

        if ($request->keterangan === 'hadir') {
            $jamMasuk = $request->jam_masuk;
            $jamKeluar = $request->jam_keluar;

            if ($jamMasuk && $jamKeluar) {
                $awal  = strtotime($jamMasuk);
                $akhir = strtotime($jamKeluar);
                $diff  = $akhir - $awal;
                $jam   = floor($diff / 3600);
                $menit = floor(($diff % 3600) / 60);
                $durasi = "$jam Jam $menit Menit";
            }
        }

        Absensi::create([
            'user_id'    => $request->user_id,
            'tanggal'    => $request->tanggal,
            'jam_masuk'  => $jamMasuk,
            'jam_keluar' => $jamKeluar,
            'durasi_jam' => $durasi,
            'kegiatan'   => $request->kegiatan,
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
            'name'    => 'required|string|max:50',
            'email'   => 'required|email',
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'password'=> ['nullable','string','min:7','max:10',
            Password::min(7)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'photo.max'   => 'Ukuran foto maksimal 1 MB.',
            'photo.image' => 'File yang diunggah harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->hasFile('photo')) {

            if ($user->photo && file_exists(public_path('profile/' . $user->photo))) {
                unlink(public_path('profile/' . $user->photo));
            }

            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profile'), $filename);

            $user->photo = $filename;
        }

        $user->save();

        return redirect()
            ->route('profil')
            ->with('success', 'Profil berhasil diperbarui!');
    }

        public function create()
    {
        return view('dashboard.add_user');
    }
   public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:50',
            'nim_nip' => 'required|string|max:20|unique:users,nim_nip',
            'email'   => 'required|email|unique:users,email',
            'status'  => 'required', 
            'role'    => 'required|in:admin,user',
            'password' => ['required','string','max:100',
            Password::min(7)->mixedCase()->numbers()->symbols(),
            ],
        ]);

        return \DB::transaction(function () use ($request) {
            $plainPassword = $request->password;

            $user = User::create([
                'name'    => $request->name,
                'nim_nip' => $request->nim_nip,
                'email'   => $request->email,
                'status'  => $request->status,
                'role'    => $request->role,
                'password' => Hash::make($plainPassword),
            ]);

            Mail::to($user->email)->send(
                new UserCreatedMail($user, $plainPassword)
            );

            return redirect()
                ->route('admin.user.index')
                ->with('success', 'User baru berhasil ditambahkan & kredensial terkirim ke email!');
        });
    }

        public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('dashboard.edit_user', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string|max:50',
            'nim_nip' => 'required|string|max:20|unique:users,nim_nip,' . $id,
            'email'   => 'required|email|unique:users,email,' . $id,
            'status'  => 'required',
            'role'    => 'required|in:admin,user',
            'password' => 'nullable|string|min:7|max:100', 
        ]);

        $user = User::findOrFail($id);

        $user->name    = $request->name;
        $user->nim_nip = $request->nim_nip;
        $user->email   = $request->email;
        $user->status  = $request->status;
        $user->role    = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus.');
    }
    
}
