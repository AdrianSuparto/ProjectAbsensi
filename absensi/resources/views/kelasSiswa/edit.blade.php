@extends('layout.main')
@section('title', 'Edit Kelas')
@section('content')

    <div class="container">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Kelas</h4>
                    <p class="card-description">Perbarui data nama kelas di bawah ini.</p>

                    <form class="forms-sample" method="POST" action="{{ route('kelasSiswa.update', $kelasSiswa->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Nama Kelas --}}
                        <div class="form-group row">
                            <label for="nama" class="col-sm-3 col-form-label">Nama Kelas</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="nama" id="nama"
                                    placeholder="Nama Kelas" value="{{ old('nama', $kelasSiswa->nama) }}">
                                @error('nama')
                                    <label for="nama" class="text-danger">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary me-2">Simpan</button>
                            <a href="{{ route('kelasSiswa.index') }}" class="btn btn-dark">Batal</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
