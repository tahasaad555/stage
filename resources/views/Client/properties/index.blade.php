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
                    <label for="property_type" class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                    <select name="property_type" id="property_type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Types</option>
                        <option value="farmland" {{ request('property_type') == 'farmland' ? 'selected' : '' }}>Farmland</option>
                        <option value="vineyard" {{ request('property_type') == 'vineyard' ? 'selected' : '' }}>Vineyard</option>
                        <option value="orchard" {{ request('property_type') == 'orchard' ? 'selected' : '' }}>Orchard</option>
                        <option value="ranch" {{ request('property_type') == 'ranch' ? 'selected' : '' }}>Ranch</option>
                        <option value="greenhouse" {{ request('property_type') == 'greenhouse' ? 'selected' : '' }}>Greenhouse</option>
                    </select>
                </div>

                <!-- Price Range -->
                <div>
                    <label for="min_price" class="block text-sm font-medium text-gray-700 mb-2">Min Price (€)</label>
                    <input type="number" 
                           name="min_price" 
                           id="min_price"
                           value="{{ request('min_price') }}"
                           placeholder="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">Max Price (€)</label>
                    <input type="number" 
                           name="max_price" 
                           id="max_price"
                           value="{{ request('max_price') }}"
                           placeholder="1000000"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" 
                           name="location" 
                           id="location"
                           value="{{ request('location') }}"
                           placeholder="Enter city or region..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Sort By -->
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select name="sort_by" id="sort_by" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Newest First</option>
                        <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Price</option>
                        <option value="area" {{ request('sort_by') == 'area' ? 'selected' : '' }}>Area</option>
                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Name</option>
                    </select>
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                    <select name="sort_order" id="sort_order" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>High to Low</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Low to High</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium">
                        <i class="fas fa-search mr-2"></i>Search Properties
                    </button>
                    <a href="{{ route('client.properties.index') }}" 
                       class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-300 transition-all font-medium">
                        <i class="fas fa-refresh mr-2"></i>Clear Filters
                    </a>
                </div>
                <div class="text-sm text-gray-600">
                    Showing {{ $properties->firstItem() ?? 0 }} - {{ $properties->lastItem() ?? 0 }} of {{ $properties->total() }} properties
                </div>
            </div>
        </form>
    </div>

    <!-- Properties Grid -->
    @if($properties->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($properties as $property)
                <div class="glass-effect rounded-2xl overflow-hidden shadow-xl card-hover">
                    <!-- Property Image -->
                    <div class="relative h-48 bg-gray-200">
                        @if($property->images && $property->images->count() > 0)
                            <img src="{{ $property->images->first()->image_url }}" 
                                 alt="{{ $property->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                                <i class="fas fa-home text-white text-4xl"></i>
                            </div>
                        @endif
                        
                        <!-- Save Button -->
                        <button onclick="toggleSave({{ $property->id }})" 
                                class="absolute top-3 right-3 w-10 h-10 rounded-full {{ in_array($property->id, $savedPropertyIds) ? 'bg-red-500 text-white' : 'bg-white text-gray-600' }} flex items-center justify-center hover:scale-110 transition-all shadow-lg"
                                id="save-btn-{{ $property->id }}">
                            <i class="fas fa-heart"></i>
                        </button>

                        <!-- Property Type Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-medium capitalize">
                                {{ str_replace('_', ' ', $property->property_type) }}
                            </span>
                        </div>

                        <!-- Price Badge -->
                        <div class="absolute bottom-3 left-3">
                            <span class="bg-green-600 text-white px-3 py-2 rounded-lg font-bold">
                                €{{ number_format($property->price) }}
                            </span>
                        </div>
                    </div>

                    <!-- Property Details -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $property->title }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($property->description, 100) }}</p>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                {{ $property->location }}
                            </div>
                            @if($property->area)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-ruler-combined mr-2 text-green-500"></i>
                                    {{ number_format($property->area) }} {{ $property->area_unit ?? 'hectares' }}
                                </div>
                            @endif
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-user mr-2 text-purple-500"></i>
                                {{ $property->user->fournisseur->company_name ?? $property->user->full_name }}
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <a href="{{ route('client.properties.show', $property) }}" 
                               class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium text-center">
                                <i class="fas fa-eye mr-2"></i>View Details
                            </a>
                            <button onclick="showInquiryModal({{ $property->id }}, '{{ $property->title }}')" 
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
            {{ $properties->appends(request()->query())->links() }}
        </div>
    @else
        <!-- No Properties Found -->
        <div class="glass-effect rounded-2xl p-12 shadow-xl text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-search text-white text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-4">No Properties Found</h3>
            <p class="text-gray-600 mb-6">We couldn't find any properties matching your search criteria. Try adjusting your filters or search terms.</p>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                              placeholder="Tell us about your interest in this property..." 
                              required></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Budget Range</label>
                        <select name="budget_range" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Not specified</option>
                            <option value="€50,000 - €100,000">€50,000 - €100,000</option>
                            <option value="€100,000 - €250,000">€100,000 - €250,000</option>
                            <option value="€250,000 - €500,000">€250,000 - €500,000</option>
                            <option value="€500,000+">€500,000+</option>
                        </select>
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
        // Toggle save property
        function toggleSave(propertyId) {
            fetch(`/client/properties/${propertyId}/toggle-save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                const btn = document.getElementById(`save-btn-${propertyId}`);
                if (data.action === 'saved') {
                    btn.className = btn.className.replace('bg-white text-gray-600', 'bg-red-500 text-white');
                } else {
                    btn.className = btn.className.replace('bg-red-500 text-white', 'bg-white text-gray-600');
                }
                
                // Show toast notification
                showToast(data.message, data.action === 'saved' ? 'success' : 'info');
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
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

        // Show toast notification
        function showToast(message, type = 'info') {
            // Create toast element
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
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            // Animate out and remove
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(toast);
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