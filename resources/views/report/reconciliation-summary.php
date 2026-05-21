@extends('layouts.app')
@section('title', 'Reconciliation Summary Report')
@section('page-title', 'Reconciliation Summary Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('report.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Reconciliation Summary</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter</h3></div>
    <div class="card-body">
        <form method="GET">
            <div class="row">
                <div class="col-md-3"><div class="form-group"><label>Start Date</label><input type="date" name="start_date" class="form-control" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"></div></div>
                <div class="col-md-3"><div class="form-group"><label>End Date</label><input type="date" name="end_date" class="form-control" value="{{ request('end_date', now()->format('Y-m-d')) }}"></div></div>
                <div class="col-md-2 d-flex align-items-end"><div class="form-group w-100"><button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Filter</button></div></div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-3"><div class="info-box bg-info"><span class="info-box-icon"><i class="fas fa-list"></i></span><div class="info-box-content"><span class="info-box-text">Total</span><span class="info-box-number">{{ $summary['total'] }}</span></div></div></div>
    <div class="col-md-3"><div class="info-box bg-success"><span class="info-box-icon"><i class="fas fa-check"></i></span><div class="info-box-content"><span class="info-box-text">Match</span><span class="info-box-number">{{ $summary['match'] }}</span></div></div></div>
    <div class="col-md-3"><div class="info-box bg-danger"><span class="info-box-icon"><i class="fas fa-times"></i></span><div class="info-box-content"><span class="info-box-text">Mismatch</span><span class="info-box-number">{{ $summary['mismatch'] }}</span></div></div></div>
    <div class="col-md-3"><div class="info-box bg-primary"><span class="info-box-icon"><i class="fas fa-percent"></i></span><div class="info-box-content"><span class="info-box-text">Accuracy Rate</span><span class="info-box-number">{{ $summary['accuracy_rate'] }}%</span></div></div></div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">Performa per Vendor</h3></div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead class="bg-primary"><tr><th>Vendor</th><th>Total</th><th>Match</th><th>Mismatch</th><th>Accuracy</th></tr></thead>
            <tbody>
                @forelse($byVendor as $vendorName => $data)
                <tr>
                    <td>{{ $vendorName ?? 'Unknown' }}</td>
                    <td>{{ $data['total'] }}</td>
                    <td><span class="badge badge-success">{{ $data['match'] }}</span></td>
                    <td><span class="badge badge-danger">{{ $data['mismatch'] }}</span></td>
                    <td>
                        <div class="progress" style="height:20px">
                            <div class="progress-bar bg-{{ $data['accuracy'] >= 90 ? 'success' : 'danger' }}" style="width:{{ $data['accuracy'] }}%">{{ $data['accuracy'] }}%</div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection