@extends('layouts.admin')

@section('title', 'Land Details')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>
<style>
.glass-effect {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.leaflet-container {
    font-family: 'Inter', sans-serif;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.leaflet-popup-content-wrapper {
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    border: 2px solid #10b981;
}

.leaflet-popup-content {
    margin: 16px 20px;
    line-height: 1.6;
}

.leaflet-control-zoom a {
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.land-marker {
    background: linear-gradient(135deg, #10b981, #059669);
    border: 4px solid white;
    border-radius: 50%;
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5); }
    50% { box-shadow: 0 8px 25px rgba(16, 185, 129, 0.8); }
    100% { box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5); }
}

.land-area-circle {
    stroke: #10b981;
    stroke-width: 3;
    stroke-dasharray: 10, 5;
    fill: rgba(16, 185, 129, 0.1);
    animation: dash 20s linear infinite;
}

@keyframes dash {
    to { stroke-dashoffset: -100; }
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

.card-animate {
    animation: slideUp 0.6s ease-out;
}
</style>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <a href="{{ route('admin.lands.index') }}" 
                       class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4 transition-all">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">🌾 {{ $land->title }}</h1>
                        <p class="text-xl opacity-90 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $land->region }}, {{ $land->country }}
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-ruler-combined mr-2"></i>
                        <span>{{ number_format($land->surface, 1) }} Hectares</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-dollar-sign mr-2"></i>
                        <span>{{ number_format($land->price) }} MAD</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-calculator mr-2"></i>
                        <span>{{ number_format($land->price / max($land->surface, 1), 0) }} MAD/ha</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-{{ $land->status === 'available' ? 'check-circle' : ($land->status === 'sold' ? 'times-circle' : 'clock') }} mr-2"></i>
                        <span>{{ ucfirst($land->status) }}</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-seedling text-6xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Land Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Basic Information Card -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl card-animate">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-info-circle text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Basic Information</h3>
                    <p class="text-gray-600">Essential property details and specifications</p>
                </div>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-tag text-blue-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Property Title</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $land->title }}</dd>
                    </div>
                </div>

                <div class="flex items-start p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-align-left text-green-500 mr-4 text-lg mt-1"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="text-sm text-gray-900 leading-relaxed">{{ $land->description }}</dd>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-ruler-combined text-purple-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Surface Area</dt>
                        <dd class="text-lg font-semibold text-purple-600">{{ number_format($land->surface, 2) }} hectares</dd>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl">
                    <i class="fas fa-dollar-sign text-green-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Price</dt>
                        <dd class="text-lg font-bold text-green-600">{{ number_format($land->price) }} MAD</dd>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-gradient-to-r from-{{ $land->status === 'available' ? 'green' : ($land->status === 'sold' ? 'red' : 'yellow') }}-50 to-{{ $land->status === 'available' ? 'green' : ($land->status === 'sold' ? 'red' : 'yellow') }}-100 rounded-xl">
                    <i class="fas fa-{{ $land->status === 'available' ? 'check-circle' : ($land->status === 'sold' ? 'times-circle' : 'clock') }} text-{{ $land->status === 'available' ? 'green' : ($land->status === 'sold' ? 'red' : 'yellow') }}-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Current Status</dt>
                        <dd class="text-lg font-semibold text-{{ $land->status === 'available' ? 'green' : ($land->status === 'sold' ? 'red' : 'yellow') }}-600">
                            @if($land->status === 'available') ✅ Available for Purchase
                            @elseif($land->status === 'sold') ❌ Sold
                            @else 🟡 Reserved @endif
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location & Details Card -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl card-animate">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-map-marker-alt text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Location & Details</h3>
                    <p class="text-gray-600">Geographic and agricultural specifications</p>
                </div>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-map text-red-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Region</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $land->region }}</dd>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-flag text-blue-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Country</dt>
                        <dd class="text-lg font-semibold text-gray-900 flex items-center">
                            🇲🇦 {{ $land->country }}
                        </dd>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-crosshairs text-purple-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">GPS Coordinates</dt>
                        <dd class="text-sm font-mono text-gray-900 bg-white px-3 py-2 rounded-lg border">{{ $land->gps_coordinates ?: 'Not provided' }}</dd>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-leaf text-green-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Soil Type</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $land->soil_type ?: 'Not specified' }}</dd>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-calendar-plus text-indigo-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Listed Date</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $land->created_at->format('M d, Y H:i') }}</dd>
                        <p class="text-sm text-gray-500">{{ $land->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-clock text-yellow-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $land->updated_at->format('M d, Y H:i') }}</dd>
                        <p class="text-sm text-gray-500">{{ $land->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Price Analytics Card -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl card-animate">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-chart-line text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">💰 Price Analytics</h3>
                <p class="text-gray-600">Detailed pricing breakdown and calculations</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                <div class="text-3xl font-bold text-green-600 mb-2">{{ number_format($land->price) }}</div>
                <div class="text-sm text-gray-600">Total Price (MAD)</div>
            </div>
            
            <div class="text-center p-6 bg-gradient-to-r from-blue-50 to-blue-50 rounded-xl">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ number_format($land->price / max($land->surface, 1), 0) }}</div>
                <div class="text-sm text-gray-600">Price per Hectare (MAD)</div>
            </div>
            
            <div class="text-center p-6 bg-gradient-to-r from-purple-50 to-purple-50 rounded-xl">
                <div class="text-3xl font-bold text-purple-600 mb-2">{{ number_format($land->surface, 1) }}</div>
                <div class="text-sm text-gray-600">Total Surface (ha)</div>
            </div>
            
            <div class="text-center p-6 bg-gradient-to-r from-indigo-50 to-indigo-50 rounded-xl">
                <div class="text-3xl font-bold text-indigo-600 mb-2">{{ number_format($land->surface * 10000) }}</div>
                <div class="text-sm text-gray-600">Total Surface (m²)</div>
            </div>
        </div>
    </div>

    <!-- Enhanced Location Map -->
    @if($land->gps_coordinates)
    <div class="glass-effect rounded-2xl p-8 shadow-xl card-animate">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-map text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">🗺️ Interactive Location Map</h3>
                    <p class="text-gray-600">Precise location within Morocco's agricultural regions</p>
                </div>
            </div>
            <div class="flex items-center space-x-4 text-sm text-gray-600">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-600 rounded-full mr-2"></div>
                    <span>Land Location</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 border-2 border-green-600 rounded-full mr-2" style="background: rgba(16, 185, 129, 0.1);"></div>
                    <span>Approximate Area</span>
                </div>
            </div>
        </div>
        <div class="border-2 border-gray-300 rounded-xl overflow-hidden shadow-lg">
            <div id="map" style="height: 500px; width: 100%;"></div>
        </div>
        <div class="mt-6 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wide font-semibold">GPS Coordinates</p>
                    <p class="text-sm font-mono text-gray-900 bg-white px-3 py-2 rounded-lg mt-2 border">{{ $land->gps_coordinates }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wide font-semibold">Surface Area</p>
                    <p class="text-lg font-bold text-green-600 mt-2">{{ number_format($land->surface, 1) }} hectares</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wide font-semibold">Price per Hectare</p>
                    <p class="text-lg font-bold text-blue-600 mt-2">{{ number_format($land->price / max($land->surface, 1), 0) }} MAD/ha</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Enhanced Listing Information -->
    @if(isset($land->annonce) && $land->annonce)
    <div class="glass-effect rounded-2xl p-8 shadow-xl card-animate">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-bullhorn text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">📢 Listing Information</h3>
                <p class="text-gray-600">Publication details and supplier information</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl">
                <div class="flex items-center mb-3">
                    <i class="fas fa-user-tie text-blue-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Listed By</h4>
                </div>
                <div class="text-gray-700">
                    @if($land->annonce->fournisseur && $land->annonce->fournisseur->user)
                        <div class="font-semibold text-blue-600 text-lg">
                            <a href="{{ route('admin.users.show', $land->annonce->fournisseur->user) }}" 
                               class="transition-colors">
                                {{ $land->annonce->fournisseur->user->full_name }}
                            </a>
                        </div>
                        <div class="text-gray-600 text-sm">{{ $land->annonce->fournisseur->company_name ?? 'Individual Supplier' }}</div>
                    @else
                        <div class="text-gray-500">No supplier assigned</div>
                    @endif
                </div>
            </div>

            <div class="p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl">
                <div class="flex items-center mb-3">
                    <i class="fas fa-toggle-on text-green-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Listing Status</h4>
                </div>
                <span class="inline-flex px-4 py-2 text-sm font-bold rounded-full {{ $land->annonce->is_active ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                    {{ $land->annonce->is_active ? '✅ Active Listing' : '❌ Inactive Listing' }}
                </span>
            </div>

            <div class="p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl">
                <div class="flex items-center mb-3">
                    <i class="fas fa-star text-purple-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Featured Status</h4>
                </div>
                <span class="inline-flex px-4 py-2 text-sm font-bold rounded-full {{ $land->annonce->is_featured ? 'bg-purple-200 text-purple-800' : 'bg-gray-200 text-gray-800' }}">
                    {{ $land->annonce->is_featured ? '⭐ Featured Property' : '📋 Standard Listing' }}
                </span>
            </div>

            <div class="p-4 bg-gradient-to-r from-yellow-50 to-orange-100 rounded-xl">
                <div class="flex items-center mb-3">
                    <i class="fas fa-calendar-check text-yellow-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Published Date</h4>
                </div>
                <div class="text-gray-700">
                    @if($land->annonce->published_at)
                        <div class="font-semibold">{{ $land->annonce->published_at->format('M d, Y H:i') }}</div>
                        <div class="text-sm text-gray-600">{{ $land->annonce->published_at->diffForHumans() }}</div>
                    @else
                        <div class="text-gray-500">Not yet published</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Enhanced Transaction History -->
    @if(isset($land->transactions) && $land->transactions->count() > 0)
    <div class="glass-effect rounded-2xl p-8 shadow-xl card-animate">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-exchange-alt text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">💳 Transaction History</h3>
                <p class="text-gray-600">Complete record of all transactions for this property</p>
            </div>
        </div>
        
        <div class="overflow-x-auto bg-white rounded-xl shadow-inner">
            <table class="min-w-full">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2 text-blue-500"></i>Client
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-dollar-sign mr-2 text-green-500"></i>Amount
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-percentage mr-2 text-purple-500"></i>Commission
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-toggle-on mr-2 text-yellow-500"></i>Status
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-red-500"></i>Date
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($land->transactions as $transaction)
                        <tr class="transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-sm">
                                            @if($transaction->client && $transaction->client->user)
                                                {{ substr($transaction->client->user->first_name, 0, 1) }}{{ substr($transaction->client->user->last_name, 0, 1) }}
                                            @else
                                                ?
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        @if($transaction->client && $transaction->client->user)
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('admin.users.show', $transaction->client->user) }}" 
                                                   class="text-blue-600 transition-colors">
                                                    {{ $transaction->client->user->full_name }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $transaction->client->user->email }}</div>
                                        @else
                                            <div class="text-sm text-gray-500">Unknown Client</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-bold text-green-600">{{ number_format($transaction->amount, 2) }} MAD</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-purple-600">{{ number_format($transaction->commission, 2) }} MAD</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                    @if($transaction->status === 'completed') bg-green-100 text-green-800
                                    @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($transaction->status === 'completed') ✅ Completed
                                    @elseif($transaction->status === 'pending') ⏳ Pending
                                    @elseif($transaction->status === 'failed') ❌ Failed
                                    @else ❓ Unknown @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $transaction->created_at->format('M d, Y H:i') }}</div>
                                <div class="text-xs text-gray-500">{{ $transaction->created_at->diffForHumans() }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Enhanced Management Actions -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl card-animate">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-cogs text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">🛠️ Management Actions</h3>
                <p class="text-gray-600">Administrative controls and property management tools</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Status Update -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-toggle-on text-blue-500 text-2xl"></i>
                    <span class="bg-blue-200 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">Status</span>
                </div>
                <h4 class="font-bold text-lg mb-3 text-gray-800">Change Status</h4>
                <select id="status" onchange="updateStatus()" 
                        class="w-full px-3 py-2 border-2 border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    <option value="available" {{ $land->status === 'available' ? 'selected' : '' }}>🟢 Available</option>
                    <option value="reserved" {{ $land->status === 'reserved' ? 'selected' : '' }}>🟡 Reserved</option>
                    <option value="sold" {{ $land->status === 'sold' ? 'selected' : '' }}>🔴 Sold</option>
                </select>
            </div>
            
            <!-- Edit Property -->
            <a href="{{ route('admin.lands.edit', $land) }}" 
               class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-6 block transition-all">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-edit text-green-500 text-2xl"></i>
                    <span class="bg-green-200 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Edit</span>
                </div>
                <h4 class="font-bold text-lg mb-2 text-gray-800">Edit Property</h4>
                <p class="text-sm text-gray-600">Modify property details and information</p>
            </a>
            
            <!-- View Listing or Create Listing -->
            @if($land->annonce)
            <a href="{{ route('admin.listings.show', $land->annonce) }}" 
               class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-6 block transition-all">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-bullhorn text-purple-500 text-2xl"></i>
                    <span class="bg-purple-200 text-purple-800 px-2 py-1 rounded-full text-xs font-semibold">Listing</span>
                </div>
                <h4 class="font-bold text-lg mb-2 text-gray-800">View Listing</h4>
                <p class="text-sm text-gray-600">Manage property listing details</p>
            </a>
            @else
            <a href="{{ route('admin.listings.create') }}?terre_agricole_id={{ $land->id }}" 
               class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-6 block transition-all">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-plus text-purple-500 text-2xl"></i>
                    <span class="bg-purple-200 text-purple-800 px-2 py-1 rounded-full text-xs font-semibold">Create</span>
                </div>
                <h4 class="font-bold text-lg mb-2 text-gray-800">Create Listing</h4>
                <p class="text-sm text-gray-600">Create a new listing for this property</p>
            </a>
            @endif
            
            <!-- Delete Property -->
            <button onclick="deleteLand({{ $land->id }})" 
                    class="bg-gradient-to-r from-red-50 to-red-100 rounded-xl p-6 transition-all text-left">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-trash text-red-500 text-2xl"></i>
                    <span class="bg-red-200 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Delete</span>
                </div>
                <h4 class="font-bold text-lg mb-2 text-gray-800">Delete Property</h4>
                <p class="text-sm text-gray-600">Permanently remove this property</p>
            </button>
        </div>
        
        <!-- Additional Tools -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button onclick="printPropertyDetails()" 
                        class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white px-6 py-3 rounded-xl transition-all flex items-center justify-center">
                    <i class="fas fa-print mr-2"></i>Print Details
                </button>
                <button onclick="shareProperty()" 
                        class="bg-gradient-to-r from-pink-500 to-pink-600 text-white px-6 py-3 rounded-xl transition-all flex items-center justify-center">
                    <i class="fas fa-share mr-2"></i>Share Property
                </button>
            </div>
        </div>
    </div>

    <!-- Property Analytics -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl card-animate">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-chart-bar text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">📊 Property Analytics</h3>
                <p class="text-gray-600">Performance metrics and market insights</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center p-6 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ intval($land->created_at->diffInDays(now())) }}</div>
                <div class="text-sm text-gray-600">Days Listed</div>
            </div>
            
            <div class="text-center p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                <div class="text-3xl font-bold text-green-600 mb-2">{{ $land->transactions->count() }}</div>
                <div class="text-sm text-gray-600">Total Transactions</div>
            </div>
            
            <div class="text-center p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl">
                <div class="text-3xl font-bold text-purple-600 mb-2">{{ number_format($land->transactions->where('status', 'completed')->sum('amount'), 0) }}</div>
                <div class="text-sm text-gray-600">Revenue Generated (MAD)</div>
            </div>
            
            <div class="text-center p-6 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl">
                <div class="text-3xl font-bold text-yellow-600 mb-2">{{ $land->annonce ? ($land->annonce->is_featured ? 'Yes' : 'No') : 'N/A' }}</div>
                <div class="text-sm text-gray-600">Featured Status</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>

