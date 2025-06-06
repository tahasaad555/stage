@extends('layouts.admin')

@section('title', 'Edit Agricultural Land')

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

.custom-marker {
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

.map-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 500px;
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    border-radius: 12px;
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

.card-animation {
    animation: slideUp 0.6s ease-out;
}

/* Form field enhancements */
.form-field {
    transition: all 0.3s ease;
}

/* Loading state */
.loading {
    opacity: 0.6;
    pointer-events: none;
    position: relative;
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
    border-top: 2px solid #10b981;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-green-600 to-teal-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <a href="{{ route('admin.lands.show', $land) }}" 
                       class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4 transition-all">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-edit text-3xl"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold mb-2">🌾 Edit Agricultural Land</h1>
                            <p class="text-xl opacity-90">Update "{{ $land->title }}" information in Morocco</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-map-marked-alt mr-2"></i>
                        <span>Property Update</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <span>Secure Modification</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-history mr-2"></i>
                        <span>Change Tracking</span>
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

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.lands.update', $land) }}" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Basic Information Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl card-animation">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-info-circle text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Basic Information</h3>
                            <p class="text-gray-600">Update property details and specifications</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="form-field">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag mr-2 text-blue-500"></i>Property Title *
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $land->title) }}" required
                                   placeholder="Enter property title..."
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('title') border-red-300 ring-red-100 @enderror">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Surface -->
                        <div class="form-field">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-ruler-combined mr-2 text-purple-500"></i>Surface Area (hectares) *
                            </label>
                            <input type="number" name="surface" id="surface" value="{{ old('surface', $land->surface) }}" step="0.01" min="0" required
                                   placeholder="0.00"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('surface') border-red-300 ring-red-100 @enderror">
                            @error('surface')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="form-field">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-dollar-sign mr-2 text-green-500"></i>Price (MAD) *
                            </label>
                            <input type="number" name="price" id="price" value="{{ old('price', $land->price) }}" step="0.01" min="0" required
                                   placeholder="0.00"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('price') border-red-300 ring-red-100 @enderror">
                            <div class="mt-1 text-xs text-gray-500">
                                Price per hectare: <span id="price-per-hectare" class="font-semibold text-green-600">{{ $land->surface > 0 ? number_format($land->price / $land->surface, 0) : '0' }} MAD/ha</span>
                            </div>
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="form-field">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-toggle-on mr-2 text-yellow-500"></i>Property Status *
                            </label>
                            <select name="status" id="status" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all @error('status') border-red-300 ring-red-100 @enderror">
                                <option value="available" {{ old('status', $land->status) === 'available' ? 'selected' : '' }}>🟢 Available</option>
                                <option value="reserved" {{ old('status', $land->status) === 'reserved' ? 'selected' : '' }}>🟡 Reserved</option>
                                <option value="sold" {{ old('status', $land->status) === 'sold' ? 'selected' : '' }}>🔴 Sold</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Soil Type -->
                        <div class="md:col-span-2 form-field">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-mountain mr-2 text-brown-500"></i>Soil Type
                            </label>
                            <select name="soil_type" id="soil_type" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all @error('soil_type') border-red-300 ring-red-100 @enderror">
                                <option value="">Select soil type...</option>
                                <option value="Clay" {{ old('soil_type', $land->soil_type) === 'Clay' ? 'selected' : '' }}>🏺 Clay</option>
                                <option value="Sandy" {{ old('soil_type', $land->soil_type) === 'Sandy' ? 'selected' : '' }}>🏖️ Sandy</option>
                                <option value="Loamy" {{ old('soil_type', $land->soil_type) === 'Loamy' ? 'selected' : '' }}>🌱 Loamy</option>
                                <option value="Silty" {{ old('soil_type', $land->soil_type) === 'Silty' ? 'selected' : '' }}>💧 Silty</option>
                                <option value="Rocky" {{ old('soil_type', $land->soil_type) === 'Rocky' ? 'selected' : '' }}>🪨 Rocky</option>
                                <option value="Alluvial" {{ old('soil_type', $land->soil_type) === 'Alluvial' ? 'selected' : '' }}>🌊 Alluvial</option>
                            </select>
                            @error('soil_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Information Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl card-animation">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-map-marker-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Location & Region</h3>
                            <p class="text-gray-600">Property location within Morocco</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Region -->
                        <div class="form-field">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map mr-2 text-red-500"></i>Morocco Region *
                            </label>
                            <select name="region" id="region" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all @error('region') border-red-300 ring-red-100 @enderror">
                                <option value="">Select a region...</option>
                                <option value="Tanger-Tétouan-Al Hoceïma" {{ old('region', $land->region) === 'Tanger-Tétouan-Al Hoceïma' ? 'selected' : '' }}>🏔️ Tanger-Tétouan-Al Hoceïma</option>
                                <option value="Oriental" {{ old('region', $land->region) === 'Oriental' ? 'selected' : '' }}>🌅 Oriental</option>
                                <option value="Fès-Meknès" {{ old('region', $land->region) === 'Fès-Meknès' ? 'selected' : '' }}>🏛️ Fès-Meknès</option>
                                <option value="Rabat-Salé-Kénitra" {{ old('region', $land->region) === 'Rabat-Salé-Kénitra' ? 'selected' : '' }}>🏛️ Rabat-Salé-Kénitra</option>
                                <option value="Béni Mellal-Khénifra" {{ old('region', $land->region) === 'Béni Mellal-Khénifra' ? 'selected' : '' }}>⛰️ Béni Mellal-Khénifra</option>
                                <option value="Casablanca-Settat" {{ old('region', $land->region) === 'Casablanca-Settat' ? 'selected' : '' }}>🏙️ Casablanca-Settat</option>
                                <option value="Marrakech-Safi" {{ old('region', $land->region) === 'Marrakech-Safi' ? 'selected' : '' }}>🕌 Marrakech-Safi</option>
                                <option value="Drâa-Tafilalet" {{ old('region', $land->region) === 'Drâa-Tafilalet' ? 'selected' : '' }}>🏜️ Drâa-Tafilalet</option>
                                <option value="Souss-Massa" {{ old('region', $land->region) === 'Souss-Massa' ? 'selected' : '' }}>🌊 Souss-Massa</option>
                                <option value="Guelmim-Oued Noun" {{ old('region', $land->region) === 'Guelmim-Oued Noun' ? 'selected' : '' }}>🐪 Guelmim-Oued Noun</option>
                                <option value="Laâyoune-Sakia El Hamra" {{ old('region', $land->region) === 'Laâyoune-Sakia El Hamra' ? 'selected' : '' }}>🏖️ Laâyoune-Sakia El Hamra</option>
                                <option value="Dakhla-Oued Ed-Dahab" {{ old('region', $land->region) === 'Dakhla-Oued Ed-Dahab' ? 'selected' : '' }}>🌴 Dakhla-Oued Ed-Dahab</option>
                            </select>
                            @error('region')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div class="form-field">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-flag mr-2 text-green-500"></i>Country
                            </label>
                            <input type="text" name="country" id="country" value="Morocco" readonly
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-600 cursor-not-allowed">
                            <div class="mt-1 text-xs text-gray-500 flex items-center">
                                <span class="mr-2">🇲🇦</span>
                                <span>Kingdom of Morocco</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl card-animation">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-file-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Property Description</h3>
                            <p class="text-gray-600">Detailed information about the land</p>
                        </div>
                    </div>

                    <div class="form-field">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-indigo-500"></i>Description *
                        </label>
                        <textarea name="description" id="description" rows="5" required
                                  placeholder="Describe the agricultural land, its features, potential uses, accessibility, and any other relevant details..."
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none @error('description') border-red-300 ring-red-100 @enderror">{{ old('description', $land->description) }}</textarea>
                        <div class="mt-1 text-xs text-gray-500">
                            <span id="char-count">{{ strlen($land->description) }}</span>/1000 characters
                        </div>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Interactive Map Section -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl card-animation">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-map text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">
                                🗺️ Interactive Location Map
                            </h3>
                            <p class="text-gray-600">Precise geolocation within Morocco</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-xl p-6 space-y-6 border border-green-200">
                        <!-- GPS Coordinates Input -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-field">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-crosshairs mr-2 text-green-500"></i>GPS Coordinates
                                </label>
                                <input type="text" name="gps_coordinates" id="gps_coordinates" value="{{ old('gps_coordinates', $land->gps_coordinates) }}" 
                                       placeholder="e.g., 31.7917, -7.0926 (Marrakech)"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('gps_coordinates') border-red-300 ring-red-100 @enderror">
                                @error('gps_coordinates')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-end space-x-3">
                                <button type="button" id="locate-btn" 
                                        class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-md">
                                    <i class="fas fa-location-arrow mr-2"></i>
                                    Use My Location
                                </button>
                                <button type="button" id="center-morocco-btn" 
                                        class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-md">
                                    <i class="fas fa-search mr-2"></i>
                                    Center Morocco
                                </button>
                            </div>
                        </div>

                        <!-- Interactive Map -->
                        <div class="border-2 border-gray-300 rounded-xl overflow-hidden shadow-lg">
                            <div id="map-loading" class="map-loading">
                                <div class="text-center">
                                    <i class="fas fa-spinner fa-spin text-4xl text-green-600 mb-4"></i>
                                    <p class="text-lg text-gray-700 font-semibold">Loading Morocco Map...</p>
                                    <p class="text-sm text-gray-500">Preparing interactive location selector</p>
                                </div>
                            </div>
                            <div id="map" style="height: 500px; width: 100%; display: none;"></div>
                        </div>
                        
                        <div class="flex items-center justify-between text-sm bg-white rounded-xl p-4 shadow-inner">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                <span>Click on the map to update location, or modify coordinates manually</span>
                            </div>
                            <div id="selected-coordinates" class="font-mono text-sm bg-gradient-to-r from-green-100 to-blue-100 px-3 py-2 rounded-lg border border-green-200 shadow-sm">
                                {{ $land->gps_coordinates ?: 'No location set' }}
                            </div>
                        </div>
                        
                        <!-- Quick Location Buttons -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <button type="button" onclick="goToCity('Casablanca', 33.5731, -7.5898)" 
                                    class="flex items-center justify-center px-3 py-3 text-sm bg-white border-2 border-gray-200 rounded-xl transition-all">
                                <span class="mr-2">📍</span>Casablanca
                            </button>
                            <button type="button" onclick="goToCity('Marrakech', 31.7917, -7.0926)" 
                                    class="flex items-center justify-center px-3 py-3 text-sm bg-white border-2 border-gray-200 rounded-xl transition-all">
                                <span class="mr-2">📍</span>Marrakech
                            </button>
                            <button type="button" onclick="goToCity('Rabat', 34.0209, -6.8417)" 
                                    class="flex items-center justify-center px-3 py-3 text-sm bg-white border-2 border-gray-200 rounded-xl transition-all">
                                <span class="mr-2">📍</span>Rabat
                            </button>
                            <button type="button" onclick="goToCity('Fès', 34.0181, -5.0078)" 
                                    class="flex items-center justify-center px-3 py-3 text-sm bg-white border-2 border-gray-200 rounded-xl transition-all">
                                <span class="mr-2">📍</span>Fès
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6">
                    <a href="{{ route('admin.lands.show', $land) }}" 
                       class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl transition-all flex items-center shadow-md">
                        <i class="fas fa-arrow-left mr-2"></i>Cancel & Go Back
                    </a>
                    
                    <div class="flex items-center space-x-4">
                        <button type="button" onclick="resetForm()" 
                                class="bg-yellow-500 text-white px-6 py-3 rounded-xl transition-all flex items-center shadow-md">
                            <i class="fas fa-undo mr-2"></i>Reset Changes
                        </button>
                        <button type="submit" 
                                class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-8 py-3 rounded-xl transition-all flex items-center shadow-lg">
                            <i class="fas fa-save mr-2"></i>Update Property
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar - Information & Guidelines -->
        <div class="space-y-6">
            <!-- Current Property Info -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-animation">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Current Property Info</h4>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Current Price:</span>
                        <span class="font-semibold text-green-600">{{ number_format($land->price) }} MAD</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Surface Area:</span>
                        <span class="font-semibold">{{ number_format($land->surface, 2) }} ha</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Price/Hectare:</span>
                        <span class="font-semibold text-blue-600">{{ number_format($land->price / max($land->surface, 1), 0) }} MAD/ha</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Updated:</span>
                        <span class="font-semibold">{{ $land->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Edit Guidelines -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-animation">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-lightbulb text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Edit Guidelines</h4>
                </div>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Verify location coordinates are within Morocco boundaries</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Price changes affect all related calculations automatically</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Status changes impact property visibility</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Use the map for precise location selection</p>
                    </div>
                </div>
            </div>

            <!-- Morocco Regions Info -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-animation">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-map text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Morocco Regions</h4>
                </div>
                <div class="space-y-2 text-xs">
                    <div class="bg-blue-50 p-2 rounded-lg">
                        <div class="font-semibold text-blue-800">Northern Morocco</div>
                        <div class="text-blue-600">Tanger-Tétouan, Oriental, Fès-Meknès</div>
                    </div>
                    <div class="bg-green-50 p-2 rounded-lg">
                        <div class="font-semibold text-green-800">Central Morocco</div>
                        <div class="text-green-600">Rabat-Salé, Casablanca-Settat</div>
                    </div>
                    <div class="bg-purple-50 p-2 rounded-lg">
                        <div class="font-semibold text-purple-800">Southern Morocco</div>
                        <div class="text-purple-600">Marrakech-Safi, Souss-Massa</div>
                    </div>
                    <div class="bg-yellow-50 p-2 rounded-lg">
                        <div class="font-semibold text-yellow-800">Saharan Regions</div>
                        <div class="text-yellow-600">Laâyoune, Dakhla-Oued Ed-Dahab</div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-animation">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Property Analytics</h4>
                </div>
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-3 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ \App\Models\TerreAgricole::where('region', $land->region)->count() }}</div>
                        <div class="text-xs text-gray-600">Properties in Region</div>
                    </div>
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-3 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ \App\Models\TerreAgricole::where('status', 'available')->count() }}</div>
                        <div class="text-xs text-gray-600">Available Properties</div>
                    </div>
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-3 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">{{ number_format(\App\Models\TerreAgricole::avg('price'), 0) }}</div>
                        <div class="text-xs text-gray-600">Avg Price (MAD)</div>
                    </div>
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-3 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">{{ number_format(\App\Models\TerreAgricole::avg('surface'), 1) }}</div>
                        <div class="text-xs text-gray-600">Avg Size (ha)</div>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-animation">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-orange-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-shield-alt text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Security & Changes</h4>
                </div>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-history text-blue-500 mt-1"></i>
                        <p>All modifications are logged with timestamps</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-user-shield text-green-500 mt-1"></i>
                        <p>Changes are attributed to your admin account</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-bell text-yellow-500 mt-1"></i>
                        <p>Related users will be notified of updates</p>
                    </div>
                </div>
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
// Initialize map
let map;
let marker;
let isMapInitialized = false;

