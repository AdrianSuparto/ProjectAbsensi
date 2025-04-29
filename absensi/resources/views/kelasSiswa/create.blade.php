<h2>Tambah Kelas</h2>
<form method="POST" action="{{ route('kelasSiswa.store') }}">
    @csrf
    <input type="text" name="nama" placeholder="Nama Kelas" required>
    <button type="submit">Simpan</button>
</form>
