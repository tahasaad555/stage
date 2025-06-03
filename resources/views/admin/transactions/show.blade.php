@extends('layouts.admin')

@section('title', 'Transaction Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('admin.transactions.index') }}" 
                   class="text-gray-500 hover:text-gray-700 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Transaction #{{ $transaction->id }}</h2>
                    <p class="text-gray-600">{{ $transaction->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                    @if($transaction->status === 'completed') bg-green-100 text-green-800
                    @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Transaction Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Transaction Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Transaction Details</h3>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                    <dd class="text-sm text-gray-900 font-mono">#{{ $transaction->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Amount</dt>
                    <dd class="text-lg font-semibold text-gray-900">${{ number_format($transaction->amount, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Commission</dt>
                    <dd class="text-sm text-gray-900">${{ number_format($transaction->commission, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Net Amount (after commission)</dt>
                    <dd class="text-sm text-gray-900">${{ number_format($transaction->amount - $transaction->commission, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="text-sm text-gray-900">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($transaction->status === 'completed') bg-green-100 text-green-800
                            @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                    <dd class="text-sm text-gray-900">{{ $transaction->payment_method ?: 'Not specified' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Payment Reference</dt>
                    <dd class="text-sm text-gray-900 font-mono">{{ $transaction->payment_reference ?: 'Not available' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created Date</dt>
                    <dd class="text-sm text-gray-900">{{ $transaction->created_at->format('M d, Y H:i:s') }}</dd>
                </div>
                @if($transaction->completed_at)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Completed Date</dt>
                    <dd class="text-sm text-gray-900">{{ $transaction->completed_at->format('M d, Y H:i:s') }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <!-- Client Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Client Information</h3>
            @if($transaction->client && $transaction->client->user)
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Client Name</dt>
                        <dd class="text-sm text-gray-900">
                            <a href="{{ route('admin.users.show', $transaction->client->user) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                {{ $transaction->client->user->full_name }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900">{{ $transaction->client->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="text-sm text-gray-900">{{ $transaction->client->user->phone ?: 'Not provided' }}</dd>
                    </div>
                    @if($transaction->client->specialization_type)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Specialization</dt>
                        <dd class="text-sm text-gray-900">{{ $transaction->client->specialization_type }}</dd>
                    </div>
                    @endif
                    @if($transaction->client->preferences)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Preferences</dt>
                        <dd class="text-sm text-gray-900">{{ $transaction->client->preferences }}</dd>
                    </div>
                    @endif
                </dl>
            @else
                <p class="text-red-500">Client information not available</p>
            @endif
        </div>
    </div>

    <!-- Supplier Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Supplier Information</h3>
        @if($transaction->fournisseur && $transaction->fournisseur->user)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Supplier Name</dt>
                        <dd class="text-sm text-gray-900">
                            <a href="{{ route('admin.users.show', $transaction->fournisseur->user) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                {{ $transaction->fournisseur->user->full_name }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                        <dd class="text-sm text-gray-900">{{ $transaction->fournisseur->company_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Business Registration</dt>
                        <dd class="text-sm text-gray-900">{{ $transaction->fournisseur->business_registration }}</dd>
                    </div>
                </dl>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900">{{ $transaction->fournisseur->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="text-sm text-gray-900">{{ $transaction->fournisseur->user->phone ?: 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="text-sm text-gray-900">{{ $transaction->fournisseur->address }}</dd>
                    </div>
                </dl>
            </div>
        @else
            <p class="text-red-500">Supplier information not available</p>
        @endif
    </div>

    <!-- Agricultural Land Information -->
    @if($transaction->terreAgricole)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Agricultural Land Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Land Title</dt>
                <dd class="text-sm text-gray-900">
                    <a href="{{ route('admin.lands.show', $transaction->terreAgricole) }}" 
                       class="text-green-600 hover:text-green-800">
                        {{ $transaction->terreAgricole->title }}
                    </a>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Surface Area</dt>
                <dd class="text-sm text-gray-900">{{ number_format($transaction->terreAgricole->surface, 2) }} hectares</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Listed Price</dt>
                <dd class="text-sm text-gray-900">${{ number_format($transaction->terreAgricole->price) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Region</dt>
                <dd class="text-sm text-gray-900">{{ $transaction->terreAgricole->region }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Country</dt>
                <dd class="text-sm text-gray-900">{{ $transaction->terreAgricole->country }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Current Status</dt>
                <dd class="text-sm text-gray-900">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($transaction->terreAgricole->status === 'available') bg-green-100 text-green-800
                        @elseif($transaction->terreAgricole->status === 'sold') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($transaction->terreAgricole->status) }}
                    </span>
                </dd>
            </div>
        </div>
    </div>
    @endif

    <!-- Actions -->
    @if($transaction->status !== 'completed')
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center space-x-2">
                <label for="status" class="text-sm font-medium text-gray-700">Update Status:</label>
                <select id="status" onchange="updateStatus()" 
                        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">
                    <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ $transaction->status === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="cancelled" {{ $transaction->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            
            @if(!in_array($transaction->status, ['completed']))
                <button onclick="deleteTransaction({{ $transaction->id }})" 
                        class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Transaction
                </button>
            @endif
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
async function updateStatus() {
    const status = document.getElementById('status').value;
    
    try {
        const response = await fetch(`/admin/transactions/{{ $transaction->id }}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({ status: status })
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
            setTimeout(() => window.location.href = '{{ route("admin.transactions.index") }}', 1000);
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