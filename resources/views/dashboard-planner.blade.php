@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
<style>
    body, .content-wrapper { background: #f4f6f9 !important; }

    /* ===== WELCOME BANNER ===== */
    .welcome-banner {
        border-radius: 14px;
        background: linear-gradient(135deg, #1A202C 0%, #2D3748 100%);
        color: #fff;
        padding: 18px 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(26,32,44,0.25);
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -20px; right: -20px;
        width: 120px; height: 120px;
        background: rgba(255,215,0,0.08);
        border-radius: 50%;
    }
    .welcome-banner .bg-icon {
        position: absolute;
        right: 24px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4rem;
        color: #FFD700;
        opacity: 0.15;
    }
    .welcome-banner h5 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 4px;
        color: #FFD700;
    }
    .welcome-banner small {
        font-size: 13px;
        color: #A0AEC0;
    }
    .welcome-banner small strong { color: #FFD700; }

    /* ===== STAT CARDS — OPSI E ===== */
    .stat-card {
        border-radius: 14px;
        padding: 20px 22px;
        position: relative;
        overflow: hidden;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        height: 100%;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .stat-card .stat-icon {
        position: absolute;
        right: 16px;
        top: 16px;
        font-size: 2.5rem;
        opacity: 0.15;
    }
    .stat-card .stat-number {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 2px;
        line-height: 1;
    }
    .stat-card .stat-label {
        font-size: 13px;
        font-weight: 600;
        opacity: 0.75;
        margin-bottom: 10px;
        letter-spacing: 0.3px;
    }
    .stat-card .stat-footer {
        font-size: 12px;
        font-weight: 600;
        border-top: 1px solid rgba(0,0,0,0.08);
        padding-top: 8px;
    }

    /* Card 1 — Total Backlog: KUNING */
    .stat-card.bg-total {
        background: #FFD700;
        color: #1A202C;
    }
    .stat-card.bg-total .stat-icon { color: #1A202C; }
    .stat-card.bg-total .stat-footer { border-top-color: rgba(26,32,44,0.12); }
    .stat-card.bg-total .stat-footer a { color: #1A202C !important; opacity: 0.7; }
    .stat-card.bg-total .stat-footer a:hover { opacity: 1; text-decoration: none; }

    /* Card 2 — Complete: NAVY */
    .stat-card.bg-complete {
        background: #1A202C;
        color: #FFD700;
    }
    .stat-card.bg-complete .stat-icon { color: #FFD700; }
    .stat-card.bg-complete .stat-label { color: #A0AEC0; opacity: 1; }
    .stat-card.bg-complete .stat-footer { border-top-color: rgba(255,215,0,0.15); }
    .stat-card.bg-complete .stat-footer a { color: #FFD700 !important; opacity: 0.7; }
    .stat-card.bg-complete .stat-footer a:hover { opacity: 1; text-decoration: none; }

    /* Card 3 — Outstanding: PUTIH + merah */
    .stat-card.bg-outstanding {
        background: #FFFFFF;
        color: #1A202C;
        border: 1.5px solid #FED7D7 !important;
        box-shadow: 0 4px 15px rgba(229,62,62,0.1) !important;
    }
    .stat-card.bg-outstanding .stat-number { color: #E53E3E; }
    .stat-card.bg-outstanding .stat-label { color: #718096; opacity: 1; }
    .stat-card.bg-outstanding .stat-icon { color: #E53E3E; }
    .stat-card.bg-outstanding .stat-footer { border-top-color: #FED7D7; }
    .stat-card.bg-outstanding .stat-footer a { color: #E53E3E !important; font-weight: 700; }
    .stat-card.bg-outstanding .stat-footer a:hover { text-decoration: none; }

    /* Card 4 — On Progress: ABU MUDA */
    .stat-card.bg-progress {
        background: #F7FAFC;
        color: #1A202C;
        border: 1.5px solid #E2E8F0 !important;
    }
    .stat-card.bg-progress .stat-number { color: #4A5568; }
    .stat-card.bg-progress .stat-label { color: #718096; opacity: 1; }
    .stat-card.bg-progress .stat-icon { color: #4A5568; }
    .stat-card.bg-progress .stat-footer { border-top-color: #E2E8F0; }
    .stat-card.bg-progress .stat-footer a { color: #4A5568 !important; font-weight: 700; }
    .stat-card.bg-progress .stat-footer a:hover { color: #1A202C !important; text-decoration: none; }

    /* ===== CARD ===== */
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

    /* ===== TABLE ===== */
    .table-planner-dash thead th {
        background: #1A202C;
        color: #FFD700;
        font-size: 12px;
        font-weight: 700;
        padding: 10px 8px;
        border: none;
        white-space: nowrap;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .table-planner-dash tbody td {
        font-size: 12px;
        padding: 8px;
        vertical-align: middle;
        border-bottom: 1px solid #f5f5f5;
    }
    .table-planner-dash tbody tr:hover { background: #FFFFF0; }

    /* ===== FILTER ===== */
    .filter-control {
        border-radius: 8px;
        border: 1.5px solid #E2E8F0;
        font-size: 13px;
        height: 36px;
        background: #fff;
        transition: border-color 0.2s;
    }
    .filter-control:focus {
        border-color: #E6C200 !important;
        box-shadow: 0 0 0 3px rgba(255,215,0,0.15) !important;
        outline: none;
    }

    /* ===== PAGINATION ===== */
    .pagination .page-item .page-link {
        border-radius: 6px !important;
        margin: 0 2px;
        font-size: 12px;
        padding: 5px 10px;
        color: #1A202C;
        border-color: #E2E8F0;
        font-weight: 600;
    }
    .pagination .page-item .page-link:hover {
        background: #FFD700;
        border-color: #E6C200;
        color: #1A202C;
    }
    .pagination .page-item.active .page-link {
        background: #1A202C;
        border-color: #1A202C;
        color: #FFD700;
    }

    /* ===== SECTION TITLE ===== */
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

{{-- Welcome Banner --}}
<div class="welcome-banner mb-3">
    <div class="bg-icon"><i class="fas fa-users"></i></div>
    <h5><i class="fas fa-users mr-2"></i> Selamat datang, {{ Auth::user()->name }}!</h5>
    <small>Anda login sebagai <strong>Planner (PAMA)</strong> — {{ now()->format('l, d F Y') }}</small>
</div>

{{-- 4 Kartu Statistik --}}
<div class="row mb-3">
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-total">
            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-number">{{ $totalBacklog }}</div>
            <div class="stat-label">Total Backlog</div>
            <div class="stat-footer">
                <a href="{{ route('dashboard') }}?status=">
                    <i class="fas fa-arrow-circle-right mr-1"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-complete">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $totalComplete }}</div>
            <div class="stat-label">Complete</div>
            <div class="stat-footer">
                <a href="{{ route('dashboard') }}?status=complete">
                    <i class="fas fa-arrow-circle-right mr-1"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-outstanding">
            <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="stat-number">{{ $totalOutstanding }}</div>
            <div class="stat-label">Outstanding</div>
            <div class="stat-footer">
                <a href="{{ route('dashboard') }}?status=outstanding">
                    <i class="fas fa-arrow-circle-right mr-1"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-progress">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-number">{{ $totalPending }}</div>
            <div class="stat-label">On Progress</div>
            <div class="stat-footer">
                <a href="{{ route('dashboard') }}?status=pending">
                    <i class="fas fa-arrow-circle-right mr-1"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Data Backlog --}}
<p class="section-title"><i class="fas fa-table mr-2"></i>Data Backlog Terkini</p>
<div class="card" id="monitoring">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold">
                <i class="fas fa-table mr-2" style="color:#FFD700;"></i> Data Backlog Terkini
            </h6>
            <a href="{{ route('backlog-daily.index') }}" class="btn btn-sm"
                style="border-radius: 8px; font-size: 12px; background:#1A202C; color:#FFD700; font-weight:700;">
                <i class="fas fa-eye mr-1"></i> Semua
            </a>
        </div>
    </div>
    <div class="card-body">

        {{-- Filter --}}
        <form method="GET" action="{{ route('dashboard') }}" class="mb-3">
            <div class="row">
                <div class="col-md-5">
                    <select name="unit" class="form-control filter-control"
                        onchange="if(this.value===''){window.location='{{ route('dashboard') }}';}else{this.form.submit();}">
                        <option value="">-- Filter Unit --</option>
                        @foreach($unitList as $unit)
                            <option value="{{ $unit }}" {{ request('unit') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <select name="status" class="form-control filter-control"
                        onchange="if(this.value===''){window.location='{{ route('dashboard') }}';}else{this.form.submit();}">
                        <option value="">-- Semua Status --</option>
                        <option value="complete"    {{ request('status') == 'complete'    ? 'selected' : '' }}>Complete</option>
                        <option value="outstanding" {{ request('status') == 'outstanding' ? 'selected' : '' }}>Outstanding</option>
                        <option value="pending"     {{ request('status') == 'pending'     ? 'selected' : '' }}>On Progress</option>
                    </select>
                </div>
                @if(request('unit') || request('status'))
                <div class="col-md-2 d-flex align-items-center">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm"
                        style="border-radius: 8px; font-size: 12px;">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                </div>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-planner-dash table-hover" id="backlogTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Reservation</th>
                        <th>PN</th>
                        <th>NPN</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Backlog Desc</th>
                        <th>Plan Date</th>
                        <th>Tgl BCS</th>
                        <th>Remarks</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backlogMonitoring as $index => $item)
                    <tr>
                        <td>{{ $backlogMonitoring->firstItem() + $index }}</td>
                        <td>
                            <span style="font-weight: 600; font-size: 12px;">{{ $item->reservation_number ?? '-' }}</span><br>
                            <small class="text-muted">Item: {{ $item->reservation_item ?? '-' }}</small>
                        </td>
                        <td>{{ $item->part_number ?? '-' }}</td>
                        <td>{{ $item->npn ?? '-' }}</td>
                        <td>{{ $item->qty ?? '-' }}</td>
                        <td>
                            @if($item->unit)
                                <span class="badge badge-info" style="border-radius: 20px; font-size: 11px; padding: 4px 8px;">
                                    {{ $item->unit }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $item->mo_description ?? '-' }}</td>
                        <td>{{ $item->plan_date ? \Carbon\Carbon::parse($item->plan_date)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $item->tanggal_bcs ? \Carbon\Carbon::parse($item->tanggal_bcs)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $item->remarks ?? '-' }}</td>
                        <td>
                            @if($item->status == 'complete')
                                <span class="badge badge-success" style="border-radius: 20px; padding: 5px 10px;">Complete</span>
                            @elseif($item->status == 'outstanding')
                                <span class="badge badge-warning" style="border-radius: 20px; padding: 5px 10px;">Outstanding</span>
                            @else
                                <span class="badge badge-secondary" style="border-radius: 20px; padding: 5px 10px;">On Progress</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity: 0.3;"></i>
                            Tidak ada data backlog
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $backlogMonitoring->links() }}
        </div>

    </div>
</div>

@endsection