// Morocco center coordinates and bounds
const moroccoBounds = [
    [21.0, -17.5], // Southwest corner
    [36.0, -1.0]   // Northeast corner
];
const moroccoCenter = [31.7917, -7.0926]; // Marrakech coordinates

// Morocco regions with their approximate centers
const moroccoRegions = {
    'Tanger-Tétouan-Al Hoceïma': { lat: 35.1667, lng: -5.2667, bounds: [[34.0, -6.5], [36.0, -3.5]] },
    'Oriental': { lat: 34.6833, lng: -2.9333, bounds: [[32.0, -4.5], [35.5, -1.0]] },
    'Fès-Meknès': { lat: 33.9333, lng: -5.0000, bounds: [[32.5, -6.0], [35.0, -3.5]] },
    'Rabat-Salé-Kénitra': { lat: 34.2583, lng: -6.6056, bounds: [[33.5, -7.5], [35.0, -5.5]] },
    'Béni Mellal-Khénifra': { lat: 32.3372, lng: -6.3498, bounds: [[31.5, -7.5], [33.5, -4.5]] },
    'Casablanca-Settat': { lat: 33.3000, lng: -7.5833, bounds: [[32.0, -9.0], [34.0, -6.0]] },
    'Marrakech-Safi': { lat: 31.6295, lng: -7.9811, bounds: [[30.5, -10.0], [32.5, -6.0]] },
    'Drâa-Tafilalet': { lat: 31.9314, lng: -4.4407, bounds: [[29.5, -7.0], [33.0, -2.0]] },
    'Souss-Massa': { lat: 30.4167, lng: -9.5833, bounds: [[29.0, -11.0], [31.5, -7.5]] },
    'Guelmim-Oued Noun': { lat: 28.9833, lng: -10.0667, bounds: [[27.5, -12.5], [30.0, -8.0]] },
    'Laâyoune-Sakia El Hamra': { lat: 27.1536, lng: -13.2033, bounds: [[25.5, -15.0], [28.5, -11.0]] },
    'Dakhla-Oued Ed-Dahab': { lat: 23.7167, lng: -15.9333, bounds: [[21.0, -17.5], [26.0, -14.0]] }
};

