@extends('layouts.app')
@section('title', 'Tambah Backlog')
@section('page-title', 'Tambah Backlog')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backlog.index') }}">Data Backlog</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah Backlog</h3>
    </div>
    <form action="{{ route('backlog.store') }}" method="POST">
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
                        <label>Unit Number <span class="text-danger">*</span></label>
                        <input type="text" name="unit_number" class="form-control @error('unit_number') is-invalid @enderror" value="{{ old('unit_number') }}" required>
                        @error('unit_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Model <span class="text-danger">*</span></label>
                        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model') }}" required>
                        @error('model')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Part Number <span class="text-danger">*</span></label>
                        <input type="text" name="part_number" class="form-control @error('part_number') is-invalid @enderror" value="{{ old('part_number') }}" required>
                        @error('part_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Part Name <span class="text-danger">*</span></label>
                        <input type="text" name="part_name" class="form-control @error('part_name') is-invalid @enderror" value="{{ old('part_name') }}" required>
                        @error('part_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Qty <span class="text-danger">*</span></label>
                                <input type="number" name="qty" class="form-control @error('qty') is-invalid @enderror" value="{{ old('qty', 1) }}" min="1" required>
                                @error('qty')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Satuan <span class="text-danger">*</span></label>
                                <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" value="{{ old('satuan', 'PCS') }}" required>
                                @error('satuan')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Vendor <span class="text-danger">*</span></label>
                        <select name="vendor" class="form-control @error('vendor') is-invalid @enderror" required>
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->name }}" {{ old('vendor')==$vendor->name?'selected':'' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                        @error('vendor')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>ETA</label>
                        <input type="date" name="eta" class="form-control" value="{{ old('eta') }}">
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location') }}">
                    </div>
                    <div class="form-group">
                        <label>Priority <span class="text-danger">*</span></label>
                        <select name="priority" class="form-control @error('priority') is-invalid @enderror" required>
                            <option value="">-- Pilih Priority --</option>
                            <option value="high" {{ old('priority')=='high'?'selected':'' }}>High</option>
                            <option value="medium" {{ old('priority')=='medium'?'selected':'' }}>Medium</option>
                            <option value="low" {{ old('priority')=='low'?'selected':'' }}>Low</option>
                        </select>
                        @error('priority')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
            <a href="{{ route('backlog.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
        </div>
    </form>
</div>
@endsection