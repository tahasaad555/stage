@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Section with Time-based Greeting -->
    <div class="bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-4xl font-bold mb-2">
                    <span id="greeting">Good morning</span>, {{ auth()->user()->first_name }}! 👋
                </h1>
                <p class="text-xl opacity-90 mb-4">Welcome to your AgriTerre command center</p>
                <div class="flex items-center space-x-6 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span id="current-date"></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        <span id="current-time"></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-thermometer-half mr-2"></i>
                        <span>22°C, Sunny</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-6xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-blue-100 text-sm">Total Users</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['total_users']) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-blue-200">Clients:</span>
                            <span class="font-semibold">{{ $stats['total_clients'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-blue-200">Suppliers:</span>
                            <span class="font-semibold">{{ $stats['total_fournisseurs'] }}</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 78%"></div>
                        </div>
                        <p class="text-xs text-blue-200">+12% from last month</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="w-16 h-16 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <canvas id="usersChart" width="50" height="50"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agricultural Lands Card -->
        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-map text-xl"></i>
                        </div>
                        <div>
                            <p class="text-green-100 text-sm">Agricultural Lands</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['total_lands']) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-green-200">Available:</span>
                            <span class="font-semibold">{{ $stats['available_lands'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-green-200">Total Hectares:</span>
                            <span class="font-semibold">1,247 ha</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 65%"></div>
                        </div>
                        <p class="text-xs text-green-200">+8% new listings</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="w-16 h-16 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <canvas id="landsChart" width="50" height="50"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Listings Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-bullhorn text-xl"></i>
                        </div>
                        <div>
                            <p class="text-purple-100 text-sm">Active Listings</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['total_annonces']) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-purple-200">Featured:</span>
                            <span class="font-semibold">23</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-purple-200">Active:</span>
                            <span class="font-semibold">{{ $stats['active_annonces'] }}</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 85%"></div>
                        </div>
                        <p class="text-xs text-purple-200">+15% engagement</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="w-16 h-16 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <canvas id="listingsChart" width="50" height="50"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-dollar-sign text-xl"></i>
                        </div>
                        <div>
                            <p class="text-yellow-100 text-sm">Total Revenue</p>
                            <h3 class="text-3xl font-bold">${{ number_format($stats['total_revenue'], 0) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-yellow-200">Commission:</span>
                            <span class="font-semibold">${{ number_format($stats['total_commission'], 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-yellow-200">This Month:</span>
                            <span class="font-semibold">$12,450</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 92%"></div>
                        </div>
                        <p class="text-xs text-yellow-200">+24% from last month</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="w-16 h-16 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <canvas id="revenueChart" width="50" height="50"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- User Growth Chart -->
        <div class="lg:col-span-2 glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">User Growth Analytics</h3>
                    <p class="text-gray-600">Track user registration trends over time</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">New Users</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Active Users</span>
                    </div>
                </div>
            </div>
            <div class="relative h-80">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>

        <!-- Quick Stats Panel -->
        <div class="space-y-6">
            <!-- Performance Metrics -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <h4 class="text-lg font-bold text-gray-800 mb-4">Performance Metrics</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-arrow-up text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Conversion Rate</p>
                                <p class="font-bold text-gray-800">23.5%</p>
                            </div>
                        </div>
                        <div class="text-green-600 text-sm font-semibold">+5.2%</div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-eye text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Page Views</p>
                                <p class="font-bold text-gray-800">45.2K</p>
                            </div>
                        </div>
                        <div class="text-blue-600 text-sm font-semibold">+12.1%</div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Avg. Session</p>
                                <p class="font-bold text-gray-800">8m 42s</p>
                            </div>
                        </div>
                        <div class="text-purple-600 text-sm font-semibold">+2.4%</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <h4 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h4>
                <div class="space-y-3">
                    <button class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-4 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Land
                    </button>
                    <button class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-3 px-4 rounded-xl hover:from-green-600 hover:to-green-700 transition-all flex items-center justify-center">
                        <i class="fas fa-bullhorn mr-2"></i>
                        Create Listing
                    </button>
                    <button class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white py-3 px-4 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all flex items-center justify-center">
                        <i class="fas fa-users mr-2"></i>
                        Manage Users
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue and Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Revenue Chart -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Revenue Trends</h3>
                    <p class="text-gray-600">Monthly revenue and commission tracking</p>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg">
                    <span class="text-sm font-semibold">+24% Growth</span>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="revenueAreaChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Recent Activity</h3>
                    <p class="text-gray-600">Latest platform activities</p>
                </div>
                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</button>
            </div>
            <div class="space-y-4">
                <div class="flex items-center space-x-4 p-4 bg-blue-50 rounded-xl">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-plus text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">New user registered</p>
                        <p class="text-sm text-gray-600">John Doe joined as a client</p>
                    </div>
                    <span class="text-xs text-gray-500">2m ago</span>
                </div>
                
                <div class="flex items-center space-x-4 p-4 bg-green-50 rounded-xl">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-handshake text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">Transaction completed</p>
                        <p class="text-sm text-gray-600">Land sale of €45,000 processed</p>
                    </div>
                    <span class="text-xs text-gray-500">15m ago</span>
                </div>
                
                <div class="flex items-center space-x-4 p-4 bg-purple-50 rounded-xl">
                    <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">New land listing</p>
                        <p class="text-sm text-gray-600">Premium land in Marrakech added</p>
                    </div>
                    <span class="text-xs text-gray-500">1h ago</span>
                </div>
                
                <div class="flex items-center space-x-4 p-4 bg-yellow-50 rounded-xl">
                    <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">Listing featured</p>
                        <p class="text-sm text-gray-600">Casablanca property promoted</p>
                    </div>
                    <span class="text-xs text-gray-500">3h ago</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users and Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Users -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Recent Users</h3>
                    <p class="text-gray-600">Newly registered members</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all text-sm">
                    View All Users
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentUsers as $user)
                    <div class="flex items-center justify-between p-4 hover:bg-white hover:bg-opacity-50 rounded-xl transition-all">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-{{ $user->role === 'admin' ? 'purple' : ($user->role === 'client' ? 'blue' : 'green') }}-400 to-{{ $user->role === 'admin' ? 'purple' : ($user->role === 'client' ? 'blue' : 'green') }}-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">
                                    {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $user->full_name }}</p>
                                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                @if($user->role === 'admin') bg-purple-100 text-purple-800
                                @elseif($user->role === 'client') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No recent users</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Recent Transactions</h3>
                    <p class="text-gray-600">Latest platform transactions</p>
                </div>
                <a href="{{ route('admin.transactions.index') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition-all text-sm">
                    View All Transactions
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-4 hover:bg-white hover:bg-opacity-50 rounded-xl transition-all">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-dollar-sign text-white"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-lg">
                                    ${{ number_format($transaction->amount, 2) }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $transaction->client->user->full_name ?? 'N/A' }} → 
                                    {{ $transaction->fournisseur->user->full_name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                @if($transaction->status === 'completed') bg-green-100 text-green-800
                                @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $transaction->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-credit-card text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No recent transactions</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Extended Quick Statistics -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Platform Overview</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user-plus text-white"></i>
                </div>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['new_users_this_month'] }}</p>
                <p class="text-sm text-gray-600">New Users</p>
                <p class="text-xs text-blue-500 mt-1">This Month</p>
            </div>
            
            <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
                <p class="text-2xl font-bold text-green-600">{{ $stats['completed_transactions'] }}</p>
                <p class="text-sm text-gray-600">Completed</p>
                <p class="text-xs text-green-500 mt-1">Transactions</p>
            </div>
            
            <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-star text-white"></i>
                </div>
                <p class="text-2xl font-bold text-purple-600">{{ $stats['active_annonces'] }}</p>
                <p class="text-sm text-gray-600">Active</p>
                <p class="text-xs text-purple-500 mt-1">Listings</p>
            </div>
            
            <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl">
                <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-map-marker-alt text-white"></i>
                </div>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['available_lands'] }}</p>
                <p class="text-sm text-gray-600">Available</p>
                <p class="text-xs text-yellow-500 mt-1">Lands</p>
            </div>
            
            <div class="text-center p-4 bg-gradient-to-br from-red-50 to-red-100 rounded-xl">
                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-crown text-white"></i>
                </div>
                <p class="text-2xl font-bold text-red-600">{{ $stats['total_admins'] }}</p>
                <p class="text-sm text-gray-600">Admin</p>
                <p class="text-xs text-red-500 mt-1">Users</p>
            </div>
            
            <div class="text-center p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl">
                <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
                <p class="text-2xl font-bold text-indigo-600">94%</p>
                <p class="text-sm text-gray-600">Success</p>
                <p class="text-xs text-indigo-500 mt-1">Rate</p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.glass-effect {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.card-hover:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Custom scrollbar for activity sections */
.space-y-4::-webkit-scrollbar {
    width: 4px;
}

.space-y-4::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.space-y-4::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.space-y-4::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Animation for statistics cards */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-hover {
    animation: slideUp 0.6s ease-out;
}

/* Pulse animation for new activities */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

.activity-item {
    animation: pulse 2s infinite;
}

/* Custom gradient backgrounds */
.bg-gradient-custom-1 {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-custom-2 {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.bg-gradient-custom-3 {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Time-based greeting and real-time clock
function updateTimeAndGreeting() {
    const now = new Date();
    const hours = now.getHours();
    
    // Update greeting
    let greeting = 'Good morning';
    if (hours >= 12 && hours < 17) greeting = 'Good afternoon';
    else if (hours >= 17) greeting = 'Good evening';
    
    document.getElementById('greeting').textContent = greeting;
    
    // Update date and time
    document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Call immediately and then every minute
updateTimeAndGreeting();
setInterval(updateTimeAndGreeting, 60000);

// Mini charts for cards
function createMiniChart(canvasId, data, color) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['', '', '', '', '', ''],
            datasets: [{
                data: data,
                borderColor: 'white',
                backgroundColor: 'rgba(255,255,255,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                borderWidth: 2
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { display: false },
                y: { display: false }
            }
        }
    });
}

// Create mini charts
createMiniChart('usersChart', [12, 19, 15, 25, 22, 30], '#3B82F6');
createMiniChart('landsChart', [8, 12, 18, 15, 20, 25], '#10B981');
createMiniChart('listingsChart', [15, 25, 20, 30, 28, 35], '#8B5CF6');
createMiniChart('revenueChart', [20, 35, 30, 45, 40, 50], '#F59E0B');

// Enhanced User Growth Chart
const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
new Chart(userGrowthCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(isset($userGrowthData) ? array_column($userGrowthData, 'month') : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
        datasets: [{
            label: 'New Users',
            data: {!! json_encode(isset($userGrowthData) ? array_column($userGrowthData, 'count') : [12, 19, 25, 32, 28, 45]) !!},
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#3B82F6',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 6
        }, {
            label: 'Active Users',
            data: [15, 25, 18, 32, 28, 45],
            borderColor: '#10B981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#10B981',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        plugins: {
            legend: {
                display: false
            },
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
                grid: {
                    display: false
                },
                border: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                },
                border: {
                    display: false
                }
            }
        }
    }
});

// Enhanced Revenue Chart
const revenueAreaCtx = document.getElementById('revenueAreaChart').getContext('2d');
new Chart(revenueAreaCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(isset($revenueData) ? array_column($revenueData, 'month') : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
        datasets: [{
            label: 'Revenue',
            data: {!! json_encode(isset($revenueData) ? array_column($revenueData, 'revenue') : [8000, 12000, 15000, 18000, 22000, 25000]) !!},
            borderColor: '#10B981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#10B981',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 6
        }, {
            label: 'Commission',
            data: {!! json_encode(isset($revenueData) ? array_column($revenueData, 'commission') : [800, 1200, 1500, 1800, 2200, 2500]) !!},
            borderColor: '#F59E0B',
            backgroundColor: 'rgba(245, 158, 11, 0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#F59E0B',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                borderColor: '#10B981',
                borderWidth: 1,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ':  + context.parsed.y.toLocaleString();
                    }
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                border: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                },
                border: {
                    display: false
                },
                ticks: {
                    callback: function(value) {
                        return ' + (value / 1000) + 'k';
                    }
                }
            }
        }
    }
});

// Add smooth scrolling for internal links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add loading animation for statistics cards
window.addEventListener('load', function() {
    const cards = document.querySelectorAll('.card-hover');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease-out';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, index * 150);
    });
});

