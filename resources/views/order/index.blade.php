@extends('layouts.app')
@section('title', 'Order & WR')
@section('page-title', 'Order & WR')
@section('breadcrumb')
    <li class="breadcrumb-item active">Order & WR</li>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('order.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Order
        </a>
    </div>
</div>

{{-- Filter --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('order.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Order/PO/WR/Part Number" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua</option>
                            <option value="ordered" {{ request('status')=='ordered'?'selected':'' }}>Ordered</option>
                            <option value="confirmed" {{ request('status')=='confirmed'?'selected':'' }}>Confirmed</option>
                            <option value="shipped" {{ request('status')=='shipped'?'selected':'' }}>Shipped</option>
                            <option value="received" {{ request('status')=='received'?'selected':'' }}>Received</option>
                            <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Vendor</label>
                        <select name="vendor_id" class="form-control">
                            <option value="">Semua</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ request('vendor_id')==$vendor->id?'selected':'' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <div class="form-group w-100">
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-shopping-cart mr-1"></i> Data Order & WR</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="bg-primary">
                    <tr>
                        <th>No</th>
                        <th>Order Number</th>
                        <th>PO/WR Number</th>
                        <th>Part Number</th>
                        <th>Part Name</th>
                        <th>Qty</th>
                        <th>Vendor</th>
                        <th>Planner</th>
                        <th>Tgl Order</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $i => $order)
                    <tr>
                        <td>{{ $orders->firstItem() + $i }}</td>
                        <td><small>{{ $order->order_number }}</small></td>
                        <td>
                            <small>{{ $order->po_number ?? '-' }}</small><br>
                            <small>{{ $order->wr_number ?? '-' }}</small>
                        </td>
                        <td><small>{{ $order->part_number }}</small></td>
                        <td><small>{{ $order->part_name }}</small></td>
                        <td>{{ $order->qty }}</td>
                        <td><small>{{ $order->vendor->name ?? '-' }}</small></td>
                        <td><small>{{ $order->planner->name ?? '-' }}</small></td>
                        <td><small>{{ $order->tanggal_order ? \Carbon\Carbon::parse($order->tanggal_order)->format('d/m/Y') : '-' }}</small></td>
                        <td>
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
                        </td>
                        <td>
                            <a href="{{ route('order.show', $order->id) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('order.edit', $order->id) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('order.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus order ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted"><i class="fas fa-inbox"></i> Belum ada data order</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection