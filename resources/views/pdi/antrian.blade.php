@extends('layouts.app') {{-- Ganti dengan layout yang kamu gunakan --}}

@section('title', 'Antrian Pemeriksaan')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Antrian Pemeriksaan Kendaraan</h1>

        @if ($pengajuans->isEmpty())
            <div class="alert alert-info">Tidak ada kendaraan yang menunggu pemeriksaan.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Penyewa</th>
                            <th>Mobil</th>
                            <th>Plat Nomor</th>
                            <th>Tanggal Mulai Sewa</th>
                            <th>Tanggal Akhir Sewa</th>
                            <th>Status Pemeriksaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuans as $index => $pengajuan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pengajuan->nama_penyewa }}</td>
                                <td>{{ $pengajuan->mobil->merk ?? '-' }} {{ $pengajuan->mobil->model ?? '-' }}</td>
                                <td>{{ $pengajuan->mobil->plat ?? '-' }}</td>
                                <td>{{ $pengajuan->tanggal_mulai_sewa->format('d-m-Y') }}</td>
                                <td>{{ $pengajuan->tanggal_akhir_sewa->format('d-m-Y') }}</td>
                                <td>{{ $pengajuan->pemeriksaan->status }}</td>
                                <td>
                                    <a href="{{ route('pdi.pemeriksaan.show', $pengajuan->id_transaksi) }}"
                                        class="btn btn-sm btn-primary">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
