@extends('layouts.app')
@section('title', 'Detail Backlog')
@section('page-title', 'Detail Backlog')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backlog.index') }}">Data Backlog</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Detail Backlog</h3>
        <div class="card-tools">
            <a href="{{ route('backlog.edit', $backlog->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit mr-1"></i> Edit</a>
            <a href="{{ route('backlog.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th width="40%">Unit Number</th><td>{{ $backlog->unit_number }}</td></tr>
                    <tr><th>Model</th><td>{{ $backlog->model }}</td></tr>
                    <tr><th>Part Number</th><td>{{ $backlog->part_number }}</td></tr>
                    <tr><th>Part Name</th><td>{{ $backlog->part_name }}</td></tr>
                    <tr><th>Qty</th><td>{{ $backlog->qty }} {{ $backlog->satuan }}</td></tr>
                    <tr><th>Vendor</th><td>{{ $backlog->vendor }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th width="40%">Status</th><td>
                        @php
                            $badge = match($backlog->status) {
                                'overdue' => 'warning',
                                'in_progress' => 'info',
                                'complete' => 'success',
                                'cancelled' => 'danger',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge badge-{{ $badge }}">{{ ucfirst(str_replace('_',' ',$backlog->status)) }}</span>
                    </td></tr>
                    <tr><th>Priority</th><td>
                        @php
                            $pb = match($backlog->priority) { 'high'=>'danger','medium'=>'warning','low'=>'info',default=>'secondary' };
                        @endphp
                        <span class="badge badge-{{ $pb }}">{{ ucfirst($backlog->priority ?? '-') }}</span>
                    </td></tr>
                    <tr><th>ETA</th><td>{{ $backlog->eta ? \Carbon\Carbon::parse($backlog->eta)->format('d/m/Y') : '-' }}</td></tr>
                    <tr><th>Location</th><td>{{ $backlog->location ?? '-' }}</td></tr>
                    <tr><th>Completion Date</th><td>{{ $backlog->completion_date ? \Carbon\Carbon::parse($backlog->completion_date)->format('d/m/Y') : '-' }}</td></tr>
                    <tr><th>Dibuat oleh</th><td>{{ $backlog->creator->name ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
        @if($backlog->keterangan)
        <div class="form-group mt-2">
            <label><strong>Keterangan:</strong></label>
            <p>{{ $backlog->keterangan }}</p>
        </div>
        @endif

        {{-- Related Orders --}}
        @if($backlog->orders->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0"><i class="fas fa-shopping-cart mr-1"></i> Related Orders</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead><tr><th>Order Number</th><th>PO Number</th><th>Vendor</th><th>Status</th><th>Tgl Order</th></tr></thead>
                    <tbody>
                        @foreach($backlog->orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->po_number ?? '-' }}</td>
                            <td>{{ $order->vendor->name ?? '-' }}</td>
                            <td><span class="badge badge-info">{{ ucfirst($order->status) }}</span></td>
                            <td>{{ $order->tanggal_order ? \Carbon\Carbon::parse($order->tanggal_order)->format('d/m/Y') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection