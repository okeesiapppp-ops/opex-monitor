@extends('layouts.app')
@section('title', 'Tambah User')
@section('page-title', 'Tambah User')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User Management</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah User</h3></div>
    <form action="{{ route('user.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Admin</option>
                            <option value="vendor" {{ old('role')=='vendor'?'selected':'' }}>Vendor</option>
                            <option value="planner" {{ old('role')=='planner'?'selected':'' }}>Planner</option>
                        </select>
                        @error('role')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                    <div class="form-group">
                        <label>Perusahaan</label>
                        <input type="text" name="company" class="form-control" value="{{ old('company') }}">
                    </div>
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ old('status')=='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
            <a href="{{ route('user.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
        </div>
    </form>
</div>
@endsection