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

        $query = TbBacklogDaily::whereNotIn('status', ['cancel']);

        if ($request->filled('tanggal') && !$request->filled('search') && !$request->filled('reservation')) {
            $query->whereDate('tanggal_bcs', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }
        if ($request->filled('reservation')) {
            $query->where('reservation_number', 'like', '%'.$request->reservation.'%');
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('mo_description', 'like', '%'.$request->search.'%')->orWhere('part_number', 'like', '%'.$request->search.'%')->orWhere('reservation_number', 'like', '%'.$request->search.'%')->orWhere('unit', 'like', '%'.$request->search.'%');
            });
        }

        // Sort by SAW priority if requested
        if ($request->input('sort') == 'saw') {
            $backlogDaily = $query->orderByRaw('ISNULL(priority_rank), priority_rank ASC')->paginate(20);
        } else {
            $backlogDaily = $query->latest()->paginate(20);
        }

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
            'overdue'     => TbBacklogDaily::where('status', 'overdue')->count(),
        ];

        $vendors  = collect();
        $planners = collect();

        $unitList = TbBacklogDaily::whereNotNull('unit')->distinct()->pluck('unit')->sort()->values();
        return view('backlog-daily.index', compact(
            'backlogDaily', 'vendors', 'planners', 'stats', 'tanggalFilter', 'unitList'
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
            ->where('status', 'outstanding')
            ->update([
                'status'     => 'overdue',
                'updated_at' => now(),
            ]);
    }

    // Import Excel
    public function importExcel(Request $request)
    {
        $request->validate([
            'file'      => 'required|mimes:xlsx,xls|max:5120',
            'tanggal'   => 'required|date',
            'file_zboh' => 'nullable|mimes:xlsx,xls|max:5120',
        ]);
        try {
            Excel::import(new BacklogDailyImport($request->tanggal), $request->file('file'));
            $this->autoUpdateOutstanding();
            if ($request->hasFile('file_zboh')) {
                $this->processZboh($request->file('file_zboh'));
            }
            return redirect()->route('dashboard')->with('success', 'Data BCS berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    private function processZboh($file)
    {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        $headers = null;
        foreach ($rows as $i => $row) {
            if ($headers === null) {
                $rowLower = array_map('strtolower', array_map('trim', array_map('strval', $row)));
                if (in_array('material', $rowLower)) {
                    $headers = $rowLower;
                }
                continue;
            }
            $data = array_combine($headers, $row);
            $material = trim(strval($data['material'] ?? ''));
            $refDok   = trim(strval($data['reference document'] ?? ''));
            $qty      = (int)($data['quantity'] ?? 0);
            if (empty($material)) continue;
            $items = \App\Models\TbBacklogDaily::where(function($q) use ($material) {
                    $q->where('part_number', $material)
                      ->orWhere('part_number', 'P-' . $material)
                      ->orWhere('part_number', 'P' . $material);
                })
                ->whereNotIn('status', ['complete', 'cancel'])
                ->get();
            foreach ($items as $item) {
                $qtyPama = (int)$item->qty;
                $remarks = $qty >= $qtyPama ? 'READY ONSITE' : 'NOT READY ONSITE';
                $item->update([
                    'referensi_dokumen' => $refDok,
                    'material'          => $material,
                    'qty_zboh'          => $qty,
                    'remarks'           => $remarks,
                ]);
            }
        }
    }


    public function destroyFromCancel($id)
    {
        $item = TbBacklogDaily::findOrFail($id);
        $item->delete();
        return redirect()->route('cancel.index')->with('success', 'Data berhasil dihapus!');
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