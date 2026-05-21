<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - OPEX Monitor | UTA BASIS</title>

    <!-- Google Font: Plus Jakarta Sans (clean, modern, professional) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* ===== UT BRAND DESIGN SYSTEM ===== */
        :root {
            --ut-yellow:      #FFD700;
            --ut-yellow-dark: #E6C200;
            --ut-yellow-light:#FFF3B0;
            --ut-navy:        #1A202C;
            --ut-navy-mid:    #2D3748;
            --ut-navy-light:  #4A5568;
            --ut-bg:          #F7FAFC;
            --ut-white:       #FFFFFF;
            --ut-border:      #E2E8F0;
            --ut-text:        #2D3748;
            --ut-text-muted:  #718096;
            --ut-success:     #38A169;
            --ut-danger:      #E53E3E;
            --ut-warning:     #DD6B20;
            --ut-info:        #3182CE;
            --shadow-sm:      0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
            --shadow-md:      0 4px 6px rgba(0,0,0,.07), 0 2px 4px rgba(0,0,0,.06);
            --shadow-lg:      0 10px 25px rgba(0,0,0,.1), 0 4px 10px rgba(0,0,0,.06);
            --radius:         10px;
            --radius-sm:      6px;
            --radius-lg:      16px;
            --transition:     all .2s cubic-bezier(.4,0,.2,1);
        }

        /* ===== BASE ===== */
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background-color: var(--ut-bg) !important;
            color: var(--ut-text) !important;
            font-size: 14px;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== CONTENT WRAPPER ===== */
        .content-wrapper {
            background-color: var(--ut-bg) !important;
            min-height: calc(100vh - 57px - 57px) !important;
        }

        /* ===== CONTENT HEADER ===== */
        .content-header {
            padding: 20px 24px 10px !important;
            background: var(--ut-white);
            border-bottom: 1px solid var(--ut-border);
            box-shadow: var(--shadow-sm);
        }
        .content-header h1 {
            font-size: 1.35rem !important;
            font-weight: 700 !important;
            color: var(--ut-navy) !important;
            letter-spacing: -0.3px;
        }

        /* ===== BREADCRUMB ===== */
        .breadcrumb {
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .breadcrumb-item a {
            color: var(--ut-yellow-dark) !important;
            font-weight: 500;
            text-decoration: none;
        }
        .breadcrumb-item.active {
            color: var(--ut-text-muted) !important;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            color: var(--ut-border) !important;
        }

        /* ===== SECTION CONTENT ===== */
        .content { padding: 20px 24px !important; }

        /* ===== CARDS ===== */
        .card {
            border: 1px solid var(--ut-border) !important;
            border-radius: var(--radius) !important;
            box-shadow: var(--shadow-sm) !important;
            background: var(--ut-white) !important;
            transition: var(--transition);
            overflow: hidden;
        }
        .card:hover { box-shadow: var(--shadow-md) !important; }
        .card-header {
            background: var(--ut-white) !important;
            border-bottom: 1px solid var(--ut-border) !important;
            padding: 16px 20px !important;
            font-weight: 700 !important;
            font-size: .9rem !important;
            color: var(--ut-navy) !important;
            letter-spacing: .2px;
        }
        .card-header .card-title {
            font-weight: 700 !important;
            color: var(--ut-navy) !important;
            font-size: .95rem !important;
        }
        .card-body { padding: 20px !important; }

        /* Card Yellow Accent */
        .card-header.bg-warning,
        .card-header.bg-yellow {
            background: linear-gradient(135deg, var(--ut-yellow) 0%, var(--ut-yellow-dark) 100%) !important;
            color: var(--ut-navy) !important;
            border-bottom: none !important;
        }

        /* ===== SMALL STAT BOXES (AdminLTE) ===== */
        .small-box {
            border-radius: var(--radius) !important;
            box-shadow: var(--shadow-md) !important;
            overflow: hidden;
            transition: var(--transition);
        }
        .small-box:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg) !important;
        }
        .small-box > .inner { padding: 16px 20px !important; }
        .small-box > .small-box-footer {
            font-size: 12px !important;
            font-weight: 600 !important;
            letter-spacing: .5px;
        }
        .small-box.bg-info { background: linear-gradient(135deg, var(--ut-info) 0%, #2B6CB0 100%) !important; }
        .small-box.bg-success { background: linear-gradient(135deg, var(--ut-success) 0%, #276749 100%) !important; }
        .small-box.bg-warning { background: linear-gradient(135deg, var(--ut-yellow) 0%, var(--ut-yellow-dark) 100%) !important; color: var(--ut-navy) !important; }
        .small-box.bg-warning * { color: var(--ut-navy) !important; }
        .small-box.bg-danger { background: linear-gradient(135deg, var(--ut-danger) 0%, #C53030 100%) !important; }

        /* ===== BUTTONS ===== */
        .btn {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 600 !important;
            font-size: 13px !important;
            border-radius: var(--radius-sm) !important;
            transition: var(--transition) !important;
            letter-spacing: .2px;
        }
        .btn-primary {
            background: var(--ut-navy) !important;
            border-color: var(--ut-navy) !important;
            color: var(--ut-yellow) !important;
        }
        .btn-primary:hover {
            background: var(--ut-navy-mid) !important;
            border-color: var(--ut-navy-mid) !important;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md) !important;
        }
        .btn-warning {
            background: var(--ut-yellow) !important;
            border-color: var(--ut-yellow-dark) !important;
            color: var(--ut-navy) !important;
            font-weight: 700 !important;
        }
        .btn-warning:hover {
            background: var(--ut-yellow-dark) !important;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md) !important;
        }
        .btn-success {
            background: var(--ut-success) !important;
            border-color: var(--ut-success) !important;
        }
        .btn-success:hover { filter: brightness(1.1); transform: translateY(-1px); }
        .btn-danger {
            background: var(--ut-danger) !important;
            border-color: var(--ut-danger) !important;
        }
        .btn-danger:hover { filter: brightness(1.1); transform: translateY(-1px); }
        .btn-secondary {
            background: var(--ut-bg) !important;
            border-color: var(--ut-border) !important;
            color: var(--ut-text) !important;
        }
        .btn-info {
            background: var(--ut-info) !important;
            border-color: var(--ut-info) !important;
        }

        /* ===== TABLES ===== */
        .table {
            font-size: 13px !important;
            color: var(--ut-text) !important;
        }
        .table thead th {
            background: var(--ut-navy) !important;
            color: var(--ut-yellow) !important;
            font-weight: 700 !important;
            font-size: 11.5px !important;
            letter-spacing: .8px !important;
            text-transform: uppercase !important;
            border: none !important;
            padding: 12px 14px !important;
        }
        .table tbody tr {
            transition: var(--transition);
        }
        .table tbody tr:hover { background-color: var(--ut-yellow-light) !important; }
        .table tbody td {
            border-color: var(--ut-border) !important;
            padding: 11px 14px !important;
            vertical-align: middle !important;
        }
        .table-striped tbody tr:nth-of-type(even) { background-color: #F9FAFB !important; }

        /* ===== BADGES ===== */
        .badge {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 700 !important;
            font-size: 10.5px !important;
            letter-spacing: .4px;
            padding: 4px 9px !important;
            border-radius: 20px !important;
        }
        .badge-success { background: #C6F6D5 !important; color: #276749 !important; }
        .badge-danger  { background: #FED7D7 !important; color: #9B2C2C !important; }
        .badge-warning { background: var(--ut-yellow-light) !important; color: #7B5E00 !important; }
        .badge-info    { background: #BEE3F8 !important; color: #2A4365 !important; }
        .badge-primary { background: var(--ut-navy) !important; color: var(--ut-yellow) !important; }
        .badge-secondary { background: #EDF2F7 !important; color: var(--ut-text-muted) !important; }

        /* ===== FORMS ===== */
        .form-control {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            border: 1.5px solid var(--ut-border) !important;
            border-radius: var(--radius-sm) !important;
            font-size: 13.5px !important;
            color: var(--ut-text) !important;
            transition: var(--transition) !important;
            background: var(--ut-white) !important;
            height: 38px !important;
        }
        .form-control:focus {
            border-color: var(--ut-yellow-dark) !important;
            box-shadow: 0 0 0 3px rgba(255,215,0,.2) !important;
            outline: none !important;
        }
        textarea.form-control { height: auto !important; }
        .form-label, label {
            font-weight: 600 !important;
            font-size: 13px !important;
            color: var(--ut-navy) !important;
            margin-bottom: 5px !important;
        }
        .input-group-text {
            background: var(--ut-bg) !important;
            border: 1.5px solid var(--ut-border) !important;
            color: var(--ut-text-muted) !important;
        }

        /* ===== ALERTS ===== */
        .alert {
            border-radius: var(--radius-sm) !important;
            border: none !important;
            font-size: 13.5px !important;
            font-weight: 500 !important;
        }
        .alert-success { background: #C6F6D5 !important; color: #276749 !important; }
        .alert-danger  { background: #FED7D7 !important; color: #9B2C2C !important; }
        .alert-warning { background: var(--ut-yellow-light) !important; color: #7B5E00 !important; }
        .alert-info    { background: #BEE3F8 !important; color: #2A4365 !important; }

        /* ===== PAGINATION ===== */
        .pagination { flex-wrap: wrap; }
        .pagination .page-link {
            font-size: 13px;
            padding: 5px 12px;
            border-color: var(--ut-border) !important;
            color: var(--ut-navy) !important;
            font-weight: 600;
            border-radius: var(--radius-sm) !important;
            margin: 0 2px;
            transition: var(--transition);
        }
        .pagination .page-link:hover {
            background: var(--ut-yellow) !important;
            border-color: var(--ut-yellow-dark) !important;
            color: var(--ut-navy) !important;
        }
        .pagination .page-item.active .page-link {
            background: var(--ut-navy) !important;
            border-color: var(--ut-navy) !important;
            color: var(--ut-yellow) !important;
        }
        nav[aria-label="pagination"] svg { width: 12px; height: 12px; }

        /* ===== DATATABLES ===== */
        .dataTables_wrapper .dataTables_filter input {
            border: 1.5px solid var(--ut-border) !important;
            border-radius: var(--radius-sm) !important;
            padding: 5px 10px !important;
            font-size: 13px !important;
            transition: var(--transition);
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--ut-yellow-dark) !important;
            box-shadow: 0 0 0 3px rgba(255,215,0,.2) !important;
            outline: none;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1.5px solid var(--ut-border) !important;
            border-radius: var(--radius-sm) !important;
            padding: 4px 8px !important;
        }
        .dataTables_wrapper .dataTables_info { font-size: 12.5px !important; color: var(--ut-text-muted) !important; }

        /* ===== MODAL ===== */
        .modal-content {
            border: none !important;
            border-radius: var(--radius-lg) !important;
            box-shadow: var(--shadow-lg) !important;
        }
        .modal-header {
            background: var(--ut-navy) !important;
            color: var(--ut-yellow) !important;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0 !important;
            padding: 18px 24px !important;
        }
        .modal-title { font-weight: 700 !important; }
        .modal-header .close { color: var(--ut-yellow) !important; opacity: 1 !important; }

        /* ===== PROGRESS BAR ===== */
        .progress {
            border-radius: 20px !important;
            background: var(--ut-border) !important;
            height: 10px !important;
        }
        .progress-bar {
            border-radius: 20px !important;
            background: linear-gradient(90deg, var(--ut-yellow) 0%, var(--ut-yellow-dark) 100%) !important;
        }
        .progress-bar.bg-success { background: linear-gradient(90deg, #48BB78, #38A169) !important; }
        .progress-bar.bg-danger  { background: linear-gradient(90deg, #FC8181, #E53E3E) !important; }

        /* ===== SELECT / DROPDOWN ===== */
        select.form-control {
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%234A5568' d='M6 8L1 3h10z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            padding-right: 30px !important;
        }

        /* ===== UTILITY ===== */
        .text-yellow { color: var(--ut-yellow-dark) !important; }
        .bg-ut-yellow { background-color: var(--ut-yellow) !important; }
        .border-yellow { border-color: var(--ut-yellow-dark) !important; }
        .shadow-card { box-shadow: var(--shadow-md) !important; }

        /* ===== INFO BOX ===== */
        .info-box {
            border-radius: var(--radius) !important;
            box-shadow: var(--shadow-sm) !important;
            min-height: 72px !important;
            border: 1px solid var(--ut-border) !important;
        }
        .info-box-icon {
            border-radius: var(--radius) 0 0 var(--radius) !important;
            width: 68px !important;
            font-size: 1.6rem !important;
        }
        .info-box-content { padding: 10px 16px !important; }
        .info-box-text { font-weight: 700 !important; font-size: 12px !important; text-transform: uppercase; letter-spacing: .5px; }
        .info-box-number { font-size: 1.4rem !important; font-weight: 800 !important; }
        .info-box .bg-warning { background: var(--ut-yellow) !important; color: var(--ut-navy) !important; }
        .info-box .bg-info    { background: var(--ut-info) !important; }
        .info-box .bg-success { background: var(--ut-success) !important; }
        .info-box .bg-danger  { background: var(--ut-danger) !important; }
    </style>

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('layouts.navbar')
    @include('layouts.sidebar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    @include('layouts.footer')
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')

@if(session('success'))
<script>
Swal.fire({
    icon: 'success', title: 'Berhasil!',
    text: '{{ session("success") }}',
    toast: true, position: 'top-end',
    showConfirmButton: false, timer: 3000,
    background: '#F0FFF4', color: '#276749',
    iconColor: '#38A169'
});
</script>
@endif
@if(session('error'))
<script>
Swal.fire({
    icon: 'error', title: 'Error!',
    text: '{{ session("error") }}',
    toast: true, position: 'top-end',
    showConfirmButton: false, timer: 3000
});
</script>
@endif
</body>
</html>