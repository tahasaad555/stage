<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AgriTerre Platform</title>
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
        
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .role-tab {
            transition: all 0.3s ease;
        }
        
        .role-tab.active {
            background: rgba(255, 255, 255, 0.2);
            border-bottom: 3px solid #10b981;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-20 w-32 h-32 bg-white opacity-10 rounded-full floating-animation"></div>
        <div class="absolute bottom-20 right-20 w-24 h-24 bg-green-400 opacity-20 rounded-full floating-animation" style="animation-delay: -2s;"></div>
        <div class="absolute top-1/2 left-10 w-16 h-16 bg-blue-400 opacity-15 rounded-full floating-animation" style="animation-delay: -4s;"></div>
    </div>

    <div class="relative w-full max-w-md">
        <!-- Main Login Card -->
        <div class="glass-effect rounded-3xl shadow-2xl p-8 backdrop-blur-xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <i class="fas fa-seedling text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">AgriTerre</h1>
                <p class="text-white text-opacity-80">Welcome back to your agricultural platform</p>
            </div>

            <!-- Role Selector -->
            <div class="flex mb-6 bg-white bg-opacity-10 rounded-xl p-1">
                <button type="button" class="role-tab flex-1 py-2 text-sm font-medium text-white rounded-lg active" data-role="client">
                    <i class="fas fa-user mr-2"></i>Client
                </button>
                <button type="button" class="role-tab flex-1 py-2 text-sm font-medium text-white rounded-lg" data-role="supplier">
                    <i class="fas fa-store mr-2"></i>Supplier
                </button>
                <button type="button" class="role-tab flex-1 py-2 text-sm font-medium text-white rounded-lg" data-role="admin">
                    <i class="fas fa-crown mr-2"></i>Admin
                </button>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-6 bg-red-500 bg-opacity-20 border border-red-400 border-opacity-30 text-red-100 px-4 py-3 rounded-xl">
                    @foreach($errors->all() as $error)
                        <p class="text-sm flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 bg-green-500 bg-opacity-20 border border-green-400 border-opacity-30 text-green-100 px-4 py-3 rounded-xl">
                    <p class="text-sm flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </p>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" id="user_type" name="user_type" value="client">
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email Address
                    </label>
                    <input type="email" id="email" name="email" required 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all"
                           placeholder="Enter your email">
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                               class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all pr-12"
                               placeholder="Enter your password">
                        <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white text-opacity-60 hover:text-opacity-100">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-green-400 bg-white bg-opacity-20 border border-white border-opacity-30 rounded focus:ring-green-400 focus:ring-2">
                        <span class="ml-2 text-sm text-white text-opacity-80">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-green-300 hover:text-green-200 transition-colors">
                        Forgot password?
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-green-400 to-blue-500 text-white font-semibold py-3 px-6 rounded-xl hover:from-green-500 hover:to-blue-600 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </button>
            </form>

            <!-- Divider -->
            <div class="my-8 flex items-center">
                <div class="flex-grow h-px bg-white bg-opacity-30"></div>
                <span class="mx-4 text-white text-opacity-60 text-sm">or</span>
                <div class="flex-grow h-px bg-white bg-opacity-30"></div>
            </div>

            <!-- Register Link -->
            <div class="text-center">
                <p class="text-white text-opacity-80 text-sm mb-4">Don't have an account?</p>
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center px-6 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-xl transition-all duration-200">
                    <i class="fas fa-user-plus mr-2"></i>Create Account
                </a>
            </div>

            <!-- Social Login (Optional) -->
            <div class="mt-6 grid grid-cols-2 gap-3">
                <button type="button" class="flex items-center justify-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-xl transition-all duration-200">
                    <i class="fab fa-google mr-2"></i>Google
                </button>
                <button type="button" class="flex items-center justify-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-xl transition-all duration-200">
                    <i class="fab fa-facebook mr-2"></i>Facebook
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-white text-opacity-60 text-sm">
                © 2025 AgriTerre. All rights reserved.
            </p>
        </div>
    </div>

    <script>
        // Role tab switching
        document.querySelectorAll('.role-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Update hidden input
                document.getElementById('user_type').value = this.dataset.role;
            });
        });

        // Password visibility toggle
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Form submission animation
        document.querySelector('form').addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing In...';
            button.disabled = true;
        });
    </script>
</body>
</html>