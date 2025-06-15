<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Client Portal') - AgriTerre</title>
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
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .nav-item {
            transition: all 0.3s ease;
        }
        
        .nav-item:hover {
            transform: translateX(5px);
        }

        .status-active {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .bg-gradient-custom-1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-gradient-custom-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .bg-gradient-custom-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .bg-gradient-custom-4 { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .bg-gradient-custom-5 { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .bg-gradient-custom-6 { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 gradient-bg shadow-2xl fixed h-full z-30">
            <!-- Logo Section -->
            <div class="p-6 border-b border-white border-opacity-20">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-seedling text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">AgriTerre</h1>
                        <p class="text-sm text-white opacity-75">Client Portal</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Menu -->
            <nav class="mt-6 px-3">
                <!-- Main Section -->
                <div class="px-4 py-2">
                    <span class="text-xs font-semibold text-gray-200 uppercase tracking-wide opacity-75">Main</span>
                </div>
                
                <a href="{{ route('client.dashboard') }}" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('client.dashboard') ? 'bg-white bg-opacity-20 border-r-4 border-white' : '' }} group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-home text-sm"></i>
                    </div>
                    <span class="font-medium">Dashboard</span>
                    @if(request()->routeIs('client.dashboard'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                    @endif
                </a>
                
                <a href="{{ route('client.properties.index') }}" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('client.properties*') ? 'bg-white bg-opacity-20 border-r-4 border-white' : '' }} group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-home text-sm"></i>
                    </div>
                    <span class="font-medium">Browse Properties</span>
                    @if(request()->routeIs('client.properties*'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                    @endif
                </a>
                
                <a href="{{ route('client.saved-properties') }}" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('client.saved-properties*') ? 'bg-white bg-opacity-20 border-r-4 border-white' : '' }} group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-heart text-sm"></i>
                    </div>
                    <span class="font-medium">Saved Properties</span>
                    @if(request()->routeIs('client.saved-properties*'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                    @endif
                </a>
                
                <a href="{{ route('client.inquiries.index') }}" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('client.inquiries*') ? 'bg-white bg-opacity-20 border-r-4 border-white' : '' }} group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-envelope text-sm"></i>
                    </div>
                    <span class="font-medium">My Inquiries</span>
                    @if(request()->routeIs('client.inquiries*'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                    @endif
                </a>
                
                <a href="{{ route('client.transactions.index') }}" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('client.transactions*') ? 'bg-white bg-opacity-20 border-r-4 border-white' : '' }} group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-handshake text-sm"></i>
                    </div>
                    <span class="font-medium">Transactions</span>
                    @if(request()->routeIs('client.transactions*'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                    @endif
                </a>
                
                <!-- Account Section -->
                <div class="px-4 py-2 mt-6">
                    <span class="text-xs font-semibold text-gray-200 uppercase tracking-wide opacity-75">Account</span>
                </div>
                
                <a href="{{ route('client.profile') }}" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('client.profile*') ? 'bg-white bg-opacity-20 border-r-4 border-white' : '' }} group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <span class="font-medium">Profile</span>
                    @if(request()->routeIs('client.profile*'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                    @endif
                </a>
                
              
                
             
            </nav>
            
            <!-- Logout Section -->
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-xl transition-all group">
                        <div class="w-8 h-8 bg-red-500 bg-opacity-60 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-80 transition-all">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </div>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="ml-64 flex-1">
            <!-- Top Navigation -->
            <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center">
                            <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Client Portal')</h2>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button class="relative p-2 text-gray-600 hover:text-gray-900 transition-colors">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            <!-- User Menu -->
                            <div class="relative">
                                <button onclick="toggleUserMenu()" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium text-sm">
                                            {{ substr(auth()->user()->first_name ?? 'U', 0, 1) }}{{ substr(auth()->user()->last_name ?? 'U', 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="hidden md:block text-left">
                                        <div class="text-sm font-medium text-gray-800">{{ auth()->user()->first_name ?? 'User' }} {{ auth()->user()->last_name ?? '' }}</div>
                                        <div class="text-xs text-gray-600">Client</div>
                                    </div>
                                    <i class="fas fa-chevron-down text-gray-600 text-xs"></i>
                                </button>
                                
                                <div id="user-menu" class="hidden absolute right-0 mt-2 w-64 glass-effect rounded-xl shadow-lg py-2 z-50">
                                    <div class="px-4 py-3 border-b border-white border-opacity-20">
                                        <p class="font-medium text-gray-800">{{ auth()->user()->first_name ?? 'User' }} {{ auth()->user()->last_name ?? '' }}</p>
                                        <p class="text-sm text-gray-600">{{ auth()->user()->email ?? 'No email' }}</p>
                                    </div>
                                    <div class="py-2">
                                        <a href="{{ route('client.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">
                                            <i class="fas fa-user mr-3"></i>Profile Settings
                                        </a>
                                        <a href="{{ route('client.settings') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">
                                            <i class="fas fa-cog mr-3"></i>Preferences
                                        </a>
                                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">
                                            <i class="fas fa-life-ring mr-3"></i>Help & Support
                                        </a>
                                        <div class="border-t border-white border-opacity-20 mt-2 pt-2">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('user-menu');
            const button = menu.previousElementSibling;
            
            if (!menu.contains(event.target) && !button.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>