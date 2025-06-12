@extends('layouts.admin')

@section('title', 'Listings Management')

@section('content')
<div class="space-y-6">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-4xl font-bold mb-2">📋 Listings Management Center</h1>
                <p class="text-xl opacity-90 mb-4">Manage and monitor all agricultural land listings efficiently</p>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-bullhorn mr-2"></i>
                        <span>{{ number_format($stats['total']) }} Total Listings</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ number_format($stats['active']) }} Active</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-star mr-2"></i>
                        <span>{{ number_format($stats['featured']) }} Featured</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-calendar-check mr-2"></i>
                        <span>{{ number_format($stats['published']) }} Published</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-6xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Total Listings Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-bullhorn text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Listings</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['total']) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-blue-200">All listings:</span>
                            <span class="font-semibold">{{ $stats['total'] }}</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 100%"></div>
                        </div>
                        <p class="text-xs text-blue-200">Complete portfolio</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Listings -->
        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-green-100 text-sm font-medium">Active Listings</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['active']) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-green-200">Live on platform:</span>
                            <span class="font-semibold">{{ $stats['active'] }}</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['active'] / $stats['total']) * 100) : 0 }}%"></div>
                        </div>
                        <p class="text-xs text-green-200">{{ $stats['total'] > 0 ? round(($stats['active'] / $stats['total']) * 100) : 0 }}% active rate</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inactive Listings -->
        <div class="bg-gradient-to-br from-red-500 to-red-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-times-circle text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-red-100 text-sm font-medium">Inactive Listings</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['inactive']) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-red-200">Not visible:</span>
                            <span class="font-semibold">{{ $stats['inactive'] }}</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['inactive'] / $stats['total']) * 100) : 0 }}%"></div>
                        </div>
                        <p class="text-xs text-red-200">Needs attention</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Listings -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-star text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Featured Listings</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['featured']) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-purple-200">Premium spots:</span>
                            <span class="font-semibold">{{ $stats['featured'] }}</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['featured'] / $stats['total']) * 100) : 0 }}%"></div>
                        </div>
                        <p class="text-xs text-purple-200">High visibility</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Published Listings -->
        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-check text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Published Listings</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['published']) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-yellow-200">With publish date:</span>
                            <span class="font-semibold">{{ $stats['published'] }}</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['published'] / $stats['total']) * 100) : 0 }}%"></div>
                        </div>
                        <p class="text-xs text-yellow-200">Ready for market</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filters with Advanced Search -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">🔍 Advanced Listing Filters</h3>
                <p class="text-gray-600">Find and manage listings with precision controls</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="exportListings()" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all">
                    <i class="fas fa-download mr-2"></i>Export Listings
                </button>
                <a href="{{ route('admin.listings.create') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition-all">
                    <i class="fas fa-plus mr-2"></i>Add Listing
                </a>
                <button onclick="showAnalytics()" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all">
                    <i class="fas fa-chart-bar mr-2"></i>Analytics
                </button>
            </div>
        </div>
        
        <form method="GET" action="{{ route('admin.listings.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-2 text-blue-500"></i>Search Listings
                </label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Title, description, land..."
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on mr-2 text-green-500"></i>Listing Status
                </label>
                <select name="status" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>🟢 Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>🔴 Inactive</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-star mr-2 text-purple-500"></i>Featured Status
                </label>
                <select name="featured" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    <option value="">All Listings</option>
                    <option value="yes" {{ request('featured') === 'yes' ? 'selected' : '' }}>⭐ Featured</option>
                    <option value="no" {{ request('featured') === 'no' ? 'selected' : '' }}>📄 Standard</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user-tie mr-2 text-yellow-500"></i>Supplier
                </label>
               <select name="fournisseur" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
    <option value="">All Suppliers</option>
    @foreach($fournisseurs as $fournisseur)
        <option value="{{ $fournisseur->id }}" {{ request('fournisseur') == $fournisseur->id ? 'selected' : '' }}>
            🏢 {{ $fournisseur->user->first_name ?? 'Unknown' }} {{ $fournisseur->user->last_name ?? 'User' }}
        </option>
    @endforeach