<script>
@if($land->gps_coordinates)
// Initialize map for viewing location in Morocco
document.addEventListener('DOMContentLoaded', function() {
    const coordinates = '{{ $land->gps_coordinates }}';
    const coords = coordinates.split(',').map(s => parseFloat(s.trim()));
    
    if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
        const [lat, lng] = coords;
        
        // Morocco bounds
        const moroccoBounds = [
            [21.0, -17.5], // Southwest corner
            [36.0, -1.0]   // Northeast corner
        ];
        
        // Initialize map with Morocco constraints
        const map = L.map('map', {
            maxBounds: moroccoBounds,
            maxBoundsViscosity: 1.0,
            minZoom: 5,
            maxZoom: 18
        }).setView([lat, lng], 12);
        
        // Add high-quality map tiles for Morocco
        L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 18,
            className: 'map-tiles'
        }).addTo(map);
        
        // Create custom land marker
        const landIcon = L.divIcon({
            html: '<div class="w-6 h-6 bg-green-600 border-4 border-white rounded-full shadow-lg"></div>',
            className: 'land-marker',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });
        
        // Add marker for the land location
        const marker = L.marker([lat, lng], { icon: landIcon }).addTo(map);
        
        // Enhanced popup content
        marker.bindPopup(`
            <div class="text-center p-3 min-w-0" style="min-width: 250px;">
                <div class="flex items-center justify-center mb-3">
                    <i class="fas fa-map-marker-alt text-green-600 mr-2 text-lg"></i>
                    <strong class="text-gray-800 text-base">{{ $land->title }}</strong>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Location:</span>
                        <span class="text-gray-800">{{ $land->region }}, 🇲🇦</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Surface:</span>
                        <span class="text-green-600 font-semibold">{{ number_format($land->surface, 1) }} ha</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Price:</span>
                        <span class="text-blue-600 font-semibold">{{ number_format($land->price) }} MAD</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($land->status === 'available') bg-green-100 text-green-800
                            @elseif($land->status === 'sold') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($land->status) }}
                        </span>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-t text-xs text-gray-500">
                    <div class="font-mono bg-gray-100 px-2 py-1 rounded">${lat.toFixed(6)}, ${lng.toFixed(6)}</div>
                </div>
            </div>
        `).openPopup();
        
        // Add a circle to show approximate land area with animation
        const radius = Math.sqrt({{ $land->surface }} * 10000) / 2; // Rough approximation
        const areaCircle = L.circle([lat, lng], {
            color: '#10b981',
            fillColor: '#10b981',
            fillOpacity: 0.15,
            radius: radius,
            weight: 3,
            dashArray: '10, 5',
            className: 'land-area-circle'
        }).addTo(map);
        
        // Add area label
        const areaLabel = L.tooltip({
            permanent: true,
            direction: 'center',
            className: 'area-label bg-white px-2 py-1 rounded shadow-md text-xs font-semibold text-green-700'
        })
        .setContent(`{{ number_format($land->surface, 1) }} hectares`)
        .setLatLng([lat, lng]);
        
        areaCircle.bindTooltip(areaLabel);
        
        // Fit map to show both marker and area circle
        const group = new L.featureGroup([marker, areaCircle]);
        map.fitBounds(group.getBounds().pad(0.1));
    }
});
@endif

