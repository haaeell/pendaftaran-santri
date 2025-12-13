<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>PSB Santri | @yield('title')</title>

    <!-- Bootstrap 4 + Icons -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

    <!-- STISLA TEMPLATE -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">

    <link rel="icon" href="{{ asset('babamarket.png') }}" type="image/png">

    <!-- Datatable + Summernote + Select2 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>
    <div id="app">
        <div class="main-wrapper">

            <div class="navbar-bg"></div>

            <!-- TOP NAV -->
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto"></form>

                <ul class="navbar-nav navbar-right">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle nav-link-lg" data-toggle="dropdown">
                            <div class="d-sm-none d-lg-inline-block">
                                {{ Auth::user()->name }}
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @if (Auth::user()->isSantri())
                                <a class="dropdown-item" href="{{ route('santri.profile.index') }}">
                                    <i class="fas fa-user me-1"></i> Profil Saya
                                </a>
                            @endif
                            <hr>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>

                        </div>
                    </li>
                </ul>
            </nav>

            <!-- SIDEBAR -->
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">

                    <div class="sidebar-brand">
                        <a href="#">PSB SANTRI</a>
                    </div>

                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="#">PSB</a>
                    </div>

                    <ul class="sidebar-menu">

                        {{-- ADMIN MENU --}}
                        @if (Auth::user()->role == 'admin')
                            <li class="menu-header">Admin Dashboard</li>
                            <li class="{{ request()->is('home') ? 'active' : '' }}">
                                <a href="{{ route('home') }}" class="nav-link">
                                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                                </a>
                            </li>

                            <li class="menu-header">Manajemen Seleksi</li>

                            <li class="{{ request()->is('admin/verifikasi-pembayaran*') ? 'active' : '' }}">
                                <a href="{{ route('admin.payment.index') }}" class="nav-link">
                                    <i class="fas fa-wallet"></i> <span>Verifikasi Pembayaran</span>
                                </a>
                            </li>

                            <li class="{{ request()->is('admin/jadwal-test*') ? 'active' : '' }}">
                                <a href="{{ route('admin.jadwal.index') }}" class="nav-link">
                                    <i class="fas fa-calendar-check"></i> <span>Jadwal Tes</span>
                                </a>
                            </li>

                            <li class="{{ request()->is('admin/pendaftar*') ? 'active' : '' }}">
                                <a href="{{ route('admin.pendaftar.index') }}" class="nav-link">
                                    <i class="bi bi-people"></i> <span>Data Pendaftar</span>
                                </a>
                            </li>


                            <li class="{{ request()->is('admin/hasil*') ? 'active' : '' }}">
                                <a href="{{ route('admin.hasil.index') }}" class="nav-link">
                                    <i class="fas fa-poll"></i> <span>Hasil Seleksi</span>
                                </a>
                            </li>

                            <li class="{{ request()->is('admin/pengumuman*') ? 'active' : '' }}">
                                <a href="{{ route('admin.pengumuman.index') }}" class="nav-link">
                                    <i class="fas fa-bullhorn"></i> <span>Pengumuman</span>
                                </a>
                            </li>

                            <li class="menu-header">Master Data</li>

                            <li class="{{ request()->is('admin/master-soal*') ? 'active' : '' }}">
                                <a href="{{ route('admin.master-soal.index') }}" class="nav-link">
                                    <i class="fas fa-question-circle"></i>
                                    <span>Master Soal</span>
                                </a>
                            </li>

                            <li class="{{ request()->is('admin/tahun-akademik*') ? 'active' : '' }}">
                                <a href="{{ route('admin.tahun.index') }}" class="nav-link">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Tahun Akademik</span>
                                </a>
                            </li>

                            <li class="menu-header">Setting Administrasi</li>

                            <li class="{{ request()->is('admin/pengaturan-pembayaran*') ? 'active' : '' }}">
                                <a href="{{ route('admin.pengaturan-pembayaran.index') }}" class="nav-link">
                                    <i class="fas fa-cogs"></i>
                                    <span>Pengaturan Pembayaran</span>
                                </a>
                            </li>
                        @endif

                        {{-- SANTRI MENU --}}
                        @if (Auth::user()->role == 'santri')
                            <li class="menu-header"> Dashboard</li>
                            <li class="{{ request()->is('home') ? 'active' : '' }}">
                                <a href="{{ route('home') }}" class="nav-link">
                                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                                </a>
                            </li>

                            <hr>

                            @php
                                $tahunAktif = \App\Models\TahunAkademik::where('aktif', 1)->first();

                                $pengumumanSudahAda = \App\Models\PengumumanHasil::where(
                                    'tahun_akademik_id',
                                    $tahunAktif?->id,
                                )
                                    ->where('status', 'sudah')
                                    ->exists();

                                $dataDiriAktif =
                                    Auth::user()->dataDiri &&
                                    Auth::user()->dataDiri->tahun_akademik_id === $tahunAktif?->id;
                            @endphp

                            @if ($dataDiriAktif && !$pengumumanSudahAda)
                                <li class="{{ request()->is('santri/pendaftar*') ? 'active' : '' }}">
                                    <a href="{{ route('santri.pendaftar.index') }}" class="nav-link">
                                        <i class="fas fa-user-edit"></i> <span>Data Pendaftar</span>
                                    </a>
                                </li>

                                <li class="{{ request()->is('santri/jadwal*') ? 'active' : '' }}">
                                    <a href="{{ route('santri.jadwal.index') }}" class="nav-link">
                                        <i class="fas fa-calendar-check"></i> <span>Jadwal Seleksi</span>
                                    </a>
                                </li>
                                 <li class="{{ request()->is('santri/status*') ? 'active' : '' }}">
                                    <a href="{{ route('santri.status.index') }}" class="nav-link">
                                        <i class="fas fa-info-circle"></i> <span>Status Seleksi</span>
                                    </a>
                                </li>
                            @endif


                            @if ($dataDiriAktif && $pengumumanSudahAda)
                                <li class="{{ request()->is('santri/status*') ? 'active' : '' }}">
                                    <a href="{{ route('santri.status.index') }}" class="nav-link">
                                        <i class="fas fa-info-circle"></i> <span>Status Seleksi</span>
                                    </a>
                                </li>
                            @endif

                        @endif

                    </ul>

                </aside>
            </div>

            <!-- MAIN CONTENT -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>@yield('judul')</h1>
                    </div>
                </section>

                @yield('content')
            </div>

        </div>
    </div>

    <script>
        function previewImage() {
            const image = document.querySelector('#gambar');
            const imgPreview = document.querySelector('.img-preview');

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{ asset('assets') }}/js/stisla.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>

    @yield('scripts')

    <script src="{{ asset('assets') }}/js/scripts.js"></script>
    <script src="{{ asset('assets') }}/js/custom.js"></script>

    <script src="{{ asset('assets') }}/js/page/index-0.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($errors->any())
        <script>
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += "{{ $error }}\n";
            @endforeach

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: errorMessages,
            });
        </script>
    @endif

    @if (session('success') || session('error'))
        <script>
            $(document).ready(function() {
                var successMessage = "{{ session('success') }}";
                var errorMessage = "{{ session('error') }}";

                if (successMessage) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: successMessage,
                    });
                }

                if (errorMessage) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                    });
                }
            });
        </script>
    @endif

</body>

</html>
