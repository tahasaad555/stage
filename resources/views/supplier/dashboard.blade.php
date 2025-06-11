@extends('layouts.supplier')

@section('title', 'Supplier Dashboard')

@push('styles')
<style>
.glass-effect {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.card-hover:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Animation for cards */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-hover {
    animation: slideUp 0.6s ease-out;
}

/* Pulse animation for stats cards */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.stat-card:hover {
    animation: pulse 1s infinite;
}

/* Custom gradient backgrounds */
.bg-gradient-custom-1 {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.bg-gradient-custom-2 {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
}

.bg-gradient-custom-3 {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.bg-gradient-custom-4 {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}
</style>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mr-6">
                        <i class="fas fa-store text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Welcome back, {{ auth()->user()->first_name }}! 👋</h1>
                        <p class="text-xl opacity-90">Manage your agricultural properties and business</p>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg opacity-75">{{ now()->format('l') }}</div>
                <div class="text-3xl font-bold">{{ now()->format('M d, Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-custom-1 rounded-xl flex items-center justify-center">
                    <i class="fas fa-seedling text-white text-xl"></i>
                </div>
                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium">Total</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['total_properties'] }}</h3>
            <p class="text-gray-600 font-medium">Properties Listed</p>
            <div class="mt-3 flex items-center text-sm">
                <i class="fas fa-chart-line text-green-500 mr-1"></i>
                <span class="text-green-600 font-medium">+12%</span>
                <span class="text-gray-500 ml-1">this month</span>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-custom-2 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium">Active</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['active_properties'] }}</h3>
            <p class="text-gray-600 font-medium">Active Listings</p>
            <div class="mt-3 flex items-center text-sm">
                <i class="fas fa-chart-line text-blue-500 mr-1"></i>
                <span class="text-blue-600 font-medium">+8%</span>
                <span class="text-gray-500 ml-1">this week</span>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-custom-3 rounded-xl flex items-center justify-center">
                    <i class="fas fa-envelope text-white text-xl"></i>
                </div>
                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium">New</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['total_inquiries'] }}</h3>
            <p class="text-gray-600 font-medium">Inquiries</p>
            <div class="mt-3 flex items-center text-sm">
                <i class="fas fa-chart-line text-yellow-500 mr-1"></i>
                <span class="text-yellow-600 font-medium">+25%</span>
                <span class="text-gray-500 ml-1">this week</span>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-custom-4 rounded-xl flex items-center justify-center">
                    <i class="fas fa-eye text-white text-xl"></i>
                </div>
                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium">Views</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['monthly_views']) }}</h3>
            <p class="text-gray-600 font-medium">Monthly Views</p>
            <div class="mt-3 flex items-center text-sm">
                <i class="fas fa-chart-line text-purple-500 mr-1"></i>
                <span class="text-purple-600 font-medium">+18%</span>
                <span class="text-gray-500 ml-1">vs last month</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Quick Actions -->
            <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-bolt text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">⚡ Quick Actions</h2>
                        <p class="text-gray-600">Manage your business efficiently</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button class="group flex items-center p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all border border-green-200 hover:border-green-300">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-plus text-white text-xl"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="font-bold text-gray-800 group-hover:text-green-700">Add Property</h4>
                            <p class="text-sm text-gray-600">List a new agricultural land</p>
                        </div>
                    </button>

                    <button class="group flex items-center p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all border border-blue-200 hover:border-blue-300">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-chart-bar text-white text-xl"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="font-bold text-gray-800 group-hover:text-blue-700">View Analytics</h4>
                            <p class="text-sm text-gray-600">Check property performance</p>
                        </div>
                    </button>

                    <button class="group flex items-center p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl hover:from-purple-100 hover:to-pink-100 transition-all border border-purple-200 hover:border-purple-300">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-envelope text-white text-xl"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="font-bold text-gray-800 group-hover:text-purple-700">Manage Inquiries</h4>
                            <p class="text-sm text-gray-600">Respond to client messages</p>
                        </div>
                    </button>

                    <a href="{{ route('supplier.profile') }}" class="group flex items-center p-6 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl hover:from-orange-100 hover:to-red-100 transition-all border border-orange-200 hover:border-orange-300">
                        <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-user text-white text-xl"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="font-bold text-gray-800 group-hover:text-orange-700">Update Profile</h4>
                            <p class="text-sm text-gray-600">Edit your business information</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Properties Section -->
            <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-seedling text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">🌾 Your Properties</h2>
                            <p class="text-gray-600">Manage your agricultural listings</p>
                        </div>
                    </div>
                    <button class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all font-medium">
                        View All Properties
                    </button>
                </div>

                @if($properties->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-32 h-32 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-seedling text-6xl text-gray-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-600 mb-3">No Properties Yet</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">Start building your agricultural business by adding your first property listing</p>
                        <button class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all font-medium text-lg">
                            <i class="fas fa-plus mr-3"></i>Add Your First Property
                        </button>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($properties as $property)
                            <div class="bg-white bg-opacity-50 border border-white border-opacity-30 rounded-xl p-6 hover:bg-opacity-70 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <h4 class="font-bold text-gray-800 text-lg mr-3">{{ $property->title }}</h4>
                                            <div class="flex space-x-2">
                                                @if($property->is_active)
                                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Active</span>
                                                @else
                                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">Inactive</span>
                                                @endif
                                                
                                                @if($property->is_featured)
                                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">
                                                        <i class="fas fa-star mr-1"></i>Featured
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-gray-600 mb-3">{{ Str::limit($property->description, 120) }}</p>
                                        
                                        @if($property->terreAgricole)
                                            <div class="flex items-center space-x-6 text-sm text-gray-600">
                                                <span>
                                                    <i class="fas fa-ruler-combined text-green-500 mr-1"></i>
                                                    {{ number_format($property->terreAgricole->surface, 1) }} hectares
                                                </span>
                                                <span>
                                                    <i class="fas fa-dollar-sign text-blue-500 mr-1"></i>
                                                    ${{ number_format($property->terreAgricole->price / 1000) }}K
                                                </span>
                                                <span>
                                                    <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                                                    {{ $property->terreAgricole->region }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('supplier.properties.show', $property) }}" 
                                           class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-all text-sm font-medium">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                        <button class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition-all">
                                            <i class="fas fa-ellipsis-v text-gray-600"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($stats['total_properties'] > 3)
                            <div class="text-center pt-4">
                                <a href="{{ route('supplier.properties.index') }}" 
                                   class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all font-medium">
                                    <i class="fas fa-seedling mr-2"></i>View All Properties ({{ $stats['total_properties'] }})
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Profile Summary -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Profile Summary</h3>
                </div>
                
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold text-2xl">
                            {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                        </span>
                    </div>
                    <h4 class="font-bold text-gray-800 text-lg">{{ auth()->user()->full_name }}</h4>
                    @if(auth()->user()->fournisseur)
                        <p class="text-gray-600">{{ auth()->user()->fournisseur->company_name }}</p>
                    @endif
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium mt-2 inline-block">Verified Supplier</span>
                </div>
                
                <div class="space-y-3 text-sm mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-gray-400 mr-3 w-4"></i>
                        <span class="text-gray-600">{{ auth()->user()->email }}</span>
                    </div>
                    @if(auth()->user()->phone)
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-400 mr-3 w-4"></i>
                            <span class="text-gray-600">{{ auth()->user()->phone }}</span>
                        </div>
                    @endif
                    <div class="flex items-center">
                        <i class="fas fa-calendar text-gray-400 mr-3 w-4"></i>
                        <span class="text-gray-600">Member since {{ auth()->user()->created_at->format('M Y') }}</span>
                    </div>
                </div>
                
                <a href="{{ route('supplier.profile') }}" class="block w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-center py-3 rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all font-medium">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </a>
            </div>

            <!-- Recent Activity -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-bell text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Recent Activity</h3>
                </div>
                
                @if($notifications->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bell-slash text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 text-sm">No recent activity</p>
                        <p class="text-gray-400 text-xs mt-1">New activities will appear here</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($notifications as $notification)
                            <div class="bg-white bg-opacity-50 border border-white border-opacity-30 rounded-lg p-4">
                                <p class="text-sm text-gray-700 font-medium">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-500 mt-2">{{ $notification->created_at }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Quick Stats -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-chart-pie text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Performance</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Profile Completion</span>
                        <span class="text-gray-800 font-medium">85%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Response Rate</span>
                        <span class="text-gray-800 font-medium">92%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full" style="width: 92%"></div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Customer Rating</span>
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 mr-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-800 font-medium">4.8</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection