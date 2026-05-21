<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    // List all users
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('user.index', compact('users'));
    }

    // Show create form
    public function create()
    {
        return view('user.create');
    }

    // Store new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,vendor,planner',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    // Show detail
    public function show($id)
    {
        $user = User::with(['planner', 'vendor'])->findOrFail($id);

        return view('user.show', compact('user'));
    }

    // Show edit form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,vendor,planner',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        // Update password only if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil diupdate!');
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus!');
    }

    // Update status (AJAX)
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status user berhasil diupdate!',
            'data' => $user
        ]);
    }

    // Reset password
    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return back()->with('success', 'Password user berhasil direset!');
    }

    // Profile page (for current user)
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    // Update profile (current user)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:100',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile berhasil diupdate!');
    }

    // Change password (current user)
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.'
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    // User activity log
    public function activityLog($id)
    {
        $user = User::findOrFail($id);

        // Activity log logic
        // Can use spatie/laravel-activitylog package or custom implementation

        return view('user.activity-log', compact('user'));
    }
}