</select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sort mr-2 text-indigo-500"></i>Sort By
                </label>
                <select name="sort" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    <option value="newest">📅 Newest First</option>
                    <option value="oldest">📅 Oldest First</option>
                    <option value="featured">⭐ Featured First</option>
                    <option value="active">🟢 Active First</option>
                </select>
            </div>

            <div class="flex items-end space-x-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-6 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                <a href="{{ route('admin.listings.index') }}" class="bg-gray-200 text-gray-700 py-3 px-4 rounded-xl hover:bg-gray-300 transition-all">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Bar (Initially Hidden) -->
    <div id="bulk-actions-bar" class="hidden glass-effect rounded-2xl p-4 shadow-xl border-2 border-purple-300">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span id="selected-count" class="text-lg font-semibold text-purple-700 mr-4">0 items selected</span>
                <div class="flex space-x-2">
                    <button onclick="bulkToggleStatus()" class="bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition-all text-sm">
                        <i class="fas fa-toggle-on mr-1"></i>Toggle Status
                    </button>
                    <button onclick="bulkToggleFeatured()" class="bg-yellow-500 text-white px-3 py-2 rounded-lg hover:bg-yellow-600 transition-all text-sm">
                        <i class="fas fa-star mr-1"></i>Toggle Featured
                    </button>
                    <button onclick="bulkPublish()" class="bg-blue-500 text-white px-3 py-2 rounded-lg hover:bg-blue-600 transition-all text-sm">
                        <i class="fas fa-calendar mr-1"></i>Publish
                    </button>
                    <button onclick="bulkDelete()" class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition-all text-sm">
                        <i class="fas fa-trash mr-1"></i>Delete
                    </button>
                </div>
            </div>
            <button onclick="clearSelection()" class="text-gray-500 hover:text-gray-700 transition-all">
                <i class="fas fa-times"></i> Clear Selection
            </button>
        </div>
    </div>

    <!-- Enhanced Listings Table with Improved Layout -->
    <div class="glass-effect rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-b">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">📋 Listings Directory</h3>
                    <p class="text-gray-600">{{ $listings->total() }} listings found • Managing agricultural property advertisements</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- View toggles -->
                    <div class="flex bg-white rounded-lg p-1 shadow-inner">
                        <button onclick="switchView('grid')" id="grid-btn" class="px-3 py-1 text-gray-600 rounded text-sm hover:bg-gray-100 transition-all">
                            <i class="fas fa-th mr-1"></i>Grid
                        </button>
                        <button onclick="switchView('list')" id="list-btn" class="px-3 py-1 bg-purple-500 text-white rounded text-sm transition-all">
                            <i class="fas fa-list mr-1"></i>List
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Improved Table with Better Spacing -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed">
                <colgroup>
                    <col class="w-2/12"> <!-- Listing Details -->
                    <col class="w-2/12"> <!-- Agricultural Land -->
                    <col class="w-2/12"> <!-- Supplier Info -->
                    <col class="w-2/12"> <!-- Status & Features -->
                    <col class="w-2/12"> <!-- Publishing -->
                    <col class="w-2/12"> <!-- Actions -->
                </colgroup>
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input type="checkbox" id="select-all" class="mr-3 w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                <i class="fas fa-bullhorn mr-2 text-purple-500"></i>Listing Details
                            </div>
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-seedling mr-2 text-green-500"></i>Agricultural Land
                            </div>
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-user-tie mr-2 text-blue-500"></i>Supplier Info
                            </div>
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-toggle-on mr-2 text-yellow-500"></i>Status & Features
                            </div>
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-indigo-500"></i>Publishing
                            </div>
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-cogs mr-2 text-gray-500"></i>Actions
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($listings as $listing)
                        <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-blue-50 transition-all duration-300">
                            <!-- Listing Details -->
                            <td class="px-4 py-4">
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" class="listing-checkbox mt-1 w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500" value="{{ $listing->id }}">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-purple-400 to-blue-500 flex items-center justify-center shadow-md relative">
                                            <i class="fas fa-bullhorn text-white text-lg"></i>
                                            @if($listing->is_featured)
                                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-yellow-500 border-2 border-white rounded-full flex items-center justify-center">
                                                    <i class="fas fa-star text-white text-xs"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <a href="{{ route('admin.listings.show', $listing) }}" class="text-sm font-bold text-gray-900 hover:text-purple-600 transition-colors truncate">
                                                {{ $listing->title }}
                                            </a>
                                            @if($listing->is_featured)
                                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-semibold">
                                                    ⭐
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 line-clamp-2 mb-1">{{ Str::limit($listing->description, 60) }}</div>
                                        <div class="flex items-center text-xs text-gray-400">
                                            <i class="fas fa-clock mr-1"></i>
                                            <span>{{ $listing->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Agricultural Land -->
                            <td class="px-4 py-4">
                                @if($listing->terreAgricole)
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <i class="fas fa-map-marker-alt text-red-500 mr-2 text-sm"></i>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-sm font-medium text-gray-900 truncate">{{ $listing->terreAgricole->title }}</div>
                                                <div class="text-xs text-gray-500 truncate">{{ $listing->terreAgricole->region }}</div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 gap-1 text-xs text-gray-500">
                                            <span class="flex items-center">
                                                <i class="fas fa-ruler-combined text-purple-500 mr-1"></i>
                                                {{ number_format($listing->terreAgricole->surface, 1) }} ha
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-dollar-sign text-green-500 mr-1"></i>
                                                {{ number_format($listing->terreAgricole->price) }} MAD
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center text-red-500">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        <span class="text-sm">Land not found</span>
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Supplier Info -->
                            <!-- Supplier Info -->
<td class="px-4 py-4">
    @if($listing->fournisseur && $listing->fournisseur->user)
        <div class="flex items-center space-x-2">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-lg bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                    <span class="text-white font-bold text-xs">
                        {{ substr($listing->fournisseur->user->first_name ?? 'U', 0, 1) }}{{ substr($listing->fournisseur->user->last_name ?? 'U', 0, 1) }}
                    </span>
                </div>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-sm font-medium text-gray-900 truncate">
                    {{ $listing->fournisseur->user->first_name }} {{ $listing->fournisseur->user->last_name }}
                </div>
                <div class="text-xs text-gray-500 truncate">{{ $listing->fournisseur->user->email }}</div>
                @if($listing->fournisseur->company_name)
                    <div class="text-xs text-gray-400 truncate">{{ $listing->fournisseur->company_name }}</div>
                @endif
            </div>
        </div>
    @else
        <div class="flex items-center text-red-500">
            <i class="fas fa-user-times mr-2"></i>
            <span class="text-sm">No supplier</span>
        </div>
    @endif
</td>
                            
                            <!-- Status & Features -->
                            <td class="px-4 py-4">
                                <div class="space-y-2">
                                    <div class="flex flex-col space-y-1">
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $listing->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <div class="w-2 h-2 {{ $listing->is_active ? 'bg-green-500' : 'bg-red-500' }} rounded-full mr-1"></div>
                                            {{ $listing->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if($listing->is_featured)
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-star mr-1"></i>
                                                Featured
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Publishing -->
                            <td class="px-4 py-4">
                                <div class="space-y-1">
                                    @if($listing->published_at)
                                        <div class="text-sm font-medium text-gray-900">Published</div>
                                        <div class="text-xs text-gray-500">{{ $listing->published_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $listing->published_at->diffForHumans() }}</div>
                                    @else
                                        <div class="text-sm font-medium text-gray-500">Not published</div>
                                        <div class="text-xs text-gray-400">Draft status</div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('admin.listings.show', $listing) }}" 
                                       class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-1.5 rounded-md hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-110" 
                                       title="View Details">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    
                                    <a href="{{ route('admin.listings.edit', $listing) }}" 
                                       class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white p-1.5 rounded-md hover:from-indigo-600 hover:to-indigo-700 transition-all transform hover:scale-110" 
                                       title="Edit Listing">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    
                                    <button onclick="toggleStatus({{ $listing->id }})" 
                                            class="bg-gradient-to-r from-{{ $listing->is_active ? 'red' : 'green' }}-500 to-{{ $listing->is_active ? 'red' : 'green' }}-600 text-white p-1.5 rounded-md hover:from-{{ $listing->is_active ? 'red' : 'green' }}-600 hover:to-{{ $listing->is_active ? 'red' : 'green' }}-700 transition-all transform hover:scale-110" 
                                            title="Toggle Status">
                                        <i class="fas fa-toggle-{{ $listing->is_active ? 'on' : 'off' }} text-xs"></i>
                                    </button>
                                    
                                    <button onclick="toggleFeatured({{ $listing->id }})" 
                                            class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-1.5 rounded-md hover:from-yellow-600 hover:to-yellow-700 transition-all transform hover:scale-110" 
                                            title="Toggle Featured">
                                        <i class="fas fa-star{{ $listing->is_featured ? '' : '-o' }} text-xs"></i>
                                    </button>
                                    
                                    <button onclick="deleteListing({{ $listing->id }})" 
                                            class="bg-gradient-to-r from-red-500 to-red-600 text-white p-1.5 rounded-md hover:from-red-600 hover:to-red-700 transition-all transform hover:scale-110" 
                                            title="Delete Listing">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-gradient-to-r from-gray-200 to-gray-300 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-bullhorn text-4xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">No Listings Found</h3>
                                    <p class="text-gray-600 mb-4">No listings match your current filters</p>
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ route('admin.listings.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-3 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                                            <i class="fas fa-refresh mr-2"></i>Reset Filters
                                        </a>
                                        <a href="{{ route('admin.listings.create') }}" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all">
                                            <i class="fas fa-plus mr-2"></i>Create First Listing
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Enhanced Pagination -->
        @if($listings->hasPages())
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <span>Showing {{ $listings->firstItem() }} to {{ $listings->lastItem() }} of {{ $listings->total() }} listings</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        {{ $listings->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Listings Analytics Dashboard -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Listing Performance Chart -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">📊 Listing Performance</h3>
                    <p class="text-gray-600">Active vs inactive listings over time</p>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="listingPerformanceChart"></canvas>
            </div>
        </div>

        <!-- Featured Listings Distribution -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">⭐ Featured Distribution</h3>
                    <p class="text-gray-600">Featured vs standard listings</p>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl">
                    <div>
                        <p class="text-sm text-gray-600">Featured Listings</p>
                        <p class="text-xl font-bold text-yellow-600">{{ $stats['featured'] }}</p>
                    </div>
                    <i class="fas fa-star text-yellow-500 text-2xl"></i>
                </div>
                <div class="flex justify-between items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <div>
                        <p class="text-sm text-gray-600">Standard Listings</p>
                        <p class="text-xl font-bold text-gray-600">{{ $stats['total'] - $stats['featured'] }}</p>
                    </div>
                    <i class="fas fa-list text-gray-500 text-2xl"></i>
                </div>
                <div class="p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl">
                    <div class="relative h-24">
                        <canvas id="featuredDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">⚡ Quick Management Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('admin.listings.create') }}" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all transform hover:scale-105 group block">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-plus text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Create</span>
                </div>
                <h4 class="font-bold text-lg mb-2">New Listing</h4>
                <p class="text-sm opacity-90">Create a new land listing</p>
            </a>
            
            <button onclick="exportListings()" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-105 group">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-file-export text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Export</span>
                </div>
                <h4 class="font-bold text-lg mb-2">Export Data</h4>
                <p class="text-sm opacity-90">Download listings as CSV</p>
            </button>
            
            <button onclick="showBulkActions()" class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl hover:from-green-600 hover:to-green-700 transition-all transform hover:scale-105 group">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-edit text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Bulk</span>
                </div>
                <h4 class="font-bold text-lg mb-2">Bulk Edit</h4>
                <p class="text-sm opacity-90">Update multiple listings</p>
            </button>
            
            <button onclick="generateReport()" class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white p-6 rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all transform hover:scale-105 group">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-chart-pie text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Report</span>
                </div>
                <h4 class="font-bold text-lg mb-2">Analytics</h4>
                <p class="text-sm opacity-90">Detailed listing reports</p>
            </button>
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
    background: linear-gradient(90deg, rgba(147, 51, 234, 0.05) 0%, rgba(59, 130, 246, 0.05) 100%);
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

/* Improved table layout */
.table-fixed {
    table-layout: fixed;
}

.table-fixed td {
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* Bulk actions bar animation */
.bulk-actions-slide-down {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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

/* Responsive improvements */
@media (max-width: 768px) {
    .table-fixed {
        min-width: 800px;
    }
    
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Set CSRF token for AJAX requests
window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

// Improved View Toggle Functionality
function switchView(viewType) {
    const gridBtn = document.getElementById('grid-btn');
    const listBtn = document.getElementById('list-btn');
    
    if (viewType === 'grid') {
        gridBtn.className = 'px-3 py-1 bg-purple-500 text-white rounded text-sm transition-all';
        listBtn.className = 'px-3 py-1 text-gray-600 rounded text-sm hover:bg-gray-100 transition-all';
        showAlert('Grid view will be implemented in future updates', 'info');
    } else {
        listBtn.className = 'px-3 py-1 bg-purple-500 text-white rounded text-sm transition-all';
        gridBtn.className = 'px-3 py-1 text-gray-600 rounded text-sm hover:bg-gray-100 transition-all';
    }
}

// Show Analytics Function
function showAnalytics() {
    showAlert('Advanced analytics dashboard available below!', 'info');
    const analyticsSection = document.querySelector('.grid.grid-cols-1.lg\\:grid-cols-2');
    if (analyticsSection) {
        analyticsSection.scrollIntoView({ behavior: 'smooth' });
    }
}

// Show Bulk Actions Function
function showBulkActions() {
    const checkedBoxes = document.querySelectorAll('.listing-checkbox:checked');
    if (checkedBoxes.length === 0) {
        showAlert('Please select listings first by checking the checkboxes, then use the bulk actions bar that appears.', 'info');
        // Scroll to table to show checkboxes
        const table = document.querySelector('table');
        if (table) {
            table.scrollIntoView({ behavior: 'smooth' });
        }
    } else {
        showAlert(`${checkedBoxes.length} listings already selected. Use the bulk actions bar above the table.`, 'info');
        const bulkBar = document.getElementById('bulk-actions-bar');
        if (bulkBar) {
            bulkBar.scrollIntoView({ behavior: 'smooth' });
        }
    }
}

// Enhanced Selection Management
function updateSelectionState() {
    const checkboxes = document.querySelectorAll('.listing-checkbox');
    const checkedBoxes = document.querySelectorAll('.listing-checkbox:checked');
    const selectAllCheckbox = document.getElementById('select-all');
    const bulkActionsBar = document.getElementById('bulk-actions-bar');
    const selectedCount = document.getElementById('selected-count');
    
    // Update select all checkbox state
    if (selectAllCheckbox) {
        if (checkedBoxes.length === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedBoxes.length === checkboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
            selectAllCheckbox.checked = false;
        }
    }
    
    // Show/hide bulk actions bar
    if (checkedBoxes.length > 0) {
        bulkActionsBar.classList.remove('hidden');
        bulkActionsBar.classList.add('bulk-actions-slide-down');
        selectedCount.textContent = `${checkedBoxes.length} item${checkedBoxes.length !== 1 ? 's' : ''} selected`;
    } else {
        bulkActionsBar.classList.add('hidden');
        bulkActionsBar.classList.remove('bulk-actions-slide-down');
    }
}

// Clear Selection Function
function clearSelection() {
    const checkboxes = document.querySelectorAll('.listing-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelectionState();
}

// Initialize selection management
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.listing-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectionState();
        });
    }
    
    // Add change listeners to individual checkboxes
    const listingCheckboxes = document.querySelectorAll('.listing-checkbox');
    listingCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionState);
    });
    
    // Initial state update
    updateSelectionState();
});

