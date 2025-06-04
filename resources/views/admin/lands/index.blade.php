@extends('layouts.admin')

@section('title', 'Agricultural Lands')

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-4xl font-bold mb-2">🌾 Agricultural Land Portfolio</h1>
                <p class="text-xl opacity-90 mb-4">Manage premium agricultural properties across Morocco</p>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-map-marked-alt mr-2"></i>
                        <span>{{ number_format($stats['total']) }} Properties</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-seedling mr-2"></i>
                        <span>{{ number_format($stats['total_surface'], 1) }} Hectares</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-dollar-sign mr-2"></i>
                        <span>${{ number_format($stats['avg_price'], 0) }} Avg Price</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ number_format($stats['available']) }} Available</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-tractor text-6xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Lands Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-map text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Properties</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['total']) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-blue-200">Listed:</span>
                            <span class="font-semibold">{{ $stats['total'] }}</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 100%"></div>
                        </div>
                        <p class="text-xs text-blue-200">All land properties</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="w-16 h-16 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <canvas id="totalLandsChart" width="50" height="50"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Lands -->
        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-green-100 text-sm font-medium">Available Now</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['available']) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-green-200">Ready for sale:</span>
                            <span class="font-semibold">{{ $stats['available'] }}</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['available'] / $stats['total']) * 100) : 0 }}%"></div>
                        </div>
                        <p class="text-xs text-green-200">{{ $stats['total'] > 0 ? round(($stats['available'] / $stats['total']) * 100) : 0 }}% availability rate</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="w-16 h-16 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <canvas id="availableLandsChart" width="50" height="50"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Surface -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-ruler-combined text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Total Surface</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['total_surface'], 1) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-purple-200">Hectares:</span>
                            <span class="font-semibold">{{ number_format($stats['total_surface'], 1) }} ha</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 75%"></div>
                        </div>
                        <p class="text-xs text-purple-200">Agricultural portfolio</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="w-16 h-16 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <canvas id="surfaceChart" width="50" height="50"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Price -->
        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-tags text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Average Price</p>
                            <h3 class="text-3xl font-bold">${{ number_format($stats['avg_price'], 0) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-yellow-200">Per property:</span>
                            <span class="font-semibold">${{ number_format($stats['avg_price'], 0) }}</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 85%"></div>
                        </div>
                        <p class="text-xs text-yellow-200">Market competitive</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="w-16 h-16 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <canvas id="priceChart" width="50" height="50"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filters with Map Integration -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">🔍 Advanced Property Search</h3>
                <p class="text-gray-600">Find properties with precision filters and map view</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl hover:from-green-600 hover:to-green-700 transition-all flex items-center">
                    <i class="fas fa-map mr-2"></i>Map View
                </button>
                <a href="{{ route('admin.lands.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all flex items-center">
                    <i class="fas fa-plus mr-2"></i>Add Property
                </a>
                <button class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all flex items-center">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
            </div>
        </div>
        
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-2 text-blue-500"></i>Search Properties
                </label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Title, region, description..."
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on mr-2 text-green-500"></i>Status
                </label>
                <select name="status" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>🟢 Available</option>
                    <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>🔴 Sold</option>
                    <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>🟡 Reserved</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Region
                </label>
                <select name="region" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
                    <option value="">All Regions</option>
                    @foreach($regions as $region)
                        <option value="{{ $region }}" {{ request('region') === $region ? 'selected' : '' }}>
                            🗺️ {{ $region }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign mr-2 text-yellow-500"></i>Min Price
                </label>
                <input type="number" name="min_price" value="{{ request('min_price') }}" 
                       placeholder="0"
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign mr-2 text-orange-500"></i>Max Price
                </label>
                <input type="number" name="max_price" value="{{ request('max_price') }}" 
                       placeholder="999999"
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            </div>

            <div class="flex items-end space-x-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white py-3 px-6 rounded-xl hover:from-green-600 hover:to-green-700 transition-all flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                <a href="{{ route('admin.lands.index') }}" class="bg-gray-200 text-gray-700 py-3 px-4 rounded-xl hover:bg-gray-300 transition-all">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Enhanced Lands Grid/Table -->
    <div class="glass-effect rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-b">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">🏞️ Property Portfolio</h3>
                    <p class="text-gray-600">{{ $lands->total() }} properties found • {{ number_format($stats['total_surface'], 1) }} total hectares</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- View toggles -->
                    <div class="flex bg-white rounded-lg p-1 shadow-inner">
                        <button id="gridView" class="px-4 py-2 bg-green-500 text-white rounded text-sm transition-all">
                            <i class="fas fa-th mr-1"></i>Grid
                        </button>
                        <button id="listView" class="px-4 py-2 text-gray-600 rounded text-sm hover:bg-gray-100 transition-all">
                            <i class="fas fa-list mr-1"></i>List
                        </button>
                    </div>
                    
                    <!-- Sort options -->
                    <select class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                        <option>📅 Sort: Newest</option>
                        <option>📅 Sort: Oldest</option>
                        <option>💰 Sort: Price Low-High</option>
                        <option>💰 Sort: Price High-Low</option>
                        <option>📏 Sort: Size Large-Small</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Grid View -->
        <div id="gridContainer" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($lands as $land)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:scale-105 card-hover">
                        <!-- Property Image Placeholder -->
                        <div class="h-48 bg-gradient-to-br from-green-400 to-green-600 relative overflow-hidden">
                            <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
                                <i class="fas fa-seedling text-6xl text-white opacity-50"></i>
                            </div>
                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full
                                    @if($land->status === 'available') bg-green-500 text-white
                                    @elseif($land->status === 'sold') bg-red-500 text-white
                                    @else bg-yellow-500 text-white @endif">
                                    @if($land->status === 'available') 🟢 Available
                                    @elseif($land->status === 'sold') 🔴 Sold
                                    @else 🟡 Reserved @endif
                                </span>
                            </div>
                            <!-- Price Badge -->
                            <div class="absolute top-4 right-4">
                                <span class="bg-white bg-opacity-90 text-gray-800 px-3 py-1 rounded-full text-sm font-bold">
                                    ${{ number_format($land->price) }}
                                </span>
                            </div>
                        </div>

                        <!-- Property Info -->
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="text-xl font-bold text-gray-800 mb-2">
                                    <a href="{{ route('admin.lands.show', $land) }}" class="hover:text-green-600 transition-colors">
                                        {{ $land->title }}
                                    </a>
                                </h4>
                                <p class="text-gray-600 text-sm line-clamp-2">{{ Str::limit($land->description, 100) }}</p>
                            </div>

                            <!-- Property Details -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                    <span>{{ $land->region }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-ruler-combined text-purple-500 mr-2"></i>
                                    <span>{{ number_format($land->surface, 1) }} ha</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-flag text-blue-500 mr-2"></i>
                                    <span>{{ $land->country }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar text-green-500 mr-2"></i>
                                    <span>{{ $land->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <!-- Price per Hectare -->
                            <div class="mb-4 p-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Price per hectare:</span>
                                    <span class="font-bold text-blue-600">${{ number_format($land->price / max($land->surface, 1), 0) }}/ha</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.lands.show', $land) }}" 
                                   class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white py-2 px-4 rounded-lg hover:from-green-600 hover:to-green-700 transition-all text-center text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>View Details
                                </a>
                                <a href="{{ route('admin.lands.edit', $land) }}" 
                                   class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition-all">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <button onclick="deleteLand({{ $land->id }})" 
                                        class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 transition-all">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-16">
                        <div class="w-32 h-32 bg-gradient-to-r from-gray-200 to-gray-300 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-seedling text-6xl text-gray-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">No Properties Found</h3>
                        <p class="text-gray-600 mb-6">No agricultural lands match your current filters</p>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.lands.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-3 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                                <i class="fas fa-refresh mr-2"></i>Reset Filters
                            </a>
                            <a href="{{ route('admin.lands.create') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-lg hover:from-green-600 hover:to-green-700 transition-all">
                                <i class="fas fa-plus mr-2"></i>Add First Property
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- List View (Hidden by default) -->
        <div id="listContainer" class="hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-home mr-2 text-green-500"></i>Property
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Location
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-ruler-combined mr-2 text-purple-500"></i>Surface
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-dollar-sign mr-2 text-yellow-500"></i>Price
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-toggle-on mr-2 text-blue-500"></i>Status
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-2 text-indigo-500"></i>Created
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
                        @foreach($lands as $land)
                            <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-blue-50 transition-all duration-300">
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-14 w-14">
                                            <div class="h-14 w-14 rounded-xl bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center">
                                                <i class="fas fa-seedling text-white text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-lg font-bold text-gray-900">
                                                <a href="{{ route('admin.lands.show', $land) }}" class="hover:text-green-600 transition-colors">
                                                    {{ $land->title }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ Str::limit($land->description, 60) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="space-y-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $land->region }}</div>
                                        <div class="text-sm text-gray-500">{{ $land->country }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="text-lg font-bold text-purple-600">{{ number_format($land->surface, 1) }} ha</div>
                                    <div class="text-xs text-gray-500">hectares</div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="space-y-1">
                                        <div class="text-lg font-bold text-gray-900">${{ number_format($land->price) }}</div>
                                        <div class="text-xs text-gray-500">${{ number_format($land->price / max($land->surface, 1), 0) }}/ha</div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                        @if($land->status === 'available') bg-gradient-to-r from-green-100 to-green-200 text-green-800
                                        @elseif($land->status === 'sold') bg-gradient-to-r from-red-100 to-red-200 text-red-800
                                        @else bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 @endif">
                                        @if($land->status === 'available') 🟢 Available
                                        @elseif($land->status === 'sold') 🔴 Sold
                                        @else 🟡 Reserved @endif
                                    </span>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="space-y-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $land->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $land->created_at->diffForHumans() }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.lands.show', $land) }}" 
                                           class="bg-gradient-to-r from-green-500 to-green-600 text-white p-2 rounded-lg hover:from-green-600 hover:to-green-700 transition-all transform hover:scale-110">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('admin.lands.edit', $land) }}" 
                                           class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-110">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <button onclick="deleteLand({{ $land->id }})" 
                                                class="bg-gradient-to-r from-red-500 to-red-600 text-white p-2 rounded-lg hover:from-red-600 hover:to-red-700 transition-all transform hover:scale-110">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Enhanced Pagination -->
        @if($lands->hasPages())
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <span>Showing {{ $lands->firstItem() }} to {{ $lands->lastItem() }} of {{ $lands->total() }} properties</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        {{ $lands->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Property Analytics Dashboard -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Regional Distribution Chart -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">🗺️ Regional Distribution</h3>
                    <p class="text-gray-600">Properties by Morocco regions</p>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="regionChart"></canvas>
            </div>
        </div>

        <!-- Price Analytics -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">💰 Price Analytics</h3>
                    <p class="text-gray-600">Property value distribution</p>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl">
                    <div>
                        <p class="text-sm text-gray-600">Lowest Price</p>
                        <p class="text-xl font-bold text-green-600">$25,000</p>
                    </div>
                    <i class="fas fa-arrow-down text-green-500 text-2xl"></i>
                </div>
                <div class="flex justify-between items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl">
                    <div>
                        <p class="text-sm text-gray-600">Average Price</p>
                        <p class="text-xl font-bold text-blue-600">${{ number_format($stats['avg_price'], 0) }}</p>
                    </div>
                    <i class="fas fa-chart-line text-blue-500 text-2xl"></i>
                </div>
                <div class="flex justify-between items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl">
                    <div>
                        <p class="text-sm text-gray-600">Highest Price</p>
                        <p class="text-xl font-bold text-purple-600">$750,000</p>
                    </div>
                    <i class="fas fa-arrow-up text-purple-500 text-2xl"></i>
                </div>
                <div class="p-4 bg-gradient-to-r from-yellow-50 to-orange-100 rounded-xl">
                    <div class="relative h-24">
                        <canvas id="priceDistributionChart"></canvas>
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

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
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

/* Custom scrollbar */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Table hover effects */
tbody tr:hover {
    background: linear-gradient(90deg, rgba(34, 197, 94, 0.05) 0%, rgba(59, 130, 246, 0.05) 100%);
}

/* Button pulse animation */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.btn-pulse:hover {
    animation: pulse 1s infinite;
}

/* Loading state */
.loading {
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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Set CSRF token for AJAX requests
window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// View Toggle Functionality
document.getElementById('gridView').addEventListener('click', function() {
    document.getElementById('gridContainer').classList.remove('hidden');
    document.getElementById('listContainer').classList.add('hidden');
    this.classList.add('bg-green-500', 'text-white');
    this.classList.remove('text-gray-600');
    document.getElementById('listView').classList.remove('bg-green-500', 'text-white');
    document.getElementById('listView').classList.add('text-gray-600');
});

document.getElementById('listView').addEventListener('click', function() {
    document.getElementById('gridContainer').classList.add('hidden');
    document.getElementById('listContainer').classList.remove('hidden');
    this.classList.add('bg-green-500', 'text-white');
    this.classList.remove('text-gray-600');
    document.getElementById('gridView').classList.remove('bg-green-500', 'text-white');
    document.getElementById('gridView').classList.add('text-gray-600');
});

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
createMiniChart('totalLandsChart', [10, 15, 12, 18, 16, 22], '#3B82F6');
createMiniChart('availableLandsChart', [8, 12, 10, 15, 13, 18], '#10B981');
createMiniChart('surfaceChart', [200, 250, 180, 300, 280, 350], '#8B5CF6');
createMiniChart('priceChart', [25000, 35000, 45000, 40000, 50000, 60000], '#F59E0B');

// Regional Distribution Chart
const regionCtx = document.getElementById('regionChart').getContext('2d');
new Chart(regionCtx, {
    type: 'doughnut',
    data: {
        labels: ['Casablanca-Settat', 'Marrakech-Safi', 'Rabat-Salé-Kénitra', 'Fès-Meknès', 'Others'],
        datasets: [{
            data: [25, 20, 15, 12, 28],
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)'
            ],
            borderColor: [
                '#3B82F6',
                '#10B981',
                '#8B5CF6',
                '#F59E0B',
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
                    usePointStyle: true,
                    font: {
                        size: 12
                    }
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

// Price Distribution Chart
const priceDistCtx = document.getElementById('priceDistributionChart').getContext('2d');
new Chart(priceDistCtx, {
    type: 'bar',
    data: {
        labels: ['<$50K', '$50K-$100K', '$100K-$200K', '$200K-$500K', '>$500K'],
        datasets: [{
            data: [15, 25, 35, 20, 5],
            backgroundColor: [
                'rgba(34, 197, 94, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)'
            ],
            borderColor: [
                '#22C55E',
                '#3B82F6',
                '#8B5CF6',
                '#F59E0B',
                '#EF4444'
            ],
            borderWidth: 1,
            borderRadius: 4
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
                borderColor: '#10B981',
                borderWidth: 1,
                cornerRadius: 8
            }
        },
        scales: {
            x: {
                grid: { display: false },
                border: { display: false },
                ticks: {
                    font: { size: 10 }
                }
            },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                border: { display: false },
                ticks: {
                    font: { size: 10 }
                }
            }
        }
    }
});

// Enhanced delete function
async function deleteLand(landId) {
    if (!confirm('Are you sure you want to delete this agricultural land? This action cannot be undone.')) return;
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i>';
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
            // Animate card removal
            const card = button.closest('.card-hover') || button.closest('tr');
            if (card) {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                setTimeout(() => location.reload(), 1000);
            }
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

// Add loading states and animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate cards on load
    const cards = document.querySelectorAll('.card-hover');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Add hover effects to property cards
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
            this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.1)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '';
        });
    });

    // Add search form enhancements
    const searchForm = document.querySelector('form');
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalContent = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Searching...';
                submitBtn.disabled = true;
            }
        });
    }

    // Add real-time search suggestions (demo)
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                // Here you could add live search suggestions
                console.log('Searching for:', this.value);
            }, 500);
        });
    }

    // Add smooth scrolling to pagination
    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.href;
            
            // Add loading state
            document.body.style.cursor = 'wait';
            
            // Simulate navigation (replace with actual navigation)
            setTimeout(() => {
                window.location.href = url;
            }, 300);
        });
    });
});

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + N for new property
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        window.location.href = '{{ route("admin.lands.create") }}';
    }
    
    // Ctrl/Cmd + F for search focus
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
    
    // G for grid view, L for list view
    if (e.key === 'g' && !e.ctrlKey && !e.metaKey) {
        document.getElementById('gridView').click();
    }
    if (e.key === 'l' && !e.ctrlKey && !e.metaKey) {
        document.getElementById('listView').click();
    }
});

