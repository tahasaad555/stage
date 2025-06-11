@extends('layouts.supplier')

@section('title', 'My Properties')

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

/* Property card hover effects */
.property-card {
    transition: all 0.3s ease;
}

.property-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.property-image {
    background: linear-gradient(45deg, #10b981, #34d399);
    background-size: 400% 400%;
    animation: gradientShift 6s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
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
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mr-6">
                        <i class="fas fa-seedling text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">🌾 My Properties</h1>
                        <p class="text-xl opacity-90">Manage your agricultural property listings</p>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg opacity-75">Total Portfolio</div>
                <div class="text-3xl font-bold">${{ number_format($stats['total_value'] / 1000000, 1) }}M</div>
                <div class="text-sm opacity-75">{{ number_format($stats['total_surface'], 1) }} hectares</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-list-alt text-white text-xl"></i>
                </div>
                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium">Total</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['total'] }}</h3>
            <p class="text-gray-600 font-medium">Total Properties</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium">Active</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['active'] }}</h3>
            <p class="text-gray-600 font-medium">Active Listings</p>
            <div class="mt-3 flex items-center text-sm">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full" 
                         style="width: {{ $stats['total'] > 0 ? ($stats['active'] / $stats['total']) * 100 : 0 }}%"></div>
                </div>
                <span class="text-blue-600 font-medium ml-2">{{ $stats['total'] > 0 ? round(($stats['active'] / $stats['total']) * 100) : 0 }}%</span>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-star text-white text-xl"></i>
                </div>
                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium">Featured</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['featured'] }}</h3>
            <p class="text-gray-600 font-medium">Featured Listings</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-area text-white text-xl"></i>
                </div>
                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium">Surface</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['total_surface'], 1) }}</h3>
            <p class="text-gray-600 font-medium">Total Hectares</p>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="glass-effect rounded-2xl p-6 shadow-xl">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>Search Properties
                </label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by title, region..."
                       class="w-full px-4 py-3 bg-white bg-opacity-50 border border-white border-opacity-30 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i>Status
                </label>
                <select name="status" class="w-full px-4 py-3 bg-white bg-opacity-50 border border-white border-opacity-30 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Featured Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-star mr-1"></i>Featured
                </label>
                <select name="featured" class="w-full px-4 py-3 bg-white bg-opacity-50 border border-white border-opacity-30 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">All Properties</option>
                    <option value="yes" {{ request('featured') === 'yes' ? 'selected' : '' }}>Featured Only</option>
                    <option value="no" {{ request('featured') === 'no' ? 'selected' : '' }}>Not Featured</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all font-medium">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('supplier.properties.index') }}" class="px-4 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
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

    <!-- Properties Grid -->
    @if($properties->isEmpty())
        <div class="glass-effect rounded-2xl p-16 shadow-xl text-center">
            <div class="w-32 h-32 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-8">
                <i class="fas fa-seedling text-6xl text-gray-400"></i>
            </div>
            <h3 class="text-3xl font-bold text-gray-600 mb-4">No Properties Found</h3>
            <p class="text-gray-500 mb-8 text-lg max-w-md mx-auto">
                @if(request()->hasAny(['search', 'status', 'featured']))
                    No properties match your current filters. Try adjusting your search criteria.
                @else
                    You haven't listed any properties yet. Start building your agricultural portfolio today!
                @endif
            </p>
            @if(!request()->hasAny(['search', 'status', 'featured']))
                <button class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all font-medium text-lg">
                    <i class="fas fa-plus mr-3"></i>Add Your First Property
                </button>
            @else
                <a href="{{ route('supplier.properties.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-8 py-4 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all font-medium text-lg">
                    <i class="fas fa-times mr-3"></i>Clear Filters
                </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($properties as $property)
                <div class="property-card glass-effect rounded-2xl overflow-hidden shadow-xl">
                    <!-- Property Image/Header -->
                    <div class="property-image h-48 flex items-center justify-center relative">
                        <div class="absolute top-4 left-4 flex space-x-2">
                            @if($property->is_active)
                                <span class="status-active text-white px-3 py-1 rounded-full text-xs font-bold">ACTIVE</span>
                            @else
                                <span class="status-inactive text-white px-3 py-1 rounded-full text-xs font-bold">INACTIVE</span>
                            @endif
                            
                            @if($property->is_featured)
                                <span class="status-featured text-white px-3 py-1 rounded-full text-xs font-bold">
                                    <i class="fas fa-star mr-1"></i>FEATURED
                                </span>
                            @endif
                        </div>
                        
                        <div class="text-center text-white">
                            <i class="fas fa-seedling text-6xl mb-3 opacity-80"></i>
                            <h4 class="text-xl font-bold">Agricultural Land</h4>
                        </div>
                        
                        <div class="absolute top-4 right-4">
                            <div class="flex space-x-1">
                                <button onclick="toggleStatus({{ $property->id }})" 
                                        class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-all"
                                        title="Toggle Status">
                                    <i class="fas fa-power-off text-white text-sm"></i>
                                </button>
                                <button onclick="toggleFeatured({{ $property->id }})" 
                                        class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-all"
                                        title="Toggle Featured">
                                    <i class="fas fa-star text-white text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Property Content -->
                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ $property->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-3">{{ $property->description }}</p>
                        </div>
                        
                        @if($property->terreAgricole)
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="text-center p-3 bg-white bg-opacity-50 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">{{ number_format($property->terreAgricole->surface, 1) }}</div>
                                    <div class="text-xs text-gray-600">Hectares</div>
                                </div>
                                <div class="text-center p-3 bg-white bg-opacity-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">${{ number_format($property->terreAgricole->price / 1000) }}K</div>
                                    <div class="text-xs text-gray-600">Price</div>
                                </div>
                                <div class="col-span-2 text-center p-3 bg-white bg-opacity-50 rounded-lg">
                                    <div class="font-semibold text-gray-800">{{ $property->terreAgricole->region }}</div>
                                    <div class="text-xs text-gray-600">Region</div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('supplier.properties.show', $property) }}" 
                               class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-center py-3 rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all font-medium">
                                <i class="fas fa-eye mr-2"></i>View Details
                            </a>
                            <button onclick="editProperty({{ $property->id }})" 
                                    class="px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white rounded-xl hover:from-yellow-600 hover:to-orange-700 transition-all">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteProperty({{ $property->id }})" 
                                    class="px-4 py-3 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-xl hover:from-red-600 hover:to-pink-700 transition-all">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-white border-opacity-30 text-xs text-gray-500">
                            <div class="flex justify-between">
                                <span>Created: {{ $property->created_at->format('M d, Y') }}</span>
                                @if($property->published_at)
                                    <span>Published: {{ $property->published_at->format('M d, Y') }}</span>
                                @else
                                    <span>Not Published</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($properties->hasPages())
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                {{ $properties->appends(request()->query())->links() }}
            </div>
        @endif
    @endif
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 text-center">
        <div class="w-16 h-16 border-4 border-green-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-gray-600 font-medium">Processing...</p>
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

function toggleStatus(propertyId) {
    showLoading();
    
    fetch(`/supplier/properties/${propertyId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification(data.message, 'success');
            location.reload();
        } else {
            showNotification('Error updating property status', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Error updating property status', 'error');
    });
}

function toggleFeatured(propertyId) {
    showLoading();
    
    fetch(`/supplier/properties/${propertyId}/toggle-featured`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification(data.message, 'success');
            location.reload();
        } else {
            showNotification('Error updating featured status', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Error updating featured status', 'error');
    });
}

function editProperty(propertyId) {
    // For now, redirect to show page - can be enhanced with modal
    window.location.href = `/supplier/properties/${propertyId}`;
}

function deleteProperty(propertyId) {
    if (confirm('Are you sure you want to delete this property listing? This action cannot be undone.')) {
        showLoading();
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/supplier/properties/${propertyId}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type} show fixed top-4 right-4 z-50 min-w-80 p-4 rounded-xl text-white font-medium shadow-lg`;
    
    if (type === 'success') {
        notification.style.background = 'linear-gradient(135deg, #10b981, #059669)';
    } else {
        notification.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
    }
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} mr-3"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white opacity-70 hover:opacity-100">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Auto-hide success messages
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
});
</script>
@endpush