@extends('layouts.app')
@section('title', 'Part Deviation Report')
@section('page-title', 'Part Deviation Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('report.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Part Deviation</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter</h3></div>
    <div class="card-body">
        <form method="GET">
            <div class="row">
                <div class="col-md-3"><div class="form-group"><label>Start Date</label><input type="date" name="start_date" class="form-control" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"></div></div>
                <div class="col-md-3"><div class="form-group"><label>End Date</label><input type="date" name="end_date" class="form-control" value="{{ request('end_date', now()->format('Y-m-d')) }}"></div></div>
                <div class="col-md-2 d-flex align-items-end"><div class="form-group w-100"><button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Filter</button></div></div>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-exclamation-triangle mr-1"></i> Part Deviation</h3></div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead class="bg-primary"><tr><th>No</th><th>Part Number</th><th>Part Name</th><th>Jumlah Mismatch</th><th>Total Selisih</th><th>Vendor</th></tr></thead>
            <tbody>
                @forelse($deviations as $i => $dev)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $dev['part_number'] }}</td>
                    <td>{{ $dev['part_name'] }}</td>
                    <td><span class="badge badge-danger">{{ $dev['count'] }}</span></td>
                    <td>{{ $dev['total_difference'] }}</td>
                    <td><small>{{ $dev['vendors'] }}</small></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">Tidak ada data deviasi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection