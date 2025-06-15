@extends('layouts.client')

@section('title', 'My Inquiries')
@section('page-title', 'My Inquiries')

@section('content')
    <!-- Header Section -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl mb-8 card-hover">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-custom-3 rounded-2xl flex items-center justify-center mr-6">
                    <i class="fas fa-envelope text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold mb-2">📧 My Inquiries</h1>
                    <p class="text-xl opacity-90">Track your property inquiries and responses</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg opacity-75">Total Inquiries</div>
                <div class="text-3xl font-bold">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['new'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
            </div>
        </div>

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
        </div>

        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Closed</p>
                    <p class="text-3xl font-bold text-gray-600">{{ $stats['closed'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-gray-500 to-gray-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-envelope text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-effect rounded-2xl p-6 shadow-xl mb-8">
        <form method="GET" action="{{ route('client.inquiries.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Search inquiries..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Statuses</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                        <option value="responded" {{ request('status') == 'responded' ? 'selected' : '' }}>Responded</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <!-- Inquiry Type Filter -->
                <div>
                    <label for="inquiry_type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="inquiry_type" id="inquiry_type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Types</option>
                        <option value="information" {{ request('inquiry_type') == 'information' ? 'selected' : '' }}>Information</option>
                        <option value="visit" {{ request('inquiry_type') == 'visit' ? 'selected' : '' }}>Visit</option>
                        <option value="purchase" {{ request('inquiry_type') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                        <option value="partnership" {{ request('inquiry_type') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </div>
            
            @if(request()->hasAny(['search', 'status', 'inquiry_type']))
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <span class="text-sm text-gray-600">
                        Showing {{ $inquiries->firstItem() ?? 0 }} - {{ $inquiries->lastItem() ?? 0 }} of {{ $inquiries->total() }} inquiries
                    </span>
                    <a href="{{ route('client.inquiries.index') }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Clear Filters
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Inquiries List -->
    @if($inquiries->count() > 0)
        <div class="space-y-6 mb-8">
            @foreach($inquiries as $inquiry)
                <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-3">
                                <!-- Status Badge -->
                                <span class="px-3 py-1 rounded-full text-xs font-medium mr-3 
                                    {{ $inquiry->status === 'new' ? 'bg-blue-100 text-blue-800' : 
                                       ($inquiry->status === 'responded' ? 'bg-green-100 text-green-800' : 
                                       ($inquiry->status === 'read' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($inquiry->status) }}
                                </span>

                                <!-- Inquiry Type Badge -->
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mr-3">
                                    {{ ucfirst($inquiry->inquiry_type) }}
                                </span>

                                <!-- Priority Badge -->
                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $inquiry->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                       ($inquiry->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                                       ($inquiry->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($inquiry->priority) }}
                                </span>
                            </div>

                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $inquiry->subject }}</h3>
                            <p class="text-gray-600 mb-3 line-clamp-2">{{ Str::limit($inquiry->message, 150) }}</p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-home mr-2 text-blue-500"></i>
                                    @if($inquiry->property)
                                        <a href="{{ route('client.properties.show', $inquiry->property) }}" 
                                           class="text-blue-600 hover:text-blue-800">
                                            {{ Str::limit($inquiry->property->title, 30) }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">Property not available</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-2 text-green-500"></i>
                                    {{ $inquiry->created_at->format('M d, Y') }}
                                </div>
                                @if($inquiry->budget_range)
                                    <div class="flex items-center">
                                        <i class="fas fa-euro-sign mr-2 text-yellow-500"></i>
                                        {{ $inquiry->budget_range }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col space-y-2 ml-6">
                            <a href="{{ route('client.inquiries.show', $inquiry) }}" 
                               class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium text-center">
                                <i class="fas fa-eye mr-2"></i>View
                            </a>
                            
                            @if($inquiry->status !== 'closed')
                                <form action="{{ route('client.inquiries.close', $inquiry) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            onclick="return confirm('Are you sure you want to close this inquiry?')"
                                            class="w-full bg-gray-600 text-white px-6 py-2 rounded-xl hover:bg-gray-700 transition-all font-medium">
                                        <i class="fas fa-check mr-2"></i>Close
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $inquiries->appends(request()->query())->links() }}
        </div>
    @else
        <!-- No Inquiries Found -->
        <div class="glass-effect rounded-2xl p-12 shadow-xl text-center">
            <div class="w-24 h-24 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-envelope text-white text-3xl"></i>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-4">
                @if(request()->hasAny(['search', 'status', 'inquiry_type']))
                    No Inquiries Found
                @else
                    No Inquiries Yet
                @endif
            </h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                @if(request()->hasAny(['search', 'status', 'inquiry_type']))
                    We couldn't find any inquiries matching your search criteria. Try adjusting your filters.
                @else
                    You haven't sent any property inquiries yet. Start browsing properties and send your first inquiry!
                @endif
            </p>
            <div class="flex justify-center space-x-4">
                @if(request()->hasAny(['search', 'status', 'inquiry_type']))
                    <a href="{{ route('client.inquiries.index') }}" 
                       class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all font-medium">
                        <i class="fas fa-refresh mr-2"></i>Clear Filters
                    </a>
                @endif
                <a href="{{ route('client.properties.index') }}" 
                   class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium">
                    <i class="fas fa-search mr-2"></i>Browse Properties
                </a>
            </div>
        </div>
    @endif
@endsection