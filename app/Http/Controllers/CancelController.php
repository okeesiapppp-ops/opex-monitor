<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TbBacklogDaily;
use Illuminate\Support\Facades\Auth;

class CancelController extends Controller
{
    // Halaman daftar data cancel
    public function index(Request $request)
    {
        $cancelData = TbBacklogDaily::where('status', 'cancel')
            ->when($request->filled('unit'), fn($q) => $q->where('unit', $request->unit))
            ->latest()
            ->paginate(20)
            ->appends($request->only(['unit']));

        $unitList = TbBacklogDaily::where('status', 'cancel')
            ->whereNotNull('unit')
            ->distinct()
            ->pluck('unit')
            ->sort()
            ->values();

        return view('cancel.index', compact('cancelData', 'unitList'));
    }

    // Proses cancel (dari tombol hapus)
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:500'
        ]);

        $item = TbBacklogDaily::findOrFail($id);

        $item->update([
            'previous_status' => $item->status,
            'cancel_reason'   => $request->cancel_reason,
            'status'          => 'cancel',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dicancel!'
        ]);
    }

    // Kembalikan data dari cancel
    public function restore(Request $request, $id)
    {
        $request->validate([
            'restore_status' => 'required|in:outstanding,pending'
        ]);

        $item = TbBacklogDaily::findOrFail($id);

        $item->update([
            'status'          => $request->restore_status,
            'cancel_reason'   => null,
            'previous_status' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dikembalikan!'
        ]);
    }
}
