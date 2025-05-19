@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="{{ route('pdi.antrian') }}" class="list-group-item list-group-item-action">
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
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Pemeriksaan Kendaraan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Detail Peminjaman</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">ID Peminjaman</th>
                                        <td>: {{ $peminjaman->id_peminjaman }}</td>
                                    </tr>
                                    <tr>
                                        <th>Peminjam</th>
                                        <td>: {{ $peminjaman->user->username ?? 'User tidak ditemukan' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Pinjam</th>
                                        <td>: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tujuan</th>
                                        <td>: {{ $peminjaman->tujuan }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Detail Kendaraan</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">Nama</th>
                                        <td>: {{ $peminjaman->mobil->nama ?? 'Kendaraan tidak ditemukan' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis</th>
                                        <td>: {{ $peminjaman->mobil->jenis ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Plat Nomor</th>
                                        <td>: {{ $peminjaman->mobil->plat_nomor ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Keterangan</th>
                                        <td>: {{ $peminjaman->mobil->keterangan ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Form Pemeriksaan</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pdi.cek.simpan', $peminjaman->id_peminjaman) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="hasil_pemeriksaan" class="form-label">Hasil Pemeriksaan</label>
                                <textarea class="form-control @error('hasil_pemeriksaan') is-invalid @enderror" id="hasil_pemeriksaan"
                                    name="hasil_pemeriksaan" rows="5" required>{{ old('hasil_pemeriksaan') }}</textarea>
                                @error('hasil_pemeriksaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Detail kondisi kendaraan, komponen yang diperiksa, dll.</small>
                            </div>

                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan Tambahan</label>
                                <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="3">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Status Kelayakan Kendaraan</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status_kendaraan" id="layak"
                                        value="layak" {{ old('status_kendaraan') == 'layak' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="layak">Layak Digunakan</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status_kendaraan" id="tidak_layak"
                                        value="tidak_layak"
                                        {{ old('status_kendaraan') == 'tidak_layak' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tidak_layak">Tidak Layak Digunakan</label>
                                </div>
                                @error('status_kendaraan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-save"></i> Simpan Hasil Pemeriksaan
                                </button>
                                <a href="{{ route('pdi.antrian') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
