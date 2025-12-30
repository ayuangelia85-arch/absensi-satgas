@extends('layouts.app')

@section('content')
<style>
    body {
        background: #f6f8fb;
    }

    .profile-card {
        max-width: 480px;
        border-radius: 24px;
        background: #ffffff;
        border: none;
        overflow: hidden;
    }

    /* HEADER */
    .profile-header {
        background: linear-gradient(135deg, #ff8fb7, #ff5fa2);
        padding: 45px 20px 70px;
        text-align: center;
        color: #fff;
        position: relative;
    }

    .profile-avatar {
        position: absolute;
        left: 50%;
        bottom: -55px;
        transform: translateX(-50%);
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #fff;
        background: #fff;
        box-shadow: 0 10px 25px rgba(0,0,0,.15);
    }

    .profile-name {
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 4px;
    }

    .profile-role {
        font-size: .85rem;
        opacity: .9;
    }

    /* CONTENT */
    .profile-content {
        padding: 90px 28px 30px;
    }

    .info-item {
        background: #f9fafc;
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 14px;
    }

    .info-label {
        font-size: .75rem;
        color: #888;
        margin-bottom: 2px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .info-value {
        font-weight: 600;
        color: #333;
    }

    /* BUTTON */
    .btn-pink {
        background: linear-gradient(135deg, #ff7db8, #ff4fa1);
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 14px;
        padding: 12px;
    }

    .btn-pink:hover {
        opacity: .9;
        color: #fff;
    }

    .btn-grey {
        background: #eef0f4;
        color: #555;
        font-weight: 600;
        border-radius: 14px;
        padding: 12px;
        border: none;
    }

    .btn-grey:hover {
        background: #e0e3ea;
        color: #333;
    }
</style>

<div class="container d-flex justify-content-center mt-4 mb-5">

    <div class="card profile-card shadow-sm">

        {{-- HEADER --}}
        <div class="profile-header">
            <div class="profile-name">
                {{ $user->name }}
            </div>
            <div class="profile-role">
                {{ ucfirst($user->role) }}
            </div>

            <img
                src="{{ $user->photo ? asset('profile/' . $user->photo) : asset('profile/default.png') }}"
                class="profile-avatar"
                alt="Foto Profil"
            >
        </div>

        {{-- CONTENT --}}
        <div class="profile-content">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="info-item">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value">{{ $user->name }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">NIM / NIP</div>
                <div class="info-value">{{ $user->nim_nip }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $user->email }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Status</div>
                <div class="info-value">{{ ucfirst($user->status) }}</div>
            </div>

            <a
                href="{{ route('profil.edit') }}"
                class="btn btn-pink w-100 mt-4"
            >
                Edit Profil
            </a>

            <a
                href="{{ auth()->user()->role == 'admin'
                        ? route('admin.dashboard')
                        : route('user.dashboard') }}"
                class="btn btn-grey w-100 mt-3"
            >
                Kembali ke Dashboard
            </a>

        </div>
    </div>

</div>
@endsection
