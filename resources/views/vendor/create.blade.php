@extends('layouts.app')
@section('title', 'Tambah Vendor')
@section('page-title', 'Tambah Vendor')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('vendor.index') }}">Vendor Management</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah Vendor</h3>
    </div>
    <form action="{{ route('vendor.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Vendor <span class="text-danger">*</span></label>
                        <input type="text" name="nama_vendor" class="form-control @error('nama_vendor') is-invalid @enderror" value="{{ old('nama_vendor') }}" required>
                        @error('nama_vendor')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>PIC <span class="text-danger">*</span></label>
                        <input type="text" name="pic" class="form-control @error('pic') is-invalid @enderror" value="{{ old('pic') }}" required>
                        @error('pic')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Telepon <span class="text-danger">*</span></label>
                        <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon') }}" required>
                        @error('telepon')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3">{{ old('alamat') }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5 class="card-title"><i class="fas fa-user mr-1"></i> Buat Akun User</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="create_user" name="create_user" value="1" {{ old('create_user') ? 'checked' : '' }}>
                                    <label for="create_user">Buat akun login untuk vendor ini</label>
                                </div>
                            </div>
                            <div id="user_fields" style="{{ old('create_user') ? '' : 'display:none' }}">
                                <div class="form-group">
                                    <label>Nama User</label>
                                    <input type="text" name="user_name" class="form-control" value="{{ old('user_name') }}">
                                </div>
                                <div class="form-group">
                                    <label>Email User</label>
                                    <input type="email" name="user_email" class="form-control" value="{{ old('user_email') }}">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="user_password" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
            <a href="{{ route('vendor.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$('#create_user').change(function() {
    if ($(this).is(':checked')) {
        $('#user_fields').show();
    } else {
        $('#user_fields').hide();
    }
});
</script>
@endpush