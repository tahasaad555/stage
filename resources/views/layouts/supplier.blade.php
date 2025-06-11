<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - AgriTerre Supplier</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'supplier-primary': '#10b981',
                        'supplier-secondary': '#059669',
                        'supplier-accent': '#34d399',
                        'supplier-success': '#10b981',
                        'supplier-warning': '#f59e0b',
                        'supplier-error': '#ef4444',
                        'supplier-dark': '#1f2937',
                        'supplier-light': '#f0fdf4'
                    },
                    backgroundImage: {
                        'gradient-main': 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                        'gradient-card': 'linear-gradient(135deg, #34d399 0%, #10b981 100%)',
                        'gradient-success': 'linear-gradient(135deg, #10b981 0%, #34d399 100%)',
                        'gradient-warning': 'linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%)',
                        'gradient-sidebar': 'linear-gradient(180deg, #10b981 0%, #059669 100%)',
                        'glassmorphism': 'rgba(255, 255, 255, 0.25)'
                    },
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    borderRadius: {
                        'xl': '12px',
                        '2xl': '16px',
                        '3xl': '24px'
                    },
                    boxShadow: {
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.37)',
                        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                        'lg-colored': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            min-height: 100vh;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .sidebar-gradient {
            background: linear-gradient(180deg, #10b981 0%, #059669 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .nav-item {
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }
        
        .nav-item:hover::before {
            left: 100%;
        }
        
        .stat-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .content-area {
            background: rgba(248, 250, 252, 0.95);
            backdrop-filter: blur(10px);
        }
        
        @keyframes pulse-custom {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .pulse-custom {
            animation: pulse-custom 2s infinite;
        }
        
        .notification-dot {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            animation: pulse-custom 1.5s infinite;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gradient-main">
    <div class="min-h-screen flex">
        <!-- Enhanced Sidebar -->
        <div class="w-72 sidebar-gradient shadow-2xl relative overflow-hidden">
            <!-- Sidebar decoration -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-5 rounded-full -ml-12 -mb-12"></div>
            
            <div class="p-6 relative z-10">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-leaf text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">AgriTerre</h2>
                        <p class="text-sm text-gray-200 opacity-80">Supplier Portal</p>
                    </div>
                </div>
            </div>
            
            <!-- User Profile Section -->
            <div class="px-6 py-4 border-b border-white border-opacity-20">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-bold">
                            {{ substr(auth()->user()->first_name ?? 'U', 0, 1) }}{{ substr(auth()->user()->last_name ?? 'U', 0, 1) }}
                        </span>
                    </div>
                    <div class="flex-1">
                        <p class="text-white text-sm font-medium">{{ auth()->user()->first_name ?? 'User' }} {{ auth()->user()->last_name ?? '' }}</p>
                        <p class="text-gray-200 text-xs opacity-75">Supplier</p>
                    </div>
                    <div class="relative">
                        <i class="fas fa-bell text-white text-sm opacity-75"></i>
                        <div class="notification-dot"></div>
                    </div>
                </div>
            </div>
            
            <nav class="mt-6 relative z-10">
                <!-- Main Navigation -->
                <div class="px-4 py-2">
                    <span class="text-xs font-semibold text-gray-200 uppercase tracking-wide opacity-75">Main Menu</span>
                </div>
                
                <a href="{{ route('supplier.dashboard') }}" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('supplier.dashboard') ? 'bg-white bg-opacity-20 border-r-4 border-white' : '' }} group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-chart-line text-sm"></i>
                    </div>
                    <span class="font-medium">Dashboard</span>
                    @if(request()->routeIs('supplier.dashboard'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                    @endif
                </a>
                
                <a href="{{ route('supplier.properties.index') }}" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('supplier.properties.*') ? 'bg-white bg-opacity-20 border-r-4 border-white' : '' }} group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-seedling text-sm"></i>
                    </div>
                    <span class="font-medium">My Properties</span>
                    @if(auth()->user()->fournisseur && auth()->user()->fournisseur->annonces()->count() > 0)
                        <span class="ml-auto text-xs bg-green-400 text-white px-2 py-1 rounded-full">{{ auth()->user()->fournisseur->annonces()->count() }}</span>
                    @endif
                    @if(request()->routeIs('supplier.properties.*'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                    @endif
                </a>
                
                <a href="{{ route('supplier.properties.create') }}" 
   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 group {{ request()->routeIs('supplier.properties.create') ? 'bg-white bg-opacity-20' : '' }}">
    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
        <i class="fas fa-plus text-sm"></i>
    </div>
    <span class="font-medium">Add Property</span>
</a>
                
                <a href="#" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-envelope text-sm"></i>
                    </div>
                    <span class="font-medium">Inquiries</span>
                    <span class="ml-auto text-xs bg-yellow-400 text-white px-2 py-1 rounded-full">5</span>
                </a>
                
                <a href="#" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-chart-bar text-sm"></i>
                    </div>
                    <span class="font-medium">Analytics</span>
                </a>
                
                <a href="#" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-handshake text-sm"></i>
                    </div>
                    <span class="font-medium">Transactions</span>
                </a>
                
                <!-- Account Section -->
                <div class="px-4 py-2 mt-6">
                    <span class="text-xs font-semibold text-gray-200 uppercase tracking-wide opacity-75">Account</span>
                </div>
                
                <a href="{{ route('supplier.profile') }}" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('supplier.profile*') ? 'bg-white bg-opacity-20 border-r-4 border-white' : '' }} group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <span class="font-medium">Profile</span>
                    @if(request()->routeIs('supplier.profile*'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                    @endif
                </a>
                
                <!-- Settings Section -->
                <div class="px-4 py-2 mt-6">
                    <span class="text-xs font-semibold text-gray-200 uppercase tracking-wide opacity-75">Settings</span>
                </div>
                
                <a href="#" class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-cog text-sm"></i>
                    </div>
                    <span class="font-medium">Settings</span>
                </a>
                
                <a href="#" class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-life-ring text-sm"></i>
                    </div>
                    <span class="font-medium">Help & Support</span>
                </a>
            </nav>
            
            <!-- Logout Section -->
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 text-white bg-white bg-opacity-10 rounded-lg hover:bg-opacity-20 transition-all group">
                        <div class="w-8 h-8 bg-red-500 bg-opacity-80 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </div>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Enhanced Top Bar -->
            <header class="glass-effect border-b border-white border-opacity-20 m-4 rounded-xl">
                <div class="flex items-center justify-between px-8 py-6">
                    <div class="flex items-center space-x-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
                            <p class="text-gray-600 text-sm">Welcome back! @yield('subtitle', 'Manage your agricultural business')</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Quick Stats -->
                        <div class="hidden lg:flex items-center space-x-6">
                            <div class="text-center">
                                <div class="text-lg font-bold text-gray-800">{{ auth()->user()->fournisseur ? auth()->user()->fournisseur->annonces()->count() : 0 }}</div>
                                <div class="text-xs text-gray-600">Properties</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-green-600">5</div>
                                <div class="text-xs text-gray-600">Inquiries</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-blue-600">92%</div>
                                <div class="text-xs text-gray-600">Response</div>
                            </div>
                        </div>
                        
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="w-10 h-10 bg-white bg-opacity-50 rounded-lg flex items-center justify-center hover:bg-opacity-70 transition-all">
                                <i class="fas fa-bell text-gray-600"></i>
                                <div class="notification-dot"></div>
                            </button>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="relative">
                            <button onclick="toggleUserMenu()" class="flex items-center space-x-3 bg-white bg-opacity-20 rounded-lg px-4 py-2 hover:bg-opacity-30 transition-all">
                                <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-emerald-500 rounded-lg flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">
                                        {{ substr(auth()->user()->first_name ?? 'U', 0, 1) }}{{ substr(auth()->user()->last_name ?? 'U', 0, 1) }}
                                    </span>
                                </div>
                                <div class="hidden md:block text-left">
                                    <div class="text-sm font-medium text-gray-800">{{ auth()->user()->first_name ?? 'User' }} {{ auth()->user()->last_name ?? '' }}</div>
                                    <div class="text-xs text-gray-600">Supplier</div>
                                </div>
                                <i class="fas fa-chevron-down text-gray-600 text-xs"></i>
                            </button>
                            
                            <div id="user-menu" class="hidden absolute right-0 mt-2 w-64 glass-effect rounded-xl shadow-lg py-2 z-50">
                                <div class="px-4 py-3 border-b border-white border-opacity-20">
                                    <p class="font-medium text-gray-800">{{ auth()->user()->first_name ?? 'User' }} {{ auth()->user()->last_name ?? '' }}</p>
                                    <p class="text-sm text-gray-600">{{ auth()->user()->email ?? 'No email' }}</p>
                                </div>
                                <div class="py-2">
                                    <a href="{{ route('supplier.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">
                                        <i class="fas fa-user mr-3"></i>Profile Settings
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">
                                        <i class="fas fa-cog mr-3"></i>Preferences
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">
                                        <i class="fas fa-life-ring mr-3"></i>Help & Support
                                    </a>
                                    <div class="border-t border-white border-opacity-20 mt-2 pt-2">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:bg-opacity-50">
                                                <i class="fas fa-sign-out-alt mr-3"></i>Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content Area -->
            <main class="flex-1 content-area m-4 mt-0 rounded-xl p-8 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        }

        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('user-menu');
            const button = event.target.closest('button[onclick="toggleUserMenu()"]');
            
            if (!button && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Auto-hide notifications
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(function(notification) {
                setTimeout(function() {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(function() {
                        notification.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>