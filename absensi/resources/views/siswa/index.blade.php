@extends('layout.main')
@section('title', 'Data Siswa')
@section('content')

    <div class="container">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Database Siswa</h4>
                        <a href="{{ route('siswa.create') }}" class="btn btn-primary btn-round ms-auto">
                            <i class="fa fa-plus"></i>
                            Tambah Siswa
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('siswa.index') }}" class="mb-3">
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
                        <table id="add-row" class="display table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No Kartu</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Nama Orang Tua</th>
                                    <th>Nomor Orang Tua</th>
                                    <th style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswas as $siswa)
                                    <tr>
                                        <td>{{ $siswa->no_kartu }}</td>
                                        <td>{{ $siswa->nis }}</td>
                                        <td>{{ $siswa->nama }}</td>
                                        <td>{{ $siswa->kelasSiswa->nama ?? 'Tidak ada kelas' }}</td>
                                        <td>{{ $siswa->nama_ortu }}</td>
                                        <td>{{ $siswa->nomor_ortu }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('siswa.edit', $siswa->id) }}"
                                                    class="btn btn-sm btn-warning text-white rounded px-3">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>

                                                {{-- Tombol Delete --}}
                                                <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger text-white rounded px-3 show_confirm"
                                                        data-toggle="tooltip" title="Hapus"
                                                        data-nama="{{ $siswa->nama }}">
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @if ($siswas->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data siswa.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
