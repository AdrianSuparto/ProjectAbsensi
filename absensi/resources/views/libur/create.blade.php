@extends('layout.main')
@section('title', 'Tambah Libur')
@section('content')

    <div class="container">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Hari Libur</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('libur.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required
                                value="{{ old('tanggal') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" required
                                value="{{ old('keterangan') }}">
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('libur.index') }}" class="btn btn-secondary me-2">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
