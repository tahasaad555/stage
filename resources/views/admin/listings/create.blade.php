@extends('layouts.admin')

@section('title', 'Create New Listing')

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <a href="{{ route('admin.listings.index') }}" class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4 hover:bg-opacity-30 transition-all">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">🏞️ Create New Listing</h1>
                        <p class="text-xl opacity-90">Create a compelling advertisement for agricultural land</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-plus-circle mr-2"></i>
                        <span>New Listing Creation</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-eye mr-2"></i>
                        <span>Market Visibility</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-chart-line mr-2"></i>
                        <span>Sales Optimization</span>
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

    <!-- Listing Creation Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.listings.store') }}" class="space-y-8">
                @csrf
                
                <!-- Property Selection Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-map-marked-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Property Selection</h3>
                            <p class="text-gray-600">Choose the supplier and agricultural land for this listing</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Supplier Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-building mr-2 text-emerald-500"></i>Supplier/Owner *
                            </label>
                            <select name="fournisseur_id" id="fournisseur_id" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('fournisseur_id') border-red-300 ring-red-100 @enderror">
                                <option value="">Select a supplier...</option>
                                @foreach($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}
                                            data-company="{{ $fournisseur->company_name }}" data-name="{{ $fournisseur->user->full_name }}">
                                        🏢 {{ $fournisseur->user->full_name }} - {{ $fournisseur->company_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fournisseur_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Agricultural Land Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-seedling mr-2 text-green-500"></i>Agricultural Land *
                            </label>
                            <select name="terre_agricole_id" id="terre_agricole_id" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('terre_agricole_id') border-red-300 ring-red-100 @enderror">
                                <option value="">Select agricultural land...</option>
                                @foreach($lands as $land)
                                    <option value="{{ $land->id }}" {{ old('terre_agricole_id') == $land->id ? 'selected' : '' }}
                                            data-title="{{ $land->title }}" data-region="{{ $land->region }}" 
                                            data-surface="{{ $land->surface }}" data-price="{{ $land->price }}">
                                        🌾 {{ $land->title }} - {{ $land->region }} ({{ number_format($land->surface, 1) }} ha - ${{ number_format($land->price) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('terre_agricole_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @if($lands->isEmpty())
                                <div class="mt-3 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                                        <p class="text-yellow-700 text-sm">
                                            No available lands without existing listings found. 
                                            <a href="{{ route('admin.lands.create') }}" class="text-emerald-600 hover:text-emerald-800 font-semibold">Create a new land first</a>.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Selected Property Preview -->
                    <div id="property-preview" class="hidden mt-6 p-6 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200">
                        <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-eye mr-2 text-emerald-600"></i>Property Preview
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Supplier:</span>
                                <span id="preview-supplier" class="font-semibold text-gray-800 ml-2"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Land:</span>
                                <span id="preview-land" class="font-semibold text-gray-800 ml-2"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Size:</span>
                                <span id="preview-size" class="font-semibold text-emerald-600 ml-2"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Price:</span>
                                <span id="preview-price" class="font-semibold text-blue-600 ml-2"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Listing Details Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-edit text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Listing Details</h3>
                            <p class="text-gray-600">Create compelling title and description for your listing</p>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading mr-2 text-blue-500"></i>Listing Title *
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               placeholder="e.g., Prime Agricultural Land in Marrakech Region - Perfect for Organic Farming"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('title') border-red-300 ring-red-100 @enderror">
                        <div class="mt-2 flex justify-between text-xs">
                            <span class="text-gray-500">Make it descriptive and appealing to potential buyers</span>
                            <span id="title-counter" class="text-gray-400">0/100</span>
                        </div>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-purple-500"></i>Detailed Description *
                        </label>
                        <textarea name="description" id="description" rows="6" required
                                  placeholder="Provide a comprehensive description including:&#10;• Land features and quality&#10;• Location advantages&#10;• Soil type and fertility&#10;• Water access and irrigation&#10;• Nearby infrastructure&#10;• Suitable crops or farming types&#10;• Any unique selling points"
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-none @error('description') border-red-300 ring-red-100 @enderror">{{ old('description') }}</textarea>
                        <div class="mt-2 flex justify-between text-xs">
                            <span class="text-gray-500">Be detailed and highlight unique features</span>
                            <span id="description-counter" class="text-gray-400">0/1000</span>
                        </div>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Publishing Options Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-cog text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Publishing Options</h3>
                            <p class="text-gray-600">Configure visibility and publication settings</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status Options -->
                        <div class="space-y-4">
                            <!-- Active Status -->
                            <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-green-300 transition-all">
                                <label class="flex items-start cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                                           class="w-5 h-5 text-green-600 border-2 border-gray-300 rounded focus:ring-green-500 focus:ring-2 mt-1">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-gray-700 flex items-center">
                                            <i class="fas fa-eye mr-2 text-green-500"></i>Active Listing
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">Make this listing visible to clients immediately</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Featured Status -->
                            <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-purple-300 transition-all">
                                <label class="flex items-start cursor-pointer">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                           class="w-5 h-5 text-purple-600 border-2 border-gray-300 rounded focus:ring-purple-500 focus:ring-2 mt-1">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-gray-700 flex items-center">
                                            <i class="fas fa-star mr-2 text-purple-500"></i>Featured Listing
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">Highlight this listing prominently on the homepage</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Published Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-2 text-indigo-500"></i>Publication Date & Time
                            </label>
                            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('published_at') border-red-300 ring-red-100 @enderror">
                            <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    <p class="text-blue-700 text-xs">
                                        Leave empty to auto-set publication date when the listing is activated
                                    </p>
                                </div>
                            </div>
                            @error('published_at')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.listings.index') }}" 
                       class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl hover:bg-gray-300 transition-all flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Cancel & Go Back
                    </a>
                    
                    <div class="flex items-center space-x-4">
                        <button type="button" onclick="saveDraft()" 
                                class="bg-yellow-500 text-white px-6 py-3 rounded-xl hover:bg-yellow-600 transition-all flex items-center">
                            <i class="fas fa-save mr-2"></i>Save Draft
                        </button>
                        <button type="submit" 
                                class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-8 py-3 rounded-xl hover:from-emerald-600 hover:to-teal-700 transition-all flex items-center shadow-lg">
                            <i class="fas fa-plus-circle mr-2"></i>Create Listing
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar - Instructions & Help -->
        <div class="space-y-6">
            <!-- Listing Tips -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-lightbulb text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Listing Tips</h4>
                </div>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-emerald-500 mt-1"></i>
                        <p>Use descriptive titles that include location and key features</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-emerald-500 mt-1"></i>
                        <p>Highlight unique selling points like soil quality and water access</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-emerald-500 mt-1"></i>
                        <p>Mention nearby infrastructure and transportation links</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-emerald-500 mt-1"></i>
                        <p>Include information about suitable crops or farming types</p>
                    </div>
                </div>
            </div>

            <!-- Listing Requirements -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-clipboard-check text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Requirements</h4>
                </div>
                <div class="space-y-4">
                    <div class="border-l-4 border-emerald-400 pl-4">
                        <h5 class="font-semibold text-emerald-700">🏢 Supplier Selection</h5>
                        <p class="text-xs text-gray-600">Choose a verified supplier with proper documentation</p>
                    </div>
                    <div class="border-l-4 border-green-400 pl-4">
                        <h5 class="font-semibold text-green-700">🌾 Land Selection</h5>
                        <p class="text-xs text-gray-600">Select available land without existing active listings</p>
                    </div>
                    <div class="border-l-4 border-blue-400 pl-4">
                        <h5 class="font-semibold text-blue-700">📝 Content Quality</h5>
                        <p class="text-xs text-gray-600">Provide detailed, accurate, and compelling descriptions</p>
                    </div>
                </div>
            </div>

            <!-- SEO Guidelines -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-search text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">SEO Guidelines</h4>
                </div>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-tag text-purple-500 mt-1"></i>
                        <p>Include relevant keywords like region names and farming types</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-heading text-blue-500 mt-1"></i>
                        <p>Keep titles between 50-60 characters for optimal display</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-align-left text-green-500 mt-1"></i>
                        <p>Write descriptions between 150-300 words for best results</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Platform Stats</h4>
                </div>
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-blue-600">{{ \App\Models\Annonce::count() }}</div>
                        <div class="text-xs text-gray-600">Total Listings</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-green-600">{{ \App\Models\Annonce::where('is_active', true)->count() }}</div>
                        <div class="text-xs text-gray-600">Active Listings</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-purple-600">{{ \App\Models\Annonce::where('is_featured', true)->count() }}</div>
                        <div class="text-xs text-gray-600">Featured</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-yellow-600">{{ $lands->count() }}</div>
                        <div class="text-xs text-gray-600">Available Lands</div>
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

/* Character counter animations */
.char-limit-warning {
    color: #f59e0b !important;
}

.char-limit-exceeded {
    color: #ef4444 !important;
}
</style>
@endpush

@push('scripts')
<script>
// Auto-populate title based on selected land
document.getElementById('terre_agricole_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const titleInput = document.getElementById('title');
    
    if (selectedOption.value && !titleInput.value) {
        const title = selectedOption.dataset.title;
        const region = selectedOption.dataset.region;
        
        if (title && region) {
            titleInput.value = `${title} - Premium Agricultural Land in ${region}`;
            updateCharCounter('title');
        }
    }
    
    updatePropertyPreview();
});

// Update property preview
function updatePropertyPreview() {
    const supplierSelect = document.getElementById('fournisseur_id');
    const landSelect = document.getElementById('terre_agricole_id');
    const preview = document.getElementById('property-preview');
    
    if (supplierSelect.value && landSelect.value) {
        const supplierOption = supplierSelect.options[supplierSelect.selectedIndex];
        const landOption = landSelect.options[landSelect.selectedIndex];
        
        document.getElementById('preview-supplier').textContent = supplierOption.dataset.name;
        document.getElementById('preview-land').textContent = landOption.dataset.title;
        document.getElementById('preview-size').textContent = `${parseFloat(landOption.dataset.surface).toFixed(1)} hectares`;
        document.getElementById('preview-price').textContent = `$${parseInt(landOption.dataset.price).toLocaleString()}`;
        
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}

// Character counters
function updateCharCounter(fieldId) {
    const field = document.getElementById(fieldId);
    const counter = document.getElementById(fieldId + '-counter');
    const maxLength = fieldId === 'title' ? 100 : 1000;
    
    if (field && counter) {
        const currentLength = field.value.length;
        counter.textContent = `${currentLength}/${maxLength}`;
        
        // Update counter color based on length
        counter.classList.remove('char-limit-warning', 'char-limit-exceeded');
        if (currentLength > maxLength * 0.8) {
            counter.classList.add('char-limit-warning');
        }
        if (currentLength > maxLength) {
            counter.classList.add('char-limit-exceeded');
        }
    }
}

// Initialize character counters
document.getElementById('title').addEventListener('input', () => updateCharCounter('title'));
document.getElementById('description').addEventListener('input', () => updateCharCounter('description'));

// Update supplier selection preview
document.getElementById('fournisseur_id').addEventListener('change', updatePropertyPreview);

// Save draft functionality
function saveDraft() {
    const formData = new FormData(document.querySelector('form'));
    const draftData = {};
    formData.forEach((value, key) => {
        draftData[key] = value;
    });
    
    localStorage.setItem('listing_draft', JSON.stringify(draftData));
    
    // Show draft saved notification
    const notification = document.createElement('div');
    notification.className = 'fixed bottom-4 right-4 bg-yellow-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 transition-all duration-300 transform translate-x-full';
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-save mr-2"></i>
            <span>Draft saved successfully!</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Load draft on page load
document.addEventListener('DOMContentLoaded', function() {
    const draftData = localStorage.getItem('listing_draft');
    if (draftData) {
        try {
            const data = JSON.parse(draftData);
            const form = document.querySelector('form');
            
            const restore = confirm('A draft of your listing was found. Would you like to restore it?');
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
                
                // Update counters and preview
                updateCharCounter('title');
                updateCharCounter('description');
                updatePropertyPreview();
            } else {
                localStorage.removeItem('listing_draft');
            }
        } catch (e) {
            localStorage.removeItem('listing_draft');
        }
    }
    
    // Initialize character counters
    updateCharCounter('title');
    updateCharCounter('description');
});

// Form submission enhancement
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Listing...';
    submitBtn.disabled = true;
    
    // Clear draft on successful submission
    localStorage.removeItem('listing_draft');
    
    // Re-enable if form submission fails
    setTimeout(() => {
        if (submitBtn.disabled) {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }, 10000);
});

// Real-time form validation
const requiredFields = document.querySelectorAll('[required]');
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

// Auto-save draft every 30 seconds
setInterval(saveDraft, 30000);

// Prevent accidental navigation
let formChanged = false;
const formInputs = document.querySelectorAll('input, select, textarea');
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

// Smart title suggestions
function generateTitleSuggestions() {
    const landSelect = document.getElementById('terre_agricole_id');
    const titleInput = document.getElementById('title');
    
    if (!landSelect.value) return;
    
    const landOption = landSelect.options[landSelect.selectedIndex];
    const title = landOption.dataset.title;
    const region = landOption.dataset.region;
    const surface = parseFloat(landOption.dataset.surface);
    
    const suggestions = [
        `${title} - Premium Agricultural Land in ${region}`,
        `${surface} Hectares of Prime Farmland in ${region}`,
        `Exceptional Agricultural Property - ${title}, ${region}`,
        `${region} Agricultural Land - ${title} (${surface} ha)`,
        `Investment Opportunity: ${title} in ${region}`
    ];
    
    // Show suggestions dropdown
    showTitleSuggestions(suggestions);
}

function showTitleSuggestions(suggestions) {
    // Remove existing suggestions
    const existingDropdown = document.getElementById('title-suggestions');
    if (existingDropdown) {
        existingDropdown.remove();
    }
    
    const titleInput = document.getElementById('title');
    const dropdown = document.createElement('div');
    dropdown.id = 'title-suggestions';
    dropdown.className = 'absolute z-10 w-full bg-white border border-gray-300 rounded-xl shadow-lg mt-1 max-h-60 overflow-y-auto';
    
    suggestions.forEach(suggestion => {
        const item = document.createElement('div');
        item.className = 'px-4 py-3 hover:bg-gray-100 cursor-pointer text-sm border-b border-gray-100 last:border-b-0';
        item.textContent = suggestion;
        item.addEventListener('click', () => {
            titleInput.value = suggestion;
            updateCharCounter('title');
            dropdown.remove();
            formChanged = true;
        });
        dropdown.appendChild(item);
    });
    
    // Position dropdown
    const titleContainer = titleInput.parentElement;
    titleContainer.style.position = 'relative';
    titleContainer.appendChild(dropdown);
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function closeDropdown(e) {
        if (!titleContainer.contains(e.target)) {
            dropdown.remove();
            document.removeEventListener('click', closeDropdown);
        }
    });
}

// Add title suggestions button
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const suggestButton = document.createElement('button');
    suggestButton.type = 'button';
    suggestButton.className = 'absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors';
    suggestButton.innerHTML = '<i class="fas fa-magic"></i>';
    suggestButton.title = 'Generate title suggestions';
    suggestButton.onclick = generateTitleSuggestions;
    
    titleInput.parentElement.style.position = 'relative';
    titleInput.style.paddingRight = '2.5rem';
    titleInput.parentElement.appendChild(suggestButton);
});

// Enhanced validation messages
function showValidationMessage(field, message, type = 'error') {
    // Remove existing message
    const existingMessage = field.parentElement.querySelector('.validation-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `validation-message text-sm mt-1 ${type === 'error' ? 'text-red-600' : 'text-green-600'}`;
    messageDiv.innerHTML = `<i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'} mr-1"></i>${message}`;
    
    field.parentElement.appendChild(messageDiv);
}

// Custom validation for title and description
document.getElementById('title').addEventListener('blur', function() {
    const value = this.value.trim();
    if (value.length < 10) {
        showValidationMessage(this, 'Title should be at least 10 characters long', 'error');
    } else if (value.length > 100) {
        showValidationMessage(this, 'Title should not exceed 100 characters', 'error');
    } else {
        showValidationMessage(this, 'Title looks good!', 'success');
    }
});

document.getElementById('description').addEventListener('blur', function() {
    const value = this.value.trim();
    if (value.length < 50) {
        showValidationMessage(this, 'Description should be at least 50 characters long for better SEO', 'error');
    } else if (value.length > 1000) {
        showValidationMessage(this, 'Description should not exceed 1000 characters', 'error');
    } else {
        showValidationMessage(this, 'Description is well detailed!', 'success');
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save draft
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        saveDraft();
    }
    
    // Ctrl/Cmd + Enter to submit form
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        document.querySelector('form').dispatchEvent(new Event('submit', { bubbles: true }));
    }
    
    // Escape to go back
    if (e.key === 'Escape') {
        if (confirm('Are you sure you want to leave? Unsaved changes will be lost.')) {
            window.location.href = '{{ route("admin.listings.index") }}';
        }
    }
});

// Success message function
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed top-4 right-4 z-50 bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-6 rounded-xl shadow-2xl transform transition-all duration-500';
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
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.style.transform = 'translateX(100%)';
            setTimeout(() => alertDiv.remove(), 500);
        }
    }, 5000);
}
</script>
@endpush
@endsection