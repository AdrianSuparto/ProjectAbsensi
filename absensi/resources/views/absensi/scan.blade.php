@extends('layout.main')

@section('content')
<style>
    .scan-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 70vh;
        text-align: center;
    }
    #uidInput {
        font-size: 2rem;
        text-align: center;
        letter-spacing: 2px;
        width: 300px;
    }
    #message {
        font-size: 1.5rem;
        margin-top: 30px;
        transition: all 0.3s ease;
    }
</style>

<div class="scan-container">
    <h2>Tempelkan Kartu Anda</h2>
    <form id="scanForm" autocomplete="off">
        <input type="text" id="uidInput" name="uid" maxlength="10" autofocus class="form-control">
    </form>
    <div id="message" class="alert d-none" style="white-space: pre-line;"></div>
</div>

<script>
    const input = document.getElementById('uidInput');
    const message = document.getElementById('message');

    const showMessage = (text, type = 'success') => {
        message.className = `alert alert-${type}`;
        message.textContent = text;
        message.classList.remove('d-none');

        // Simpan pesan ke localStorage agar tetap tampil
        localStorage.setItem('lastScanMessage', JSON.stringify({
            text: text,
            type: type
        }));
    };

    const resetInput = () => {
        input.value = '';
        input.focus();
    };

    const playSound = (success = true) => {
        const audio = new Audio(success ? '/sounds/success.mp3' : '/sounds/fail.mp3');
        audio.play();
    };

    // Menampilkan pesan dari localStorage saat pertama kali buka halaman
    window.addEventListener('DOMContentLoaded', () => {
        const lastMessage = localStorage.getItem('lastScanMessage');
        if (lastMessage) {
            const parsed = JSON.parse(lastMessage);
            showMessage(parsed.text, parsed.type);
        }
        input.focus();
    });

    input.addEventListener('input', () => {
        if (input.value.length === 10) {
            fetch("{{ route('absensi.scan') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ uid: input.value })
            })
            .then(async res => {
                const data = await res.json();
                if (res.ok) {
                    playSound(true);
                    showMessage(data.message, 'success');
                } else {
                    playSound(false);
                    showMessage(data.message || 'Kartu tidak dikenali.', 'danger');
                }
                resetInput();
            })
            .catch(() => {
                playSound(false);
                showMessage('Terjadi kesalahan saat koneksi.', 'danger');
                resetInput();
            });
        }
    });

    // Jaga agar input tetap fokus
    setInterval(() => {
        if (document.activeElement !== input) {
            input.focus();
        }
    }, 1000);
</script>

@endsection
