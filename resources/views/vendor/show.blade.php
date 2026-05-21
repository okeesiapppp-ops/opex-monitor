@extends('layouts.app')
@section('title', 'Detail Vendor')
@section('page-title', 'Detail Vendor')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('vendor.index') }}">Vendor Management</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Detail Vendor</h3>
        <div class="card-tools">
            <a href="{{ route('vendor.edit', $vendor->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit mr-1"></i> Edit</a>
            <a href="{{ route('vendor.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th width="40%">Nama Vendor</th><td>{{ $vendor->nama_vendor ?? $vendor->name }}</td></tr>
                    <tr><th>Kode Vendor</th><td>{{ $vendor->kode_vendor ?? $vendor->code ?? '-' }}</td></tr>
                    <tr><th>PIC</th><td>{{ $vendor->pic ?? '-' }}</td></tr>
                    <tr><th>Telepon</th><td>{{ $vendor->telepon ?? $vendor->phone ?? '-' }}</td></tr>
                    <tr><th>Email</th><td>{{ $vendor->email }}</td></tr>
                    <tr><th>Alamat</th><td>{{ $vendor->alamat ?? $vendor->address ?? '-' }}</td></tr>
                    <tr><th>Status</th><td>
                        <span class="badge badge-{{ $vendor->is_active ? 'success' : 'danger' }}">
                            {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-boxes"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Parts</span>
                                <span class="info-box-number">{{ $stats['total_parts'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-percent"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Fulfillment Rate</span>
                                <span class="info-box-number">{{ $stats['fulfillment_rate'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection