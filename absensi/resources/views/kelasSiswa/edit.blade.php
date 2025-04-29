<form action="{{ route('kelasSiswa.update', $kelasSiswa->id) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="nama" value="{{ $kelasSiswa->nama }}">
    <button type="submit">Update</button>
</form>