function initializeMap() {
    if (isMapInitialized) return;
    
    // Hide loading, show map
    document.getElementById('map-loading').style.display = 'none';
    document.getElementById('map').style.display = 'block';
    
    // Get existing coordinates or use default
    const existingCoords = document.getElementById('gps_coordinates').value;
    let initialLat = moroccoCenter[0];
    let initialLng = moroccoCenter[1];
    let initialZoom = 6;
    
    if (existingCoords) {
        const coords = existingCoords.split(',').map(s => parseFloat(s.trim()));
        if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
            initialLat = coords[0];
            initialLng = coords[1];
            initialZoom = 12;
        }
    }
    
    // Initialize map
    map = L.map('map', {
        maxBounds: moroccoBounds,
        maxBoundsViscosity: 1.0,
        minZoom: 5,
        maxZoom: 18
    }).setView([initialLat, initialLng], initialZoom);
    
    // Add high-quality map tiles
    L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18,
        className: 'map-tiles'
    }).addTo(map);
    
    // Add click event to map
    map.on('click', function(e) {
        updateLocationSelection(e.latlng.lat, e.latlng.lng);
    });
    
    // Initialize with existing coordinates if available
    if (existingCoords) {
        parseAndSetCoordinates(existingCoords);
    }
    
    isMapInitialized = true;
}

