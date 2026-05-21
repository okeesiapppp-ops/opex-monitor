@extends('layouts.app')
@section('title', 'Track Order')
@section('page-title', 'Track Order')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Order & WR</a></li>
    <li class="breadcrumb-item active">Track</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-search mr-1"></i> Track Order</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('order.track') }}">
            <div class="input-group mb-3">
                <input type="text" name="order_number" class="form-control" placeholder="Masukkan Order Number atau Tracking Number" value="{{ request('order_number') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Cari</button>
                </div>
            </div>
        </form>

        @if(isset($error))
            <div class="alert alert-danger">{{ $error }}</div>
        @endif

        @if(isset($order))
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Order: {{ $order->order_number }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr><th>Part Number</th><td>{{ $order->part_number }}</td></tr>
                            <tr><th>Part Name</th><td>{{ $order->part_name }}</td></tr>
                            <tr><th>Qty</th><td>{{ $order->qty }}</td></tr>
                            <tr><th>Vendor</th><td>{{ $order->vendor->name ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
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
                            <tr><th>Tanggal Order</th><td>{{ $order->tanggal_order ? \Carbon\Carbon::parse($order->tanggal_order)->format('d/m/Y') : '-' }}</td></tr>
                            <tr><th>Expected Arrival</th><td>{{ $order->expected_arrival ? \Carbon\Carbon::parse($order->expected_arrival)->format('d/m/Y') : '-' }}</td></tr>
                            <tr><th>Tracking Number</th><td>{{ $order->tracking_number ?? '-' }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection