@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-4xl font-bold mb-2">User Management Center</h1>
                <p class="text-xl opacity-90 mb-4">Manage and monitor all platform users efficiently</p>
                <div class="flex items-center space-x-6 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-users mr-2"></i>
                        <span>{{ number_format($stats['total']) }} Total Users</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-user-check mr-2"></i>
                        <span>{{ number_format($stats['active']) }} Active</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        <span>Last updated: {{ now()->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users-cog text-6xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Total Users Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-blue-100 text-sm">Total Users</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['total']) }}</h3>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full h-2 mb-2">
                        <div class="bg-white h-2 rounded-full" style="width: 100%"></div>
                    </div>
                    <p class="text-xs text-blue-200">Platform members</p>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-user-check text-xl"></i>
                        </div>
                        <div>
                            <p class="text-green-100 text-sm">Active Users</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['active']) }}</h3>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full h-2 mb-2">
                        <div class="bg-white h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['active'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-green-200">{{ $stats['total'] > 0 ? round(($stats['active'] / $stats['total']) * 100) : 0 }}% of total</p>
                </div>
            </div>
        </div>

        <!-- Clients -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-user text-xl"></i>
                        </div>
                        <div>
                            <p class="text-purple-100 text-sm">Clients</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['clients']) }}</h3>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full h-2 mb-2">
                        <div class="bg-white h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['clients'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-purple-200">Land buyers</p>
                </div>
            </div>
        </div>

        <!-- Suppliers -->
        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-building text-xl"></i>
                        </div>
                        <div>
                            <p class="text-yellow-100 text-sm">Suppliers</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['fournisseurs']) }}</h3>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full h-2 mb-2">
                        <div class="bg-white h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['fournisseurs'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-yellow-200">Land sellers</p>
                </div>
            </div>
        </div>

        <!-- Admins -->
        <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-crown text-xl"></i>
                        </div>
                        <div>
                            <p class="text-red-100 text-sm">Administrators</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['admins']) }}</h3>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full h-2 mb-2">
                        <div class="bg-white h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['admins'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-red-200">Platform admins</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filters -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Advanced Filters</h3>
                <p class="text-gray-600">Find and filter users with precision</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all">
                    <i class="fas fa-download mr-2"></i>Export Users
                </button>
                <button class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition-all">
                    <i class="fas fa-user-plus mr-2"></i>Add User
                </button>
            </div>
        </div>
        
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-2 text-blue-500"></i>Search Users
                </label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Name or email..."
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user-tag mr-2 text-purple-500"></i>User Role
                </label>
                <select name="role" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>👑 Administrator</option>
                    <option value="client" {{ request('role') === 'client' ? 'selected' : '' }}>👤 Client</option>
                    <option value="fournisseur" {{ request('role') === 'fournisseur' ? 'selected' : '' }}>🏢 Supplier</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on mr-2 text-green-500"></i>Account Status
                </label>
                <select name="status" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>✅ Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>❌ Inactive</option>
                </select>
            </div>

            <div class="flex items-end space-x-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-6 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 py-3 px-4 rounded-xl hover:bg-gray-300 transition-all">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Enhanced Users Table -->
    <div class="glass-effect rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-b">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Users Directory</h3>
                    <p class="text-gray-600">{{ $users->total() }} users found</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- View toggles -->
                    <div class="flex bg-white rounded-lg p-1 shadow-inner">
                        <button class="px-3 py-1 bg-blue-500 text-white rounded text-sm">Grid</button>
                        <button class="px-3 py-1 text-gray-600 rounded text-sm hover:bg-gray-100">List</button>
                    </div>
                    
                    <!-- Sort options -->
                    <select class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        <option>Sort: Newest</option>
                        <option>Sort: Oldest</option>
                        <option>Sort: Name A-Z</option>
                        <option>Sort: Name Z-A</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2 text-blue-500"></i>User Profile
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-envelope mr-2 text-green-500"></i>Contact Info
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-user-tag mr-2 text-purple-500"></i>Role & Status
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2 text-yellow-500"></i>Activity
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-red-500"></i>Joined
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-cogs mr-2 text-gray-500"></i>Actions
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-300">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-14 w-14 relative">
                                        <div class="h-14 w-14 rounded-xl bg-gradient-to-r from-{{ $user->role === 'admin' ? 'red' : ($user->role === 'client' ? 'blue' : 'green') }}-400 to-{{ $user->role === 'admin' ? 'pink' : ($user->role === 'client' ? 'purple' : 'yellow') }}-500 flex items-center justify-center shadow-lg">
                                            <span class="text-white font-bold text-lg">
                                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                        @if($user->is_active)
                                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full"></div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            <a href="{{ route('admin.users.show', $user) }}" class="text-lg font-bold text-gray-900 hover:text-blue-600 transition-colors">
                                                {{ $user->full_name }}
                                            </a>
                                            @if($user->email_verified_at)
                                                <i class="fas fa-check-circle text-blue-500 ml-2" title="Verified"></i>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 flex items-center">
                                            <span class="mr-2">ID: {{ $user->id }}</span>
                                            @if($user->role === 'admin')
                                                <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs">👑 Admin</span>
                                            @elseif($user->role === 'client')
                                                <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs">👤 Client</span>
                                            @else
                                                <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">🏢 Supplier</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-6">
                                <div class="space-y-2">
                                    <div class="flex items-center text-gray-900">
                                        <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                        <a href="mailto:{{ $user->email }}" class="hover:text-blue-600 transition-colors">{{ $user->email }}</a>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-phone text-gray-400 mr-2"></i>
                                        <span>{{ $user->phone ?: 'Not provided' }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-6">
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                            @if($user->role === 'admin') bg-gradient-to-r from-red-100 to-pink-100 text-red-800
                                            @elseif($user->role === 'client') bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800
                                            @else bg-gradient-to-r from-green-100 to-yellow-100 text-green-800 @endif">
                                            @if($user->role === 'admin') 👑 @elseif($user->role === 'client') 👤 @else 🏢 @endif
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $user->is_active ? 'bg-gradient-to-r from-green-100 to-green-200 text-green-800' : 'bg-gradient-to-r from-red-100 to-red-200 text-red-800' }}">
                                            {{ $user->is_active ? '✅ Active' : '❌ Inactive' }}
                                        </span>
                                        
                                        @if($user->email_verified_at)
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800">
                                                ✓ Verified
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800">
                                                ⚠ Unverified
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-6">
                                <div class="space-y-1">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $user->last_login_at ? $user->last_login_at->format('M d, Y') : 'Never logged in' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'No activity' }}
                                    </div>
                                    @if($user->last_login_at && $user->last_login_at->isToday())
                                        <div class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse"></div>
                                            Online today
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-6">
                                <div class="space-y-1">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-6">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-110" 
                                       title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    
                                    @if($user->id !== auth()->id())
                                        <button onclick="toggleUserStatus({{ $user->id }})" 
                                                class="bg-gradient-to-r from-{{ $user->is_active ? 'red' : 'green' }}-500 to-{{ $user->is_active ? 'red' : 'green' }}-600 text-white p-2 rounded-lg hover:from-{{ $user->is_active ? 'red' : 'green' }}-600 hover:to-{{ $user->is_active ? 'red' : 'green' }}-700 transition-all transform hover:scale-110" 
                                                title="{{ $user->is_active ? 'Deactivate' : 'Activate' }} User">
                                            <i class="fas fa-toggle-{{ $user->is_active ? 'on' : 'off' }} text-sm"></i>
                                        </button>
                                        
                                        <button onclick="deleteUser({{ $user->id }})" 
                                                class="bg-gradient-to-r from-red-500 to-red-600 text-white p-2 rounded-lg hover:from-red-600 hover:to-red-700 transition-all transform hover:scale-110" 
                                                title="Delete User">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    @else
                                        <div class="flex items-center space-x-2 bg-gray-100 px-3 py-2 rounded-lg">
                                            <i class="fas fa-lock text-gray-400"></i>
                                            <span class="text-gray-500 text-xs">Your Account</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-gradient-to-r from-gray-200 to-gray-300 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-users text-4xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">No Users Found</h3>
                                    <p class="text-gray-600 mb-4">No users match your current filters</p>
                                    <a href="{{ route('admin.users.index') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all">
                                        <i class="fas fa-refresh mr-2"></i>Reset Filters
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Enhanced Pagination -->
        @if($users->hasPages())
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <span>Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- User Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- User Growth Chart -->
        <div class="lg:col-span-2 glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">User Registration Trends</h3>
                    <p class="text-gray-600">Monthly user registration analytics</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">New Registrations</span>
                    </div>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="userRegistrationChart"></canvas>
            </div>
        </div>

        <!-- User Distribution -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">User Distribution</h3>
                    <p class="text-gray-600">By role and status</p>
                </div>
            </div>
            
            <!-- Role Distribution -->
            <div class="space-y-4 mb-6">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Clients</span>
                        <span class="font-semibold">{{ $stats['clients'] }} ({{ $stats['total'] > 0 ? round(($stats['clients'] / $stats['total']) * 100) : 0 }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['clients'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Suppliers</span>
                        <span class="font-semibold">{{ $stats['fournisseurs'] }} ({{ $stats['total'] > 0 ? round(($stats['fournisseurs'] / $stats['total']) * 100) : 0 }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-500 to-yellow-500 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['fournisseurs'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Administrators</span>
                        <span class="font-semibold">{{ $stats['admins'] }} ({{ $stats['total'] > 0 ? round(($stats['admins'] / $stats['total']) * 100) : 0 }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-red-500 to-pink-500 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['admins'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Status Pie Chart -->
            <div class="relative h-48">
                <canvas id="userStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Quick Management Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <button class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-105 group">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-user-plus text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Action</span>
                </div>
                <h4 class="font-bold text-lg mb-2">Add New User</h4>
                <p class="text-sm opacity-90">Create a new user account</p>
            </button>
            
            <button class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl hover:from-green-600 hover:to-green-700 transition-all transform hover:scale-105 group">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-file-export text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Export</span>
                </div>
                <h4 class="font-bold text-lg mb-2">Export Users</h4>
                <p class="text-sm opacity-90">Download user data as CSV</p>
            </button>
            
            <button class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all transform hover:scale-105 group">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-envelope text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Bulk</span>
                </div>
                <h4 class="font-bold text-lg mb-2">Send Newsletter</h4>
                <p class="text-sm opacity-90">Send bulk emails to users</p>
            </button>
            
            <button class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white p-6 rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all transform hover:scale-105 group">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-chart-bar text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Report</span>
                </div>
                <h4 class="font-bold text-lg mb-2">User Analytics</h4>
                <p class="text-sm opacity-90">Detailed user reports</p>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// User Registration Trend Chart
const registrationCtx = document.getElementById('userRegistrationChart').getContext('2d');
new Chart(registrationCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'New Registrations',
            data: [12, 19, 15, 25, 22, 30],
            borderColor: '#3B82F6',
            backgroundColor: function(context) {
                const chart = context.chart;
                const {ctx, chartArea} = chart;
                if (!chartArea) return null;
                
                const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
                gradient.addColorStop(1, 'rgba(59, 130, 246, 0.05)');
                return gradient;
            },
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#3B82F6',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 3,
            pointRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                borderColor: '#3B82F6',
                borderWidth: 1,
                cornerRadius: 8
            }
        },
        scales: {
            x: {
                grid: { display: false },
                border: { display: false }
            },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                border: { display: false }
            }
        }
    }
});

// User Status Distribution Chart
const statusCtx = document.getElementById('userStatusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Active Users', 'Inactive Users'],
        datasets: [{
            data: [{{ $stats['active'] }}, {{ $stats['total'] - $stats['active'] }}],
            backgroundColor: [
                'rgba(16, 185, 129, 0.8)',
                'rgba(239, 68, 68, 0.8)'
            ],
            borderColor: [
                '#10B981',
                '#EF4444'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                borderColor: '#10B981',
                borderWidth: 1,
                cornerRadius: 8
            }
        }
    }
});

// Enhanced JavaScript functions
async function toggleUserStatus(userId) {
    if (!confirm('Are you sure you want to toggle this user\'s status?')) return;
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i>';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/users/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(result.message, 'error');
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    } catch (error) {
        showAlert('An error occurred', 'error');
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

async function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) return;
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i>';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            // Fade out the row
            const row = button.closest('tr');
            row.style.transition = 'all 0.5s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateX(-100%)';
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(result.message, 'error');
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    } catch (error) {
        showAlert('An error occurred', 'error');
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 z-50 p-6 rounded-xl shadow-2xl transform transition-all duration-500 ${
        type === 'success' ? 'bg-gradient-to-r from-green-500 to-green-600 text-white' :
        'bg-gradient-to-r from-red-500 to-red-600 text-white'
    }`;
    
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            </div>
            <div class="flex-1">
                <div class="font-semibold">${type === 'success' ? 'Success!' : 'Error!'}</div>
                <div class="text-sm opacity-90">${message}</div>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 w-6 h-6 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-all">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Animate in
    setTimeout(() => {
        alertDiv.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.style.transform = 'translateX(100%)';
            setTimeout(() => alertDiv.remove(), 500);
        }
    }, 5000);
}

// Add loading states to search and filter forms
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalContent = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Searching...';
                submitBtn.disabled = true;
            }
        });
    });

    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.zIndex = '10';
        });
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.zIndex = 'auto';
        });
    });

    // Animate statistics cards on load
    const statCards = document.querySelectorAll('.card-hover');
    statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush
@endsection