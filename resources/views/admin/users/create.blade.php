@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2>Tambah Pengguna Baru</h2>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                            name="username" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="lvl_users" class="form-label">Level Pengguna</label>
                        <select class="form-select @error('lvl_users') is-invalid @enderror" id="lvl_users" name="lvl_users"
                            required>
                            <option value="" selected disabled>Pilih Level Pengguna</option>
                            <option value="admin" {{ old('lvl_users') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kepala_bengkel" {{ old('lvl_users') == 'kepala_bengkel' ? 'selected' : '' }}>
                                Kepala Bengkel</option>
                            <option value="pdi" {{ old('lvl_users') == 'pdi' ? 'selected' : '' }}>PDI</option>
                            <option value="satpam" {{ old('lvl_users') == 'satpam' ? 'selected' : '' }}>Satpam</option>
                        </select>
                        @error('lvl_users')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
