@extends('layouts.app')
@section('title', 'Data Cancel')
@section('page-title', 'Data Cancel')
@section('breadcrumb')
<li class="breadcrumb-item active">Cancel</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 font-weight-bold">
            <i class="fas fa-ban mr-2" style="color:#E53E3E;"></i> Data Cancel
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead style="background:#1A202C; color:#FFD700;">
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
                        <th>Alasan Cancel</th>
                        <th>Aksi</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @forelse($cancelData as $index => $item)
                    <tr>
                        <td>{{ $cancelData->firstItem() + $index }}</td>
                        <td>
                            <span style="font-weight:600;">{{ $item->reservation_number ?? "-" }}</span><br>
                            <small class="text-muted">Item: {{ $item->reservation_item ?? "-" }}</small>
                        </td>
                        <td>{{ $item->part_number ?? "-" }}</td>
                        <td>{{ $item->npn ?? "-" }}</td>
                        <td>{{ $item->qty ?? "-" }}</td>
                        <td>
                            <span class="badge badge-info" style="border-radius:20px;">{{ $item->unit ?? "-" }}</span>
                        </td>
                        <td>{{ $item->mo_description ?? "-" }}</td>
                        <td>{{ $item->plan_date ? \Carbon\Carbon::parse($item->plan_date)->format("d/m/Y") : "-" }}</td>
                        <td>{{ $item->tanggal_bcs ? \Carbon\Carbon::parse($item->tanggal_bcs)->format("d/m/Y") : "-" }}</td>
                        <td>
                            <span class="badge badge-danger" style="border-radius:20px; padding:5px 10px;">
                                {{ $item->cancel_reason ?? "-" }}
                            </span>
                        </td>
                        <td>
                            @if(Auth::user()->role == 'admin')
                            <form action="{{ route('backlog-daily.delete-from-cancel', $item->id) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Yakin hapus permanen data ini?')">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-danger" title="Hapus Permanen"
                                    style="border-radius: 6px; padding: 4px 8px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-5">
                            <i class="fas fa-ban fa-2x mb-2 d-block" style="opacity:0.3;"></i>
                            Tidak ada data cancel
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $cancelData->links() }}</div>
    </div>
</div>

{{-- Modal Restore --}}
<div class="modal fade" id="restoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kembalikan Data</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Kembalikan data ini ke status:</p>
                <select id="restoreStatus" class="form-control">
                    <option value="overdue">Overdue</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnConfirmRestore">
                    <i class="fas fa-undo mr-1"></i> Kembalikan
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
var restoreId = null;
$(document).on('click', '.btn-restore', function() {
    restoreId = $(this).data('id');
    $('#restoreModal').modal('show');
});

$('#btnConfirmRestore').on('click', function() {
    var status = $('#restoreStatus').val();
    $.ajax({
        url: '/cancel/' + restoreId + '/restore',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}', restore_status: status },
        success: function(response) {
            if (response.success) {
                $('#restoreModal').modal('hide');
                location.reload();
            }
        },
        error: function() { alert('Gagal mengembalikan data!'); }
    });
});
</script>
@endpush
@endsection
