@extends('layouts.app')
@section('title', 'Edit Rekonsiliasi')
@section('page-title', 'Edit Rekonsiliasi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reconciliation.index') }}">Reconciliation</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Edit Rekonsiliasi</h3>
    </div>
    <form action="{{ route('reconciliation.update', $reconciliation->id) }}" method="POST">
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
                        <label>Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $reconciliation->tanggal) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Backlog <span class="text-danger">*</span></label>
                        <select name="backlog_id" class="form-control" required>
                            <option value="">-- Pilih Backlog --</option>
                            @foreach($backlogs as $backlog)
                                <option value="{{ $backlog->id }}" {{ old('backlog_id', $reconciliation->backlog_id)==$backlog->id?'selected':'' }}>
                                    {{ $backlog->unit_number }} - {{ $backlog->part_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Part Number <span class="text-danger">*</span></label>
                        <input type="text" name="part_number" class="form-control" value="{{ old('part_number', $reconciliation->part_number) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Part Name <span class="text-danger">*</span></label>
                        <input type="text" name="part_name" class="form-control" value="{{ old('part_name', $reconciliation->part_name) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Vendor <span class="text-danger">*</span></label>
                        <select name="vendor_id" class="form-control" required>
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id', $reconciliation->vendor_id)==$vendor->id?'selected':'' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Qty Ordered <span class="text-danger">*</span></label>
                        <input type="number" name="qty_ordered" class="form-control" value="{{ old('qty_ordered', $reconciliation->qty_ordered) }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Qty Received <span class="text-danger">*</span></label>
                        <input type="number" name="qty_received" class="form-control" value="{{ old('qty_received', $reconciliation->qty_received) }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $reconciliation->keterangan) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update</button>
            <a href="{{ route('reconciliation.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
        </div>
    </form>
</div>
@endsection