@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_users']) }}</h3>
                    <p class="text-sm text-gray-600">Total Users</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Clients: {{ $stats['total_clients'] }}</span>
                    <span class="text-gray-500">Suppliers: {{ $stats['total_fournisseurs'] }}</span>
                </div>
            </div>
        </div>

        <!-- Agricultural Lands -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-map text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_lands']) }}</h3>
                    <p class="text-sm text-gray-600">Agricultural Lands</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">
                    Available: {{ $stats['available_lands'] }}
                </div>
            </div>
        </div>

        <!-- Active Listings -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bullhorn text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_annonces']) }}</h3>
                    <p class="text-sm text-gray-600">Total Listings</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">
                    Active: {{ $stats['active_annonces'] }}
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">${{ number_format($stats['total_revenue'], 2) }}</h3>
                    <p class="text-sm text-gray-600">Total Revenue</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">
                    Commission: ${{ number_format($stats['total_commission'], 2) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Growth Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Growth (Last 6 Months)</h3>
            <div class="relative h-64">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trend (Last 6 Months)</h3>
            <div class="relative h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Users</h3>
            <div class="space-y-4">
                @forelse($recentUsers as $user)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-600">
                                    {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $user->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($user->role === 'admin') bg-purple-100 text-purple-800
                                @elseif($user->role === 'client') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No recent users</p>
                @endforelse
            </div>
            <div class="mt-4 pt-4 border-t">
                <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    View all users →
                </a>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Transactions</h3>
            <div class="space-y-4">
                @forelse($recentTransactions as $transaction)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                ${{ number_format($transaction->amount, 2) }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $transaction->client->user->full_name ?? 'N/A' }} → 
                                {{ $transaction->fournisseur->user->full_name ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($transaction->status === 'completed') bg-green-100 text-green-800
                                @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $transaction->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No recent transactions</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Statistics</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $stats['new_users_this_month'] }}</p>
                <p class="text-sm text-gray-600">New Users This Month</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ $stats['completed_transactions'] }}</p>
                <p class="text-sm text-gray-600">Completed Transactions</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-purple-600">{{ $stats['active_annonces'] }}</p>
                <p class="text-sm text-gray-600">Active Listings</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['available_lands'] }}</p>
                <p class="text-sm text-gray-600">Available Lands</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-red-600">{{ $stats['total_admins'] }}</p>
                <p class="text-sm text-gray-600">Admin Users</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// User Growth Chart
const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
new Chart(userGrowthCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($userGrowthData, 'month')) !!},
        datasets: [{
            label: 'New Users',
            data: {!! json_encode(array_column($userGrowthData, 'count')) !!},
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($revenueData, 'month')) !!},
        datasets: [{
            label: 'Revenue',
            data: {!! json_encode(array_column($revenueData, 'revenue')) !!},
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
@endpush
@endsection