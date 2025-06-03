@extends('layouts.admin')

@section('title', 'Transactions')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Transactions</h2>
            <p class="text-gray-600">Monitor and manage all platform transactions</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-credit-card text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ number_format($stats['total']) }}</h3>
                    <p class="text-sm text-gray-600">Total Transactions</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-green-600">Completed: {{ $stats['completed'] }}</span>
                    <span class="text-yellow-600">Pending: {{ $stats['pending'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">${{ number_format($stats['total_revenue'], 2) }}</h3>
                    <p class="text-sm text-gray-600">Total Revenue</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">
                    Avg: ${{ number_format($stats['avg_amount'], 2) }}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-percentage text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">${{ number_format($stats['total_commission'], 2) }}</h3>
                    <p class="text-sm text-gray-600">Total Commission</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">
                    {{ $stats['total_revenue'] > 0 ? number_format(($stats['total_commission'] / $stats['total_revenue']) * 100, 1) : 0 }}% rate
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $stats['total'] > 0 ? number_format(($stats['completed'] / $stats['total']) * 100, 1) : 0 }}%
                    </h3>
                    <p class="text-sm text-gray-600">Success Rate</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">
                    Failed: {{ $stats['failed'] }} | Cancelled: {{ $stats['cancelled'] }}
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Reference, client, supplier..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">
                    <option value="">All Methods</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method }}" {{ request('payment_method') === $method ? 'selected' : '' }}>
                            {{ ucfirst($method) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('admin.transactions.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Land</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('admin.transactions.show', $transaction) }}" class="hover:text-green-600">
                                            #{{ $transaction->id }}
                                        </a>
                                    </div>
                                    @if($transaction->payment_reference)
                                        <div class="text-sm text-gray-500">{{ $transaction->payment_reference }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->client && $transaction->client->user)
                                    <div class="text-sm text-gray-900">
                                        <a href="{{ route('admin.users.show', $transaction->client->user) }}" class="hover:text-blue-600">
                                            {{ $transaction->client->user->full_name }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $transaction->client->user->email }}</div>
                                @else
                                    <span class="text-red-500">Client not found</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->fournisseur && $transaction->fournisseur->user)
                                    <div class="text-sm text-gray-900">
                                        <a href="{{ route('admin.users.show', $transaction->fournisseur->user) }}" class="hover:text-blue-600">
                                            {{ $transaction->fournisseur->user->full_name }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $transaction->fournisseur->company_name }}</div>
                                @else
                                    <span class="text-red-500">Supplier not found</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->terreAgricole)
                                    <div class="text-sm text-gray-900">
                                        <a href="{{ route('admin.lands.show', $transaction->terreAgricole) }}" class="hover:text-green-600">
                                            {{ Str::limit($transaction->terreAgricole->title, 20) }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $transaction->terreAgricole->region }}</div>
                                @else
                                    <span class="text-red-500">Land not found</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${{ number_format($transaction->amount, 2) }}</div>
                                @if($transaction->commission > 0)
                                    <div class="text-sm text-gray-500">Commission: ${{ number_format($transaction->commission, 2) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($transaction->status === 'completed') bg-green-100 text-green-800
                                    @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $transaction->created_at->format('M d, Y') }}</div>
                                <div>{{ $transaction->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.transactions.show', $transaction) }}" 
                                       class="text-green-600 hover:text-green-900" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($transaction->status !== 'completed')
                                        <button onclick="deleteTransaction({{ $transaction->id }})" 
                                                class="text-red-600 hover:text-red-900" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-credit-card text-4xl text-gray-300 mb-4"></i>
                                <p>No transactions found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
async function deleteTransaction(transactionId) {
    if (!confirm('Are you sure you want to delete this transaction? This action cannot be undone.')) return;
    
    try {
        const response = await fetch(`/admin/transactions/${transactionId}`, {
            method: 'DELETE',
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
        }
    } catch (error) {
        showAlert('An error occurred', 'error');
    }
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-md ${
        type === 'success' ? 'bg-green-100 text-green-700 border border-green-400' :
        'bg-red-100 text-red-700 border border-red-400'
    }`;
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
            ${message}
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-lg">&times;</button>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endpush
@endsection