<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Peminjaman Mobil</title>
    <!-- Bootstrap CSS dari CDN (tidak perlu npm) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome dari CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @yield('styles')
</head>

<body>
    @auth
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Sistem Peminjaman Mobil</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        @if (Auth::user()->lvl_users == 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users.index') }}">Manajemen User</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.mobil.index') }}">Manajemen Mobil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.report') }}">Laporan</a>
                            </li>
                        @elseif(Auth::user()->lvl_users == 'kepala_bengkel')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kepalabengkel.pengajuan') }}">Antrian Pengajuan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kepalabengkel.status') }}">Status Peminjaman</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kepalabengkel.grafik') }}">Grafik Kendaraan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kepalabengkel.report') }}">Laporan</a>
                            </li>
                        @elseif(Auth::user()->lvl_users == 'pdi')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('pdi.antrian') }}">Antrian Cek</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('pdi.riwayat') }}">Riwayat Pemeriksaan</a>
                            </li>
                        @elseif(Auth::user()->lvl_users == 'satpam')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('satpam.list') }}">Daftar Transaksi</a>
                            </li>
                        @endif
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->username }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endauth

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS dari CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery dari CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Chart.js dari CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
</body>

</html>
