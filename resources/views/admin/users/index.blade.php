@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2>Manajemen Pengguna</h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Pengguna Baru
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
                                <th>Username</th>
                                <th>Level Pengguna</th>
                                <th>Status Verifikasi</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($users) > 0)
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->user_id }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>
                                            @if ($user->lvl_users == 'admin')
                                                <span class="badge bg-danger">Admin</span>
                                            @elseif($user->lvl_users == 'kepala_bengkel')
                                                <span class="badge bg-primary">Kepala Bengkel</span>
                                            @elseif($user->lvl_users == 'pdi')
                                                <span class="badge bg-info">PDI</span>
                                            @elseif($user->lvl_users == 'satpam')
                                                <span class="badge bg-warning text-dark">Satpam</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $user->lvl_users }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($user->verification_status))
                                                @if ($user->verification_status == 'verified')
                                                    <span class="badge bg-success">Terverifikasi</span>
                                                @elseif($user->verification_status == 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @else
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Tidak Diketahui</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.edit', $user->user_id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data pengguna</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
