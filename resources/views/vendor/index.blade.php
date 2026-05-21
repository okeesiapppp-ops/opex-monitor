@extends('layouts.app')
@section('title', 'Vendor Management')
@section('page-title', 'Vendor Management')
@section('breadcrumb')
    <li class="breadcrumb-item active">Vendor Management</li>
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
    .card .card-footer {
        background: #fff;
        border-top: 1px solid #f0f0f0;
        border-radius: 0 0 14px 14px !important;
        padding: 12px 20px;
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
        padding: 12px 14px;
        border: none;
        white-space: nowrap;
    }
    .table-vendor tbody td {
        font-size: 13px;
        padding: 11px 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f5f5f5;
    }
    .table-vendor tbody tr:hover { background: #f0f5ff; }

    .vendor-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #e8f0fe;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .kode-badge {
        background: #f1f3f4;
        color: #546e7a;
        border-radius: 6px;
        padding: 3px 10px;
        font-size: 11px;
        font-weight: 600;
        font-family: monospace;
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

    .section-title {
        font-size: 13px;
        font-weight: 700;
        color: #546e7a;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 12px;
        padding-left: 4px;
    }
</style>
@endpush

@section('content')

{{-- Tombol Tambah --}}
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('vendor.create') }}" class="btn btn-primary" style="border-radius: 8px; font-size: 13px;">
            <i class="fas fa-plus mr-1"></i> Tambah Vendor
        </a>
    </div>
</div>

{{-- Filter --}}
<p class="section-title"><i class="fas fa-filter mr-2"></i>Filter Data</p>
<div class="card mb-3">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold">
                <i class="fas fa-filter mr-2 text-primary"></i> Filter
            </h6>
            <button type="button" class="btn btn-tool" data-card-widget="collapse" style="background: none; border: none;">
                <i class="fas fa-minus text-muted"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('vendor.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label class="filter-label">Search</label>
                        <input type="text" name="search" class="form-control filter-control"
                            placeholder="Nama/PIC/Email/Kode Vendor"
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="filter-label">Status</label>
                        <select name="status" class="form-control filter-control">
                            <option value="">Semua</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary btn-block mr-1"
                            style="border-radius: 8px; font-size: 13px; height: 36px;">
                            <i class="fas fa-search mr-1"></i> Cari
                        </button>
                        <a href="{{ route('vendor.index') }}" class="btn btn-secondary"
                            style="border-radius: 8px; font-size: 13px; height: 36px; line-height: 1.2;">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Vendor --}}
<p class="section-title"><i class="fas fa-truck mr-2"></i>Data Vendor</p>
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 font-weight-bold">
            <i class="fas fa-truck mr-2 text-primary"></i> Data Vendor
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-vendor mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Vendor</th>
                        <th>Nama Vendor</th>
                        <th>PIC</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $i => $vendor)
                    <tr>
                        <td>{{ $vendors->firstItem() + $i }}</td>
                        <td>
                            <span class="kode-badge">
                                {{ $vendor->kode_vendor ?? $vendor->code ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="vendor-avatar">
                                    <i class="fas fa-truck" style="color: #1a73e8; font-size: 14px;"></i>
                                </div>
                                <span style="font-weight: 600;">{{ $vendor->nama_vendor ?? $vendor->name }}</span>
                            </div>
                        </td>
                        <td>{{ $vendor->pic ?? '-' }}</td>
                        <td>
                            @if($vendor->telepon ?? $vendor->phone ?? false)
                                <i class="fas fa-phone text-muted mr-1" style="font-size: 11px;"></i>
                                {{ $vendor->telepon ?? $vendor->phone }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($vendor->email)
                                <i class="fas fa-envelope text-muted mr-1" style="font-size: 11px;"></i>
                                {{ $vendor->email }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $vendor->is_active ? 'success' : 'danger' }}"
                                style="border-radius: 20px; padding: 5px 12px; font-size: 11px;">
                                <i class="fas fa-{{ $vendor->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('vendor.show', $vendor->id) }}"
                                class="btn btn-xs btn-info" title="Lihat Detail"
                                style="border-radius: 6px; padding: 4px 8px;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('vendor.edit', $vendor->id) }}"
                                class="btn btn-xs btn-warning" title="Edit"
                                style="border-radius: 6px; padding: 4px 8px;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('vendor.destroy', $vendor->id) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Yakin hapus vendor ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger" title="Hapus"
                                    style="border-radius: 6px; padding: 4px 8px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity: 0.3;"></i>
                            Belum ada data vendor
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $vendors->withQueryString()->links() }}
    </div>
</div>

@endsection