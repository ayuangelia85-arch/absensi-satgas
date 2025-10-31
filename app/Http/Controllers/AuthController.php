<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 🔹 Tampilan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 🔹 Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nim_nip' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('user.dashboard');
            }
        }

        return back()->with('error', 'NIM/NIP atau password salah!');
    }

    // 🔹 Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
