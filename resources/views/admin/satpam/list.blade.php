@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Manajemen Kendaraan Keluar-Masuk</h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Peminjam</th>
                                        <th>Kendaraan</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tujuan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($transaksi) > 0)
                                        @foreach ($transaksi as $pinjam)
                                            <tr>
                                                <td>{{ $pinjam->id_peminjaman }}</td>
                                                <td>{{ $pinjam->user->username ?? 'User tidak ditemukan' }}</td>
                                                <td>
                                                    {{ $pinjam->mobil->nama ?? 'Kendaraan tidak ditemukan' }}
                                                    <br>
                                                    <small class="text-muted">{{ $pinjam->mobil->plat_nomor ?? '' }}</small>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>{{ $pinjam->tujuan }}</td>
                                                <td>
                                                    @if ($pinjam->status == 'approved')
                                                        <span class="badge bg-success">Disetujui - Siap Diambil</span>
                                                    @elseif($pinjam->status == 'in_progress')
                                                        <span class="badge bg-primary">Sedang Dipinjam</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $pinjam->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($pinjam->status == 'approved')
                                                        <form
                                                            action="{{ route('satpam.mobil-keluar', $pinjam->id_peminjaman) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-warning"
                                                                onclick="return confirm('Apakah Anda yakin kendaraan ini akan keluar?')">
                                                                <i class="fas fa-sign-out-alt"></i> Kendaraan Keluar
                                                            </button>
                                                        </form>
                                                    @elseif($pinjam->status == 'in_progress')
                                                        <form
                                                            action="{{ route('satpam.mobil-masuk', $pinjam->id_peminjaman) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success"
                                                                onclick="return confirm('Apakah Anda yakin kendaraan ini sudah kembali?')">
                                                                <i class="fas fa-sign-in-alt"></i> Kendaraan Kembali
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada transaksi peminjaman aktif</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body text-center">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
