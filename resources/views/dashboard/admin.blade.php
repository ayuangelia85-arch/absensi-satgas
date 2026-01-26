@extends('layouts.app')

@section('content')
<div class="container my-5">

    {{-- JUDUL --}}
    <div class="mb-4">
        <h3 class="fw-bold">Dashboard Admin</h3>
        <small class="text-muted">Kelola data absensi pegawai & mahasiswa</small>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- FILTER TANGGAL --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Filter Absensi</h6>

            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <button class="btn btn-primary w-100 rounded-pill">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- INPUT ABSENSI MANUAL --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Tambah Absensi Manual</h6>

            <form action="{{ route('admin.absensi.storeManual') }}" method="POST">
                @csrf
                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-select" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} - {{ $user->nim_nip }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Jam Keluar</label>
                        <input type="time" name="jam_keluar" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kegiatan</label>
                        <textarea name="kegiatan" class="form-control" rows="2"
                                  placeholder="Contoh: Pengaduan, Sosialisasi.."></textarea>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Keterangan</label>
                        <select name="keterangan" class="form-select">
                            <option value="">- Pilih -</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpa">Alpa</option>
                        </select>
                    </div>

                </div>

                <div class="mt-4 text-end">
                    <button class="btn btn-primary px-4 rounded-pill">
                        Simpan Absensi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- HEADER DATA + PER PAGE --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <strong>Data Absensi</strong>

        <form method="GET" action="{{ route('admin.dashboard') }}"
              class="d-flex align-items-center gap-2">

            {{-- JAGA FILTER --}}
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">

            <label class="mb-0">Tampilkan</label>

            <select name="per_page"
                    class="form-select form-select-sm w-auto"
                    onchange="this.form.submit()">
                <option value="25" {{ request('per_page',25) == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>

            <span>data</span>
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Data Absensi Pegawai / Mahasiswa</h6>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Total</th>
                            <th>Kegiatan</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @forelse ($absensi as $i => $item)

                        @php
                            $totalJam = '-';
                            if ($item->jam_masuk && $item->jam_keluar) {
                                $selisih = strtotime($item->jam_keluar) - strtotime($item->jam_masuk);
                                if ($selisih > 0) {
                                    $totalJam = round($selisih / 3600, 2) . ' jam';
                                }
                            }
                        @endphp

                        <tr>
                            <td>{{ $absensi->firstItem() + $i }}</td>
                            <td class="text-start">{{ $item->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
                            <td>
                                @if($item->jam_masuk)
                                    {{ \Carbon\Carbon::parse($item->jam_masuk)->format('H:i:s') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($item->keterangan === 'hadir')
                                    @if ($item->jam_keluar)
                                        @php
                                            try {
                                                $jamKeluar = \Carbon\Carbon::parse($item->jam_keluar)->format('H:i:s');
                                            } catch (\Exception $e) {
                                                $jamKeluar = '-';
                                            }
                                        @endphp

                                        <div>{{ $jamKeluar }}</div>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Checkout</span>

                                        <form action="{{ route('admin.absensi.updateAbsensi', $item->id) }}"
                                            method="POST"
                                            class="d-flex gap-1 justify-content-center mt-1">
                                            @csrf
                                            @method('PUT')

                                            <input type="time"
                                                name="jam_keluar"
                                                class="form-control form-control-sm"
                                                required>

                                            <button class="btn btn-sm btn-warning">Simpan</button>
                                        </form>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $totalJam }}</td>
                            <td class="text-start text-wrap" style="max-width:200px">
                                {{ $item->kegiatan ?? '-' }}
                            </td>
                            <td>{{ ucfirst($item->keterangan ?? 'hadir') }}</td>

                            <td>
                                @if ($item->latitude && $item->longitude)
                                    <a href="{{ route('absensi.location', $item->id) }}"
                                    class="btn btn-sm btn-success">
                                        Lokasi
                                    </a>
                                @elseif (in_array($item->keterangan, ['izin','sakit','alpa']))
                                    -
                                @else
                                    <span class="badge bg-secondary">Input Admin</span>
                                @endif
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="11" class="text-muted text-center">
                                Belum ada data absensi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-3 d-flex justify-content-end">
                {{ $absensi->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
