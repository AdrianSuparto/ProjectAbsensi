<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="{{ asset('https://fonts.gstatic.com') }}">
    <link rel="shortcut icon" href="{{ asset('img/icons/icon-48x48.png') }}" />

    <link rel="canonical" href="{{ asset('https://demo-basic.adminkit.io/') }}" />

    <title>@yield('title')</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap') }}"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>


</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="{{ asset('/') }}">
                    <span class="align-middle">Attendix</span>
                </a>

                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Menu Utama
                    </li>

                    <li class="sidebar-item {{ Request::is('dashboard') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('dashboard.index') }}">
                            <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                        </a>
                    </li>


                    <li class="sidebar-item {{ Request::is('siswa') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ asset('siswa') }}">
                            <i class="align-middle" data-feather="users"></i> <span class="align-middle">Database
                                Siswa</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ Request::is('kelasSiswa') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ asset('kelasSiswa') }}">
                            <i class="align-middle" data-feather="book"></i> <span class="align-middle">Database
                                Kelas</span>
                        </a>
                    </li>

                    {{-- Menu Scan Absensi --}}
                    <li class="sidebar-item {{ Request::routeIs('absensi.scan.page') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('absensi.scan.page') }}">
                            <i class="align-middle" data-feather="check-square"></i>
                            <span class="align-middle">Scan Absensi</span>
                        </a>
                    </li>

                    {{-- Menu Database Absensi --}}
                    <li class="sidebar-item {{ Request::routeIs('absensi.index') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('absensi.index') }}">
                            <i class="align-middle" data-feather="database"></i>
                            <span class="align-middle">Database Absensi</span>
                        </a>
                    </li>


                    <li class="sidebar-item {{ Request::is('izinSakit') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ asset('izinSakit') }}">
                            <i class="align-middle" data-feather="database"></i> <span class="align-middle">Database
                                Izin</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ Request::is('libur') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ asset('libur') }}">
                            <i class="align-middle" data-feather="moon"></i> <span class="align-middle">Database
                                Libur</span>
                        </a>
                    </li>
                </ul>

        </nav>

        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

                <center> Aplikasi Absensi Berbasis RFID</center>
            </nav>

            <main class="content">
                <div class="container-fluid p-0">
                    @yield('content')
                </div>
            </main>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a class="text-muted" href="{{ asset('') }}"
                                    target="_blank"><strong>Attendix</strong></a> &copy; 2025
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a class="text-muted" href="{{ asset('') }}" target="_blank">Support</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Sweet Alert -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(document).on('click', '.show_confirm', function(event) {
            event.preventDefault();
            var form = $(this).closest("form");
            var nama = $(this).data("nama");

            Swal.fire({
                title: `Hapus Data ${nama}?`,
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#ffc107',
                confirmButtonText: '🔥 YA, HAPUS!',
                cancelButtonText: '⛔ BATALKAN',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>


    <script type="text/javascript">
        @if (session('success'))
            Swal.fire({
                title: 'BERHASIL!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: '👍 OKE',
                confirmButtonColor: '#28a745'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: '❌ GAGAL!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: '⚠️ MENGERTI',
                confirmButtonColor: '#dc3545'
            });
        @endif
    </script>


</body>

</html>
