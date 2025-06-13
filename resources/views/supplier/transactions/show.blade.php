@extends('layouts.supplier')

@section('title', 'Transaction Details')

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

/* Timeline styles */
.timeline-item {
    position: relative;
    padding-left: 40px;
    padding-bottom: 20px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: -20px;
    width: 2px;
    background: linear-gradient(to bottom, #e5e7eb, #d1d5db);
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-dot {
    position: absolute;
    left: 8px;
    top: 8px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Info cards styling */
.info-card {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-left: 4px solid #6366f1;
}

/* Enhanced buttons */
.btn-primary {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #4f46e5, #4338ca);
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.btn-secondary {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    border: none;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #4b5563, #374151);
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

/* Progress bar */
.progress-bar {
    height: 8px;
    background: linear-gradient(90deg, #e5e7eb 0%, #d1d5db 100%);
    border-radius: 10px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981, #059669);
    border-radius: 10px;
    transition: width 0.6s ease;
}

/* Property image container */
.property-image {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
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
                    <a href="{{ route('supplier.transactions.index') }}" class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-6 hover:bg-opacity-30 transition-all">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="flex-1">
                        <h1 class="text-4xl font-bold mb-2">📋 Transaction Details</h1>
                        <p class="text-xl opacity-90">Complete information about transaction #{{ $transaction->id }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Status and Amount -->
                <div class="text-right">
                    <div class="flex space-x-3 mb-2">
                        <span class="status-{{ $transaction->status }} text-white px-4 py-2 rounded-full text-sm font-bold">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                    <div class="text-3xl font-bold">${{ number_format($transaction->amount, 2) }}</div>
                    <div class="text-sm opacity-90">Transaction Amount</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Transaction Overview -->
            <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-info-circle text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">💰 Transaction Overview</h3>
                        <p class="text-gray-600">Essential transaction information and financial details</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="info-card p-4 rounded-xl">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-hashtag text-indigo-600 mr-2"></i>
                            <span class="text-sm font-semibold text-gray-700">Transaction ID</span>
                        </div>
                        <div class="text-2xl font-bold text-gray-800">#{{ $transaction->id }}</div>
                    </div>

                    <div class="info-card p-4 rounded-xl">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-receipt text-indigo-600 mr-2"></i>
                            <span class="text-sm font-semibold text-gray-700">Payment Reference</span>
                        </div>
                        <div class="text-lg font-bold text-gray-800">
                            {{ $transaction->payment_reference ?: 'Not assigned yet' }}
                        </div>
                    </div>

                    <div class="info-card p-4 rounded-xl">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-dollar-sign text-indigo-600 mr-2"></i>
                            <span class="text-sm font-semibold text-gray-700">Sale Amount</span>
                        </div>
                        <div class="text-2xl font-bold text-gray-800">${{ number_format($transaction->amount, 2) }}</div>
                    </div>

                    <div class="info-card p-4 rounded-xl">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-percentage text-indigo-600 mr-2"></i>
                            <span class="text-sm font-semibold text-gray-700">Your Commission</span>
                        </div>
                        <div class="text-2xl font-bold text-emerald-600">${{ number_format($transaction->commission, 2) }}</div>
                        @if($transaction->amount > 0)
                            <div class="text-xs text-gray-500 mt-1">
                                {{ number_format(($transaction->commission / $transaction->amount) * 100, 1) }}% of sale
                            </div>
                        @endif
                    </div>

                    @if($transaction->payment_method)
                        <div class="info-card p-4 rounded-xl">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-credit-card text-indigo-600 mr-2"></i>
                                <span class="text-sm font-semibold text-gray-700">Payment Method</span>
                            </div>
                            <div class="text-lg font-bold text-gray-800">
                                {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                            </div>
                        </div>
                    @endif

                    <div class="info-card p-4 rounded-xl">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-calendar text-indigo-600 mr-2"></i>
                            <span class="text-sm font-semibold text-gray-700">Transaction Date</span>
                        </div>
                        <div class="text-lg font-bold text-gray-800">
                            {{ $transaction->created_at->format('M j, Y g:i A') }}
                        </div>
                    </div>
                </div>

                @if($transaction->status === 'completed' && $transaction->completed_at)
                    <div class="mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-green-800">Transaction Completed</div>
                                <div class="text-xs text-green-600">{{ $transaction->completed_at->format('M j, Y g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Client Information -->
            <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">👤 Client Information</h3>
                        <p class="text-gray-600">Details about the property buyer</p>
                    </div>
                </div>

                @if($transaction->client && $transaction->client->user)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                <span class="text-white font-bold text-xl">
                                    {{ substr($transaction->client->user->first_name, 0, 1) }}{{ substr($transaction->client->user->last_name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-gray-800">
                                    {{ $transaction->client->user->first_name }} {{ $transaction->client->user->last_name }}
                                </div>
                                <div class="text-sm text-gray-600">{{ $transaction->client->user->email }}</div>
                                @if($transaction->client->user->phone)
                                    <div class="text-sm text-gray-600">{{ $transaction->client->user->phone }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="info-card p-4 rounded-xl">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-envelope text-indigo-600 mr-2"></i>
                                    <span class="text-sm font-semibold text-gray-700">Email Address</span>
                                </div>
                                <div class="text-sm text-gray-800">{{ $transaction->client->user->email }}</div>
                            </div>

                            @if($transaction->client->specialization_type)
                                <div class="info-card p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-briefcase text-indigo-600 mr-2"></i>
                                        <span class="text-sm font-semibold text-gray-700">Specialization</span>
                                    </div>
                                    <div class="text-sm text-gray-800">{{ ucfirst($transaction->client->specialization_type) }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user-slash text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-600 mb-2">Client Information Unavailable</h4>
                        <p class="text-gray-500">Client data could not be loaded for this transaction</p>
                    </div>
                @endif
            </div>

            <!-- Property Information -->
            <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-seedling text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">🌱 Property Information</h3>
                        <p class="text-gray-600">Details about the agricultural land sold</p>
                    </div>
                </div>

                @if($transaction->terreAgricole)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="info-card p-4 rounded-xl">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-tag text-indigo-600 mr-2"></i>
                                    <span class="text-sm font-semibold text-gray-700">Property Title</span>
                                </div>
                                <div class="text-lg font-bold text-gray-800">{{ $transaction->terreAgricole->title }}</div>
                            </div>

                            <div class="info-card p-4 rounded-xl">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-indigo-600 mr-2"></i>
                                    <span class="text-sm font-semibold text-gray-700">Location</span>
                                </div>
                                <div class="text-sm text-gray-800">
                                    {{ $transaction->terreAgricole->region }}
                                    @if($transaction->terreAgricole->country)
                                        , {{ $transaction->terreAgricole->country }}
                                    @endif
                                </div>
                                @if($transaction->terreAgricole->localisation)
                                    <div class="text-xs text-gray-600 mt-1">{{ $transaction->terreAgricole->localisation }}</div>
                                @endif
                            </div>

                            @if($transaction->terreAgricole->surface)
                                <div class="info-card p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-ruler-combined text-indigo-600 mr-2"></i>
                                        <span class="text-sm font-semibold text-gray-700">Surface Area</span>
                                    </div>
                                    <div class="text-lg font-bold text-gray-800">
                                        {{ number_format($transaction->terreAgricole->surface, 2) }} hectares
                                    </div>
                                </div>
                            @endif

                            @if($transaction->terreAgricole->soil_type)
                                <div class="info-card p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-seedling text-indigo-600 mr-2"></i>
                                        <span class="text-sm font-semibold text-gray-700">Soil Type</span>
                                    </div>
                                    <div class="text-sm text-gray-800">{{ ucfirst($transaction->terreAgricole->soil_type) }}</div>
                                </div>
                            @endif

                            @if($transaction->terreAgricole->price)
                                <div class="info-card p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-dollar-sign text-indigo-600 mr-2"></i>
                                        <span class="text-sm font-semibold text-gray-700">Listed Price</span>
                                    </div>
                                    <div class="text-lg font-bold text-gray-800">${{ number_format($transaction->terreAgricole->price, 2) }}</div>
                                </div>
                            @endif
                        </div>

                        <div>
                            @if($transaction->terreAgricole->description)
                                <div class="info-card p-4 rounded-xl mb-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-align-left text-indigo-600 mr-2"></i>
                                        <span class="text-sm font-semibold text-gray-700">Description</span>
                                    </div>
                                    <div class="text-sm text-gray-800 leading-relaxed">
                                        {{ $transaction->terreAgricole->description }}
                                    </div>
                                </div>
                            @endif

                            @if($transaction->terreAgricole->photos && count($transaction->terreAgricole->photos) > 0)
                                <div class="info-card p-4 rounded-xl">
                                    <div class="flex items-center mb-3">
                                        <i class="fas fa-images text-indigo-600 mr-2"></i>
                                        <span class="text-sm font-semibold text-gray-700">Property Photos</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        @foreach(array_slice($transaction->terreAgricole->photos, 0, 4) as $photo)
                                            <div class="property-image">
                                                <img src="{{ asset('storage/' . $photo) }}" 
                                                     alt="Property photo" 
                                                     class="w-full h-24 object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(count($transaction->terreAgricole->photos) > 4)
                                        <div class="text-xs text-gray-500 mt-2">
                                            +{{ count($transaction->terreAgricole->photos) - 4 }} more photos
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-exclamation-triangle text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-600 mb-2">Property Information Unavailable</h4>
                        <p class="text-gray-500">Property data could not be loaded for this transaction</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Transaction Progress -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-line text-white text-sm"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Transaction Progress</h4>
                </div>

                @php
                    $progressPercentage = 0;
                    switch($transaction->status) {
                        case 'pending':
                            $progressPercentage = 25;
                            break;
                        case 'completed':
                            $progressPercentage = 100;
                            break;
                        case 'failed':
                        case 'cancelled':
                            $progressPercentage = 0;
                            break;
                    }
                @endphp

                <div class="progress-bar mb-4">
                    <div class="progress-fill" style="width: {{ $progressPercentage }}%"></div>
                </div>

                <div class="space-y-4">
                    <div class="timeline-item">
                        <div class="timeline-dot bg-green-500"></div>
                        <div>
                            <div class="text-sm font-semibold text-gray-800">Transaction Created</div>
                            <div class="text-xs text-gray-500">{{ $transaction->created_at->format('M j, Y g:i A') }}</div>
                        </div>
                    </div>

                    @if($transaction->status === 'pending')
                        <div class="timeline-item">
                            <div class="timeline-dot bg-yellow-500"></div>
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Payment Processing</div>
                                <div class="text-xs text-gray-500">Awaiting payment confirmation</div>
                            </div>
                        </div>
                    @endif

                    @if($transaction->status === 'completed' && $transaction->completed_at)
                        <div class="timeline-item">
                            <div class="timeline-dot bg-green-500"></div>
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Payment Completed</div>
                                <div class="text-xs text-gray-500">{{ $transaction->completed_at->format('M j, Y g:i A') }}</div>
                            </div>
                        </div>
                    @endif

                    @if($transaction->status === 'failed')
                        <div class="timeline-item">
                            <div class="timeline-dot bg-red-500"></div>
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Payment Failed</div>
                                <div class="text-xs text-gray-500">Transaction could not be completed</div>
                            </div>
                        </div>
                    @endif

                    @if($transaction->status === 'cancelled')
                        <div class="timeline-item">
                            <div class="timeline-dot bg-gray-500"></div>
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Transaction Cancelled</div>
                                <div class="text-xs text-gray-500">Cancelled by client or system</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-cog text-white text-sm"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Quick Actions</h4>
                </div>

                <div class="space-y-3">
                    <button onclick="window.print()" class="w-full btn-primary text-white px-4 py-2 rounded-lg font-semibold no-print">
                        <i class="fas fa-print mr-2"></i>Print Transaction
                    </button>

                    <a href="{{ route('supplier.transactions.index') }}" class="w-full btn-secondary text-white px-4 py-2 rounded-lg font-semibold text-center block">
                        <i class="fas fa-list mr-2"></i>Back to Transactions
                    </a>

                    @if($transaction->terreAgricole)
                        <a href="{{ route('supplier.properties.show', $transaction->terreAgricole->id) }}" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all font-semibold text-center block">
                            <i class="fas fa-seedling mr-2"></i>View Property
                        </a>
                    @endif
                </div>
            </div>

            <!-- Transaction Summary -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calculator text-white text-sm"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Financial Summary</h4>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-sm text-gray-600">Sale Amount:</span>
                        <span class="text-sm font-bold text-gray-800">${{ number_format($transaction->amount, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-sm text-gray-600">Commission Rate:</span>
                        <span class="text-sm font-bold text-gray-800">
                            @if($transaction->amount > 0)
                                {{ number_format(($transaction->commission / $transaction->amount) * 100, 1) }}%
                            @else
                                N/A
                            @endif
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-sm text-gray-600">Your Commission:</span>
                        <span class="text-sm font-bold text-emerald-600">${{ number_format($transaction->commission, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2 bg-emerald-50 px-3 rounded-lg">
                        <span class="text-sm font-semibold text-emerald-800">Your Earnings:</span>
                        <span class="text-lg font-bold text-emerald-600">${{ number_format($transaction->commission, 2) }}</span>
                    </div>
                </div>

                @if($transaction->status === 'completed')
                    <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center text-green-800">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span class="text-sm font-semibold">Payment Received</span>
                        </div>
                        <div class="text-xs text-green-600 mt-1">
                            Commission will be processed within 3-5 business days
                        </div>
                    </div>
                @elseif($transaction->status === 'pending')
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="flex items-center text-yellow-800">
                            <i class="fas fa-clock mr-2"></i>
                            <span class="text-sm font-semibold">Payment Pending</span>
                        </div>
                        <div class="text-xs text-yellow-600 mt-1">
                            Commission will be available once payment is confirmed
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth animations to cards
    const cards = document.querySelectorAll('.card-hover');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

    // Auto-refresh for pending transactions
    @if($transaction->status === 'pending')
        setInterval(() => {
            // Check for status updates
            fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Check if status changed by looking for different status class
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newStatus = doc.querySelector('.status-{{ $transaction->status }}');
                
                if (!newStatus) {
                    // Status changed, reload page
                    window.location.reload();
                }
            })
            .catch(error => console.log('Status check failed:', error));
        }, 10000); // Check every 10 seconds
    @endif

    // Enhanced print styling
    window.addEventListener('beforeprint', function() {
        document.title = 'Transaction #{{ $transaction->id }} - {{ $transaction->client && $transaction->client->user ? $transaction->client->user->first_name . " " . $transaction->client->user->last_name : "Client" }}';
    });
});
</script>
@endpush
@endsection