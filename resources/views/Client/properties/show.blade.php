@extends('layouts.client')

@section('title', $property->titre ?: $property->title)
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
                    @if($property->terreAgricole && $property->terreAgricole->soil_type)
                        <span class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-medium capitalize mr-4">
                            {{ $property->terreAgricole->soil_type }} Soil
                        </span>
                    @endif
                    @if($property->is_featured)
                        <span class="bg-yellow-500 text-white px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-star mr-1"></i>Featured
                        </span>
                    @endif
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $property->titre ?: $property->title }}</h1>
                
                @if($property->terreAgricole)
                    <div class="flex items-center text-gray-600 mb-4">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                        <span class="text-lg">{{ $property->terreAgricole->region }}</span>
                        @if($property->terreAgricole->localisation && $property->terreAgricole->localisation != $property->terreAgricole->region)
                            <span class="text-lg">, {{ $property->terreAgricole->localisation }}</span>
                        @endif
                    </div>
                    
                    <div class="text-3xl font-bold text-green-600">
                        @php
                            $price = $property->prix ?: ($property->terreAgricole->price ?? 0);
                        @endphp
                        {{ number_format($price) }} MAD
                        @if($property->terreAgricole->surface)
                            <div class="text-lg text-gray-600 font-normal">
                                {{ number_format($price / $property->terreAgricole->surface) }} MAD per hectare
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-lg text-red-600">Property details not available</div>
                @endif
            </div>
            
            <div class="flex space-x-3">
                <button onclick="toggleSave({{ $property->id }})" 
                        id="save-btn-{{ $property->id }}"
                        class="w-12 h-12 {{ $isSaved ? 'bg-red-500 text-white' : 'bg-white text-gray-600 border border-gray-300' }} rounded-xl hover:scale-110 transition-all flex items-center justify-center"
                        title="{{ $isSaved ? 'Remove from saved' : 'Save property' }}">
                    <i class="{{ $isSaved ? 'fas fa-heart' : 'far fa-heart' }}"></i>
                </button>
                
                <button onclick="shareProperty()" 
                        class="w-12 h-12 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all flex items-center justify-center"
                        title="Share property">
                    <i class="fas fa-share-alt"></i>
                </button>
                
                <button onclick="printProperty()" 
                        class="w-12 h-12 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all flex items-center justify-center"
                        title="Print property details">
                    <i class="fas fa-print"></i>
                </button>
                
                <button onclick="reportProperty()" 
                        class="w-12 h-12 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all flex items-center justify-center"
                        title="Report property">
                    <i class="fas fa-flag"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Property Images -->
            @if($property->terreAgricole && $property->terreAgricole->photos && count($property->terreAgricole->photos) > 0)
                <div class="glass-effect rounded-2xl p-6 shadow-xl">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Property Images</h3>
                    
                    <!-- Main Image -->
                    <div class="mb-4">
                        <img id="mainImage" 
                             src="{{ asset('storage/' . $property->terreAgricole->photos[0]) }}" 
                             alt="{{ $property->titre ?: $property->title }}"
                             class="w-full h-96 object-cover rounded-xl">
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    @if(count($property->terreAgricole->photos) > 1)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($property->terreAgricole->photos as $index => $photo)
                                <button onclick="changeMainImage('{{ asset('storage/' . $photo) }}')" 
                                        data-image-btn
                                        class="border-2 {{ $index === 0 ? 'border-blue-500' : 'border-gray-300' }} rounded-lg overflow-hidden hover:border-blue-500 transition-colors">
                                    <img src="{{ asset('storage/' . $photo) }}" 
                                         alt="Property image {{ $index + 1 }}"
                                         class="w-full h-20 object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <!-- Property Description -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Description</h3>
                <div class="prose max-w-none text-gray-700 property-description">
                    {!! nl2br(e($property->description)) !!}
                </div>
            </div>

            <!-- Property Features -->
            @if($property->terreAgricole)
                <div class="glass-effect rounded-2xl p-6 shadow-xl">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Property Features</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 property-features">
                        
                        <!-- Surface Area -->
                        @if($property->terreAgricole->surface)
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-ruler-combined text-green-500 mr-3"></i>
                                    <h4 class="font-bold text-gray-800">Surface Area</h4>
                                </div>
                                <p class="text-2xl font-bold text-green-600">{{ number_format($property->terreAgricole->surface, 1) }} hectares</p>
                            </div>
                        @endif

                        <!-- Soil Type -->
                        @if($property->terreAgricole->soil_type)
                            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-4 border border-yellow-200">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-seedling text-yellow-500 mr-3"></i>
                                    <h4 class="font-bold text-gray-800">Soil Type</h4>
                                </div>
                                <p class="text-xl font-bold text-yellow-600 capitalize">{{ $property->terreAgricole->soil_type }}</p>
                            </div>
                        @endif

                        <!-- GPS Coordinates -->
                        @if($property->terreAgricole->gps_coordinates)
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-4 border border-blue-200">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-map-pin text-blue-500 mr-3"></i>
                                    <h4 class="font-bold text-gray-800">GPS Coordinates</h4>
                                </div>
                                <p class="text-sm font-mono text-blue-600">{{ $property->terreAgricole->gps_coordinates }}</p>
                            </div>
                        @endif

                        <!-- Status -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-flag text-purple-500 mr-3"></i>
                                <h4 class="font-bold text-gray-800">Status</h4>
                            </div>
                            <p class="text-xl font-bold text-purple-600 capitalize">
                                {{ $property->terreAgricole->status ?? 'Available' }}
                            </p>
                        </div>
                        
                    </div>
                </div>
            @endif

            <!-- Location Details -->
            @if($property->terreAgricole && ($property->terreAgricole->gps_coordinates || $property->terreAgricole->region))
                <div class="glass-effect rounded-2xl p-6 shadow-xl">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Location Details</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-globe text-blue-500 mr-3"></i>
                            <span class="font-semibold">Country:</span>
                            <span class="ml-2">{{ $property->terreAgricole->country ?? 'Morocco' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-green-500 mr-3"></i>
                            <span class="font-semibold">Region:</span>
                            <span class="ml-2">{{ $property->terreAgricole->region }}</span>
                        </div>
                        @if($property->terreAgricole->localisation && $property->terreAgricole->localisation != $property->terreAgricole->region)
                            <div class="flex items-center">
                                <i class="fas fa-location-arrow text-orange-500 mr-3"></i>
                                <span class="font-semibold">Specific Location:</span>
                                <span class="ml-2">{{ $property->terreAgricole->localisation }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Contact Card -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Property Owner</h3>
                
                @if($property->fournisseur)
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">
                                    {{ $property->fournisseur->company_name ?? $property->fournisseur->user->full_name ?? 'N/A' }}
                                </h4>
                                @if($property->fournisseur->company_name && $property->fournisseur->user)
                                    <p class="text-sm text-gray-600">{{ $property->fournisseur->user->full_name }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <button onclick="showInquiryModal()" 
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium">
                            <i class="fas fa-envelope mr-2"></i>Send Inquiry
                        </button>
                    </div>
                @else
                    <p class="text-gray-600">Owner information not available</p>
                @endif
            </div>

            <!-- Quick Stats -->
            @if($property->terreAgricole)
                <div class="glass-effect rounded-2xl p-6 shadow-xl">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Stats</h3>
                    <div class="space-y-4">
                        @if($property->terreAgricole->surface)
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <span class="font-medium text-gray-700">Total Area</span>
                                <span class="font-bold text-green-600">{{ number_format($property->terreAgricole->surface, 1) }} ha</span>
                            </div>
                        @endif
                        
                        @php
                            $price = $property->prix ?: ($property->terreAgricole->price ?? 0);
                        @endphp
                        <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                            <span class="font-medium text-gray-700">Total Price</span>
                            <span class="font-bold text-blue-600">{{ number_format($price) }} MAD</span>
                        </div>
                        
                        @if($property->terreAgricole->surface && $price > 0)
                            <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                                <span class="font-medium text-gray-700">Price/Hectare</span>
                                <span class="font-bold text-purple-600">{{ number_format($price / $property->terreAgricole->surface) }} MAD</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                            <span class="font-medium text-gray-700">Listed</span>
                            <span class="font-bold text-yellow-600">{{ $property->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Related Properties -->
            @if($relatedProperties->count() > 0)
                <div class="glass-effect rounded-2xl p-6 shadow-xl">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Related Properties</h3>
                    <div class="space-y-4">
                        @foreach($relatedProperties->take(3) as $related)
                            <a href="{{ route('client.properties.show', $related) }}" 
                               class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <h4 class="font-medium text-gray-800 mb-1">{{ $related->titre ?: $related->title }}</h4>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>{{ $related->terreAgricole->region ?? 'N/A' }}</span>
                                    @php
                                        $relatedPrice = $related->prix ?: ($related->terreAgricole->price ?? 0);
                                    @endphp
                                    <span class="font-bold">{{ number_format($relatedPrice) }} MAD</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Send Inquiry Modal -->
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
                           value="Inquiry about: {{ $property->titre ?: $property->title }}"
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
                    <textarea name="message" rows="6" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Please provide details about your inquiry, specific questions, or requirements..."
                              required></textarea>
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
        // Image gallery functionality
        function changeMainImage(imageUrl) {
            document.getElementById('mainImage').src = imageUrl;
            
            // Update active thumbnail
            document.querySelectorAll('[data-image-btn]').forEach((btn) => {
                btn.classList.remove('border-blue-500');
                btn.classList.add('border-gray-300');
            });
            
            event.target.closest('[data-image-btn]').classList.remove('border-gray-300');
            event.target.closest('[data-image-btn]').classList.add('border-blue-500');
        }

        // Enhanced toggle save property
        function toggleSave(propertyId) {
            const btn = document.getElementById(`save-btn-${propertyId}`);
            if (!btn) return;
            
            const icon = btn.querySelector('i');
            const originalClass = icon.className;
            
            // Show loading state
            icon.className = 'fas fa-spinner fa-spin';
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
                        btn.className = 'w-12 h-12 bg-red-500 text-white rounded-xl hover:scale-110 transition-all flex items-center justify-center';
                        icon.className = 'fas fa-heart';
                        btn.title = 'Remove from saved';
                    } else {
                        btn.className = 'w-12 h-12 bg-white text-gray-600 border border-gray-300 rounded-xl hover:scale-110 transition-all flex items-center justify-center';
                        icon.className = 'far fa-heart';
                        btn.title = 'Save property';
                    }
                    showToast(data.message, data.action === 'saved' ? 'success' : 'info');
                } else {
                    icon.className = originalClass;
                    showToast('An error occurred. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                icon.className = originalClass;
                showToast('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                btn.disabled = false;
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

        // Enhanced share property function
        function shareProperty() {
            const propertyTitle = document.querySelector('h1').textContent;
            const propertyUrl = window.location.href;
            
            if (navigator.share) {
                navigator.share({
                    title: propertyTitle,
                    text: `Check out this amazing property: ${propertyTitle}`,
                    url: propertyUrl
                }).then(() => {
                    showToast('Property shared successfully!', 'success');
                }).catch((error) => {
                    if (error.name !== 'AbortError') {
                        fallbackShare(propertyTitle, propertyUrl);
                    }
                });
            } else {
                fallbackShare(propertyTitle, propertyUrl);
            }
        }

        function fallbackShare(title, url) {
            // Create share modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-2xl p-6 max-w-md w-full">
                    <h3 class="text-xl font-bold mb-4">Share Property</h3>
                    <div class="space-y-3">
                        <button onclick="copyToClipboard('${url}')" class="w-full bg-blue-600 text-white p-3 rounded-xl hover:bg-blue-700 transition-all">
                            <i class="fas fa-copy mr-2"></i>Copy Link
                        </button>
                        <a href="mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent(url)}" 
                           class="block w-full bg-gray-600 text-white p-3 rounded-xl hover:bg-gray-700 transition-all text-center">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </a>
                        <a href="https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}" 
                           target="_blank" 
                           class="block w-full bg-green-600 text-white p-3 rounded-xl hover:bg-green-700 transition-all text-center">
                            <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                        </a>
                    </div>
                    <button onclick="this.closest('.fixed').remove()" 
                            class="w-full mt-4 bg-gray-200 text-gray-700 p-3 rounded-xl hover:bg-gray-300 transition-all">
                        Close
                    </button>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Close on outside click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.remove();
                }
            });
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Link copied to clipboard!', 'success');
                document.querySelector('.fixed').remove();
            }).catch(() => {
                showToast('Could not copy link. Please copy manually.', 'error');
            });
        }

        // Enhanced print property function
        function printProperty() {
            const printWindow = window.open('', '_blank');
            const propertyData = {
                title: document.querySelector('h1').textContent,
                price: document.querySelector('.text-3xl.font-bold.text-green-600')?.textContent || 'Price not available',
                location: document.querySelector('.fas.fa-map-marker-alt')?.parentElement?.textContent?.replace('🗺️', '').trim() || 'Location not available',
                description: document.querySelector('.property-description')?.textContent || 'Description not available'
            };
            
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Property Details - ${propertyData.title}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; color: #333; line-height: 1.6; }
                        .header { border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 20px; }
                        .title { font-size: 28px; font-weight: bold; color: #007bff; margin-bottom: 10px; }
                        .price { font-size: 24px; font-weight: bold; color: #28a745; margin: 10px 0; }
                        .location { color: #666; margin-bottom: 20px; font-size: 16px; }
                        .section { margin: 20px 0; }
                        .section h3 { color: #007bff; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
                        .footer { margin-top: 40px; text-align: center; color: #666; font-size: 14px; }
                        @media print { 
                            .no-print { display: none; }
                            body { margin: 0; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="title">${propertyData.title}</div>
                        <div class="price">${propertyData.price}</div>
                        <div class="location">${propertyData.location}</div>
                    </div>
                    
                    <div class="section">
                        <h3>Description</h3>
                        <p>${propertyData.description}</p>
                    </div>
                    
                    <div class="footer">
                        <p>Printed on ${new Date().toLocaleDateString()}</p>
                        <p>Visit us at: ${window.location.origin}</p>
                    </div>
                </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
            
            showToast('Print dialog opened!', 'success');
        }

        // Report property function
        function reportProperty() {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-2xl p-6 max-w-md w-full">
                    <h3 class="text-xl font-bold mb-4">Report Property</h3>
                    <form id="reportForm">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for reporting</label>
                                <select name="reason" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Select a reason</option>
                                    <option value="inappropriate_content">Inappropriate content</option>
                                    <option value="misleading_info">Misleading information</option>
                                    <option value="duplicate_listing">Duplicate listing</option>
                                    <option value="property_sold">Property already sold</option>
                                    <option value="spam">Spam</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Additional details</label>
                                <textarea name="details" rows="3" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500"
                                        placeholder="Please provide more details..."></textarea>
                            </div>
                        </div>
                        <div class="flex space-x-3 mt-6">
                            <button type="button" onclick="this.closest('.fixed').remove()" 
                                    class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl hover:bg-gray-300 transition-all">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="flex-1 bg-red-600 text-white py-3 rounded-xl hover:bg-red-700 transition-all">
                                Submit Report
                            </button>
                        </div>
                    </form>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Handle form submission
            modal.querySelector('#reportForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                // Submit report via API
                fetch(`/client/properties/{{ $property->id }}/report`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showToast(data.message, 'success');
                    modal.remove();
                })
                .catch(error => {
                    showToast('Error submitting report. Please try again.', 'error');
                });
            });
            
            // Close on outside click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.remove();
                }
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

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeInquiryModal();
                // Close any other modals
                document.querySelectorAll('.fixed.inset-0').forEach(modal => {
                    if (!modal.querySelector('#inquiryModal')) {
                        modal.remove();
                    }
                });
            }
        });
    </script>
@endsection