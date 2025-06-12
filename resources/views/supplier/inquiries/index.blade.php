@extends('layouts.supplier')

@section('title', 'My Inquiries')

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
    transform: translateY(-8px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Enhanced Filters */
.filter-active {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
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
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mr-6">
                        <i class="fas fa-envelope text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">📨 My Inquiries</h1>
                        <p class="text-xl opacity-90">Manage client inquiries about your properties</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-6">
                <!-- Quick Stats -->
                <div class="text-right">
                    <div class="text-lg opacity-75">Total Inquiries</div>
                    <div class="text-3xl font-bold">{{ $stats['total'] }}</div>
                    <div class="text-sm opacity-75">{{ $stats['new'] }} new this month</div>
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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- New Inquiries -->
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">New</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['new'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-envelope text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                    {{ $stats['this_week'] }} this week
                </div>
            </div>
        </div>

        <!-- Responded -->
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Responded</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['responded'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-reply text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-check text-green-500 mr-1"></i>
                    {{ number_format(($stats['responded'] / max($stats['total'], 1)) * 100, 1) }}% response rate
                </div>
            </div>
        </div>

        <!-- High Priority -->
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">High Priority</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $stats['high_priority'] + $stats['urgent'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-fire text-orange-500 mr-1"></i>
                    {{ $stats['urgent'] }} urgent
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['this_month'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-trend-up text-purple-500 mr-1"></i>
                    +{{ rand(5, 20) }}% vs last month
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="glass-effect rounded-2xl p-6 shadow-xl">
        <form method="GET" action="{{ route('supplier.inquiries.index') }}" class="space-y-6">
            <!-- Search Bar -->
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search inquiries, client names, property titles..."
                               class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    </div>
                </div>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all font-medium">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>

            <!-- Filter Options -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                        <option value="responded" {{ request('status') === 'responded' ? 'selected' : '' }}>Responded</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <!-- Priority Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select name="priority" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <option value="all" {{ request('priority') === 'all' ? 'selected' : '' }}>All Priorities</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="inquiry_type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <option value="all" {{ request('inquiry_type') === 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="purchase" {{ request('inquiry_type') === 'purchase' ? 'selected' : '' }}>Purchase</option>
                        <option value="lease" {{ request('inquiry_type') === 'lease' ? 'selected' : '' }}>Lease</option>
                        <option value="partnership" {{ request('inquiry_type') === 'partnership' ? 'selected' : '' }}>Partnership</option>
                        <option value="information" {{ request('inquiry_type') === 'information' ? 'selected' : '' }}>Information</option>
                        <option value="general" {{ request('inquiry_type') === 'general' ? 'selected' : '' }}>General</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all">
                        Filter
                    </button>
                    <a href="{{ route('supplier.inquiries.index') }}" class="px-4 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div id="bulk-actions" class="glass-effect rounded-2xl p-4 shadow-xl hidden">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="font-medium text-gray-700">
                    <span id="selected-count">0</span> inquiries selected
                </span>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="bulkAction('mark_read')" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all">
                    Mark as Read
                </button>
                <button onclick="bulkAction('mark_responded')" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all">
                    Mark as Responded
                </button>
                <button onclick="bulkAction('mark_closed')" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all">
                    Close
                </button>
                <button onclick="bulkAction('mark_spam')" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-all">
                    Mark as Spam
                </button>
                <button onclick="bulkAction('delete')" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Inquiries List -->
    @if($inquiries->count() > 0)
        <div class="space-y-6">
            <!-- Select All -->
            <div class="glass-effect rounded-xl p-4 shadow-xl">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" id="select-all" class="w-5 h-5 text-purple-600 border-2 border-gray-300 rounded focus:ring-purple-500">
                    <span class="font-medium text-gray-700">Select all inquiries on this page</span>
                </label>
            </div>

            <!-- Inquiries Cards -->
            @foreach($inquiries as $inquiry)
                <div class="glass-effect rounded-2xl shadow-xl card-hover inquiry-card" data-inquiry-id="{{ $inquiry->id }}">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <!-- Left Content -->
                            <div class="flex items-start space-x-4 flex-1">
                                <!-- Checkbox -->
                                <input type="checkbox" class="inquiry-checkbox w-5 h-5 text-purple-600 border-2 border-gray-300 rounded focus:ring-purple-500 mt-1" value="{{ $inquiry->id }}">
                                
                                <!-- Inquiry Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <!-- Status Badge -->
                                        <span class="status-{{ $inquiry->status }} px-3 py-1 rounded-full text-xs font-bold">
                                            {{ strtoupper($inquiry->status) }}
                                        </span>
                                        
                                        <!-- Priority Badge -->
                                        <span class="priority-{{ $inquiry->priority }} px-3 py-1 rounded-full text-xs font-bold">
                                            {{ strtoupper($inquiry->priority) }}
                                        </span>
                                        
                                        <!-- Type Badge -->
                                        <span class="type-{{ $inquiry->inquiry_type }} px-3 py-1 rounded-full text-xs font-bold">
                                            {{ $inquiry->formatted_type }}
                                        </span>
                                        
                                        @if($inquiry->is_unread)
                                            <span class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></span>
                                        @endif
                                    </div>
                                    
                                    <!-- Subject and Property -->
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $inquiry->subject }}</h3>
                                    <p class="text-sm text-gray-600 mb-3">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        Property: {{ $inquiry->annonce->title ?? $inquiry->annonce->titre }}
                                    </p>
                                    
                                    <!-- Client Info -->
                                    <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                        <span>
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $inquiry->client_name }}
                                        </span>
                                        <span>
                                            <i class="fas fa-envelope mr-1"></i>
                                            {{ $inquiry->client_email }}
                                        </span>
                                        @if($inquiry->client_phone)
                                            <span>
                                                <i class="fas fa-phone mr-1"></i>
                                                {{ $inquiry->client_phone }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Message Preview -->
                                    <p class="text-gray-700 line-clamp-2 mb-3">
                                        {{ Str::limit($inquiry->message, 150) }}
                                    </p>
                                    
                                    <!-- Metadata -->
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span>
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $inquiry->time_since }}
                                        </span>
                                        @if($inquiry->budget_range)
                                            <span>
                                                <i class="fas fa-dollar-sign mr-1"></i>
                                                Budget: {{ $inquiry->budget_range }}
                                            </span>
                                        @endif
                                        <span>
                                            <i class="fas fa-comment mr-1"></i>
                                            Preferred: {{ ucfirst($inquiry->preferred_contact_method) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Actions -->
                            <div class="flex items-center space-x-2">
                                <!-- Priority Selector -->
                                <select onchange="updatePriority({{ $inquiry->id }}, this.value)" 
                                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="low" {{ $inquiry->priority === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $inquiry->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $inquiry->priority === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ $inquiry->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>

                                <!-- Actions Dropdown -->
                                <div class="relative">
                                    <button onclick="toggleDropdown({{ $inquiry->id }})" 
                                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div id="dropdown-{{ $inquiry->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 border">
                                        <a href="{{ route('supplier.inquiries.show', $inquiry) }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-eye mr-2"></i>View Details
                                        </a>
                                        <button onclick="updateStatus({{ $inquiry->id }}, 'read')" 
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-check mr-2"></i>Mark as Read
                                        </button>
                                        <button onclick="updateStatus({{ $inquiry->id }}, 'responded')" 
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-reply mr-2"></i>Mark as Responded
                                        </button>
                                        <button onclick="updateStatus({{ $inquiry->id }}, 'closed')" 
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-times mr-2"></i>Close
                                        </button>
                                        <hr class="my-1">
                                        <button onclick="markAsSpam({{ $inquiry->id }})" 
                                                class="block w-full text-left px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-50">
                                            <i class="fas fa-flag mr-2"></i>Mark as Spam
                                        </button>
                                        <button onclick="deleteInquiry({{ $inquiry->id }})" 
                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <i class="fas fa-trash mr-2"></i>Delete
                                        </button>
                                    </div>
                                </div>

                                <!-- View Button -->
                                <a href="{{ route('supplier.inquiries.show', $inquiry) }}" 
                                   class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all font-medium">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if($inquiries->hasPages())
                <div class="glass-effect rounded-2xl p-6 shadow-xl">
                    {{ $inquiries->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="glass-effect rounded-2xl p-12 text-center shadow-xl">
            <div class="w-24 h-24 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-envelope text-white text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-4">No inquiries found</h3>
            @if(request()->hasAny(['search', 'status', 'priority', 'inquiry_type']))
                <p class="text-gray-600 mb-6">No inquiries match your current filters. Try adjusting your search criteria.</p>
                <a href="{{ route('supplier.inquiries.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all font-medium">
                    <i class="fas fa-times mr-2"></i>Clear Filters
                </a>
            @else
                <p class="text-gray-600 mb-6">You haven't received any inquiries yet. When clients are interested in your properties, their inquiries will appear here.</p>
                <a href="{{ route('supplier.properties.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all font-medium">
                    <i class="fas fa-plus mr-2"></i>Add Properties
                </a>
            @endif
        </div>
    @endif
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

// Toggle dropdown
function toggleDropdown(inquiryId) {
    const dropdown = document.getElementById(`dropdown-${inquiryId}`);
    
    // Close all other dropdowns
    document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
        if (d !== dropdown) {
            d.classList.add('hidden');
        }
    });
    
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('[onclick*="toggleDropdown"]') && !e.target.closest('[id^="dropdown-"]')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
            d.classList.add('hidden');
        });
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
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification(data.message, 'success');
            location.reload();
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
            // Update the badge color without reloading
            const card = document.querySelector(`[data-inquiry-id="${inquiryId}"]`);
            const priorityBadge = card.querySelector(`[class*="priority-"]`);
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
            location.reload();
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
            // Remove the card from the page
            const card = document.querySelector(`[data-inquiry-id="${inquiryId}"]`);
            if (card) {
                card.remove();
            }
        } else {
            showNotification('Error deleting inquiry', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Error deleting inquiry', 'error');
    });
}

// Bulk selection functionality
const selectAllCheckbox = document.getElementById('select-all');
const inquiryCheckboxes = document.querySelectorAll('.inquiry-checkbox');
const bulkActionsDiv = document.getElementById('bulk-actions');
const selectedCountSpan = document.getElementById('selected-count');

// Select all functionality
if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
        inquiryCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });
}

