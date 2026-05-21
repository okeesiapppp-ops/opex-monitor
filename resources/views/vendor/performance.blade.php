@extends('layouts.app')
@section('title', 'Performa Vendor')
@section('page-title', 'Performa Vendor')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('vendor.index') }}">Vendor Management</a></li>
    <li class="breadcrumb-item active">Performa</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Performa Vendor: {{ $vendor->nama_vendor ?? $vendor->name }}</h3>
        <div class="card-tools">
            <a href="{{ route('vendor.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-boxes"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Supplies</span>
                        <span class="info-box-number">{{ $performance['total_supplies'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ready Supplies</span>
                        <span class="info-box-number">{{ $performance['ready_supplies'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending Supplies</span>
                        <span class="info-box-number">{{ $performance['pending_supplies'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-primary">
                    <span class="info-box-icon"><i class="fas fa-percent"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Fulfillment Rate</span>
                        <span class="info-box-number">{{ $performance['fulfillment_rate'] }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection