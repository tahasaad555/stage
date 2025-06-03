@extends('layouts.admin')

@section('title', 'Add New Agricultural Land')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>
<style>
.leaflet-container {
    font-family: 'Inter', sans-serif;
    border-radius: 8px;
}
.leaflet-popup-content-wrapper {
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}
.leaflet-popup-content {
    margin: 12px 16px;
    line-height: 1.4;
}
.leaflet-control-zoom a {
    border-radius: 4px;
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
    border-radius: 8px;
}
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center">
        <a href="{{ route('admin.lands.index') }}" 
           class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Add New Agricultural Land</h2>
            <p class="text-gray-600">Create a new agricultural land listing in Morocco</p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.lands.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('title') border-red-300 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Surface -->
                <div>
                    <label for="surface" class="block text-sm font-medium text-gray-700">Surface Area (hectares) *</label>
                    <input type="number" name="surface" id="surface" value="{{ old('surface') }}" step="0.01" min="0" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('surface') border-red-300 @enderror">
                    @error('surface')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price (MAD) *</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('price') border-red-300 @enderror">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Region -->
                <div>
                    <label for="region" class="block text-sm font-medium text-gray-700">Region *</label>
                    <select name="region" id="region" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('region') border-red-300 @enderror">
                        <option value="">Select a region</option>
                        <option value="Tanger-Tétouan-Al Hoceïma" {{ old('region') === 'Tanger-Tétouan-Al Hoceïma' ? 'selected' : '' }}>Tanger-Tétouan-Al Hoceïma</option>
                        <option value="Oriental" {{ old('region') === 'Oriental' ? 'selected' : '' }}>Oriental</option>
                        <option value="Fès-Meknès" {{ old('region') === 'Fès-Meknès' ? 'selected' : '' }}>Fès-Meknès</option>
                        <option value="Rabat-Salé-Kénitra" {{ old('region') === 'Rabat-Salé-Kénitra' ? 'selected' : '' }}>Rabat-Salé-Kénitra</option>
                        <option value="Béni Mellal-Khénifra" {{ old('region') === 'Béni Mellal-Khénifra' ? 'selected' : '' }}>Béni Mellal-Khénifra</option>
                        <option value="Casablanca-Settat" {{ old('region') === 'Casablanca-Settat' ? 'selected' : '' }}>Casablanca-Settat</option>
                        <option value="Marrakech-Safi" {{ old('region') === 'Marrakech-Safi' ? 'selected' : '' }}>Marrakech-Safi</option>
                        <option value="Drâa-Tafilalet" {{ old('region') === 'Drâa-Tafilalet' ? 'selected' : '' }}>Drâa-Tafilalet</option>
                        <option value="Souss-Massa" {{ old('region') === 'Souss-Massa' ? 'selected' : '' }}>Souss-Massa</option>
                        <option value="Guelmim-Oued Noun" {{ old('region') === 'Guelmim-Oued Noun' ? 'selected' : '' }}>Guelmim-Oued Noun</option>
                        <option value="Laâyoune-Sakia El Hamra" {{ old('region') === 'Laâyoune-Sakia El Hamra' ? 'selected' : '' }}>Laâyoune-Sakia El Hamra</option>
                        <option value="Dakhla-Oued Ed-Dahab" {{ old('region') === 'Dakhla-Oued Ed-Dahab' ? 'selected' : '' }}>Dakhla-Oued Ed-Dahab</option>
                    </select>
                    @error('region')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Country -->
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700">Country *</label>
                    <input type="text" name="country" id="country" value="{{ old('country', 'Morocco') }}" readonly
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-600 cursor-not-allowed">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" id="status" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('status') border-red-300 @enderror">
                        <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="reserved" {{ old('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                        <option value="sold" {{ old('status') === 'sold' ? 'selected' : '' }}>Sold</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Soil Type -->
                <div>
                    <label for="soil_type" class="block text-sm font-medium text-gray-700">Soil Type</label>
                    <select name="soil_type" id="soil_type" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('soil_type') border-red-300 @enderror">
                        <option value="">Select soil type</option>
                        <option value="Clay" {{ old('soil_type') === 'Clay' ? 'selected' : '' }}>Clay</option>
                        <option value="Sandy" {{ old('soil_type') === 'Sandy' ? 'selected' : '' }}>Sandy</option>
                        <option value="Loamy" {{ old('soil_type') === 'Loamy' ? 'selected' : '' }}>Loamy</option>
                        <option value="Silty" {{ old('soil_type') === 'Silty' ? 'selected' : '' }}>Silty</option>
                        <option value="Rocky" {{ old('soil_type') === 'Rocky' ? 'selected' : '' }}>Rocky</option>
                        <option value="Alluvial" {{ old('soil_type') === 'Alluvial' ? 'selected' : '' }}>Alluvial</option>
                    </select>
                    @error('soil_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                <textarea name="description" id="description" rows="4" required
                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt mr-2 text-green-600"></i>
                    Location Selection in Morocco
                </label>
                <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-lg p-6 space-y-4 border border-green-200">
                    <!-- GPS Coordinates Input -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="gps_coordinates" class="block text-sm font-medium text-gray-700">GPS Coordinates</label>
                            <input type="text" name="gps_coordinates" id="gps_coordinates" value="{{ old('gps_coordinates') }}" 
                                   placeholder="e.g., 31.7917, -7.0926 (Marrakech)"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('gps_coordinates') border-red-300 @enderror">
                            @error('gps_coordinates')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="button" id="locate-btn" 
                                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-md hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-md">
                                <i class="fas fa-location-arrow mr-2"></i>
                                Use My Location
                            </button>
                            <button type="button" id="center-morocco-btn" 
                                    class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-md hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-md">
                                <i class="fas fa-search mr-2"></i>
                                Center Morocco
                            </button>
                        </div>
                    </div>

                    <!-- Interactive Map -->
                    <div class="border-2 border-gray-300 rounded-xl overflow-hidden shadow-lg">
                        <div id="map-loading" class="map-loading">
                            <div class="text-center">
                                <i class="fas fa-spinner fa-spin text-3xl text-green-600 mb-2"></i>
                                <p class="text-gray-600">Loading Morocco map...</p>
                            </div>
                        </div>
                        <div id="map" style="height: 500px; width: 100%; display: none;"></div>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            <span>Click on the map to select the exact location of your agricultural land</span>
                        </div>
                        <div id="selected-coordinates" class="font-mono text-xs bg-white px-3 py-2 rounded-lg border shadow-sm">
                            No location selected
                        </div>
                    </div>
                    
                    <!-- Quick Location Buttons -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
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
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('admin.lands.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-md">
                    <i class="fas fa-save mr-2"></i>
                    Create Land
                </button>
            </div>
        </form>
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

// Morocco center coordinates
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
        alert('Please select a location within Morocco.');
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
        alert('Geolocation is not supported by this browser.');
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
                alert('Your current location is outside Morocco. Please select a location within Morocco on the map.');
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
            alert('Error getting location: ' + errorMsg);
            
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
</script>
@endpush