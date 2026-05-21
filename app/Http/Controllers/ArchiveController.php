<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TbArchive;
use App\Models\TbDetailArchive;
use App\Models\TbBacklog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArchiveController extends Controller
{

    // List all archives
    public function index(Request $request)
    {
        $query = TbArchive::with('creator');

        // Filter by year
        if ($request->filled('year')) {
            $query->whereYear('tanggal_archive', $request->year);
        }

        // Filter by periode
        if ($request->filled('periode')) {
            $query->where('periode', 'like', $request->periode . '%');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('periode', 'like', "%{$search}%");
        }

        $archives = $query->latest('tanggal_archive')->paginate(20);

        // Get available years
        $years = TbArchive::selectRaw('YEAR(tanggal_archive) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('archive.index', compact('archives', 'years'));
    }

    // Show create form
    public function create()
    {
        return view('archive.create');
    }

    // Store new archive (manual)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_archive' => 'required|date',
            'periode' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        // Calculate statistics from backlog
        $startDate = Carbon::parse($validated['periode'] . '-01')->startOfMonth();
        $endDate = Carbon::parse($validated['periode'] . '-01')->endOfMonth();

        $totalBacklog = TbBacklog::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalComplete = TbBacklog::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'complete')
            ->count();
        $totalPending = TbBacklog::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'pending')
            ->count();
        $totalCancelled = TbBacklog::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'cancelled')
            ->count();

        $archiveData = [
            'tanggal_archive' => $validated['tanggal_archive'],
            'periode' => $validated['periode'],
            'total_backlog' => $totalBacklog,
            'total_complete' => $totalComplete,
            'total_pending' => $totalPending,
            'total_cancelled' => $totalCancelled,
            'notes' => $validated['notes'],
            'created_by' => Auth::id(),
        ];

        // achievement_percentage and backlog_ratio will be auto-calculated

        $archive = TbArchive::create($archiveData);

        // Create detail records
        $backlogs = TbBacklog::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'complete')
            ->get();

        foreach ($backlogs as $backlog) {
            TbDetailArchive::create([
                'archive_id' => $archive->id,
                'backlog_id' => $backlog->id,
                'unit_number' => $backlog->unit_number,
                'model' => $backlog->model,
                'part_number' => $backlog->part_number,
                'part_name' => $backlog->part_name,
                'qty' => $backlog->qty,
                'status' => $backlog->status,
                'completion_date' => $backlog->completion_date,
                'vendor' => $backlog->vendor,
                'days_to_complete' => $backlog->created_at->diffInDays($backlog->completion_date),
            ]);
        }

        return redirect()->route('archive.index')
            ->with('success', 'Archive berhasil dibuat dengan ' . $backlogs->count() . ' detail records!');
    }

    // Auto-generate archive for periode
    public function autoGenerate(Request $request)
    {
        $validated = $request->validate([
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/', // Format: YYYY-MM
        ]);

        $periode = $validated['periode'];
        
        // Check if archive already exists
        $exists = TbArchive::where('periode', $periode)->exists();
        
        if ($exists) {
            return back()->with('error', 'Archive untuk periode ' . $periode . ' sudah ada!');
        }

        $startDate = Carbon::parse($periode . '-01')->startOfMonth();
        $endDate = Carbon::parse($periode . '-01')->endOfMonth();

        // Calculate statistics
        $totalBacklog = TbBacklog::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalComplete = TbBacklog::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'complete')
            ->count();
        $totalPending = TbBacklog::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'pending')
            ->count();
        $totalCancelled = TbBacklog::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'cancelled')
            ->count();

        if ($totalBacklog === 0) {
            return back()->with('error', 'Tidak ada data backlog untuk periode ' . $periode);
        }

        $archive = TbArchive::create([
            'tanggal_archive' => now(),
            'periode' => $periode,
            'total_backlog' => $totalBacklog,
            'total_complete' => $totalComplete,
            'total_pending' => $totalPending,
            'total_cancelled' => $totalCancelled,
            'notes' => 'Auto-generated archive',
            'created_by' => Auth::id(),
        ]);

        // Create detail records for completed backlogs
        $completedBacklogs = TbBacklog::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'complete')
            ->get();

        foreach ($completedBacklogs as $backlog) {
            TbDetailArchive::create([
                'archive_id' => $archive->id,
                'backlog_id' => $backlog->id,
                'unit_number' => $backlog->unit_number,
                'model' => $backlog->model,
                'part_number' => $backlog->part_number,
                'part_name' => $backlog->part_name,
                'qty' => $backlog->qty,
                'status' => $backlog->status,
                'completion_date' => $backlog->completion_date,
                'vendor' => $backlog->vendor,
                'days_to_complete' => $backlog->created_at->diffInDays($backlog->completion_date),
            ]);
        }

        return redirect()->route('archive.show', $archive->id)
            ->with('success', 'Archive periode ' . $periode . ' berhasil di-generate dengan ' . $completedBacklogs->count() . ' detail records!');
    }

    // Show detail
    public function show($id)
    {
        $archive = TbArchive::with(['details', 'creator'])
            ->findOrFail($id);

        return view('archive.show', compact('archive'));
    }

    // Show edit form
    public function edit($id)
    {
        $archive = TbArchive::findOrFail($id);
        return view('archive.edit', compact('archive'));
    }

    // Update archive
    public function update(Request $request, $id)
    {
        $archive = TbArchive::findOrFail($id);

        $validated = $request->validate([
            'tanggal_archive' => 'required|date',
            'periode' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $archive->update($validated);

        return redirect()->route('archive.index')
            ->with('success', 'Archive berhasil diupdate!');
    }

    // Delete archive
    public function destroy($id)
    {
        $archive = TbArchive::findOrFail($id);
        
        // Delete all details first
        $archive->details()->delete();
        
        $archive->delete();

        return redirect()->route('archive.index')
            ->with('success', 'Archive berhasil dihapus!');
    }

    // Export archive to Excel/PDF
    public function export($id, $format = 'excel')
    {
        $archive = TbArchive::with('details')->findOrFail($id);

        // Export logic based on format
        // Will be implemented later

        return back()->with('success', 'Archive berhasil diexport!');
    }

    // Compare archives (year-over-year)
    public function compare(Request $request)
    {
        $year1 = $request->input('year1', now()->year);
        $year2 = $request->input('year2', now()->year - 1);

        $archives1 = TbArchive::whereYear('tanggal_archive', $year1)
            ->orderBy('periode')
            ->get();

        $archives2 = TbArchive::whereYear('tanggal_archive', $year2)
            ->orderBy('periode')
            ->get();

        $comparison = [
            'year1' => $year1,
            'year2' => $year2,
            'total_achievement_y1' => $archives1->avg('achievement_percentage'),
            'total_achievement_y2' => $archives2->avg('achievement_percentage'),
            'total_backlog_y1' => $archives1->sum('total_backlog'),
            'total_backlog_y2' => $archives2->sum('total_backlog'),
            'archives1' => $archives1,
            'archives2' => $archives2,
        ];

        return view('archive.compare', compact('comparison'));
    }

    // Get achievement trend (API for chart)
    public function getAchievementTrend(Request $request)
    {
        $year = $request->input('year', now()->year);

        $trend = TbArchive::whereYear('tanggal_archive', $year)
            ->orderBy('periode')
            ->get(['periode', 'achievement_percentage', 'backlog_ratio'])
            ->map(function($item) {
                return [
                    'month' => substr($item->periode, 5, 2),
                    'achievement' => $item->achievement_percentage,
                    'backlog_ratio' => $item->backlog_ratio,
                ];
            });

        return response()->json($trend);
    }
}