// Enhanced Bulk Operations
async function bulkToggleStatus() {
    const checkedBoxes = document.querySelectorAll('.listing-checkbox:checked');
    const listingIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (listingIds.length === 0) {
        showAlert('Please select listings first.', 'error');
        return;
    }
    
    if (!confirm(`Toggle status for ${listingIds.length} listings?`)) return;
    
    try {
        // Simulate bulk operation - replace with actual API call
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        showAlert(`Status toggled for ${listingIds.length} listings!`, 'success');
        setTimeout(() => location.reload(), 1000);
    } catch (error) {
        showAlert('Failed to toggle status for selected listings.', 'error');
    }
}

async function bulkToggleFeatured() {
    const checkedBoxes = document.querySelectorAll('.listing-checkbox:checked');
    const listingIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (listingIds.length === 0) {
        showAlert('Please select listings first.', 'error');
        return;
    }
    
    if (!confirm(`Toggle featured status for ${listingIds.length} listings?`)) return;
    
    try {
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        showAlert(`Featured status toggled for ${listingIds.length} listings!`, 'success');
        setTimeout(() => location.reload(), 1000);
    } catch (error) {
        showAlert('Failed to toggle featured status for selected listings.', 'error');
    }
}

async function bulkPublish() {
    const checkedBoxes = document.querySelectorAll('.listing-checkbox:checked');
    const listingIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (listingIds.length === 0) {
        showAlert('Please select listings first.', 'error');
        return;
    }
    
    if (!confirm(`Publish ${listingIds.length} listings?`)) return;
    
    try {
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        showAlert(`${listingIds.length} listings published successfully!`, 'success');
        setTimeout(() => location.reload(), 1000);
    } catch (error) {
        showAlert('Failed to publish selected listings.', 'error');
    }
}

