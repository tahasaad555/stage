@extends('layouts.admin')

@section('title', 'Land Details')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>
<style>
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
.land-marker {
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
.land-area-circle {
    stroke: #10b981;
    stroke-width: 3;
    stroke-dasharray: 10, 5;
    fill: rgba(16, 185, 129, 0.1);
    animation: dash 20s linear infinite;
}
@keyframes dash {
    to { stroke-dashoffset: -100; }
}
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('admin.lands.index') }}" 
                   class="text-gray-500 hover:text-gray-700 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $land->title }}</h2>
                    <p class="text-gray-600">
                        <i class="fas fa-map-marker-alt mr-1 text-green-600"></i>
                        {{ $land->region }}, {{ $land->country }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                    @if($land->status === 'available') bg-green-100 text-green-800
                    @elseif($land->status === 'sold') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($land->status) }}
                </span>
                <a href="{{ route('admin.lands.edit', $land) }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Land Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Title</dt>
                    <dd class="text-sm text-gray-900">{{ $land->title }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="text-sm text-gray-900">{{ $land->description }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Surface Area</dt>
                    <dd class="text-sm text-gray-900">{{ number_format($land->surface, 2) }} hectares</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Price</dt>
                    <dd class="text-sm text-gray-900 font-semibold text-green-600">{{ number_format($land->price) }} MAD</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="text-sm text-gray-900">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($land->status === 'available') bg-green-100 text-green-800
                            @elseif($land->status === 'sold') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($land->status) }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Location Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Location & Details</h3>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Region</dt>
                    <dd class="text-sm text-gray-900">{{ $land->region }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Country</dt>
                    <dd class="text-sm text-gray-900 flex items-center">
                        🇲🇦 {{ $land->country }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">GPS Coordinates</dt>
                    <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $land->gps_coordinates ?: 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Soil Type</dt>
                    <dd class="text-sm text-gray-900">{{ $land->soil_type ?: 'Not specified' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Listed Date</dt>
                    <dd class="text-sm text-gray-900">{{ $land->created_at->format('M d, Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="text-sm text-gray-900">{{ $land->updated_at->format('M d, Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Location Map -->
    @if($land->gps_coordinates)
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-map mr-2 text-green-600"></i>
                Location Map in Morocco
            </h3>
            <div class="flex items-center space-x-4 text-sm text-gray-600">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-600 rounded-full mr-2"></div>
                    <span>Land Location</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 border-2 border-green-600 rounded-full mr-2" style="background: rgba(16, 185, 129, 0.1);"></div>
                    <span>Approximate Area</span>
                </div>
            </div>
        </div>
        <div class="border-2 border-gray-300 rounded-xl overflow-hidden shadow-lg">
            <div id="map" style="height: 500px; width: 100%;"></div>
        </div>
        <div class="mt-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Coordinates</p>
                    <p class="text-sm font-mono text-gray-900 bg-white px-2 py-1 rounded mt-1">{{ $land->gps_coordinates }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Surface Area</p>
                    <p class="text-sm font-semibold text-green-600 mt-1">{{ number_format($land->surface, 1) }} hectares</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Price per Hectare</p>
                    <p class="text-sm font-semibold text-blue-600 mt-1">{{ number_format($land->price / max($land->surface, 1), 0) }} MAD/ha</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Listing Information -->
    @if(isset($land->annonce) && $land->annonce)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Listing Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Listed By</dt>
                <dd class="text-sm text-gray-900">
                    @if($land->annonce->fournisseur && $land->annonce->fournisseur->user)
                        <a href="{{ route('admin.users.show', $land->annonce->fournisseur->user) }}" 
                           class="text-blue-600 hover:text-blue-800">
                            {{ $land->annonce->fournisseur->user->full_name }}
                        </a>
                        <span class="text-gray-500">({{ $land->annonce->fournisseur->company_name ?? 'No company' }})</span>
                    @else
                        No supplier assigned
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Listing Status</dt>
                <dd class="text-sm text-gray-900">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $land->annonce->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $land->annonce->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Featured</dt>
                <dd class="text-sm text-gray-900">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $land->annonce->is_featured ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $land->annonce->is_featured ? 'Yes' : 'No' }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Published Date</dt>
                <dd class="text-sm text-gray-900">
                    {{ $land->annonce->published_at ? $land->annonce->published_at->format('M d, Y H:i') : 'Not published' }}
                </dd>
            </div>
        </div>
    </div>
    @endif

    <!-- Transaction History -->
    @if(isset($land->transactions) && $land->transactions->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Transaction History</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($land->transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($transaction->client && $transaction->client->user)
                                        <a href="{{ route('admin.users.show', $transaction->client->user) }}" 
                                           class="text-blue-600 hover:text-blue-800">
                                            {{ $transaction->client->user->full_name }}
                                        </a>
                                    @else
                                        Unknown Client
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($transaction->amount, 2) }} MAD</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($transaction->commission, 2) }} MAD</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($transaction->status === 'completed') bg-green-100 text-green-800
                                    @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->created_at->format('M d, Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center space-x-2">
                <label for="status" class="text-sm font-medium text-gray-700">Change Status:</label>
                <select id="status" onchange="updateStatus()" 
                        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">
                    <option value="available" {{ $land->status === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="reserved" {{ $land->status === 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="sold" {{ $land->status === 'sold' ? 'selected' : '' }}>Sold</option>
                </select>
            </div>
            
            <a href="{{ route('admin.lands.edit', $land) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-edit mr-2"></i>
                Edit Land
            </a>
            
            <button onclick="deleteLand({{ $land->id }})" 
                    class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                <i class="fas fa-trash mr-2"></i>
                Delete Land
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>

<script>
@if($land->gps_coordinates)
// Initialize map for viewing location in Morocco
document.addEventListener('DOMContentLoaded', function() {
    const coordinates = '{{ $land->gps_coordinates }}';
    const coords = coordinates.split(',').map(s => parseFloat(s.trim()));
    
    if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
        const [lat, lng] = coords;
        
        // Morocco bounds
        const moroccoBounds = [
            [21.0, -17.5], // Southwest corner
            [36.0, -1.0]   // Northeast corner
        ];
        
        // Initialize map with Morocco constraints
        const map = L.map('map', {
            maxBounds: moroccoBounds,
            maxBoundsViscosity: 1.0,
            minZoom: 5,
            maxZoom: 18
        }).setView([lat, lng], 12);
        
        // Add high-quality map tiles for Morocco
        L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 18,
            className: 'map-tiles'
        }).addTo(map);
        
        // Create custom land marker
        const landIcon = L.divIcon({
            html: '<div class="w-6 h-6 bg-green-600 border-4 border-white rounded-full shadow-lg"></div>',
            className: 'land-marker',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });
        
        // Add marker for the land location
        const marker = L.marker([lat, lng], { icon: landIcon }).addTo(map);
        
        // Enhanced popup content
        marker.bindPopup(`
            <div class="text-center p-3 min-w-0" style="min-width: 250px;">
                <div class="flex items-center justify-center mb-3">
                    <i class="fas fa-map-marker-alt text-green-600 mr-2 text-lg"></i>
                    <strong class="text-gray-800 text-base">{{ $land->title }}</strong>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Location:</span>
                        <span class="text-gray-800">{{ $land->region }}, 🇲🇦</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Surface:</span>
                        <span class="text-green-600 font-semibold">{{ number_format($land->surface, 1) }} ha</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Price:</span>
                        <span class="text-blue-600 font-semibold">{{ number_format($land->price) }} MAD</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($land->status === 'available') bg-green-100 text-green-800
                            @elseif($land->status === 'sold') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($land->status) }}
                        </span>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-t text-xs text-gray-500">
                    <div class="font-mono bg-gray-100 px-2 py-1 rounded">${lat.toFixed(6)}, ${lng.toFixed(6)}</div>
                </div>
            </div>
        `).openPopup();
        
        // Add a circle to show approximate land area with animation
        const radius = Math.sqrt({{ $land->surface }} * 10000) / 2; // Rough approximation
        const areaCircle = L.circle([lat, lng], {
            color: '#10b981',
            fillColor: '#10b981',
            fillOpacity: 0.15,
            radius: radius,
            weight: 3,
            dashArray: '10, 5',
            className: 'land-area-circle'
        }).addTo(map);
        
        // Add area label
        const areaLabel = L.tooltip({
            permanent: true,
            direction: 'center',
            className: 'area-label bg-white px-2 py-1 rounded shadow-md text-xs font-semibold text-green-700'
        })
        .setContent(`{{ number_format($land->surface, 1) }} hectares`)
        .setLatLng([lat, lng]);
        
        areaCircle.bindTooltip(areaLabel);
        
        // Fit map to show both marker and area circle
        const group = new L.featureGroup([marker, areaCircle]);
        map.fitBounds(group.getBounds().pad(0.1));
    }
});
@endif

// Get CSRF token from meta tag or create a simple way to include it
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

async function updateStatus() {
    const status = document.getElementById('status').value;
    
    try {
        const response = await fetch(`/admin/lands/{{ $land->id }}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ status: status })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(result.message, 'error');
        }
    } catch (error) {
        showAlert('An error occurred', 'error');
    }
}

async function deleteLand(landId) {
    if (!confirm('Are you sure you want to delete this agricultural land? This action cannot be undone.')) return;
    
    try {
        const response = await fetch(`/admin/lands/${landId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            setTimeout(() => window.location.href = '{{ route("admin.lands.index") }}', 1000);
        } else {
            showAlert(result.message, 'error');
        }
    } catch (error) {
        showAlert('An error occurred', 'error');
    }
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-md ${
        type === 'success' ? 'bg-green-100 text-green-700 border border-green-400' :
        'bg-red-100 text-red-700 border border-red-400'
    }`;
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
            ${message}
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-lg">&times;</button>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endpush