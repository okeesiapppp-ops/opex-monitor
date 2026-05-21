<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TbReconsult;
use App\Models\TbBacklog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReconsultController extends Controller
{

    // List all reconsults
    public function index(Request $request)
    {
        $query = TbReconsult::with(['backlog', 'vendorPic', 'plannerPic', 'creator']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_konsultasi', [$request->start_date, $request->end_date]);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('masalah', 'like', "%{$search}%")
                  ->orWhere('solusi', 'like', "%{$search}%")
                  ->orWhereHas('backlog', function($q2) use ($search) {
                      $q2->where('part_number', 'like', "%{$search}%")
                         ->orWhere('unit_number', 'like', "%{$search}%");
                  });
            });
        }

        $reconsults = $query->latest('tanggal_konsultasi')->paginate(20);
        
        $vendorUsers = User::where('role', 'vendor')->where('status', 'active')->get();
        $plannerUsers = User::where('role', 'planner')->where('status', 'active')->get();

        // Statistics
        $stats = [
            'total' => TbReconsult::count(),
            'open' => TbReconsult::open()->count(),
            'in_progress' => TbReconsult::where('status', 'in_progress')->count(),
            'resolved' => TbReconsult::resolved()->count(),
            'high_priority' => TbReconsult::highPriority()->count(),
        ];

        return view('reconsult.index', compact('reconsults', 'vendorUsers', 'plannerUsers', 'stats'));
    }

    // Show create form
    public function create()
    {
        $backlogs = TbBacklog::whereNotIn('status', ['complete', 'cancelled'])->get();
        $vendorUsers = User::where('role', 'vendor')->where('status', 'active')->get();
        $plannerUsers = User::where('role', 'planner')->where('status', 'active')->get();

        return view('reconsult.create', compact('backlogs', 'vendorUsers', 'plannerUsers'));
    }

    // Store new reconsult
    public function store(Request $request)
    {
        $validated = $request->validate([
            'backlog_id' => 'required|exists:tb_backlog,id',
            'tanggal_konsultasi' => 'required|date',
            'masalah' => 'required|string',
            'solusi' => 'nullable|string',
            'priority' => 'required|in:high,medium,low',
            'pic_vendor' => 'nullable|exists:users,id',
            'pic_planner' => 'nullable|exists:users,id',
            'keterangan' => 'nullable|string',
        ]);

        $validated['status'] = 'open';
        $validated['created_by'] = Auth::id();

        TbReconsult::create($validated);

        return redirect()->route('reconsult.index')
            ->with('success', 'Data konsultasi berhasil ditambahkan!');
    }

    // Show detail
    public function show($id)
    {
        $reconsult = TbReconsult::with(['backlog', 'vendorPic', 'plannerPic', 'creator'])
            ->findOrFail($id);

        return view('reconsult.show', compact('reconsult'));
    }

    // Show edit form
    public function edit($id)
    {
        $reconsult = TbReconsult::findOrFail($id);
        $backlogs = TbBacklog::whereNotIn('status', ['complete', 'cancelled'])->get();
        $vendorUsers = User::where('role', 'vendor')->where('status', 'active')->get();
        $plannerUsers = User::where('role', 'planner')->where('status', 'active')->get();

        return view('reconsult.edit', compact('reconsult', 'backlogs', 'vendorUsers', 'plannerUsers'));
    }

    // Update reconsult
    public function update(Request $request, $id)
    {
        $reconsult = TbReconsult::findOrFail($id);

        $validated = $request->validate([
            'backlog_id' => 'required|exists:tb_backlog,id',
            'tanggal_konsultasi' => 'required|date',
            'masalah' => 'required|string',
            'solusi' => 'nullable|string',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'priority' => 'required|in:high,medium,low',
            'pic_vendor' => 'nullable|exists:users,id',
            'pic_planner' => 'nullable|exists:users,id',
            'keterangan' => 'nullable|string',
        ]);

        // Set resolved date if status is resolved/closed
        if (in_array($validated['status'], ['resolved', 'closed']) && !$reconsult->isResolved()) {
            $validated['resolved_date'] = now();
        }

        $reconsult->update($validated);

        return redirect()->route('reconsult.index')
            ->with('success', 'Data konsultasi berhasil diupdate!');
    }

    // Delete reconsult
    public function destroy($id)
    {
        $reconsult = TbReconsult::findOrFail($id);
        $reconsult->delete();

        return redirect()->route('reconsult.index')
            ->with('success', 'Data konsultasi berhasil dihapus!');
    }

    // Update status (AJAX)
    public function updateStatus(Request $request, $id)
    {
        $reconsult = TbReconsult::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'solusi' => 'required_if:status,resolved,closed|string'
        ]);

        $data = ['status' => $validated['status']];

        // Set resolved date
        if (in_array($validated['status'], ['resolved', 'closed']) && !$reconsult->isResolved()) {
            $data['resolved_date'] = now();
        }

        // Update solution if provided
        if ($request->filled('solusi')) {
            $data['solusi'] = $validated['solusi'];
        }

        $reconsult->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Status konsultasi berhasil diupdate!',
            'data' => $reconsult->fresh()
        ]);
    }

    // Assign PIC (AJAX)
    public function assignPic(Request $request, $id)
    {
        $reconsult = TbReconsult::findOrFail($id);
        
        $validated = $request->validate([
            'pic_vendor' => 'nullable|exists:users,id',
            'pic_planner' => 'nullable|exists:users,id',
        ]);

        $reconsult->update($validated);

        // Update status to in_progress if still open
        if ($reconsult->status === 'open') {
            $reconsult->update(['status' => 'in_progress']);
        }

        return response()->json([
            'success' => true,
            'message' => 'PIC berhasil ditugaskan!',
            'data' => $reconsult->fresh()
        ]);
    }

    // Get resolution time report
    public function resolutionTimeReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $reconsults = TbReconsult::whereBetween('tanggal_konsultasi', [$startDate, $endDate])
            ->whereNotNull('resolved_date')
            ->get();

        $report = [
            'total_resolved' => $reconsults->count(),
            'avg_resolution_days' => $reconsults->avg(function($item) {
                return $item->getResolutionTime();
            }),
            'fastest_resolution' => $reconsults->min(function($item) {
                return $item->getResolutionTime();
            }),
            'slowest_resolution' => $reconsults->max(function($item) {
                return $item->getResolutionTime();
            }),
        ];

        return view('reconsult.resolution-report', compact('report', 'reconsults'));
    }
}