@extends('layouts.app')
@section('title', 'Order Status Report')
@section('page-title', 'Order Status Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('report.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Order Status</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter</h3></div>
    <div class="card-body">
        <form method="GET">
            <div class="row">
                <div class="col-md-3"><div class="form-group"><label>Start Date</label><input type="date" name="start_date" class="form-control" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"></div></div>
                <div class="col-md-3"><div class="form-group"><label>End Date</label><input type="date" name="end_date" class="form-control" value="{{ request('end_date', now()->format('Y-m-d')) }}"></div></div>
                <div class="col-md-2 d-flex align-items-end"><div class="form-group w-100"><button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Filter</button></div></div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-2"><div class="info-box bg-info"><span class="info-box-icon"><i class="fas fa-list"></i></span><div class="info-box-content"><span class="info-box-text">Total</span><span class="info-box-number">{{ $summary['total'] }}</span></div></div></div>
    <div class="col-md-2"><div class="info-box bg-warning"><span class="info-box-icon"><i class="fas fa-clock"></i></span><div class="info-box-content"><span class="info-box-text">Ordered</span><span class="info-box-number">{{ $summary['ordered'] }}</span></div></div></div>
    <div class="col-md-2"><div class="info-box bg-primary"><span class="info-box-icon"><i class="fas fa-ship"></i></span><div class="info-box-content"><span class="info-box-text">Shipped</span><span class="info-box-number">{{ $summary['shipped'] }}</span></div></div></div>
    <div class="col-md-2"><div class="info-box bg-success"><span class="info-box-icon"><i class="fas fa-check"></i></span><div class="info-box-content"><span class="info-box-text">Received</span><span class="info-box-number">{{ $summary['received'] }}</span></div></div></div>
    <div class="col-md-2"><div class="info-box bg-danger"><span class="info-box-icon"><i class="fas fa-times"></i></span><div class="info-box-content"><span class="info-box-text">Cancelled</span><span class="info-box-number">{{ $summary['cancelled'] }}</span></div></div></div>
    <div class="col-md-2"><div class="info-box bg-secondary"><span class="info-box-icon"><i class="fas fa-exclamation"></i></span><div class="info-box-content"><span class="info-box-text">Delayed</span><span class="info-box-number">{{ $summary['delayed'] }}</span></div></div></div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">Data Order</h3></div>
    <div class="card-body p-0">
        <table class="table table-striped table-sm">
            <thead class="bg-primary"><tr><th>Order Number</th><th>Part Number</th><th>Part Name</th><th>Vendor</th><th>Planner</th><th>Tgl Order</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><small>{{ $order->order_number }}</small></td>
                    <td><small>{{ $order->part_number }}</small></td>
                    <td><small>{{ $order->part_name }}</small></td>
                    <td><small>{{ $order->vendor->name ?? '-' }}</small></td>
                    <td><small>{{ $order->planner->name ?? '-' }}</small></td>
                    <td><small>{{ $order->tanggal_order ? \Carbon\Carbon::parse($order->tanggal_order)->format('d/m/Y') : '-' }}</small></td>
                    <td><span class="badge badge-{{ match($order->status) { 'ordered'=>'warning','confirmed'=>'info','shipped'=>'primary','received'=>'success','cancelled'=>'danger',default=>'secondary' } }}">{{ ucfirst($order->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection