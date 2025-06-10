<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard - AgriTerre</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                            <i class="fas fa-seedling text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-xl font-bold text-white">AgriTerre - Client Portal</h1>
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
            <h2 class="text-3xl font-bold text-gray-900">Client Dashboard</h2>
            <p class="text-gray-600">Explore and discover the perfect agricultural land for your needs</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="glass-effect rounded-xl p-6 text-center">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">0</h3>
                <p class="text-gray-600">Searches Made</p>
            </div>
            
            <div class="glass-effect rounded-xl p-6 text-center">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heart text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">0</h3>
                <p class="text-gray-600">Saved Properties</p>
            </div>
            
            <div class="glass-effect rounded-xl p-6 text-center">
                <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">0</h3>
                <p class="text-gray-600">Inquiries Sent</p>
            </div>
            
            <div class="glass-effect rounded-xl p-6 text-center">
                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-eye text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">0</h3>
                <p class="text-gray-600">Property Views</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Search Properties -->
            <div class="glass-effect rounded-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-search mr-3 text-blue-500"></i>Find Properties
                </h3>
                <p class="text-gray-600 mb-4">Search for agricultural land that meets your specific requirements</p>
                <div class="space-y-4">
                    <div>
                        <input type="text" placeholder="Location (City, Region...)" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="number" placeholder="Min Price" 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <input type="number" placeholder="Max Price" 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <button class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-search mr-2"></i>Search Properties
                    </button>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="glass-effect rounded-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clock mr-3 text-green-500"></i>Recent Activity
                </h3>
                <div class="space-y-4">
                    <div class="text-center py-8">
                        <i class="fas fa-history text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No recent activity</p>
                        <p class="text-sm text-gray-400">Start exploring properties to see your activity here</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="#" class="glass-effect rounded-xl p-6 hover:shadow-lg transition-all group">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-map-marked-alt text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Browse All Properties</h4>
                        <p class="text-sm text-gray-600">View available agricultural lands</p>
                    </div>
                </div>
            </a>
            
            <a href="#" class="glass-effect rounded-xl p-6 hover:shadow-lg transition-all group">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-calculator text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Investment Calculator</h4>
                        <p class="text-sm text-gray-600">Calculate potential returns</p>
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