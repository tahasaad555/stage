@extends('layouts.supplier')

@section('title', 'My Transactions')

@push('styles')
<style>
.glass-effect {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Animation for cards */
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
    transition: all 0.3s ease;
}

/* Status badges */
.status-completed {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.status-pending {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.status-failed {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.status-cancelled {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
}

/* Filter section */
.filter-section {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

/* Enhanced table styling */
.table-row {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.table-row:hover {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-left-color: #6366f1;
    transform: translateX(4px);
}

/* Loading animation */
.loading-spinner {
    border: 3px solid #f3f4f6;
    border-top: 3px solid #6366f1;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Custom scrollbar */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-green-600 to-teal-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <a href="{{ route('supplier.dashboard') }}" class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-6 hover:bg-opacity-30 transition-all">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="flex-1">
                        <h1 class="text-4xl font-bold mb-2">💰 My Transactions</h1>
                        <p class="text-xl opacity-90">Track your sales and earnings from agricultural land transactions</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <div class="text-3xl font-bold">{{ number_format($stats['total']) }}</div>
                    <div class="text-sm opacity-90">Total Transactions</div>
                </div>
                <div class="w-px h-12 bg-white opacity-30"></div>
                <div class="text-right">
                    <div class="text-3xl font-bold">${{ number_format($stats['total_revenue'], 2) }}</div>
                    <div class="text-sm opacity-90">Total Revenue</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Completed Transactions -->
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <span class="text-sm text-gray-500">Completed</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['completed']) }}</h3>
            <p class="text-gray-600">Successful Sales</p>
            <div class="mt-3 flex items-center text-sm">
                <i class="fas fa-dollar-sign text-green-600 mr-1"></i>
                <span class="text-green-600 font-semibold">${{ number_format($stats['total_revenue'], 2) }}</span>
            </div>
        </div>

        <!-- Pending Transactions -->
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
                <span class="text-sm text-gray-500">Pending</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['pending']) }}</h3>
            <p class="text-gray-600">Awaiting Payment</p>
            <div class="mt-3 flex items-center text-sm">
                <i class="fas fa-hourglass-half text-yellow-600 mr-1"></i>
                <span class="text-yellow-600 font-semibold">In Progress</span>
            </div>
        </div>

        <!-- Failed Transactions -->
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-rose-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-times-circle text-white text-xl"></i>
                </div>
                <span class="text-sm text-gray-500">Failed</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['failed']) }}</h3>
            <p class="text-gray-600">Payment Failed</p>
            <div class="mt-3 flex items-center text-sm">
                <i class="fas fa-exclamation-triangle text-red-600 mr-1"></i>
                <span class="text-red-600 font-semibold">Attention Needed</span>
            </div>
        </div>

        <!-- Average Transaction -->
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <span class="text-sm text-gray-500">Average</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">${{ number_format($stats['avg_amount'], 0) }}</h3>
            <p class="text-gray-600">Per Transaction</p>
            <div class="mt-3 flex items-center text-sm">
                <i class="fas fa-trending-up text-purple-600 mr-1"></i>
                <span class="text-purple-600 font-semibold">Growth Metric</span>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="glass-effect rounded-2xl p-6 shadow-xl filter-section">
        <form method="GET" action="{{ route('supplier.transactions.index') }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🔍 Search</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Client name, reference, amount..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">📊 Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Statuses</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Payment Method Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">💳 Payment Method</label>
                    <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Methods</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method }}" {{ request('payment_method') === $method ? 'selected' : '' }}>
                                {{ ucfirst($method) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🔄 Sort By</label>
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="amount_high" {{ request('sort') === 'amount_high' ? 'selected' : '' }}>Highest Amount</option>
                        <option value="amount_low" {{ request('sort') === 'amount_low' ? 'selected' : '' }}>Lowest Amount</option>
                        <option value="status" {{ request('sort') === 'status' ? 'selected' : '' }}>By Status</option>
                    </select>
                </div>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">📅 From Date</label>
                    <input type="date" 
                           name="date_from" 
                           value="{{ request('date_from') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">📅 To Date</label>
                    <input type="date" 
                           name="date_to" 
                           value="{{ request('date_to') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-2 rounded-lg hover:from-emerald-600 hover:to-green-700 transition-all font-semibold">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>

                <div class="flex items-end">
                    <a href="{{ route('supplier.transactions.index') }}" class="w-full bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-all font-semibold text-center">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center justify-between pt-4 border-t border-gray-200">
            <div class="text-sm text-gray-600">
                Showing {{ $transactions->count() }} of {{ $transactions->total() }} transactions
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('supplier.transactions.export', request()->query()) }}" 
                   class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all text-sm font-semibold">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="glass-effect rounded-2xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">💼 Transaction History</h3>
                    <p class="text-gray-600">Complete list of your agricultural land sales</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="loading-spinner hidden" id="loadingSpinner"></div>
                    <span class="text-sm text-gray-500">Auto-refreshes every 30s</span>
                </div>
            </div>
        </div>

        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Transaction</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Property</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Commission</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Payment</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transactions as $transaction)
                            <tr class="table-row">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mr-3">
                                            <i class="fas fa-handshake text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">#{{ $transaction->id }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $transaction->payment_reference ?: 'Ref: Pending' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaction->client && $transaction->client->user)
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-white text-xs font-bold">
                                                    {{ substr($transaction->client->user->first_name, 0, 1) }}{{ substr($transaction->client->user->last_name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $transaction->client->user->first_name }} {{ $transaction->client->user->last_name }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $transaction->client->user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-user text-white text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm text-gray-500">Client Unavailable</div>
                                                <div class="text-xs text-gray-400">Data not found</div>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if($transaction->terreAgricole)
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900 mb-1">
                                                {{ Str::limit($transaction->terreAgricole->title, 30) }}
                                            </div>
                                            <div class="text-xs text-gray-500 flex items-center">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $transaction->terreAgricole->region }}
                                            </div>
                                            @if($transaction->terreAgricole->surface)
                                                <div class="text-xs text-gray-500 flex items-center mt-1">
                                                    <i class="fas fa-ruler-combined mr-1"></i>
                                                    {{ number_format($transaction->terreAgricole->surface, 2) }} hectares
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-500">Property Unavailable</div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">${{ number_format($transaction->amount, 2) }}</div>
                                    <div class="text-xs text-gray-500">Transaction Amount</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-emerald-600">${{ number_format($transaction->commission, 2) }}</div>
                                    <div class="text-xs text-gray-500">Your Earning</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-{{ $transaction->status }} px-3 py-1 rounded-full text-xs font-bold">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                    @if($transaction->status === 'completed' && $transaction->completed_at)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $transaction->completed_at->format('M j, Y') }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaction->payment_method)
                                        <div class="flex items-center">
                                            @if($transaction->payment_method === 'credit_card')
                                                <i class="fas fa-credit-card text-blue-500 mr-2"></i>
                                            @elseif($transaction->payment_method === 'bank_transfer')
                                                <i class="fas fa-university text-green-500 mr-2"></i>
                                            @elseif($transaction->payment_method === 'paypal')
                                                <i class="fab fa-paypal text-blue-600 mr-2"></i>
                                            @else
                                                <i class="fas fa-money-bill text-gray-500 mr-2"></i>
                                            @endif
                                            <span class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">Not specified</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $transaction->created_at->format('M j, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $transaction->created_at->format('g:i A') }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('supplier.transactions.show', $transaction) }}" 
                                           class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-3 py-1 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all text-xs font-semibold">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gradient-to-r from-gray-200 to-gray-300 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-handshake text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">No Transactions Found</h3>
                <p class="text-gray-600 mb-6">
                    @if(request()->hasAny(['search', 'status', 'payment_method', 'date_from', 'date_to']))
                        No transactions match your current filters. Try adjusting your search criteria.
                    @else
                        You haven't completed any transactions yet. Your sales will appear here once clients purchase your agricultural lands.
                    @endif
                </p>
                <div class="flex items-center justify-center space-x-4">
                    @if(request()->hasAny(['search', 'status', 'payment_method', 'date_from', 'date_to']))
                        <a href="{{ route('supplier.transactions.index') }}" 
                           class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all font-semibold">
                            <i class="fas fa-undo mr-2"></i>Clear Filters
                        </a>
                    @endif
                    <a href="{{ route('supplier.properties.index') }}" 
                       class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-2 rounded-lg hover:from-emerald-600 hover:to-green-700 transition-all font-semibold">
                        <i class="fas fa-seedling mr-2"></i>View My Properties
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on filter changes
    const filterForm = document.getElementById('filterForm');
    const filterInputs = filterForm.querySelectorAll('select, input[type="date"]');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Show loading spinner
            const loadingSpinner = document.getElementById('loadingSpinner');
            if (loadingSpinner) {
                loadingSpinner.classList.remove('hidden');
            }
            
            // Submit form after a short delay to allow for multiple quick changes
            setTimeout(() => {
                filterForm.submit();
            }, 300);
        });
    });

    // Search input with debounce
    const searchInput = filterForm.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const loadingSpinner = document.getElementById('loadingSpinner');
                if (loadingSpinner) {
                    loadingSpinner.classList.remove('hidden');
                }
                filterForm.submit();
            }, 500);
        });
    }

    // Auto-refresh every 30 seconds if there are pending transactions
    const hasPendingTransactions = {{ $stats['pending'] > 0 ? 'true' : 'false' }};
    if (hasPendingTransactions) {
        setInterval(() => {
            const loadingSpinner = document.getElementById('loadingSpinner');
            if (loadingSpinner) {
                loadingSpinner.classList.remove('hidden');
            }
            
            // Refresh page with current query parameters
            window.location.reload();
        }, 30000); // 30 seconds
    }

    // Enhanced table row interactions
    const tableRows = document.querySelectorAll('.table-row');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 10px 25px -5px rgba(0, 0, 0, 0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });
});
</script>
@endpush
@endsection