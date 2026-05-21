@extends('layouts.app')
@section('title', 'Consultation')
@section('page-title', 'Consultation')
@section('breadcrumb')
    <li class="breadcrumb-item active">Consultation</li>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('reconsult.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Konsultasi</a>
    </div>
</div>

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
        <div class="info-box bg-warning">
            <span class="info-box-icon"><i class="fas fa-folder-open"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Open</span>
                <span class="info-box-number">{{ $stats['open'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box bg-success">
            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Resolved</span>
                <span class="info-box-number">{{ $stats['resolved'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box bg-danger">
            <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">High Priority</span>
                <span class="info-box-number">{{ $stats['high_priority'] }}</span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter</h3>
        <div class="card-tools"><button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button></div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reconsult.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Masalah/Part/Unit" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua</option>
                            <option value="open" {{ request('status')=='open'?'selected':'' }}>Open</option>
                            <option value="in_progress" {{ request('status')=='in_progress'?'selected':'' }}>In Progress</option>
                            <option value="resolved" {{ request('status')=='resolved'?'selected':'' }}>Resolved</option>
                            <option value="closed" {{ request('status')=='closed'?'selected':'' }}>Closed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Priority</label>
                        <select name="priority" class="form-control">
                            <option value="">Semua</option>
                            <option value="high" {{ request('priority')=='high'?'selected':'' }}>High</option>
                            <option value="medium" {{ request('priority')=='medium'?'selected':'' }}>Medium</option>
                            <option value="low" {{ request('priority')=='low'?'selected':'' }}>Low</option>
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

<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-comments mr-1"></i> Data Konsultasi</h3></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="bg-primary">
                    <tr><th>No</th><th>Tanggal</th><th>Backlog</th><th>Masalah</th><th>Priority</th><th>Status</th><th>PIC Vendor</th><th>PIC Planner</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($reconsults as $i => $rec)
                    <tr>
                        <td>{{ $reconsults->firstItem() + $i }}</td>
                        <td><small>{{ $rec->tanggal_konsultasi ? \Carbon\Carbon::parse($rec->tanggal_konsultasi)->format('d/m/Y') : '-' }}</small></td>
                        <td><small>{{ $rec->backlog->unit_number ?? '-' }} - {{ $rec->backlog->part_number ?? '-' }}</small></td>
                        <td><small>{{ \Str::limit($rec->masalah, 50) }}</small></td>
                        <td>
                            @php $pb = match($rec->priority) { 'high'=>'danger','medium'=>'warning','low'=>'info',default=>'secondary' }; @endphp
                            <span class="badge badge-{{ $pb }}">{{ ucfirst($rec->priority) }}</span>
                        </td>
                        <td>
                            @php $sb = match($rec->status) { 'open'=>'warning','in_progress'=>'info','resolved'=>'success','closed'=>'secondary',default=>'secondary' }; @endphp
                            <span class="badge badge-{{ $sb }}">{{ ucfirst(str_replace('_',' ',$rec->status)) }}</span>
                        </td>
                        <td><small>{{ $rec->vendorPic->name ?? '-' }}</small></td>
                        <td><small>{{ $rec->plannerPic->name ?? '-' }}</small></td>
                        <td>
                            <a href="{{ route('reconsult.show', $rec->id) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('reconsult.edit', $rec->id) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('reconsult.destroy', $rec->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted"><i class="fas fa-inbox"></i> Belum ada data konsultasi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">{{ $reconsults->withQueryString()->links() }}</div>
</div>
@endsection