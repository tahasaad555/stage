@extends('layouts.admin')

@section('title', 'Listing Details')

@push('styles')
<style>
.glass-effect {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
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

/* Status indicator animations */
.status-active {
    background: linear-gradient(135deg, #10b981, #059669);
    animation: pulse-green 2s infinite;
}

.status-inactive {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.status-featured {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    animation: pulse-purple 2s infinite;
}

@keyframes pulse-green {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
}

@keyframes pulse-purple {
    0%, 100% { box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(139, 92, 246, 0); }
}

/* Notification styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    padding: 16px;
    border-radius: 12px;
    color: white;
    font-weight: 500;
    transform: translateX(100%);
    transition: transform 0.3s ease-in-out;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.notification.show {
    transform: translateX(0);
}

.notification.success {
    background: linear-gradient(135deg, #10b981, #059669);
}

.notification.error {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}
</style>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.listings.index') }}" 
                   class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-6 hover:bg-opacity-30 transition-all">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div class="flex items-center space-x-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-400 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clipboard-list text-white text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">{{ $listing->title }}</h1>
                        <p class="text-xl opacity-90 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Listing Details & Management
                        </p>
                        <div class="flex items-center space-x-4 mt-2">
                            @if($listing->is_featured)
                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            @endif
                            <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $listing->is_active ? '✅ Active' : '❌ Inactive' }}
                            </span>
                            @if($listing->published_at)
                                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-semibold">
                                    📅 Published {{ $listing->published_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-eye text-6xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Listing Information -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Listing Details Card -->
            <div class="glass-effect rounded-2xl p-8 shadow-xl">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-clipboard-list text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Listing Information</h3>
                        <p class="text-gray-600">Core details and metadata</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <i class="fas fa-heading text-emerald-500 mr-4 text-lg"></i>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Title</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $listing->title }}</dd>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <i class="fas fa-toggle-on text-green-500 mr-4 text-lg"></i>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="text-lg font-semibold">
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $listing->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $listing->is_active ? '✅ Active' : '❌ Inactive' }}
                                    </span>
                                </dd>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <i class="fas fa-star text-purple-500 mr-4 text-lg"></i>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Featured Status</dt>
                                <dd class="text-lg font-semibold">
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $listing->is_featured ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $listing->is_featured ? '⭐ Featured' : '📋 Standard' }}
                                    </span>
                                </dd>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <i class="fas fa-calendar text-blue-500 mr-4 text-lg"></i>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Published Date</dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    {{ $listing->published_at ? $listing->published_at->format('M d, Y H:i') : 'Not published' }}
                                </dd>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <i class="fas fa-plus text-green-500 mr-4 text-lg"></i>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $listing->created_at->format('M d, Y H:i') }}</dd>
                                <p class="text-sm text-gray-500">{{ $listing->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <i class="fas fa-edit text-yellow-500 mr-4 text-lg"></i>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $listing->updated_at->format('M d, Y H:i') }}</dd>
                                <p class="text-sm text-gray-500">{{ $listing->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="mt-8 p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl border border-blue-200">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-align-left text-blue-500 mr-2"></i>Description
                    </h4>
                    <p class="text-gray-700 leading-relaxed">{{ $listing->description }}</p>
                </div>
            </div>

            <!-- Agricultural Land Information -->
            @if($listing->terreAgricole)
            <div class="glass-effect rounded-2xl p-8 shadow-xl">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-seedling text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Agricultural Land Details</h3>
                        <p class="text-gray-600">Property specifications and location</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                            <h4 class="font-semibold text-gray-800">Land Title</h4>
                        </div>
                        <a href="{{ route('admin.lands.show', $listing->terreAgricole) }}" 
                           class="text-green-600 hover:text-green-800 font-semibold">
                            {{ $listing->terreAgricole->title }}
                        </a>
                    </div>

                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-ruler-combined text-blue-500 mr-2"></i>
                            <h4 class="font-semibold text-gray-800">Surface Area</h4>
                        </div>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($listing->terreAgricole->surface, 1) }}</p>
                        <p class="text-sm text-gray-600">hectares</p>
                    </div>

                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-dollar-sign text-yellow-500 mr-2"></i>
                            <h4 class="font-semibold text-gray-800">Price</h4>
                        </div>
                        <p class="text-2xl font-bold text-green-600">${{ number_format($listing->terreAgricole->price) }}</p>
                        <p class="text-sm text-gray-600">${{ number_format($listing->terreAgricole->price / max($listing->terreAgricole->surface, 1), 0) }}/ha</p>
                    </div>

                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-globe text-purple-500 mr-2"></i>
                            <h4 class="font-semibold text-gray-800">Location</h4>
                        </div>
                        <p class="font-semibold text-gray-800">{{ $listing->terreAgricole->region }}</p>
                        <p class="text-sm text-gray-600">🇲🇦 {{ $listing->terreAgricole->country }}</p>
                    </div>

                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-toggle-on text-indigo-500 mr-2"></i>
                            <h4 class="font-semibold text-gray-800">Land Status</h4>
                        </div>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                            @if($listing->terreAgricole->status === 'available') bg-green-100 text-green-800
                            @elseif($listing->terreAgricole->status === 'sold') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($listing->terreAgricole->status) }}
                        </span>
                    </div>

                    @if($listing->terreAgricole->soil_type)
                    <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl p-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-mountain text-amber-500 mr-2"></i>
                            <h4 class="font-semibold text-gray-800">Soil Type</h4>
                        </div>
                        <p class="font-semibold text-gray-800">{{ $listing->terreAgricole->soil_type }}</p>
                    </div>
                    @endif
                </div>

                @if($listing->terreAgricole->description)
                <div class="mt-6 p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-info-circle text-green-500 mr-2"></i>Land Description
                    </h4>
                    <p class="text-gray-700 leading-relaxed">{{ $listing->terreAgricole->description }}</p>
                </div>
                @endif

                @if($listing->terreAgricole->gps_coordinates)
                <div class="mt-6 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-map-pin text-blue-500 mr-2"></i>GPS Coordinates
                    </h4>
                    <p class="font-mono text-lg bg-white px-4 py-2 rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors" 
                       onclick="copyToClipboard('{{ $listing->terreAgricole->gps_coordinates }}')"
                       title="Click to copy coordinates">
                        {{ $listing->terreAgricole->gps_coordinates }}
                        <i class="fas fa-copy ml-2 text-gray-400"></i>
                    </p>
                </div>
                @endif
            </div>
            @else
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="text-center py-12">
                        <i class="fas fa-exclamation-triangle text-6xl text-red-500 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Agricultural Land Not Found</h3>
                        <p class="text-red-500">The associated agricultural land for this listing is not available</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Supplier Information -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-building text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Supplier Info</h4>
                </div>
                
                @if($listing->fournisseur && $listing->fournisseur->user)
                    <div class="space-y-4">
                        <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-white font-bold text-lg">
                                    {{ substr($listing->fournisseur->user->first_name, 0, 1) }}{{ substr($listing->fournisseur->user->last_name, 0, 1) }}
                                </span>
                            </div>
                            <h5 class="font-bold text-gray-800">{{ $listing->fournisseur->user->full_name }}</h5>
                            <p class="text-sm text-gray-600">{{ $listing->fournisseur->company_name }}</p>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-envelope text-blue-500 mr-3"></i>
                                <div>
                                    <span class="text-gray-600">Email:</span>
                                    <a href="mailto:{{ $listing->fournisseur->user->email }}" class="text-blue-600 hover:text-blue-800 ml-1">
                                        {{ $listing->fournisseur->user->email }}
                                    </a>
                                </div>
                            </div>
                            
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-phone text-green-500 mr-3"></i>
                                <div>
                                    <span class="text-gray-600">Phone:</span>
                                    <span class="text-gray-800 ml-1">{{ $listing->fournisseur->user->phone ?: 'Not provided' }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-certificate text-purple-500 mr-3"></i>
                                <div>
                                    <span class="text-gray-600">Registration:</span>
                                    <span class="text-gray-800 ml-1 font-mono">{{ $listing->fournisseur->business_registration }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-map-marker-alt text-red-500 mr-3 mt-1"></i>
                                <div>
                                    <span class="text-gray-600">Address:</span>
                                    <p class="text-gray-800 ml-1">{{ $listing->fournisseur->address }}</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('admin.users.show', $listing->fournisseur->user) }}" 
                           class="block w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-center py-3 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all">
                            <i class="fas fa-user mr-2"></i>View Supplier Profile
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-3"></i>
                        <p class="text-red-500 font-semibold">Supplier information not available</p>
                    </div>
                @endif
            </div>

            <!-- Performance Metrics -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Performance</h4>
                </div>
                
                <div class="space-y-4">
                    <div class="text-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                        <div class="text-3xl font-bold text-green-600">{{ (int) $listing->created_at->diffInDays(now()) }}</div>
                        <div class="text-sm text-gray-600">Days Listed</div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <div class="text-lg font-bold text-blue-600">
                                {{ $listing->is_active ? '✅' : '❌' }}
                            </div>
                            <div class="text-xs text-gray-600">Status</div>
                        </div>
                        
                        <div class="text-center p-3 bg-purple-50 rounded-lg">
                            <div class="text-lg font-bold text-purple-600">
                                {{ $listing->is_featured ? '⭐' : '📋' }}
                            </div>
                            <div class="text-xs text-gray-600">Featured</div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-4">
                        <h5 class="font-semibold text-gray-800 mb-3">Listing Health</h5>
                        <div class="space-y-2">
                            @php
                                $completeness = 60; // Base score
                                if($listing->description) $completeness += 15;
                                if($listing->terreAgricole && $listing->terreAgricole->gps_coordinates) $completeness += 10;
                                if($listing->terreAgricole && $listing->terreAgricole->soil_type) $completeness += 10;
                                if($listing->is_active) $completeness += 5;
                                $completeness = min($completeness, 100);
                            @endphp
                            <div class="flex justify-between text-sm">
                                <span>Completeness</span>
                                <span class="font-semibold">{{ $completeness }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full" style="width: {{ $completeness }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-cogs text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Quick Actions</h4>
                </div>
                
                <div class="space-y-3">
                    <button onclick="toggleStatus()" 
                            class="w-full bg-gradient-to-r from-{{ $listing->is_active ? 'red' : 'green' }}-500 to-{{ $listing->is_active ? 'red' : 'green' }}-600 text-white py-3 rounded-lg hover:from-{{ $listing->is_active ? 'red' : 'green' }}-600 hover:to-{{ $listing->is_active ? 'red' : 'green' }}-700 transition-all flex items-center justify-center">
                        <i class="fas fa-toggle-{{ $listing->is_active ? 'off' : 'on' }} mr-2"></i>
                        {{ $listing->is_active ? 'Deactivate' : 'Activate' }} Listing
                    </button>
                    
                    <button onclick="toggleFeatured()" 
                            class="w-full bg-gradient-to-r from-{{ $listing->is_featured ? 'purple' : 'yellow' }}-500 to-{{ $listing->is_featured ? 'purple' : 'orange' }}-600 text-white py-3 rounded-lg hover:from-{{ $listing->is_featured ? 'purple' : 'yellow' }}-600 hover:to-{{ $listing->is_featured ? 'purple' : 'orange' }}-700 transition-all flex items-center justify-center">
                        <i class="fas fa-star mr-2"></i>
                        {{ $listing->is_featured ? 'Remove Featured' : 'Make Featured' }}
                    </button>
                    
                    <a href="{{ route('admin.listings.edit', $listing) }}" 
                       class="block w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-center py-3 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all">
                        <i class="fas fa-edit mr-2"></i>Edit Listing
                    </a>
                    
                    <button onclick="deleteListing({{ $listing->id }})" 
                            class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white py-3 rounded-lg hover:from-red-600 hover:to-red-700 transition-all flex items-center justify-center">
                        <i class="fas fa-trash mr-2"></i>Delete Listing
                    </button>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Recent Activity</h4>
                </div>
                
                <div class="space-y-3 text-sm">
                    <div class="border-l-4 border-blue-400 pl-3">
                        <div class="font-semibold text-blue-700">Listing Updated</div>
                        <div class="text-gray-600">{{ $listing->updated_at->diffForHumans() }}</div>
                    </div>
                    
                    @if($listing->published_at)
                    <div class="border-l-4 border-green-400 pl-3">
                        <div class="font-semibold text-green-700">Published</div>
                        <div class="text-gray-600">{{ $listing->published_at->diffForHumans() }}</div>
                    </div>
                    @endif
                    
                    <div class="border-l-4 border-gray-400 pl-3">
                        <div class="font-semibold text-gray-700">Listing Created</div>
                        <div class="text-gray-600">{{ $listing->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>

            <!-- SEO & Visibility -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-search text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Visibility</h4>
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-eye text-green-500 mr-2"></i>
                            <span class="text-sm font-medium">Public Visibility</span>
                        </div>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $listing->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $listing->is_active ? 'Visible' : 'Hidden' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-star text-purple-500 mr-2"></i>
                            <span class="text-sm font-medium">Featured Status</span>
                        </div>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $listing->is_featured ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $listing->is_featured ? 'Featured' : 'Standard' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-calendar text-blue-500 mr-2"></i>
                            <span class="text-sm font-medium">Publication</span>
                        </div>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $listing->published_at ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $listing->published_at ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Set CSRF token
window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

// Toggle listing status
async function toggleStatus() {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/listings/{{ $listing->id }}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(result.message, 'error');
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Toggle featured status
async function toggleFeatured() {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/listings/{{ $listing->id }}/toggle-featured`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(result.message, 'error');
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Delete listing
async function deleteListing(listingId) {
    if (!confirm('Are you sure you want to delete this listing? This action cannot be undone.')) return;
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Deleting...';
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
            showNotification(result.message, 'success');
            setTimeout(() => window.location.href = '{{ route("admin.listings.index") }}', 1000);
        } else {
            showNotification(result.message, 'error');
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('GPS coordinates copied to clipboard!', 'success');
    }).catch(err => {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('GPS coordinates copied to clipboard!', 'success');
    });
}

// Show notification function
function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
    
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                <i class="${icon}"></i>
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
    
    document.body.appendChild(notification);
    
    // Show notification with animation
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

// Enhanced loading states and error handling
function setLoading(element, isLoading) {
    if (isLoading) {
        element.classList.add('loading');
        element.setAttribute('disabled', 'disabled');
    } else {
        element.classList.remove('loading');
        element.removeAttribute('disabled');
    }
}

// Animate cards on load
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.glass-effect');
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
    // Ctrl/Cmd + E to edit listing
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
        e.preventDefault();
        window.location.href = '{{ route("admin.listings.edit", $listing) }}';
    }
    
    // Ctrl/Cmd + Backspace to go back
    if ((e.ctrlKey || e.metaKey) && e.key === 'Backspace') {
        e.preventDefault();
        window.location.href = '{{ route("admin.listings.index") }}';
    }
    
    // T to toggle status
    if (e.key === 't' && !e.ctrlKey && !e.metaKey && !e.target.matches('input, textarea, select')) {
        e.preventDefault();
        toggleStatus();
    }
    
    // F to toggle featured
    if (e.key === 'f' && !e.ctrlKey && !e.metaKey && !e.target.matches('input, textarea, select')) {
        e.preventDefault();
        toggleFeatured();
    }
    
    // Delete key to delete listing
    if (e.key === 'Delete' && (e.ctrlKey || e.metaKey) && !e.target.matches('input, textarea, select')) {
        e.preventDefault();
        deleteListing({{ $listing->id }});
    }
});

// Auto-refresh listing status every 30 seconds
let autoRefreshInterval;
function startAutoRefresh() {
    autoRefreshInterval = setInterval(() => {
        if (document.hidden) return;
        
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Update status indicators if changed
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Check for status changes and update if needed
            const currentStatus = document.querySelector('.status-active, .status-inactive');
            const newStatusElements = doc.querySelectorAll('.status-active, .status-inactive');
            
            if (newStatusElements.length > 0 && currentStatus) {
                const newStatus = newStatusElements[0].textContent;
                const currentStatusText = currentStatus.textContent;
                
                if (newStatus !== currentStatusText) {
                    showNotification('Listing status has been updated', 'success');
                    setTimeout(() => location.reload(), 1000);
                }
            }
        })
        .catch(error => {
            console.warn('Auto-refresh failed:', error);
        });
    }, 30000);
}

// Start auto-refresh
document.addEventListener('DOMContentLoaded', startAutoRefresh);

// Stop auto-refresh when page is hidden
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        clearInterval(autoRefreshInterval);
    } else {
        startAutoRefresh();
    }
});

// Cleanup when page unloads
window.addEventListener('beforeunload', function() {
    clearInterval(autoRefreshInterval);
});

// Print listing details function
function printListingDetails() {
    const printWindow = window.open('', '_blank');
    const listingContent = document.querySelector('.space-y-8').innerHTML;
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Listing Details - {{ $listing->title }}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .glass-effect { background: #f9f9f9; border: 1px solid #ddd; padding: 20px; margin: 10px 0; }
                    h1, h2, h3 { color: #333; }
                    .hidden { display: none; }
                    button { display: none; }
                    a[href*="javascript"] { display: none; }
                    .no-print { display: none; }
                </style>
            </head>
            <body>
                <h1>Listing Details Report</h1>
                <p><strong>Listing:</strong> {{ $listing->title }}</p>
                <p><strong>Generated on:</strong> ${new Date().toLocaleString()}</p>
                <hr>
                ${listingContent}
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
        printListingDetails();
    }
});

// Enhanced tooltips for action buttons
document.addEventListener('DOMContentLoaded', function() {
    const actionButtons = document.querySelectorAll('[onclick]');
    actionButtons.forEach(button => {
        if (button.textContent.includes('Activate') || button.textContent.includes('Deactivate')) {
            button.setAttribute('title', 'Toggle listing visibility (T)');
        } else if (button.textContent.includes('Featured')) {
            button.setAttribute('title', 'Toggle featured status (F)');
        } else if (button.textContent.includes('Delete')) {
            button.setAttribute('title', 'Delete listing (Ctrl+Del)');
        }
    });
});

// Track user activity for analytics
let activityLog = [];
function logActivity(action, details = {}) {
    activityLog.push({
        action: action,
        details: details,
        timestamp: new Date().toISOString(),
        url: window.location.href
    });
    
    // Optionally send to analytics endpoint
    // fetch('/admin/analytics/activity', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': window.csrfToken
    //     },
    //     body: JSON.stringify(activityLog.slice(-1)[0])
    // });
}

// Log page view
document.addEventListener('DOMContentLoaded', function() {
    logActivity('listing_view', {
        listing_id: {{ $listing->id }},
        listing_title: '{{ addslashes($listing->title) }}'
    });
});

// Add click tracking to important elements
document.addEventListener('click', function(e) {
    if (e.target.closest('a[href*="admin.lands.show"]')) {
        logActivity('view_land_details', { listing_id: {{ $listing->id }} });
    } else if (e.target.closest('a[href*="admin.users.show"]')) {
        logActivity('view_supplier_profile', { listing_id: {{ $listing->id }} });
    } else if (e.target.closest('a[href*="edit"]')) {
        logActivity('edit_listing_click', { listing_id: {{ $listing->id }} });
    }
});

// Performance monitoring
const performanceData = {
    loadTime: window.performance.timing.loadEventEnd - window.performance.timing.navigationStart,
    domReady: window.performance.timing.domContentLoadedEventEnd - window.performance.timing.navigationStart,
    firstPaint: 0
};

// Capture first paint if available
if ('getEntriesByType' in performance) {
    const paintEntries = performance.getEntriesByType('paint');
    const firstPaint = paintEntries.find(entry => entry.name === 'first-paint');
    if (firstPaint) {
        performanceData.firstPaint = firstPaint.startTime;
    }
}

// Log performance data
setTimeout(() => {
    logActivity('page_performance', performanceData);
}, 1000);

// Add search functionality for the page content
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        // Let default browser search work, but we could enhance this
        // with custom in-page search if needed
    }
});

// Status change confirmations with detailed info
const originalToggleStatus = toggleStatus;
toggleStatus = function() {
    const currentStatus = {{ $listing->is_active ? 'true' : 'false' }};
    const action = currentStatus ? 'deactivate' : 'activate';
    const message = currentStatus ? 
        'This will hide the listing from public view. Suppliers will not be able to see this listing.' :
        'This will make the listing visible to the public. Suppliers will be able to see and interact with this listing.';
    
    if (confirm(`Are you sure you want to ${action} this listing?\n\n${message}`)) {
        originalToggleStatus.call(this);
    }
};

const originalToggleFeatured = toggleFeatured;
toggleFeatured = function() {
    const currentFeatured = {{ $listing->is_featured ? 'true' : 'false' }};
    const action = currentFeatured ? 'remove featured status from' : 'make featured';
    const message = currentFeatured ? 
        'This listing will no longer appear in featured sections and will have standard visibility.' :
        'This listing will appear in featured sections and have enhanced visibility.';
    
    if (confirm(`Are you sure you want to ${action} this listing?\n\n${message}`)) {
        originalToggleFeatured.call(this);
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Focus management for accessibility
    const main = document.querySelector('main') || document.body;
    main.setAttribute('tabindex', '-1');
    
    // Announce page load to screen readers
    const announcement = document.createElement('div');
    announcement.setAttribute('aria-live', 'polite');
    announcement.setAttribute('aria-atomic', 'true');
    announcement.className = 'sr-only';
    announcement.textContent = 'Listing details page loaded successfully';
    document.body.appendChild(announcement);
    
    setTimeout(() => announcement.remove(), 1000);
});
</script>
@endpush
@endsection