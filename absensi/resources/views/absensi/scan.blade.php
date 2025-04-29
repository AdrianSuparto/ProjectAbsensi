@extends('layout.main')

@section('content')
    <div class="container">
        <h2>Scan Kartu Absensi</h2>
        <form id="scanForm">
            <input type="text" id="uidInput" name="uid" maxlength="10" autofocus class="form-control">
        </form>
        <div id="message" class="mt-3 alert"></div>
    </div>

    <script>
        const input = document.getElementById('uidInput');
        const form = document.getElementById('scanForm');
        const message = document.getElementById('message');

        input.focus();

        input.addEventListener('input', () => {
            if (input.value.length === 10) {
                fetch("{{ route('absensi.scan') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            uid: input.value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        message.className = 'alert alert-success';
                        message.innerText = data.message;
                        input.value = '';
                        input.focus();
                    })
                    .catch(err => {
                        message.className = 'alert alert-danger';
                        message.innerText = 'Gagal: Kartu tidak dikenal atau terjadi error.';
                        input.value = '';
                        input.focus();
                    });
            }
        });
    </script>
@endsection
