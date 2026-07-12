@extends('layouts.app')
@section('title', 'Vendor Performance Report')
@section('page-title', 'Vendor Performance Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('report.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Vendor Performance</li>
@endsection

@push('styles')
<style>
    body, .content-wrapper { background: #f4f6f9 !important; }

    .card {
        border-radius: 14px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        background: #fff;
    }
    .card .card-header {
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
        border-radius: 14px 14px 0 0 !important;
        padding: 14px 20px;
    }

    .filter-label {
        font-size: 12px;
        font-weight: 600;
        color: #546e7a;
        margin-bottom: 4px;
    }
    .filter-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        font-size: 13px;
        height: 36px;
        background: #fff;
    }

    .table-vendor thead th {
        background: #1a73e8;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        padding: 12px 16px;
        border: none;
        white-space: nowrap;
    }
    .table-vendor tbody td {
        font-size: 13px;
        padding: 12px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f5f5f5;
    }
    .table-vendor tbody tr:hover { background: #f0f5ff; }

    .vendor-name {
        font-weight: 600;
        color: #263238;
    }

    .fulfillment-bar {
        height: 10px;
        border-radius: 5px;
        background: #f0f0f0;
        min-width: 150px;
    }
    .fulfillment-bar .bar-fill {
        height: 10px;
        border-radius: 5px;
    }

    .rate-badge {
        font-size: 12px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
        min-width: 60px;
        text-align: center;
    }
    .rate-success { background: #e8f5e9; color: #2e7d32; }
    .rate-warning { background: #fff3e0; color: #f57c00; }
    .rate-danger  { background: #ffebee; color: #c62828; }

    .section-title {
        font-size: 13px;
        font-weight: 700;
        color: #546e7a;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 12px;
        padding-left: 4px;
    }

    .pagination .page-item .page-link {
        border-radius: 6px !important;
        margin: 0 2px;
        font-size: 12px;
        padding: 5px 10px;
        color: #1a73e8;
        border-color: #e0e0e0;
    }
    .pagination .page-item.active .page-link {
        background: #1a73e8;
        border-color: #1a73e8;
        color: #fff;
    }
</style>
@endpush

@section('content')

{{-- Filter --}}
<p class="section-title"><i class="fas fa-filter mr-2"></i>Filter Periode</p>
<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0 font-weight-bold">
            <i class="fas fa-filter mr-2 text-primary"></i> Filter
        </h6>
    </div>
    <div class="card-body">
        <form method="GET">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="filter-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control filter-control"
                            value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="filter-label">End Date</label>
                        <input type="date" name="end_date" class="form-control filter-control"
                            value="{{ request('end_date', now()->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block"
                        style="border-radius: 8px; font-size: 13px; height: 36px;">
                        <i class="fas fa-search mr-1"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Vendor --}}
<p class="section-title"><i class="fas fa-truck mr-2"></i>Performa Vendor</p>
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 font-weight-bold">
            <i class="fas fa-truck mr-2 text-primary"></i> Performa Vendor
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-vendor mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Unit</th>
                        <th>Total Part</th>
                        <th>Ready Onsite</th>
                        <th>Not Ready</th>
                        <th>Fulfillment Rate</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $i => $vendor)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><span class="vendor-name">{{ $vendor->unit }}</span></td>
                        <td><span class="badge badge-info" style="border-radius:20px;padding:5px 12px;font-size:12px;">{{ $vendor->total_supplies }}</span></td>
                        <td><span class="badge badge-success" style="border-radius:20px;padding:5px 12px;font-size:12px;">{{ $vendor->ready_supplies }}</span></td>
                        <td><span class="badge badge-danger" style="border-radius:20px;padding:5px 12px;font-size:12px;">{{ $vendor->not_ready }}</span></td>
                        <td><span class="rate-badge rate-{{ $vendor->fulfillment_rate >= 90 ? 'success' : ($vendor->fulfillment_rate >= 70 ? 'warning' : 'danger') }}">{{ $vendor->fulfillment_rate }}%</span></td>
                        <td style="min-width:180px;">
                            <div class="fulfillment-bar">
                                <div class="bar-fill" style="width:{{ $vendor->fulfillment_rate }}%;background:{{ $vendor->fulfillment_rate >= 90 ? '#2e7d32' : ($vendor->fulfillment_rate >= 70 ? '#f57c00' : '#c62828') }};"></div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity: 0.3;"></i>
                            Tidak ada data
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection