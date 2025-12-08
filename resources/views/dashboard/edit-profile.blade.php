@extends('layouts.app')

@section('content')

<style>
    .edit-card {
        max-width: 500px;
        border-radius: 20px;
        background: #ffffff;
        border: 1px solid #f3f3f3;
        padding: 25px 30px;
    }

    .edit-title {
        font-weight: 600;
        color: #ff4fa1;
    }

    .btn-pink {
        background-color: #ff7db8;
        border-color: #ff7db8;
        color: white;
        font-weight: 600;
        padding: 10px;
        border-radius: 12px;
    }

    .btn-pink:hover {
        background-color: #ff4fa1;
        border-color: #ff4fa1;
        color: white;
    }

    .btn-back {
        background-color: #f0f0f0;
        color: #555;
        font-weight: 600;
        padding: 10px;
        border-radius: 12px;
        border: none;
    }

    .btn-back:hover {
        background-color: #e4e4e4;
        color: #444;
    }

    .label-pink {
        font-weight: 600;
        color: #d94c93;
    }
</style>

<div class="container d-flex justify-content-center mt-4">

    <div class="card edit-card shadow">

        <h3 class="edit-title text-center mb-4">Edit Profil</h3>

        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="label-pink">Nama</label>
                <input type="text" name="name" class="form-control rounded-3" value="{{ $user->name }}" required>
            </div>

            <div class="mb-3">
                <label class="label-pink">NIM / NIP</label>
                <input type="text" name="nim_nip" class="form-control rounded-3" value="{{ $user->nim_nip }}" required>
            </div>

            <div class="mb-3">
                <label class="label-pink">Email</label>
                <input type="email" name="email" class="form-control rounded-3" value="{{ $user->email }}" required>
            </div>

            <div class="mb-3">
                <label class="label-pink">Foto Profil (opsional)</label>
                <input type="file" name="photo" class="form-control rounded-3">
                <small class="text-muted">Biarkan kosong jika tidak mengganti foto.</small>
            </div>

            <div class="mb-3">
                <label class="label-pink">Password Baru (opsional)</label>
                <input type="password" name="password" class="form-control rounded-3">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti password.</small>
            </div>

            <button type="submit" class="btn btn-pink w-100 mt-3">
                Simpan Perubahan
            </button>

            <!-- TOMBOL BACK -->
            <a href="{{ route('profil') }}" class="btn btn-back w-100 mt-2 text-center">
                Kembali ke Profil
            </a>

        </form>
    </div>

</div>

@endsection