function updateLocationSelection(lat, lng) {
    // Check if coordinates are within Morocco bounds
    if (lat < 21.0 || lat > 36.0 || lng < -17.5 || lng > -1.0) {
        return;
    }
    
    // Round to 6 decimal places for precision
    const roundedLat = Math.round(lat * 1000000) / 1000000;
    const roundedLng = Math.round(lng * 1000000) / 1000000;
    
    // Update the input field
    document.getElementById('gps_coordinates').value = `${roundedLat}, ${roundedLng}`;
    
    // Update the display
    document.getElementById('selected-coordinates').textContent = `${roundedLat}, ${roundedLng}`;
    
    // Update or create marker with custom styling
    if (marker) {
        marker.setLatLng([roundedLat, roundedLng]);
    } else {
        // Create custom icon
        const customIcon = L.divIcon({
            html: '<div class="w-5 h-5 bg-green-600 border-3 border-white rounded-full shadow-lg"></div>',
            className: 'custom-marker',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
        
        marker = L.marker([roundedLat, roundedLng], {
            icon: customIcon,
            draggable: true
        }).addTo(map);
        
        // Make marker draggable
        marker.on('dragend', function(e) {
            const position = e.target.getLatLng();
            updateLocationSelection(position.lat, position.lng);
        });
    }
    
    // Add enhanced popup to marker
    marker.bindPopup(`
        <div class="text-center p-3 min-w-0" style="min-width: 250px;">
            <div class="flex items-center justify-center mb-3">
                <i class="fas fa-map-marker-alt text-green-600 mr-2 text-lg"></i>
                <strong class="text-gray-800 text-base">{{ $land->title }}</strong>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Location:</span>
                    <span class="text-gray-800">Morocco 🇲🇦</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Coordinates:</span>
                    <span class="text-blue-600 font-mono text-xs">${roundedLat}, ${roundedLng}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Surface:</span>
                    <span class="text-green-600 font-semibold">{{ number_format($land->surface, 1) }} ha</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Price:</span>
                    <span class="text-purple-600 font-semibold">{{ number_format($land->price) }} MAD</span>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t text-xs text-green-600">
                📍 Click and drag to adjust location
            </div>
        </div>
    `).openPopup();
    
    // Auto-detect region
    updateRegionSuggestion(roundedLat, roundedLng);
}

function parseAndSetCoordinates(coordString) {
    const coords = coordString.split(',').map(s => parseFloat(s.trim()));
    if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
        const [lat, lng] = coords;
        if (isMapInitialized) {
            map.setView([lat, lng], 12);
        }
        updateLocationSelection(lat, lng);
    }
}

function goToCity(cityName, lat, lng) {
    if (map) {
        map.setView([lat, lng], 10);
        updateLocationSelection(lat, lng);
    }
}

// Auto-suggest region based on coordinates
function updateRegionSuggestion(lat, lng) {
    const regionSelect = document.getElementById('region');
    
    for (const [regionName, regionData] of Object.entries(moroccoRegions)) {
        const bounds = regionData.bounds;
        if (lat >= bounds[0][0] && lat <= bounds[1][0] &&
            lng >= bounds[0][1] && lng <= bounds[1][1]) {
            
            if (!regionSelect.value || confirm(`Update region to "${regionName}" based on selected location?`)) {
                regionSelect.value = regionName;
                regionSelect.style.backgroundColor = '#f0fdf4'; // Light green highlight
                setTimeout(() => {
                    regionSelect.style.backgroundColor = '';
                }, 2000);
            }
            break;
        }
    }
}

// Calculate price per hectare dynamically
function updatePricePerHectare() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const surface = parseFloat(document.getElementById('surface').value) || 1;
    const pricePerHectare = surface > 0 ? price / surface : 0;
    
    document.getElementById('price-per-hectare').textContent = `${pricePerHectare.toLocaleString()} MAD/ha`;
}

