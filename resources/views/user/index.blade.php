@extends('layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')
@section('breadcrumb')
    <li class="breadcrumb-item active">User Management</li>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('user.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah User</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter</h3>
        <div class="card-tools"><button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button></div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('user.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Nama/Email/Perusahaan" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control">
                            <option value="">Semua</option>
                            <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                            <option value="vendor" {{ request('role')=='vendor'?'selected':'' }}>Vendor</option>
                            <option value="planner" {{ request('role')=='planner'?'selected':'' }}>Planner</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua</option>
                            <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
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
                        <a href="{{ route('user.index') }}" class="btn btn-secondary btn-block"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-users mr-1"></i> Data User</h3></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="bg-primary">
                    <tr><th>No</th><th>Nama</th><th>Email</th><th>Role</th><th>Perusahaan</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $i }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $roleBadge = match($user->role) { 'admin'=>'danger','vendor'=>'info','planner'=>'success',default=>'secondary' };
                            @endphp
                            <span class="badge badge-{{ $roleBadge }}">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td>{{ $user->company ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ ($user->status ?? 'active') == 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($user->status ?? 'active') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('user.show', $user->id) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus user ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted"><i class="fas fa-inbox"></i> Belum ada data user</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">{{ $users->withQueryString()->links() }}</div>
</div>
@endsection