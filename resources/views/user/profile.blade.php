@extends('layouts.app')
@section('title', 'Profile Saya')
@section('page-title', 'Profile Saya')
@section('breadcrumb')
    <li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-user mr-1"></i> Edit Profile</h3></div>
            <form action="{{ route('user.update-profile') }}" method="POST">
                @csrf @method('PUT')
                <div class="card-body">
                    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                    <div class="form-group">
                        <label>Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="form-group">
                        <label>Perusahaan</label>
                        <input type="text" name="company" class="form-control" value="{{ old('company', $user->company) }}">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update Profile</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-key mr-1"></i> Ganti Password</h3></div>
            <form action="{{ route('user.change-password') }}" method="POST">
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Password Lama <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning"><i class="fas fa-key mr-1"></i> Ganti Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection