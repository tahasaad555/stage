<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriTerre - Agricultural Land Management Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .floating {
            animation: floating 6s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(67, 233, 123, 0.3);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    
    <!-- Main Container -->
    <div class="max-w-md w-full space-y-6 text-center">
        <!-- Logo Section -->
        <div class="floating">
            <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-gradient-to-r from-green-400 to-emerald-500 shadow-lg">
                <i class="fas fa-seedling text-white text-xl"></i>
            </div>
        </div>
        
        <!-- Title Section -->
        <div class="space-y-2">
            <h1 class="text-3xl font-bold text-white">
                AgriTerre
            </h1>
            <p class="text-white opacity-80 text-sm">
                Agricultural Land Management Platform
            </p>
        </div>
        
        <!-- Main Card -->
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            @if(auth()->check())
                <!-- Authenticated User -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-medium text-sm">
                                {{ substr(auth()->user()->first_name ?? 'U', 0, 1) }}{{ substr(auth()->user()->last_name ?? 'U', 0, 1) }}
                            </span>
                        </div>
                        <div class="text-left">
                            <h3 class="text-lg font-semibold text-gray-800">Welcome back!</h3>
                            <p class="text-gray-600 text-sm">{{ auth()->user()->full_name }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <span class="text-green-800 text-sm font-medium">
                                Logged in as {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Role-based Dashboard Access -->
                    <div class="space-y-2">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" 
                               class="btn-primary flex items-center justify-center w-full px-6 py-3 text-white font-medium rounded-lg">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Admin Dashboard
                            </a>
                        @endif
                        
                        @if(auth()->user()->isClient())
                            <a href="{{ route('client.dashboard') }}" 
                               class="btn-secondary flex items-center justify-center w-full px-6 py-3 text-white font-medium rounded-lg">
                                <i class="fas fa-user mr-2"></i>
                                Client Portal
                            </a>
                        @endif
                        
                        @if(auth()->user()->isFournisseur())
                            <a href="{{ route('supplier.dashboard') }}" 
                               class="btn-success flex items-center justify-center w-full px-6 py-3 text-white font-medium rounded-lg">
                                <i class="fas fa-store mr-2"></i>
                                Supplier Portal
                            </a>
                        @endif
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" 
                                class="text-gray-500 hover:text-gray-700 text-sm transition-colors">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            @else
                <!-- Not Authenticated -->
                <div class="space-y-4">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-user-shield text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Access Your Account</h3>
                        <p class="text-gray-600 text-sm">
                            Sign in to access your agricultural platform dashboard.
                        </p>
                    </div>
                    
                    <div class="space-y-2">
                        <a href="{{ route('login') }}" 
                           class="btn-primary flex items-center justify-center w-full px-6 py-3 text-white font-medium rounded-lg">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Sign In
                        </a>
                        
                        <a href="{{ route('register') }}" 
                           class="btn-secondary flex items-center justify-center w-full px-6 py-3 text-white font-medium rounded-lg">
                            <i class="fas fa-user-plus mr-2"></i>
                            Create Account
                        </a>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Features -->
        <div class="glass-effect rounded-xl p-4">
            <div class="grid grid-cols-3 gap-3 text-center">
                <div class="space-y-1">
                    <div class="w-8 h-8 bg-blue-500 bg-opacity-20 rounded-lg flex items-center justify-center mx-auto">
                        <i class="fas fa-map-marked-alt text-blue-400 text-sm"></i>
                    </div>
                    <h4 class="text-white font-medium text-xs">Land Management</h4>
                </div>
                
                <div class="space-y-1">
                    <div class="w-8 h-8 bg-green-500 bg-opacity-20 rounded-lg flex items-center justify-center mx-auto">
                        <i class="fas fa-chart-line text-green-400 text-sm"></i>
                    </div>
                    <h4 class="text-white font-medium text-xs">Investment Tools</h4>
                </div>
                
                <div class="space-y-1">
                    <div class="w-8 h-8 bg-purple-500 bg-opacity-20 rounded-lg flex items-center justify-center mx-auto">
                        <i class="fas fa-handshake text-purple-400 text-sm"></i>
                    </div>
                    <h4 class="text-white font-medium text-xs">Marketplace</h4>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center">
            <div class="flex items-center justify-center space-x-4 text-white opacity-50 text-xs">
                <span class="flex items-center">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Secure
                </span>
                <span class="flex items-center">
                    <i class="fas fa-cloud mr-1"></i>
                    Cloud-Based
                </span>
                <span class="flex items-center">
                    <i class="fas fa-mobile-alt mr-1"></i>
                    Responsive
                </span>
            </div>
        </div>
    </div>
</body>
</html>