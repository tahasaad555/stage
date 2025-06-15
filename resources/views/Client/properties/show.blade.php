@extends('layouts.client')

@section('title', $property->title)
@section('page-title', 'Property Details')

@section('content')
    <!-- Back Navigation -->
    <div class="mb-6">
        <a href="{{ route('client.properties.index') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Properties
        </a>
    </div>

    <!-- Property Header -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl mb-8 card-hover">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <span class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-medium capitalize mr-4">
                        {{ str_replace('_', ' ', $property->property_type) }}
                    </span>
                    @if($property->is_featured)
                        <span class="bg-yellow-500 text-white px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-star mr-1"></i>Featured
                        </span>
                    @endif
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $property->title }}</h1>
                <div class="flex items-center text-gray-600 mb-4">
                    <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                    <span class="text-lg">{{ $property->location }}</span>
                </div>
                <div class="text-3xl font-bold text-green-600">
                    €{{ number_format($property->price) }}
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button onclick="toggleSave({{ $property->id }})" 
                        class="w-12 h-12 rounded-xl {{ $isSaved ? 'bg-red-500 text-white' : 'bg-white text-gray-600 border border-gray-300' }} flex items-center justify-center hover:scale-110 transition-all shadow-lg"
                        id="save-btn-{{ $property->id }}">
                    <i class="fas fa-heart"></i>
                </button>
                <button onclick="showInquiryModal()" 
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium">
                    <i class="fas fa-envelope mr-2"></i>Send Inquiry
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Property Images -->
            <div class="glass-effect rounded-2xl overflow-hidden shadow-xl">
                @if($property->images && $property->images->count() > 0)
                    <div class="relative">
                        <!-- Main Image -->
                        <div class="h-96 bg-gray-200" id="mainImageContainer">
                            <img src="{{ $property->images->first()->image_url }}" 
                                 alt="{{ $property->title }}"
                                 class="w-full h-full object-cover"
                                 id="mainImage">
                        </div>
                        
                        <!-- Image Gallery Thumbnails -->
                        @if($property->images->count() > 1)
                            <div class="p-4 bg-white">
                                <div class="flex space-x-3 overflow-x-auto">
                                    @foreach($property->images as $index => $image)
                                        <button onclick="changeMainImage('{{ $image->image_url }}')" 
                                                class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 {{ $index === 0 ? 'border-blue-500' : 'border-gray-300' }} hover:border-blue-500 transition-colors"
                                                data-image-btn="{{ $index }}">
                                            <img src="{{ $image->image_url }}" 
                                                 alt="Property image {{ $index + 1 }}"
                                                 class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="h-96 bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                        <div class="text-center text-white">
                            <i class="fas fa-home text-6xl mb-4"></i>
                            <p class="text-xl font-medium">No images available</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Property Description -->
            <div class="glass-effect rounded-2xl p-8 shadow-xl">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-custom-1 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-info-circle text-white text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">📄 Property Description</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700">
                    {!! nl2br(e($property->description)) !!}
                </div>
            </div>

            <!-- Property Features -->
            @if($property->features)
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-custom-2 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-list text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">✨ Features & Amenities</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(explode(',', $property->features) as $feature)
                            <div class="flex items-center p-3 bg-white bg-opacity-50 rounded-lg">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span class="text-gray-700 font-medium">{{ trim($feature) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Related Properties -->
            @if($relatedProperties->count() > 0)
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-custom-3 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-home text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">🏡 Similar Properties</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($relatedProperties as $relatedProperty)
                            <div class="bg-white bg-opacity-50 rounded-xl p-4 hover:bg-opacity-70 transition-all">
                                <div class="flex items-start space-x-4">
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                        @if($relatedProperty->images && $relatedProperty->images->count() > 0)
                                            <img src="{{ $relatedProperty->images->first()->image_url }}" 
                                                 alt="{{ $relatedProperty->title }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                                                <i class="fas fa-home text-white text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-800 mb-1">{{ Str::limit($relatedProperty->title, 40) }}</h4>
                                        <p class="text-sm text-gray-600 mb-2">{{ $relatedProperty->location }}</p>
                                        <p class="text-lg font-bold text-green-600">€{{ number_format($relatedProperty->price) }}</p>
                                        <a href="{{ route('client.properties.show', $relatedProperty) }}" 
                                           class="inline-block mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View Details →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Property Details -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-custom-4 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-ruler-combined text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">📊 Property Details</h3>
                </div>
                
                <div class="space-y-4">
                    @if($property->area)
                        <div class="flex items-center justify-between p-3 bg-white bg-opacity-50 rounded-lg">
                            <span class="text-gray-600 font-medium">Area:</span>
                            <span class="text-gray-800 font-bold">{{ number_format($property->area) }} {{ $property->area_unit ?? 'hectares' }}</span>
                        </div>
                    @endif
                    
                    <div class="flex items-center justify-between p-3 bg-white bg-opacity-50 rounded-lg">
                        <span class="text-gray-600 font-medium">Type:</span>
                        <span class="text-gray-800 font-bold capitalize">{{ str_replace('_', ' ', $property->property_type) }}</span>
                    </div>
                    
                    @if($property->water_source)
                        <div class="flex items-center justify-between p-3 bg-white bg-opacity-50 rounded-lg">
                            <span class="text-gray-600 font-medium">Water Source:</span>
                            <span class="text-gray-800 font-bold capitalize">{{ $property->water_source }}</span>
                        </div>
                    @endif
                    
                    @if($property->soil_type)
                        <div class="flex items-center justify-between p-3 bg-white bg-opacity-50 rounded-lg">
                            <span class="text-gray-600 font-medium">Soil Type:</span>
                            <span class="text-gray-800 font-bold capitalize">{{ $property->soil_type }}</span>
                        </div>
                    @endif
                    
                    <div class="flex items-center justify-between p-3 bg-white bg-opacity-50 rounded-lg">
                        <span class="text-gray-600 font-medium">Listed:</span>
                        <span class="text-gray-800 font-bold">{{ $property->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Property Owner -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-custom-5 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">👤 Property Owner</h3>
                </div>
                
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold text-xl">
                            {{ substr($property->user->first_name, 0, 1) }}{{ substr($property->user->last_name, 0, 1) }}
                        </span>
                    </div>
                    <h4 class="font-bold text-gray-800 text-lg">{{ $property->user->full_name }}</h4>
                    @if($property->user->fournisseur)
                        <p class="text-gray-600">{{ $property->user->fournisseur->company_name }}</p>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium mt-2 inline-block">Verified Supplier</span>
                    @endif
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-envelope text-blue-500 mr-3 w-4"></i>
                        <span>{{ $property->user->email }}</span>
                    </div>
                    @if($property->user->phone)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-phone text-green-500 mr-3 w-4"></i>
                            <span>{{ $property->user->phone }}</span>
                        </div>
                    @endif
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar text-purple-500 mr-3 w-4"></i>
                        <span>Member since {{ $property->user->created_at->format('M Y') }}</span>
                    </div>
                </div>
                
                <div class="mt-6 space-y-3">
                    <button onclick="showInquiryModal()" 
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium">
                        <i class="fas fa-envelope mr-2"></i>Send Message
                    </button>
                    @if($property->user->phone)
                        <a href="tel:{{ $property->user->phone }}" 
                           class="w-full bg-green-600 text-white py-3 rounded-xl hover:bg-green-700 transition-all font-medium text-center block">
                            <i class="fas fa-phone mr-2"></i>Call Now
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-custom-6 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-bolt text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">⚡ Quick Actions</h3>
                </div>
                
                <div class="space-y-3">
                    <button onclick="shareProperty()" 
                            class="w-full bg-blue-100 text-blue-700 py-3 rounded-xl hover:bg-blue-200 transition-all font-medium">
                        <i class="fas fa-share-alt mr-2"></i>Share Property
                    </button>
                    <button onclick="printProperty()" 
                            class="w-full bg-gray-100 text-gray-700 py-3 rounded-xl hover:bg-gray-200 transition-all font-medium">
                        <i class="fas fa-print mr-2"></i>Print Details
                    </button>
                    <button onclick="reportProperty()" 
                            class="w-full bg-red-100 text-red-700 py-3 rounded-xl hover:bg-red-200 transition-all font-medium">
                        <i class="fas fa-flag mr-2"></i>Report Issue
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Inquiry Modal -->
    <div id="inquiryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Send Inquiry</h3>
                <button onclick="closeInquiryModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('client.properties.inquire', $property) }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" 
                           name="subject" 
                           value="Inquiry about: {{ $property->title }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           required>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Budget Range (Optional)</label>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" 
                              rows="6" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                              placeholder="Please provide details about your interest in this property, any specific questions you have, preferred contact method, and when you would like to be contacted..." 
                              required></textarea>
                </div>
                
                <div class="flex space-x-4">
                    <button type="button" 
                            onclick="closeInquiryModal()" 
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
        // Image gallery functionality
        function changeMainImage(imageUrl) {
            document.getElementById('mainImage').src = imageUrl;
            
            // Update active thumbnail
            document.querySelectorAll('[data-image-btn]').forEach((btn, index) => {
                btn.classList.remove('border-blue-500');
                btn.classList.add('border-gray-300');
            });
            
            event.target.closest('[data-image-btn]').classList.remove('border-gray-300');
            event.target.closest('[data-image-btn]').classList.add('border-blue-500');
        }

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
                    btn.className = btn.className.replace('bg-white text-gray-600 border border-gray-300', 'bg-red-500 text-white');
                } else {
                    btn.className = btn.className.replace('bg-red-500 text-white', 'bg-white text-gray-600 border border-gray-300');
                }
                
                showToast(data.message, data.action === 'saved' ? 'success' : 'info');
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
            });
        }

        // Show inquiry modal
        function showInquiryModal() {
            document.getElementById('inquiryModal').classList.remove('hidden');
        }

        // Close inquiry modal
        function closeInquiryModal() {
            document.getElementById('inquiryModal').classList.add('hidden');
        }

        // Share property
        function shareProperty() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $property->title }}',
                    text: 'Check out this amazing property: {{ $property->title }}',
                    url: window.location.href
                });
            } else {
                // Fallback to copying URL
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showToast('Property link copied to clipboard!', 'success');
                });
            }
        }

        // Print property
        function printProperty() {
            window.print();
        }

        // Report property
        function reportProperty() {
            showToast('Report functionality will be implemented soon.', 'info');
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