@extends('layouts.app')
@section('title', 'Monthly Trend Report')
@section('page-title', 'Monthly Trend Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('report.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Monthly Trend</li>
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

    .table-trend thead th {
        background: #1a73e8;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        padding: 12px 16px;
        border: none;
        white-space: nowrap;
    }
    .table-trend tbody td {
        font-size: 13px;
        padding: 12px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f5f5f5;
    }
    .table-trend tbody tr:hover { background: #f0f5ff; }

    .periode-badge {
        background: #e8f0fe;
        color: #1a73e8;
        border-radius: 8px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }

    .achievement-bar {
        height: 10px;
        border-radius: 5px;
        background: #f0f0f0;
        min-width: 120px;
        display: inline-block;
        vertical-align: middle;
        margin-right: 8px;
    }
    .achievement-bar .bar-fill {
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

    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
        vertical-align: middle;
    }
</style>
@endpush

@section('content')

{{-- Filter --}}
<p class="section-title"><i class="fas fa-filter mr-2"></i>Filter Tahun</p>
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
                        <label class="filter-label">Tahun</label>
                        <input type="number" name="year" class="form-control filter-control"
                            value="{{ $year }}" min="2020" max="2030">
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

{{-- Grafik --}}
<p class="section-title"><i class="fas fa-chart-bar mr-2"></i>Grafik Complete vs Overdue {{ $year }}</p>
<div class="card mb-3">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold">
                <i class="fas fa-chart-bar mr-2 text-primary"></i> Complete vs Overdue {{ $year }}
            </h6>
            <div>
                <span class="legend-dot" style="background: #2e7d32;"></span>
                <small class="text-muted mr-3">Complete</small>
                <span class="legend-dot" style="background: #f57c00;"></span>
                <small class="text-muted">Overdue</small>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(count($trend) > 0)
            <div style="height: 280px;">
                <canvas id="trendChart"></canvas>
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-chart-bar fa-2x mb-2 d-block" style="opacity: 0.3;"></i>
                Tidak ada data untuk ditampilkan
            </div>
        @endif
    </div>
</div>

{{-- Tabel Trend --}}
<p class="section-title"><i class="fas fa-table mr-2"></i>Trend Bulanan {{ $year }}</p>
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 font-weight-bold">
            <i class="fas fa-chart-bar mr-2 text-primary"></i> Trend Bulanan {{ $year }}
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-trend mb-0">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Total Backlog</th>
                        <th>Complete</th>
                        <th>Pending</th>
                        <th>Overdue</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                    @endphp
                    @forelse($trend as $t)
                    @php $overdue = $t->total_backlog - $t->complete - $t->pending; @endphp
                    <tr>
                        <td>
                            <span class="periode-badge">
                                {{ $months[$t->month - 1] }} {{ $t->year }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-info" style="border-radius: 20px; padding: 5px 12px; font-size: 12px;">
                                {{ $t->total_backlog }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-success" style="border-radius: 20px; padding: 5px 12px; font-size: 12px;">
                                {{ $t->complete }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-secondary" style="border-radius: 20px; padding: 5px 12px; font-size: 12px;">
                                {{ $t->pending }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-warning" style="border-radius: 20px; padding: 5px 12px; font-size: 12px;">
                                {{ $overdue }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity: 0.3;"></i>
                            Tidak ada data untuk tahun {{ $year }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
@php
    $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $labels = [];
    $completeData = [];
    $overdueData = [];
    foreach ($trend as $t) {
        $labels[] = $months[$t->month - 1] . ' ' . $t->year;
        $completeData[] = $t->complete;
        $overdueData[] = $t->total_backlog - $t->complete - $t->pending;
    }
@endphp

const trendCtx = document.getElementById('trendChart');
if (trendCtx) {
    new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Complete',
                    data: @json($completeData),
                    backgroundColor: 'rgba(46,125,50,0.85)',
                    borderColor: '#2e7d32',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Overdue',
                    data: @json($overdueData),
                    backgroundColor: 'rgba(245,124,0,0.85)',
                    borderColor: '#f57c00',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { size: 12 }, padding: 16 }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' backlog';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });
}
</script>
@endpush