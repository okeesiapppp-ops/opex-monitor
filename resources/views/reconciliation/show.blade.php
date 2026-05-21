@extends('layouts.app')
@section('title', 'Detail Rekonsiliasi')
@section('page-title', 'Detail Rekonsiliasi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reconciliation.index') }}">Reconciliation</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Detail Rekonsiliasi</h3>
        <div class="card-tools">
            <a href="{{ route('reconciliation.edit', $reconciliation->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit mr-1"></i> Edit</a>
            <form action="{{ route('reconciliation.approve', $reconciliation->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check mr-1"></i> Approve</button>
            </form>
            <a href="{{ route('reconciliation.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th width="40%">Tanggal</th><td>{{ $reconciliation->tanggal ? \Carbon\Carbon::parse($reconciliation->tanggal)->format('d/m/Y') : '-' }}</td></tr>
                    <tr><th>Part Number</th><td>{{ $reconciliation->part_number }}</td></tr>
                    <tr><th>Part Name</th><td>{{ $reconciliation->part_name }}</td></tr>
                    <tr><th>Vendor</th><td>{{ $reconciliation->vendor->name ?? '-' }}</td></tr>
                    <tr><th>Qty Ordered</th><td>{{ $reconciliation->qty_ordered }}</td></tr>
                    <tr><th>Qty Received</th><td>{{ $reconciliation->qty_received }}</td></tr>
                    <tr><th>Selisih</th><td>{{ $reconciliation->qty_difference ?? ($reconciliation->qty_ordered - $reconciliation->qty_received) }}</td></tr>
                    <tr><th>Status</th><td>
                        @php
                            $badge = match($reconciliation->status) {
                                'match' => 'success',
                                'mismatch' => 'danger',
                                'partial' => 'warning',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge badge-{{ $badge }}">{{ ucfirst($reconciliation->status) }}</span>
                    </td></tr>
                    <tr><th>Dibuat oleh</th><td>{{ $reconciliation->creator->name ?? '-' }}</td></tr>
                    <tr><th>Diapprove oleh</th><td>{{ $reconciliation->approver->name ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
        @if($reconciliation->keterangan)
        <div class="form-group mt-2">
            <label><strong>Keterangan:</strong></label>
            <p>{{ $reconciliation->keterangan }}</p>
        </div>
        @endif
    </div>
</div>
@endsection