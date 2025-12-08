@extends('layouts.app')

@section('content')
<style>
    .profile-card {
        max-width: 480px;
        border-radius: 20px;
        background: #ffffff;
        border: 1px solid #f3f3f3;
    }

    .profile-header {
        background: linear-gradient(135deg, #ff9acb, #ff6fa7);
        padding: 40px 20px 60px 20px;
        border-radius: 20px 20px 0 0;
        color: white;
        text-align: center;
        position: relative;
    }

    .profile-avatar {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        bottom: -45px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .profile-table th {
        width: 35%;
        color: #555;
    }

    .btn-pink {
        background-color: #ff7db8;
        border-color: #ff7db8;
        color: white;
    }

    .btn-pink:hover {
        background-color: #ff4fa1;
        border-color: #ff4fa1;
        color: white;
    }

    .btn-grey {
        background-color: #e9e9e9;
        color: #555;
        font-weight: 600;
    }

    .btn-grey:hover {
        background-color: #d5d5d5;
        color: #333;
    }
</style>

<div class="container d-flex justify-content-center mt-4">

    <div class="card profile-card shadow">

        {{-- HEADER PINK --}}
        <div class="profile-header">
            <h4 class="mb-1">{{ $user->name }}</h4>

            <img src="{{ $user->photo ? asset('profile/' . $user->photo) : asset('profile/default.png') }}"
                 class="profile-avatar">
        </div>

        <div class="p-4 mt-5">


            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- DETAIL --}}
            <table class="table profile-table">
                <tr>
                    <th>Nama</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>NIM / NIP</th>
                    <td>{{ $user->nim_nip }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $user->status }}</td>
                </tr>
            </table>

            {{-- TOMBOL EDIT PROFIL --}}
            <a href="{{ route('profil.edit') }}" class="btn btn-pink w-100 py-2 mt-3">
                Edit Profil
            </a>

            {{-- TOMBOL KEMBALI KE DASHBOARD --}}
            <a href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"class="btn btn-grey w-100 py-2 mt-3">
                Kembali ke Dashboard
            </a>

        </div>
    </div>

</div>
@endsection
