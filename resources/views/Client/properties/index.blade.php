@extends('layouts.client')

@section('title', 'Browse Properties')
@section('page-title', 'Browse Properties')

@section('content')
    <!-- Header Section -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl mb-8 card-hover">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-custom-1 rounded-2xl flex items-center justify-center mr-6">
                    <i class="fas fa-home text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold mb-2">🏡 Browse Properties</h1>
                    <p class="text-xl opacity-90">Find your perfect agricultural property</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg opacity-75">Available Properties</div>
                <div class="text-3xl font-bold">{{ $properties->total() }}</div>
            </div>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex space-x-3">
            <a href="{{ route('client.properties.index') }}" 
               class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl font-medium">
                <i class="fas fa-th-large mr-2"></i>Grid View
            </a>
            <a href="{{ route('client.properties.map') }}" 
               class="bg-white text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-50 transition-all font-medium border border-gray-300">
                <i class="fas fa-map mr-2"></i>Map View
            </a>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="glass-effect rounded-2xl p-6 shadow-xl mb-8">
        <form method="GET" action="{{ route('client.properties.index') }}" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}"
                               placeholder="Search properties..."
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class="fas fa-search absolute right-3 top-4 text-gray-400"></i>
                    </div>
                </div>

                <!-- Property Type -->
                <div>
                    <label for="property_type" class="block text-sm font-medium text-gray-700 mb-2">Soil Type</label>
                    <select name="property_type" id="property_type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Types</option>
                        <option value="clay" {{ request('property_type') == 'clay' ? 'selected' : '' }}>Clay</option>
                        <option value="sandy" {{ request('property_type') == 'sandy' ? 'selected' : '' }}>Sandy</option>
                        <option value="loamy" {{ request('property_type') == 'loamy' ? 'selected' : '' }}>Loamy</option>
                        <option value="rocky" {{ request('property_type') == 'rocky' ? 'selected' : '' }}>Rocky</option>
                    </select>
                </div>

                <!-- Price Range -->
                <div>
                    <label for="min_price" class="block text-sm font-medium text-gray-700 mb-2">Min Price (MAD)</label>
                    <input type="number" 
                           name="min_price" 
                           id="min_price"
                           value="{{ request('min_price') }}"
                           placeholder="Min price in MAD"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">Max Price (MAD)</label>
                    <input type="number" 
                           name="max_price" 
                           id="max_price"
                           value="{{ request('max_price') }}"
                           placeholder="Max price in MAD"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div class="flex space-x-3">
                <button type="submit" 
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium">
                    <i class="fas fa-search mr-2"></i>Search Properties
                </button>
                <button type="button" onclick="clearFilters()"
                        class="bg-gray-500 text-white px-6 py-3 rounded-xl hover:bg-gray-600 transition-all font-medium">
                    <i class="fas fa-times mr-2"></i>Clear Filters
                </button>
            </div>
        </form>
    </div>

    @if($properties->count() > 0)
        <!-- Properties Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($properties as $property)
                <div class="glass-effect rounded-2xl overflow-hidden shadow-xl card-hover">
                    <!-- Property Image -->
                    <div class="relative h-48 bg-gradient-to-r from-green-400 to-blue-500">
                        @if($property->terreAgricole && $property->terreAgricole->photos && count($property->terreAgricole->photos) > 0)
                            <img src="{{ asset('storage/' . $property->terreAgricole->photos[0]) }}" 
                                 alt="{{ $property->titre ?: $property->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-seedling text-white text-4xl"></i>
                            </div>
                        @endif
                        
                        <!-- Featured Badge -->
                        @if($property->is_featured)
                            <div class="absolute top-4 left-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-star mr-1"></i>Featured
                            </div>
                        @endif

                        <!-- Save Button -->
                        <button onclick="toggleSave({{ $property->id }})" 
                                id="save-btn-{{ $property->id }}"
                                class="absolute top-4 right-4 w-10 h-10 {{ in_array($property->id, $savedPropertyIds) ? 'bg-red-500 text-white' : 'bg-white text-gray-600 border border-gray-300' }} rounded-xl hover:scale-110 transition-all flex items-center justify-center">
                            <i class="{{ in_array($property->id, $savedPropertyIds) ? 'fas fa-heart' : 'far fa-heart' }}"></i>
                        </button>
                    </div>

                    <!-- Property Content -->
                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $property->titre ?: $property->title }}</h3>
                            
                            <!-- Location -->
                            <div class="flex items-center text-gray-600 mb-3">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                <span>{{ $property->terreAgricole->region ?? 'Location not specified' }}</span>
                            </div>

                            <!-- Price -->
                            <div class="text-2xl font-bold text-green-600 mb-3">
                                @php
                                    $price = $property->prix ?: ($property->terreAgricole->price ?? 0);
                                @endphp
                                {{ number_format($price) }} MAD
                                @if($property->terreAgricole && $property->terreAgricole->surface)
                                    <div class="text-sm text-gray-600 font-normal">
                                        {{ number_format($price / $property->terreAgricole->surface) }} MAD/hectare
                                    </div>
                                @endif
                            </div>

                            <!-- Area -->
                            @if($property->terreAgricole && $property->terreAgricole->surface)
                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <i class="fas fa-ruler-combined mr-2 text-green-500"></i>
                                    <span>{{ number_format($property->terreAgricole->surface, 1) }} hectares</span>
                                </div>
                            @endif

                            <!-- Soil Type -->
                            @if($property->terreAgricole && $property->terreAgricole->soil_type)
                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <i class="fas fa-seedling mr-2 text-brown-500"></i>
                                    <span class="capitalize">{{ $property->terreAgricole->soil_type }} soil</span>
                                </div>
                            @endif

                            <!-- Supplier -->
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-user mr-2 text-purple-500"></i>
                                {{ $property->fournisseur->company_name ?? $property->fournisseur->user->full_name ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <a href="{{ route('client.properties.show', $property) }}" 
                               class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium text-center">
                                <i class="fas fa-eye mr-2"></i>View Details
                            </a>
                            <button onclick="showInquiryModal({{ $property->id }}, '{{ $property->titre ?: $property->title }}')" 
                                    class="bg-green-600 text-white py-3 px-4 rounded-xl hover:bg-green-700 transition-all">
                                <i class="fas fa-envelope"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $properties->links() }}
        </div>
    @else
        <!-- No Properties Found -->
        <div class="glass-effect rounded-2xl p-12 shadow-xl text-center">
            <div class="w-24 h-24 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-search text-white text-3xl"></i>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-4">No Properties Found</h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                We couldn't find any properties matching your criteria. 
                Try adjusting your filters or search terms.</p>
            <a href="{{ route('client.properties.index') }}" 
               class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium">
                <i class="fas fa-refresh mr-2"></i>View All Properties
            </a>
        </div>
    @endif

    <!-- Quick Inquiry Modal -->
    <div id="inquiryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Quick Inquiry</h3>
                <button onclick="closeInquiryModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="inquiryForm" action="" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="property_id" id="modalPropertyId">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" name="subject" id="modalSubject" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Inquiry Type</label>
                    <select name="inquiry_type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            required>
                        <option value="information">Request Information</option>
                        <option value="visit">Schedule Visit</option>
                        <option value="purchase">Purchase Inquiry</option>
                        <option value="partnership">Partnership Opportunity</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Budget Range (MAD)</label>
                    <select name="budget_range" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select budget range</option>
                        <option value="<100000">< 100,000 MAD</option>
                        <option value="100000-500000">100,000 - 500,000 MAD</option>
                        <option value="500000-1000000">500,000 - 1,000,000 MAD</option>
                        <option value="1000000-5000000">1,000,000 - 5,000,000 MAD</option>
                        <option value=">5000000">> 5,000,000 MAD</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Please provide details about your inquiry..."
                              required></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select name="priority" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeInquiryModal()" 
                            class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl hover:bg-gray-300 transition-all font-medium">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium">
                        <i class="fas fa-paper-plane mr-2"></i>Send Inquiry
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle save property with enhanced error handling - FIXED
        function toggleSave(propertyId) {
            const btn = document.getElementById(`save-btn-${propertyId}`);
            if (!btn) return;
            
            const icon = btn.querySelector('i');
            const originalContent = btn.innerHTML;
            const originalClass = btn.className;
            
            // Show loading state
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
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
                        // Property was saved
                        btn.className = 'absolute top-4 right-4 w-10 h-10 bg-red-500 text-white rounded-xl hover:scale-110 transition-all flex items-center justify-center';
                        btn.innerHTML = '<i class="fas fa-heart"></i>';
                        btn.title = 'Remove from saved';
                    } else {
                        // Property was unsaved
                        btn.className = 'absolute top-4 right-4 w-10 h-10 bg-white text-gray-600 border border-gray-300 rounded-xl hover:scale-110 transition-all flex items-center justify-center';
                        btn.innerHTML = '<i class="far fa-heart"></i>';
                        btn.title = 'Save property';
                    }
                    showToast(data.message, data.action === 'saved' ? 'success' : 'info');
                } else {
                    btn.innerHTML = originalContent;
                    btn.className = originalClass;
                    showToast(data.message || 'An error occurred. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = originalContent;
                btn.className = originalClass;
                showToast('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                btn.disabled = false;
            });
        }

        // Show inquiry modal
        function showInquiryModal(propertyId, propertyTitle) {
            document.getElementById('modalPropertyId').value = propertyId;
            document.getElementById('modalSubject').value = `Inquiry about: ${propertyTitle}`;
            document.getElementById('inquiryForm').action = `/client/properties/${propertyId}/inquire`;
            document.getElementById('inquiryModal').classList.remove('hidden');
        }

        // Close inquiry modal
        function closeInquiryModal() {
            document.getElementById('inquiryModal').classList.add('hidden');
        }

        // Clear all filters
        function clearFilters() {
            document.getElementById('search').value = '';
            document.getElementById('property_type').value = '';
            document.getElementById('min_price').value = '';
            document.getElementById('max_price').value = '';
            document.querySelector('form[method="GET"]').submit();
        }

        // Show toast notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
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

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeInquiryModal();
            }
        });
    </script>
@endsection 