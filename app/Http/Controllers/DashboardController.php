<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TbBacklog;
use App\Models\TbBacklogDaily;
use App\Models\TbOrder;
use App\Models\TbReconciliation;
use App\Models\TbPlanner;
use App\Models\TbVendor;
use App\Models\TbArchive;
use App\Models\TbDetailArchive;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ✅ OTOMATIS: Update outstanding setiap dashboard dibuka
        $this->autoUpdateOutstanding();

        $user = Auth::user();
        if ($user->role === 'vendor') {
            return $this->vendorDashboard($user, $request);
        } elseif ($user->role === 'planner') {
            return $this->plannerDashboard($user, $request);
        } else {
            return $this->adminDashboard($user, $request);
        }
    }

    // =====================================================
    // ✅ AUTO UPDATE OUTSTANDING
    // Cek semua data yang plan_date sudah lewat hari ini
    // dan status masih pending → otomatis jadi outstanding
    // Kecuali yang sudah complete → tidak disentuh
    // =====================================================
    private function autoUpdateOutstanding()
    {
        $today = Carbon::today()->toDateString();

        // Update semua data yang:
        // 1. Plan date sudah lewat (< hari ini)
        // 2. Status masih pending (on progress)
        // 3. Bukan complete
        DB::table('tb_backlog_daily')
            ->whereNotNull('plan_date')
            ->whereDate('plan_date', '<', $today)
            ->where('status', 'pending')
            ->update([
                'status'     => 'outstanding',
                'updated_at' => now(),
            ]);
    }

    private function hitungJarakEksekusi($collection)
    {
        return $collection->getCollection()->transform(function ($item) {
            if ($item->plan_date) {
                $planDate = Carbon::parse($item->plan_date);
                $today = Carbon::today();
                $item->jarak_eksekusi = (int) $planDate->diffInDays($today, false);
            } else {
                $item->jarak_eksekusi = null;
            }
            return $item;
        });
    }

    private function adminDashboard($user, $request)
    {
        $totalBacklog     = TbBacklogDaily::count();
        $totalComplete    = TbBacklogDaily::where('status', 'complete')->count();
        $totalOutstanding = TbBacklogDaily::where('status', 'outstanding')->count();
        $totalPending     = TbBacklogDaily::where('status', 'pending')->count();

        $achievementPercentage = $totalBacklog > 0
            ? round(($totalComplete / $totalBacklog) * 100, 2)
            : 0;

        $backlogMonitoring = TbBacklogDaily::with(['vendor', 'planner'])
            ->whereNotIn('status', ['complete'])
            ->when($request->filled('unit'), fn($q) => $q->where('unit', $request->unit))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->appends($request->only(['unit', 'status']));

        $this->hitungJarakEksekusi($backlogMonitoring);

        $unitList = TbBacklogDaily::whereNotNull('unit')
            ->whereNotIn('status', ['complete'])
            ->distinct()
            ->pluck('unit')
            ->sort()
            ->values();

        // ✅ List tanggal upload untuk modal hapus
        $tanggalList = DB::table('tb_backlog_daily')
            ->whereNotNull('tanggal')
            ->select('tanggal')
            ->distinct()
            ->orderBy('tanggal', 'desc')
            ->pluck('tanggal')
            ->map(fn($t) => Carbon::parse($t)->format('Y-m-d'));

        $totalOverdue = TbBacklogDaily::whereNotNull('plan_date')
            ->whereNotIn('status', ['complete'])
            ->whereDate('plan_date', '<', now()->toDateString())
            ->count();

        $overdueItems = TbBacklogDaily::whereNotNull('plan_date')
            ->whereNotIn('status', ['complete'])
            ->whereDate('plan_date', '<', now()->toDateString())
            ->orderBy('plan_date', 'asc')
            ->limit(5)
            ->get();

        $recentOrders      = TbOrder::with(['vendor'])->latest()->limit(10)->get();
        $backlogByStatus   = TbBacklogDaily::select('status', DB::raw('count(*) as total'))->groupBy('status')->get();
        $topVendors        = TbVendor::where('is_active', true)->limit(6)->get();
        $totalVendors      = TbVendor::count();
        $totalPlanners     = TbPlanner::count();
        $totalUsers        = User::count();
        $backlogToday      = collect();
        $backlogByPriority = collect();
        $monthlyTrend      = collect();
        $totalStillProgress = $totalPending + $totalOutstanding;
        $totalInProgress    = $totalStillProgress;

        return view('dashboard', compact(
            'totalBacklog', 'totalComplete', 'totalPending',
            'totalOutstanding', 'totalStillProgress',
            'totalInProgress', 'totalOverdue', 'overdueItems',
            'achievementPercentage', 'backlogMonitoring',
            'unitList', 'tanggalList', 'recentOrders', 'backlogByStatus',
            'backlogByPriority', 'monthlyTrend', 'topVendors',
            'totalVendors', 'totalPlanners', 'totalUsers',
            'backlogToday'
        ));
    }

    private function vendorDashboard($user, $request)
    {
        $totalBacklog      = TbBacklogDaily::count();
        $totalComplete     = TbBacklogDaily::where('status', 'complete')->count();
        $totalOutstanding  = TbBacklogDaily::where('status', 'outstanding')->count();
        $totalPending      = TbBacklogDaily::where('status', 'pending')->count();
        $achievementPercentage = $totalBacklog > 0
            ? round(($totalComplete / $totalBacklog) * 100, 2)
            : 0;
        $totalArchive  = TbArchive::count();
        $totalOverdue  = 0;
        $overdueItems  = collect();

        $queryVendor = TbBacklogDaily::latest();
        if ($request->filled('unit')) {
            $queryVendor->where('unit', $request->unit);
        }
        $statusInput = isset($_GET['status']) ? $_GET['status'] : 'NOT_SET';
        if ($statusInput === 'NOT_SET') {
            $queryVendor->where('status', 'outstanding');
        } elseif ($statusInput !== '') {
            $queryVendor->where('status', $statusInput);
        }
        $backlogMonitoring = $queryVendor->paginate(20)->appends($request->only(['unit', 'status']));

        $this->hitungJarakEksekusi($backlogMonitoring);

        $unitList = TbBacklogDaily::whereNotNull('unit')
            ->distinct()
            ->pluck('unit')
            ->sort()
            ->values();

        return view('dashboard-vendor', compact(
            'totalBacklog', 'totalComplete', 'totalOutstanding',
            'totalPending', 'achievementPercentage', 'totalOverdue',
            'overdueItems', 'totalArchive', 'backlogMonitoring', 'unitList'
        ));
    }

    private function plannerDashboard($user, $request)
    {
        $totalBacklog      = TbBacklogDaily::count();
        $totalComplete     = TbBacklogDaily::where('status', 'complete')->count();
        $totalOutstanding  = TbBacklogDaily::where('status', 'outstanding')->count();
        $totalPending      = TbBacklogDaily::where('status', 'pending')->count();
        $totalOverdue      = 0;
        $overdueItems      = collect();

        $achievementPercentage = $totalBacklog > 0
            ? round(($totalComplete / $totalBacklog) * 100, 2)
            : 0;

        $queryPlanner = TbBacklogDaily::latest();
        if ($request->filled('unit')) {
            $queryPlanner->where('unit', $request->unit);
        }
        $statusInput = isset($_GET['status']) ? $_GET['status'] : 'NOT_SET';
        if ($statusInput === 'NOT_SET') {
            $queryPlanner->where('status', 'outstanding');
        } elseif ($statusInput !== '') {
            $queryPlanner->where('status', $statusInput);
        }
        $backlogMonitoring = $queryPlanner->paginate(20)->appends($request->only(['unit', 'status']));

        $this->hitungJarakEksekusi($backlogMonitoring);

        $unitList = TbBacklogDaily::whereNotNull('unit')
            ->distinct()
            ->pluck('unit')
            ->sort()
            ->values();

        $totalArchive = TbArchive::count();

        return view('dashboard-planner', compact(
            'totalBacklog', 'totalComplete', 'totalOutstanding',
            'totalPending', 'achievementPercentage', 'totalOverdue',
            'overdueItems', 'backlogMonitoring', 'unitList', 'totalArchive'
        ));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'status' => 'required|in:complete,outstanding,pending'
        ]);

        TbBacklogDaily::whereIn('id', $request->ids)
            ->update(['status' => $request->status]);

        if ($request->status === 'complete') {
            $this->archiveCompleted($request->ids);
        }

        $this->updateArchiveStats();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diupdate!'
        ]);
    }

    private function updateArchiveStats()
    {
        $archives = TbArchive::where('periode', now()->format('Y-m'))->get();

        foreach ($archives as $archive) {
            $archive->total_complete    = TbDetailArchive::where('archive_id', $archive->id)->count();
            $archive->total_backlog     = TbBacklogDaily::count();
            $archive->total_pending     = TbBacklogDaily::where('status', 'pending')->count();
            $archive->total_outstanding = TbBacklogDaily::where('status', 'outstanding')->count();
            $archive->save();
        }
    }

    private function archiveCompleted(array $ids)
    {
        $items   = TbBacklogDaily::whereIn('id', $ids)->get();
        $periode = now()->format('Y-m');
        $tanggal = now()->toDateString();

        $archive = TbArchive::firstOrCreate(
            [
                'periode'         => $periode,
                'tanggal_archive' => $tanggal,
            ],
            [
                'total_backlog'     => 0,
                'total_complete'    => 0,
                'total_pending'     => 0,
                'total_outstanding' => 0,
                'notes'             => 'Auto-generated',
                'created_by'        => Auth::id(),
            ]
        );

        foreach ($items as $item) {
            $exists = TbDetailArchive::where('archive_id', $archive->id)
                ->where('reservation_number', $item->reservation_number)
                ->where('reservation_item', $item->reservation_item)
                ->exists();

            if (!$exists) {
                TbDetailArchive::create([
                    'archive_id'         => $archive->id,
                    'reservation_number' => $item->reservation_number,
                    'reservation_item'   => $item->reservation_item,
                    'mo_description'     => $item->mo_description,
                    'equipment'          => $item->equipment,
                    'egi'                => $item->egi,
                    'part_number'        => $item->part_number,
                    'npn'                => $item->npn,
                    'manufacture'        => $item->manufacture,
                    'qty'                => $item->qty,
                    'unit'               => $item->unit,
                    'plan_date'          => $item->plan_date,
                    'jarak_eksekusi'     => $item->jarak_eksekusi,
                    'tanggal_bcs'        => $item->tanggal_bcs,
                    'remarks'            => $item->remarks,
                    'status'             => 'complete',
                    'tanggal'            => $item->tanggal,
                ]);
            }
        }

        $archive->total_complete    = TbDetailArchive::where('archive_id', $archive->id)->count();
        $archive->total_backlog     = TbBacklogDaily::count();
        $archive->total_pending     = TbBacklogDaily::where('status', 'pending')->count();
        $archive->total_outstanding = TbBacklogDaily::where('status', 'outstanding')->count();
        $archive->save();
    }

    public function updateRemarks(Request $request)
    {
        $request->validate([
            'id'      => 'required|integer',
            'remarks' => 'nullable|string|max:500',
        ]);

        TbBacklogDaily::where('id', $request->id)
            ->update(['remarks' => $request->remarks]);

        return response()->json([
            'success' => true,
            'message' => 'Remarks berhasil disimpan!'
        ]);
    }

    public function hapusDataImport(Request $request)
    {
        $request->validate([
            'tanggal' => 'nullable|date'
        ]);

        if ($request->filled('tanggal')) {
            $deleted = TbBacklogDaily::whereDate('tanggal', $request->tanggal)
                ->whereNotIn('status', ['complete'])
                ->delete();
            $message = 'Data tanggal ' . $request->tanggal . ' berhasil dihapus! (' . $deleted . ' data)';
        } else {
            $deleted = TbBacklogDaily::whereNotIn('status', ['complete'])->delete();
            $message = 'Semua data berhasil dihapus! (' . $deleted . ' data)';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function getChartData(Request $request)
    {
        $type = $request->input('type', 'status');
        switch ($type) {
            case 'status':
                $data = TbBacklogDaily::select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')->get();
                break;
            case 'monthly':
                $data = TbBacklogDaily::select(
                        DB::raw('YEAR(created_at) as year'),
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('COUNT(*) as total'),
                        DB::raw('SUM(CASE WHEN status = "complete" THEN 1 ELSE 0 END) as completed')
                    )
                    ->where('created_at', '>=', now()->subMonths(12))
                    ->groupBy('year', 'month')
                    ->orderBy('year')->orderBy('month')->get();
                break;
            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }
        return response()->json($data);
    }
}