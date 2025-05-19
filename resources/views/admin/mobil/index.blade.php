@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2>Manajemen Kendaraan</h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.mobil.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Kendaraan Baru
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">ID</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Plat Nomor</th>
                                <th>Status</th>
                                <th>Foto</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($mobil) > 0)
                                @foreach ($mobil as $kendaraan)
                                    <tr>
                                        <td>{{ $kendaraan->id_mobil }}</td>
                                        <td>{{ $kendaraan->nama }}</td>
                                        <td>{{ $kendaraan->jenis }}</td>
                                        <td>{{ $kendaraan->plat_nomor }}</td>
                                        <td>
                                            @if ($kendaraan->status == 'tersedia')
                                                <span class="badge bg-success">Tersedia</span>
                                            @elseif($kendaraan->status == 'dipinjam')
                                                <span class="badge bg-warning text-dark">Dipinjam</span>
                                            @else
                                                <span class="badge bg-danger">Pemeliharaan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($kendaraan->foto)
                                                <img src="{{ asset('storage/' . $kendaraan->foto) }}"
                                                    alt="{{ $kendaraan->nama }}" class="img-thumbnail" width="80">
                                            @else
                                                <span class="badge bg-secondary">Tidak ada foto</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.mobil.edit', $kendaraan->id_mobil) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.mobil.destroy', $kendaraan->id_mobil) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus kendaraan ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data kendaraan</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
