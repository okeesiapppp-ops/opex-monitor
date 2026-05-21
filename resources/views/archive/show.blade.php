@extends('layouts.app')
@section('title', 'Detail Archive')
@section('page-title', 'Detail Archive')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('archive.index') }}">Archive</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')

<!-- Statistik -->
<div class="row">
    <div class="col-md-3">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $archive->total_backlog }}</h3>
                <p>Total Backlog</p>
            </div>
            <div class="icon"><i class="fas fa-clipboard-list"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $archive->total_complete }}</h3>
                <p>Complete</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $archive->total_outstanding ?? 0 }}</h3>
                <p>Outstanding</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $archive->total_pending }}</h3>
                <p>Pending</p>
            </div>
            <div class="icon"><i class="fas fa-spinner"></i></div>
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