<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Dashboard - AgriTerre</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="gradient-bg shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-white rounded-lg flex items-center justify-center">
                            <i class="fas fa-store text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-xl font-bold text-white">AgriTerre - Supplier Portal</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="text-white">
                        <span class="text-sm">Welcome, {{ $user->first_name }}!</span>
                    </div>
                    <div class="relative">
                        <button class="bg-white bg-opacity-20 text-white px-4 py-2 rounded-lg hover:bg-opacity-30 transition-all">
                            <i class="fas fa-user mr-2"></i>{{ $user->initials }}
                        </button>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 bg-opacity-80 text-white px-4 py-2 rounded-lg hover:bg-opacity-100 transition-all">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-500 bg-opacity-20 border border-green-400 border-opacity-30 text-green-700 px-4 py-3 rounded-xl">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Dashboard Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Supplier Dashboard</h2>
            <p class="text-gray-600">Manage your agricultural property listings and connect with potential buyers</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="glass-effect rounded-xl p-6 text-center">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marked-alt text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_properties'] }}</h3>
                <p class="text-gray-600">Total Properties</p>
            </div>
            
            <div class="glass-effect rounded-xl p-6 text-center">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['active_properties'] }}</h3>
                <p class="text-gray-600">Active Listings</p>
            </div>
            
            <div class="glass-effect rounded-xl p-6 text-center">
                <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_inquiries'] }}</h3>
                <p class="text-gray-600">Total Inquiries</p>
            </div>
            
            <div class="glass-effect rounded-xl p-6 text-center">
                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-eye text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['monthly_views'] }}</h3>
                <p class="text-gray-600">Monthly Views</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Add New Property -->
            <div class="glass-effect rounded-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-plus mr-3 text-green-500"></i>Add New Property
                </h3>
                <p class="text-gray-600 mb-4">List a new agricultural property for sale or lease</p>
                <div class="space-y-4">
                    <div>
                        <input type="text" placeholder="Property Title" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <input type="text" placeholder="Location" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="number" placeholder="Price" 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option>For Sale</option>
                            <option>For Lease</option>
                            <option>For Rent</option>
                        </select>
                    </div>
                    <button class="w-full bg-green-500 text-white py-3 rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Create Listing
                    </button>
                </div>
            </div>

            <!-- Recent Inquiries -->
            <div class="glass-effect rounded-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-envelope mr-3 text-blue-500"></i>Recent Inquiries
                </h3>
                <div class="space-y-4">
                    @if($inquiries->isEmpty())
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">No inquiries yet</p>
                            <p class="text-sm text-gray-400">Inquiries from interested buyers will appear here</p>
                        </div>
                    @else
                        @foreach($inquiries as $inquiry)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $inquiry->client_name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $inquiry->property_title }}</p>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $inquiry->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Property Management -->
        <div class="mt-8">
            <div class="glass-effect rounded-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-list mr-3 text-purple-500"></i>Your Properties
                </h3>
                
                @if($properties->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-map-marked-alt text-gray-400 text-6xl mb-4"></i>
                        <h4 class="text-xl font-semibold text-gray-600 mb-2">No Properties Listed</h4>
                        <p class="text-gray-500 mb-6">Start by adding your first agricultural property listing</p>
                        <button class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Your First Property
                        </button>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($properties as $property)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $property->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $property->location }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $property->price }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $property->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <button class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                            <button class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Links -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="#" class="glass-effect rounded-xl p-6 hover:shadow-lg transition-all group">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Analytics</h4>
                        <p class="text-sm text-gray-600">View performance metrics</p>
                    </div>
                </div>
            </a>
            
            <a href="#" class="glass-effect rounded-xl p-6 hover:shadow-lg transition-all group">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-comments text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Messages</h4>
                        <p class="text-sm text-gray-600">Chat with clients</p>
                    </div>
                </div>
            </a>
            
            <a href="#" class="glass-effect rounded-xl p-6 hover:shadow-lg transition-all group">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-cog text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Account Settings</h4>
                        <p class="text-sm text-gray-600">Manage your profile</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p>&copy; 2025 AgriTerre. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>