// Get CSRF token
window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

// Update Status Function
async function updateStatus() {
    const status = document.getElementById('status').value;
    const select = document.getElementById('status');
    const originalContent = select.innerHTML;
    
    select.disabled = true;
    select.innerHTML = '<option>Updating...</option>';
    
    try {
        const response = await fetch(`/admin/lands/{{ $land->id }}/update-status`, {
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
            select.innerHTML = originalContent;
            select.disabled = false;
        }
    } catch (error) {
        showAlert('An error occurred', 'error');
        select.innerHTML = originalContent;
        select.disabled = false;
    }
}

// Delete Land Function
async function deleteLand(landId) {
    if (!confirm('Are you sure you want to delete this agricultural land? This action cannot be undone.')) return;
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-2xl mb-4"></i><h4 class="font-bold text-lg mb-2 text-gray-800">Deleting...</h4><p class="text-sm text-gray-600">Please wait</p>';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/lands/${landId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            setTimeout(() => window.location.href = '{{ route("admin.lands.index") }}', 1000);
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

// Print Property Details Function
function printPropertyDetails() {
    // Create a new window for printing
    const printWindow = window.open('', '_blank');
    
    // Get the main content without buttons and actions
    const landTitle = '{{ $land->title }}';
    const landRegion = '{{ $land->region }}';
    const landCountry = '{{ $land->country }}';
    const landSurface = '{{ number_format($land->surface, 1) }}';
    const landPrice = '{{ number_format($land->price) }}';
    const landStatus = '{{ ucfirst($land->status) }}';
    const landDescription = '{{ $land->description }}';
    const landSoilType = '{{ $land->soil_type ?: "Not specified" }}';
    const landCoordinates = '{{ $land->gps_coordinates ?: "Not provided" }}';
    const landCreated = '{{ $land->created_at->format("M d, Y H:i") }}';
    const landUpdated = '{{ $land->updated_at->format("M d, Y H:i") }}';
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
            <head>
                <title>Property Details - ${landTitle}</title>
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        margin: 20px; 
                        line-height: 1.6;
                        color: #333;
                    }
                    .header {
                        text-align: center;
                        border-bottom: 2px solid #10b981;
                        padding-bottom: 20px;
                        margin-bottom: 30px;
                    }
                    .header h1 {
                        color: #10b981;
                        margin: 0;
                        font-size: 28px;
                    }
                    .section {
                        margin-bottom: 25px;
                        padding: 15px;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                    }
                    .section h2 {
                        color: #10b981;
                        border-bottom: 1px solid #eee;
                        padding-bottom: 10px;
                    }
                    .info-row {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 10px;
                        padding: 8px 0;
                        border-bottom: 1px dotted #ddd;
                    }
                    .info-label {
                        font-weight: bold;
                        color: #666;
                    }
                    .info-value {
                        color: #333;
                    }
                    .price-highlight {
                        color: #10b981;
                        font-weight: bold;
                        font-size: 18px;
                    }
                    .status-available { color: #10b981; }
                    .status-sold { color: #ef4444; }
                    .status-reserved { color: #f59e0b; }
                    .footer {
                        margin-top: 40px;
                        text-align: center;
                        font-size: 12px;
                        color: #666;
                        border-top: 1px solid #ddd;
                        padding-top: 20px;
                    }
                    @media print {
                        body { margin: 0; }
                        .section { break-inside: avoid; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>🌾 Agricultural Property Details</h1>
                    <p>Property Report for: <strong>${landTitle}</strong></p>
                    <p>Generated on: ${new Date().toLocaleString()}</p>
                </div>

                <div class="section">
                    <h2>Basic Information</h2>
                    <div class="info-row">
                        <span class="info-label">Property Title:</span>
                        <span class="info-value">${landTitle}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Description:</span>
                        <span class="info-value">${landDescription}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Surface Area:</span>
                        <span class="info-value">${landSurface} hectares</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total Price:</span>
                        <span class="info-value price-highlight">${landPrice} MAD</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Price per Hectare:</span>
                        <span class="info-value">{{ number_format($land->price / max($land->surface, 1), 0) }} MAD/ha</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value status-${landStatus.toLowerCase()}">${landStatus}</span>
                    </div>
                </div>

                <div class="section">
                    <h2>Location Details</h2>
                    <div class="info-row">
                        <span class="info-label">Region:</span>
                        <span class="info-value">${landRegion}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Country:</span>
                        <span class="info-value">🇲🇦 ${landCountry}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">GPS Coordinates:</span>
                        <span class="info-value">${landCoordinates}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Soil Type:</span>
                        <span class="info-value">${landSoilType}</span>
                    </div>
                </div>

                <div class="section">
                    <h2>Property Timeline</h2>
                    <div class="info-row">
                        <span class="info-label">Listed Date:</span>
                        <span class="info-value">${landCreated}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Last Updated:</span>
                        <span class="info-value">${landUpdated}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Days Listed:</span>
                        <span class="info-value">{{ intval($land->created_at->diffInDays(now())) }} days</span>
                    </div>
                </div>

                @if($land->transactions->count() > 0)
                <div class="section">
                    <h2>Transaction Summary</h2>
                    <div class="info-row">
                        <span class="info-label">Total Transactions:</span>
                        <span class="info-value">{{ $land->transactions->count() }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Completed Transactions:</span>
                        <span class="info-value">{{ $land->transactions->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total Revenue:</span>
                        <span class="info-value price-highlight">{{ number_format($land->transactions->where('status', 'completed')->sum('amount'), 0) }} MAD</span>
                    </div>
                </div>
                @endif

                <div class="footer">
                    <p>This report was generated from the AgriTerre Admin Panel</p>
                    <p>For more information, visit the property details page</p>
                </div>
            </body>
        </html>
    `);
    
    printWindow.document.close();
    
    // Wait for content to load then print
    setTimeout(() => {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }, 500);
    
    showAlert('Property details prepared for printing!', 'success');
}

// Share Property Function
function shareProperty() {
    const url = window.location.href;
    const title = '{{ $land->title }}';
    const text = `Check out this agricultural property: ${title} in {{ $land->region }}, {{ $land->country }}. Surface: {{ number_format($land->surface, 1) }} hectares, Price: {{ number_format($land->price) }} MAD`;
    
    // Try to use the Web Share API if available
    if (navigator.share && navigator.canShare && navigator.canShare({ title, text, url })) {
        navigator.share({
            title: title,
            text: text,
            url: url
        }).then(() => {
            showAlert('Property shared successfully!', 'success');
        }).catch((error) => {
            // If share fails, fallback to clipboard
            copyToClipboard(url, text);
        });
    } else {
        // Fallback to clipboard copy
        copyToClipboard(url, text);
    }
}

// Helper function to copy to clipboard
function copyToClipboard(url, text) {
    const shareText = `${text}\n\nView details: ${url}`;
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(shareText).then(() => {
            showAlert('Property details copied to clipboard!', 'success');
        }).catch(() => {
            // Fallback for older browsers
            fallbackCopyToClipboard(shareText);
        });
    } else {
        // Fallback for older browsers
        fallbackCopyToClipboard(shareText);
    }
}

// Fallback copy function for older browsers
function fallbackCopyToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showAlert('Property details copied to clipboard!', 'success');
    } catch (err) {
        showAlert('Unable to copy to clipboard. Please copy the URL manually.', 'error');
    }
    
    document.body.removeChild(textArea);
}

// Enhanced Alert Function
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    let bgColor = 'bg-gradient-to-r from-blue-500 to-blue-600';
    let icon = 'fa-info-circle';
    
    switch(type) {
        case 'success':
            bgColor = 'bg-gradient-to-r from-green-500 to-green-600';
            icon = 'fa-check-circle';
            break;
        case 'error':
            bgColor = 'bg-gradient-to-r from-red-500 to-red-600';
            icon = 'fa-exclamation-circle';
            break;
    }
    
    alertDiv.className = `fixed top-4 right-4 z-50 p-6 rounded-xl shadow-2xl transform transition-all duration-500 ${bgColor} text-white`;
    
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                <i class="fas ${icon}"></i>
            </div>
            <div class="flex-1">
                <div class="font-semibold">${type.charAt(0).toUpperCase() + type.slice(1)}!</div>
                <div class="text-sm opacity-90">${message}</div>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 w-6 h-6 bg-white bg-opacity-20 rounded-lg flex items-center justify-center transition-all">
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

// Initialize page animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate cards on load
    const cards = document.querySelectorAll('.card-animate');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + E to edit land
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
        e.preventDefault();
        window.location.href = '{{ route("admin.lands.edit", $land) }}';
    }
    
    // Ctrl/Cmd + P to print
    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault();
        printPropertyDetails();
    }
    
    // Ctrl/Cmd + Backspace to go back
    if ((e.ctrlKey || e.metaKey) && e.key === 'Backspace') {
        e.preventDefault();
        window.location.href = '{{ route("admin.lands.index") }}';
    }
    
    // Ctrl/Cmd + S to share
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        shareProperty();
    }
});
</script>
@endpush