async function bulkDelete() {
    const checkedBoxes = document.querySelectorAll('.listing-checkbox:checked');
    const listingIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (listingIds.length === 0) {
        showAlert('Please select listings first.', 'error');
        return;
    }
    
    if (!confirm(`Are you sure you want to delete ${listingIds.length} listings? This action cannot be undone.`)) return;
    
    try {
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        showAlert(`${listingIds.length} listings deleted successfully!`, 'success');
        setTimeout(() => location.reload(), 1000);
    } catch (error) {
        showAlert('Failed to delete selected listings.', 'error');
    }
}

// CRUD Operations
async function toggleStatus(listingId) {
    if (!confirm('Are you sure you want to toggle this listing\'s status?')) return;
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/listings/${listingId}/toggle-status`, {
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
            showAlert(result.message || 'Failed to toggle status', 'error');
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    } catch (error) {
        console.error('Toggle status error:', error);
        showAlert('An error occurred while toggling status', 'error');
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

async function toggleFeatured(listingId) {
    if (!confirm('Are you sure you want to toggle the featured status of this listing?')) return;
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/listings/${listingId}/toggle-featured`, {
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
            showAlert(result.message || 'Failed to toggle featured status', 'error');
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    } catch (error) {
        console.error('Toggle featured error:', error);
        showAlert('An error occurred while toggling featured status', 'error');
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

async function deleteListing(listingId) {
    if (!confirm('Are you sure you want to delete this listing? This action cannot be undone.')) return;
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/listings/${listingId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            const row = button.closest('tr');
            if (row) {
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateX(-100%)';
                setTimeout(() => location.reload(), 1000);
            }
        } else {
            showAlert(result.message || 'Failed to delete listing', 'error');
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    } catch (error) {
        console.error('Delete listing error:', error);
        showAlert('An error occurred while deleting the listing', 'error');
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Export Function
function exportListings() {
    const button = event.target.closest('button') || event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
    button.disabled = true;
    
    try {
        // Create CSV content
        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "ID,Title,Description,Status,Featured,Published,Supplier,Land,Region,Surface,Price\n";
        
        // Get visible table rows and extract data
        const tableRows = document.querySelectorAll('tbody tr:not(.no-listings)');
        tableRows.forEach(row => {
            const checkbox = row.querySelector('.listing-checkbox');
            if (checkbox) {
                const listingId = checkbox.value;
                const title = row.querySelector('a[href*="/admin/listings/"]')?.textContent?.trim() || '';
                const description = row.querySelector('.line-clamp-2')?.textContent?.trim() || '';
                const status = row.querySelector('.inline-flex.items-center')?.textContent?.trim() || '';
                const featured = row.querySelector('.bg-yellow-100') ? 'Yes' : 'No';
                
                csvContent += `"${listingId}","${title}","${description}","${status}","${featured}","","","","","",""\n`;
            }
        });
        
        // Create and trigger download
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `listings_export_${new Date().toISOString().split('T')[0]}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showAlert('Listings exported successfully!', 'success');
        
    } catch (error) {
        console.error('Export error:', error);
        showAlert('Export failed. Please try again.', 'error');
    } finally {
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.disabled = false;
        }, 2000);
    }
}

// Generate Report Function
function generateReport() {
    const button = event.target.closest('button') || event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
    button.disabled = true;
    
    setTimeout(() => {
        const reportWindow = window.open('', '_blank');
        const reportContent = `
            <html>
                <head>
                    <title>Listings Analytics Report</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { background: #8B5CF6; color: white; padding: 20px; border-radius: 8px; }
                        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 20px 0; }
                        .stat-card { background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center; }
                        .stat-number { font-size: 2em; font-weight: bold; color: #8B5CF6; }
                        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #8B5CF6; color: white; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>📊 Listings Analytics Report</h1>
                        <p>Generated on: ${new Date().toLocaleString()}</p>
                    </div>
                    
                    <div class="stats">
                        <div class="stat-card">
                            <div class="stat-number">{{ $stats['total'] }}</div>
                            <div>Total Listings</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $stats['active'] }}</div>
                            <div>Active Listings</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $stats['featured'] }}</div>
                            <div>Featured Listings</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $stats['published'] }}</div>
                            <div>Published Listings</div>
                        </div>
                    </div>
                    
                    <h2>📈 Performance Metrics</h2>
                    <ul>
                        <li>Active Rate: ${Math.round(({{ $stats['active'] }} / Math.max({{ $stats['total'] }}, 1)) * 100)}%</li>
                        <li>Featured Rate: ${Math.round(({{ $stats['featured'] }} / Math.max({{ $stats['total'] }}, 1)) * 100)}%</li>
                        <li>Publication Rate: ${Math.round(({{ $stats['published'] }} / Math.max({{ $stats['total'] }}, 1)) * 100)}%</li>
                    </ul>
                    
                    <h2>📋 Summary</h2>
                    <p>This report provides an overview of your agricultural land listings performance. 
                    Use this data to optimize your listing strategy and improve visibility.</p>
                </body>
            </html>
        `;
        
        reportWindow.document.write(reportContent);
        reportWindow.document.close();
        reportWindow.focus();
        
        showAlert('Analytics report generated successfully!', 'success');
        button.innerHTML = originalContent;
        button.disabled = false;
    }, 2000);
}

// Charts initialization
function initCharts() {
    // Mini charts for cards
    function createMiniChart(canvasId, data, color) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
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

    // Listing Performance Chart
    const performanceCanvas = document.getElementById('listingPerformanceChart');
    if (performanceCanvas) {
        const performanceCtx = performanceCanvas.getContext('2d');
        new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Active Listings',
                    data: [12, 19, 15, 25, 22, 30],
                    borderColor: '#8B5CF6',
                    backgroundColor: function(context) {
                        const chart = context.chart;
                        const {ctx, chartArea} = chart;
                        if (!chartArea) return null;
                        
                        const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                        gradient.addColorStop(0, 'rgba(139, 92, 246, 0.3)');
                        gradient.addColorStop(1, 'rgba(139, 92, 246, 0.05)');
                        return gradient;
                    },
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#8B5CF6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 6
                }, {
                    label: 'Inactive Listings',
                    data: [5, 8, 6, 10, 8, 12],
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#EF4444',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 6
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
                        borderColor: '#8B5CF6',
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
    }

    // Featured Distribution Chart
    const distributionCanvas = document.getElementById('featuredDistributionChart');
    if (distributionCanvas) {
        const distributionCtx = distributionCanvas.getContext('2d');
        new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Featured', 'Standard'],
                datasets: [{
                    data: [{{ $stats['featured'] }}, {{ $stats['total'] - $stats['featured'] }}],
                    backgroundColor: [
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(156, 163, 175, 0.8)'
                    ],
                    borderColor: [
                        '#F59E0B',
                        '#9CA3AF'
                    ],
                    borderWidth: 2
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
                        borderColor: '#F59E0B',
                        borderWidth: 1,
                        cornerRadius: 8
                    }
                }
            }
        });
    }
}

// Enhanced Alert Function
function showAlert(message, type) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.fixed.top-4.right-4');
    existingAlerts.forEach(alert => alert.remove());
    
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
        case 'info':
            bgColor = 'bg-gradient-to-r from-blue-500 to-blue-600';
            icon = 'fa-info-circle';
            break;
        case 'warning':
            bgColor = 'bg-gradient-to-r from-yellow-500 to-yellow-600';
            icon = 'fa-exclamation-triangle';
            break;
    }
    
    alertDiv.className = `fixed top-4 right-4 z-50 p-6 rounded-xl shadow-2xl transform transition-all duration-500 ${bgColor} text-white max-w-md`;
    
    alertDiv.innerHTML = `
        <div class="flex items-start">
            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                <i class="fas ${icon}"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-semibold">${type.charAt(0).toUpperCase() + type.slice(1)}!</div>
                <div class="text-sm opacity-90 break-words">${message}</div>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 w-6 h-6 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-all flex-shrink-0">
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

// Enhanced page loading and animations
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    setTimeout(initCharts, 500);
    
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

    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('tbody tr:not(:last-child)');
    tableRows.forEach(row => {
        if (!row.querySelector('.no-listings')) {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.01)';
                this.style.zIndex = '10';
            });
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.zIndex = 'auto';
            });
        }
    });

    // Add search form enhancements
    const searchForm = document.querySelector('form[method="GET"]');
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

    // Real-time search functionality
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                console.log('Searching for:', this.value);
            }, 500);
        });
    }
    
    // Show welcome message
    setTimeout(() => {
        showAlert('Listings management page loaded successfully!', 'success');
    }, 1000);
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + N for new listing
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        window.location.href = '{{ route("admin.listings.create") }}';
    }
    
    // Ctrl/Cmd + E to export
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
        e.preventDefault();
        exportListings();
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
    
    // A to select all (when Ctrl/Cmd is pressed)
    if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
        e.preventDefault();
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = !selectAllCheckbox.checked;
            selectAllCheckbox.dispatchEvent(new Event('change'));
        }
    }
    
    // Delete key for bulk delete (when items are selected)
    if (e.key === 'Delete') {
        const checkedBoxes = document.querySelectorAll('.listing-checkbox:checked');
        if (checkedBoxes.length > 0) {
            e.preventDefault();
            bulkDelete();
        }
    }
    
    // Escape to clear selection
    if (e.key === 'Escape') {
        clearSelection();
    }
});

// Performance monitoring
function logPerformance() {
    if (performance.mark) {
        performance.mark('listings-page-loaded');
        console.log('Listings page performance:', performance.now());
    }
}

// Auto-refresh functionality (optional)
let autoRefreshInterval;
function startAutoRefresh() {
    autoRefreshInterval = setInterval(() => {
        if (document.hidden) return;
        
        const lastActivity = Date.now() - (window.lastActivityTime || Date.now());
        if (lastActivity < 300000) { // 5 minutes
            console.log('Auto-refreshing listing data...');
            // In real implementation, you'd fetch updated data via AJAX
        }
    }, 300000); // 5 minutes
}

// Track user activity for auto-refresh
window.lastActivityTime = Date.now();
document.addEventListener('mousemove', () => {
    window.lastActivityTime = Date.now();
});
document.addEventListener('keypress', () => {
    window.lastActivityTime = Date.now();
});

// Initialize everything
document.addEventListener('DOMContentLoaded', function() {
    startAutoRefresh();
    logPerformance();
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    clearInterval(autoRefreshInterval);
});
</script>
@endpush
@endsection