@extends('layouts.client')

@section('title', 'Properties Map View')
@section('page-title', 'Properties Map')

@section('content')
    <!-- Header Section -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl mb-8 card-hover">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-custom-3 rounded-2xl flex items-center justify-center mr-6">
                    <i class="fas fa-map-marked-alt text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold mb-2">🗺️ Properties Map</h1>
                    <p class="text-xl opacity-90">Explore properties by location</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg opacity-75">Properties on Map</div>
                <div class="text-3xl font-bold">{{ $properties->count() }}</div>
            </div>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex space-x-3">
            <a href="{{ route('client.properties.index') }}" 
               class="bg-white text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-50 transition-all font-medium border border-gray-300">
                <i class="fas fa-th-large mr-2"></i>Grid View
            </a>
            <a href="{{ route('client.properties.map') }}" 
               class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl font-medium">
                <i class="fas fa-map mr-2"></i>Map View
            </a>
        </div>
        
        <!-- Quick Filters -->
        <div class="flex space-x-3">
            <select id="mapFilterType" class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                <option value="">All Soil Types</option>
                <option value="clay">Clay Soil</option>
                <option value="sandy">Sandy Soil</option>
                <option value="loamy">Loamy Soil</option>
                <option value="rocky">Rocky Soil</option>
            </select>
            <select id="mapFilterPrice" class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                <option value="">All Prices</option>
                <option value="0-100000">< 100K MAD</option>
                <option value="100000-500000">100K - 500K MAD</option>
                <option value="500000-1000000">500K - 1M MAD</option>
                <option value="1000000-99999999">> 1M MAD</option>
            </select>
        </div>
    </div>

    <!-- Map Container -->
    <div class="glass-effect rounded-2xl overflow-hidden shadow-xl mb-8">
        <div id="map" class="w-full h-[600px] relative">
            <!-- Map will be rendered here -->
        </div>
    </div>

    <!-- Properties Sidebar -->
    <div class="glass-effect rounded-2xl p-6 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Properties List</h3>
            <div class="flex space-x-3">
                <button onclick="fitMapToMarkers()" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition-all text-sm">
                    <i class="fas fa-expand-arrows-alt mr-2"></i>Fit All
                </button>
                <button onclick="locateUser()" 
                        class="bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition-all text-sm">
                    <i class="fas fa-location-arrow mr-2"></i>My Location
                </button>
            </div>
        </div>
        
        <div id="propertiesList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
            @foreach($properties as $property)
                <div class="property-card bg-white rounded-xl p-4 border border-gray-200 hover:shadow-lg transition-all cursor-pointer"
                     data-property-id="{{ $property->id }}"
                     onclick="focusProperty({{ $property->id }})">
                    
                    <!-- Property Image -->
                    <div class="relative h-32 bg-gradient-to-r from-green-400 to-blue-500 rounded-lg mb-3">
                        @if($property->terreAgricole && $property->terreAgricole->photos && count($property->terreAgricole->photos) > 0)
                            <img src="{{ asset('storage/' . $property->terreAgricole->photos[0]) }}" 
                                 alt="{{ $property->titre ?: $property->title }}"
                                 class="w-full h-full object-cover rounded-lg">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-seedling text-white text-2xl"></i>
                            </div>
                        @endif
                        
                        <!-- Save Button -->
                        <button onclick="event.stopPropagation(); toggleSave({{ $property->id }})" 
                                id="save-btn-{{ $property->id }}"
                                class="absolute top-2 right-2 w-8 h-8 {{ in_array($property->id, $savedPropertyIds) ? 'bg-red-500 text-white' : 'bg-white text-gray-600 border border-gray-300' }} rounded-lg hover:scale-110 transition-all flex items-center justify-center">
                            <i class="{{ in_array($property->id, $savedPropertyIds) ? 'fas fa-heart' : 'far fa-heart' }} text-sm"></i>
                        </button>
                    </div>

                    <!-- Property Info -->
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2 text-sm">{{ $property->titre ?: $property->title }}</h4>
                        
                        <div class="flex items-center text-xs text-gray-600 mb-2">
                            <i class="fas fa-map-marker-alt mr-1 text-blue-500"></i>
                            <span>{{ $property->terreAgricole->region ?? 'N/A' }}</span>
                        </div>

                        <div class="text-lg font-bold text-green-600 mb-2">
                            @php
                                $price = $property->prix ?: ($property->terreAgricole->price ?? 0);
                            @endphp
                            {{ number_format($price) }} MAD
                        </div>

                        @if($property->terreAgricole && $property->terreAgricole->surface)
                            <div class="text-xs text-gray-600">
                                {{ number_format($property->terreAgricole->surface, 1) }} hectares
                            </div>
                        @endif

                        @if($property->terreAgricole && $property->terreAgricole->gps_coordinates)
                            <div class="text-xs text-blue-600 mt-1">
                                <i class="fas fa-map-pin mr-1"></i>
                                {{ $property->terreAgricole->gps_coordinates }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        let map;
        let markers = [];
        let userLocationMarker = null;

        // Properties data for JavaScript
        const properties = [
            @foreach($properties as $property)
                @php
                    $price = $property->prix ?: ($property->terreAgricole->price ?? 0);
                    $coordinates = $property->terreAgricole->gps_coordinates ?? null;
                    
                    // Parse coordinates if they exist
                    $lat = null;
                    $lng = null;
                    if ($coordinates) {
                        if (strpos($coordinates, ',') !== false) {
                            $coords = explode(',', $coordinates);
                            $lat = (float) trim($coords[0]);
                            $lng = (float) trim($coords[1]);
                        } elseif (preg_match('/(-?\d+\.?\d*)\s+(-?\d+\.?\d*)/', $coordinates, $matches)) {
                            $lat = (float) $matches[1];
                            $lng = (float) $matches[2];
                        }
                    }
                @endphp
                {
                    id: {{ $property->id }},
                    title: "{{ addslashes($property->titre ?: $property->title) }}",
                    price: "{{ number_format($price) }} MAD",
                    location: "{{ addslashes($property->terreAgricole->region ?? 'N/A') }}",
                    area: "{{ $property->terreAgricole->surface ? number_format($property->terreAgricole->surface, 1) . ' hectares' : 'N/A' }}",
                    soil_type: "{{ $property->terreAgricole->soil_type ?? '' }}",
                    lat: {{ $lat ?? 'null' }},
                    lng: {{ $lng ?? 'null' }},
                    image: "{{ $property->terreAgricole && $property->terreAgricole->photos && count($property->terreAgricole->photos) > 0 ? asset('storage/' . $property->terreAgricole->photos[0]) : '' }}",
                    is_saved: {{ in_array($property->id, $savedPropertyIds) ? 'true' : 'false' }},
                    url: "{{ route('client.properties.show', $property) }}"
                }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ];

        const savedPropertyIds = @json($savedPropertyIds);

        // Initialize map with Leaflet (OpenStreetMap)
        function initMap() {
            // Initialize the map centered on Morocco
            map = L.map('map').setView([31.7917, -7.0926], 6);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add markers for properties
            addMarkersToMap();

            // Fit map to show all markers
            if (markers.length > 0) {
                fitMapToMarkers();
            }
        }

        // Add markers to map
        function addMarkersToMap() {
            properties.forEach(property => {
                if (property.lat && property.lng) {
                    addPropertyMarker(property);
                }
            });
        }

        // Add single property marker
        function addPropertyMarker(property) {
            // Create custom icon
            const icon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: #3B82F6; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3); font-size: 14px;">🏡</div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });

            const marker = L.marker([property.lat, property.lng], { icon: icon }).addTo(map);

            // Create popup content
            const popupContent = `
                <div class="max-w-xs">
                    ${property.image ? `
                        <img src="${property.image}" alt="${property.title}" class="w-full h-32 object-cover rounded-lg mb-3">
                    ` : `
                        <div class="w-full h-32 bg-gradient-to-r from-green-400 to-blue-500 rounded-lg mb-3 flex items-center justify-center">
                            <i class="fas fa-seedling text-white text-2xl"></i>
                        </div>
                    `}
                    
                    <h4 class="font-bold text-gray-800 mb-2">${property.title}</h4>
                    <div class="text-sm text-gray-600 mb-2">
                        <i class="fas fa-map-marker-alt mr-1 text-blue-500"></i>
                        ${property.location}
                    </div>
                    <div class="text-lg font-bold text-green-600 mb-2">${property.price}</div>
                    <div class="text-sm text-gray-600 mb-3">${property.area}</div>
                    
                    <div class="flex space-x-2">
                        <a href="${property.url}" 
                           class="flex-1 bg-blue-600 text-white py-2 px-3 rounded-lg hover:bg-blue-700 transition-all text-center text-sm">
                            View Details
                        </a>
                        <button onclick="toggleSave(${property.id})" 
                                class="bg-red-500 text-white py-2 px-3 rounded-lg hover:bg-red-600 transition-all text-sm">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent);
            markers.push(marker);
            
            // Store property data with marker
            marker.propertyData = property;
        }

        // Fit map to show all markers
        function fitMapToMarkers() {
            if (markers.length === 0) return;
            
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }

        // Focus on specific property
        function focusProperty(propertyId) {
            const marker = markers.find(m => m.propertyData.id === propertyId);
            if (marker) {
                map.setView(marker.getLatLng(), 14);
                marker.openPopup();
            }
        }

        // Locate user
        function locateUser() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        const userLocation = [position.coords.latitude, position.coords.longitude];
                        
                        // Remove existing user location marker
                        if (userLocationMarker) {
                            map.removeLayer(userLocationMarker);
                        }
                        
                        // Add user location marker
                        const userIcon = L.divIcon({
                            className: 'user-location-icon',
                            html: `<div style="background-color: #10B981; border-radius: 50%; width: 20px; height: 20px; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                            iconSize: [20, 20],
                            iconAnchor: [10, 10]
                        });
                        
                        userLocationMarker = L.marker(userLocation, { icon: userIcon }).addTo(map);
                        userLocationMarker.bindPopup("Your Location").openPopup();
                        
                        // Center map on user location
                        map.setView(userLocation, 12);
                        
                        showToast('Location found!', 'success');
                    },
                    error => {
                        showToast('Could not get your location. Please enable location services.', 'error');
                    }
                );
            } else {
                showToast('Geolocation is not supported by this browser.', 'error');
            }
        }

        // Filter properties on map
        function filterProperties() {
            const typeFilter = document.getElementById('mapFilterType').value;
            const priceFilter = document.getElementById('mapFilterPrice').value;
            
            markers.forEach((marker, index) => {
                const property = marker.propertyData;
                let show = true;
                
                // Type filter
                if (typeFilter && property.soil_type !== typeFilter) {
                    show = false;
                }
                
                // Price filter
                if (priceFilter && show) {
                    const [min, max] = priceFilter.split('-').map(Number);
                    const propertyPrice = parseInt(property.price.replace(/[^\d]/g, ''));
                    
                    if (propertyPrice < min || (max && propertyPrice > max)) {
                        show = false;
                    }
                }
                
                // Show/hide marker
                if (show) {
                    map.addLayer(marker);
                } else {
                    map.removeLayer(marker);
                }
                
                // Show/hide in sidebar
                const card = document.querySelector(`[data-property-id="${property.id}"]`);
                if (card) {
                    card.style.display = show ? 'block' : 'none';
                }
            });
        }

        // Toggle save property
        function toggleSave(propertyId) {
            const btn = document.getElementById(`save-btn-${propertyId}`);
            if (!btn) return;
            
            const icon = btn.querySelector('i');
            const originalContent = btn.innerHTML;
            
            // Show loading state
            btn.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i>';
            btn.disabled = true;
            
            fetch(`/client/properties/${propertyId}/toggle-save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.action === 'saved') {
                        btn.className = 'absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-lg hover:scale-110 transition-all flex items-center justify-center';
                        btn.innerHTML = '<i class="fas fa-heart text-sm"></i>';
                        btn.title = 'Remove from saved';
                    } else {
                        btn.className = 'absolute top-2 right-2 w-8 h-8 bg-white text-gray-600 border border-gray-300 rounded-lg hover:scale-110 transition-all flex items-center justify-center';
                        btn.innerHTML = '<i class="far fa-heart text-sm"></i>';
                        btn.title = 'Save property';
                    }
                    showToast(data.message, data.action === 'saved' ? 'success' : 'info');
                } else {
                    btn.innerHTML = originalContent;
                    showToast('An error occurred. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = originalContent;
                showToast('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                btn.disabled = false;
            });
        }

        // Show toast notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transition-all transform translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check' : type === 'error' ? 'fa-exclamation-triangle' : 'fa-info'} mr-3"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (document.body.contains(toast)) {
                        document.body.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map
            initMap();
            
            // Filter event listeners
            document.getElementById('mapFilterType').addEventListener('change', filterProperties);
            document.getElementById('mapFilterPrice').addEventListener('change', filterProperties);
        });
    </script>
@endsection