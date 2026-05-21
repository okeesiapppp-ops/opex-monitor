import './bootstrap';

// Import AdminLTE
import 'admin-lte';

// Import jQuery (required by AdminLTE)
import $ from 'jquery';
window.$ = window.jQuery = $;

// Import Bootstrap
import 'bootstrap';

// Import DataTables
import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';

// Import Select2
import 'select2';

// Import SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// Import Chart.js
import Chart from 'chart.js/auto';
window.Chart = Chart;

// Import Moment.js
import moment from 'moment';
window.moment = moment;

/*
|--------------------------------------------------------------------------
| Custom JavaScript for UTA BASIS - OPEX Monitor
|--------------------------------------------------------------------------
*/

$(document).ready(function() {
    
    /*
    |--------------------------------------------------------------------------
    | Initialize DataTables
    |--------------------------------------------------------------------------
    */
    
    if ($('.datatable').length) {
        $('.datatable').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            order: [[0, 'desc']], // Sort by first column descending
            columnDefs: [
                { orderable: false, targets: -1 } // Disable sorting on last column (usually actions)
            ]
        });
    }
    
    // DataTable with custom options
    if ($('.datatable-simple').length) {
        $('.datatable-simple').DataTable({
            responsive: true,
            autoWidth: false,
            searching: false,
            paging: false,
            info: false
        });
    }
    
    /*
    |--------------------------------------------------------------------------
    | Initialize Select2
    |--------------------------------------------------------------------------
    */
    
    if ($('.select2').length) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih...',
            allowClear: true
        });
    }
    
    if ($('.select2-tags').length) {
        $('.select2-tags').select2({
            theme: 'bootstrap-5',
            width: '100%',
            tags: true,
            tokenSeparators: [',']
        });
    }
    
    /*
    |--------------------------------------------------------------------------
    | Confirm Delete with SweetAlert2
    |--------------------------------------------------------------------------
    */
    
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const itemName = $(this).data('name') || 'data ini';
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            html: `Data <strong>${itemName}</strong> akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
    
    /*
    |--------------------------------------------------------------------------
    | Confirm Action (Generic)
    |--------------------------------------------------------------------------
    */
    
    $(document).on('click', '.btn-confirm', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const message = $(this).data('message') || 'Apakah Anda yakin ingin melanjutkan?';
        const confirmText = $(this).data('confirm') || 'Ya, Lanjutkan!';
        
        Swal.fire({
            title: 'Konfirmasi',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmText,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
    
    /*
    |--------------------------------------------------------------------------
    | AJAX Setup with CSRF Token
    |--------------------------------------------------------------------------
    */
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    /*
    |--------------------------------------------------------------------------
    | Loading Overlay for AJAX Requests
    |--------------------------------------------------------------------------
    */
    
    $(document).ajaxStart(function() {
        if ($('.spinner-overlay').length) {
            $('.spinner-overlay').addClass('active');
        }
    }).ajaxStop(function() {
        if ($('.spinner-overlay').length) {
            $('.spinner-overlay').removeClass('active');
        }
    });
    
    /*
    |--------------------------------------------------------------------------
    | AJAX Error Handler
    |--------------------------------------------------------------------------
    */
    
    $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
        console.error('AJAX Error:', thrownError);
        
        let errorMessage = 'Terjadi kesalahan pada server.';
        
        if (jqxhr.status === 401) {
            errorMessage = 'Sesi Anda telah berakhir. Silakan login kembali.';
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
        } else if (jqxhr.status === 403) {
            errorMessage = 'Anda tidak memiliki akses untuk melakukan aksi ini.';
        } else if (jqxhr.status === 404) {
            errorMessage = 'Data tidak ditemukan.';
        } else if (jqxhr.status === 422) {
            errorMessage = 'Validasi gagal. Periksa kembali input Anda.';
        } else if (jqxhr.status === 500) {
            errorMessage = 'Terjadi kesalahan pada server. Silakan coba lagi.';
        }
        
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: errorMessage,
            confirmButtonText: 'OK'
        });
    });
    
    /*
    |--------------------------------------------------------------------------
    | Date Picker Initialization (if using date inputs)
    |--------------------------------------------------------------------------
    */
    
    if ($('.datepicker').length) {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            language: 'id'
        });
    }
    
    /*
    |--------------------------------------------------------------------------
    | Form Validation Helper
    |--------------------------------------------------------------------------
    */
    
    $('form.needs-validation').on('submit', function(e) {
        if (this.checkValidity() === false) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Toggle Password Visibility
    |--------------------------------------------------------------------------
    */
    
    $(document).on('click', '.toggle-password', function() {
        const input = $($(this).data('target'));
        const icon = $(this).find('i');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    /*
    |--------------------------------------------------------------------------
    | Auto-hide Alerts
    |--------------------------------------------------------------------------
    */
    
    $('.alert-auto-hide').each(function() {
        const alert = $(this);
        setTimeout(() => {
            alert.fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    });
    
    /*
    |--------------------------------------------------------------------------
    | Sidebar Toggle State (Remember collapsed state)
    |--------------------------------------------------------------------------
    */
    
    $('[data-widget="pushmenu"]').on('collapsed.lte.pushmenu', function() {
        localStorage.setItem('sidebar-collapsed', 'true');
    });
    
    $('[data-widget="pushmenu"]').on('shown.lte.pushmenu', function() {
        localStorage.setItem('sidebar-collapsed', 'false');
    });
    
    // Restore sidebar state on page load
    if (localStorage.getItem('sidebar-collapsed') === 'true') {
        $('body').addClass('sidebar-collapse');
    }
    
    /*
    |--------------------------------------------------------------------------
    | Print Function
    |--------------------------------------------------------------------------
    */
    
    $(document).on('click', '.btn-print', function(e) {
        e.preventDefault();
        window.print();
    });
    
    /*
    |--------------------------------------------------------------------------
    | Export Table to Excel (Simple)
    |--------------------------------------------------------------------------
    */
    
    $(document).on('click', '.btn-export-excel', function(e) {
        e.preventDefault();
        const table = $(this).data('table') || '.datatable';
        const filename = $(this).data('filename') || 'export.xls';
        
        const html = $(table).get(0).outerHTML;
        const blob = new Blob([html], {
            type: 'application/vnd.ms-excel'
        });
        
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    });
    
    /*
    |--------------------------------------------------------------------------
    | Number Format Helper (Thousand Separator)
    |--------------------------------------------------------------------------
    */
    
    $('.number-format').each(function() {
        const number = parseFloat($(this).text());
        if (!isNaN(number)) {
            $(this).text(number.toLocaleString('id-ID'));
        }
    });
    
    /*
    |--------------------------------------------------------------------------
    | Currency Format Helper
    |--------------------------------------------------------------------------
    */
    
    $('.currency-format').each(function() {
        const number = parseFloat($(this).text());
        if (!isNaN(number)) {
            $(this).text('Rp ' + number.toLocaleString('id-ID'));
        }
    });
    
    /*
    |--------------------------------------------------------------------------
    | Copy to Clipboard
    |--------------------------------------------------------------------------
    */
    
    $(document).on('click', '.btn-copy', function(e) {
        e.preventDefault();
        const text = $(this).data('text');
        
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Tersalin!',
                text: 'Text berhasil disalin ke clipboard',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        }).catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal menyalin text',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        });
    });
    
    /*
    |--------------------------------------------------------------------------
    | Tooltip Initialization (Bootstrap)
    |--------------------------------------------------------------------------
    */
    
    if ($('[data-bs-toggle="tooltip"]').length) {
        const tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    /*
    |--------------------------------------------------------------------------
    | Popover Initialization (Bootstrap)
    |--------------------------------------------------------------------------
    */
    
    if ($('[data-bs-toggle="popover"]').length) {
        const popoverTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="popover"]')
        );
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }
    
    /*
    |--------------------------------------------------------------------------
    | Preloader (if exists)
    |--------------------------------------------------------------------------
    */
    
    if ($('.preloader').length) {
        setTimeout(() => {
            $('.preloader').fadeOut('slow');
        }, 500);
    }
    
    console.log('UTA BASIS - OPEX Monitor initialized successfully! 🚀');
});

/*
|--------------------------------------------------------------------------
| Custom Functions (Global)
|--------------------------------------------------------------------------
*/

// Show loading
window.showLoading = function() {
    if ($('.spinner-overlay').length) {
        $('.spinner-overlay').addClass('active');
    }
};

// Hide loading
window.hideLoading = function() {
    if ($('.spinner-overlay').length) {
        $('.spinner-overlay').removeClass('active');
    }
};

// Show toast notification
window.showToast = function(type, message) {
    Swal.fire({
        icon: type,
        title: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
};

// Format number to Indonesian locale
window.formatNumber = function(number) {
    return parseFloat(number).toLocaleString('id-ID');
};

// Format currency to Rupiah
window.formatCurrency = function(number) {
    return 'Rp ' + parseFloat(number).toLocaleString('id-ID');
};

// Format date using moment.js
window.formatDate = function(date, format = 'DD MMMM YYYY') {
    return moment(date).format(format);
};