// Character counter for description
function updateCharCount() {
    const description = document.getElementById('description');
    const charCount = document.getElementById('char-count');
    const currentLength = description.value.length;
    
    charCount.textContent = currentLength;
    
    if (currentLength > 800) {
        charCount.style.color = '#ef4444';
    } else if (currentLength > 600) {
        charCount.style.color = '#f59e0b';
    } else {
        charCount.style.color = '#10b981';
    }
}

// Reset form function
function resetForm() {
    if (confirm('Are you sure you want to reset all changes? This will restore the original values.')) {
        location.reload();
    }
}

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map with delay
    setTimeout(initializeMap, 100);
    
    // Add event listeners
    const priceInput = document.getElementById('price');
    const surfaceInput = document.getElementById('surface');
    const descriptionTextarea = document.getElementById('description');
    const coordsInput = document.getElementById('gps_coordinates');
    const form = document.querySelector('form');
    
    // Price per hectare calculator
    priceInput.addEventListener('input', updatePricePerHectare);
    surfaceInput.addEventListener('input', updatePricePerHectare);
    
    // Character counter
    descriptionTextarea.addEventListener('input', updateCharCount);
    
    // Manual coordinate input
    coordsInput.addEventListener('blur', function(e) {
        const value = e.target.value.trim();
        if (value) {
            parseAndSetCoordinates(value);
        }
    });
    
    // Form submission enhancement
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating Property...';
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        
        // Re-enable if form submission fails
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
            }
        }, 10000);
    });
    
    // Animate cards on load
    const cards = document.querySelectorAll('.card-animation');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Initialize calculations
    updatePricePerHectare();
    updateCharCount();
});

