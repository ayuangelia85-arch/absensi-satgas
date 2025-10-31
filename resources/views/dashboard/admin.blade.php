@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Dashboard Admin</h2>
    <p>Selamat datang, <b>{{ Auth::user()->name }}</b></p>

    <hr>
    <h4>Daftar User</h4>
    <table class="table table-striped">
        <tr>
            <th>NIM/NIP</th>
            <th>Nama</th>
            <th>Role</th>
        </tr>
        @foreach($absensi as $item)
        <tr>
            <td>{{ $item->user->nim_nip }}</td>
            <td>{{ $item->user->name }}</td>
            <td>{{ $item->user->role }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
