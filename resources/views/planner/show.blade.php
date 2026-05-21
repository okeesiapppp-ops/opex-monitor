@extends('layouts.app')
@section('title', 'Detail Planner')
@section('page-title', 'Detail Planner')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('planner.index') }}">Planner Management</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Detail Planner</h3>
        <div class="card-tools">
            <a href="{{ route('planner.edit', $planner->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit mr-1"></i> Edit</a>
            <a href="{{ route('planner.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th width="40%">Nama</th><td>{{ $planner->nama ?? $planner->name }}</td></tr>
                    <tr><th>Email</th><td>{{ $planner->email }}</td></tr>
                    <tr><th>Telepon</th><td>{{ $planner->telepon ?? $planner->phone ?? '-' }}</td></tr>
                    <tr><th>Jabatan</th><td>{{ $planner->jabatan ?? '-' }}</td></tr>
                    <tr><th>Perusahaan</th><td>{{ $planner->perusahaan ?? '-' }}</td></tr>
                    <tr><th>Department</th><td>{{ $planner->department ?? '-' }}</td></tr>
                    <tr><th>Status</th><td>
                        <span class="badge badge-{{ $planner->is_active ? 'success' : 'danger' }}">
                            {{ $planner->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Backlog</span>
                                <span class="info-box-number">{{ $stats['total_backlog_handled'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pending Orders</span>
                                <span class="info-box-number">{{ $stats['pending_orders'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection