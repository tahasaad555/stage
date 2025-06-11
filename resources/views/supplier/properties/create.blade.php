@extends('layouts.supplier')

@section('title', 'Add New Property')

@push('styles')
<style>
.glass-effect {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.card-hover:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    transform: translateY(-2px);
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

/* Character counter styles */
.char-counter {
    font-size: 0.75rem;
    color: #6b7280;
}

.char-limit-warning {
    color: #f59e0b !important;
}

.char-limit-exceeded {
    color: #ef4444 !important;
}

/* Land preview styles */
.land-preview {
    background: linear-gradient(135deg, #10b981, #34d399);
    color: white;
}

/* Form focus effects */
.form-input:focus {
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    border-color: #10b981;
}

/* Enhanced Land Card Selection */
.land-card {
    border: 2px solid transparent;
    transition: all 0.3s ease;
    cursor: pointer;
}

.land-card:hover {
    border-color: #10b981;
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.1);
    transform: translateY(-2px);
}

.land-card.selected {
    border-color: #10b981;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(52, 211, 153, 0.1));
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);
}

.land-card .status-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-available {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.status-assigned {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
}

.land-visual {
    background: linear-gradient(45deg, #10b981, #34d399, #6ee7b7);
    background-size: 200% 200%;
    animation: landGradient 3s ease infinite;
    border-radius: 8px;
    position: relative;
    overflow: hidden;
}

@keyframes landGradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.land-visual::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M20,80 Q30,60 40,80 T60,80 T80,80" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="2"/><circle cx="25" cy="20" r="8" fill="rgba(255,255,255,0.4)"/><circle cx="75" cy="25" r="6" fill="rgba(255,255,255,0.3)"/><rect x="10" y="10" width="4" height="4" fill="rgba(255,255,255,0.5)"/><rect x="85" y="15" width="3" height="3" fill="rgba(255,255,255,0.4)"/></svg>') center/cover;
}

.empty-state {
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    border: 2px dashed #d1d5db;
}

.assignment-info {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    border: 1px solid #93c5fd;
    border-radius: 8px;
    padding: 12px;
    margin-top: 16px;
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
                    <a href="{{ route('supplier.properties.index') }}" class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4 hover:bg-opacity-30 transition-all">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">🌱 Add New Property</h1>
                        <p class="text-xl opacity-90">Create a compelling listing for your assigned agricultural land</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <span>Your Assigned Lands Only</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-eye mr-2"></i>
                        <span>Market Visibility</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-chart-line mr-2"></i>
                        <span>Sales Potential</span>
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

    <!-- Property Creation Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('supplier.properties.store') }}" class="space-y-8">
                @csrf
                
                <!-- Enhanced Land Selection Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-map-marked-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">🗺️ Select Your Agricultural Land</h2>
                                <p class="text-gray-600">Choose from your assigned lands</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            {{ count($agriculturalLands) }} land(s) available
                        </div>
                    </div>

                    @if(count($agriculturalLands) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            @foreach($agriculturalLands as $land)
                                <div class="land-card glass-effect rounded-xl p-6 relative" 
                                     onclick="selectLand({{ $land->id }})" 
                                     data-land-id="{{ $land->id }}">
                                    
                                    <!-- Status Badge -->
                                    <div class="status-badge status-{{ $land->assigned_supplier_id ? 'assigned' : 'available' }}">
                                        {{ $land->assigned_supplier_id ? 'Assigned to You' : 'Available' }}
                                    </div>
                                    
                                    <!-- Land Visual -->
                                    <div class="land-visual h-24 w-full mb-4 flex items-center justify-center">
                                        <div class="text-center text-white">
                                            <i class="fas fa-seedling text-2xl mb-1"></i>
                                            <div class="text-xs font-semibold">{{ number_format($land->surface, 1) }} ha</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Land Info -->
                                    <div class="space-y-2">
                                        <h3 class="font-bold text-gray-800 text-lg">{{ $land->title }}</h3>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                            <span class="font-medium">{{ $land->region }}</span>
                                        </div>
                                        
                                        @if($land->localisation)
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="fas fa-location-dot mr-2 text-blue-500"></i>
                                                <span>{{ $land->localisation }}</span>
                                            </div>
                                        @endif
                                        
                                        <div class="grid grid-cols-2 gap-2 mt-3">
                                            <div class="text-center p-2 bg-white bg-opacity-30 rounded-lg">
                                                <div class="text-sm font-bold text-green-700">{{ number_format($land->surface, 1) }}</div>
                                                <div class="text-xs text-gray-600">Hectares</div>
                                            </div>
                                            <div class="text-center p-2 bg-white bg-opacity-30 rounded-lg">
                                                <div class="text-sm font-bold text-blue-700">{{ $land->soil_type ?? 'Mixed' }}</div>
                                                <div class="text-xs text-gray-600">Soil Type</div>
                                            </div>
                                        </div>
                                        
                                        @if($land->price)
                                            <div class="mt-3 p-2 bg-yellow-50 rounded-lg">
                                                <div class="text-center">
                                                    <div class="text-lg font-bold text-yellow-700">{{ number_format($land->price / 1000) }}K MAD</div>
                                                    <div class="text-xs text-yellow-600">Base Land Value</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Selection Indicator -->
                                    <div class="selection-indicator hidden absolute top-4 left-4 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-white text-sm"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Hidden Input for Selected Land -->
                        <input type="hidden" id="terre_agricole_id" name="terre_agricole_id" value="{{ old('terre_agricole_id') }}" required>
                        
                        @error('terre_agricole_id')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        
                        <!-- Assignment Info -->
                        <div class="assignment-info">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Land Assignment Information</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        You can only create listings for lands that have been assigned to you by the administrator. 
                                        If you need access to additional lands, please contact support.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                    @else
                        <!-- Empty State -->
                        <div class="empty-state rounded-xl p-12 text-center">
                            <div class="w-24 h-24 bg-gray-300 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-map text-4xl text-gray-500"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-600 mb-4">No Lands Assigned</h3>
                            <p class="text-gray-500 mb-6 max-w-md mx-auto">
                                You don't have any agricultural lands assigned to you yet. Contact the administrator to get lands assigned to your account.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('supplier.dashboard') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-3 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                                </a>
                                <button type="button" onclick="contactSupport()" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all">
                                    <i class="fas fa-envelope mr-2"></i>Contact Support
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                @if(count($agriculturalLands) > 0)
                    <!-- Property Details Card -->
                    <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-edit text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">📝 Property Details</h2>
                                <p class="text-gray-600">Provide compelling information about your property</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="titre" class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-heading mr-2 text-blue-500"></i>Property Title *
                                    <span class="char-counter" id="titre-counter">0/255</span>
                                </label>
                                <input type="text" id="titre" name="titre" 
                                       value="{{ old('titre') }}" required maxlength="255"
                                       placeholder="e.g., Premium Agricultural Land in Casablanca Region"
                                       class="form-input w-full px-4 py-4 bg-white bg-opacity-50 border border-white border-opacity-30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 font-medium">
                                @error('titre')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-align-left mr-2 text-blue-500"></i>Description *
                                    <span class="char-counter" id="description-counter">0/2000</span>
                                </label>
                                <textarea id="description" name="description" rows="6" required
                                          maxlength="2000" placeholder="Describe your property's features, location advantages, soil quality, accessibility, nearby amenities, and potential uses..."
                                          class="form-input w-full px-4 py-4 bg-white bg-opacity-50 border border-white border-opacity-30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 font-medium">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="prix" class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-dollar-sign mr-2 text-green-500"></i>Your Listing Price (MAD) *
                                </label>
                                <div class="relative">
                                    <input type="number" id="prix" name="prix" 
                                           value="{{ old('prix') }}" required min="0" step="0.01"
                                           placeholder="Enter your listing price in Moroccan Dirhams"
                                           class="form-input w-full px-4 py-4 bg-white bg-opacity-50 border border-white border-opacity-30 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-800 font-medium">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">MAD</span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Set your desired selling/rental price (can be different from the base land value)
                                </p>
                                @error('prix')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Property Settings Card -->
                    <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-cogs text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">⚙️ Listing Settings</h2>
                                <p class="text-gray-600">Configure how your property appears</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white bg-opacity-50 rounded-xl p-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" value="1" 
                                           {{ old('is_active') ? 'checked' : '' }}
                                           class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                                    <span class="ml-3 text-gray-700 font-medium">
                                        <i class="fas fa-eye mr-2 text-green-500"></i>Activate Listing
                                    </span>
                                </label>
                                <p class="text-sm text-gray-600 mt-2 ml-8">Make this property visible to potential buyers</p>
                            </div>

                            <div class="bg-white bg-opacity-50 rounded-xl p-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_featured" value="1" 
                                           {{ old('is_featured') ? 'checked' : '' }}
                                           class="w-5 h-5 text-yellow-600 rounded focus:ring-yellow-500">
                                    <span class="ml-3 text-gray-700 font-medium">
                                        <i class="fas fa-star mr-2 text-yellow-500"></i>Featured Listing
                                    </span>
                                </label>
                                <p class="text-sm text-gray-600 mt-2 ml-8">Highlight this property for better visibility</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all font-bold text-lg shadow-lg">
                            <i class="fas fa-plus mr-3"></i>Create Property Listing
                        </button>
                        
                        <button type="button" onclick="saveDraft()" 
                                class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-8 py-4 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all font-medium">
                            <i class="fas fa-save mr-3"></i>Save Draft
                        </button>
                        
                        <a href="{{ route('supplier.properties.index') }}" 
                           class="bg-gradient-to-r from-red-500 to-red-600 text-white px-8 py-4 rounded-xl hover:from-red-600 hover:to-red-700 transition-all font-medium text-center">
                            <i class="fas fa-times mr-3"></i>Cancel
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Sidebar - Land Preview & Tips -->
        <div class="space-y-6">
            <!-- Land Preview -->
            <div id="land-preview" class="glass-effect rounded-2xl p-6 shadow-xl card-hover hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-map-marker-alt mr-2 text-green-500"></i>Selected Land Details
                </h3>
                <div class="land-preview rounded-xl p-6">
                    <div class="space-y-3 text-sm">
                        <div><strong>Land Name:</strong> <span id="preview-title">-</span></div>
                        <div><strong>Location:</strong> <span id="preview-location">-</span></div>
                        <div><strong>Region:</strong> <span id="preview-region">-</span></div>
                        <div><strong>Surface:</strong> <span id="preview-surface">-</span> hectares</div>
                        <div><strong>Soil Type:</strong> <span id="preview-type">-</span></div>
                        <div><strong>Base Value:</strong> <span id="preview-price">-</span> MAD</div>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>💡 Listing Tips
                </h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Select the land that best matches your listing goals</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Set competitive pricing based on market research</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Include details about soil quality and water access</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Mention nearby infrastructure and accessibility</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Activate and feature listings for better visibility</span>
                    </li>
                </ul>
            </div>

            <!-- Security Notice -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover border-l-4 border-blue-500">
                <h3 class="text-lg font-bold text-gray-800 mb-3">
                    <i class="fas fa-shield-alt mr-2 text-blue-500"></i>🔒 Security Notice
                </h3>
                <p class="text-sm text-gray-600">
                    For your security and business integrity, you can only create listings for agricultural lands that have been specifically assigned to you by the platform administrator.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedLandData = null;

// Land Selection Function
function selectLand(landId) {
    // Remove previous selection
    document.querySelectorAll('.land-card').forEach(card => {
        card.classList.remove('selected');
        card.querySelector('.selection-indicator').classList.add('hidden');
    });
    
    // Add selection to clicked card
    const selectedCard = document.querySelector(`[data-land-id="${landId}"]`);
    selectedCard.classList.add('selected');
    selectedCard.querySelector('.selection-indicator').classList.remove('hidden');
    
    // Set hidden input value
    document.getElementById('terre_agricole_id').value = landId;
    
    // Get land data from the selected card
    const landTitle = selectedCard.querySelector('h3').textContent;
    const landRegion = selectedCard.querySelector('.fa-map-marker-alt').nextElementSibling.textContent;
    const landLocation = selectedCard.querySelector('.fa-location-dot')?.nextElementSibling?.textContent || 'Not specified';
    const landSurface = selectedCard.querySelector('.text-green-700').textContent;
    const landSoilType = selectedCard.querySelector('.text-blue-700').textContent;
    const landPrice = selectedCard.querySelector('.text-yellow-700')?.textContent || 'Not specified';
    
    // Store selected land data
    selectedLandData = {
        title: landTitle,
        region: landRegion,
        location: landLocation,
        surface: landSurface,
        soilType: landSoilType,
        price: landPrice
    };
    
    // Update preview
    updateLandPreview();
    
    // Auto-populate title if empty
    const titleInput = document.getElementById('titre');
    if (!titleInput.value && landTitle && landRegion) {
        titleInput.value = `Premium ${landTitle} in ${landRegion}`;
        updateCharCounter('titre', 255);
    }
}

// Update Land Preview
function updateLandPreview() {
    const preview = document.getElementById('land-preview');
    
    if (selectedLandData) {
        document.getElementById('preview-title').textContent = selectedLandData.title;
        document.getElementById('preview-location').textContent = selectedLandData.location;
        document.getElementById('preview-region').textContent = selectedLandData.region;
        document.getElementById('preview-surface').textContent = selectedLandData.surface;
        document.getElementById('preview-type').textContent = selectedLandData.soilType;
        document.getElementById('preview-price').textContent = selectedLandData.price;
        
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}

// Character counters
function updateCharCounter(fieldId, maxLength) {
    const field = document.getElementById(fieldId);
    const counter = document.getElementById(fieldId + '-counter');
    
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
document.getElementById('titre')?.addEventListener('input', () => updateCharCounter('titre', 255));
document.getElementById('description')?.addEventListener('input', () => updateCharCounter('description', 2000));

// Contact Support Function
function contactSupport() {
    showNotification('Support contact feature will be implemented soon. Please use the main contact form for now.', 'info');
}

// Save draft functionality
function saveDraft() {
    const formData = new FormData(document.querySelector('form'));
    const draftData = {};
    
    formData.forEach((value, key) => {
        draftData[key] = value;
    });
    
    // Remove the CSRF token from draft data
    delete draftData._token;
    
    // Include selected land data
    if (selectedLandData) {
        draftData.selectedLandData = selectedLandData;
    }
    
    localStorage.setItem('property_draft', JSON.stringify(draftData));
    
    // Show notification
    showNotification('Draft saved successfully!', 'success');
}

// Load draft on page load
document.addEventListener('DOMContentLoaded', function() {
    const draftData = localStorage.getItem('property_draft');
    if (draftData) {
        try {
            const data = JSON.parse(draftData);
            
            if (confirm('A draft was found. Would you like to restore it?')) {
                Object.keys(data).forEach(key => {
                    if (key === 'selectedLandData') {
                        return; // Handle separately
                    }
                    
                    const field = document.querySelector(`[name="${key}"]`);
                    if (field) {
                        if (field.type === 'checkbox') {
                            field.checked = data[key] === '1';
                        } else {
                            field.value = data[key];
                        }
                    }
                });
                
                // Restore selected land
                if (data.terre_agricole_id) {
                    selectLand(data.terre_agricole_id);
                }
                
                // Update character counters
                updateCharCounter('titre', 255);
                updateCharCounter('description', 2000);
            }
        } catch (e) {
            console.error('Error loading draft:', e);
        }
    }
    
    // Pre-select land if there's an old value
    const oldLandId = document.getElementById('terre_agricole_id').value;
    if (oldLandId) {
        selectLand(oldLandId);
    }
});

// Clear draft after successful submission
document.querySelector('form')?.addEventListener('submit', function() {
    localStorage.removeItem('property_draft');
});

// Show notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    let bgColor = 'bg-green-500';
    let icon = 'check';
    
    if (type === 'error') {
        bgColor = 'bg-red-500';
        icon = 'exclamation-triangle';
    } else if (type === 'info') {
        bgColor = 'bg-blue-500';
        icon = 'info-circle';
    } else if (type === 'warning') {
        bgColor = 'bg-yellow-500';
        icon = 'exclamation-triangle';
    }
    
    notification.className = `fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded-xl shadow-lg z-50 transition-all duration-300 transform translate-x-full`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${icon} mr-2"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white opacity-70 hover:opacity-100">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Form validation before submit
document.querySelector('form')?.addEventListener('submit', function(e) {
    const selectedLandId = document.getElementById('terre_agricole_id').value;
    
    if (!selectedLandId) {
        e.preventDefault();
        showNotification('Please select an agricultural land before submitting.', 'error');
        
        // Scroll to land selection section
        const landSection = document.querySelector('.land-card').closest('.glass-effect');
        if (landSection) {
            landSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            landSection.style.borderColor = '#ef4444';
            setTimeout(() => {
                landSection.style.borderColor = '';
            }, 3000);
        }
        
        return false;
    }
});

// Add visual feedback for form interactions
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
        }, index * 150);
    });
    
    // Add hover sound effect (optional)
    document.querySelectorAll('.land-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            if (!this.classList.contains('selected')) {
                this.style.transform = 'translateY(0) scale(1)';
            }
        });
        
        card.addEventListener('click', function() {
            // Add click ripple effect
            const ripple = document.createElement('div');
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(16, 185, 129, 0.3);
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
                left: 50%;
                top: 50%;
                width: 100px;
                height: 100px;
                margin-left: -50px;
                margin-top: -50px;
            `;
            
            this.style.position = 'relative';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});

// Add CSS animation for ripple effect
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush