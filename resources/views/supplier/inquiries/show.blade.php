@extends('layouts.supplier')

@section('title', 'Inquiry Details')

@push('styles')
<style>
/* Enhanced Glass Effect Styles */
.glass-effect {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.dark-glass {
    background: rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Loading Overlay */
.loading-overlay {
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
}

/* Card Hover Effects */
.card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Priority Badges */
.priority-low { @apply bg-gray-100 text-gray-600; }
.priority-medium { @apply bg-blue-100 text-blue-600; }
.priority-high { @apply bg-orange-100 text-orange-600; }
.priority-urgent { @apply bg-red-100 text-red-600; }

/* Status Badges */
.status-new { @apply bg-blue-100 text-blue-800; }
.status-read { @apply bg-yellow-100 text-yellow-800; }
.status-responded { @apply bg-green-100 text-green-800; }
.status-closed { @apply bg-gray-100 text-gray-800; }
.status-spam { @apply bg-red-100 text-red-800; }

/* Inquiry Type Badges */
.type-general { @apply bg-purple-100 text-purple-600; }
.type-purchase { @apply bg-green-100 text-green-600; }
.type-lease { @apply bg-blue-100 text-blue-600; }
.type-partnership { @apply bg-indigo-100 text-indigo-600; }
.type-information { @apply bg-gray-100 text-gray-600; }

/* Animation for notifications */
@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.notification-slide {
    animation: slideInRight 0.3s ease-out;
}

/* Response form styling */
.response-form {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

/* Character counter styling */
.char-counter {
    transition: color 0.3s ease;
}

.char-limit-warning {
    color: #f59e0b;
}

.char-limit-exceeded {
    color: #ef4444;
}

/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <a href="{{ route('supplier.inquiries.index') }}" class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-6 hover:bg-opacity-30 transition-all">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="flex-1">
                        <h1 class="text-4xl font-bold mb-2">📧 Inquiry Details</h1>
                        <p class="text-xl opacity-90">Review and respond to client inquiry</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Status and Priority Badges -->
                <div class="text-right">
                    <div class="flex space-x-3 mb-2">
                        <span class="status-{{ $inquiry->status }} text-white px-4 py-2 rounded-full text-sm font-bold">
                            {{ strtoupper($inquiry->status) }}
                        </span>
                        <span class="priority-{{ $inquiry->priority }} text-white px-4 py-2 rounded-full text-sm font-bold">
                            {{ strtoupper($inquiry->priority) }}
                        </span>
                        <span class="type-{{ $inquiry->inquiry_type }} text-white px-4 py-2 rounded-full text-sm font-bold">
                            {{ $inquiry->formatted_type }}
                        </span>
                    </div>
                    <div class="text-sm opacity-75">
                        Received {{ $inquiry->created_at->format('M d, Y \a\t g:i A') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="glass-effect rounded-xl p-6 border-l-4 border-green-500 shadow-xl">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Success!</h4>
                    <p class="text-gray-600">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="glass-effect rounded-xl p-6 border-l-4 border-red-500 shadow-xl">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Error!</h4>
                    <p class="text-gray-600">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Inquiry Information -->
            <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-envelope text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">📋 Inquiry Information</h2>
                            <p class="text-gray-600">Client inquiry details and message</p>
                        </div>
                    </div>
                </div>

                <!-- Subject -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <div class="p-4 bg-gray-50 rounded-xl border-2 border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800">{{ $inquiry->subject }}</h3>
                    </div>
                </div>

                <!-- Message -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <div class="p-6 bg-gray-50 rounded-xl border-2 border-gray-200">
                        <div class="prose prose-gray max-w-none">
                            {!! nl2br(e($inquiry->message)) !!}
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                @if($inquiry->budget_range || $inquiry->preferred_contact_method)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        @if($inquiry->budget_range)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Budget Range</label>
                                <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                                    <span class="text-green-800 font-medium">
                                        <i class="fas fa-dollar-sign mr-1"></i>{{ $inquiry->budget_range }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Contact</label>
                            <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <span class="text-blue-800 font-medium">
                                    <i class="fas fa-{{ $inquiry->preferred_contact_method === 'phone' ? 'phone' : ($inquiry->preferred_contact_method === 'email' ? 'envelope' : 'comments') }} mr-1"></i>
                                    {{ ucfirst($inquiry->preferred_contact_method) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Notes (if any) -->
                @if($inquiry->notes)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Internal Notes</label>
                        <div class="p-4 bg-yellow-50 rounded-xl border-2 border-yellow-200">
                            <p class="text-yellow-800">{{ $inquiry->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Response History -->
                @if($inquiry->status === 'responded' && $inquiry->response_message)
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-reply mr-2 text-green-500"></i>Your Response
                        </h3>
                        <div class="p-6 bg-green-50 rounded-xl border-2 border-green-200">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm text-green-600 font-medium">
                                    Sent on {{ $inquiry->responded_at->format('M d, Y \a\t g:i A') }}
                                </span>
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                                    RESPONDED
                                </span>
                            </div>
                            <div class="prose prose-green max-w-none">
                                {!! nl2br(e($inquiry->response_message)) !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Property Information -->
            <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-map-marker-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">🏞️ Property Details</h2>
                            <p class="text-gray-600">The property this inquiry is about</p>
                        </div>
                    </div>
                    <a href="{{ route('supplier.properties.show', $inquiry->annonce) }}" 
                       class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all">
                        View Property
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Property Basic Info -->
                    <div>
                        <h4 class="font-bold text-gray-800 mb-3">{{ $inquiry->annonce->title ?? $inquiry->annonce->titre }}</h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            @if($inquiry->annonce->terreAgricole)
                                <p><i class="fas fa-map-marker-alt mr-2"></i>{{ $inquiry->annonce->terreAgricole->region }}</p>
                                <p><i class="fas fa-ruler-combined mr-2"></i>{{ number_format($inquiry->annonce->terreAgricole->surface, 1) }} hectares</p>
                                <p><i class="fas fa-seedling mr-2"></i>{{ ucfirst($inquiry->annonce->terreAgricole->type_culture) }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Property Pricing -->
                    <div>
                        @if($inquiry->annonce->terreAgricole)
                            <h4 class="font-bold text-gray-800 mb-3">Pricing Information</h4>
                            <div class="space-y-2">
                                <div class="text-2xl font-bold text-green-600">
                                    €{{ number_format($inquiry->annonce->terreAgricole->price) }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    €{{ number_format($inquiry->annonce->terreAgricole->price / $inquiry->annonce->terreAgricole->surface) }} per hectare
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Response Form -->
            @if($inquiry->status !== 'responded')
                <div class="glass-effect rounded-2xl p-8 shadow-xl response-form">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-reply text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">✉️ Send Response</h2>
                            <p class="text-gray-600">Respond to the client's inquiry</p>
                        </div>
                    </div>

                    <form action="{{ route('supplier.inquiries.send-response', $inquiry) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Response Message -->
                        <div>
                            <label for="response_message" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-comment mr-2"></i>Your Response *
                            </label>
                            <textarea id="response_message" name="response_message" rows="8" required
                                      placeholder="Type your response to the client here..."
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none @error('response_message') border-red-300 ring-red-100 @enderror"
                                      oninput="updateCharCounter('response_message', 2000)">{{ old('response_message') }}</textarea>
                            <div class="flex justify-between items-center mt-2">
                                <div class="char-counter text-sm text-gray-500" id="response_message-counter">0/2000</div>
                                @error('response_message')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-phone mr-2"></i>Follow-up Contact Method
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 transition-all">
                                    <input type="radio" name="contact_method" value="email" 
                                           {{ old('contact_method', 'email') === 'email' ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Email Only</span>
                                </label>
                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 transition-all">
                                    <input type="radio" name="contact_method" value="phone" 
                                           {{ old('contact_method') === 'phone' ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Phone Call</span>
                                </label>
                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 transition-all">
                                    <input type="radio" name="contact_method" value="both" 
                                           {{ old('contact_method') === 'both' ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Both</span>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4">
                            <button type="button" onclick="saveDraft()" 
                                    class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-medium">
                                <i class="fas fa-save mr-2"></i>Save Draft
                            </button>
                            <button type="submit" 
                                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium">
                                <i class="fas fa-paper-plane mr-2"></i>Send Response
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Client Information -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">👤 Client Information</h3>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Name</label>
                        <p class="font-medium text-gray-800">{{ $inquiry->client_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                        <a href="mailto:{{ $inquiry->client_email }}" 
                           class="text-blue-600 hover:text-blue-800 transition-colors break-all">
                            {{ $inquiry->client_email }}
                        </a>
                    </div>
                    
                    @if($inquiry->client_phone)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Phone</label>
                            <a href="tel:{{ $inquiry->client_phone }}" 
                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                {{ $inquiry->client_phone }}
                            </a>
                        </div>
                    @endif
                    
                    <!-- Client Type -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Client Type</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $inquiry->client ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $inquiry->client ? 'Registered User' : 'Guest' }}
                        </span>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex space-x-2">
                        <a href="mailto:{{ $inquiry->client_email }}" 
                           class="flex-1 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-all text-center text-sm font-medium">
                            <i class="fas fa-envelope mr-1"></i>Email
                        </a>
                        @if($inquiry->client_phone)
                            <a href="tel:{{ $inquiry->client_phone }}" 
                               class="flex-1 px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-all text-center text-sm font-medium">
                                <i class="fas fa-phone mr-1"></i>Call
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Inquiry Management -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-cogs text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">⚙️ Manage Inquiry</h3>
                </div>

                <!-- Status Update -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                        <select onchange="updateStatus({{ $inquiry->id }}, this.value)" 
                                class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="new" {{ $inquiry->status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="read" {{ $inquiry->status === 'read' ? 'selected' : '' }}>Read</option>
                            <option value="responded" {{ $inquiry->status === 'responded' ? 'selected' : '' }}>Responded</option>
                            <option value="closed" {{ $inquiry->status === 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="spam" {{ $inquiry->status === 'spam' ? 'selected' : '' }}>Spam</option>
                        </select>
                    </div>

                    <!-- Priority Update -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Update Priority</label>
                        <select onchange="updatePriority({{ $inquiry->id }}, this.value)" 
                                class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="low" {{ $inquiry->priority === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $inquiry->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $inquiry->priority === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ $inquiry->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Internal Notes</label>
                        <textarea id="inquiry-notes" rows="3" placeholder="Add private notes about this inquiry..."
                                  class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">{{ $inquiry->notes }}</textarea>
                        <button onclick="saveNotes()" class="mt-2 w-full px-3 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-all text-sm font-medium">
                            Save Notes
                        </button>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Danger Zone</h4>
                    <div class="space-y-2">
                        <button onclick="markAsSpam({{ $inquiry->id }})" 
                                class="w-full px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-all text-sm font-medium">
                            <i class="fas fa-flag mr-1"></i>Mark as Spam
                        </button>
                        <button onclick="deleteInquiry({{ $inquiry->id }})" 
                                class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i>Delete Inquiry
                        </button>
                    </div>
                </div>
            </div>

            <!-- Inquiry Timeline -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">📊 Timeline</h3>
                </div>

                <div class="space-y-4">
                    <!-- Received -->
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mt-1">
                            <i class="fas fa-envelope text-blue-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">Inquiry Received</p>
                            <p class="text-xs text-gray-500">{{ $inquiry->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>

                    @if($inquiry->status !== 'new')
                        <!-- Read -->
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mt-1">
                                <i class="fas fa-eye text-yellow-600 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Marked as Read</p>
                                <p class="text-xs text-gray-500">{{ $inquiry->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($inquiry->responded_at)
                        <!-- Responded -->
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mt-1">
                                <i class="fas fa-reply text-green-600 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Response Sent</p>
                                <p class="text-xs text-gray-500">{{ $inquiry->responded_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 loading-overlay flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-8 flex items-center space-x-4 shadow-2xl">
        <div class="animate-spin rounded-full h-8 w-8 border-4 border-purple-500 border-t-transparent"></div>
        <span class="text-gray-800 font-medium">Processing...</span>
    </div>
</div>

<!-- Notification Container -->
<div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
@endsection

@push('scripts')
<script>
// Show/Hide loading overlay
function showLoading() {
    document.getElementById('loading-overlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading-overlay').classList.add('hidden');
}

// Show notification
function showNotification(message, type = 'success') {
    const container = document.getElementById('notification-container');
    const notification = document.createElement('div');
    
    const bgColor = type === 'success' ? 'bg-green-500' : 
                   type === 'error' ? 'bg-red-500' : 
                   type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
    
    notification.className = `${bgColor} text-white px-6 py-4 rounded-xl shadow-lg notification-slide max-w-sm`;
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    container.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Character counter
function updateCharCounter(fieldId, maxLength) {
    const field = document.getElementById(fieldId);
    const counter = document.getElementById(fieldId + '-counter');
    
    if (field && counter) {
        const currentLength = field.value.length;
        counter.textContent = `${currentLength}/${maxLength}`;
        
        // Update counter color based on length
        counter.classList.remove('char-limit-warning', 'char-limit-exceeded');
        if (currentLength > maxLength * 0.8) {
            counter.classList.add('char-limit-warning');
        }
        if (currentLength > maxLength) {
            counter.classList.add('char-limit-exceeded');
        }
    }
}

// Initialize character counter
document.addEventListener('DOMContentLoaded', function() {
    const responseField = document.getElementById('response_message');
    if (responseField) {
        updateCharCounter('response_message', 2000);
    }
});

// Update inquiry status
function updateStatus(inquiryId, status) {
    showLoading();
    
    fetch(`/supplier/inquiries/${inquiryId}/update-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            status: status,
            notes: document.getElementById('inquiry-notes').value 
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification(data.message, 'success');
            // Update the status badge in the header
            const statusBadge = document.querySelector('.status-' + {{ json_encode($inquiry->status) }});
            if (statusBadge) {
                statusBadge.className = statusBadge.className.replace(/status-\w+/, `status-${status}`);
                statusBadge.textContent = status.toUpperCase();
            }
        } else {
            showNotification('Error updating inquiry status', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Error updating inquiry status', 'error');
    });
}

// Update priority
function updatePriority(inquiryId, priority) {
    showLoading();
    
    fetch(`/supplier/inquiries/${inquiryId}/update-priority`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ priority: priority })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification(data.message, 'success');
            // Update the priority badge in the header
            const priorityBadge = document.querySelector('.priority-' + {{ json_encode($inquiry->priority) }});
            if (priorityBadge) {
                priorityBadge.className = priorityBadge.className.replace(/priority-\w+/, `priority-${priority}`);
                priorityBadge.textContent = priority.toUpperCase();
            }
        } else {
            showNotification('Error updating priority', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Error updating priority', 'error');
    });
}

// Save notes
function saveNotes() {
    const notes = document.getElementById('inquiry-notes').value;
    const inquiryId = {{ $inquiry->id }};
    
    showLoading();
    
    fetch(`/supplier/inquiries/${inquiryId}/update-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            status: '{{ $inquiry->status }}',
            notes: notes 
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification('Notes saved successfully', 'success');
        } else {
            showNotification('Error saving notes', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Error saving notes', 'error');
    });
}

// Mark as spam
function markAsSpam(inquiryId) {
    if (!confirm('Are you sure you want to mark this inquiry as spam?')) {
        return;
    }
    
    showLoading();
    
    fetch(`/supplier/inquiries/${inquiryId}/mark-spam`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification(data.message, 'success');
            // Redirect back to inquiries list
            setTimeout(() => {
                window.location.href = '{{ route("supplier.inquiries.index") }}';
            }, 1500);
        } else {
            showNotification('Error marking as spam', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Error marking as spam', 'error');
    });
}

// Delete inquiry
function deleteInquiry(inquiryId) {
    if (!confirm('Are you sure you want to delete this inquiry? This action cannot be undone.')) {
        return;
    }
    
    showLoading();
    
    fetch(`/supplier/inquiries/${inquiryId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification(data.message, 'success');
            // Redirect back to inquiries list
            setTimeout(() => {
                window.location.href = '{{ route("supplier.inquiries.index") }}';
            }, 1500);
        } else {
            showNotification('Error deleting inquiry', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Error deleting inquiry', 'error');
    });
}

// Save draft functionality
function saveDraft() {
    const responseMessage = document.getElementById('response_message').value;
    const contactMethod = document.querySelector('input[name="contact_method"]:checked')?.value;
    
    if (!responseMessage.trim()) {
        showNotification('Please write a response message first', 'warning');
        return;
    }
    
    const draftData = {
        inquiry_id: {{ $inquiry->id }},
        response_message: responseMessage,
        contact_method: contactMethod,
        timestamp: new Date().toISOString()
    };
    
    localStorage.setItem(`inquiry_response_draft_${{{ $inquiry->id }}}`, JSON.stringify(draftData));
    showNotification('Draft saved successfully!', 'success');
}

// Load draft on page load
document.addEventListener('DOMContentLoaded', function() {
    const draftKey = `inquiry_response_draft_${{{ $inquiry->id }}}`;
    const draftData = localStorage.getItem(draftKey);
    
    if (draftData && document.getElementById('response_message')) {
        try {
            const data = JSON.parse(draftData);
            
            if (confirm('A draft response was found. Would you like to restore it?')) {
                document.getElementById('response_message').value = data.response_message;
                
                if (data.contact_method) {
                    const contactRadio = document.querySelector(`input[name="contact_method"][value="${data.contact_method}"]`);
                    if (contactRadio) {
                        contactRadio.checked = true;
                    }
                }
                
                // Update character counter
                updateCharCounter('response_message', 2000);
                
                showNotification('Draft restored successfully', 'info');
            }
        } catch (e) {
            console.error('Error loading draft:', e);
        }
    }
});

// Clear draft when response is sent successfully
document.querySelector('form')?.addEventListener('submit', function() {
    // This will be executed when form is submitted
    // Clear the draft after successful submission
    setTimeout(() => {
        localStorage.removeItem(`inquiry_response_draft_${{{ $inquiry->id }}}`);
    }, 1000);
});

// Auto-save draft every 30 seconds
setInterval(() => {
    const responseMessage = document.getElementById('response_message')?.value;
    if (responseMessage && responseMessage.trim().length > 10) {
        saveDraft();
    }
}, 30000);

// Prevent accidental page leave if there's unsaved content
window.addEventListener('beforeunload', function(e) {
    const responseMessage = document.getElementById('response_message')?.value;
    if (responseMessage && responseMessage.trim().length > 10) {
        e.preventDefault();
        e.returnValue = '';
        return '';
    }
});
</script>
@endpush