<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - AgriTerre Platform</title>
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

    <div class="relative w-full max-w-md">
        <!-- Main Forgot Password Card -->
        <div class="glass-effect rounded-3xl shadow-2xl p-8 backdrop-blur-xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-orange-400 to-red-500 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <i class="fas fa-key text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Forgot Password?</h1>
                <p class="text-white text-opacity-80">No worries! Enter your email and we'll send you reset instructions</p>
            </div>

            <!-- Success Message -->
            @if(session('status'))
                <div class="mb-6 bg-green-500 bg-opacity-20 border border-green-400 border-opacity-30 text-green-100 px-4 py-3 rounded-xl" id="successMessage">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-green-300"></i>
                        <div>
                            <p class="font-semibold">Email Sent Successfully!</p>
                            <p class="text-sm text-green-200">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

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

            <!-- Reset Form -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-6" id="resetForm">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email Address
                    </label>
                    <input type="email" id="email" name="email" required 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition-all"
                           placeholder="Enter your registered email address">
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn"
                        class="w-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-semibold py-3 px-6 rounded-xl hover:from-orange-500 hover:to-red-600 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="fas fa-paper-plane mr-2"></i>Send Reset Link
                </button>
            </form>

            <!-- Additional Information -->
            <div class="mt-8 space-y-4">
                <!-- Steps Info -->
                <div class="glass-effect rounded-xl p-4">
                    <h4 class="text-white font-semibold mb-3 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-300"></i>What happens next?
                    </h4>
                    <div class="space-y-2 text-sm text-white text-opacity-80">
                        <div class="flex items-center">
                            <span class="w-6 h-6 bg-orange-400 rounded-full flex items-center justify-center text-black font-bold text-xs mr-3">1</span>
                            <span>We'll send a secure reset link to your email</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-6 h-6 bg-orange-400 rounded-full flex items-center justify-center text-black font-bold text-xs mr-3">2</span>
                            <span>Click the link to access the password reset form</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-6 h-6 bg-orange-400 rounded-full flex items-center justify-center text-black font-bold text-xs mr-3">3</span>
                            <span>Create a new secure password</span>
                        </div>
                    </div>
                </div>

                <!-- Security Note -->
                <div class="glass-effect rounded-xl p-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-shield-alt text-green-300 mt-1"></i>
                        <div class="text-sm text-white text-opacity-80">
                            <p class="font-semibold text-white mb-1">Security Note</p>
                            <p>Reset links expire after 60 minutes for your security. If you don't receive the email, check your spam folder or try again.</p>
                        </div>
                    </div>
                </div>

                <!-- Troubleshooting -->
                <div class="glass-effect rounded-xl p-4">
                    <h5 class="text-white font-semibold mb-2 flex items-center">
                        <i class="fas fa-question-circle mr-2 text-yellow-300"></i>Need Help?
                    </h5>
                    <div class="text-sm text-white text-opacity-80 space-y-1">
                        <p>• Make sure you're using the email associated with your account</p>
                        <p>• Check your spam/junk folder</p>
                        <p>• Contact support if you continue having issues</p>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="my-8 flex items-center">
                <div class="flex-grow h-px bg-white bg-opacity-30"></div>
                <span class="mx-4 text-white text-opacity-60 text-sm">or</span>
                <div class="flex-grow h-px bg-white bg-opacity-30"></div>
            </div>

            <!-- Back to Login -->
            <div class="text-center space-y-4">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center px-6 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-xl transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Login
                </a>
                
                <div class="text-sm text-white text-opacity-60">
                    Remember your password? 
                    <a href="{{ route('login') }}" class="text-green-300 hover:text-green-200 underline font-medium">Sign in here</a>
                </div>
            </div>

            <!-- Support Contact -->
            <div class="mt-8 text-center">
                <p class="text-xs text-white text-opacity-50 mb-2">Still having trouble?</p>
                <a href="mailto:support@agriterre.com" class="text-sm text-blue-300 hover:text-blue-200 underline">
                    <i class="fas fa-envelope mr-1"></i>Contact Support
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
        // Form submission handling
        document.getElementById('resetForm').addEventListener('submit', function() {
            const button = document.getElementById('submitBtn');
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
            button.disabled = true;
            
            // Re-enable button after 10 seconds (in case of network issues)
            setTimeout(() => {
                if (button.disabled) {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            }, 10000);
        });

        // Auto-hide success message after 10 seconds
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.opacity = '0';
                successMessage.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 300);
            }, 10000);
        }

        // Email validation on input
        document.getElementById('email').addEventListener('input', function() {
            const email = this.value;
            const submitBtn = document.getElementById('submitBtn');
            
            if (email && isValidEmail(email)) {
                submitBtn.disabled = false;
                this.classList.remove('border-red-400');
                this.classList.add('border-white');
            } else {
                submitBtn.disabled = true;
                if (email) {
                    this.classList.add('border-red-400');
                    this.classList.remove('border-white');
                }
            }
        });

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        // Add floating effect to icons
        setInterval(() => {
            const icons = document.querySelectorAll('.fa-key, .fa-shield-alt, .fa-question-circle');
            icons.forEach(icon => {
                icon.classList.add('pulse-animation');
                setTimeout(() => {
                    icon.classList.remove('pulse-animation');
                }, 2000);
            });
        }, 5000);
    </script>
</body>
</html>