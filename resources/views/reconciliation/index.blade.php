@extends('layouts.app')
@section('title', 'Reconciliation')
@section('page-title', 'Reconciliation')
@section('breadcrumb')
    <li class="breadcrumb-item active">Reconciliation</li>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('reconciliation.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Rekonsiliasi
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="row">
    <div class="col-md-3">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fas fa-list"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total</span>
                <span class="info-box-number">{{ $stats['total'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box bg-success">
            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Match</span>
                <span class="info-box-number">{{ $stats['match'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box bg-danger">
            <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Mismatch</span>
                <span class="info-box-number">{{ $stats['mismatch'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box bg-warning">
            <span class="info-box-icon"><i class="fas fa-exclamation-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Partial</span>
                <span class="info-box-number">{{ $stats['partial'] }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reconciliation.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Part Number/Name" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua</option>
                            <option value="match" {{ request('status')=='match'?'selected':'' }}>Match</option>
                            <option value="mismatch" {{ request('status')=='mismatch'?'selected':'' }}>Mismatch</option>
                            <option value="partial" {{ request('status')=='partial'?'selected':'' }}>Partial</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Vendor</label>
                        <select name="vendor_id" class="form-control">
                            <option value="">Semua</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ request('vendor_id')==$vendor->id?'selected':'' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <div class="form-group w-100">
                        <button type="submit" class="btn btn-info btn-block"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-balance-scale mr-1"></i> Data Reconciliation</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="bg-primary">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Part Number</th>
                        <th>Part Name</th>
                        <th>Vendor</th>
                        <th>Qty Order</th>
                        <th>Qty Terima</th>
                        <th>Selisih</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reconciliations as $i => $rec)
                    <tr>
                        <td>{{ $reconciliations->firstItem() + $i }}</td>
                        <td><small>{{ $rec->tanggal ? \Carbon\Carbon::parse($rec->tanggal)->format('d/m/Y') : '-' }}</small></td>
                        <td><small>{{ $rec->part_number }}</small></td>
                        <td><small>{{ $rec->part_name }}</small></td>
                        <td><small>{{ $rec->vendor->name ?? '-' }}</small></td>
                        <td>{{ $rec->qty_ordered }}</td>
                        <td>{{ $rec->qty_received }}</td>
                        <td>{{ $rec->qty_difference ?? ($rec->qty_ordered - $rec->qty_received) }}</td>
                        <td>
                            @php
                                $badge = match($rec->status) {
                                    'match' => 'success',
                                    'mismatch' => 'danger',
                                    'partial' => 'warning',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge badge-{{ $badge }}">{{ ucfirst($rec->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('reconciliation.show', $rec->id) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('reconciliation.edit', $rec->id) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('reconciliation.destroy', $rec->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted"><i class="fas fa-inbox"></i> Belum ada data rekonsiliasi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">{{ $reconciliations->withQueryString()->links() }}</div>
</div>
@endsection