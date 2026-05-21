@extends('layouts.app')
@section('title', 'Planner Management')
@section('page-title', 'Planner Management')
@section('breadcrumb')
    <li class="breadcrumb-item active">Planner Management</li>
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

    .table-planner thead th {
        background: #1a73e8;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        padding: 12px 14px;
        border: none;
        white-space: nowrap;
    }
    .table-planner tbody td {
        font-size: 13px;
        padding: 11px 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f5f5f5;
    }
    .table-planner tbody tr:hover { background: #f0f5ff; }

    .planner-avatar {
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
        <a href="{{ route('planner.create') }}" class="btn btn-primary" style="border-radius: 8px; font-size: 13px;">
            <i class="fas fa-plus mr-1"></i> Tambah Planner
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
        <form method="GET" action="{{ route('planner.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label class="filter-label">Search</label>
                        <input type="text" name="search" class="form-control filter-control"
                            placeholder="Nama/Email/Jabatan"
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
                    <div class="d-flex w-100">
                        <button type="submit" class="btn btn-primary btn-block mr-1"
                            style="border-radius: 8px; font-size: 13px; height: 36px;">
                            <i class="fas fa-search mr-1"></i> Cari
                        </button>
                        <a href="{{ route('planner.index') }}" class="btn btn-secondary"
                            style="border-radius: 8px; font-size: 13px; height: 36px; line-height: 1.2;">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Planner --}}
<p class="section-title"><i class="fas fa-users mr-2"></i>Data Planner</p>
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 font-weight-bold">
            <i class="fas fa-users mr-2 text-primary"></i> Data Planner
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-planner mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Jabatan</th>
                        <th>Perusahaan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($planners as $i => $planner)
                    <tr>
                        <td>{{ $planners->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="planner-avatar">
                                    <i class="fas fa-user" style="color: #1a73e8; font-size: 14px;"></i>
                                </div>
                                <span style="font-weight: 600;">{{ $planner->nama ?? $planner->name }}</span>
                            </div>
                        </td>
                        <td>
                            @if($planner->email)
                                <i class="fas fa-envelope text-muted mr-1" style="font-size: 11px;"></i>
                                {{ $planner->email }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($planner->telepon ?? $planner->phone ?? false)
                                <i class="fas fa-phone text-muted mr-1" style="font-size: 11px;"></i>
                                {{ $planner->telepon ?? $planner->phone }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($planner->jabatan ?? false)
                                <span style="background: #f1f3f4; color: #546e7a; border-radius: 6px; padding: 3px 10px; font-size: 11px; font-weight: 600;">
                                    {{ $planner->jabatan }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $planner->perusahaan ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $planner->is_active ? 'success' : 'danger' }}"
                                style="border-radius: 20px; padding: 5px 12px; font-size: 11px;">
                                <i class="fas fa-{{ $planner->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                {{ $planner->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('planner.show', $planner->id) }}"
                                class="btn btn-xs btn-info" title="Lihat Detail"
                                style="border-radius: 6px; padding: 4px 8px;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('planner.edit', $planner->id) }}"
                                class="btn btn-xs btn-warning" title="Edit"
                                style="border-radius: 6px; padding: 4px 8px;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('planner.destroy', $planner->id) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Yakin hapus planner ini?')">
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
                            Belum ada data planner
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $planners->withQueryString()->links() }}
    </div>
</div>

@endsection