@extends('layouts.app')
@section('title', 'Detail Archive')
@section('page-title', 'Detail Archive')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('archive.index') }}">Archive</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')

<!-- Statistik -->
<style>
.stat-card { border-radius: 14px; padding: 20px 22px; position: relative; overflow: hidden; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.12); height: 100%; }
.stat-card .stat-icon { position: absolute; right: 18px; top: 18px; font-size: 2.8rem; opacity: 0.15; }
.stat-card .stat-number { font-size: 2.2rem; font-weight: 800; margin-bottom: 2px; line-height: 1; }
.stat-card .stat-label { font-size: 13px; font-weight: 600; opacity: 0.75; margin-bottom: 0; }
.stat-card.bg-total { background: #FFD700; color: #1A202C; }
.stat-card.bg-total .stat-icon { color: #1A202C; }
.stat-card.bg-complete { background: #1A202C; color: #FFD700; }
.stat-card.bg-complete .stat-icon { color: #FFD700; }
.stat-card.bg-complete .stat-label { color: #A0AEC0; opacity: 1; }
.stat-card.bg-overdue { background: #FFFFFF; color: #1A202C; border: 1.5px solid #FED7D7 !important; }
.stat-card.bg-overdue .stat-number { color: #E53E3E; }
.stat-card.bg-overdue .stat-icon { color: #E53E3E; }
.stat-card.bg-outstanding { background: #F7FAFC; color: #1A202C; border: 1.5px solid #E2E8F0 !important; }
.stat-card.bg-outstanding .stat-number { color: #4A5568; }
.stat-card.bg-outstanding .stat-icon { color: #4A5568; }
</style>
<div class="row mb-3">
    <div class="col-md-3 mb-3">
        <div class="stat-card bg-total">
            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-number">{{ $archive->total_backlog }}</div>
            <div class="stat-label">Total Backlog</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card bg-complete">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $archive->total_complete }}</div>
            <div class="stat-label">Complete</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card bg-outstanding">
            <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="stat-number">{{ $archive->total_outstanding ?? 0 }}</div>
            <div class="stat-label">Outstanding</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card bg-overdue">
            <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="stat-number">{{ $archive->total_overdue ?? 0 }}</div>
            <div class="stat-label">Overdue</div>
        </div>
    </div>
</div>

<!-- Info Periode -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-info-circle mr-1"></i> Archive Periode {{ $archive->periode }}
        </h3>
        <div class="card-tools">
            <a href="{{ route('archive.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <table class="table table-bordered">
                    <tr>
                        <th>Periode</th>
                        <td>{{ $archive->periode }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Archive</th>
                        <td>{{ \Carbon\Carbon::parse($archive->tanggal_archive)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Achievement</th>
                        <td>
                            <div class="progress" style="height:20px;">
                                <div class="progress-bar bg-{{ $archive->achievement_percentage >= 95 ? 'success' : ($archive->achievement_percentage >= 90 ? 'warning' : 'danger') }}"
                                    style="width:{{ $archive->achievement_percentage }}%">
                                    {{ $archive->achievement_percentage }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Detail Data -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-table mr-1"></i> Detail Backlog Complete
            <span class="badge badge-success ml-2">{{ $archive->details->count() }} data</span>
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>No</th>
                        <th>Reservation</th>
                        <th>PN</th>
                        <th>NPN</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Backlog Desc</th>
                        <th>Plan Date</th>
                        <th>Jarak Eksekusi</th>
                        <th>Tgl BCS</th>
                        <th>Remarks</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($archive->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <small>{{ $detail->reservation_number ?? '-' }}</small><br>
                            <small class="text-muted">Item: {{ $detail->reservation_item ?? '-' }}</small>
                        </td>
                        <td>{{ $detail->part_number ?? '-' }}</td>
                        <td>{{ $detail->npn ?? '-' }}</td>
                        <td>{{ $detail->qty ?? '-' }}</td>
                        <td><span class="badge badge-info">{{ $detail->unit ?? '-' }}</span></td>
                        <td>{{ $detail->mo_description ?? '-' }}</td>
                        <td>{{ $detail->plan_date ? \Carbon\Carbon::parse($detail->plan_date)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $detail->jarak_eksekusi ?? '-' }}</td>
                        <td>{{ $detail->tanggal_bcs ? \Carbon\Carbon::parse($detail->tanggal_bcs)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $detail->remarks ?? '-' }}</td>
                        <td><span class="badge badge-success">Complete</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted">
                            <i class="fas fa-inbox"></i> Belum ada detail data
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection