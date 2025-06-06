@extends('layouts.admin')

@section('title', 'Edit Listing')

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <a href="{{ route('admin.listings.show', $listing) }}" class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4 hover:bg-opacity-30 transition-all">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-edit text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold mb-2">✏️ Edit Listing</h1>
                            <p class="text-xl opacity-90">Modify listing: {{ $listing->title }}</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-clipboard-edit mr-2"></i>
                        <span>Listing Modification</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <span>Secure Update</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-history mr-2"></i>
                        <span>Change Tracking</span>
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

    <!-- Listing Edit Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.listings.update', $listing) }}" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Basic Information Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-clipboard-list text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Listing Information</h3>
                            <p class="text-gray-600">Update listing details and metadata</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Supplier -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-building mr-2 text-blue-500"></i>Supplier *
                            </label>
                            <select name="fournisseur_id" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('fournisseur_id') border-red-300 ring-red-100 @enderror">
                                <option value="">Select a supplier...</option>
                                @foreach($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id', $listing->fournisseur_id) == $fournisseur->id ? 'selected' : '' }}>
                                        🏢 {{ $fournisseur->user->full_name }} - {{ $fournisseur->company_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fournisseur_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Agricultural Land -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-seedling mr-2 text-green-500"></i>Agricultural Land *
                            </label>
                            <select name="terre_agricole_id" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('terre_agricole_id') border-red-300 ring-red-100 @enderror">
                                <option value="">Select agricultural land...</option>
                                @foreach($lands as $land)
                                    <option value="{{ $land->id }}" {{ old('terre_agricole_id', $listing->terre_agricole_id) == $land->id ? 'selected' : '' }}>
                                        🌾 {{ $land->title }} - {{ $land->region }} ({{ number_format($land->surface, 1) }} ha - ${{ number_format($land->price) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('terre_agricole_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading mr-2 text-purple-500"></i>Listing Title *
                        </label>
                        <input type="text" name="title" value="{{ old('title', $listing->title) }}" required
                               placeholder="e.g., Prime Agricultural Land in Marrakech Region"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('title') border-red-300 ring-red-100 @enderror">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-indigo-500"></i>Description *
                        </label>
                        <textarea name="description" rows="6" required
                                  placeholder="Provide detailed description of the land, its features, location benefits, soil quality, accessibility, and any unique selling points..."
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none @error('description') border-red-300 ring-red-100 @enderror">{{ old('description', $listing->description) }}</textarea>
                        <div class="mt-2 text-xs text-gray-600">
                            <div class="flex items-center space-x-4">
                                <span id="char-count">{{ strlen($listing->description) }} characters</span>
                                <span>• Recommended: 200-500 characters</span>
                                <span>• Include key features and benefits</span>
                            </div>
                        </div>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status & Visibility Settings Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-toggle-on text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Status & Visibility</h3>
                            <p class="text-gray-600">Control listing visibility and features</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Active Status -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $listing->is_active) ? 'checked' : '' }}
                                       class="w-5 h-5 text-green-600 border-2 border-gray-300 rounded focus:ring-green-500 focus:ring-2 mt-1">
                                <div>
                                    <span class="text-lg font-semibold text-gray-800 flex items-center">
                                        <i class="fas fa-eye mr-2 text-green-500"></i>Active Listing
                                    </span>
                                    <p class="text-sm text-gray-600 mt-1">Make this listing visible to clients and searchable on the platform</p>
                                    <div class="mt-2 text-xs text-green-700 bg-green-100 px-2 py-1 rounded-full inline-block">
                                        ✅ Recommended for public listings
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Featured Status -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200">
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $listing->is_featured) ? 'checked' : '' }}
                                       class="w-5 h-5 text-purple-600 border-2 border-gray-300 rounded focus:ring-purple-500 focus:ring-2 mt-1">
                                <div>
                                    <span class="text-lg font-semibold text-gray-800 flex items-center">
                                        <i class="fas fa-star mr-2 text-purple-500"></i>Featured Listing
                                    </span>
                                    <p class="text-sm text-gray-600 mt-1">Highlight this listing prominently in search results and homepage</p>
                                    <div class="mt-2 text-xs text-purple-700 bg-purple-100 px-2 py-1 rounded-full inline-block">
                                        ⭐ Premium visibility boost
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Published Date -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-blue-500"></i>Published Date
                        </label>
                        <input type="datetime-local" name="published_at" 
                               value="{{ old('published_at', $listing->published_at ? $listing->published_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('published_at') border-red-300 ring-red-100 @enderror">
                        <div class="mt-2 text-xs text-gray-600 bg-blue-50 px-3 py-2 rounded-lg">
                            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                            Leave empty to auto-set when listing is activated. This controls when the listing becomes publicly visible.
                        </div>
                        @error('published_at')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.listings.show', $listing) }}" 
                       class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl hover:bg-gray-300 transition-all flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Cancel & Go Back
                    </a>
                    
                    <div class="flex items-center space-x-4">
                        <button type="button" onclick="resetForm()" 
                                class="bg-yellow-500 text-white px-6 py-3 rounded-xl hover:bg-yellow-600 transition-all flex items-center">
                            <i class="fas fa-undo mr-2"></i>Reset Changes
                        </button>
                        <button type="submit" 
                                class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-8 py-3 rounded-xl hover:from-emerald-600 hover:to-teal-700 transition-all flex items-center shadow-lg">
                            <i class="fas fa-save mr-2"></i>Update Listing
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar - Instructions & Current Info -->
        <div class="space-y-6">
            <!-- Current Listing Info -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Current Listing Info</h4>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Listing ID:</span>
                        <span class="font-semibold">{{ $listing->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-semibold {{ $listing->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $listing->is_active ? '✅ Active' : '❌ Inactive' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Featured:</span>
                        <span class="font-semibold {{ $listing->is_featured ? 'text-purple-600' : 'text-gray-600' }}">
                            {{ $listing->is_featured ? '⭐ Yes' : '📋 No' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Published:</span>
                        <span class="font-semibold">
                            {{ $listing->published_at ? $listing->published_at->format('M d, Y') : 'Not published' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Created:</span>
                        <span class="font-semibold">{{ $listing->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Updated:</span>
                        <span class="font-semibold">{{ $listing->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Edit Guidelines -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-lightbulb text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Edit Guidelines</h4>
                </div>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Ensure agricultural land is available and not already listed</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Write compelling descriptions highlighting land benefits</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Active listings appear in public search results</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Featured listings get premium visibility placement</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Published date controls when listing becomes visible</p>
                    </div>
                </div>
            </div>

            <!-- Associated Land Info -->
            @if($listing->terreAgricole)
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-seedling text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Associated Land</h4>
                </div>
                <div class="space-y-4">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4">
                        <h5 class="font-semibold text-gray-800 mb-2">{{ $listing->terreAgricole->title }}</h5>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-600">Surface:</span>
                                <span class="font-semibold text-green-600">{{ number_format($listing->terreAgricole->surface, 1) }} ha</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Price:</span>
                                <span class="font-semibold text-green-600">${{ number_format($listing->terreAgricole->price) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Region:</span>
                                <span class="font-semibold">{{ $listing->terreAgricole->region }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Status:</span>
                                <span class="font-semibold {{ $listing->terreAgricole->status === 'available' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ucfirst($listing->terreAgricole->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.lands.show', $listing->terreAgricole) }}" 
                       class="block w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white text-center py-2 rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all text-sm">
                        <i class="fas fa-external-link-alt mr-1"></i>View Land Details
                    </a>
                </div>
            </div>
            @endif

            <!-- Change History -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Recent Changes</h4>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="border-l-4 border-emerald-400 pl-3">
                        <div class="font-semibold text-emerald-700">Listing Updated</div>
                        <div class="text-gray-600">{{ $listing->updated_at->diffForHumans() }}</div>
                    </div>
                    @if($listing->published_at)
                    <div class="border-l-4 border-blue-400 pl-3">
                        <div class="font-semibold text-blue-700">Published</div>
                        <div class="text-gray-600">{{ $listing->published_at->diffForHumans() }}</div>
                    </div>
                    @endif
                    <div class="border-l-4 border-gray-400 pl-3">
                        <div class="font-semibold text-gray-700">Listing Created</div>
                        <div class="text-gray-600">{{ $listing->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>

            <!-- Performance Tips -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Performance Tips</h4>
                </div>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-star text-yellow-500 mt-1"></i>
                        <p>Featured listings get 3x more views than standard listings</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-clock text-blue-500 mt-1"></i>
                        <p>Listings published during weekdays get better engagement</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-image text-green-500 mt-1"></i>
                        <p>Include detailed descriptions for better search visibility</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-map-marker-alt text-red-500 mt-1"></i>
                        <p>Ensure land location details are accurate and complete</p>
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

/* Form enhancements */
.form-field-focus {
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    border-color: #10b981;
}

/* Character counter */
.char-counter {
    font-size: 0.75rem;
    color: #6b7280;
}

.char-counter.warning {
    color: #f59e0b;
}

.char-counter.danger {
    color: #ef4444;
}
</style>
@endpush

@push('scripts')
<script>
// Character counter for description
document.addEventListener('DOMContentLoaded', function() {
    const descriptionField = document.querySelector('textarea[name="description"]');
    const charCounter = document.getElementById('char-count');
    
    if (descriptionField && charCounter) {
        descriptionField.addEventListener('input', function() {
            const length = this.value.length;
            charCounter.textContent = `${length} characters`;
            
            // Add visual feedback based on length
            charCounter.className = 'char-counter';
            if (length < 200) {
                charCounter.className += ' text-yellow-600';
            } else if (length > 500) {
                charCounter.className += ' text-red-600';
            } else {
                charCounter.className += ' text-green-600';
            }
        });
    }
});

// Reset form
function resetForm() {
    if (confirm('Are you sure you want to reset all changes? This will restore the original values.')) {
        location.reload();
    }
}

// Form validation and enhancement
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');

    // Form submission enhancement
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating Listing...';
        submitBtn.disabled = true;
        
        // Re-enable if form submission fails
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }, 10000);
    });

    // Real-time form validation
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('border-red-300', 'ring-red-100');
                this.classList.remove('border-green-300', 'ring-green-100');
            } else {
                this.classList.add('border-green-300', 'ring-green-100');
                this.classList.remove('border-red-300', 'ring-red-100');
            }
        });
    });

    // Enhanced field focus effects
    const inputFields = form.querySelectorAll('input, select, textarea');
    inputFields.forEach(field => {
        field.addEventListener('focus', function() {
            this.classList.add('form-field-focus');
        });
        
        field.addEventListener('blur', function() {
            this.classList.remove('form-field-focus');
        });
    });

    // Animate form sections on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    // Observe all form cards
    const formCards = document.querySelectorAll('.glass-effect');
    formCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });

    // Show unsaved changes warning
    let formChanged = false;
    const formInputs = form.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('change', () => {
            formChanged = true;
        });
    });

    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    // Remove warning when form is submitted
    form.addEventListener('submit', function() {
        formChanged = false;
    });

    // Smart field interactions
    const activeCheckbox = document.querySelector('input[name="is_active"]');
    const publishedField = document.querySelector('input[name="published_at"]');
    
    if (activeCheckbox && publishedField) {
        activeCheckbox.addEventListener('change', function() {
            if (this.checked && !publishedField.value) {
                if (confirm('Would you like to set the published date to now since you\'re activating the listing?')) {
                    const now = new Date();
                    const formattedDate = now.toISOString().slice(0, 16);
                    publishedField.value = formattedDate;
                }
            }
        });
    }

    // Land selection helper
    const landSelect = document.querySelector('select[name="terre_agricole_id"]');
    if (landSelect) {
        landSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                // Extract information from the option text and show helpful info
                const optionText = selectedOption.textContent;
                showFieldHelper('Selected land information updated', 'success');
            }
        });
    }

    // Supplier selection helper
    const supplierSelect = document.querySelector('select[name="fournisseur_id"]');
    if (supplierSelect) {
        supplierSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                showFieldHelper('Supplier information updated', 'success');
            }
        });
    }
});

