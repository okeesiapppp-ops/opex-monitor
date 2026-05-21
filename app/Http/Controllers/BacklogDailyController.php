<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TbBacklogDaily;
use App\Models\TbBacklog;
use App\Models\TbPlanner;
use App\Models\TbVendor;
use App\Exports\BacklogDailyExport;
use App\Imports\BacklogDailyImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BacklogDailyController extends Controller
{
    public function index(Request $request)
    {
        // ✅ OTOMATIS: Update outstanding setiap halaman backlog daily dibuka
        $this->autoUpdateOutstanding();

        $tanggalFilter = $request->input('tanggal', now()->format('Y-m-d'));

        $query = TbBacklogDaily::query();

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_bcs', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $backlogDaily = $query->latest()->paginate(20);

        // Hitung jarak eksekusi otomatis
        $backlogDaily->getCollection()->transform(function ($item) {
            if ($item->plan_date) {
                $planDate = Carbon::parse($item->plan_date);
                $today = Carbon::today();
                $selisih = $planDate->diffInDays($today, false);
                $item->jarak_eksekusi = (int) $selisih;
            } else {
                $item->jarak_eksekusi = null;
            }
            return $item;
        });

        $stats = [
            'total'       => TbBacklogDaily::count(),
            'complete'    => TbBacklogDaily::where('status', 'complete')->count(),
            'outstanding' => TbBacklogDaily::where('status', 'outstanding')->count(),
            'pending'     => TbBacklogDaily::where('status', 'pending')->count(),
        ];

        $vendors  = collect();
        $planners = collect();

        return view('backlog-daily.index', compact(
            'backlogDaily', 'vendors', 'planners', 'stats', 'tanggalFilter'
        ));
    }

    // =====================================================
    // ✅ AUTO UPDATE OUTSTANDING
    // Plan date sudah lewat + status pending → outstanding
    // Status complete tidak disentuh sama sekali
    // =====================================================
    private function autoUpdateOutstanding()
    {
        $today = Carbon::today()->toDateString();

        DB::table('tb_backlog_daily')
            ->whereNotNull('plan_date')
            ->whereDate('plan_date', '<', $today)
            ->where('status', 'pending')
            ->update([
                'status'     => 'outstanding',
                'updated_at' => now(),
            ]);
    }

    // Import Excel
    public function importExcel(Request $request)
    {
        $request->validate([
            'file'    => 'required|mimes:xlsx,xls|max:5120',
            'tanggal' => 'required|date',
        ]);

        try {
            Excel::import(new BacklogDailyImport($request->tanggal), $request->file('file'));

            // ✅ Setelah import, langsung cek outstanding otomatis
            $this->autoUpdateOutstanding();

            return redirect()->route('dashboard')->with('success', 'Data BCS berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    // Export Excel
    public function exportExcel(Request $request)
    {
        $tanggal  = $request->input('tanggal', today()->format('Y-m-d'));
        $filename = 'backlog-daily-' . $tanggal . '.xlsx';

        return Excel::download(new BacklogDailyExport($tanggal), $filename);
    }

    // Hapus per baris
    public function destroy($id)
    {
        $item = TbBacklogDaily::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus!'
        ]);
    }

    // Hapus bulk
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array'
        ]);

        $deleted = TbBacklogDaily::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => $deleted . ' data berhasil dihapus!'
        ]);
    }

    public function getByDateRange(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate   = $request->input('end_date', now()->endOfMonth());

        $data = TbBacklogDaily::whereBetween('tanggal_bcs', [$startDate, $endDate])->get();

        return response()->json($data);
    }
}