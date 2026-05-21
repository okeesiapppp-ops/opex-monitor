@extends('layouts.app')
@section('title', 'Detail User')
@section('page-title', 'Detail User')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User Management</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Detail User</h3>
        <div class="card-tools">
            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit mr-1"></i> Edit</a>
            <a href="{{ route('user.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th width="40%">Nama</th><td>{{ $user->name }}</td></tr>
                    <tr><th>Email</th><td>{{ $user->email }}</td></tr>
                    <tr><th>Role</th><td>
                        @php $roleBadge = match($user->role) { 'admin'=>'danger','vendor'=>'info','planner'=>'success',default=>'secondary' }; @endphp
                        <span class="badge badge-{{ $roleBadge }}">{{ ucfirst($user->role) }}</span>
                    </td></tr>
                    <tr><th>Phone</th><td>{{ $user->phone ?? '-' }}</td></tr>
                    <tr><th>Perusahaan</th><td>{{ $user->company ?? '-' }}</td></tr>
                    <tr><th>Status</th><td>
                        <span class="badge badge-{{ ($user->status ?? 'active') == 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($user->status ?? 'active') }}
                        </span>
                    </td></tr>
                    <tr><th>Dibuat</th><td>{{ $user->created_at->format('d/m/Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection