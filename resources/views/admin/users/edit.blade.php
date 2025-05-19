@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2>Edit Pengguna: {{ $user->username }}</h2>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                            name="username" value="{{ old('username', $user->username) }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password (kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="lvl_users" class="form-label">Level Pengguna</label>
                        <select class="form-select @error('lvl_users') is-invalid @enderror" id="lvl_users" name="lvl_users"
                            required>
                            <option value="admin" {{ $user->lvl_users == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kepala_bengkel" {{ $user->lvl_users == 'kepala_bengkel' ? 'selected' : '' }}>
                                Kepala Bengkel</option>
                            <option value="pdi" {{ $user->lvl_users == 'pdi' ? 'selected' : '' }}>PDI</option>
                            <option value="satpam" {{ $user->lvl_users == 'satpam' ? 'selected' : '' }}>Satpam</option>
                        </select>
                        @error('lvl_users')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Perbarui
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
