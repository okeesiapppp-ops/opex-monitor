@extends('layouts.app')
@section('title', 'Edit Vendor')
@section('page-title', 'Edit Vendor')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('vendor.index') }}">Vendor Management</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Edit Vendor</h3>
    </div>
    <form action="{{ route('vendor.update', $vendor->id) }}" method="POST">
        @csrf @method('PUT')
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
                        <input type="text" name="nama_vendor" class="form-control" value="{{ old('nama_vendor', $vendor->nama_vendor ?? $vendor->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Kode Vendor <span class="text-danger">*</span></label>
                        <input type="text" name="kode_vendor" class="form-control" value="{{ old('kode_vendor', $vendor->kode_vendor ?? $vendor->code) }}" required>
                    </div>
                    <div class="form-group">
                        <label>PIC <span class="text-danger">*</span></label>
                        <input type="text" name="pic" class="form-control" value="{{ old('pic', $vendor->pic) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Telepon <span class="text-danger">*</span></label>
                        <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $vendor->telepon ?? $vendor->phone) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $vendor->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $vendor->alamat ?? $vendor->address) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ old('status', $vendor->is_active ? 'active' : 'inactive')=='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ old('status', $vendor->is_active ? 'active' : 'inactive')=='inactive'?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update</button>
            <a href="{{ route('vendor.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
        </div>
    </form>
</div>
@endsection