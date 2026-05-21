<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TbOrder;
use App\Models\TbBacklog;
use App\Models\TbVendor;
use App\Models\TbPlanner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    // List all orders
    public function index(Request $request)
    {
        $query = TbOrder::with(['backlog', 'vendor', 'planner', 'creator']);

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
            $query->whereBetween('tanggal_order', [$request->start_date, $request->end_date]);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('po_number', 'like', "%{$search}%")
                  ->orWhere('wr_number', 'like', "%{$search}%")
                  ->orWhere('part_number', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest('tanggal_order')->paginate(20);
        
        $vendors = TbVendor::active()->get();
        $planners = TbPlanner::active()->get();

        return view('order.index', compact('orders', 'vendors', 'planners'));
    }

    // Show create form
    public function create()
    {
        $backlogs = TbBacklog::whereIn('status', ['pending', 'in_progress'])->get();
        $vendors = TbVendor::active()->get();
        $planners = TbPlanner::active()->get();

        return view('order.create', compact('backlogs', 'vendors', 'planners'));
    }

    // Store new order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'backlog_id' => 'required|exists:tb_backlog,id',
            'tanggal_order' => 'required|date',
            'part_number' => 'required|string|max:100',
            'part_name' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'vendor_id' => 'required|exists:tb_vendor,id',
            'planner_id' => 'required|exists:tb_planner,id',
            'po_number' => 'nullable|string|max:50',
            'wr_number' => 'nullable|string|max:50',
            'expected_arrival' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        // Generate order number
        $validated['order_number'] = TbOrder::generateOrderNumber();
        $validated['status'] = 'ordered';
        $validated['created_by'] = Auth::id();

        TbOrder::create($validated);

        // Update backlog status
        $backlog = TbBacklog::find($validated['backlog_id']);
        if ($backlog && $backlog->status === 'pending') {
            $backlog->update(['status' => 'in_progress']);
        }

        return redirect()->route('order.index')
            ->with('success', 'Order berhasil dibuat!');
    }

    // Show detail
    public function show($id)
    {
        $order = TbOrder::with(['backlog', 'vendor', 'planner', 'creator'])
            ->findOrFail($id);

        return view('order.show', compact('order'));
    }

    // Show edit form
    public function edit($id)
    {
        $order = TbOrder::findOrFail($id);
        $backlogs = TbBacklog::whereIn('status', ['pending', 'in_progress'])->get();
        $vendors = TbVendor::active()->get();
        $planners = TbPlanner::active()->get();

        return view('order.edit', compact('order', 'backlogs', 'vendors', 'planners'));
    }

    // Update order
    public function update(Request $request, $id)
    {
        $order = TbOrder::findOrFail($id);

        $validated = $request->validate([
            'backlog_id' => 'required|exists:tb_backlog,id',
            'tanggal_order' => 'required|date',
            'part_number' => 'required|string|max:100',
            'part_name' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'status' => 'required|in:ordered,confirmed,shipped,received,cancelled',
            'vendor_id' => 'required|exists:tb_vendor,id',
            'planner_id' => 'required|exists:tb_planner,id',
            'po_number' => 'nullable|string|max:50',
            'wr_number' => 'nullable|string|max:50',
            'expected_arrival' => 'nullable|date',
            'actual_arrival' => 'nullable|date',
            'tracking_number' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        // Set actual arrival if status is received
        if ($validated['status'] === 'received' && $order->status !== 'received') {
            $validated['actual_arrival'] = now();
            
            // Update backlog to complete
            $backlog = TbBacklog::find($validated['backlog_id']);
            if ($backlog) {
                $backlog->update([
                    'status' => 'complete',
                    'completion_date' => now()
                ]);
            }
        }

        $order->update($validated);

        return redirect()->route('order.index')
            ->with('success', 'Order berhasil diupdate!');
    }

    // Delete order
    public function destroy($id)
    {
        $order = TbOrder::findOrFail($id);
        $order->delete();

        return redirect()->route('order.index')
            ->with('success', 'Order berhasil dihapus!');
    }

    // Update status (AJAX)
    public function updateStatus(Request $request, $id)
    {
        $order = TbOrder::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:ordered,confirmed,shipped,received,cancelled'
        ]);

        $data = ['status' => $validated['status']];

        // Set actual arrival if received
        if ($validated['status'] === 'received') {
            $data['actual_arrival'] = now();
            
            // Update backlog
            if ($order->backlog) {
                $order->backlog->update([
                    'status' => 'complete',
                    'completion_date' => now()
                ]);
            }
        }

        $order->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Status order berhasil diupdate!',
            'data' => $order->fresh()
        ]);
    }

    // Track order (public or authenticated)
    public function track(Request $request)
    {
        $orderNumber = $request->input('order_number');
        
        if (!$orderNumber) {
            return view('order.track');
        }

        $order = TbOrder::with(['backlog', 'vendor', 'planner'])
            ->where('order_number', $orderNumber)
            ->orWhere('tracking_number', $orderNumber)
            ->first();

        if (!$order) {
            return view('order.track')->with('error', 'Order tidak ditemukan!');
        }

        return view('order.track', compact('order'));
    }
}