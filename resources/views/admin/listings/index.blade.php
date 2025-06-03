@extends('layouts.admin')

@section('title', 'Listings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Listings</h2>
            <p class="text-gray-600">Manage agricultural land listings and advertisements</p>
        </div>
        <a href="{{ route('admin.listings.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>
            Add New Listing
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bullhorn text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ number_format($stats['total']) }}</h3>
                    <p class="text-sm text-gray-600">Total Listings</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ number_format($stats['active']) }}</h3>
                    <p class="text-sm text-gray-600">Active</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times text-red-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ number_format($stats['inactive']) }}</h3>
                    <p class="text-sm text-gray-600">Inactive</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-star text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ number_format($stats['featured']) }}</h3>
                    <p class="text-sm text-gray-600">Featured</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ number_format($stats['published']) }}</h3>
                    <p class="text-sm text-gray-600">Published</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Title, description, land..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Featured</label>
                <select name="featured" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">
                    <option value="">All</option>
                    <option value="yes" {{ request('featured') === 'yes' ? 'selected' : '' }}>Featured</option>
                    <option value="no" {{ request('featured') === 'no' ? 'selected' : '' }}>Not Featured</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                <select name="fournisseur" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">
                    <option value="">All Suppliers</option>
                    @foreach($fournisseurs as $fournisseur)
                        <option value="{{ $fournisseur->id }}" {{ request('fournisseur') == $fournisseur->id ? 'selected' : '' }}>
                            {{ $fournisseur->user->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('admin.listings.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Listings Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Listing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Land</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Published</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($listings as $listing)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('admin.listings.show', $listing) }}" class="hover:text-green-600">
                                                {{ $listing->title }}
                                            </a>
                                        </div>
                                        @if($listing->is_featured)
                                            <span class="ml-2">
                                                <i class="fas fa-star text-yellow-500" title="Featured"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ Str::limit($listing->description, 50) }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($listing->terreAgricole)
                                    <div class="text-sm text-gray-900">{{ $listing->terreAgricole->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $listing->terreAgricole->region }}</div>
                                @else
                                    <span class="text-red-500">Land not found</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($listing->fournisseur)
                                    <div class="text-sm text-gray-900">{{ $listing->fournisseur->user->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $listing->fournisseur->company_name }}</div>
                                @else
                                    <span class="text-red-500">Supplier not found</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $listing->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $listing->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $listing->published_at ? $listing->published_at->format('M d, Y') : 'Not published' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.listings.show', $listing) }}" 
                                       class="text-green-600 hover:text-green-900" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.listings.edit', $listing) }}" 
                                       class="text-blue-600 hover:text-blue-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="toggleStatus({{ $listing->id }})" 
                                            class="text-purple-600 hover:text-purple-900" title="Toggle Status">
                                        <i class="fas fa-toggle-{{ $listing->is_active ? 'on' : 'off' }}"></i>
                                    </button>
                                    <button onclick="toggleFeatured({{ $listing->id }})" 
                                            class="text-yellow-600 hover:text-yellow-900" title="Toggle Featured">
                                        <i class="fas fa-star{{ $listing->is_featured ? '' : '-o' }}"></i>
                                    </button>
                                    <button onclick="deleteListing({{ $listing->id }})" 
                                            class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-bullhorn text-4xl text-gray-300 mb-4"></i>
                                <p>No listings found</p>
                                <a href="{{ route('admin.listings.create') }}" class="text-green-600 hover:text-green-800 mt-2 inline-block">
                                    Create the first listing →
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($listings->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $listings->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
async function toggleStatus(listingId) {
    try {
        const response = await fetch(`/admin/listings/${listingId}/toggle-status`, {
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

async function toggleFeatured(listingId) {
    try {
        const response = await fetch(`/admin/listings/${listingId}/toggle-featured`, {
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
            setTimeout(() => location.reload(), 1000);
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