// Add real-time updates simulation
function simulateRealTimeUpdates() {
    const revenueElement = document.querySelector('.text-3xl.font-bold');
    if (revenueElement && revenueElement.textContent.includes(')) {
        // Simulate small incremental updates
        setInterval(() => {
            const currentValue = parseFloat(revenueElement.textContent.replace(/[$,]/g, ''));
            const newValue = currentValue + Math.floor(Math.random() * 10);
            revenueElement.textContent = ' + newValue.toLocaleString();
        }, 30000); // Update every 30 seconds
    }
}

// Initialize real-time updates
simulateRealTimeUpdates();

// Add notification system for new activities
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'warning' ? 'bg-yellow-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    } text-white transform translate-x-full transition-transform duration-300`;
    
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'warning' ? 'exclamation' : type === 'error' ? 'times' : 'info'}-circle"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(full)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Simulate periodic notifications
setInterval(() => {
    const messages = [
        'New user registered successfully!',
        'Transaction completed.',
        'New land listing added.',
        'System backup completed.'
    ];
    const types = ['success', 'info', 'success', 'info'];
    const randomIndex = Math.floor(Math.random() * messages.length);
    
    if (Math.random() > 0.7) { // 30% chance every 60 seconds
        showNotification(messages[randomIndex], types[randomIndex]);
    }
}, 60000);
</script>
@endpush

@endsection