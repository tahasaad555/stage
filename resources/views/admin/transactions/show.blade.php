@extends('layouts.admin')

@section('title', 'Transaction Details')

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <a href="{{ route('admin.transactions.index') }}" class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4 hover:bg-opacity-30 transition-all">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">💰 Transaction Details</h1>
                        <p class="text-xl opacity-90">Transaction #{{ $transaction->id }} • {{ $transaction->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-dollar-sign mr-2"></i>
                        <span>${{ number_format($transaction->amount, 2) }}</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-percentage mr-2"></i>
                        <span>${{ number_format($transaction->commission, 2) }} Commission</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-{{ $transaction->status === 'completed' ? 'check-circle' : ($transaction->status === 'pending' ? 'clock' : 'times-circle') }} mr-2"></i>
                        <span>{{ ucfirst($transaction->status) }}</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-calendar mr-2"></i>
                        <span>{{ $transaction->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-receipt text-6xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Transaction Amount -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-dollar-sign text-xl"></i>
                        </div>
                        <div>
                            <p class="text-green-100 text-sm">Transaction Amount</p>
                            <h3 class="text-3xl font-bold">${{ number_format($transaction->amount, 2) }}</h3>
                        </div>
                    </div>
                    <p class="text-xs text-green-200">Total transaction value</p>
                </div>
            </div>
        </div>

        <!-- Commission -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-percentage text-xl"></i>
                        </div>
                        <div>
                            <p class="text-blue-100 text-sm">Commission</p>
                            <h3 class="text-3xl font-bold">${{ number_format($transaction->commission, 2) }}</h3>
                        </div>
                    </div>
                    <p class="text-xs text-blue-200">Platform fee</p>
                </div>
            </div>
        </div>

        <!-- Net Amount -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-calculator text-xl"></i>
                        </div>
                        <div>
                            <p class="text-purple-100 text-sm">Net Amount</p>
                            <h3 class="text-3xl font-bold">${{ number_format($transaction->amount - $transaction->commission, 2) }}</h3>
                        </div>
                    </div>
                    <p class="text-xs text-purple-200">After commission</p>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-gradient-to-br from-{{ $transaction->status === 'completed' ? 'green' : ($transaction->status === 'pending' ? 'yellow' : 'red') }}-500 to-{{ $transaction->status === 'completed' ? 'green' : ($transaction->status === 'pending' ? 'orange' : 'red') }}-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-{{ $transaction->status === 'completed' ? 'check-circle' : ($transaction->status === 'pending' ? 'clock' : 'times-circle') }} text-xl"></i>
                        </div>
                        <div>
                            <p class="text-{{ $transaction->status === 'completed' ? 'green' : ($transaction->status === 'pending' ? 'yellow' : 'red') }}-100 text-sm">Status</p>
                            <h3 class="text-2xl font-bold">{{ ucfirst($transaction->status) }}</h3>
                        </div>
                    </div>
                    <p class="text-xs text-{{ $transaction->status === 'completed' ? 'green' : ($transaction->status === 'pending' ? 'yellow' : 'red') }}-200">Current state</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Transaction Details -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-file-invoice-dollar text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Transaction Information</h3>
                    <p class="text-gray-600">Complete transaction details and metadata</p>
                </div>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-hashtag text-emerald-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                        <dd class="text-lg font-bold text-gray-900 font-mono">#{{ $transaction->id }}</dd>
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-credit-card text-blue-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $transaction->payment_method ?: 'Not specified' }}</dd>
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-barcode text-purple-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Payment Reference</dt>
                        <dd class="text-lg font-semibold text-gray-900 font-mono">{{ $transaction->payment_reference ?: 'Not available' }}</dd>
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-calendar-plus text-indigo-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created Date</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $transaction->created_at->format('M d, Y H:i:s') }}</dd>
                        <p class="text-sm text-gray-500">{{ $transaction->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                
                @if($transaction->completed_at)
                <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-100 rounded-xl">
                    <i class="fas fa-check-circle text-green-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Completed Date</dt>
                        <dd class="text-lg font-semibold text-green-700">{{ $transaction->completed_at->format('M d, Y H:i:s') }}</dd>
                        <p class="text-sm text-green-600">{{ $transaction->completed_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endif
                
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-4">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-chart-pie text-emerald-500 mr-2"></i>
                        Financial Breakdown
                    </h4>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-emerald-600">${{ number_format($transaction->amount, 2) }}</div>
                            <div class="text-xs text-gray-600">Gross Amount</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600">${{ number_format($transaction->commission, 2) }}</div>
                            <div class="text-xs text-gray-600">Commission</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-emerald-200">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">${{ number_format($transaction->amount - $transaction->commission, 2) }}</div>
                            <div class="text-sm text-gray-600">Net to Supplier</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Information -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">👤 Client Information</h3>
                    <p class="text-gray-600">Land buyer details and preferences</p>
                </div>
            </div>
            
            @if($transaction->client && $transaction->client->user)
                <div class="space-y-6">
                    <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl">
                        <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-white font-bold text-lg">
                                {{ substr($transaction->client->user->first_name, 0, 1) }}{{ substr($transaction->client->user->last_name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Client Name</dt>
                            <dd class="text-lg font-bold text-gray-900">
                                <a href="{{ route('admin.users.show', $transaction->client->user) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors">
                                    {{ $transaction->client->user->full_name }}
                                </a>
                            </dd>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                        <i class="fas fa-envelope text-green-500 mr-4 text-lg"></i>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                            <dd class="text-lg font-semibold text-gray-900">
                                <a href="mailto:{{ $transaction->client->user->email }}" class="hover:text-blue-600 transition-colors">
                                    {{ $transaction->client->user->email }}
                                </a>
                            </dd>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                        <i class="fas fa-phone text-purple-500 mr-4 text-lg"></i>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $transaction->client->user->phone ?: 'Not provided' }}</dd>
                        </div>
                    </div>
                    
                    @if($transaction->client->specialization_type)
                    <div class="flex items-center p-4 bg-gradient-to-r from-yellow-50 to-orange-100 rounded-xl">
                        <i class="fas fa-seedling text-yellow-500 mr-4 text-lg"></i>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Specialization</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $transaction->client->specialization_type }}</dd>
                        </div>
                    </div>
                    @endif
                    
                    @if($transaction->client->preferences)
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4">
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-heart text-blue-500 mr-2"></i>
                            Client Preferences
                        </h4>
                        <p class="text-gray-700">{{ $transaction->client->preferences }}</p>
                    </div>
                    @endif
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-slash text-red-500 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-red-600 mb-2">Client Information Unavailable</h4>
                    <p class="text-gray-600">Client data could not be loaded for this transaction</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Supplier Information -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-building text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">🏢 Supplier Information</h3>
                <p class="text-gray-600">Land seller business details and contact info</p>
            </div>
        </div>
        
        @if($transaction->fournisseur && $transaction->fournisseur->user)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                        <div class="w-14 h-14 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-white font-bold text-lg">
                                {{ substr($transaction->fournisseur->user->first_name, 0, 1) }}{{ substr($transaction->fournisseur->user->last_name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Supplier Name</dt>
                            <dd class="text-lg font-bold text-gray-900">
                                <a href="{{ route('admin.users.show', $transaction->fournisseur->user) }}" 
                                   class="text-green-600 hover:text-green-800 transition-colors">
                                    {{ $transaction->fournisseur->user->full_name }}
                                </a>
                            </dd>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                        <i class="fas fa-building text-green-500 mr-4 text-lg"></i>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $transaction->fournisseur->company_name }}</dd>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                        <i class="fas fa-certificate text-blue-500 mr-4 text-lg"></i>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Business Registration</dt>
                            <dd class="text-lg font-semibold text-gray-900 font-mono">{{ $transaction->fournisseur->business_registration }}</dd>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                        <i class="fas fa-envelope text-green-500 mr-4 text-lg"></i>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                            <dd class="text-lg font-semibold text-gray-900">
                                <a href="mailto:{{ $transaction->fournisseur->user->email }}" class="hover:text-blue-600 transition-colors">
                                    {{ $transaction->fournisseur->user->email }}
                                </a>
                            </dd>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                        <i class="fas fa-phone text-purple-500 mr-4 text-lg"></i>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $transaction->fournisseur->user->phone ?: 'Not provided' }}</dd>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                        <i class="fas fa-map-marker-alt text-red-500 mr-4 text-lg"></i>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Business Address</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $transaction->fournisseur->address }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-building-slash text-red-500 text-2xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-red-600 mb-2">Supplier Information Unavailable</h4>
                <p class="text-gray-600">Supplier data could not be loaded for this transaction</p>
            </div>
        @endif
    </div>

    <!-- Agricultural Land Information -->
    @if($transaction->terreAgricole)
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-seedling text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">🌾 Agricultural Land Details</h3>
                <p class="text-gray-600">Property information and specifications</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-tag text-green-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Land Title</h4>
                </div>
                <p class="text-gray-700 text-lg font-semibold">
                    <a href="{{ route('admin.lands.show', $transaction->terreAgricole) }}" 
                       class="text-green-600 hover:text-green-800 transition-colors">
                        {{ $transaction->terreAgricole->title }}
                    </a>
                </p>
            </div>
            
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-ruler-combined text-blue-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Surface Area</h4>
                </div>
                <p class="text-blue-600 text-2xl font-bold">{{ number_format($transaction->terreAgricole->surface, 2) }} ha</p>
                <p class="text-gray-600 text-sm">hectares</p>
            </div>
            
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-dollar-sign text-purple-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Listed Price</h4>
                </div>
                <p class="text-purple-600 text-2xl font-bold">${{ number_format($transaction->terreAgricole->price) }}</p>
                <p class="text-gray-600 text-sm">${{ number_format($transaction->terreAgricole->price / max($transaction->terreAgricole->surface, 1), 0) }}/ha</p>
            </div>
            
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-map-marker-alt text-yellow-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Location</h4>
                </div>
                <p class="text-gray-700 text-lg">{{ $transaction->terreAgricole->region }}</p>
                <p class="text-gray-600 text-sm">🇲🇦 {{ $transaction->terreAgricole->country }}</p>
            </div>
            
            <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-toggle-on text-red-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Current Status</h4>
                </div>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                    @if($transaction->terreAgricole->status === 'available') bg-green-100 text-green-800
                    @elseif($transaction->terreAgricole->status === 'sold') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    @if($transaction->terreAgricole->status === 'available') 🟢 Available
                    @elseif($transaction->terreAgricole->status === 'sold') 🔴 Sold
                    @else 🟡 Reserved @endif
                </span>
            </div>
            
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-chart-line text-indigo-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Price Analysis</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Listed:</span>
                        <span class="font-semibold">${{ number_format($transaction->terreAgricole->price) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sold for:</span>
                        <span class="font-semibold text-green-600">${{ number_format($transaction->amount) }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-gray-600">Difference:</span>
                        <span class="font-semibold {{ $transaction->amount > $transaction->terreAgricole->price ? 'text-green-600' : ($transaction->amount < $transaction->terreAgricole->price ? 'text-red-600' : 'text-gray-600') }}">
                            @if($transaction->amount > $transaction->terreAgricole->price)
                                +${{ number_format($transaction->amount - $transaction->terreAgricole->price) }}
                            @elseif($transaction->amount < $transaction->terreAgricole->price)
                                -${{ number_format($transaction->terreAgricole->price - $transaction->amount) }}
                            @else
                                $0
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Transaction Actions -->
    @if($transaction->status !== 'completed')
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-cogs text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Transaction Management</h3>
                <p class="text-gray-600">Administrative controls and status updates</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Status Update -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-edit text-blue-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Update Status</h4>
                </div>
                <select id="status" onchange="updateStatus()" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                    <option value="failed" {{ $transaction->status === 'failed' ? 'selected' : '' }}>❌ Failed</option>
                    <option value="cancelled" {{ $transaction->status === 'cancelled' ? 'selected' : '' }}>🚫 Cancelled</option>
                </select>
            </div>
            
            <!-- Send Notification -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-bell text-green-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Notifications</h4>
                </div>
                <button onclick="sendNotification()" 
                        class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white py-3 px-4 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Notify Parties
                </button>
            </div>
            
            <!-- Download Receipt -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-download text-purple-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Export</h4>
                </div>
                <button onclick="downloadReceipt()" 
                        class="w-full bg-gradient-to-r from-purple-500 to-pink-600 text-white py-3 px-4 rounded-xl hover:from-purple-600 hover:to-pink-700 transition-all">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Download PDF
                </button>
            </div>
        </div>
        
        @if(!in_array($transaction->status, ['completed']))
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-red-700">Danger Zone</h4>
                </div>
                <p class="text-red-600 text-sm mb-4">This action cannot be undone. Please be certain before proceeding.</p>
                <button onclick="deleteTransaction({{ $transaction->id }})" 
                        class="bg-gradient-to-r from-red-500 to-red-600 text-white py-3 px-6 rounded-xl hover:from-red-600 hover:to-red-700 transition-all">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Transaction
                </button>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Transaction Timeline -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-history text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Transaction Timeline</h3>
                <p class="text-gray-600">Track the complete transaction lifecycle</p>
            </div>
        </div>
        
        <div class="relative">
            <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-400 to-purple-400"></div>
            
            <div class="space-y-8">
                <!-- Created -->
                <div class="relative flex items-center">
                    <div class="absolute left-6 w-4 h-4 bg-blue-500 border-4 border-white rounded-full shadow-lg"></div>
                    <div class="ml-16 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-gray-800">Transaction Created</h4>
                                <p class="text-gray-600 text-sm">Initial transaction record established</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-blue-600">{{ $transaction->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($transaction->status === 'completed' && $transaction->completed_at)
                <!-- Completed -->
                <div class="relative flex items-center">
                    <div class="absolute left-6 w-4 h-4 bg-green-500 border-4 border-white rounded-full shadow-lg"></div>
                    <div class="ml-16 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-gray-800">Transaction Completed</h4>
                                <p class="text-gray-600 text-sm">Payment processed and transaction finalized</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-green-600">{{ $transaction->completed_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->completed_at->format('H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($transaction->status === 'pending')
                <!-- Pending -->
                <div class="relative flex items-center">
                    <div class="absolute left-6 w-4 h-4 bg-yellow-500 border-4 border-white rounded-full shadow-lg animate-pulse"></div>
                    <div class="ml-16 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-4 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-gray-800">Transaction Pending</h4>
                                <p class="text-gray-600 text-sm">Awaiting payment confirmation or processing</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-yellow-600">In Progress</p>
                                <p class="text-xs text-gray-500">{{ $transaction->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($transaction->status === 'failed')
                <!-- Failed -->
                <div class="relative flex items-center">
                    <div class="absolute left-6 w-4 h-4 bg-red-500 border-4 border-white rounded-full shadow-lg"></div>
                    <div class="ml-16 bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-4 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-gray-800">Transaction Failed</h4>
                                <p class="text-gray-600 text-sm">Payment processing failed or was declined</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-red-600">Failed</p>
                                <p class="text-xs text-gray-500">{{ $transaction->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Last Updated -->
                <div class="relative flex items-center">
                    <div class="absolute left-6 w-4 h-4 bg-gray-400 border-4 border-white rounded-full shadow-lg"></div>
                    <div class="ml-16 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-gray-800">Last Updated</h4>
                                <p class="text-gray-600 text-sm">Most recent modification to transaction record</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-600">{{ $transaction->updated_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
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
}

/* Loading animation */
.loading {
    position: relative;
    opacity: 0.6;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #ccc;
    border-top: 2px solid #333;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Timeline animations */
.timeline-item {
    opacity: 0;
    transform: translateX(-20px);
    animation: slideInFromLeft 0.6s ease-out forwards;
}

.timeline-item:nth-child(2) { animation-delay: 0.2s; }
.timeline-item:nth-child(3) { animation-delay: 0.4s; }
.timeline-item:nth-child(4) { animation-delay: 0.6s; }

@keyframes slideInFromLeft {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
@endpush

@push('scripts')
<script>
// Get CSRF token
window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

async function updateStatus() {
    const status = document.getElementById('status').value;
    const selectElement = document.getElementById('status');
    const originalContent = selectElement.innerHTML;
    
    selectElement.disabled = true;
    selectElement.style.opacity = '0.6';
    
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
            selectElement.disabled = false;
            selectElement.style.opacity = '1';
        }
    } catch (error) {
        showAlert('An error occurred while updating status', 'error');
        selectElement.disabled = false;
        selectElement.style.opacity = '1';
    }
}

async function deleteTransaction(transactionId) {
    if (!confirm('Are you sure you want to delete this transaction? This action cannot be undone.')) return;
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Deleting...';
    button.disabled = true;
    
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
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    } catch (error) {
        showAlert('An error occurred while deleting transaction', 'error');
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

async function sendNotification() {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/transactions/{{ $transaction->id }}/send-notification`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
        } else {
            showAlert(result.message || 'Failed to send notifications', 'error');
        }
    } catch (error) {
        showAlert('An error occurred while sending notifications', 'error');
    } finally {
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

function downloadReceipt() {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
    button.disabled = true;
    
    try {
        // Create a form and submit it to download the PDF
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = `/admin/transactions/{{ $transaction->id }}/export-pdf`;
        form.target = '_blank';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = window.csrfToken;
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
        
        showAlert('Transaction receipt download started!', 'success');
    } catch (error) {
        showAlert('An error occurred while generating PDF', 'error');
    } finally {
        // Reset button after a short delay
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.disabled = false;
        }, 1000);
    }
}

function showAlert(message, type) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert-notification');
    existingAlerts.forEach(alert => alert.remove());
    
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert-notification fixed top-4 right-4 z-50 p-6 rounded-xl shadow-2xl transform transition-all duration-500';
    
    let bgColor, icon;
    switch(type) {
        case 'success':
            bgColor = 'bg-gradient-to-r from-green-500 to-emerald-600';
            icon = 'fa-check-circle';
            break;
        case 'error':
            bgColor = 'bg-gradient-to-r from-red-500 to-red-600';
            icon = 'fa-exclamation-circle';
            break;
        default:
            bgColor = 'bg-gradient-to-r from-blue-500 to-blue-600';
            icon = 'fa-info-circle';
    }
    
    alertDiv.className += ` ${bgColor} text-white`;
    
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                <i class="fas ${icon}"></i>
            </div>
            <div class="flex-1">
                <div class="font-semibold">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
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

// Animate cards on load
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.glass-effect');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Add hover effects to interactive elements
    const interactiveElements = document.querySelectorAll('button, select, a[href]');
    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        element.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Animate timeline items
    const timelineItems = document.querySelectorAll('.relative.flex.items-center');
    timelineItems.forEach((item, index) => {
        item.classList.add('timeline-item');
        item.style.animationDelay = `${index * 0.2}s`;
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + Backspace to go back
    if ((e.ctrlKey || e.metaKey) && e.key === 'Backspace') {
        e.preventDefault();
        window.location.href = '{{ route("admin.transactions.index") }}';
    }
    
    // Ctrl/Cmd + D to download receipt
    if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
        e.preventDefault();
        downloadReceipt();
    }
});

// Auto-refresh transaction status every 30 seconds
let autoRefreshInterval = setInterval(() => {
    if (document.visibilityState === 'visible' && '{{ $transaction->status }}' === 'pending') {
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Check if status has changed
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newStatus = doc.querySelector('#status')?.value;
            const currentStatus = document.querySelector('#status')?.value;
            
            if (newStatus && currentStatus && newStatus !== currentStatus) {
                showAlert('Transaction status has been updated', 'info');
                setTimeout(() => location.reload(), 2000);
            }
        })
        .catch(error => console.warn('Auto-refresh failed:', error));
    }
}, 30000);

// Stop auto-refresh when page is hidden
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        clearInterval(autoRefreshInterval);
    } else if ('{{ $transaction->status }}' === 'pending') {
        // Restart auto-refresh when page becomes visible again
        autoRefreshInterval = setInterval(() => {
            // Auto-refresh logic here
        }, 30000);
    }
});

// Print transaction details
function printTransaction() {
    const printWindow = window.open('', '_blank');
    const transactionDetails = document.querySelector('.space-y-8').innerHTML;
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Transaction #{{ $transaction->id }} - Details</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .no-print { display: none; }
                    .glass-effect { background: #f9f9f9; border: 1px solid #ddd; padding: 20px; margin: 10px 0; }
                    h1, h2, h3 { color: #333; }
                    .gradient { background: #f0f0f0 !important; }
                </style>
            </head>
            <body>
                <h1>Transaction Details Report</h1>
                <p>Transaction ID: #{{ $transaction->id }}</p>
                <p>Generated on: ${new Date().toLocaleString()}</p>
                <hr>
                ${transactionDetails}
            </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}

// Add print shortcut
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault();
        printTransaction();
    }
});
</script>
@endpush
@endsection