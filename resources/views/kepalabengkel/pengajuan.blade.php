<!-- resources/views/kepalabengkel/pengajuan.blade.php -->
@extends('layouts.app')

@section('title', 'Antrian Pengajuan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Antrian Pengajuan Peminjaman</h1>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($pengajuans->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Tidak ada pengajuan peminjaman yang menunggu persetujuan.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Penyewa</th>
                                <th>Mobil</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Akhir</th>
                                <th>Total Biaya</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengajuans as $pengajuan)
                                <tr>
                                    <td>{{ $pengajuan->id_transaksi }}</td>
                                    <td>{{ $pengajuan->nama_penyewa }}</td>
                                    <td>
                                        @if ($pengajuan->mobil)
                                            <span class="fw-bold">{{ $pengajuan->mobil->merk }}
                                                {{ $pengajuan->mobil->model }}</span><br>
                                            <small class="text-muted">{{ $pengajuan->mobil->plat }}</small>
                                        @else
                                            <span class="text-danger">Data mobil tidak tersedia</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai_sewa)->format('d M Y H:i') }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_akhir_sewa)->format('d M Y H:i') }}
                                    </td>
                                    <td>Rp {{ number_format($pengajuan->total_biaya, 0, ',', '.') }}</td>
                                    <td>{{ $pengajuan->keterangan_pengajuan ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('kepalabengkel.setuju', $pengajuan->id_transaksi) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')">
                                                    <i class="fas fa-check"></i> Setuju
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#tolakModal{{ $pengajuan->id_transaksi }}">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        </div>

                                        <!-- Modal Tolak -->
                                        <div class="modal fade" id="tolakModal{{ $pengajuan->id_transaksi }}"
                                            tabindex="-1" aria-labelledby="tolakModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="tolakModalLabel">Tolak Pengajuan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form
                                                        action="{{ route('kepalabengkel.tolak', $pengajuan->id_transaksi) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="alasan_penolakan" class="form-label">Alasan
                                                                    Penolakan</label>
                                                                <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Tolak
                                                                Pengajuan</button>
                                                        </div>
                                                    </form>
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

    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Panduan Pengajuan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-check-circle text-success"></i> Menyetujui Pengajuan</h6>
                    <p>Saat Anda menyetujui pengajuan:</p>
                    <ul>
                        <li>Status mobil akan berubah menjadi "Disewa"</li>
                        <li>Pengajuan akan diteruskan ke PDI untuk pemeriksaan kendaraan</li>
                        <li>Penyewa akan dapat mengambil kendaraan setelah pemeriksaan selesai</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-times-circle text-danger"></i> Menolak Pengajuan</h6>
                    <p>Saat Anda menolak pengajuan:</p>
                    <ul>
                        <li>Status pengajuan akan berubah menjadi "Ditolak"</li>
                        <li>Wajib memberikan alasan penolakan yang jelas</li>
                        <li>Penyewa perlu membuat pengajuan baru jika ingin mengajukan kembali</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