// Center Morocco button
document.getElementById('center-morocco-btn').addEventListener('click', function() {
    if (map) {
        map.setView(moroccoCenter, 6);
    }
});

// Handle geolocation
document.getElementById('locate-btn').addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;
    
    if (!navigator.geolocation) {
        return;
    }
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Locating...';
    btn.disabled = true;
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Check if user is in Morocco
            if (lat >= 21.0 && lat <= 36.0 && lng >= -17.5 && lng <= -1.0) {
                map.setView([lat, lng], 15);
                updateLocationSelection(lat, lng);
            } else {
                map.setView(moroccoCenter, 6);
            }
            
            btn.innerHTML = originalText;
            btn.disabled = false;
        },
        function(error) {
            btn.innerHTML = originalText;
            btn.disabled = false;
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
});

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
            window.location.href = '{{ route("admin.lands.show", $land) }}';
        }
    }
});

// Auto-save draft functionality (bonus feature)
let formChanged = false;
let autoSaveInterval;

function enableAutoSave() {
    const formInputs = document.querySelectorAll('form input, form select, form textarea');
    formInputs.forEach(input => {
        input.addEventListener('change', () => {
            formChanged = true;
        });
    });

    autoSaveInterval = setInterval(() => {
        if (formChanged) {
            saveDraft();
            formChanged = false;
        }
    }, 30000); // Auto-save every 30 seconds
}

