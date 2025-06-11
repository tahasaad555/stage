<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - AgriTerre Platform</title>
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
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
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

    <div class="relative w-full max-w-lg">
        <!-- Main Verification Card -->
        <div class="glass-effect rounded-3xl shadow-2xl p-8 backdrop-blur-xl text-center">
            <!-- Header -->
            <div class="mb-8">
                <div class="mx-auto h-20 w-20 bg-gradient-to-r from-green-400 to-blue-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg pulse-animation">
                    <i class="fas fa-envelope-open text-white text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Verify Your Email</h1>
                <p class="text-white text-opacity-80">We've sent a verification link to your email address</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-500 bg-opacity-20 border border-green-400 border-opacity-30 text-green-100 px-4 py-3 rounded-xl">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Resend Success Message -->
            @if(session('message'))
                <div class="mb-6 bg-blue-500 bg-opacity-20 border border-blue-400 border-opacity-30 text-blue-100 px-4 py-3 rounded-xl">
                    <div class="flex items-center">
                        <i class="fas fa-paper-plane mr-3"></i>
                        <span>{{ session('message') }}</span>
                    </div>
                </div>
            @endif

            <!-- Email Info -->
            <div class="mb-8 bg-white bg-opacity-10 rounded-xl p-6">
                <div class="flex items-center justify-center mb-4">
                    <i class="fas fa-envelope text-white text-opacity-60 mr-3"></i>
                    <span class="text-white text-opacity-80">Email sent to:</span>
                </div>
                <p class="text-white font-semibold text-lg">{{ auth()->user()->email }}</p>
            </div>

            <!-- Instructions -->
            <div class="mb-8 text-white text-opacity-80">
                <p class="mb-4">Please check your email and click the verification link to activate your account.</p>
                <p class="text-sm">Don't see the email? Check your spam folder or request a new one below.</p>
            </div>

            <!-- Resend Button -->
            <form method="POST" action="{{ route('verification.send') }}" class="mb-6">
                @csrf
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-green-400 to-blue-500 text-white font-semibold py-3 px-6 rounded-xl hover:from-green-500 hover:to-blue-600 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="fas fa-paper-plane mr-2"></i>Resend Verification Email
                </button>
            </form>

            <!-- Divider -->
            <div class="mb-6 flex items-center">
                <div class="flex-grow h-px bg-white bg-opacity-30"></div>
                <span class="mx-4 text-white text-opacity-60 text-sm">or</span>
                <div class="flex-grow h-px bg-white bg-opacity-30"></div>
            </div>

            <!-- Back to Login -->
            <div class="space-y-3">
                <a href="{{ route('login') }}" 
                   class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white py-2 px-4 rounded-xl transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Login
                </a>

                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-red-500 bg-opacity-20 hover:bg-opacity-30 text-white py-2 px-4 rounded-xl transition-all duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 text-center">
            <p class="text-white text-opacity-60 text-sm mb-4">
                Having trouble? Contact our support team for assistance.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="mailto:support@agriterre.com" 
                   class="text-white text-opacity-70 hover:text-opacity-100 transition-colors">
                    <i class="fas fa-envelope mr-1"></i>support@agriterre.com
                </a>
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
        // Auto-hide success messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[class*="bg-green-500"], [class*="bg-blue-500"]');
            alerts.forEach(alert => {
                if (alert.style.display !== 'none') {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.style.display = 'none', 500);
                }
            });
        }, 5000);

        // Prevent multiple form submissions
        document.querySelector('form').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
            
            // Re-enable after 3 seconds to allow retry if needed
            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Resend Verification Email';
            }, 3000);
        });
    </script>
</body>
</html>