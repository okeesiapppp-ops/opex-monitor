@extends('layouts.app')
@section('title', 'Edit Planner')
@section('page-title', 'Edit Planner')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('planner.index') }}">Planner Management</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Edit Planner</h3>
    </div>
    <form action="{{ route('planner.update', $planner->id) }}" method="POST">
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
                        <label>Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $planner->nama ?? $planner->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $planner->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Telepon <span class="text-danger">*</span></label>
                        <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $planner->telepon ?? $planner->phone) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" name="perusahaan" class="form-control" value="{{ old('perusahaan', $planner->perusahaan) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $planner->jabatan) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <input type="text" name="department" class="form-control" value="{{ old('department', $planner->department) }}">
                    </div>
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ old('status', $planner->is_active ? 'active' : 'inactive')=='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ old('status', $planner->is_active ? 'active' : 'inactive')=='inactive'?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update</button>
            <a href="{{ route('planner.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
        </div>
    </form>
</div>
@endsection