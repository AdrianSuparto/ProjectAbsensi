<h2>Daftar Kelas</h2>
<a href="{{ route('kelasSiswa.create') }}">+ Tambah Kelas</a>
<ul>
    @foreach ($kelasSiswa as $k)
        <li>{{ $k->nama }}
            <a href="{{ route('kelasSiswa.edit', $k) }}">Edit</a>
            <form action="{{ route('kelasSiswa.destroy', $k) }}" method="POST" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </li>
    @endforeach
</ul>
