@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="{{ route('pdi.antrian') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-clipboard-list"></i> Antrian Pemeriksaan
                    </a>
                    <a href="{{ route('pdi.riwayat') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-history"></i> Riwayat Pemeriksaan
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-block w-100">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Antrian Pemeriksaan Kendaraan</h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (count($antrianCek) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Peminjam</th>
                                            <th>Kendaraan</th>
                                            <th>Tanggal Pinjam</th>
                                            <th>Tujuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($antrianCek as $pinjam)
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
                                                    <a href="{{ route('pdi.cek', $pinjam->id_peminjaman) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-clipboard-check"></i> Cek Kendaraan
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Tidak ada kendaraan yang perlu diperiksa.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
