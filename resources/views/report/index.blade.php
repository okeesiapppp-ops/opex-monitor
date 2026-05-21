@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports')
@section('breadcrumb')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-primary">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Backlog Achievement</h3></div>
            <div class="card-body">
                <p class="text-muted">Laporan pencapaian penyelesaian backlog berdasarkan periode.</p>
                <a href="{{ route('report.backlog-achievement') }}" class="btn btn-primary btn-block"><i class="fas fa-arrow-right mr-1"></i> Lihat Report</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline card-success">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-truck mr-1"></i> Vendor Performance</h3></div>
            <div class="card-body">
                <p class="text-muted">Laporan performa vendor berdasarkan fulfillment rate.</p>
                <a href="{{ route('report.vendor-performance') }}" class="btn btn-success btn-block"><i class="fas fa-arrow-right mr-1"></i> Lihat Report</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline card-warning">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-exclamation-triangle mr-1"></i> Part Deviation</h3></div>
            <div class="card-body">
                <p class="text-muted">Laporan part yang sering mengalami penyimpangan.</p>
                <a href="{{ route('report.part-deviation') }}" class="btn btn-warning btn-block"><i class="fas fa-arrow-right mr-1"></i> Lihat Report</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Monthly Trend</h3></div>
            <div class="card-body">
                <p class="text-muted">Laporan tren bulanan achievement dan backlog ratio.</p>
                <a href="{{ route('report.monthly-trend') }}" class="btn btn-info btn-block"><i class="fas fa-arrow-right mr-1"></i> Lihat Report</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline card-secondary">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-shopping-cart mr-1"></i> Order Status</h3></div>
            <div class="card-body">
                <p class="text-muted">Laporan status order dan WR per periode.</p>
                <a href="{{ route('report.order-status') }}" class="btn btn-secondary btn-block"><i class="fas fa-arrow-right mr-1"></i> Lihat Report</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline card-danger">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-balance-scale mr-1"></i> Reconciliation Summary</h3></div>
            <div class="card-body">
                <p class="text-muted">Laporan ringkasan rekonsiliasi per periode.</p>
                <a href="{{ route('report.reconciliation-summary') }}" class="btn btn-danger btn-block"><i class="fas fa-arrow-right mr-1"></i> Lihat Report</a>
            </div>
        </div>
    </div>
</div>
@endsection