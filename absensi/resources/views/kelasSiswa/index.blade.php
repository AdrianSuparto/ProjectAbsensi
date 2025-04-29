@extends('layout.main')
@section('title', 'Kelas')
@section('content')

    <div class="container">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Database Kelas</h4>
                        <a href="{{ route('kelasSiswa.create') }}" class="btn btn-primary btn-round ms-auto">
                            <i class="fa fa-plus"></i>
                            Tambah Kelas
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="add-row" class="display table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Nama Kelas</th>
                                    <th style="width: 50%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kelasSiswa as $kelas)
                                    <tr>
                                        <td>{{ $kelas->nama }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('kelasSiswa.edit', $kelas->id) }}"
                                                    class="btn btn-sm btn-warning text-white rounded px-3">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>

                                                {{-- Tombol Delete --}}
                                                <form action="{{ route('kelasSiswa.destroy', $kelas->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger text-white rounded px-3 show_confirm"
                                                        data-toggle="tooltip" title="Hapus"
                                                        data-nama="{{ $kelas->nama }}">
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @if ($kelasSiswa->isEmpty())
                                    <tr>
                                        <td colspan="2" class="text-center">Belum ada data kelas.</td>
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
