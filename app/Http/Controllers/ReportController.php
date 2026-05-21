<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TbBacklog;
use App\Models\TbBacklogDaily;
use App\Models\TbOrder;
use App\Models\TbReconciliation;
use App\Models\TbVendor;
use App\Models\TbPlanner;
use App\Models\TbArchive;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('report.index');
    }

    // Backlog achievement — filter per bulan berdasarkan tanggal_bcs (tanggal laporan BCS dari SM)
    public function backlogAchievement(Request $request)
    {
        $month = (int) $request->input('month', 0);
        $year  = (int) $request->input('year',  0);

        // Jika tidak ada parameter, otomatis tampilkan bulan terakhir yang ada datanya
        if ($month === 0 || $year === 0) {
            $latestData = DB::table('tb_backlog_daily')
                ->whereNotNull('tanggal_bcs')
                ->selectRaw('MONTH(tanggal_bcs) as month, YEAR(tanggal_bcs) as year')
                ->orderBy('tanggal_bcs', 'desc')
                ->first();

            $month = ($month === 0 && $latestData) ? (int) $latestData->month : ($month ?: now()->month);
            $year  = ($year  === 0 && $latestData) ? (int) $latestData->year  : ($year  ?: now()->year);
        }

        // Hitung range tanggal dari bulan yang dipilih
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
        $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();

        // Semua query pakai tanggal_bcs
        $total       = DB::table('tb_backlog_daily')->whereBetween('tanggal_bcs', [$startDate, $endDate])->count();
        $complete    = DB::table('tb_backlog_daily')->whereBetween('tanggal_bcs', [$startDate, $endDate])->where('status', 'complete')->count();
        $pending     = DB::table('tb_backlog_daily')->whereBetween('tanggal_bcs', [$startDate, $endDate])->where('status', 'pending')->count();
        $outstanding = DB::table('tb_backlog_daily')->whereBetween('tanggal_bcs', [$startDate, $endDate])->where('status', 'outstanding')->count();
        $achievement = $total > 0 ? round(($complete / $total) * 100, 2) : 0;

        $data = DB::table('tb_backlog_daily')
            ->select('status', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_bcs', [$startDate, $endDate])
            ->whereNotNull('status')
            ->groupBy('status')
            ->get();

        // Ambil semua bulan yang ada datanya untuk navigasi
        $availableMonths = DB::table('tb_backlog_daily')
            ->whereNotNull('tanggal_bcs')
            ->selectRaw('MONTH(tanggal_bcs) as month, YEAR(tanggal_bcs) as year')
            ->groupBy(DB::raw('YEAR(tanggal_bcs)'), DB::raw('MONTH(tanggal_bcs)'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('report.backlog-achievement', compact(
            'data', 'total', 'complete', 'pending', 'outstanding',
            'achievement', 'startDate', 'endDate', 'month', 'year',
            'availableMonths'
        ));
    }

    // Vendor performance report
    public function vendorPerformance(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->endOfMonth()->toDateString());

        $vendors = TbVendor::withCount([
                'backlogDaily as total_supplies' => function($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate]);
                },
                'backlogDaily as ready_supplies' => function($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                          ->where('is_ready', true);
                }
            ])
            ->get()
            ->map(function($vendor) {
                $vendor->fulfillment_rate = $vendor->total_supplies > 0
                    ? round(($vendor->ready_supplies / $vendor->total_supplies) * 100, 2)
                    : 0;
                return $vendor;
            })
            ->sortByDesc('fulfillment_rate');

        return view('report.vendor-performance', compact('vendors', 'startDate', 'endDate'));
    }

    // Monthly trend — pakai tanggal_bcs
    public function monthlyTrend(Request $request)
    {
        $year = $request->input('year', now()->year);

        $trend = DB::table('tb_backlog_daily')
            ->select(
                DB::raw('YEAR(tanggal_bcs) as year'),
                DB::raw('MONTH(tanggal_bcs) as month'),
                DB::raw('COUNT(*) as total_backlog'),
                DB::raw('SUM(CASE WHEN status = "complete" THEN 1 ELSE 0 END) as complete'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "outstanding" THEN 1 ELSE 0 END) as outstanding'),
                DB::raw('ROUND(SUM(CASE WHEN status = "complete" THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) as achievement')
            )
            ->whereYear('tanggal_bcs', $year)
            ->whereNotNull('tanggal_bcs')
            ->groupBy(DB::raw('YEAR(tanggal_bcs)'), DB::raw('MONTH(tanggal_bcs)'))
            ->orderBy('month')
            ->get();

        return view('report.monthly-trend', compact('trend', 'year'));
    }

    // Daily backlog position report
    public function dailyBacklogPosition(Request $request)
    {
        $tanggal = $request->input('tanggal', today());

        $backlogDaily = TbBacklogDaily::with(['vendor', 'planner'])
            ->whereDate('tanggal', $tanggal)
            ->get();

        $summary = [
            'total'   => $backlogDaily->count(),
            'ready'   => $backlogDaily->where('is_ready', true)->count(),
            'pending' => $backlogDaily->where('is_ready', false)->count(),
        ];

        return view('report.daily-backlog-position', compact(
            'backlogDaily', 'summary', 'tanggal'
        ));
    }

    public function export(Request $request)
    {
        return back()->with('success', 'Report berhasil diexport!');
    }
}