@extends('layouts.app')
@section('title', 'Data Backlog')
@section('page-title', 'Data Backlog')
@section('breadcrumb')
    <li class="breadcrumb-item active">Data Backlog</li>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('backlog.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Backlog
        </a>
        <a href="{{ route('backlog.export-excel') }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importModal">
            <i class="fas fa-file-import"></i> Import Excel
        </button>
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
        <form method="GET" action="{{ route('backlog.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Unit/Part Number/Name" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua</option>
                            <option value="overdue" {{ request('status')=='overdue'?'selected':'' }}>Pending</option>
                            <option value="in_progress" {{ request('status')=='in_progress'?'selected':'' }}>In Progress</option>
                            <option value="complete" {{ request('status')=='complete'?'selected':'' }}>Complete</option>
                            <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
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
                        <label>Vendor</label>
                        <select name="vendor" class="form-control">
                            <option value="">Semua</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->name }}" {{ request('vendor')==$vendor->name?'selected':'' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <div class="form-group w-100">
                        <button type="submit" class="btn btn-info btn-block"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <div class="form-group w-100">
                        <a href="{{ route('backlog.index') }}" class="btn btn-secondary btn-block"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-clipboard-list mr-1"></i> Data Backlog</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="bg-primary">
                    <tr>
                        <th>No</th>
                        <th>Unit Number</th>
                        <th>Model</th>
                        <th>Part Number</th>
                        <th>Part Name</th>
                        <th>Qty</th>
                        <th>Vendor</th>
                        <th>ETA</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backlogs as $i => $backlog)
                    <tr>
                        <td>{{ $backlogs->firstItem() + $i }}</td>
                        <td>{{ $backlog->unit_number }}</td>
                        <td>{{ $backlog->model }}</td>
                        <td><small>{{ $backlog->part_number }}</small></td>
                        <td><small>{{ $backlog->part_name }}</small></td>
                        <td>{{ $backlog->qty }} {{ $backlog->satuan }}</td>
                        <td><small>{{ $backlog->vendor }}</small></td>
                        <td><small>{{ $backlog->eta ? \Carbon\Carbon::parse($backlog->eta)->format('d/m/Y') : '-' }}</small></td>
                        <td>
                            @php
                                $priorityBadge = match($backlog->priority) {
                                    'high' => 'danger',
                                    'medium' => 'warning',
                                    'low' => 'info',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge badge-{{ $priorityBadge }}">{{ ucfirst($backlog->priority ?? '-') }}</span>
                        </td>
                        <td>
                            @php
                                $statusBadge = match($backlog->status) {
                                    'overdue' => 'warning',
                                    'in_progress' => 'info',
                                    'complete' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge badge-{{ $statusBadge }}">{{ ucfirst(str_replace('_',' ',$backlog->status)) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('backlog.show', $backlog->id) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('backlog.edit', $backlog->id) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('backlog.destroy', $backlog->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted"><i class="fas fa-inbox"></i> Belum ada data backlog</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $backlogs->withQueryString()->links() }}
    </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Excel</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('backlog.import-excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih File Excel</label>
                        <input type="file" name="file" class="form-control-file" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">Format: .xlsx, .xls, .csv (Max 5MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload mr-1"></i> Import</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection