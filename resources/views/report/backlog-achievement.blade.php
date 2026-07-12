@extends('layouts.app')
@section('title', 'Backlog Achievement Report')
@section('page-title', 'Backlog Achievement Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('report.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Backlog Achievement</li>
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

    /* ===== MONTH NAVIGATION ===== */
    .month-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .month-nav-label {
        font-size: 12px;
        font-weight: 700;
        color: #4A5568;
        text-transform: uppercase;
        letter-spacing: .8px;
        margin-right: 4px;
    }
    .month-btn {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        border: 1.5px solid #E2E8F0;
        background: #fff;
        color: #4A5568;
        cursor: pointer;
        text-decoration: none;
        transition: all .18s ease;
        display: inline-block;
    }
    .month-btn:hover {
        background: #FFD700;
        border-color: #E6C200;
        color: #1A202C;
        text-decoration: none;
    }
    .month-btn.active {
        background: #1A202C;
        border-color: #1A202C;
        color: #FFD700;
        box-shadow: 0 2px 8px rgba(26,32,44,.25);
    }
    .year-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: auto;
    }
    .year-btn {
        width: 30px; height: 30px;
        border-radius: 50%;
        border: 1.5px solid #E2E8F0;
        background: #fff;
        color: #4A5568;
        font-size: 13px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        text-decoration: none;
        transition: all .18s ease;
    }
    .year-btn:hover {
        background: #FFD700;
        border-color: #E6C200;
        color: #1A202C;
        text-decoration: none;
    }
    .year-label {
        font-size: 14px;
        font-weight: 800;
        color: #1A202C;
        min-width: 50px;
        text-align: center;
    }

    /* ===== STAT CARDS — UT THEME ===== */
    .stat-card {
        border-radius: 14px;
        padding: 22px 24px;
        position: relative;
        overflow: hidden;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.15); }
    .stat-card .stat-icon {
        position: absolute;
        right: 18px; top: 18px;
        font-size: 2.8rem;
        opacity: 0.15;
    }
    .stat-card .stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 2px;
        line-height: 1;
    }
    .stat-card .stat-label {
        font-size: 13px;
        font-weight: 600;
        opacity: 0.75;
        margin-bottom: 0;
    }

    /* Card Total: KUNING */
    .stat-card.bg-total {
        background: #FFD700;
        color: #1A202C;
    }
    .stat-card.bg-total .stat-icon { color: #1A202C; }

    /* Card Complete: NAVY */
    .stat-card.bg-complete {
        background: #1A202C;
        color: #FFD700;
    }
    .stat-card.bg-complete .stat-icon { color: #FFD700; }
    .stat-card.bg-complete .stat-label { color: #A0AEC0; opacity: 1; }

    /* Card Achievement: dinamis */
    .stat-card.bg-success-ach { background: linear-gradient(135deg, #1A4731, #276749); color: #fff; }
    .stat-card.bg-warning-ach { background: linear-gradient(135deg, #7B5E00, #B7791F); color: #fff; }
    .stat-card.bg-danger-ach  { background: linear-gradient(135deg, #9B2C2C, #C53030); color: #fff; }

    /* ===== FILTER ===== */
    .filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #1A202C;
        margin-bottom: 4px;
    }
    .filter-control {
        border-radius: 8px;
        border: 1.5px solid #E2E8F0;
        font-size: 13px;
        height: 36px;
        background: #fff;
        transition: border-color .2s;
    }
    .filter-control:focus {
        border-color: #E6C200 !important;
        box-shadow: 0 0 0 3px rgba(255,215,0,.15) !important;
        outline: none;
    }

    /* ===== TABLE ===== */
    .table-achievement thead th {
        background: #1A202C;
        color: #FFD700;
        font-size: 12px;
        font-weight: 700;
        padding: 12px 16px;
        border: none;
        letter-spacing: .5px;
        text-transform: uppercase;
    }
    .table-achievement tbody td {
        font-size: 13px;
        padding: 12px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f5f5f5;
    }
    .table-achievement tbody tr:hover { background: #FFFFF0; }

    .pct-badge {
        font-size: 13px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
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

    /* ===== ACHIEVEMENT RING ===== */
    .achievement-ring {
        width: 110px; height: 110px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 800;
        color: #fff;
        margin: 0 auto 12px;
    }
    .ring-success { background: linear-gradient(135deg, #1A4731, #276749); box-shadow: 0 4px 15px rgba(26,71,49,.3); }
    .ring-warning { background: linear-gradient(135deg, #7B5E00, #B7791F); box-shadow: 0 4px 15px rgba(123,94,0,.3); }
    .ring-danger  { background: linear-gradient(135deg, #9B2C2C, #C53030); box-shadow: 0 4px 15px rgba(155,44,44,.3); }

    /* ===== PROGRESS BAR ===== */
    .progress-track {
        background: #EDF2F7;
        border-radius: 8px;
        height: 12px;
        overflow: hidden;
    }
    .progress-fill {
        height: 12px;
        border-radius: 8px;
        transition: width .6s ease;
    }
    .fill-success { background: linear-gradient(90deg, #48BB78, #276749); }
    .fill-warning { background: linear-gradient(90deg, #FFD700, #B7791F); }
    .fill-danger  { background: linear-gradient(90deg, #FC8181, #C53030); }

    /* Proporsi bar di tabel */
    .prop-track {
        background: #EDF2F7;
        border-radius: 4px;
        height: 8px;
        overflow: hidden;
        min-width: 120px;
    }
    .prop-complete    { background: linear-gradient(90deg, #48BB78, #276749); height: 8px; border-radius: 4px; }
    .prop-overdue { background: linear-gradient(90deg, #FC8181, #C53030); height: 8px; border-radius: 4px; }
    .prop-pending     { background: linear-gradient(90deg, #A0AEC0, #4A5568); height: 8px; border-radius: 4px; }
</style>
@endpush

@section('content')

{{-- ===== NAVIGASI BULAN ===== --}}
@php
    $currentMonth = (int) request('month', now()->month);
    $currentYear  = (int) request('year',  now()->year);
    $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
@endphp

<div class="card mb-3">
    <div class="card-body" style="padding: 16px 20px;">
        <div class="month-nav">
            <span class="month-nav-label"><i class="fas fa-calendar-alt mr-1"></i> Pilih Bulan:</span>
            @foreach($months as $i => $m)
            <a href="{{ route('report.backlog-achievement') }}?month={{ $i+1 }}&year={{ $currentYear }}"
               class="month-btn {{ $currentMonth == ($i+1) ? 'active' : '' }}">
                {{ $m }}
            </a>
            @endforeach

            {{-- Navigasi Tahun --}}
            <div class="year-nav">
                <a href="{{ route('report.backlog-achievement') }}?month={{ $currentMonth }}&year={{ $currentYear - 1 }}"
                   class="year-btn" title="Tahun sebelumnya">
                    <i class="fas fa-chevron-left" style="font-size:11px;"></i>
                </a>
                <span class="year-label">{{ $currentYear }}</span>
                <a href="{{ route('report.backlog-achievement') }}?month={{ $currentMonth }}&year={{ $currentYear + 1 }}"
                   class="year-btn" title="Tahun berikutnya">
                    <i class="fas fa-chevron-right" style="font-size:11px;"></i>
                </a>
            </div>
        </div>

        {{-- Info periode aktif --}}
        <div style="
            background: #F7FAFC;
            border: 1px solid #E2E8F0;
            border-left: 4px solid #FFD700;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 13px;
            color: #4A5568;
            font-weight: 600;
            margin-top: 4px;
        ">
            <i class="fas fa-info-circle mr-2" style="color:#FFD700;"></i>
            Menampilkan data:
            <strong style="color:#1A202C;">
                {{ $months[$currentMonth-1] }} {{ $currentYear }}
            </strong>
            <span style="color:#A0AEC0; font-weight:400; margin-left:8px;">
                ({{ $startDate }} s/d {{ $endDate }})
            </span>
        </div>
    </div>
</div>

{{-- Kartu Statistik --}}
<div class="row mb-3">
    <div class="col-md-4 mb-3">
        <div class="stat-card bg-total">
            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-number">{{ $total }}</div>
            <div class="stat-label">Total Backlog</div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stat-card bg-complete">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $complete }}</div>
            <div class="stat-label">Complete</div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stat-card" style="background:#FFFFFF;border:1.5px solid #FED7D7;box-shadow:0 4px 15px rgba(229,62,62,0.1);">
            <div class="stat-icon" style="color:#E53E3E;"><i class="fas fa-exclamation-circle"></i></div>
            <div class="stat-number" style="color:#E53E3E;">{{ $overdue }}</div>
            <div class="stat-label" style="color:#718096;">Overdue</div>
        </div>
    </div>
</div>

{{-- Distribusi Status --}}
<p class="section-title"><i class="fas fa-chart-bar mr-2"></i>Distribusi Status Backlog</p>
<div class="row mb-3">

    {{-- Tabel --}}
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 font-weight-bold">
                    <i class="fas fa-table mr-2" style="color:#FFD700;"></i> Distribusi Status Backlog
                </h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-achievement mb-0">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Persentase</th>
                            <th>Proporsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                        @php $pct = $total > 0 ? round(($row->total / $total) * 100, 2) : 0; @endphp
                        <tr>
                            <td>
                                @if($row->status == 'overdue')
                                    <span class="badge badge-secondary" style="border-radius: 20px; padding: 5px 12px;">Overdue</span>
                                @elseif($row->status == 'overdue')
                                    <span class="badge badge-danger" style="border-radius: 20px; padding: 5px 12px;">Overdue</span>
                                @elseif($row->status == 'complete')
                                    <span class="badge badge-success" style="border-radius: 20px; padding: 5px 12px;">Complete</span>
                                @else
                                    <span class="badge badge-secondary" style="border-radius: 20px; padding: 5px 12px;">{{ ucfirst($row->status) }}</span>
                                @endif
                            </td>
                            <td style="font-weight: 700; color:#1A202C;">{{ $row->total }}</td>
                            <td>
                                <span class="pct-badge" style="
                                    background: {{ $row->status == 'complete' ? '#C6F6D5' : ($row->status == 'overdue' ? '#FED7D7' : '#EDF2F7') }};
                                    color: {{ $row->status == 'complete' ? '#276749' : ($row->status == 'overdue' ? '#9B2C2C' : '#4A5568') }};
                                ">
                                    {{ $pct }}%
                                </span>
                            </td>
                            <td style="min-width: 150px;">
                                <div class="prop-track">
                                    <div class="
                                        @if($row->status == 'complete') prop-complete
                                        @elseif($row->status == 'overdue') prop-overdue
                                        @else prop-pending @endif"
                                        style="width: {{ $pct }}%;">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity:.3;"></i>
                                Tidak ada data untuk periode ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Comparison Summary --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 font-weight-bold">
                    <i class="fas fa-chart-pie mr-2" style="color:#FFD700;"></i> Perbandingan
                </h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center py-4">
                <div class="mt-3 w-100">
                    <div class="d-flex justify-content-between py-2" style="border-top: 1px solid #f0f0f0;">
                        <small class="text-muted">Total Backlog</small>
                        <span style="font-weight:700;font-size:13px;color:#1A202C;">{{ $total }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-top: 1px solid #f0f0f0;">
                        <small class="text-muted">Complete</small>
                        <span style="font-weight:700;font-size:13px;color:#276749;">{{ $complete }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-top: 1px solid #f0f0f0;">
                        <small class="text-muted">Overdue</small>
                        <span style="font-weight:700;font-size:13px;color:#C53030;">{{ $overdue }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-top: 1px solid #f0f0f0;">
                        <small class="text-muted">Outstanding</small>
                        <span style="font-weight:700;font-size:13px;color:#4A5568;">{{ $outstanding }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="col-md-12 mt-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 font-weight-bold">
                    <i class="fas fa-chart-bar mr-2" style="color:#FFD700;"></i> Perbandingan Complete vs Overdue (Persen)
                </h6>
            </div>
            <div class="card-body">
                <div style="height:220px;">
                    <canvas id="completeOverdueBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("completeOverdueBarChart");
    if (ctx) {
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Complete", "Overdue"],
                datasets: [{
                    label: "Persentase",
                    data: [{{ $completePercentage }}, {{ $overduePercentage }}],
                    backgroundColor: ["#28a745", "#dc3545"],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, max: 100, ticks: { callback: function(v){ return v + "%"; } } } }
            }
        });
    }
});
</script>
@endpush
n
@endsection
