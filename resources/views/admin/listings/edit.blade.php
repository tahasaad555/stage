@extends('layouts.admin')

@section('title', 'Edit Listing')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center">
        <a href="{{ route('admin.listings.show', $listing) }}" 
           class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Listing</h2>
            <p class="text-gray-600">Update listing information</p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.listings.update', $listing) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Supplier -->
                <div>
                    <label for="fournisseur_id" class="block text-sm font-medium text-gray-700">Supplier *</label>
                    <select name="fournisseur_id" id="fournisseur_id" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('fournisseur_id') border-red-300 @enderror">
                        <option value="">Select a supplier</option>
                        @foreach($fournisseurs as $fournisseur)
                            <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id', $listing->fournisseur_id) == $fournisseur->id ? 'selected' : '' }}>
                                {{ $fournisseur->user->full_name }} - {{ $fournisseur->company_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('fournisseur_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Agricultural Land -->
                <div>
                    <label for="terre_agricole_id" class="block text-sm font-medium text-gray-700">Agricultural Land *</label>
                    <select name="terre_agricole_id" id="terre_agricole_id" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('terre_agricole_id') border-red-300 @enderror">
                        <option value="">Select agricultural land</option>
                        @foreach($lands as $land)
                            <option value="{{ $land->id }}" {{ old('terre_agricole_id', $listing->terre_agricole_id) == $land->id ? 'selected' : '' }}>
                                {{ $land->title }} - {{ $land->region }} ({{ number_format($land->surface, 1) }} ha - ${{ number_format($land->price) }})
                            </option>
                        @endforeach
                    </select>
                    @error('terre_agricole_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Listing Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $listing->title) }}" required
                       placeholder="e.g., Prime Agricultural Land in Bordeaux Region"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('title') border-red-300 @enderror">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                <textarea name="description" id="description" rows="4" required
                          placeholder="Provide detailed description of the land, its features, location benefits, soil quality, etc."
                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('description') border-red-300 @enderror">{{ old('description', $listing->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Options -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Active Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $listing->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">
                            <strong>Active Listing</strong><br>
                            <span class="text-gray-500">Make this listing visible to clients</span>
                        </span>
                    </label>
                </div>

                <!-- Featured Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $listing->is_featured) ? 'checked' : '' }}
                               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">
                            <strong>Featured Listing</strong><br>
                            <span class="text-gray-500">Highlight this listing prominently</span>
                        </span>
                    </label>
                </div>

                <!-- Published Date -->
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700">Published Date</label>
                    <input type="datetime-local" name="published_at" id="published_at" 
                           value="{{ old('published_at', $listing->published_at ? $listing->published_at->format('Y-m-d\TH:i') : '') }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('published_at') border-red-300 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Leave empty to auto-set when activated</p>
                    @error('published_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('admin.listings.show', $listing) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-save mr-2"></i>
                    Update Listing
                </button>
            </div>
        </form>
    </div>
</div>
@endsection