// Show field helper function
function showFieldHelper(message, type = 'success') {
    const helper = document.createElement('div');
    helper.className = `fixed bottom-4 right-4 z-50 p-3 rounded-lg text-white text-sm transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 'bg-blue-500'
    }`;
    helper.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(helper);
    
    // Animate in
    setTimeout(() => {
        helper.style.transform = 'translateY(0)';
        helper.style.opacity = '1';
    }, 100);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        helper.style.transform = 'translateY(100%)';
        helper.style.opacity = '0';
        setTimeout(() => helper.remove(), 300);
    }, 3000);
}

// Show success message function
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'notification success';
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="flex-1">
                <div class="font-semibold">Success!</div>
                <div class="text-sm opacity-90">${message}</div>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 w-6 h-6 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-all">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Show notification
    setTimeout(() => alertDiv.classList.add('show'), 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 300);
        }
    }, 5000);
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save (submit form)
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.querySelector('form').dispatchEvent(new Event('submit', { bubbles: true }));
    }
    
    // Escape to go back
    if (e.key === 'Escape') {
        if (confirm('Are you sure you want to leave? Unsaved changes will be lost.')) {
            window.location.href = '{{ route("admin.listings.show", $listing) }}';
        }
    }
    
    // Ctrl/Cmd + R to reset
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        resetForm();
    }
});

