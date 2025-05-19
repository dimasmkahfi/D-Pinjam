<!-- resources/views/kepalabengkel/status.blade.php -->
@extends('layouts.app')

@section('title', 'Status Peminjaman')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Status Peminjaman Aktif</h1>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($transaksis->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Tidak ada peminjaman aktif saat ini.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Penyewa</th>
                                <th>Mobil</th>
                                <th>Tanggal</th>
                                <th>Pemeriksaan</th>
                                <th>Status</th>
                                <th>Keluar/Masuk</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksis as $transaksi)
                                <tr>
                                    <td>{{ $transaksi->id_transaksi }}</td>
                                    <td>{{ $transaksi->nama_penyewa }}</td>
                                    <td>
                                        @if ($transaksi->mobil)
                                            <span class="fw-bold">{{ $transaksi->mobil->merk }}
                                                {{ $transaksi->mobil->model }}</span><br>
                                            <small class="text-muted">{{ $transaksi->mobil->plat }}</small>
                                        @else
                                            <span class="text-danger">Data mobil tidak tersedia</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            <strong>Mulai:</strong>
                                            {{ \Carbon\Carbon::parse($transaksi->tanggal_mulai_sewa)->format('d M Y H:i') }}<br>
                                            <strong>Akhir:</strong>
                                            {{ \Carbon\Carbon::parse($transaksi->tanggal_akhir_sewa)->format('d M Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if ($transaksi->pemeriksaan)
                                            @if ($transaksi->pemeriksaan->status == 'menunggu')
                                                <span class="badge bg-warning">Menunggu Pemeriksaan</span>
                                            @elseif($transaksi->pemeriksaan->status == 'lulus')
                                                <span class="badge bg-success">Lulus Pemeriksaan</span>
                                            @elseif($transaksi->pemeriksaan->status == 'tidak_lulus')
                                                <span class="badge bg-danger">Tidak Lulus Pemeriksaan</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Belum ada pemeriksaan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($transaksi->status_peminjaman == 'disetujui')
                                            <span class="badge bg-info">Disetujui</span>
                                        @elseif($transaksi->status_peminjaman == 'berjalan')
                                            <span class="badge bg-primary">Berjalan</span>
                                        @elseif($transaksi->status_peminjaman == 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($transaksi->status_mobil_keluar)
                                            <span class="badge bg-danger">Mobil Keluar</span>
                                            <small
                                                class="d-block">{{ $transaksi->tanggal_mobil_keluar ? \Carbon\Carbon::parse($transaksi->tanggal_mobil_keluar)->format('d/m/Y H:i') : '-' }}</small>
                                        @else
                                            <span class="badge bg-secondary">Belum Keluar</span>
                                        @endif

                                        @if ($transaksi->status_mobil_masuk)
                                            <span class="badge bg-success mt-1">Mobil Kembali</span>
                                            <small
                                                class="d-block">{{ $transaksi->tanggal_mobil_masuk ? \Carbon\Carbon::parse($transaksi->tanggal_mobil_masuk)->format('d/m/Y H:i') : '-' }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $transaksi->id_transaksi }}">
                                            <i class="fas fa-info-circle"></i> Detail
                                        </button>

                                        <!-- Modal Detail -->
                                        <div class="modal fade" id="detailModal{{ $transaksi->id_transaksi }}"
                                            tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="detailModalLabel">Detail Peminjaman
                                                            #{{ $transaksi->id_transaksi }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h6>Informasi Penyewa</h6>
                                                                <p>
                                                                    <strong>Nama:</strong>
                                                                    {{ $transaksi->nama_penyewa }}<br>
                                                                    <strong>Total Biaya:</strong> Rp
                                                                    {{ number_format($transaksi->total_biaya, 0, ',', '.') }}<br>
                                                                    <strong>Tambahan Sewa:</strong>
                                                                    {{ $transaksi->tambahan_sewa ?: 'Tidak ada' }}<br>
                                                                    <strong>Disetujui Oleh:</strong>
                                                                    {{ $transaksi->approver->username ?? 'N/A' }}<br>
                                                                    <strong>Tanggal Persetujuan:</strong>
                                                                    {{ $transaksi->approval_at ? \Carbon\Carbon::parse($transaksi->approval_at)->format('d M Y H:i') : 'N/A' }}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>Informasi Kendaraan</h6>
                                                                <p>
                                                                    @if ($transaksi->mobil)
                                                                        <strong>Merk & Model:</strong>
                                                                        {{ $transaksi->mobil->merk }}
                                                                        {{ $transaksi->mobil->model }}<br>
                                                                        <strong>Plat Nomor:</strong>
                                                                        {{ $transaksi->mobil->plat }}<br>
                                                                        <strong>Tahun:</strong>
                                                                        {{ $transaksi->mobil->tahun }}<br>
                                                                        <strong>Warna:</strong>
                                                                        {{ $transaksi->mobil->warna }}<br>
                                                                        <strong>Status Mobil:</strong>
                                                                        {{ $transaksi->mobil->status }}
                                                                    @else
                                                                        Data mobil tidak tersedia
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <hr>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h6>Hasil Pemeriksaan</h6>
                                                                @if ($transaksi->pemeriksaan && $transaksi->pemeriksaan->status != 'menunggu')
                                                                    <p>
                                                                        <strong>Status:</strong>
                                                                        @if ($transaksi->pemeriksaan->status == 'lulus')
                                                                            <span class="text-success">Lulus</span>
                                                                        @else
                                                                            <span class="text-danger">Tidak Lulus</span>
                                                                        @endif
                                                                        <br>
                                                                        <strong>Tanggal Pemeriksaan:</strong>
                                                                        {{ $transaksi->pemeriksaan->tanggal_pemeriksaan ? \Carbon\Carbon::parse($transaksi->pemeriksaan->tanggal_pemeriksaan)->format('d M Y H:i') : 'N/A' }}<br>
                                                                        <strong>Petugas:</strong>
                                                                        {{ $transaksi->pemeriksaan->petugas->username ?? 'N/A' }}<br>
                                                                        <strong>Oli:</strong>
                                                                        {{ $transaksi->pemeriksaan->oli ? 'OK' : 'Tidak OK' }}<br>
                                                                        <strong>Tekanan Ban:</strong>
                                                                        {{ $transaksi->pemeriksaan->tekanan_ban ? 'OK' : 'Tidak OK' }}<br>
                                                                        <strong>Tool Set:</strong>
                                                                        {{ $transaksi->pemeriksaan->tool_set ? 'OK' : 'Tidak OK' }}<br>
                                                                        <strong>Mesin:</strong>
                                                                        {{ $transaksi->pemeriksaan->mesin ? 'OK' : 'Tidak OK' }}<br>
                                                                        <strong>Catatan:</strong>
                                                                        {{ $transaksi->pemeriksaan->catatan ?: 'Tidak ada' }}
                                                                    </p>
                                                                @elseif($transaksi->pemeriksaan)
                                                                    <p>Pemeriksaan masih menunggu untuk dilakukan.</p>
                                                                @else
                                                                    <p>Belum ada data pemeriksaan.</p>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>Status Kendaraan</h6>
                                                                <p>
                                                                    <strong>Status Peminjaman:</strong>
                                                                    {{ ucfirst($transaksi->status_peminjaman) }}<br>
                                                                    @if ($transaksi->status_mobil_keluar)
                                                                        <strong>Mobil Keluar:</strong>
                                                                        {{ $transaksi->tanggal_mobil_keluar ? \Carbon\Carbon::parse($transaksi->tanggal_mobil_keluar)->format('d M Y H:i') : 'N/A' }}<br>
                                                                    @else
                                                                        <strong>Mobil Keluar:</strong> <span
                                                                            class="text-danger">Belum</span><br>
                                                                    @endif

                                                                    @if ($transaksi->status_mobil_masuk)
                                                                        <strong>Mobil Kembali:</strong>
                                                                        {{ $transaksi->tanggal_mobil_masuk ? \Carbon\Carbon::parse($transaksi->tanggal_mobil_masuk)->format('d M Y H:i') : 'N/A' }}<br>
                                                                    @else
                                                                        <strong>Mobil Kembali:</strong> <span
                                                                            class="text-danger">Belum</span><br>
                                                                    @endif

                                                                    @if ($transaksi->kerusakan)
                                                                        <strong>Kerusakan:</strong>
                                                                        {{ $transaksi->kerusakan }}<br>
                                                                    @endif

                                                                    @if ($transaksi->denda > 0)
                                                                        <strong>Denda:</strong> Rp
                                                                        {{ number_format($transaksi->denda, 0, ',', '.') }}<br>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