// Individual checkbox functionality
inquiryCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkActions);
});

function updateBulkActions() {
    const selectedCheckboxes = document.querySelectorAll('.inquiry-checkbox:checked');
    const selectedCount = selectedCheckboxes.length;
    
    selectedCountSpan.textContent = selectedCount;
    
    if (selectedCount > 0) {
        bulkActionsDiv.classList.remove('hidden');
    } else {
        bulkActionsDiv.classList.add('hidden');
    }
    
    // Update select all checkbox state
    if (selectAllCheckbox) {
        if (selectedCount === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (selectedCount === inquiryCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }
}

// Bulk actions
function bulkAction(action) {
    const selectedCheckboxes = document.querySelectorAll('.inquiry-checkbox:checked');
    const inquiryIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    if (inquiryIds.length === 0) {
        showNotification('Please select at least one inquiry', 'warning');
        return;
    }
    
    const confirmMessage = `Are you sure you want to ${action.replace('_', ' ')} ${inquiryIds.length} inquiries?`;
    if (!confirm(confirmMessage)) {
        return;
    }
    
    showLoading();
    
    fetch('/supplier/inquiries/bulk-update', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            inquiry_ids: inquiryIds,
            action: action
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification(data.message, 'success');
            location.reload();
        } else {
            showNotification('Error performing bulk action', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Error performing bulk action', 'error');
    });
}

// Initialize bulk actions on page load
document.addEventListener('DOMContentLoaded', function() {
    updateBulkActions();
});
</script>
@endpush