<!DOCTYPE html>
<html>

<head>
    <title>Absensi Siswa</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <h2>Silakan Tempelkan Kartu</h2>
    <input type="text" id="uid_input" autofocus autocomplete="off" style="font-size:24px;">
    <div id="status" style="margin-top:10px; font-size:18px;"></div>

    <script>
        const input = document.getElementById('uid_input');
        const status = document.getElementById('status');

        input.addEventListener('input', function() {
            const uid = input.value.trim();

            if (uid.length === 10) {
                fetch('/absensi/scan', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            uid: uid
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        status.innerText = data.message;
                        input.value = "";
                        input.focus();
                    })
                    .catch(() => {
                        status.innerText = "Gagal memproses absensi.";
                        input.value = "";
                        input.focus();
                    });
            }
        });
    </script>
</body>

</html>
