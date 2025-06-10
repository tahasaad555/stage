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
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'admin-primary': '#059669',
                        'admin-secondary': '#0891b2',
                        'admin-accent': '#7c3aed',
                        'admin-success': '#10b981',
                        'admin-warning': '#f59e0b',
                        'admin-error': '#ef4444',
                        'admin-dark': '#1f2937',
                        'admin-light': '#f8fafc'
                    },
                    backgroundImage: {
                        'gradient-main': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                        'gradient-card': 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                        'gradient-success': 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                        'gradient-warning': 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                        'gradient-sidebar': 'linear-gradient(180deg, #667eea 0%, #764ba2 100%)',
                        'glassmorphism': 'rgba(255, 255, 255, 0.25)'
                    },
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
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
            min-width: 18px;
            height: 18px;
            background: #ef4444;
            border-radius: 50%;
            animation: pulse-custom 1.5s infinite;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .sidebar-fixed {
            position: fixed !important;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 40;
        }
        
        .main-content {
            margin-left: 288px;
        }
        
        /* Enhanced search modal styles */
        #search-modal {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        
        /* Loading states */
        .loading {
            position: relative;
            pointer-events: none;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top: 2px solid #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Notification styles */
        .global-notification {
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Enhanced dropdown animations */
        #notifications-dropdown {
            transform-origin: top right;
            transition: opacity 0.2s ease-out, transform 0.2s ease-out;
        }
        
        #notifications-dropdown.hidden {
            opacity: 0;
            transform: scale(0.95) translateY(-10px);
            pointer-events: none;
        }
        
        #notifications-dropdown:not(.hidden) {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gradient-main">
    <div class="min-h-screen">
        <!-- FIXED SIDEBAR -->
        <div class="w-72 sidebar-gradient shadow-2xl relative overflow-hidden sidebar-fixed">
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
                        <p class="text-sm text-gray-200 opacity-80">Admin Dashboard</p>
                    </div>
                </div>
            </div>
            
            <!-- User Profile Section -->
            <div class="px-6 py-4 border-b border-white border-opacity-20">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-bold">
                            {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                        </span>
                    </div>
                    <div class="flex-1">
                        <p class="text-white text-sm font-medium">{{ auth()->user()->full_name }}</p>
                        <p class="text-gray-200 text-xs opacity-75">Administrator</p>
                    </div>
                    <div class="relative">
                        <i class="fas fa-bell text-white text-sm opacity-75"></i>
                        <div class="notification-dot" id="sidebar-notification-dot" style="display: none;"></div>
                    </div>
                </div>
            </div>
            
            <nav class="mt-6 relative z-10" style="height: calc(100vh - 300px); overflow-y: auto;">
                <!-- Main Navigation -->
                <div class="px-4 py-2">
                    <span class="text-xs font-semibold text-gray-200 uppercase tracking-wide opacity-75">Main Menu</span>
                </div>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.dashboard') ? 'bg-white bg-opacity-20 border-r-4 border-white' : '' }} group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-chart-line text-sm"></i>
                    </div>
                    <span class="font-medium">Dashboard</span>
                    @if(request()->routeIs('admin.dashboard'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                    @endif
                </a>
                
                <a href="{{ route('admin.users.index') }}" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.users.*') ? 'bg-white bg-opacity-20 border-r-4 border-white' : '' }} group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                    <span class="font-medium">Users</span>
                    <div class="ml-auto flex items-center space-x-2">
                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full" id="users-count-badge">24</span>
                        @if(request()->routeIs('admin.users.*'))
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                        @endif
                    </div>
                </a>
                
                <!-- Management Section -->
                <div class="px-4 py-2 mt-6">
                    <span class="text-xs font-semibold text-gray-200 uppercase tracking-wide opacity-75">Management</span>
                </div>
                
                <a href="{{ route('admin.lands.index') }}" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.lands.*') ? 'bg-white bg-opacity-20 border-r-4 border-white' : '' }} group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-map text-sm"></i>
                    </div>
                    <span class="font-medium">Agricultural Lands</span>
                    @if(request()->routeIs('admin.lands.*'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                    @endif
                </a>
                
                <a href="{{ route('admin.listings.index') }}" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.listings.*') ? 'bg-white bg-opacity-20 border-r-4 border-white' : '' }} group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-bullhorn text-sm"></i>
                    </div>
                    <span class="font-medium">Listings</span>
                    @if(request()->routeIs('admin.listings.*'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                    @endif
                </a>
                
                <a href="{{ route('admin.transactions.index') }}" 
                   class="nav-item flex items-center px-6 py-3 text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.transactions.*') ? 'bg-white bg-opacity-20 border-r-4 border-white' : '' }} group">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-opacity-30 transition-all">
                        <i class="fas fa-credit-card text-sm"></i>
                    </div>
                    <span class="font-medium">Transactions</span>
                    <div class="ml-auto flex items-center space-x-2">
                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full" id="revenue-badge">€2.4K</span>
                        @if(request()->routeIs('admin.transactions.*'))
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                        @endif
                    </div>
                </a>
                
             

                <div style="height: 100px;"></div>
            </nav>
            
            <!-- LOGOUT FIXED AT BOTTOM -->
            <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 24px; background: linear-gradient(to top, rgba(0,0,0,0.3) 0%, transparent 100%);">
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
        
        <!-- MAIN CONTENT WITH LEFT MARGIN -->
        <div class="main-content flex flex-col min-h-screen">
            <!-- Enhanced Top Bar -->
            <header class="glass-effect border-b border-white border-opacity-20 m-4 rounded-xl">
                <div class="flex items-center justify-between px-8 py-6">
                    <div class="flex items-center space-x-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
                            <p class="text-gray-600 text-sm">Welcome back! Here's what's happening today.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <!-- Quick Stats -->
                        <div class="hidden lg:flex items-center space-x-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-800" id="users-count">47</div>
                                <div class="text-xs text-gray-600">Active Users</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600" id="revenue-amount">€12.4K</div>
                                <div class="text-xs text-gray-600">Revenue</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600" id="properties-count">156</div>
                                <div class="text-xs text-gray-600">Properties</div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center space-x-3">
                          
                            
                            <!-- Enhanced Notification Button -->
                            <div class="relative">
                                <button id="notification-button" class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-all group" title="Notifications (Ctrl+N)">
                                    <i class="fas fa-bell text-gray-700 group-hover:text-gray-900"></i>
                                </button>
                                <div class="notification-dot" id="header-notification-dot" style="display: none;"></div>
                                
                                <!-- Enhanced Notifications Dropdown -->
                                <div id="notifications-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-200 z-50">
                                    <div class="p-4 border-b border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
                                            <div class="flex items-center space-x-2">
                                                <button onclick="notificationManager.markAllAsRead()" 
                                                        class="text-xs text-blue-600 hover:text-blue-700 px-2 py-1 rounded hover:bg-blue-50 transition-colors">
                                                    Mark all read
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="max-h-96 overflow-y-auto">
                                        <div class="p-8 text-center text-gray-500">
                                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
                                            <p>Loading notifications...</p>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4 border-t border-gray-200">
                                        <button class="w-full text-center text-blue-600 hover:text-blue-700 text-sm font-medium hover:bg-blue-50 py-2 rounded transition-colors">
                                            View all notifications
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Enhanced User Menu -->
                            <div class="relative">
                                <button id="user-menu-button" class="flex items-center space-x-3 bg-white bg-opacity-20 rounded-lg px-4 py-2 hover:bg-opacity-30 transition-all">
                                    <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">
                                            {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="hidden md:block text-left">
                                        <div class="text-sm font-medium text-gray-800">{{ auth()->user()->full_name }}</div>
                                        <div class="text-xs text-gray-600">Administrator</div>
                                    </div>
                                    <i class="fas fa-chevron-down text-gray-600 text-xs"></i>
                                </button>
                                
                                <div id="user-menu" class="hidden absolute right-0 mt-2 w-64 glass-effect rounded-xl shadow-lg py-2 z-50">
                                    <div class="px-4 py-3 border-b border-white border-opacity-20">
                                        <p class="font-medium text-gray-800">{{ auth()->user()->full_name }}</p>
                                        <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                                        <div class="mt-2 flex items-center space-x-2">
                                            <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                            <span class="text-xs text-gray-600">Online</span>
                                        </div>
                                    </div>
                                    <div class="py-2">
                                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-20 transition-colors">
                                            <i class="fas fa-user mr-3"></i>Profile Settings
                                        </a>
                                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-20 transition-colors">
                                            <i class="fas fa-cog mr-3"></i>Preferences
                                        </a>
                                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-20 transition-colors">
                                            <i class="fas fa-question-circle mr-3"></i>Help & Support
                                        </a>
                                        <hr class="my-2 border-white border-opacity-20">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
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
            
            <!-- Page Content -->
            <main class="flex-1 p-6 content-area m-4 rounded-xl overflow-auto">
                <!-- Enhanced Flash Messages -->
                @if(session('success'))
                    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl mb-6 shadow-lg transform transition-all hover:scale-105">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-lg"></i>
                            <div class="flex-1">
                                <div class="font-semibold">Success!</div>
                                <div class="text-sm opacity-90">{{ session('success') }}</div>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 hover:bg-white hover:bg-opacity-20 rounded-full p-1 transition-all">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-4 rounded-xl mb-6 shadow-lg transform transition-all hover:scale-105">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                            <div class="flex-1">
                                <div class="font-semibold">Error!</div>
                                <div class="text-sm opacity-90">{{ session('error') }}</div>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 hover:bg-white hover:bg-opacity-20 rounded-full p-1 transition-all">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-4 rounded-xl mb-6 shadow-lg transform transition-all hover:scale-105">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle mr-3 text-lg mt-1"></i>
                            <div class="flex-1">
                                <div class="font-semibold mb-2">Please fix the following errors:</div>
                                <ul class="text-sm space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li class="opacity-90">• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 hover:bg-white hover:bg-opacity-20 rounded-full p-1 transition-all">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        // Set CSRF token for AJAX requests
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Enhanced Global Search with Real Backend Integration
        class AdminSearch {
            constructor() {
                this.searchModal = document.getElementById('search-modal');
                this.searchInput = document.getElementById('global-search');
                this.searchResults = document.getElementById('search-results');
                this.searchButton = document.getElementById('search-button');
                this.closeButton = document.getElementById('close-search');
                
                this.debounceTimer = null;
                this.currentRequest = null;
                
                this.init();
            }
            
            init() {
                this.searchButton.addEventListener('click', () => this.openModal());
                this.closeButton.addEventListener('click', () => this.closeModal());
                this.searchInput.addEventListener('input', (e) => this.handleSearch(e.target.value));
                
                // Close on escape key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && !this.searchModal.classList.contains('hidden')) {
                        this.closeModal();
                    }
                });
                
                // Close on outside click
                this.searchModal.addEventListener('click', (e) => {
                    if (e.target === this.searchModal) {
                        this.closeModal();
                    }
                });
                
                // Handle enter key to select first result
                this.searchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        const firstResult = this.searchResults.querySelector('[onclick]');
                        if (firstResult) {
                            const url = firstResult.getAttribute('onclick').match(/'([^']+)'/);
                            if (url && url[1]) {
                                window.location.href = url[1];
                            }
                        }
                    }
                });
            }
            
            openModal() {
                this.searchModal.classList.remove('hidden');
                this.searchInput.focus();
            }
            
            closeModal() {
                this.searchModal.classList.add('hidden');
                this.searchInput.value = '';
                this.resetResults();
            }
            
            handleSearch(query) {
                // Clear previous timer
                clearTimeout(this.debounceTimer);
                
                if (query.length < 2) {
                    this.resetResults();
                    return;
                }
                
                // Show loading state
                this.showLoading();
                
                // Debounce the search
                this.debounceTimer = setTimeout(() => {
                    this.performSearch(query);
                }, 300);
            }
            
            async performSearch(query) {
                try {
                    // Cancel previous request if it exists
                    if (this.currentRequest) {
                        this.currentRequest.abort();
                    }
                    
                    // Create new request
                    const controller = new AbortController();
                    this.currentRequest = controller;
                    
                    const response = await fetch(`/admin/search?query=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-CSRF-TOKEN': window.csrfToken,
                            'Accept': 'application/json',
                        },
                        signal: controller.signal
                    });
                    
                    if (!response.ok) {
                        throw new Error('Search failed');
                    }
                    
                    const data = await response.json();
                    this.displayResults(data.results, query);
                    
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        console.error('Search error:', error);
                        this.showError('Search failed. Please try again.');
                    }
                } finally {
                    this.currentRequest = null;
                }
            }
            
            showLoading() {
                this.searchResults.innerHTML = `
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
                        <p class="text-gray-500">Searching...</p>
                    </div>
                `;
            }
            
            showError(message) {
                this.searchResults.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-3xl mb-4 text-red-500"></i>
                        <p class="text-red-500">${message}</p>
                        <button onclick="adminSearch.handleSearch(adminSearch.searchInput.value)" 
                                class="mt-2 text-sm text-blue-600 hover:text-blue-700">
                            Try again
                        </button>
                    </div>
                `;
            }
            
            resetResults() {
                this.searchResults.innerHTML = `
                    <div class="text-gray-500 text-center py-8">
                        <i class="fas fa-search text-3xl mb-4 opacity-50"></i>
                        <p>Start typing to search...</p>
                        <div class="mt-4 text-xs">
                            <p class="text-gray-400">Search across users, lands, listings, and transactions</p>
                        </div>
                    </div>
                `;
            }
            
            displayResults(results, query) {
                if (results.length === 0) {
                    this.searchResults.innerHTML = `
                        <div class="text-gray-500 text-center py-8">
                            <i class="fas fa-search text-3xl mb-4 opacity-50"></i>
                            <p>No results found for "${query}"</p>
                            <div class="mt-4 text-xs text-gray-400">
                                <p>Try using different keywords or check spelling</p>
                            </div>
                        </div>
                    `;
                    return;
                }
                
                let resultsHTML = '<div class="space-y-2">';
                results.forEach((result, index) => {
                    resultsHTML += `
                        <div onclick="window.location.href='${result.url}'" 
                             class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors group ${index === 0 ? 'bg-blue-50 border border-blue-200' : ''}">
                            <div class="w-10 h-10 bg-${result.color}-500 rounded-lg flex items-center justify-center">
                                <i class="${result.icon} text-white text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800 group-hover:text-${result.color}-600">${result.title}</p>
                                <p class="text-sm text-gray-600">${result.subtitle}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                ${index === 0 ? '<kbd class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-xs">↵</kbd>' : ''}
                                <i class="fas fa-arrow-right text-gray-400 group-hover:text-${result.color}-500 transition-colors"></i>
                            </div>
                        </div>
                    `;
                });
                resultsHTML += '</div>';
                
                // Add results summary
                resultsHTML += `
                    <div class="mt-4 pt-4 border-t text-xs text-gray-500 text-center">
                        Found ${results.length} result${results.length !== 1 ? 's' : ''} for "${query}"
                    </div>
                `;
                
                this.searchResults.innerHTML = resultsHTML;
            }
        }

        // Enhanced Notification System with Real Backend Integration
        class NotificationManager {
            constructor() {
                this.notificationButton = document.getElementById('notification-button');
                this.notificationDropdown = document.getElementById('notifications-dropdown');
                this.headerNotificationDot = document.getElementById('header-notification-dot');
                this.sidebarNotificationDot = document.getElementById('sidebar-notification-dot');
                
                this.notifications = [];
                this.unreadCount = 0;
                
                this.init();
                this.loadNotifications();
                
                // Refresh notifications every 30 seconds
                setInterval(() => this.loadNotifications(), 30000);
            }
            
            init() {
                this.notificationButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleDropdown();
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('#notification-button') && !e.target.closest('#notifications-dropdown')) {
                        this.closeDropdown();
                    }
                });
            }
            
            async loadNotifications() {
                try {
                    const response = await fetch('/admin/notifications', {
                        headers: {
                            'X-CSRF-TOKEN': window.csrfToken,
                            'Accept': 'application/json',
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error('Failed to load notifications');
                    }
                    
                    const data = await response.json();
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                    
                    this.updateUI();
                    
                } catch (error) {
                    console.error('Failed to load notifications:', error);
                    this.showErrorInDropdown();
                }
            }
            
            updateUI() {
                this.updateNotificationDots();
                this.updateDropdownContent();
            }
            
            updateNotificationDots() {
                const dots = [this.headerNotificationDot, this.sidebarNotificationDot];
                
                dots.forEach(dot => {
                    if (this.unreadCount > 0) {
                        dot.style.display = 'flex';
                        dot.textContent = this.unreadCount > 99 ? '99+' : this.unreadCount;
                    } else {
                        dot.style.display = 'none';
                    }
                });
            }
            
            updateDropdownContent() {
                const dropdown = this.notificationDropdown;
                
                // Update header
                const header = dropdown.querySelector('.p-4.border-b');
                header.innerHTML = `
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
                        <div class="flex items-center space-x-2">
                            ${this.unreadCount > 0 ? `<span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">${this.unreadCount}</span>` : ''}
                            <button onclick="notificationManager.markAllAsRead()" 
                                    class="text-xs text-blue-600 hover:text-blue-700 px-2 py-1 rounded hover:bg-blue-50 transition-colors">
                                Mark all read
                            </button>
                        </div>
                    </div>
                `;
                
                // Update content
                const content = dropdown.querySelector('.max-h-96.overflow-y-auto');
                
                if (this.notifications.length === 0) {
                    content.innerHTML = `
                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-bell-slash text-3xl mb-4 opacity-50"></i>
                            <p>No notifications yet</p>
                            <p class="text-xs text-gray-400 mt-2">You'll see updates about your admin activities here</p>
                        </div>
                    `;
                    return;
                }
                
                let notificationsHTML = '';
                this.notifications.forEach(notification => {
                    const isUnread = !notification.read_at;
                    notificationsHTML += `
                        <div class="p-4 hover:bg-gray-50 border-b border-gray-100 cursor-pointer transition-colors group ${isUnread ? 'bg-blue-50 border-l-4 border-l-blue-500' : ''}"
                             onclick="notificationManager.handleNotificationClick(${notification.id}, '${notification.action_url || '#'}')">
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-${notification.color}-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="${notification.icon} text-white text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800 ${isUnread ? 'font-semibold' : ''}">${notification.title}</p>
                                    <p class="text-xs text-gray-600 mt-1">${notification.message}</p>
                                    <p class="text-xs text-gray-400 mt-2">${this.formatDate(notification.created_at)}</p>
                                </div>
                                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    ${isUnread ? '<div class="w-2 h-2 bg-blue-500 rounded-full"></div>' : ''}
                                    <button onclick="event.stopPropagation(); notificationManager.deleteNotification(${notification.id})" 
                                            class="text-gray-400 hover:text-red-500 p-1 rounded hover:bg-red-50 transition-colors">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                content.innerHTML = notificationsHTML;
            }
            
            showErrorInDropdown() {
                const content = this.notificationDropdown.querySelector('.max-h-96.overflow-y-auto');
                content.innerHTML = `
                    <div class="p-8 text-center text-red-500">
                        <i class="fas fa-exclamation-triangle text-3xl mb-4"></i>
                        <p>Failed to load notifications</p>
                        <button onclick="notificationManager.loadNotifications()" 
                                class="mt-2 text-sm text-blue-600 hover:text-blue-700 px-3 py-1 rounded hover:bg-blue-50">
                            Retry
                        </button>
                    </div>
                `;
            }
            
            toggleDropdown() {
                this.notificationDropdown.classList.toggle('hidden');
                
                // Hide notification dots when opened
                if (!this.notificationDropdown.classList.contains('hidden')) {
                    this.headerNotificationDot.style.display = 'none';
                    this.sidebarNotificationDot.style.display = 'none';
                }
            }
            
            closeDropdown() {
                this.notificationDropdown.classList.add('hidden');
                this.updateNotificationDots(); // Restore dots if there are unread notifications
            }
            
            async handleNotificationClick(notificationId, actionUrl) {
                // Mark as read
                await this.markAsRead(notificationId);
                
                // Navigate to action URL if provided
                if (actionUrl && actionUrl !== '#') {
                    window.location.href = actionUrl;
                }
            }
            
            async markAsRead(notificationId) {
                try {
                    const response = await fetch(`/admin/notifications/mark-read/${notificationId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': window.csrfToken,
                            'Accept': 'application/json',
                        }
                    });
                    
                    if (response.ok) {
                        // Update local state
                        const notification = this.notifications.find(n => n.id === notificationId);
                        if (notification && !notification.read_at) {
                            notification.read_at = new Date().toISOString();
                            this.unreadCount = Math.max(0, this.unreadCount - 1);
                            this.updateUI();
                        }
                    }
                } catch (error) {
                    console.error('Failed to mark notification as read:', error);
                }
            }
            
            async markAllAsRead() {
                try {
                    const response = await fetch('/admin/notifications/mark-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': window.csrfToken,
                            'Accept': 'application/json',
                        }
                    });
                    
                    if (response.ok) {
                        // Update local state
                        this.notifications.forEach(notification => {
                            if (!notification.read_at) {
                                notification.read_at = new Date().toISOString();
                            }
                        });
                        this.unreadCount = 0;
                        this.updateUI();
                        
                        showNotification('All notifications marked as read', 'success');
                    }
                } catch (error) {
                    console.error('Failed to mark all notifications as read:', error);
                    showNotification('Failed to mark notifications as read', 'error');
                }
            }
            
            async deleteNotification(notificationId) {
                try {
                    const response = await fetch(`/admin/notifications/${notificationId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': window.csrfToken,
                            'Accept': 'application/json',
                        }
                    });
                    
                    if (response.ok) {
                        // Remove from local state
                        const index = this.notifications.findIndex(n => n.id === notificationId);
                        if (index !== -1) {
                            const notification = this.notifications[index];
                            if (!notification.read_at) {
                                this.unreadCount = Math.max(0, this.unreadCount - 1);
                            }
                            this.notifications.splice(index, 1);
                            this.updateUI();
                        }
                        
                        showNotification('Notification deleted', 'success');
                    }
                } catch (error) {
                    console.error('Failed to delete notification:', error);
                    showNotification('Failed to delete notification', 'error');
                }
            }
            
            formatDate(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffMs = now - date;
                const diffMins = Math.floor(diffMs / 60000);
                const diffHours = Math.floor(diffMs / 3600000);
                const diffDays = Math.floor(diffMs / 86400000);
                
                if (diffMins < 1) return 'just now';
                if (diffMins < 60) return `${diffMins} minute${diffMins !== 1 ? 's' : ''} ago`;
                if (diffHours < 24) return `${diffHours} hour${diffHours !== 1 ? 's' : ''} ago`;
                if (diffDays < 7) return `${diffDays} day${diffDays !== 1 ? 's' : ''} ago`;
                
                return date.toLocaleDateString();
            }
        }

        // Enhanced notification system for showing toast messages
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.global-notification');
            existingNotifications.forEach(notification => notification.remove());
            
            const notification = document.createElement('div');
            notification.className = `global-notification fixed top-4 right-4 z-50 min-w-80 p-4 rounded-xl shadow-2xl transform transition-all duration-500 translate-x-full`;
            
            const colors = {
                success: 'bg-gradient-to-r from-green-500 to-green-600',
                info: 'bg-gradient-to-r from-blue-500 to-blue-600',
                warning: 'bg-gradient-to-r from-yellow-500 to-yellow-600',
                error: 'bg-gradient-to-r from-red-500 to-red-600'
            };
            
            const icons = {
                success: 'fa-check-circle',
                info: 'fa-info-circle',
                warning: 'fa-exclamation-triangle',
                error: 'fa-times-circle'
            };
            
            notification.classList.add(colors[type]);
            notification.innerHTML = `
                <div class="flex items-center text-white">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas ${icons[type]}"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold capitalize">${type}</div>
                        <div class="text-sm opacity-90">${message}</div>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 w-6 h-6 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-all">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => notification.remove(), 500);
                }
            }, 5000);
        }

        // User menu toggle
        document.getElementById('user-menu-button').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('user-menu').classList.toggle('hidden');
        });
        
        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#user-menu-button') && !event.target.closest('#user-menu')) {
                document.getElementById('user-menu').classList.add('hidden');
            }
        });

        // Initialize the enhanced search and notification systems
        let adminSearch, notificationManager;

        document.addEventListener('DOMContentLoaded', function() {
            adminSearch = new AdminSearch();
            notificationManager = new NotificationManager();
            
            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + K for search
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    adminSearch.openModal();
                }
                
                // Ctrl/Cmd + N for notifications
                if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                    e.preventDefault();
                    notificationManager.toggleDropdown();
                }
            });
            
            // Show welcome notification
            setTimeout(() => {
                showNotification('Search and notification systems are now fully functional!', 'success');
            }, 1000);
        });

        // Real-time updates using Server-Sent Events (optional enhancement)
        class RealTimeUpdates {
            constructor() {
                this.eventSource = null;
                this.reconnectAttempts = 0;
                this.maxReconnectAttempts = 5;
                this.init();
            }
            
            init() {
                // Only initialize if the browser supports EventSource
                if (typeof(EventSource) !== "undefined") {
                    this.connect();
                }
            }
            
            connect() {
                try {
                    // Create connection to server-sent events endpoint
                    this.eventSource = new EventSource('/admin/sse');
                    
                    this.eventSource.onopen = () => {
                        console.log('SSE connection established');
                        this.reconnectAttempts = 0;
                    };
                    
                    this.eventSource.onmessage = (event) => {
                        try {
                            const data = JSON.parse(event.data);
                            this.handleUpdate(data);
                        } catch (error) {
                            console.error('Error parsing SSE data:', error);
                        }
                    };
                    
                    this.eventSource.onerror = (error) => {
                        console.log('SSE connection error:', error);
                        this.eventSource.close();
                        
                        // Attempt to reconnect with exponential backoff
                        if (this.reconnectAttempts < this.maxReconnectAttempts) {
                            this.reconnectAttempts++;
                            const delay = Math.pow(2, this.reconnectAttempts) * 1000;
                            setTimeout(() => this.connect(), delay);
                        }
                    };
                } catch (error) {
                    console.error('Failed to establish SSE connection:', error);
                }
            }
            
            handleUpdate(data) {
                switch(data.type) {
                    case 'notification':
                        // Add new notification to the manager
                        if (notificationManager) {
                            notificationManager.notifications.unshift(data.notification);
                            notificationManager.unreadCount++;
                            notificationManager.updateUI();
                            
                            // Show toast for high priority notifications
                            if (data.notification.priority === 'high') {
                                showNotification(data.notification.title, 'warning');
                            }
                        }
                        break;
                        
                    case 'stats_update':
                        // Update dashboard stats in real-time
                        this.updateDashboardStats(data.stats);
                        break;
                        
                    case 'user_activity':
                        // Update user activity indicators
                        this.updateUserActivity(data.activity);
                        break;
                }
            }
            
            updateDashboardStats(stats) {
                // Update various stat elements on the page
                const statElements = {
                    'users-count': stats.users,
                    'revenue-amount': stats.revenue,
                    'properties-count': stats.properties
                };
                
                Object.entries(statElements).forEach(([elementId, value]) => {
                    const element = document.getElementById(elementId);
                    if (element) {
                        // Animate the change
                        element.style.transform = 'scale(1.1)';
                        element.style.transition = 'transform 0.2s ease';
                        element.textContent = value;
                        setTimeout(() => {
                            element.style.transform = 'scale(1)';
                        }, 200);
                    }
                });
                
                // Update sidebar badges
                if (stats.users) {
                    const usersBadge = document.getElementById('users-count-badge');
                    if (usersBadge) {
                        usersBadge.textContent = stats.users;
                    }
                }
                
                if (stats.revenue) {
                    const revenueBadge = document.getElementById('revenue-badge');
                    if (revenueBadge) {
                        revenueBadge.textContent = stats.revenue;
                    }
                }
            }
            
            updateUserActivity(activity) {
                // Add activity indicators or update user status
                console.log('User activity update:', activity);
            }
            
            disconnect() {
                if (this.eventSource) {
                    this.eventSource.close();
                }
            }
        }

        // Initialize real-time updates (optional)
        let realTimeUpdates;
        if (window.location.pathname.includes('/admin/dashboard')) {
            realTimeUpdates = new RealTimeUpdates();
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (realTimeUpdates) {
                realTimeUpdates.disconnect();
            }
        });

        // Add some interactivity to cards
        document.querySelectorAll('.card-hover').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px) scale(1.02)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Performance monitoring
        function logPerformance() {
            if (performance.mark) {
                performance.mark('admin-layout-loaded');
                console.log('Admin layout performance:', performance.now());
            }
        }

        // Initialize performance monitoring
        window.addEventListener('load', logPerformance);

        // Add loading states for navigation
        document.querySelectorAll('a[href]').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.href.includes('/admin/')) {
                    const loader = document.createElement('div');
                    loader.className = 'fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-50';
                    loader.innerHTML = `
                        <div class="bg-white rounded-xl p-6 shadow-2xl">
                            <div class="flex items-center space-x-3">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                                <span class="text-gray-700">Loading...</span>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(loader);
                    
                    // Remove loader after 5 seconds (fallback)
                    setTimeout(() => {
                        if (loader.parentNode) {
                            loader.remove();
                        }
                    }, 5000);
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>