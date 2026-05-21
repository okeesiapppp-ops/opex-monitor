@extends('layouts.app')
@section('title', 'Detail Order')
@section('page-title', 'Detail Order')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Order & WR</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Detail Order {{ $order->order_number }}</h3>
        <div class="card-tools">
            <a href="{{ route('order.edit', $order->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit mr-1"></i> Edit</a>
            <a href="{{ route('order.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th width="40%">Order Number</th><td>{{ $order->order_number }}</td></tr>
                    <tr><th>PO Number</th><td>{{ $order->po_number ?? '-' }}</td></tr>
                    <tr><th>WR Number</th><td>{{ $order->wr_number ?? '-' }}</td></tr>
                    <tr><th>Part Number</th><td>{{ $order->part_number }}</td></tr>
                    <tr><th>Part Name</th><td>{{ $order->part_name }}</td></tr>
                    <tr><th>Qty</th><td>{{ $order->qty }}</td></tr>
                    <tr><th>Status</th><td>
                        @php
                            $badge = match($order->status) {
                                'ordered' => 'warning',
                                'confirmed' => 'info',
                                'shipped' => 'primary',
                                'received' => 'success',
                                'cancelled' => 'danger',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge badge-{{ $badge }}">{{ ucfirst($order->status) }}</span>
                    </td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th width="40%">Vendor</th><td>{{ $order->vendor->name ?? '-' }}</td></tr>
                    <tr><th>Planner</th><td>{{ $order->planner->name ?? '-' }}</td></tr>
                    <tr><th>Tanggal Order</th><td>{{ $order->tanggal_order ? \Carbon\Carbon::parse($order->tanggal_order)->format('d/m/Y') : '-' }}</td></tr>
                    <tr><th>Expected Arrival</th><td>{{ $order->expected_arrival ? \Carbon\Carbon::parse($order->expected_arrival)->format('d/m/Y') : '-' }}</td></tr>
                    <tr><th>Actual Arrival</th><td>{{ $order->actual_arrival ? \Carbon\Carbon::parse($order->actual_arrival)->format('d/m/Y') : '-' }}</td></tr>
                    <tr><th>Tracking Number</th><td>{{ $order->tracking_number ?? '-' }}</td></tr>
                    <tr><th>Dibuat oleh</th><td>{{ $order->creator->name ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
        @if($order->keterangan)
        <div class="form-group">
            <label><strong>Keterangan:</strong></label>
            <p>{{ $order->keterangan }}</p>
        </div>
        @endif
    </div>
</div>
@endsection