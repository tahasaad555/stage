@extends('layouts.admin')

@section('title', 'Listing Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('admin.listings.index') }}" 
                   class="text-gray-500 hover:text-gray-700 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $listing->title }}</h2>
                    <p class="text-gray-600">Listing Details</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                @if($listing->is_featured)
                    <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                        <i class="fas fa-star mr-1"></i>
                        Featured
                    </span>
                @endif
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $listing->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $listing->is_active ? 'Active' : 'Inactive' }}
                </span>
                <a href="{{ route('admin.listings.edit', $listing) }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Listing Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Listing Information</h3>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Title</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->title }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->description }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="text-sm text-gray-900">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $listing->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $listing->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Featured</dt>
                    <dd class="text-sm text-gray-900">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $listing->is_featured ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $listing->is_featured ? 'Yes' : 'No' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Published Date</dt>
                    <dd class="text-sm text-gray-900">
                        {{ $listing->published_at ? $listing->published_at->format('M d, Y H:i') : 'Not published' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->created_at->format('M d, Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->updated_at->format('M d, Y H:i') }}</dd>
                </div>
            </dl>
        </div>

        <!-- Supplier Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Supplier Information</h3>
            @if($listing->fournisseur)
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Supplier Name</dt>
                        <dd class="text-sm text-gray-900">
                            <a href="{{ route('admin.users.show', $listing->fournisseur->user) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                {{ $listing->fournisseur->user->full_name }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                        <dd class="text-sm text-gray-900">{{ $listing->fournisseur->company_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Business Registration</dt>
                        <dd class="text-sm text-gray-900">{{ $listing->fournisseur->business_registration }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900">{{ $listing->fournisseur->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="text-sm text-gray-900">{{ $listing->fournisseur->user->phone ?: 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="text-sm text-gray-900">{{ $listing->fournisseur->address }}</dd>
                    </div>
                </dl>
            @else
                <p class="text-red-500">Supplier information not available</p>
            @endif
        </div>
    </div>

    <!-- Agricultural Land Information -->
    @if($listing->terreAgricole)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Agricultural Land Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Land Title</dt>
                <dd class="text-sm text-gray-900">
                    <a href="{{ route('admin.lands.show', $listing->terreAgricole) }}" 
                       class="text-green-600 hover:text-green-800">
                        {{ $listing->terreAgricole->title }}
                    </a>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Surface Area</dt>
                <dd class="text-sm text-gray-900">{{ number_format($listing->terreAgricole->surface, 2) }} hectares</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Price</dt>
                <dd class="text-sm text-gray-900 font-semibold text-green-600">${{ number_format($listing->terreAgricole->price) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Region</dt>
                <dd class="text-sm text-gray-900">{{ $listing->terreAgricole->region }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Country</dt>
                <dd class="text-sm text-gray-900">{{ $listing->terreAgricole->country }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Land Status</dt>
                <dd class="text-sm text-gray-900">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($listing->terreAgricole->status === 'available') bg-green-100 text-green-800
                        @elseif($listing->terreAgricole->status === 'sold') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($listing->terreAgricole->status) }}
                    </span>
                </dd>
            </div>
            <div class="md:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Description</dt>
                <dd class="text-sm text-gray-900">{{ $listing->terreAgricole->description }}</dd>
            </div>
            @if($listing->terreAgricole->gps_coordinates)
            <div>
                <dt class="text-sm font-medium text-gray-500">GPS Coordinates</dt>
                <dd class="text-sm text-gray-900">{{ $listing->terreAgricole->gps_coordinates }}</dd>
            </div>
            @endif
            @if($listing->terreAgricole->soil_type)
            <div>
                <dt class="text-sm font-medium text-gray-500">Soil Type</dt>
                <dd class="text-sm text-gray-900">{{ $listing->terreAgricole->soil_type }}</dd>
            </div>
            @endif
        </div>
    </div>
    @else
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Agricultural Land Details</h3>
            <p class="text-red-500">Associated agricultural land not found</p>
        </div>
    @endif

    <!-- Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
        <div class="flex flex-wrap gap-4">
            <button onclick="toggleStatus()" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white {{ $listing->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                <i class="fas fa-toggle-{{ $listing->is_active ? 'off' : 'on' }} mr-2"></i>
                {{ $listing->is_active ? 'Deactivate' : 'Activate' }} Listing
            </button>
            
            <button onclick="toggleFeatured()" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white {{ $listing->is_featured ? 'bg-purple-600 hover:bg-purple-700' : 'bg-yellow-600 hover:bg-yellow-700' }}">
                <i class="fas fa-star{{ $listing->is_featured ? '' : '-o' }} mr-2"></i>
                {{ $listing->is_featured ? 'Remove from Featured' : 'Make Featured' }}
            </button>
            
            <a href="{{ route('admin.listings.edit', $listing) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-edit mr-2"></i>
                Edit Listing
            </a>
            
            <button onclick="deleteListing({{ $listing->id }})" 
                    class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                <i class="fas fa-trash mr-2"></i>
                Delete Listing
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
async function toggleStatus() {
    try {
        const response = await fetch(`/admin/listings/{{ $listing->id }}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
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

async function toggleFeatured() {
    try {
        const response = await fetch(`/admin/listings/{{ $listing->id }}/toggle-featured`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
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

async function deleteListing(listingId) {
    if (!confirm('Are you sure you want to delete this listing? This action cannot be undone.')) return;
    
    try {
        const response = await fetch(`/admin/listings/${listingId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            setTimeout(() => window.location.href = '{{ route("admin.listings.index") }}', 1000);
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
@endsection