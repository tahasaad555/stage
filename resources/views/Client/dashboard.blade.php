@extends('layouts.client')

@section('title', 'Dashboard')
@section('page-title', 'Client Dashboard')

@section('content')
    <!-- Success Message -->
    @if(session('success'))
        <div class="glass-effect rounded-xl p-6 border-l-4 border-green-500 shadow-xl mb-8">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Success!</h4>
                    <p class="text-gray-600">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Dashboard Header -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl mb-8 card-hover">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-custom-1 rounded-2xl flex items-center justify-center mr-6">
                    <i class="fas fa-seedling text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold mb-2">🌾 Welcome, {{ $user->first_name }}!</h1>
                    <p class="text-xl opacity-90">Explore and discover the perfect agricultural land for your needs</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg opacity-75">Today</div>
                <div class="text-2xl font-bold">{{ now()->format('M d') }}</div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Searches Made</p>
                    <p class="text-3xl font-bold text-blue-600">0</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-search text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                    Start exploring today
                </div>
            </div>
        </div>
        
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Saved Properties</p>
                    <p class="text-3xl font-bold text-green-600">{{ auth()->user()->savedProperties()->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-heart text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-heart text-red-500 mr-1"></i>
                    Properties you love
                </div>
            </div>
        </div>
        
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Inquiries Sent</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ auth()->user()->clientInquiries()->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-envelope text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-paper-plane text-blue-500 mr-1"></i>
                    Messages to sellers
                </div>
            </div>
        </div>
        
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Profile Complete</p>
                    <p class="text-3xl font-bold text-purple-600">
                        {{ $user->email && $user->first_name && $user->last_name && $user->phone ? '100' : '75' }}%
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-check text-green-500 mr-1"></i>
                    Profile completion
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Search Properties -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-custom-1 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-search text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">🔍 Find Properties</h3>
                    <p class="text-gray-600">Search for agricultural land that meets your requirements</p>
                </div>
            </div>
            
            <form action="{{ route('client.properties.index') }}" method="GET" class="space-y-4">
                <div>
                    <input type="text" 
                           name="search"
                           placeholder="Location (City, Region...)" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" 
                           name="min_price"
                           placeholder="Min Price (€)" 
                           class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <input type="number" 
                           name="max_price"
                           placeholder="Max Price (€)" 
                           class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium">
                    <i class="fas fa-search mr-2"></i>Search Properties
                </button>
            </form>
        </div>

        <!-- Recent Activity -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-custom-4 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">⏰ Recent Activity</h3>
                    <p class="text-gray-600">Your latest actions and updates</p>
                </div>
            </div>
            
            @if(auth()->user()->savedProperties()->count() > 0 || auth()->user()->clientInquiries()->count() > 0)
                <div class="space-y-4">
                    @if(auth()->user()->savedProperties()->latest()->limit(3)->count() > 0)
                        <div class="p-4 bg-white bg-opacity-50 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-heart text-red-500 mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-800">Recently saved properties</p>
                                    <p class="text-sm text-gray-600">{{ auth()->user()->savedProperties()->count() }} total saved</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(auth()->user()->clientInquiries()->latest()->limit(3)->count() > 0)
                        <div class="p-4 bg-white bg-opacity-50 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-blue-500 mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-800">Recent inquiries</p>
                                    <p class="text-sm text-gray-600">{{ auth()->user()->clientInquiries()->count() }} total sent</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <a href="{{ route('client.inquiries.index') }}" 
                       class="block text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        View All Activity
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-history text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500 font-medium">No recent activity</p>
                    <p class="text-sm text-gray-400 mb-4">Start exploring properties to see your activity here</p>
                    <a href="{{ route('client.properties.index') }}" 
                       class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Browse Properties
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('client.properties.index') }}" 
           class="glass-effect rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all group card-hover">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-custom-4 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-map-marked-alt text-white"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 text-lg">Browse All Properties</h4>
                    <p class="text-sm text-gray-600">View available agricultural lands</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('client.saved-properties') }}" 
           class="glass-effect rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all group card-hover">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-custom-2 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-heart text-white"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 text-lg">Saved Properties</h4>
                    <p class="text-sm text-gray-600">Your bookmarked favorites</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('client.profile') }}" 
           class="glass-effect rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all group card-hover">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-custom-5 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-cog text-white"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 text-lg">Account Settings</h4>
                    <p class="text-sm text-gray-600">Manage your profile</p>
                </div>
            </div>
        </a>
    </div>
@endsection