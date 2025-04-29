@extends('layout.main')
@section('title', 'Edit Siswa')
@section('content')

    <div class="container">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Data Siswa</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="no_kartu">No Kartu</label>
                            <input type="text" class="form-control" id="no_kartu" name="no_kartu" required
                                value="{{ old('no_kartu', $siswa->no_kartu) }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="nis">NIS</label>
                            <input type="text" class="form-control" id="nis" name="nis" required
                                value="{{ old('nis', $siswa->nis) }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="nama">Nama Siswa</label>
                            <input type="text" class="form-control" id="nama" name="nama" required
                                value="{{ old('nama', $siswa->nama) }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="kelas_siswa_id">Kelas</label>
                            <select class="form-control" id="kelas_siswa_id" name="kelas_siswa_id" required>
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelasSiswa as $kelas)
                                    <option value="{{ $kelas->id }}"
                                        {{ old('kelas_siswa_id', $siswa->kelas_siswa_id) == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nama_ortu">Nama Orang Tua</label>
                            <input type="text" class="form-control" id="nama_ortu" name="nama_ortu" required
                                value="{{ old('nama_ortu', $siswa->nama_ortu) }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="nomor_ortu">Nomor Orang Tua</label>
                            <input type="text" class="form-control" id="nomor_ortu" name="nomor_ortu" required
                                value="{{ old('nomor_ortu', $siswa->nomor_ortu) }}">
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('siswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
                            <button type="submit" class="btn btn-primary">Update </button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
