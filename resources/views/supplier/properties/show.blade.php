@extends('layouts.supplier')

@section('title', 'Property Details')

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

/* Status indicators */
.status-active {
    background: linear-gradient(135deg, #10b981, #059669);
    animation: pulse-green 2s infinite;
}

.status-inactive {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.status-featured {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    animation: pulse-yellow 2s infinite;
}

@keyframes pulse-green {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
}

@keyframes pulse-yellow {
    0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
}

/* Edit form styles */
.edit-form {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.edit-form.active {
    max-height: 600px;
}

/* Property image/header */
.property-header {
    background: linear-gradient(135deg, #10b981, #34d399);
    background-size: 400% 400%;
    animation: gradientShift 8s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Loading overlay */
.loading-overlay {
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
}

/* Notification styles */
.notification {
    transform: translateX(100%);
    transition: transform 0.3s ease;
}

.notification.show {
    transform: translateX(0);
}
</style>
@endpush

@section('content')
<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 loading-overlay flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-8 flex items-center space-x-4 shadow-2xl">
        <div class="animate-spin rounded-full h-8 w-8 border-4 border-green-500 border-t-transparent"></div>
        <span class="text-gray-800 font-medium">Processing...</span>
    </div>
</div>

<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <a href="{{ route('supplier.properties.index') }}" class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-6 hover:bg-opacity-30 transition-all">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="flex-1">
                        <h1 class="text-4xl font-bold mb-2">🏞️ Property Details</h1>
                        <p class="text-xl opacity-90">Manage your property listing</p>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <div class="flex space-x-3">
                    @if($property->is_active)
                        <span class="status-active text-white px-4 py-2 rounded-full text-sm font-bold">
                            <i class="fas fa-check-circle mr-1"></i>ACTIVE
                        </span>
                    @else
                        <span class="status-inactive text-white px-4 py-2 rounded-full text-sm font-bold">
                            <i class="fas fa-times-circle mr-1"></i>INACTIVE
                        </span>
                    @endif
                    
                    @if($property->is_featured)
                        <span class="status-featured text-white px-4 py-2 rounded-full text-sm font-bold">
                            <i class="fas fa-star mr-1"></i>FEATURED
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="glass-effect rounded-xl p-6 border-l-4 border-green-500 shadow-xl">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Success!</h4>
                    <p class="text-gray-600">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Property Information -->
            <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-info-circle text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">📋 Listing Information</h2>
                            <p class="text-gray-600">Property listing details and description</p>
                        </div>
                    </div>
                    <button onclick="toggleEdit()" class="bg-gradient-to-r from-yellow-500 to-orange-600 text-white px-6 py-3 rounded-xl hover:from-yellow-600 hover:to-orange-700 transition-all font-medium">
                        <i class="fas fa-edit mr-2"></i>Edit Listing
                    </button>
                </div>

                <!-- Display Mode -->
                <div id="display-mode">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ $property->title }}</h3>
                        <div class="flex items-center space-x-4 text-sm text-gray-600 mb-4">
                            <span><i class="fas fa-calendar mr-1"></i>Created: {{ $property->created_at->format('M d, Y') }}</span>
                            @if($property->published_at)
                                <span><i class="fas fa-globe mr-1"></i>Published: {{ $property->published_at->format('M d, Y') }}</span>
                            @else
                                <span><i class="fas fa-eye-slash mr-1"></i>Not Published</span>
                            @endif
                            <span><i class="fas fa-clock mr-1"></i>Updated: {{ $property->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="prose max-w-none">
                        <div class="bg-white bg-opacity-50 rounded-xl p-6 border border-white border-opacity-30">
                            <h4 class="text-lg font-semibold text-gray-800 mb-3">Description</h4>
                            <p class="text-gray-700 leading-relaxed">{{ $property->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Edit Mode -->
                <div id="edit-mode" class="edit-form">
                    <form action="{{ route('supplier.properties.update', $property) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-bold text-gray-700 mb-3">
                                <i class="fas fa-heading mr-2 text-gray-400"></i>Listing Title *
                            </label>
                            <input type="text" id="title" name="title" 
                                   value="{{ old('title', $property->title) }}" required
                                   class="w-full px-4 py-4 bg-white bg-opacity-50 border border-white border-opacity-30 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-800 font-medium">
                            @error('title')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-3">
                                <i class="fas fa-align-left mr-2 text-gray-400"></i>Description *
                            </label>
                            <textarea id="description" name="description" rows="6" required
                                      class="w-full px-4 py-4 bg-white bg-opacity-50 border border-white border-opacity-30 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-800 font-medium">{{ old('description', $property->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Settings -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white bg-opacity-50 rounded-xl p-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" value="1" 
                                           {{ $property->is_active ? 'checked' : '' }}
                                           class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                                    <span class="ml-3 text-gray-700 font-medium">
                                        <i class="fas fa-eye mr-2 text-green-500"></i>Active (Visible to public)
                                    </span>
                                </label>
                            </div>

                            <div class="bg-white bg-opacity-50 rounded-xl p-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_featured" value="1" 
                                           {{ $property->is_featured ? 'checked' : '' }}
                                           class="w-5 h-5 text-yellow-600 rounded focus:ring-yellow-500">
                                    <span class="ml-3 text-gray-700 font-medium">
                                        <i class="fas fa-star mr-2 text-yellow-500"></i>Featured (Premium placement)
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-6">
                            <button type="button" onclick="toggleEdit()" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all font-medium">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </button>
                            <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-3 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all font-medium">
                                <i class="fas fa-save mr-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Agricultural Land Details -->
            @if($property->terreAgricole)
            <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-seedling text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">🌾 Agricultural Land Details</h2>
                        <p class="text-gray-600">Property specifications and features</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Surface Area -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-ruler-combined text-green-500 mr-3 text-xl"></i>
                            <h4 class="font-bold text-gray-800">Surface Area</h4>
                        </div>
                        <p class="text-3xl font-bold text-green-600 mb-1">{{ number_format($property->terreAgricole->surface, 1) }}</p>
                        <p class="text-sm text-gray-600">hectares</p>
                    </div>

                    <!-- Price -->
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-dollar-sign text-blue-500 mr-3 text-xl"></i>
                            <h4 class="font-bold text-gray-800">Price</h4>
                        </div>
                        <p class="text-3xl font-bold text-blue-600 mb-1">${{ number_format($property->terreAgricole->price) }}</p>
                        <p class="text-sm text-gray-600">${{ number_format($property->terreAgricole->price / $property->terreAgricole->surface) }}/hectare</p>
                    </div>

                    <!-- Status -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-flag text-purple-500 mr-3 text-xl"></i>
                            <h4 class="font-bold text-gray-800">Land Status</h4>
                        </div>
                        <p class="text-xl font-bold text-purple-600 mb-1 capitalize">{{ $property->terreAgricole->status }}</p>
                        <p class="text-sm text-gray-600">Current state</p>
                    </div>

                    <!-- Region -->
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-map-marker-alt text-yellow-500 mr-3 text-xl"></i>
                            <h4 class="font-bold text-gray-800">Location</h4>
                        </div>
                        <p class="text-xl font-bold text-yellow-600 mb-1">{{ $property->terreAgricole->region }}</p>
                        <p class="text-sm text-gray-600">{{ $property->terreAgricole->country }}</p>
                    </div>

                    <!-- Soil Type -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-mountain text-indigo-500 mr-3 text-xl"></i>
                            <h4 class="font-bold text-gray-800">Soil Type</h4>
                        </div>
                        <p class="text-xl font-bold text-indigo-600 mb-1">{{ $property->terreAgricole->soil_type }}</p>
                        <p class="text-sm text-gray-600">Soil composition</p>
                    </div>

                    <!-- GPS Coordinates -->
                    @if($property->terreAgricole->gps_coordinates)
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-6 border border-red-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-crosshairs text-red-500 mr-3 text-xl"></i>
                            <h4 class="font-bold text-gray-800">GPS Location</h4>
                        </div>
                        <p class="text-lg font-bold text-red-600 mb-1">{{ $property->terreAgricole->gps_coordinates }}</p>
                        <p class="text-sm text-gray-600">Coordinates</p>
                    </div>
                    @endif
                </div>

                <!-- Land Description -->
                @if($property->terreAgricole->description)
                <div class="mt-8 p-6 bg-white bg-opacity-50 rounded-xl border border-white border-opacity-30">
                    <h4 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-file-alt text-gray-500 mr-2"></i>Land Description
                    </h4>
                    <p class="text-gray-700 leading-relaxed">{{ $property->terreAgricole->description }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-bolt text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Quick Actions</h3>
                </div>

                <div class="space-y-3">
                    <button onclick="toggleStatus({{ $property->id }})" 
                            class="w-full bg-gradient-to-r from-{{ $property->is_active ? 'red' : 'green' }}-500 to-{{ $property->is_active ? 'pink' : 'emerald' }}-600 text-white py-3 rounded-xl hover:from-{{ $property->is_active ? 'red' : 'green' }}-600 hover:to-{{ $property->is_active ? 'pink' : 'emerald' }}-700 transition-all font-medium">
                        <i class="fas fa-{{ $property->is_active ? 'eye-slash' : 'eye' }} mr-2"></i>
                        {{ $property->is_active ? 'Deactivate' : 'Activate' }} Listing
                    </button>

                    <button onclick="toggleFeatured({{ $property->id }})" 
                            class="w-full bg-gradient-to-r from-{{ $property->is_featured ? 'purple' : 'yellow' }}-500 to-{{ $property->is_featured ? 'purple' : 'orange' }}-600 text-white py-3 rounded-xl hover:from-{{ $property->is_featured ? 'purple' : 'yellow' }}-600 hover:to-{{ $property->is_featured ? 'purple' : 'orange' }}-700 transition-all font-medium">
                        <i class="fas fa-star mr-2"></i>
                        {{ $property->is_featured ? 'Remove Featured' : 'Make Featured' }}
                    </button>

                    <button class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all font-medium">
                        <i class="fas fa-share mr-2"></i>Share Property
                    </button>

                    <button class="w-full bg-gradient-to-r from-purple-500 to-pink-600 text-white py-3 rounded-xl hover:from-purple-600 hover:to-pink-700 transition-all font-medium">
                        <i class="fas fa-chart-bar mr-2"></i>View Analytics
                    </button>
                </div>
            </div>

            <!-- Property Statistics -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-chart-pie text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Property Stats</h3>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-white bg-opacity-50 rounded-lg">
                        <span class="text-gray-600 text-sm font-medium">Views (30 days)</span>
                        <span class="text-gray-800 font-bold">247</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white bg-opacity-50 rounded-lg">
                        <span class="text-gray-600 text-sm font-medium">Inquiries</span>
                        <span class="text-gray-800 font-bold">12</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white bg-opacity-50 rounded-lg">
                        <span class="text-gray-600 text-sm font-medium">Favorites</span>
                        <span class="text-gray-800 font-bold">8</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white bg-opacity-50 rounded-lg">
                        <span class="text-gray-600 text-sm font-medium">Response Rate</span>
                        <span class="text-green-600 font-bold">95%</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Recent Activity</h3>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex items-start p-3 bg-white bg-opacity-50 rounded-lg">
                        <i class="fas fa-eye text-blue-500 mr-3 mt-1"></i>
                        <div>
                            <p class="text-gray-700 font-medium">Property viewed by client</p>
                            <p class="text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start p-3 bg-white bg-opacity-50 rounded-lg">
                        <i class="fas fa-envelope text-green-500 mr-3 mt-1"></i>
                        <div>
                            <p class="text-gray-700 font-medium">New inquiry received</p>
                            <p class="text-gray-500">1 day ago</p>
                        </div>
                    </div>
                    <div class="flex items-start p-3 bg-white bg-opacity-50 rounded-lg">
                        <i class="fas fa-edit text-yellow-500 mr-3 mt-1"></i>
                        <div>
                            <p class="text-gray-700 font-medium">Listing updated</p>
                            <p class="text-gray-500">3 days ago</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover border-2 border-red-200">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-red-600">Danger Zone</h3>
                </div>

                <p class="text-gray-600 text-sm mb-4">
                    Permanently delete this property listing. This action cannot be undone.
                </p>

                <button onclick="deleteProperty({{ $property->id }})" 
                        class="w-full bg-gradient-to-r from-red-500 to-pink-600 text-white py-3 rounded-xl hover:from-red-600 hover:to-pink-700 transition-all font-medium">
                    <i class="fas fa-trash mr-2"></i>Delete Property
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showLoading() {
    document.getElementById('loading-overlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading-overlay').classList.add('hidden');
}

function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

function toggleEdit() {
    const displayMode = document.getElementById('display-mode');
    const editMode = document.getElementById('edit-mode');
    
    if (editMode.classList.contains('active')) {
        editMode.classList.remove('active');
        displayMode.style.display = 'block';
    } else {
        editMode.classList.add('active');
        displayMode.style.display = 'none';
    }
}

async function toggleStatus(propertyId) {
    if (!confirm('Are you sure you want to toggle this property\'s status?')) {
        return;
    }

    showLoading();
    
    try {
        const response = await fetch(`/supplier/properties/${propertyId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Error updating property status', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Network error. Please try again.', 'error');
    } finally {
        hideLoading();
    }
}

async function toggleFeatured(propertyId) {
    if (!confirm('Are you sure you want to toggle this property\'s featured status?')) {
        return;
    }

    showLoading();
    
    try {
        const response = await fetch(`/supplier/properties/${propertyId}/toggle-featured`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Error updating featured status', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Network error. Please try again.', 'error');
    } finally {
        hideLoading();
    }
}

async function deleteProperty(propertyId) {
    const confirmMessage = `⚠️ Are you sure you want to delete this property listing?

This action cannot be undone and will permanently remove:
• The property listing
• All associated data
• All inquiries

This is a permanent action!`;

    if (!confirm(confirmMessage)) {
        return;
    }

    const confirmation = prompt('Please type "DELETE" to confirm deletion:');
    if (confirmation !== 'DELETE') {
        if (confirmation !== null) {
            showNotification('Deletion cancelled - confirmation text did not match', 'error');
        }
        return;
    }

    showLoading();
    
    try {
        const response = await fetch(`/supplier/properties/${propertyId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message || 'Property deleted successfully', 'success');
            setTimeout(() => {
                window.location.href = '/supplier/properties';
            }, 1500);
        } else {
            showNotification(data.message || 'Error deleting property', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Network error. Please try again.', 'error');
    } finally {
        hideLoading();
    }
}

function showNotification(message, type = 'success') {
    // Remove any existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());

    const notification = document.createElement('div');
    notification.className = `notification ${type} show fixed top-4 right-4 z-50 min-w-80 p-4 rounded-xl text-white font-medium shadow-lg`;
    
    if (type === 'success') {
        notification.style.background = 'linear-gradient(135deg, #10b981, #059669)';
    } else {
        notification.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
    }
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} mr-3"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white opacity-70 hover:opacity-100">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

// Auto-hide success messages from server
document.addEventListener('DOMContentLoaded', function() {
    const successMessages = document.querySelectorAll('.alert-success');
    successMessages.forEach(function(message) {
        setTimeout(function() {
            message.style.opacity = '0';
            setTimeout(function() {
                message.remove();
            }, 300);
        }, 5000);
    });

    // Add loading states to form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            showLoading();
        });
    });
});

// Handle browser back/forward buttons
window.addEventListener('pageshow', function(event) {
    hideLoading();
});

// Add error handling for network issues
window.addEventListener('online', function() {
    showNotification('Connection restored', 'success');
});

window.addEventListener('offline', function() {
    showNotification('Connection lost. Some features may not work.', 'error');
});
</script>
@endpush