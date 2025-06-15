@extends('layouts.client')

@section('title', 'Saved Properties')
@section('page-title', 'Saved Properties')

@section('content')
    <!-- Header Section -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl mb-8 card-hover">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-custom-2 rounded-2xl flex items-center justify-center mr-6">
                    <i class="fas fa-heart text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold mb-2">❤️ Saved Properties</h1>
                    <p class="text-xl opacity-90">Your bookmarked properties</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg opacity-75">Total Saved</div>
                <div class="text-3xl font-bold">{{ $savedProperties->total() }}</div>
            </div>
        </div>
    </div>

    @if($savedProperties->count() > 0)
        <!-- Saved Properties Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($savedProperties as $savedProperty)
                @php $property = $savedProperty->property; @endphp
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
                        
                        <!-- Remove from Saved Button -->
                        <button onclick="removeSaved({{ $property->id }})" 
                                class="absolute top-3 right-3 w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 hover:scale-110 transition-all shadow-lg"
                                title="Remove from saved">
                            <i class="fas fa-times"></i>
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

                        <!-- Saved Date -->
                        <div class="absolute bottom-3 right-3">
                            <span class="bg-white bg-opacity-90 text-gray-700 px-2 py-1 rounded text-xs font-medium">
                                Saved {{ $savedProperty->created_at->diffForHumans() }}
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
            {{ $savedProperties->links() }}
        </div>

        <!-- Quick Actions -->
        <div class="glass-effect rounded-2xl p-6 shadow-xl mt-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">⚡ Quick Actions</h3>
                    <p class="text-gray-600">Manage your saved properties</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="exportSaved()" 
                            class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all font-medium">
                        <i class="fas fa-download mr-2"></i>Export List
                    </button>
                    <button onclick="clearAllSaved()" 
                            class="bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-all font-medium">
                        <i class="fas fa-trash mr-2"></i>Clear All
                    </button>
                </div>
            </div>
        </div>
    @else
        <!-- No Saved Properties -->
        <div class="glass-effect rounded-2xl p-12 shadow-xl text-center">
            <div class="w-24 h-24 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-heart text-white text-3xl"></i>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-4">No Saved Properties</h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                You haven't saved any properties yet. Start browsing our amazing collection of agricultural properties and save your favorites for later!
            </p>
            <a href="{{ route('client.properties.index') }}" 
               class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium text-lg">
                <i class="fas fa-search mr-2"></i>Browse Properties
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
        // Remove from saved
        function removeSaved(propertyId) {
            if (confirm('Are you sure you want to remove this property from your saved list?')) {
                fetch(`/client/properties/${propertyId}/toggle-save`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.action === 'removed') {
                        showToast(data.message, 'success');
                        // Reload page to update the list
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.', 'error');
                });
            }
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

        // Export saved properties
        function exportSaved() {
            showToast('Export functionality will be implemented soon.', 'info');
        }

        // Clear all saved properties
        function clearAllSaved() {
            if (confirm('Are you sure you want to remove ALL saved properties? This action cannot be undone.')) {
                showToast('Clear all functionality will be implemented soon.', 'info');
            }
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