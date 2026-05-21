@extends('layouts.app')
@section('title', 'Tambah Archive')
@section('page-title', 'Tambah Archive')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('archive.index') }}">Archive</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah Archive</h3></div>
    <form action="{{ route('archive.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal Archive <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_archive" class="form-control" value="{{ old('tanggal_archive', date('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Periode <span class="text-danger">*</span></label>
                        <input type="month" name="periode" class="form-control" value="{{ old('periode') }}" required>
                        <small class="text-muted">Format: YYYY-MM (contoh: 2026-02)</small>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
            <a href="{{ route('archive.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
        </div>
    </form>
</div>
@endsection