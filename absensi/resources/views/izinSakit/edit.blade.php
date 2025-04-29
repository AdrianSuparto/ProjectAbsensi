@extends('layout.main')
@section('title', 'Edit Izin/Sakit')
@section('content')

    <div class="container">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Izin/Sakit</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('izinSakit.update', $izinSakit->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="siswa_id">Siswa</label>
                            <select name="siswa_id" id="siswa_id" class="form-control" required>
                                @foreach ($siswas as $siswa)
                                    <option value="{{ $siswa->id }}"
                                        {{ $siswa->id == $izinSakit->siswa_id ? 'selected' : '' }}>
                                        {{ $siswa->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required
                                value="{{ old('tanggal', $izinSakit->tanggal->format('Y-m-d')) }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="jenis">Jenis</label>
                            <select name="jenis" id="jenis" class="form-control" required>
                                <option value="Izin" {{ $izinSakit->jenis == 'Izin' ? 'selected' : '' }}>Izin</option>
                                <option value="Sakit" {{ $izinSakit->jenis == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan"
                                value="{{ old('keterangan', $izinSakit->keterangan) }}">
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('izinSakit.index') }}" class="btn btn-secondary me-2">Kembali</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
