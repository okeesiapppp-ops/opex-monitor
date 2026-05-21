@extends('layouts.app')
@section('title', 'Archive')
@section('page-title', 'Archive')
@section('breadcrumb')
    <li class="breadcrumb-item active">Archive</li>
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

    .table-archive thead th {
        background: #1a73e8;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        padding: 10px 12px;
        border: none;
        white-space: nowrap;
    }
    .table-archive tbody td {
        font-size: 12px;
        padding: 10px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f5f5f5;
    }
    .table-archive tbody tr:hover { background: #f0f5ff; }

    .periode-badge {
        background: #e8f0fe;
        color: #1a73e8;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }

    .progress-archive {
        height: 22px;
        border-radius: 8px;
        background: #f0f0f0;
        min-width: 120px;
    }
    .progress-archive .progress-bar {
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
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
        <form method="GET" action="{{ route('archive.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="filter-label">Tahun</label>
                        <select name="year" class="form-control filter-control">
                            <option value="">Semua</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="filter-label">Periode</label>
                        <input type="month" name="periode" class="form-control filter-control"
                            value="{{ request('periode') }}">
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

{{-- Tabel Archive --}}
<p class="section-title"><i class="fas fa-archive mr-2"></i>Data Archive</p>
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 font-weight-bold">
            <i class="fas fa-archive mr-2 text-primary"></i> Data Archive
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-archive table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Periode</th>
                        <th>Tanggal Archive</th>
                        <th>Total Backlog</th>
                        <th>Complete</th>
                        <th>Pending</th>
                        <th>Outstanding</th>
                        <th>Achievement</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($archives as $i => $archive)
                    <tr>
                        <td>{{ $archives->firstItem() + $i }}</td>
                        <td>
                            <span class="periode-badge">{{ $archive->periode }}</span>
                        </td>
                        <td>
                            <i class="fas fa-calendar-alt text-muted mr-1"></i>
                            {{ \Carbon\Carbon::parse($archive->tanggal_archive)->format('d/m/Y') }}
                        </td>
                        <td>
                            <span class="badge badge-info" style="border-radius: 20px; padding: 5px 10px; font-size: 12px;">
                                {{ $archive->total_backlog }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-success" style="border-radius: 20px; padding: 5px 10px; font-size: 12px;">
                                {{ $archive->total_complete }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-secondary" style="border-radius: 20px; padding: 5px 10px; font-size: 12px;">
                                {{ $archive->total_pending }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-warning" style="border-radius: 20px; padding: 5px 10px; font-size: 12px;">
                                {{ $archive->total_outstanding ?? 0 }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress progress-archive flex-grow-1">
                                    <div class="progress-bar bg-{{ $archive->achievement_percentage >= 95 ? 'success' : ($archive->achievement_percentage >= 90 ? 'warning' : 'danger') }}"
                                        style="width: {{ $archive->achievement_percentage }}%">
                                        {{ $archive->achievement_percentage }}%
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('archive.show', $archive->id) }}"
                                class="btn btn-xs btn-info" title="Lihat Detail"
                                style="border-radius: 6px; padding: 4px 8px;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('archive.destroy', $archive->id) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Yakin hapus archive ini?')">
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
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity: 0.3;"></i>
                            Belum ada data archive
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $archives->withQueryString()->links() }}
    </div>
</div>

@endsection