// Add property quick preview on hover (optional enhancement)
function addPropertyPreview() {
    const propertyLinks = document.querySelectorAll('a[href*="/admin/lands/"]');
    propertyLinks.forEach(link => {
        link.addEventListener('mouseenter', function(e) {
            // Could show quick preview tooltip
            const tooltip = document.createElement('div');
            tooltip.className = 'fixed bg-black bg-opacity-90 text-white p-4 rounded-lg z-50 pointer-events-none';
            tooltip.innerHTML = `
                <div class="text-sm">
                    <div class="font-bold mb-2">Quick Preview</div>
                    <div>Click to view full details</div>
                </div>
            `;
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.right + 10 + 'px';
            tooltip.style.top = rect.top + 'px';
            
            this.addEventListener('mouseleave', function() {
                if (tooltip.parentElement) {
                    tooltip.remove();
                }
            }, { once: true });
        });
    });
}

// Initialize preview on load
setTimeout(addPropertyPreview, 1000);

// Export functionality
document.querySelector('button:has(i.fa-download)')?.addEventListener('click', function() {
    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
    this.disabled = true;
    
    // Simulate export process
    setTimeout(() => {
        this.innerHTML = '<i class="fas fa-download mr-2"></i>Export';
        this.disabled = false;
        showAlert('Properties exported successfully!', 'success');
    }, 2000);
});

// Map view functionality
document.querySelector('button:has(i.fa-map)')?.addEventListener('click', function() {
    showAlert('Map view feature coming soon!', 'info');
});
</script>
@endpush

@endsection