@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>

<script>
$(document).on('change', '.eta-input', function() {
    var id = $(this).data('id');
    var eta = $(this).val();
    $.ajax({
        url: '/backlog-daily/' + id + '/update-eta',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}', eta: eta },
        success: function(response) {
            if (response.success) {
                toastr.success('ETA berhasil disimpan!');
            }
        },
        error: function() { alert('Gagal update ETA!'); }
    });
});

$(document).on('change', '.posisi-input', function() {
    var id = $(this).data('id');
    var posisi = $(this).val();
    $.ajax({
        url: '/backlog-daily/' + id + '/update-posisi',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}', posisi: posisi },
        success: function(response) {
            if (response.success) {
                toastr.success('Posisi berhasil disimpan!');
            }
        },
        error: function() { alert('Gagal update Posisi!'); }
    });
});
</script>

@endsection

@push('styles')
<style>
    body, .content-wrapper { background: #f4f6f9 !important; }

    /* ===== WELCOME BANNER ===== */
    .welcome-banner {
        border-radius: 14px;
        background: linear-gradient(135deg, #1A202C 0%, #2D3748 100%);
        color: #fff;
        padding: 18px 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(26,32,44,0.25);
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -20px; right: -20px;
        width: 120px; height: 120px;
        background: rgba(255,215,0,0.08);
        border-radius: 50%;
    }
    .welcome-banner .bg-icon {
        position: absolute;
        right: 24px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4rem;
        color: #FFD700;
        opacity: 0.15;
    }
    .welcome-banner h5 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 4px;
        color: #FFD700;
    }
    .welcome-banner small {
        font-size: 13px;
        color: #A0AEC0;
    }
    .welcome-banner small strong { color: #FFD700; }

    /* ===== STAT CARDS — OPSI E ===== */
    .stat-card {
        border-radius: 14px;
        padding: 20px 22px;
        position: relative;
        overflow: hidden;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        height: 100%;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .stat-card .stat-icon {
        position: absolute;
        right: 16px;
        top: 16px;
        font-size: 2.5rem;
        opacity: 0.15;
    }
    .stat-card .stat-number {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 2px;
        line-height: 1;
    }
    .stat-card .stat-label {
        font-size: 13px;
        font-weight: 600;
        opacity: 0.75;
        margin-bottom: 10px;
        letter-spacing: 0.3px;
    }
    .stat-card .stat-footer {
        font-size: 12px;
        font-weight: 600;
        border-top: 1px solid rgba(0,0,0,0.08);
        padding-top: 8px;
    }

    /* Card 1 — Total Backlog: KUNING */
    .stat-card.bg-total {
        background: #FFD700;
        color: #1A202C;
    }
    .stat-card.bg-total .stat-icon { color: #1A202C; }
    .stat-card.bg-total .stat-footer { border-top-color: rgba(26,32,44,0.12); }
    .stat-card.bg-total .stat-footer a { color: #1A202C !important; opacity: 0.7; }
    .stat-card.bg-total .stat-footer a:hover { opacity: 1; text-decoration: none; }

    /* Card 2 — Complete: NAVY */
    .stat-card.bg-complete {
        background: #1A202C;
        color: #FFD700;
    }
    .stat-card.bg-complete .stat-icon { color: #FFD700; }
    .stat-card.bg-complete .stat-label { color: #A0AEC0; opacity: 1; }
    .stat-card.bg-complete .stat-footer { border-top-color: rgba(255,215,0,0.15); }
    .stat-card.bg-complete .stat-footer a { color: #FFD700 !important; opacity: 0.7; }
    .stat-card.bg-complete .stat-footer a:hover { opacity: 1; text-decoration: none; }

    /* Card 3 — Overdue: PUTIH + merah */
    .stat-card.bg-overdue {
        background: #FFFFFF;
        color: #1A202C;
        border: 1.5px solid #FED7D7 !important;
        box-shadow: 0 4px 15px rgba(229,62,62,0.1) !important;
    }
    .stat-card.bg-overdue .stat-number { color: #E53E3E; }
    .stat-card.bg-overdue .stat-label { color: #718096; opacity: 1; }
    .stat-card.bg-overdue .stat-icon { color: #E53E3E; }
    .stat-card.bg-overdue .stat-footer { border-top-color: #FED7D7; }
    .stat-card.bg-overdue .stat-footer a { color: #E53E3E !important; font-weight: 700; }
    .stat-card.bg-overdue .stat-footer a:hover { text-decoration: none; }

    /* Card 4 — Overdue: ABU GELAP */
    .stat-card.bg-progress {
        background: #F7FAFC;
        color: #1A202C;
        border: 1.5px solid #E2E8F0 !important;
    }
    .stat-card.bg-progress .stat-number { color: #4A5568; }
    .stat-card.bg-progress .stat-label { color: #718096; opacity: 1; }
    .stat-card.bg-progress .stat-icon { color: #4A5568; }
    .stat-card.bg-progress .stat-footer { border-top-color: #E2E8F0; }
    .stat-card.bg-progress .stat-footer a { color: #4A5568 !important; font-weight: 700; }
    .stat-card.bg-progress .stat-footer a:hover { color: #1A202C !important; text-decoration: none; }

    /* ===== CARD ===== */
    .card {
        border-radius: 14px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        background: #fff;
    }
    .card .card-header {
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
        border-radius: 14px 14px 0 0 !important;
        padding: 14px 20px;
    }

    /* ===== TABLE ===== */
    .table-vendor-dash thead th {
        background: #1A202C;
        color: #FFD700;
        font-size: 12px;
        font-weight: 700;
        padding: 10px 8px;
        border: none;
        white-space: nowrap;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .table-vendor-dash tbody td {
        font-size: 12px;
        padding: 8px;
        vertical-align: middle;
        border-bottom: 1px solid #f5f5f5;
    }
    .table-vendor-dash tbody tr:hover { background: #FFFFF0; }

    /* ===== FILTER ===== */
    .filter-control {
        border-radius: 8px;
        border: 1.5px solid #E2E8F0;
        font-size: 13px;
        height: 36px;
        background: #fff;
        transition: border-color 0.2s;
    }
    .filter-control:focus {
        border-color: #E6C200 !important;
        box-shadow: 0 0 0 3px rgba(255,215,0,0.15) !important;
        outline: none;
    }

    /* ===== PAGINATION ===== */
    .pagination .page-item .page-link {
        border-radius: 6px !important;
        margin: 0 2px;
        font-size: 12px;
        padding: 5px 10px;
        color: #1A202C;
        border-color: #E2E8F0;
        font-weight: 600;
    }
    .pagination .page-item .page-link:hover {
        background: #FFD700;
        border-color: #E6C200;
        color: #1A202C;
    }
    .pagination .page-item.active .page-link {
        background: #1A202C;
        border-color: #1A202C;
        color: #FFD700;
    }

    /* ===== SECTION TITLE ===== */
    .section-title {
        font-size: 13px;
        font-weight: 700;
        color: #546e7a;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 12px;
        padding-left: 4px;
    }
</style>
@endpush

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-banner mb-3">
    <div class="bg-icon"><i class="fas fa-truck"></i></div>
    <h5><i class="fas fa-truck mr-2"></i> Selamat datang, {{ Auth::user()->name }}!</h5>
    <small>Anda login sebagai <strong>Vendor</strong> — {{ now()->format('l, d F Y') }}</small>
</div>

{{-- 3 Kartu Statistik --}}
<div class="row mb-3">
    <div class="col-lg-4 col-6 mb-3">
        <div class="stat-card bg-total">
            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-number">{{ $totalBacklog }}</div>
            <div class="stat-label">Total Backlog</div>
            <div class="stat-footer">
                <a href="{{ route('dashboard') }}?status=">
                    <i class="fas fa-arrow-circle-right mr-1"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6 mb-3">
        <div class="stat-card bg-complete">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $totalComplete }}</div>
            <div class="stat-label">Complete</div>
            <div class="stat-footer">
                <a href="{{ route('dashboard') }}?status=complete">
                    <i class="fas fa-arrow-circle-right mr-1"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6 mb-3">
        <div class="stat-card bg-outstanding">
            <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="stat-number">{{ $totalOutstanding }}</div>
            <div class="stat-label">Outstanding</div>
            <div class="stat-footer">
                <a href="{{ route('dashboard') }}?status=outstanding">
                    <i class="fas fa-arrow-circle-right mr-1"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Data Backlog --}}
<p class="section-title"><i class="fas fa-table mr-2"></i>Data Backlog Terkini</p>
<div class="card" id="monitoring">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-table mr-2" style="color:#FFD700;"></i> Data Backlog Terkini</h6>
            <div>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalImport" style="border-radius:8px;font-size:12px;"><i class="fas fa-file-excel mr-1"></i> Import Excel</button>
                <div class="dropdown d-inline-block">
                    <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-toggle="dropdown" style="border-radius:8px;font-size:12px;"><i class="fas fa-download mr-1"></i> Export Excel</button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('dashboard.export-excel') }}?type=all"><i class="fas fa-table mr-1"></i> Export Semua Data</a>
                        <a class="dropdown-item" href="{{ route('dashboard.export-excel') }}?type=outstanding"><i class="fas fa-exclamation-circle mr-1"></i> Export Outstanding</a>
                        <a class="dropdown-item" href="{{ route('dashboard.export-excel') }}?type=overdue"><i class="fas fa-clock mr-1"></i> Export Overdue</a>
                    </div>
                </div>
                
                <a href="{{ route('backlog-daily.index') }}" class="btn btn-sm" style="border-radius:8px;font-size:12px;background:#1A202C;color:#FFD700;font-weight:700;"><i class="fas fa-eye mr-1"></i> Semua</a>
            </div>
        </div>
    </div>
    <div class="card-body">

        {{-- Filter --}}
        <form method="GET" action="{{ route('dashboard') }}" class="mb-3" id="filter-form-vendor">
            <div class="d-flex align-items-center" style="gap:8px;">
                <div class="dropdown">
                    <button class="btn btn-dark" type="button" onclick="toggleFilterVendor()" style="border-radius:8px;font-size:13px;background:#1A202C;border-color:#1A202C;">
                        <i class="fas fa-filter mr-1"></i> Filter
                        @if(request('unit') || request('status') || request('reservation'))
                            <span class="badge badge-warning ml-1" style="font-size:10px;">Aktif</span>
                        @endif
                    </button>
                    <div id="filter-dropdown-vendor" style="min-width:280px;border-radius:8px;display:none;position:fixed;z-index:99999;background:#fff;border:1px solid #e0e0e0;padding:16px;box-shadow:0 4px 20px rgba(0,0,0,0.15);">
                        <p class="mb-2" style="font-weight:600;font-size:13px;">Filter Data</p>
                        <div class="mb-2">
                            <label style="font-size:12px;color:#666;">Unit</label>
                            <select name="unit" class="form-control form-control-sm">
                                <option value="">-- Semua Unit --</option>
                                @foreach($unitList as $unit)
                                    <option value="{{ $unit }}" {{ request('unit') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label style="font-size:12px;color:#666;">Status</label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="">-- Semua Status --</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>Complete</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label style="font-size:12px;color:#666;">Reservation</label>
                            <input type="text" name="reservation" class="form-control form-control-sm" placeholder="No. reservation..." value="{{ request('reservation') }}">
                        </div>
                        <div class="d-flex" style="gap:6px;">
                            <button type="submit" class="btn btn-dark btn-sm flex-fill" style="border-radius:6px;background:#1A202C;">Terapkan</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm flex-fill" style="border-radius:6px;">Reset</a>
                        </div>
                    </div>
                </div>
                <div style="max-width:300px;flex:1;">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Cari deskripsi, part number..." value="{{ request('search') }}" autocomplete="off" style="border-radius:8px 0 0 8px;font-size:13px;">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-dark" style="border-radius:0 8px 8px 0;background:#1A202C;"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script>function toggleFilterVendor(){var d=document.getElementById("filter-dropdown-vendor");var btn=event.currentTarget;var rect=btn.getBoundingClientRect();if(d.style.display==="none"){d.style.top=(rect.bottom+window.scrollY+4)+"px";d.style.left=rect.left+"px";d.style.display="block";}else{d.style.display="none";}}</script>

        <div class="table-responsive">
            <table class="table table-vendor-dash table-hover" id="backlogTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Reservation</th>
                        <th>PN</th>
                        <th>NPN</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Backlog Desc</th>
                        <th>Plan Date</th>
                        <th>Tgl BCS</th>
                        <th>Remarks</th>
                        <th>ETA</th>
                        <th>Posisi Part</th>
                        <th>Ref. Dokumen</th>
                        <th>Material</th>
                        <th>Qty ZBOH</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backlogMonitoring as $index => $item)
                    <tr>
                        <td>{{ $backlogMonitoring->firstItem() + $index }}</td>
                        <td>
                            <span style="font-weight: 600; font-size: 12px;">{{ $item->reservation_number ?? '-' }}</span><br>
                            <small class="text-muted">Item: {{ $item->reservation_item ?? '-' }}</small>
                        </td>
                        <td>{{ $item->part_number ?? '-' }}</td>
                        <td>{{ $item->npn ?? '-' }}</td>
                        <td>{{ $item->qty ?? '-' }}</td>
                        <td>
                            @if($item->unit)
                                <span class="badge badge-info" style="border-radius: 20px; font-size: 11px; padding: 4px 8px;">
                                    {{ $item->unit }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $item->mo_description ?? '-' }}</td>
                        <td>{{ $item->plan_date ? \Carbon\Carbon::parse($item->plan_date)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $item->tanggal_bcs ? \Carbon\Carbon::parse($item->tanggal_bcs)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $item->remarks ?? '-' }}</td>
                        <td>
    @if($item->eta)
        <input type="date" class="form-control form-control-sm eta-input" data-id="{{ $item->id }}" value="{{ \Carbon\Carbon::parse($item->eta)->format('Y-m-d') }}" style="width:130px; border-radius:6px; font-size:11px;">
    @else
        <input type="date" class="form-control form-control-sm eta-input" data-id="{{ $item->id }}" style="width:130px; border-radius:6px; font-size:11px;">
    @endif
</td>
                        <td><input type="text" class="form-control form-control-sm posisi-input" data-id="{{ $item->id }}" value="{{ $item->posisi ?? '' }}" placeholder="Posisi part..." style="width:150px; border-radius:6px; font-size:11px;"></td>
                        <td>{{ $item->referensi_dokumen ?? '-' }}</td>
                        <td>{{ $item->material ?? '-' }}</td>
                        <td>{{ $item->qty_zboh ?? '-' }}</td>
                        <td>
                            @if($item->status == 'complete')
                                <span class="badge badge-success" style="border-radius: 20px; padding: 5px 10px;">Complete</span>
                            @elseif($item->status == 'overdue')
                                <span class="badge badge-danger" style="border-radius: 20px; padding: 5px 10px;">Overdue</span>
                            @elseif($item->status == 'outstanding')
                                <span class="badge badge-warning" style="border-radius: 20px; padding: 5px 10px;">Outstanding</span>
                            @else
                                <span class="badge badge-secondary" style="border-radius: 20px; padding: 5px 10px;">{{ $item->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity: 0.3;"></i>
                            Tidak ada data backlog
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $backlogMonitoring->links() }}
        </div>

    </div>
</div>


<script>
$(document).on('change', '.eta-input', function() {
    var id = $(this).data('id');
    var eta = $(this).val();
    $.ajax({
        url: '/backlog-daily/' + id + '/update-eta',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}', eta: eta },
        success: function(response) {
            if (response.success) {
                toastr.success('ETA berhasil disimpan!');
            }
        },
        error: function() { alert('Gagal update ETA!'); }
    });
});

$(document).on('change', '.posisi-input', function() {
    var id = $(this).data('id');
    var posisi = $(this).val();
    $.ajax({
        url: '/backlog-daily/' + id + '/update-posisi',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}', posisi: posisi },
        success: function(response) {
            if (response.success) {
                toastr.success('Posisi berhasil disimpan!');
            }
        },
        error: function() { alert('Gagal update Posisi!'); }
    });
});
</script>

{{-- Modal Import --}}
<div class="modal fade" id="modalImport" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 14px;">
            <div class="modal-header bg-primary text-white" style="border-radius: 14px 14px 0 0;">
                <h5 class="modal-title"><i class="fas fa-file-excel mr-2"></i> Import Excel BCS</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('backlog-daily.import-excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600;">Tanggal BCS</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required style="border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600;">File Excel BCS (.xlsx / .xls)</label>
                        <input type="file" name="file" class="form-control-file" accept=".xlsx,.xls" required>
                        <small class="text-muted">Format: file BCS dari SM. Maks 5MB.</small>
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600;">File ZBOH (.xlsx / .xls)</label>
                        <input type="file" name="file_zboh" class="form-control-file" accept=".xlsx,.xls">
                        <small class="text-muted">Upload file ZBOH untuk auto-fill Remarks dan Qty ZBOH.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px;"><i class="fas fa-upload mr-1"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Modal Hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 14px;">
            <div class="modal-header bg-danger text-white" style="border-radius: 14px 14px 0 0;">
                <h5 class="modal-title"><i class="fas fa-trash mr-2"></i> Hapus Data Import</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Masukkan tanggal import yang ingin dihapus. Data <strong>Complete</strong> tidak akan terhapus.
                </div>
                <div class="form-group">
                    <label style="font-size: 13px; font-weight: 600;"><i class="fas fa-calendar mr-1"></i> Pilih Tanggal Import:</label>
                    <input type="date" id="hapus-tanggal-input" class="form-control" style="border-radius: 8px;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <button type="button" class="btn btn-danger" id="btn-hapus-data" style="border-radius: 8px;">
                    <i class="fas fa-trash mr-1"></i> Hapus Data
                </button>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('btn-hapus-data').addEventListener('click', function() {
    var tgl = document.getElementById('hapus-tanggal-input').value;
    if (!tgl) { alert('Pilih tanggal dulu!'); return; }
    if (!confirm('Yakin hapus data tanggal ' + tgl + '?')) return;
    fetch('/dashboard/hapus-data', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')},
        body: JSON.stringify({tanggal: tgl})
    }).then(r => r.json()).then(d => { alert(d.message); location.reload(); });
});
</script>
@endsection