// Auto-save draft functionality
function saveDraft() {
    const formData = new FormData(document.querySelector('form'));
    const draftData = {};
    formData.forEach((value, key) => {
        draftData[key] = value;
    });
    
    localStorage.setItem('listing_edit_draft_{{ $listing->id }}', JSON.stringify(draftData));
    
    // Show draft saved indicator
    const indicator = document.createElement('div');
    indicator.className = 'fixed bottom-4 left-4 bg-blue-500 text-white px-4 py-2 rounded-lg text-sm opacity-0 transition-opacity';
    indicator.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-save mr-2"></i>
            Draft saved automatically
        </div>
    `;
    document.body.appendChild(indicator);
    
    setTimeout(() => indicator.style.opacity = '1', 100);
    setTimeout(() => {
        indicator.style.opacity = '0';
        setTimeout(() => indicator.remove(), 300);
    }, 2000);
}

// Load draft on page load
document.addEventListener('DOMContentLoaded', function() {
    const draftData = localStorage.getItem('listing_edit_draft_{{ $listing->id }}');
    if (draftData) {
        try {
            const data = JSON.parse(draftData);
            const form = document.querySelector('form');
            
            let hasChanges = false;
            Object.keys(data).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field) {
                    const currentValue = field.type === 'checkbox' ? field.checked : field.value;
                    const draftValue = field.type === 'checkbox' ? data[key] === '1' : data[key];
                    
                    if (currentValue !== draftValue) {
                        hasChanges = true;
                    }
                }
            });
            
            if (hasChanges) {
                const restore = confirm('A draft of your changes was found. Would you like to restore it?');
                if (restore) {
                    Object.keys(data).forEach(key => {
                        const field = form.querySelector(`[name="${key}"]`);
                        if (field) {
                            if (field.type === 'checkbox') {
                                field.checked = data[key] === '1';
                            } else {
                                field.value = data[key];
                            }
                        }
                    });
                    showFieldHelper('Draft restored successfully', 'success');
                } else {
                    localStorage.removeItem('listing_edit_draft_{{ $listing->id }}');
                }
            } else {
                localStorage.removeItem('listing_edit_draft_{{ $listing->id }}');
            }
        } catch (e) {
            localStorage.removeItem('listing_edit_draft_{{ $listing->id }}');
        }
    }
});

// Auto-save every 30 seconds
setInterval(saveDraft, 30000);

// Clear draft on successful submission
document.querySelector('form').addEventListener('submit', function() {
    localStorage.removeItem('listing_edit_draft_{{ $listing->id }}');
});

// Form field validation helpers
document.addEventListener('DOMContentLoaded', function() {
    const titleField = document.querySelector('input[name="title"]');
    const descriptionField = document.querySelector('textarea[name="description"]');
    
    // Title validation
    if (titleField) {
        titleField.addEventListener('input', function() {
            const length = this.value.length;
            if (length < 10) {
                this.style.borderColor = '#f59e0b';
                showFieldHelper('Title should be at least 10 characters', 'warning');
            } else if (length > 100) {
                this.style.borderColor = '#ef4444';
                showFieldHelper('Title should be under 100 characters', 'error');
            } else {
                this.style.borderColor = '#10b981';
            }
        });
    }
    
    // Description validation
    if (descriptionField) {
        descriptionField.addEventListener('input', function() {
            const length = this.value.length;
            const wordCount = this.value.trim().split(/\s+/).length;
            
            if (length < 50) {
                this.style.borderColor = '#f59e0b';
            } else if (length > 1000) {
                this.style.borderColor = '#ef4444';
            } else {
                this.style.borderColor = '#10b981';
            }
        });
    }
});

// Enhanced accessibility
document.addEventListener('DOMContentLoaded', function() {
    // Add proper ARIA labels
    const formFields = document.querySelectorAll('input, select, textarea');
    formFields.forEach(field => {
        const label = document.querySelector(`label[for="${field.id}"]`) || 
                     field.closest('label') ||
                     field.previousElementSibling;
        
        if (label && !field.getAttribute('aria-label')) {
            field.setAttribute('aria-label', label.textContent.trim());
        }
        
        // Add required indicator for screen readers
        if (field.required && !field.getAttribute('aria-required')) {
            field.setAttribute('aria-required', 'true');
        }
    });
    
    // Announce form changes to screen readers
    const announcement = document.createElement('div');
    announcement.setAttribute('aria-live', 'polite');
    announcement.setAttribute('aria-atomic', 'true');
    announcement.className = 'sr-only';
    document.body.appendChild(announcement);
    
    // Announce when form fields are updated
    formFields.forEach(field => {
        field.addEventListener('change', function() {
            announcement.textContent = `${this.getAttribute('aria-label') || this.name} updated`;
        });
    });
});

// Performance monitoring
const performanceData = {
    loadTime: window.performance.timing.loadEventEnd - window.performance.timing.navigationStart,
    domReady: window.performance.timing.domContentLoadedEventEnd - window.performance.timing.navigationStart
};

// Log performance data for optimization
setTimeout(() => {
    console.log('Page Performance:', performanceData);
}, 1000);

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Focus management
    const firstField = document.querySelector('select[name="fournisseur_id"]');
    if (firstField) {
        setTimeout(() => firstField.focus(), 500);
    }
    
    // Set up smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endpush
@endsection