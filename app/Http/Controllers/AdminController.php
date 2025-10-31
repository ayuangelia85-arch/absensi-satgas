<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $absensi = Absensi::with('user')->latest()->paginate(10);
        return view('dashboard.admin', compact('absensi'));
    }

    public function absensi()
    {
        $absensi = Absensi::with('user')->get();
        return view('absensi.index', compact('absensi'));
    }
}
