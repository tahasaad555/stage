<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['client', 'fournisseur']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->byRole($request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } else {
                $query->where('is_active', false);
            }
        }

        $users = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total' => User::count(),
            'active' => User::active()->count(),
            'inactive' => User::where('is_active', false)->count(),
            'clients' => User::byRole('client')->count(),
            'fournisseurs' => User::byRole('fournisseur')->count(),
            'admins' => User::byRole('admin')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        $user->load(['client', 'fournisseur']);
        
        return view('admin.users.show', compact('user'));
    }

    public function toggleStatus(User $user)
    {
        // Prevent admin from deactivating themselves
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot deactivate your own account.'
            ], 422);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'message' => "User {$status} successfully.",
            'status' => $user->is_active
        ]);
    }

    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account.'
            ], 422);
        }

        $user->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}