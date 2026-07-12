<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TbPlanner;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PlannerController extends Controller
{

    // List all planners
    public function index(Request $request)
    {
        $query = TbPlanner::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by company
        if ($request->filled('perusahaan')) {
            $query->where('perusahaan', $request->perusahaan);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        $planners = $query->paginate(20);

        return view('planner.index', compact('planners'));
    }

    // Show create form
    public function create()
    {
        return view('planner.create');
    }

    // Store new planner
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:tb_planner,email',
            'telepon' => 'required|string|max:20',
            'perusahaan' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'department' => 'nullable|string|max:100',
            
            // User account
            'create_user' => 'nullable|boolean',
            'user_name' => 'required_if:create_user,1|string|max:255',
            'user_email' => 'required_if:create_user,1|email|unique:users,email',
            'user_password' => 'required_if:create_user,1|string|min:6',
        ]);

        $plannerData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telepon' => $validated['telepon'],
            'perusahaan' => $validated['perusahaan'],
            'jabatan' => $validated['jabatan'],
            'department' => $validated['department'] ?? null,
            'status' => 'active',
        ];

        // Create user account if requested
        if ($request->filled('create_user') && $request->create_user) {
            $user = User::create([
                'name' => $validated['user_name'],
                'email' => $validated['user_email'],
                'password' => Hash::make($validated['user_password']),
                'role' => 'planner',
                'status' => 'active',
                'company' => $validated['perusahaan'],
            ]);

            $plannerData['user_id'] = $user->id;
        }

        TbPlanner::create($plannerData);

        return redirect()->route('planner.index')
            ->with('success', 'Planner berhasil ditambahkan!');
    }

    // Show detail
    public function show($id)
    {
        $planner = TbPlanner::with(['user', 'backlogDaily', 'orders'])
            ->withCount(['backlogDaily', 'orders'])
            ->findOrFail($id);

        // Calculate statistics
        $stats = [
            'total_backlog_handled' => $planner->getTotalBacklogHandled(),
            'pending_orders' => $planner->getPendingOrders(),
        ];

        return view('planner.show', compact('planner', 'stats'));
    }

    // Show edit form
    public function edit($id)
    {
        $planner = TbPlanner::with('user')->findOrFail($id);
        return view('planner.edit', compact('planner'));
    }

    // Update planner
    public function update(Request $request, $id)
    {
        $planner = TbPlanner::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:tb_planner,email,' . $id,
            'telepon' => 'required|string|max:20',
            'perusahaan' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'department' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $planner->update($validated);

        // Update user account if exists
        if ($planner->user) {
            $planner->user->update([
                'name' => $validated['name'],
                'company' => $validated['perusahaan'],
                'status' => $validated['status'],
            ]);
        }

        return redirect()->route('planner.index')
            ->with('success', 'Planner berhasil diupdate!');
    }

    // Delete planner
    public function destroy($id)
    {
        $planner = TbPlanner::findOrFail($id);
        
        // Check if planner has related data
        if ($planner->backlogDaily()->count() > 0 || $planner->orders()->count() > 0) {
            return back()->with('error', 'Planner tidak dapat dihapus karena memiliki data terkait!');
        }

        // Delete user account if exists
        if ($planner->user) {
            $planner->user->delete();
        }

        $planner->delete();

        return redirect()->route('planner.index')
            ->with('success', 'Planner berhasil dihapus!');
    }

    // Update status (AJAX)
    public function updateStatus(Request $request, $id)
    {
        $planner = TbPlanner::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $planner->update($validated);

        // Update user status too
        if ($planner->user) {
            $planner->user->update(['status' => $validated['status']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status planner berhasil diupdate!',
            'data' => $planner
        ]);
    }

    // Get planner activity report
    public function activity($id)
    {
        $planner = TbPlanner::with(['backlogDaily', 'orders'])
            ->findOrFail($id);

        $activity = [
            'total_backlog' => $planner->backlogDaily()->count(),
            'ready_backlog' => $planner->backlogDaily()->where('is_ready', true)->count(),
            'pending_backlog' => $planner->backlogDaily()->where('is_ready', false)->count(),
            'total_orders' => $planner->orders()->count(),
            'completed_orders' => $planner->orders()->where('status', 'received')->count(),
            'pending_orders' => $planner->orders()->whereNotIn('status', ['received', 'cancelled'])->count(),
        ];

        return view('planner.activity', compact('planner', 'activity'));
    }
}