<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TbBacklog;
use App\Models\TbVendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BacklogController extends Controller
{

    // List all backlog
    public function index(Request $request)
    {
        $query = TbBacklog::with(['creator', 'updater']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by vendor
        if ($request->filled('vendor')) {
            $query->where('vendor', $request->vendor);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('unit_number', 'like', "%{$search}%")
                  ->orWhere('part_number', 'like', "%{$search}%")
                  ->orWhere('part_name', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $backlogs = $query->paginate(20);
        $vendors = TbVendor::active()->get();

        return view('backlog.index', compact('backlogs', 'vendors'));
    }

    // Show create form
    public function create()
    {
        $vendors = TbVendor::active()->get();
        return view('backlog.create', compact('vendors'));
    }

    // Store new backlog
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_number' => 'required|string|max:50',
            'model' => 'required|string|max:100',
            'part_number' => 'required|string|max:100',
            'part_name' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'satuan' => 'required|string|max:20',
            'vendor' => 'required|string|max:100',
            'eta' => 'nullable|date',
            'location' => 'nullable|string|max:100',
            'priority' => 'required|in:high,medium,low',
            'keterangan' => 'nullable|string',
        ]);

        $validated['status'] = 'pending';
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        TbBacklog::create($validated);

        return redirect()->route('backlog.index')
            ->with('success', 'Data backlog berhasil ditambahkan!');
    }

    // Show detail
    public function show($id)
    {
        $backlog = TbBacklog::with([
            'backlogDaily', 
            'orders', 
            'reconciliations', 
            'reconsults',
            'creator',
            'updater'
        ])->findOrFail($id);

        return view('backlog.show', compact('backlog'));
    }

    // Show edit form
    public function edit($id)
    {
        $backlog = TbBacklog::findOrFail($id);
        $vendors = TbVendor::active()->get();

        return view('backlog.edit', compact('backlog', 'vendors'));
    }

    // Update backlog
    public function update(Request $request, $id)
    {
        $backlog = TbBacklog::findOrFail($id);

        $validated = $request->validate([
            'unit_number' => 'required|string|max:50',
            'model' => 'required|string|max:100',
            'part_number' => 'required|string|max:100',
            'part_name' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'satuan' => 'required|string|max:20',
            'vendor' => 'required|string|max:100',
            'status' => 'required|in:pending,in_progress,complete,cancelled',
            'eta' => 'nullable|date',
            'location' => 'nullable|string|max:100',
            'priority' => 'required|in:high,medium,low',
            'keterangan' => 'nullable|string',
        ]);

        $validated['updated_by'] = Auth::id();

        // Set completion date if status is complete
        if ($validated['status'] === 'complete' && $backlog->status !== 'complete') {
            $validated['completion_date'] = now();
        }

        $backlog->update($validated);

        return redirect()->route('backlog.index')
            ->with('success', 'Data backlog berhasil diupdate!');
    }

    // Delete backlog
    public function destroy($id)
    {
        $backlog = TbBacklog::findOrFail($id);
        $backlog->delete();

        return redirect()->route('backlog.index')
            ->with('success', 'Data backlog berhasil dihapus!');
    }

    // Bulk import from Excel
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        // Import logic here (use Laravel Excel package)
        // Will be implemented in next step

        return back()->with('success', 'Data berhasil diimport!');
    }

    // Export to Excel
    public function exportExcel(Request $request)
    {
        // Export logic here
        // Will be implemented in next step

        return back()->with('success', 'Data berhasil diexport!');
    }

    // Update status (AJAX)
    public function updateStatus(Request $request, $id)
    {
        $backlog = TbBacklog::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,complete,cancelled'
        ]);

        $backlog->update([
            'status' => $validated['status'],
            'updated_by' => Auth::id(),
            'completion_date' => $validated['status'] === 'complete' ? now() : null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diupdate!',
            'data' => $backlog
        ]);
    }
}