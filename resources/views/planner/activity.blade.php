@extends('layouts.app')
@section('title', 'Aktivitas Planner')
@section('page-title', 'Aktivitas Planner')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('planner.index') }}">Planner Management</a></li>
    <li class="breadcrumb-item active">Aktivitas</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Aktivitas Planner: {{ $planner->nama ?? $planner->name }}</h3>
        <div class="card-tools">
            <a href="{{ route('planner.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Backlog</span>
                        <span class="info-box-number">{{ $activity['total_backlog'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ready Backlog</span>
                        <span class="info-box-number">{{ $activity['ready_backlog'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending Backlog</span>
                        <span class="info-box-number">{{ $activity['pending_backlog'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection