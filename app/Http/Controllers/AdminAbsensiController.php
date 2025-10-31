<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;

class AdminAbsensiController extends Controller
{
    public function index()
    {
        // Ambil semua data absensi dengan relasi user
        $absensis = Absensi::with('user')->latest()->get();

        return view('absensi.index', compact('absensis'));
    }

    // Kalau kamu belum punya method lain (create/store/edit), biarin dulu kosong
    
    public function create()    
    {
        return view('absensi.create');
    }

}
