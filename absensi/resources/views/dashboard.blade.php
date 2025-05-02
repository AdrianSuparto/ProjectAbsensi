@extends('layout.main')
@section('title', 'Dashboard')

@section('content')

<div class="container">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title">Daftar Absensi</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard.index') }}" class="mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <label for="kelas">Filter Kelas:</label>
                                <select name="kelas" id="kelas" class="form-select" onchange="this.form.submit()">
                                    <option value="">-- Semua Kelas --</option>
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}" {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
                                            {{ $kelas->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="display table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jam Masuk</th>
                                    <th>Status Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Status Pulang</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($absensis as $absensi)
                                    <tr>
                                        <td>{{ $absensi->tanggal->format('d-m-Y') }}</td>
                                        <td>{{ $absensi->siswa->nama }}</td>
                                        <td>{{ $absensi->siswa->kelasSiswa->nama_kelas ?? '-' }}</td>
                                        <td>{{ $absensi->jam_masuk ? $absensi->jam_masuk->format('H:i:s') : '-' }}</td>
                                        <td>{{ $absensi->status_masuk ?? '-' }}</td>
                                        <td>{{ $absensi->jam_pulang ? $absensi->jam_pulang->format('H:i:s') : '-' }}</td>
                                        <td>{{ $absensi->status_pulang ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('absensi.edit', $absensi->id) }}"
                                                    class="btn btn-sm btn-warning text-white">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('absensi.destroy', $absensi->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger text-white show_confirm">
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Belum ada data absensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection
