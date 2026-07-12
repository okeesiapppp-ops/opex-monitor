@extends('layouts.app')
@section('title', 'Edit Backlog')
@section('page-title', 'Edit Backlog')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backlog.index') }}">Data Backlog</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Edit Backlog</h3>
    </div>
    <form action="{{ route('backlog.update', $backlog->id) }}" method="POST">
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
                        <label>Unit Number <span class="text-danger">*</span></label>
                        <input type="text" name="unit_number" class="form-control" value="{{ old('unit_number', $backlog->unit_number) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Model <span class="text-danger">*</span></label>
                        <input type="text" name="model" class="form-control" value="{{ old('model', $backlog->model) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Part Number <span class="text-danger">*</span></label>
                        <input type="text" name="part_number" class="form-control" value="{{ old('part_number', $backlog->part_number) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Part Name <span class="text-danger">*</span></label>
                        <input type="text" name="part_name" class="form-control" value="{{ old('part_name', $backlog->part_name) }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Qty <span class="text-danger">*</span></label>
                                <input type="number" name="qty" class="form-control" value="{{ old('qty', $backlog->qty) }}" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Satuan <span class="text-danger">*</span></label>
                                <input type="text" name="satuan" class="form-control" value="{{ old('satuan', $backlog->satuan) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="overdue" {{ old('status', $backlog->status)=='overdue'?'selected':'' }}>Pending</option>
                            <option value="in_progress" {{ old('status', $backlog->status)=='in_progress'?'selected':'' }}>In Progress</option>
                            <option value="complete" {{ old('status', $backlog->status)=='complete'?'selected':'' }}>Complete</option>
                            <option value="cancelled" {{ old('status', $backlog->status)=='cancelled'?'selected':'' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Vendor <span class="text-danger">*</span></label>
                        <select name="vendor" class="form-control" required>
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->name }}" {{ old('vendor', $backlog->vendor)==$vendor->name?'selected':'' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ETA</label>
                        <input type="date" name="eta" class="form-control" value="{{ old('eta', $backlog->eta) }}">
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $backlog->location) }}">
                    </div>
                    <div class="form-group">
                        <label>Priority <span class="text-danger">*</span></label>
                        <select name="priority" class="form-control" required>
                            <option value="high" {{ old('priority', $backlog->priority)=='high'?'selected':'' }}>High</option>
                            <option value="medium" {{ old('priority', $backlog->priority)=='medium'?'selected':'' }}>Medium</option>
                            <option value="low" {{ old('priority', $backlog->priority)=='low'?'selected':'' }}>Low</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $backlog->keterangan) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update</button>
            <a href="{{ route('backlog.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
        </div>
    </form>
</div>
@endsection