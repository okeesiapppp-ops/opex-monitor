@extends('layouts.app')
@section('title', 'Tambah Konsultasi')
@section('page-title', 'Tambah Konsultasi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reconsult.index') }}">Consultation</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah Konsultasi</h3></div>
    <form action="{{ route('reconsult.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Backlog <span class="text-danger">*</span></label>
                        <select name="backlog_id" class="form-control @error('backlog_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Backlog --</option>
                            @foreach($backlogs as $backlog)
                                <option value="{{ $backlog->id }}" {{ old('backlog_id')==$backlog->id?'selected':'' }}>
                                    {{ $backlog->unit_number }} - {{ $backlog->part_number }} - {{ $backlog->part_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('backlog_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Tanggal Konsultasi <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_konsultasi" class="form-control" value="{{ old('tanggal_konsultasi', date('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Priority <span class="text-danger">*</span></label>
                        <select name="priority" class="form-control" required>
                            <option value="high" {{ old('priority')=='high'?'selected':'' }}>High</option>
                            <option value="medium" {{ old('priority','medium')=='medium'?'selected':'' }}>Medium</option>
                            <option value="low" {{ old('priority')=='low'?'selected':'' }}>Low</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>PIC Vendor</label>
                        <select name="pic_vendor" class="form-control">
                            <option value="">-- Pilih PIC Vendor --</option>
                            @foreach($vendorUsers as $user)
                                <option value="{{ $user->id }}" {{ old('pic_vendor')==$user->id?'selected':'' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>PIC Planner</label>
                        <select name="pic_planner" class="form-control">
                            <option value="">-- Pilih PIC Planner --</option>
                            @foreach($plannerUsers as $user)
                                <option value="{{ $user->id }}" {{ old('pic_planner')==$user->id?'selected':'' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Masalah <span class="text-danger">*</span></label>
                        <textarea name="masalah" class="form-control @error('masalah') is-invalid @enderror" rows="4" required>{{ old('masalah') }}</textarea>
                        @error('masalah')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Solusi</label>
                        <textarea name="solusi" class="form-control" rows="4">{{ old('solusi') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
            <a href="{{ route('reconsult.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
        </div>
    </form>
</div>
@endsection