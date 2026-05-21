@extends('layouts.app')
@section('title', 'Edit Order')
@section('page-title', 'Edit Order')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Order & WR</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Edit Order {{ $order->order_number }}</h3>
    </div>
    <form action="{{ route('order.update', $order->id) }}" method="POST">
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
                        <label>Backlog <span class="text-danger">*</span></label>
                        <select name="backlog_id" class="form-control" required>
                            <option value="">-- Pilih Backlog --</option>
                            @foreach($backlogs as $backlog)
                                <option value="{{ $backlog->id }}" {{ old('backlog_id', $order->backlog_id)==$backlog->id?'selected':'' }}>
                                    {{ $backlog->unit_number }} - {{ $backlog->part_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Part Number <span class="text-danger">*</span></label>
                        <input type="text" name="part_number" class="form-control" value="{{ old('part_number', $order->part_number) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Part Name <span class="text-danger">*</span></label>
                        <input type="text" name="part_name" class="form-control" value="{{ old('part_name', $order->part_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Qty <span class="text-danger">*</span></label>
                        <input type="number" name="qty" class="form-control" value="{{ old('qty', $order->qty) }}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Order <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_order" class="form-control" value="{{ old('tanggal_order', $order->tanggal_order) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="ordered" {{ old('status', $order->status)=='ordered'?'selected':'' }}>Ordered</option>
                            <option value="confirmed" {{ old('status', $order->status)=='confirmed'?'selected':'' }}>Confirmed</option>
                            <option value="shipped" {{ old('status', $order->status)=='shipped'?'selected':'' }}>Shipped</option>
                            <option value="received" {{ old('status', $order->status)=='received'?'selected':'' }}>Received</option>
                            <option value="cancelled" {{ old('status', $order->status)=='cancelled'?'selected':'' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Vendor <span class="text-danger">*</span></label>
                        <select name="vendor_id" class="form-control" required>
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id', $order->vendor_id)==$vendor->id?'selected':'' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Planner <span class="text-danger">*</span></label>
                        <select name="planner_id" class="form-control" required>
                            <option value="">-- Pilih Planner --</option>
                            @foreach($planners as $planner)
                                <option value="{{ $planner->id }}" {{ old('planner_id', $order->planner_id)==$planner->id?'selected':'' }}>{{ $planner->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>PO Number</label>
                        <input type="text" name="po_number" class="form-control" value="{{ old('po_number', $order->po_number) }}">
                    </div>
                    <div class="form-group">
                        <label>WR Number</label>
                        <input type="text" name="wr_number" class="form-control" value="{{ old('wr_number', $order->wr_number) }}">
                    </div>
                    <div class="form-group">
                        <label>Expected Arrival</label>
                        <input type="date" name="expected_arrival" class="form-control" value="{{ old('expected_arrival', $order->expected_arrival) }}">
                    </div>
                    <div class="form-group">
                        <label>Tracking Number</label>
                        <input type="text" name="tracking_number" class="form-control" value="{{ old('tracking_number', $order->tracking_number) }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $order->keterangan) }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update</button>
            <a href="{{ route('order.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
        </div>
    </form>
</div>
@endsection