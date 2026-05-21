@extends('layouts.app')
@section('title', 'Tambah Order')
@section('page-title', 'Tambah Order')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Order & WR</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah Order</h3>
    </div>
    <form action="{{ route('order.store') }}" method="POST">
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
                    <div class="form-group">
                        <label>Qty <span class="text-danger">*</span></label>
                        <input type="number" name="qty" class="form-control @error('qty') is-invalid @enderror" value="{{ old('qty', 1) }}" min="1" required>
                        @error('qty')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Tanggal Order <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_order" class="form-control @error('tanggal_order') is-invalid @enderror" value="{{ old('tanggal_order', date('Y-m-d')) }}" required>
                        @error('tanggal_order')<span class="invalid-feedback">{{ $message }}</span>@enderror
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
                        <label>Planner <span class="text-danger">*</span></label>
                        <select name="planner_id" class="form-control @error('planner_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Planner --</option>
                            @foreach($planners as $planner)
                                <option value="{{ $planner->id }}" {{ old('planner_id')==$planner->id?'selected':'' }}>{{ $planner->name }}</option>
                            @endforeach
                        </select>
                        @error('planner_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>PO Number</label>
                        <input type="text" name="po_number" class="form-control" value="{{ old('po_number') }}">
                    </div>
                    <div class="form-group">
                        <label>WR Number</label>
                        <input type="text" name="wr_number" class="form-control" value="{{ old('wr_number') }}">
                    </div>
                    <div class="form-group">
                        <label>Expected Arrival</label>
                        <input type="date" name="expected_arrival" class="form-control" value="{{ old('expected_arrival') }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
            <a href="{{ route('order.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
        </div>
    </form>
</div>
@endsection