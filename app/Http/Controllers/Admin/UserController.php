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
    // Add this method to your UserController class

public function create()
{
    return view('admin.users.create');
}

public function store(Request $request)
{
    // Validation rules
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|string|max:20',
        'date_of_birth' => 'nullable|date',
        'role' => 'required|in:admin,client,fournisseur',
        'password' => 'required|string|min:8|confirmed',
        'is_active' => 'boolean',
        'email_verified' => 'boolean',
        'send_welcome_email' => 'boolean'
    ]);

    // Create the user
    $user = User::create([
        'first_name' => $validated['first_name'],
        'last_name' => $validated['last_name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'date_of_birth' => $validated['date_of_birth'],
        'role' => $validated['role'],
        'password' => bcrypt($validated['password']),
        'is_active' => $request->boolean('is_active', true),
        'email_verified_at' => $request->boolean('email_verified') ? now() : null,
    ]);

    // Send welcome email if requested
    if ($request->boolean('send_welcome_email')) {
        // You can implement email sending here
        // Mail::to($user->email)->send(new WelcomeEmail($user));
    }

    return redirect()->route('admin.users.index')
        ->with('success', "User '{$user->full_name}' has been created successfully!");
}

public function edit(User $user)
{
    return view('admin.users.edit', compact('user'));
}

public function update(Request $request, User $user)
{
    // Validation rules
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'date_of_birth' => 'nullable|date',
        'role' => 'required|in:admin,client,fournisseur',
        'password' => 'nullable|string|min:8|confirmed',
        'is_active' => 'boolean',
    ]);

    // Update user data
    $updateData = [
        'first_name' => $validated['first_name'],
        'last_name' => $validated['last_name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'date_of_birth' => $validated['date_of_birth'],
        'role' => $validated['role'],
        'is_active' => $request->boolean('is_active', true),
    ];

    // Only update password if provided
    if ($validated['password']) {
        $updateData['password'] = bcrypt($validated['password']);
    }

    $user->update($updateData);

    return redirect()->route('admin.users.index')
        ->with('success', "User '{$user->full_name}' has been updated successfully!");
}
/**
 * Export users to CSV
 */
public function export(Request $request)
{
    try {
        // Get the same filtered users as the index method
        $query = User::query();

        // Apply the same filters as index method
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }

        if ($request->filled('status')) {
            if ($request->get('status') === 'active') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('first_name', 'asc')->orderBy('last_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('first_name', 'desc')->orderBy('last_name', 'desc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }

        $users = $query->get();

        // Generate CSV content
        $csvData = [];
        
        // CSV Headers
        $csvData[] = [
            'ID',
            'First Name',
            'Last Name',
            'Full Name',
            'Email',
            'Phone',
            'Role',
            'Status',
            'Email Verified',
            'Last Login',
            'Created At',
            'Updated At'
        ];

        // CSV Data
        foreach ($users as $user) {
            $csvData[] = [
                $user->id,
                $user->first_name,
                $user->last_name,
                $user->full_name,
                $user->email,
                $user->phone ?: 'N/A',
                ucfirst($user->role),
                $user->is_active ? 'Active' : 'Inactive',
                $user->email_verified_at ? 'Yes' : 'No',
                $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never',
                $user->created_at->format('Y-m-d H:i:s'),
                $user->updated_at->format('Y-m-d H:i:s')
            ];
        }

        // Generate filename with timestamp and filters
        $filename = 'users_export_' . now()->format('Y_m_d_H_i_s');
        
        if ($request->filled('role')) {
            $filename .= '_' . $request->get('role');
        }
        
        if ($request->filled('status')) {
            $filename .= '_' . $request->get('status');
        }
        
        $filename .= '.csv';

        // Create CSV content
        $csvContent = '';
        foreach ($csvData as $row) {
            $csvContent .= '"' . implode('","', $row) . '"' . "\n";
        }

        // Return CSV response
        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
            'Pragma' => 'public',
        ]);

    } catch (\Exception $e) {
        // Log the error
        \Log::error('User export failed: ' . $e->getMessage());
        
        // Return error response
        return response()->json([
            'success' => false,
            'message' => 'Export failed. Please try again.'
        ], 500);
    }
}
}