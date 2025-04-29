@extends('layout.main')
@section('title', 'Data Izin/Sakit')
@section('content')

    <div class="container">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title">Daftar Izin/Sakit</h4>
                    <a href="{{ route('izinSakit.create') }}" class="btn btn-primary btn-round ms-auto">
                        <i class="fa fa-plus"></i> Tambah Izin/Sakit
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Siswa</th>
                                    <th>Jenis</th>
                                    <th>Keterangan</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($izinSakits as $izinSakit)
                                    <tr>
                                        <td>{{ $izinSakit->formatted_tanggal }}</td>
                                        <td>{{ $izinSakit->siswa->nama }}</td>
                                        <td>{{ $izinSakit->jenis }}</td>
                                        <td>{{ $izinSakit->keterangan }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('izinSakit.edit', $izinSakit->id) }}"
                                                    class="btn btn-sm btn-warning text-white">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('izinSakit.destroy', $izinSakit->id) }}"
                                                    method="POST" class="d-inline">
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
                                        <td colspan="5" class="text-center">Belum ada data izin/sakit.</td>
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