function saveDraft() {
    const formData = new FormData(document.querySelector('form'));
    const draftData = {};
    formData.forEach((value, key) => {
        draftData[key] = value;
    });
    
    localStorage.setItem('land_edit_draft_{{ $land->id }}', JSON.stringify(draftData));
}

// Load draft on page load
function loadDraft() {
    const draftData = localStorage.getItem('land_edit_draft_{{ $land->id }}');
    if (draftData) {
        try {
            const data = JSON.parse(draftData);
            
            if (confirm('A draft of your changes was found. Would you like to restore it?')) {
                const form = document.querySelector('form');
                
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
                
                // Trigger calculations after loading draft
                updatePricePerHectare();
                updateCharCount();
            } else {
                localStorage.removeItem('land_edit_draft_{{ $land->id }}');
            }
        } catch (e) {
            localStorage.removeItem('land_edit_draft_{{ $land->id }}');
        }
    }
}

// Clear draft on successful submission
document.querySelector('form').addEventListener('submit', function() {
    localStorage.removeItem('land_edit_draft_{{ $land->id }}');
    clearInterval(autoSaveInterval);
});

// Initialize auto-save after DOM is loaded
setTimeout(() => {
    enableAutoSave();
    loadDraft();
}, 1000);

// Cleanup when page unloads
window.addEventListener('beforeunload', function(e) {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
    }
    clearInterval(autoSaveInterval);
});
</script>
@endpush