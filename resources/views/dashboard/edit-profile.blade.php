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

    .label-pink {
        font-weight: 600;
        color: #d94c93;
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
    }

    .btn-back {
        background-color: #f0f0f0;
        font-weight: 600;
        padding: 10px;
        border-radius: 12px;
        border: none;
    }

    .profile-photo {
        width: 110px;
        height: 110px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #ff7db8;
    }
</style>

<div class="container d-flex justify-content-center mt-4">

    <div class="card edit-card shadow">

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- ALERT ERROR --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('profil.update') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            {{-- NAMA --}}
            <div class="mb-3">
                <label class="label-pink">
                    Nama
                </label>
                <input
                    type="text"
                    name="name"
                    class="form-control rounded-3"
                    value="{{ auth()->user()->name }}"
                    required
                >
            </div>

            {{-- NIM / NIP --}}
            <div class="mb-3">
                <label class="label-pink">
                    NIM / NIP
                </label>
                <input
                    type="text"
                    name="nim_nip"
                    class="form-control rounded-3"
                    value="{{ auth()->user()->nim_nip }}"
                    required
                >
            </div>

            {{-- EMAIL --}}
            <div class="mb-3">
                <label class="label-pink">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    class="form-control rounded-3"
                    value="{{ auth()->user()->email }}"
                    required
                >
            </div>

            {{-- FOTO --}}
            <div class="mb-3">
                <label class="label-pink">
                    Foto Profil
                </label>
                <input
                    type="file"
                    name="photo"
                    class="form-control rounded-3"
                    accept="image/png,image/jpg,image/jpeg"
                >
                <small class="text-muted">
                    Maksimal ukuran foto <strong>1 MB</strong>
                    (JPG, JPEG, PNG)
                </small>
            </div>

            {{-- PASSWORD --}}
            <div class="mb-3">
                <label class="label-pink">
                    Password Baru (opsional)
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control rounded-3"
                    maxlength="10"
                    pattern="^(?=.*[A-Z])(?=.*[\W_]).{1,10}$"
                    title="Password maksimal 10 karakter, mengandung minimal 1 huruf besar dan 1 karakter unik"
                >

                <small class="text-muted d-block mt-1">
                    🔒 Maksimal <strong>10 karakter</strong>,
                    harus mengandung <strong>1 huruf besar</strong>
                    dan <strong>1 karakter unik</strong>
                    (contoh: ! @ # $ %).
                </small>

                <small class="text-muted">
                    Kosongkan jika tidak ingin mengganti password.
                </small>
            </div>

            {{-- BUTTON --}}
            <button type="submit" class="btn btn-pink w-100 mt-3">
                Simpan Perubahan
            </button>

            <a href="{{ route('profil') }}" class="btn btn-back w-100 mt-2">
                Kembali
            </a>

        </form>
    </div>

</div>
@endsection
