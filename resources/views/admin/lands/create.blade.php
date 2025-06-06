@extends('layouts.admin')

@section('title', 'Add New Agricultural Land')

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

.custom-marker {
    background: linear-gradient(135deg, #10b981, #059669);
    border: 3px solid white;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.map-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 400px;
    background: #f3f4f6;
    border-radius: 12px;
}

/* Removed hover animations to prevent resolution issues */
</style>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <a href="{{ route('admin.lands.index') }}" class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4 hover:bg-opacity-30 transition-all">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">🌾 Add New Agricultural Land</h1>
                        <p class="text-xl opacity-90">Create a premium agricultural property listing in Morocco</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-map-marked-alt mr-2"></i>
                        <span>Location Mapping</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-seedling mr-2"></i>
                        <span>Property Details</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-dollar-sign mr-2"></i>
                        <span>Market Pricing</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-plus-circle text-6xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.lands.store') }}" class="space-y-8">
                @csrf
                
                <!-- Basic Information Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-info-circle text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Basic Information</h3>
                            <p class="text-gray-600">Property details and identification</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag mr-2 text-blue-500"></i>Property Title *
                            </label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                   placeholder="e.g., Premium Farmland in Marrakech..."
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('title') border-red-300 ring-red-100 @enderror">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Surface -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-ruler-combined mr-2 text-purple-500"></i>Surface Area (hectares) *
                            </label>
                            <input type="number" name="surface" id="surface-input" value="{{ old('surface') }}" step="0.01" min="0" required
                                   placeholder="e.g., 5.5"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('surface') border-red-300 ring-red-100 @enderror">
                            @error('surface')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-dollar-sign mr-2 text-green-500"></i>Price (MAD) *
                            </label>
                            <input type="number" name="price" id="price-input" value="{{ old('price') }}" step="0.01" min="0" required
                                   placeholder="e.g., 500000"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('price') border-red-300 ring-red-100 @enderror">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-toggle-on mr-2 text-yellow-500"></i>Property Status *
                            </label>
                            <select name="status" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all @error('status') border-red-300 ring-red-100 @enderror">
                                <option value="">Select status...</option>
                                <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>🟢 Available for Sale</option>
                                <option value="reserved" {{ old('status') === 'reserved' ? 'selected' : '' }}>🟡 Reserved</option>
                                <option value="sold" {{ old('status') === 'sold' ? 'selected' : '' }}>🔴 Sold</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-indigo-500"></i>Property Description *
                        </label>
                        <textarea name="description" rows="4" required
                                  placeholder="Describe the agricultural land, its features, soil quality, water access, crops grown, etc..."
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none @error('description') border-red-300 ring-red-100 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location & Details Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-map-marker-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Location & Property Details</h3>
                            <p class="text-gray-600">Geographic and agricultural specifications</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Region -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map mr-2 text-red-500"></i>Morocco Region *
                            </label>
                            <select name="region" id="region-select" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all @error('region') border-red-300 ring-red-100 @enderror">
                                <option value="">Select a region...</option>
                                <option value="Tanger-Tétouan-Al Hoceïma" {{ old('region') === 'Tanger-Tétouan-Al Hoceïma' ? 'selected' : '' }}>🗺️ Tanger-Tétouan-Al Hoceïma</option>
                                <option value="Oriental" {{ old('region') === 'Oriental' ? 'selected' : '' }}>🗺️ Oriental</option>
                                <option value="Fès-Meknès" {{ old('region') === 'Fès-Meknès' ? 'selected' : '' }}>🗺️ Fès-Meknès</option>
                                <option value="Rabat-Salé-Kénitra" {{ old('region') === 'Rabat-Salé-Kénitra' ? 'selected' : '' }}>🗺️ Rabat-Salé-Kénitra</option>
                                <option value="Béni Mellal-Khénifra" {{ old('region') === 'Béni Mellal-Khénifra' ? 'selected' : '' }}>🗺️ Béni Mellal-Khénifra</option>
                                <option value="Casablanca-Settat" {{ old('region') === 'Casablanca-Settat' ? 'selected' : '' }}>🗺️ Casablanca-Settat</option>
                                <option value="Marrakech-Safi" {{ old('region') === 'Marrakech-Safi' ? 'selected' : '' }}>🗺️ Marrakech-Safi</option>
                                <option value="Drâa-Tafilalet" {{ old('region') === 'Drâa-Tafilalet' ? 'selected' : '' }}>🗺️ Drâa-Tafilalet</option>
                                <option value="Souss-Massa" {{ old('region') === 'Souss-Massa' ? 'selected' : '' }}>🗺️ Souss-Massa</option>
                                <option value="Guelmim-Oued Noun" {{ old('region') === 'Guelmim-Oued Noun' ? 'selected' : '' }}>🗺️ Guelmim-Oued Noun</option>
                                <option value="Laâyoune-Sakia El Hamra" {{ old('region') === 'Laâyoune-Sakia El Hamra' ? 'selected' : '' }}>🗺️ Laâyoune-Sakia El Hamra</option>
                                <option value="Dakhla-Oued Ed-Dahab" {{ old('region') === 'Dakhla-Oued Ed-Dahab' ? 'selected' : '' }}>🗺️ Dakhla-Oued Ed-Dahab</option>
                            </select>
                            @error('region')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-flag mr-2 text-blue-500"></i>Country
                            </label>
                            <input type="text" name="country" value="Morocco" readonly
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>

                        <!-- Soil Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-mountain mr-2 text-amber-500"></i>Soil Type
                            </label>
                            <select name="soil_type" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all @error('soil_type') border-red-300 ring-red-100 @enderror">
                                <option value="">Select soil type...</option>
                                <option value="Clay" {{ old('soil_type') === 'Clay' ? 'selected' : '' }}>🟤 Clay</option>
                                <option value="Sandy" {{ old('soil_type') === 'Sandy' ? 'selected' : '' }}>🟨 Sandy</option>
                                <option value="Loamy" {{ old('soil_type') === 'Loamy' ? 'selected' : '' }}>🟫 Loamy</option>
                                <option value="Silty" {{ old('soil_type') === 'Silty' ? 'selected' : '' }}>⚫ Silty</option>
                                <option value="Rocky" {{ old('soil_type') === 'Rocky' ? 'selected' : '' }}>🗿 Rocky</option>
                                <option value="Alluvial" {{ old('soil_type') === 'Alluvial' ? 'selected' : '' }}>🌊 Alluvial</option>
                            </select>
                            @error('soil_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- GPS Coordinates Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-crosshairs mr-2 text-emerald-500"></i>GPS Coordinates
                            </label>
                            <input type="text" name="gps_coordinates" id="gps_coordinates" value="{{ old('gps_coordinates') }}" 
                                   placeholder="e.g., 31.7917, -7.0926 (Marrakech)"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('gps_coordinates') border-red-300 ring-red-100 @enderror">
                            @error('gps_coordinates')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Interactive Location Map Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-map text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">Interactive Location Selection</h3>
                                <p class="text-gray-600">Pin your property location on the Morocco map</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" id="locate-btn" 
                                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 flex items-center">
                                <i class="fas fa-location-arrow mr-2"></i>
                                My Location
                            </button>
                            <button type="button" id="center-morocco-btn" 
                                    class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 flex items-center">
                                <i class="fas fa-search mr-2"></i>
                                Center Map
                            </button>
                        </div>
                    </div>

                    <!-- Map Container -->
                    <div class="border-2 border-gray-300 rounded-xl overflow-hidden shadow-lg">
                        <div id="map-loading" class="map-loading">
                            <div class="text-center">
                                <i class="fas fa-spinner fa-spin text-3xl text-green-600 mb-2"></i>
                                <p class="text-gray-600">Loading Morocco map...</p>
                            </div>
                        </div>
                        <div id="map" style="height: 500px; width: 100%; display: none;"></div>
                    </div>
                    
                    <div class="mt-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Selected Coordinates</p>
                                <p id="selected-coordinates" class="text-sm font-mono text-gray-900 bg-white px-2 py-1 rounded mt-1">Click map to select</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Estimated Area</p>
                                <p id="estimated-area" class="text-sm font-semibold text-green-600 mt-1">Based on surface input</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Price per Hectare</p>
                                <p id="price-per-hectare" class="text-sm font-semibold text-blue-600 mt-1">Auto-calculated</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Location Buttons -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-4">
                        <button type="button" onclick="goToCity('Casablanca', 33.5731, -7.5898)" 
                                class="px-3 py-2 text-xs bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            📍 Casablanca
                        </button>
                        <button type="button" onclick="goToCity('Marrakech', 31.7917, -7.0926)" 
                                class="px-3 py-2 text-xs bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            📍 Marrakech
                        </button>
                        <button type="button" onclick="goToCity('Rabat', 34.0209, -6.8417)" 
                                class="px-3 py-2 text-xs bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            📍 Rabat
                        </button>
                        <button type="button" onclick="goToCity('Fès', 34.0181, -5.0078)" 
                                class="px-3 py-2 text-xs bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            📍 Fès
                        </button>
                    </div>

                    <!-- Map Instructions -->
                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mr-3 mt-1"></i>
                            <div class="text-sm text-blue-700">
                                <p class="font-semibold mb-1">How to use the map:</p>
                                <ul class="space-y-1 text-xs">
                                    <li>• Click anywhere on the map to set your property location</li>
                                    <li>• Drag the marker to fine-tune the position</li>
                                    <li>• Use the quick city buttons for common locations</li>
                                    <li>• Coordinates will automatically update in the form</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6">
                    <a href="{{ route('admin.lands.index') }}" 
                       class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl hover:bg-gray-300 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Cancel & Go Back
                    </a>
                    
                    <div class="flex items-center space-x-4">
                        <button type="button" onclick="saveDraft()" 
                                class="bg-yellow-500 text-white px-6 py-3 rounded-xl hover:bg-yellow-600 flex items-center">
                            <i class="fas fa-save mr-2"></i>Save Draft
                        </button>
                        <button type="submit" 
                                class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-3 rounded-xl hover:from-green-600 hover:to-emerald-700 flex items-center shadow-lg">
                            <i class="fas fa-plus-circle mr-2"></i>Create Property
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar - Instructions & Help -->
        <div class="space-y-6">
            <!-- Property Creation Tips -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-lightbulb text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Property Tips</h4>
                </div>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Use descriptive titles that highlight key features</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Accurate surface measurements increase buyer trust</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Detailed descriptions improve search visibility</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Precise GPS coordinates enhance property credibility</p>
                    </div>
                </div>
            </div>

            <!-- Pricing Guidelines -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-teal-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-dollar-sign text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Pricing Guidelines</h4>
                </div>
                <div class="space-y-4">
                    <div class="border-l-4 border-green-400 pl-4">
                        <h5 class="font-semibold text-green-700">💰 Market Rates</h5>
                        <p class="text-xs text-gray-600">Research local agricultural land prices per hectare</p>
                    </div>
                    <div class="border-l-4 border-blue-400 pl-4">
                        <h5 class="font-semibold text-blue-700">📊 Factors to Consider</h5>
                        <p class="text-xs text-gray-600">Location, soil quality, water access, infrastructure</p>
                    </div>
                    <div class="border-l-4 border-purple-400 pl-4">
                        <h5 class="font-semibold text-purple-700">📈 Price Calculation</h5>
                        <p class="text-xs text-gray-600">Price per hectare automatically calculated</p>
                    </div>
                </div>
            </div>

            <!-- Morocco Agricultural Regions -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-map text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Agricultural Regions</h4>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Casablanca-Settat:</span>
                        <span class="font-semibold text-green-600">Cereals, Citrus</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Marrakech-Safi:</span>
                        <span class="font-semibold text-orange-600">Olives, Argan</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Souss-Massa:</span>
                        <span class="font-semibold text-yellow-600">Vegetables, Fruits</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Fès-Meknès:</span>
                        <span class="font-semibold text-purple-600">Grains, Livestock</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Oriental:</span>
                        <span class="font-semibold text-indigo-600">Livestock, Cereals</span>
                    </div>
                </div>
            </div>

            <!-- Market Statistics -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Market Statistics</h4>
                </div>
                <div class="space-y-4">
                    <div class="text-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                        <div class="text-2xl font-bold text-green-600">{{ \App\Models\TerreAgricole::count() }}</div>
                        <div class="text-xs text-gray-600">Total Properties</div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl">
                            <div class="text-lg font-bold text-blue-600">{{ \App\Models\TerreAgricole::where('status', 'available')->count() }}</div>
                            <div class="text-xs text-gray-600">Available</div>
                        </div>
                        <div class="text-center p-3 bg-gradient-to-r from-yellow-50 to-orange-100 rounded-xl">
                            <div class="text-lg font-bold text-orange-600">{{ number_format(\App\Models\TerreAgricole::avg('price') ?: 0, 0) }}</div>
                            <div class="text-xs text-gray-600">Avg Price MAD</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-bolt text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Quick Actions</h4>
                </div>
                <div class="space-y-3">
                    <button type="button" onclick="fillSampleData()" 
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-2 px-4 rounded-lg hover:from-blue-600 hover:to-blue-700 text-sm">
                        <i class="fas fa-magic mr-2"></i>Fill Sample Data
                    </button>
                    <button type="button" onclick="validateForm()" 
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-2 px-4 rounded-lg hover:from-green-600 hover:to-green-700 text-sm">
                        <i class="fas fa-check-circle mr-2"></i>Validate Form
                    </button>
                    <button type="button" onclick="resetForm()" 
                            class="w-full bg-gradient-to-r from-gray-500 to-gray-600 text-white py-2 px-4 rounded-lg hover:from-gray-600 hover:to-gray-700 text-sm">
                        <i class="fas fa-undo mr-2"></i>Reset Form
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>

<script>
// Initialize map variables
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
    
    // Initialize map centered on Morocco
    map = L.map('map', {
        maxBounds: moroccoBounds,
        maxBoundsViscosity: 1.0,
        minZoom: 5,
        maxZoom: 18
    }).setView(moroccoCenter, 6);
    
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
    
    // Restrict map to Morocco bounds
    map.fitBounds(moroccoBounds);
    
    // Initialize with existing coordinates if available
    const existingCoords = document.getElementById('gps_coordinates').value;
    if (existingCoords) {
        parseAndSetCoordinates(existingCoords);
    }
    
    isMapInitialized = true;
}

function updateLocationSelection(lat, lng) {
    // Check if coordinates are within Morocco bounds
    if (lat < 21.0 || lat > 36.0 || lng < -17.5 || lng > -1.0) {
        showAlert('Please select a location within Morocco.', 'error');
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
            html: '<div class="w-4 h-4 bg-green-600 border-2 border-white rounded-full shadow-lg"></div>',
            className: 'custom-marker',
            iconSize: [16, 16],
            iconAnchor: [8, 8]
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
    
    // Add popup to marker
    marker.bindPopup(`
        <div class="text-center p-2">
            <div class="flex items-center justify-center mb-2">
                <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
                <strong class="text-gray-800">Selected Location</strong>
            </div>
            <div class="text-xs text-gray-600 font-mono bg-gray-100 px-2 py-1 rounded">
                ${roundedLat}, ${roundedLng}
            </div>
            <div class="mt-2 text-xs text-green-600">
                📍 Click and drag to adjust
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
        if (map) {
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

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initializeMap, 100);
    
    // Initialize price calculations
    updateCalculations();
    
    // Add event listeners for real-time calculations
    document.getElementById('surface-input').addEventListener('input', updateCalculations);
    document.getElementById('price-input').addEventListener('input', updateCalculations);
});

// Handle manual coordinate input
document.getElementById('gps_coordinates').addEventListener('blur', function(e) {
    const value = e.target.value.trim();
    if (value) {
        parseAndSetCoordinates(value);
    }
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
        showAlert('Geolocation is not supported by this browser.', 'error');
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
                showAlert('Location found and set on map!', 'success');
            } else {
                showAlert('Your current location is outside Morocco. Please select a location within Morocco on the map.', 'error');
                map.setView(moroccoCenter, 6);
            }
            
            btn.innerHTML = originalText;
            btn.disabled = false;
        },
        function(error) {
            let errorMsg;
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMsg = "Location access denied. Please select a location on the map.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMsg = "Location information is unavailable. Please select manually.";
                    break;
                case error.TIMEOUT:
                    errorMsg = "Location request timed out. Please try again.";
                    break;
                default:
                    errorMsg = "An unknown error occurred.";
                    break;
            }
            showAlert('Error getting location: ' + errorMsg, 'error');
            
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

// Auto-suggest region based on coordinates
function updateRegionSuggestion(lat, lng) {
    const regionSelect = document.getElementById('region-select');
    
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

// Update calculations for area and price per hectare
function updateCalculations() {
    const surface = parseFloat(document.getElementById('surface-input').value) || 0;
    const price = parseFloat(document.getElementById('price-input').value) || 0;
    
    // Update estimated area display
    const estimatedAreaEl = document.getElementById('estimated-area');
    if (surface > 0) {
        estimatedAreaEl.textContent = `${surface.toFixed(2)} hectares`;
        estimatedAreaEl.className = 'text-sm font-semibold text-green-600 mt-1';
    } else {
        estimatedAreaEl.textContent = 'Enter surface area';
        estimatedAreaEl.className = 'text-sm font-semibold text-gray-400 mt-1';
    }
    
    // Update price per hectare
    const pricePerHectareEl = document.getElementById('price-per-hectare');
    if (surface > 0 && price > 0) {
        const pricePerHa = (price / surface).toFixed(0);
        pricePerHectareEl.textContent = `${new Intl.NumberFormat().format(pricePerHa)} MAD/ha`;
        pricePerHectareEl.className = 'text-sm font-semibold text-blue-600 mt-1';
    } else {
        pricePerHectareEl.textContent = 'Enter price & surface';
        pricePerHectareEl.className = 'text-sm font-semibold text-gray-400 mt-1';
    }
}

// Fill sample data function
function fillSampleData() {
    if (!confirm('This will fill the form with sample data. Continue?')) return;
    
    document.querySelector('input[name="title"]').value = 'Premium Agricultural Land in Marrakech Region';
    document.querySelector('textarea[name="description"]').value = 'Excellent fertile agricultural land located in the heart of Marrakech region. This property features rich alluvial soil, excellent water access, and is perfect for olive cultivation, cereals, and vegetable farming. The land is flat, well-drained, and has existing irrigation infrastructure. Road access is excellent with proximity to major markets.';
    document.querySelector('input[name="surface"]').value = '10.5';
    document.querySelector('input[name="price"]').value = '750000';
    document.querySelector('select[name="region"]').value = 'Marrakech-Safi';
    document.querySelector('select[name="soil_type"]').value = 'Alluvial';
    document.querySelector('select[name="status"]').value = 'available';
    
    // Set sample coordinates (Marrakech area)
    const sampleLat = 31.7917;
    const sampleLng = -7.0926;
    document.getElementById('gps_coordinates').value = `${sampleLat}, ${sampleLng}`;
    
    if (map) {
        map.setView([sampleLat, sampleLng], 12);
        updateLocationSelection(sampleLat, sampleLng);
    }
    
    updateCalculations();
    showAlert('Sample data filled successfully!', 'success');
}

// Validate form function
function validateForm() {
    const requiredFields = document.querySelectorAll('[required]');
    let isValid = true;
    let missingFields = [];
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            missingFields.push(field.previousElementSibling.textContent.replace('*', '').trim());
            field.classList.add('border-red-300', 'ring-red-100');
        } else {
            field.classList.remove('border-red-300', 'ring-red-100');
            field.classList.add('border-green-300', 'ring-green-100');
        }
    });
    
    if (isValid) {
        showAlert('Form validation passed! All required fields are completed.', 'success');
    } else {
        showAlert(`Please fill in the following required fields: ${missingFields.join(', ')}`, 'error');
    }
}

// Reset form function
function resetForm() {
    if (!confirm('Are you sure you want to reset all form data?')) return;
    
    document.querySelector('form').reset();
    document.getElementById('selected-coordinates').textContent = 'Click map to select';
    document.getElementById('estimated-area').textContent = 'Based on surface input';
    document.getElementById('price-per-hectare').textContent = 'Auto-calculated';
    
    if (marker) {
        map.removeLayer(marker);
        marker = null;
    }
    
    if (map) {
        map.setView(moroccoCenter, 6);
    }
    
    // Clear any validation styles
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.classList.remove('border-red-300', 'ring-red-100', 'border-green-300', 'ring-green-100');
    });
    
    showAlert('Form reset successfully!', 'success');
}

// Save draft function
function saveDraft() {
    const formData = new FormData(document.querySelector('form'));
    const draftData = {};
    formData.forEach((value, key) => {
        draftData[key] = value;
    });
    
    // Add coordinates
    draftData.gps_coordinates = document.getElementById('gps_coordinates').value;
    
    localStorage.setItem('agricultural_land_draft', JSON.stringify(draftData));
    
    showAlert('Draft saved successfully! You can restore it later.', 'success');
}

// Load draft function
function loadDraft() {
    const draftData = localStorage.getItem('agricultural_land_draft');
    if (draftData) {
        try {
            const data = JSON.parse(draftData);
            const form = document.querySelector('form');
            
            Object.keys(data).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field) {
                    field.value = data[key];
                }
            });
            
            if (data.gps_coordinates) {
                parseAndSetCoordinates(data.gps_coordinates);
            }
            
            updateCalculations();
            showAlert('Draft loaded successfully!', 'success');
        } catch (e) {
            localStorage.removeItem('agricultural_land_draft');
            showAlert('Error loading draft data.', 'error');
        }
    } else {
        showAlert('No draft data found.', 'error');
    }
}

// Show alert function
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-2xl transform transition-all duration-500 ${
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

// Form submission enhancement
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Property...';
        submitBtn.disabled = true;
        
        // Clear draft on successful submission
        localStorage.removeItem('agricultural_land_draft');
        
        // Re-enable if form submission fails
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }, 10000);
    });
    
    // Check for draft on page load
    const draftExists = localStorage.getItem('agricultural_land_draft');
    if (draftExists) {
        const restoreDraft = confirm('A draft of your form was found. Would you like to restore it?');
        if (restoreDraft) {
            loadDraft();
        } else {
            localStorage.removeItem('agricultural_land_draft');
        }
    }
    
    // Auto-save draft every 30 seconds
    setInterval(function() {
        const form = document.querySelector('form');
        const formData = new FormData(form);
        let hasData = false;
        
        formData.forEach((value) => {
            if (value.trim() !== '') {
                hasData = true;
            }
        });
        
        if (hasData) {
            saveDraft();
        }
    }, 30000);
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save draft
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        saveDraft();
    }
    
    // Ctrl/Cmd + R to reset (with confirmation)
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        resetForm();
    }
    
    // Escape to go back
    if (e.key === 'Escape') {
        if (confirm('Are you sure you want to leave? Unsaved changes will be lost.')) {
            window.location.href = '{{ route("admin.lands.index") }}';
        }
    }
});
</script>
@endpush
@endsection