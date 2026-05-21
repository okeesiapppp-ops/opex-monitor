<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TbReconciliation;
use App\Models\TbBacklog;
use App\Models\TbVendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReconciliationController extends Controller
{

    // List all reconciliations
    public function index(Request $request)
    {
        $query = TbReconciliation::with(['backlog', 'vendor', 'creator']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by vendor
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('part_number', 'like', "%{$search}%")
                  ->orWhere('part_name', 'like', "%{$search}%");
            });
        }

        $reconciliations = $query->latest('tanggal')->paginate(20);
        
        $vendors = TbVendor::active()->get();

        // Statistics
        $stats = [
            'total' => TbReconciliation::count(),
            'match' => TbReconciliation::match()->count(),
            'mismatch' => TbReconciliation::mismatch()->count(),
            'partial' => TbReconciliation::where('status', 'partial')->count(),
        ];

        return view('reconciliation.index', compact('reconciliations', 'vendors', 'stats'));
    }

    // Show create form
    public function create()
    {
        $backlogs = TbBacklog::whereIn('status', ['in_progress', 'complete'])->get();
        $vendors = TbVendor::active()->get();

        return view('reconciliation.create', compact('backlogs', 'vendors'));
    }

    // Store new reconciliation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'backlog_id' => 'required|exists:tb_backlog,id',
            'part_number' => 'required|string|max:100',
            'part_name' => 'required|string|max:255',
            'qty_ordered' => 'required|integer|min:0',
            'qty_received' => 'required|integer|min:0',
            'vendor_id' => 'required|exists:tb_vendor,id',
            'keterangan' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();

        // qty_difference and status will be auto-calculated by model boot method

        TbReconciliation::create($validated);

        return redirect()->route('reconciliation.index')
            ->with('success', 'Data rekonsiliasi berhasil ditambahkan!');
    }

    // Show detail
    public function show($id)
    {
        $reconciliation = TbReconciliation::with(['backlog', 'vendor', 'creator', 'reconciler', 'approver'])
            ->findOrFail($id);

        return view('reconciliation.show', compact('reconciliation'));
    }

    // Show edit form
    public function edit($id)
    {
        $reconciliation = TbReconciliation::findOrFail($id);
        $backlogs = TbBacklog::whereIn('status', ['in_progress', 'complete'])->get();
        $vendors = TbVendor::active()->get();

        return view('reconciliation.edit', compact('reconciliation', 'backlogs', 'vendors'));
    }

    // Update reconciliation
    public function update(Request $request, $id)
    {
        $reconciliation = TbReconciliation::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'backlog_id' => 'required|exists:tb_backlog,id',
            'part_number' => 'required|string|max:100',
            'part_name' => 'required|string|max:255',
            'qty_ordered' => 'required|integer|min:0',
            'qty_received' => 'required|integer|min:0',
            'vendor_id' => 'required|exists:tb_vendor,id',
            'keterangan' => 'nullable|string',
        ]);

        // qty_difference and status will be auto-calculated

        $reconciliation->update($validated);

        return redirect()->route('reconciliation.index')
            ->with('success', 'Data rekonsiliasi berhasil diupdate!');
    }

    // Delete reconciliation
    public function destroy($id)
    {
        $reconciliation = TbReconciliation::findOrFail($id);
        $reconciliation->delete();

        return redirect()->route('reconciliation.index')
            ->with('success', 'Data rekonsiliasi berhasil dihapus!');
    }

    // Approve reconciliation
    public function approve(Request $request, $id)
    {
        $reconciliation = TbReconciliation::findOrFail($id);

        $reconciliation->update([
            'approved_by' => Auth::id()
        ]);

        return back()->with('success', 'Rekonsiliasi berhasil di-approve!');
    }

    // Export reconciliation report
    public function exportReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $reconciliations = TbReconciliation::with(['backlog', 'vendor'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        // Export to Excel logic
        // Will be implemented later

        return back()->with('success', 'Report rekonsiliasi berhasil diexport!');
    }

    // Get reconciliation summary (API)
    public function getSummary(Request $request)
    {
        $vendorId = $request->input('vendor_id');
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $query = TbReconciliation::whereBetween('tanggal', [$startDate, $endDate]);

        if ($vendorId) {
            $query->where('vendor_id', $vendorId);
        }

        $summary = [
            'total' => $query->count(),
            'match' => $query->where('status', 'match')->count(),
            'mismatch' => $query->where('status', 'mismatch')->count(),
            'partial' => $query->where('status', 'partial')->count(),
            'total_ordered' => $query->sum('qty_ordered'),
            'total_received' => $query->sum('qty_received'),
            'total_difference' => $query->sum('qty_difference'),
        ];

        return response()->json($summary);
    }
}