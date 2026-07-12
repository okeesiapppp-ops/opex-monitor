<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TbVendor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{

    // List all vendors
    public function index(Request $request)
    {
        $query = TbVendor::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_vendor', 'like', "%{$search}%")
                  ->orWhere('pic', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $vendors = $query->paginate(20);

        return view('vendor.index', compact('vendors'));
    }

    // Show create form
    public function create()
    {
        return view('vendor.create');
    }

    // Store new vendor
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_vendor' => 'required|string|max:100',
            'pic' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|unique:tb_vendor,email',
            'alamat' => 'nullable|string',
            'code' => 'required|string|max:20|unique:tb_vendor,kode_vendor',
            
            // User account
            'create_user' => 'nullable|boolean',
            'user_name' => 'required_if:create_user,1|string|max:255',
            'user_email' => 'required_if:create_user,1|email|unique:users,email',
            'user_password' => 'required_if:create_user,1|string|min:6',
        ]);

        $vendorData = [
            'nama_vendor' => $validated['nama_vendor'],
            'pic' => $validated['pic'],
            'telepon' => $validated['telepon'],
            'email' => $validated['email'],
            'alamat' => $validated['alamat'] ?? null,
            'code' => $validated['code'],
            'status' => 'active',
        ];

        // Create user account if requested
        if ($request->filled('create_user') && $request->create_user) {
            $user = User::create([
                'name' => $validated['user_name'],
                'email' => $validated['user_email'],
                'password' => Hash::make($validated['user_password']),
                'role' => 'vendor',
                'status' => 'active',
                'company' => $validated['nama_vendor'],
            ]);

            $vendorData['user_id'] = $user->id;
        }

        TbVendor::create($vendorData);

        return redirect()->route('vendor.index')
            ->with('success', 'Vendor berhasil ditambahkan!');
    }

    // Show detail
    public function show($id)
    {
        $vendor = TbVendor::with(['user', 'backlogDaily', 'orders', 'reconciliations'])
            ->withCount(['backlogDaily', 'orders'])
            ->findOrFail($id);

        // Calculate statistics
        $stats = [
            'total_parts' => $vendor->getTotalPartsSupplied(),
            'pending_supplies' => $vendor->getPendingSupplies(),
            'fulfillment_rate' => $vendor->getFulfillmentRate(),
        ];

        return view('vendor.show', compact('vendor', 'stats'));
    }

    // Show edit form
    public function edit($id)
    {
        $vendor = TbVendor::with('user')->findOrFail($id);
        return view('vendor.edit', compact('vendor'));
    }

    // Update vendor
    public function update(Request $request, $id)
    {
        $vendor = TbVendor::findOrFail($id);

        $validated = $request->validate([
            'nama_vendor' => 'required|string|max:100',
            'pic' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|unique:tb_vendor,email,' . $id,
            'alamat' => 'nullable|string',
            'code' => 'required|string|max:20|unique:tb_vendor,kode_vendor,' . $id,
            'status' => 'required|in:active,inactive',
        ]);

        $vendor->update($validated);

        // Update user account if exists
        if ($vendor->user) {
            $vendor->user->update([
                'name' => $validated['pic'],
                'company' => $validated['nama_vendor'],
                'status' => $validated['status'],
            ]);
        }

        return redirect()->route('vendor.index')
            ->with('success', 'Vendor berhasil diupdate!');
    }

    // Delete vendor
    public function destroy($id)
    {
        $vendor = TbVendor::findOrFail($id);
        
        // Check if vendor has related data
        if ($vendor->backlogDaily()->count() > 0 || $vendor->orders()->count() > 0) {
            return back()->with('error', 'Vendor tidak dapat dihapus karena memiliki data terkait!');
        }

        // Delete user account if exists
        if ($vendor->user) {
            $vendor->user->delete();
        }

        $vendor->delete();

        return redirect()->route('vendor.index')
            ->with('success', 'Vendor berhasil dihapus!');
    }

    // Update status (AJAX)
    public function updateStatus(Request $request, $id)
    {
        $vendor = TbVendor::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $vendor->update($validated);

        // Update user status too
        if ($vendor->user) {
            $vendor->user->update(['status' => $validated['status']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status vendor berhasil diupdate!',
            'data' => $vendor
        ]);
    }

    // Get vendor performance report
    public function performance($id)
    {
        $vendor = TbVendor::with(['backlogDaily', 'orders'])
            ->findOrFail($id);

        $performance = [
            'total_supplies' => $vendor->backlogDaily()->count(),
            'ready_supplies' => $vendor->backlogDaily()->where('is_ready', true)->count(),
            'pending_supplies' => $vendor->backlogDaily()->where('is_ready', false)->count(),
            'total_orders' => $vendor->orders()->count(),
            'completed_orders' => $vendor->orders()->where('status', 'received')->count(),
            'pending_orders' => $vendor->orders()->whereNotIn('status', ['received', 'cancelled'])->count(),
            'fulfillment_rate' => $vendor->getFulfillmentRate(),
        ];

        return view('vendor.performance', compact('vendor', 'performance'));
    }
}