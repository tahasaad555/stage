<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - AgriTerre Admin</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-4">
                <div class="flex items-center">
                    <i class="fas fa-leaf text-green-600 text-2xl mr-2"></i>
                    <h2 class="text-xl font-bold text-gray-800">AgriTerre</h2>
                </div>
                <p class="text-sm text-gray-500">Admin Panel</p>
            </div>
            
            <nav class="mt-4">
                <div class="px-4 py-2">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Main</span>
                </div>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('admin.dashboard') ? 'bg-green-50 text-green-700 border-r-2 border-green-500' : '' }}">
                    <i class="fas fa-chart-line mr-3"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('admin.users.*') ? 'bg-green-50 text-green-700 border-r-2 border-green-500' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    Users
                </a>
                
                <div class="px-4 py-2 mt-4">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Management</span>
                </div>
                
                <a href="{{ route('admin.lands.index') }}" 
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('admin.lands.*') ? 'bg-green-50 text-green-700 border-r-2 border-green-500' : '' }}">
                    <i class="fas fa-map mr-3"></i>
                    Agricultural Lands
                </a>
                
                <a href="{{ route('admin.listings.index') }}" 
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('admin.listings.*') ? 'bg-green-50 text-green-700 border-r-2 border-green-500' : '' }}">
                    <i class="fas fa-bullhorn mr-3"></i>
                    Listings
                </a>
                
                <a href="{{ route('admin.transactions.index') }}" 
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('admin.transactions.*') ? 'bg-green-50 text-green-700 border-r-2 border-green-500' : '' }}">
                    <i class="fas fa-credit-card mr-3"></i>
                    Transactions
                </a>
                
                <div class="px-4 py-2 mt-4">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Settings</span>
                </div>
                
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-700">
                    <i class="fas fa-cog mr-3"></i>
                    System Settings
                    <span class="ml-auto text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded">Soon</span>
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- User Menu -->
                        <div class="relative">
                            <button id="user-menu-button" class="flex items-center text-gray-700 hover:text-gray-900">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                    {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                                </div>
                                <span class="ml-2">{{ auth()->user()->full_name }}</span>
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            
                            <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                    <p class="font-medium">{{ auth()->user()->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        // User menu toggle
        document.getElementById('user-menu-button').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('hidden');
        });
        
        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#user-menu-button')) {
                document.getElementById('user-menu').classList.add('hidden');
            }
        });
        
        // CSRF token for AJAX requests
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    
    @stack('scripts')
</body>
</html>