@extends('layouts.app')
@section('title', 'Detail Konsultasi')
@section('page-title', 'Detail Konsultasi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reconsult.index') }}">Consultation</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Detail Konsultasi</h3>
        <div class="card-tools">
            <a href="{{ route('reconsult.edit', $reconsult->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit mr-1"></i> Edit</a>
            <a href="{{ route('reconsult.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th width="40%">Backlog</th><td>{{ $reconsult->backlog->unit_number ?? '-' }} - {{ $reconsult->backlog->part_number ?? '-' }}</td></tr>
                    <tr><th>Tanggal</th><td>{{ $reconsult->tanggal_konsultasi ? \Carbon\Carbon::parse($reconsult->tanggal_konsultasi)->format('d/m/Y') : '-' }}</td></tr>
                    <tr><th>Priority</th><td>
                        @php $pb = match($reconsult->priority) { 'high'=>'danger','medium'=>'warning','low'=>'info',default=>'secondary' }; @endphp
                        <span class="badge badge-{{ $pb }}">{{ ucfirst($reconsult->priority) }}</span>
                    </td></tr>
                    <tr><th>Status</th><td>
                        @php $sb = match($reconsult->status) { 'open'=>'warning','in_progress'=>'info','resolved'=>'success','closed'=>'secondary',default=>'secondary' }; @endphp
                        <span class="badge badge-{{ $sb }}">{{ ucfirst(str_replace('_',' ',$reconsult->status)) }}</span>
                    </td></tr>
                    <tr><th>PIC Vendor</th><td>{{ $reconsult->vendorPic->name ?? '-' }}</td></tr>
                    <tr><th>PIC Planner</th><td>{{ $reconsult->plannerPic->name ?? '-' }}</td></tr>
                    <tr><th>Dibuat oleh</th><td>{{ $reconsult->creator->name ?? '-' }}</td></tr>
                    <tr><th>Resolved Date</th><td>{{ $reconsult->resolved_date ? \Carbon\Carbon::parse($reconsult->resolved_date)->format('d/m/Y') : '-' }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="card card-outline card-danger">
                    <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-exclamation-circle mr-1 text-danger"></i> Masalah</h5></div>
                    <div class="card-body"><p>{{ $reconsult->masalah }}</p></div>
                </div>
                <div class="card card-outline card-success">
                    <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-check-circle mr-1 text-success"></i> Solusi</h5></div>
                    <div class="card-body"><p>{{ $reconsult->solusi ?? 'Belum ada solusi' }}</p></div>
                </div>
                @if($reconsult->keterangan)
                <div class="card card-outline card-info">
                    <div class="card-header"><h5 class="card-title mb-0">Keterangan</h5></div>
                    <div class="card-body"><p>{{ $reconsult->keterangan }}</p></div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection