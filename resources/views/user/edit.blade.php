@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User Management</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-edit mr-1"></i> Edit User</h3></div>
    <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Password Baru <small class="text-muted">(kosongkan jika tidak ingin ganti)</small></label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-control" required>
                            <option value="admin" {{ old('role', $user->role)=='admin'?'selected':'' }}>Admin</option>
                            <option value="vendor" {{ old('role', $user->role)=='vendor'?'selected':'' }}>Vendor</option>
                            <option value="planner" {{ old('role', $user->role)=='planner'?'selected':'' }}>Planner</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="form-group">
                        <label>Perusahaan</label>
                        <input type="text" name="company" class="form-control" value="{{ old('company', $user->company) }}">
                    </div>
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ old('status', $user->status)=='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status)=='inactive'?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update</button>
            <a href="{{ route('user.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
        </div>
    </form>
</div>
@endsection