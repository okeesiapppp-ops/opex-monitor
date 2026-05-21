@extends('layouts.app')
@section('title', 'Tambah Rekonsiliasi')
@section('page-title', 'Tambah Rekonsiliasi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reconciliation.index') }}">Reconciliation</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah Rekonsiliasi</h3>
    </div>
    <form action="{{ route('reconciliation.store') }}" method="POST">
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
                        <label>Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Backlog <span class="text-danger">*</span></label>
                        <select name="backlog_id" class="form-control @error('backlog_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Backlog --</option>
                            @foreach($backlogs as $backlog)
                                <option value="{{ $backlog->id }}" {{ old('backlog_id')==$backlog->id?'selected':'' }}>
                                    {{ $backlog->unit_number }} - {{ $backlog->part_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('backlog_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
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
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Vendor <span class="text-danger">*</span></label>
                        <select name="vendor_id" class="form-control @error('vendor_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id')==$vendor->id?'selected':'' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                        @error('vendor_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Qty Ordered <span class="text-danger">*</span></label>
                        <input type="number" name="qty_ordered" class="form-control @error('qty_ordered') is-invalid @enderror" value="{{ old('qty_ordered', 0) }}" min="0" required>
                        @error('qty_ordered')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Qty Received <span class="text-danger">*</span></label>
                        <input type="number" name="qty_received" class="form-control @error('qty_received') is-invalid @enderror" value="{{ old('qty_received', 0) }}" min="0" required>
                        @error('qty_received')<span class="invalid-feedback">{{ $message }}</span>@enderror
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
            <a href="{{ route('reconciliation.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
        </div>
    </form>
</div>
@endsection