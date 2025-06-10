<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - AgriTerre Platform</title>
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
        
        .password-strength {
            height: 4px;
            transition: all 0.3s ease;
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
        <!-- Main Reset Password Card -->
        <div class="glass-effect rounded-3xl shadow-2xl p-8 backdrop-blur-xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <i class="fas fa-lock text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Reset Password</h1>
                <p class="text-white text-opacity-80">Create a new secure password for your account</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-6 bg-red-500 bg-opacity-20 border border-red-400 border-opacity-30 text-red-100 px-4 py-3 rounded-xl">
                    <h4 class="font-semibold mb-2">Please fix the following errors:</h4>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Reset Password Form -->
            <form action="{{ route('password.update') }}" method="POST" class="space-y-6" id="resetForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <!-- Email Field (Read-only) -->
                <div>
                    <label for="email" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email Address
                    </label>
                    <input type="email" id="email" name="email" required readonly
                           value="{{ $email ?? old('email') }}"
                           class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-xl text-white placeholder-white placeholder-opacity-60 cursor-not-allowed">
                    <p class="mt-1 text-xs text-white text-opacity-60">This field cannot be changed</p>
                </div>

                <!-- New Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                        <i class="fas fa-key mr-2"></i>New Password
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                               class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all pr-12"
                               placeholder="Enter your new password">
                        <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white text-opacity-60 hover:text-opacity-100">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="password-strength bg-gray-300 bg-opacity-30 rounded-full" id="passwordStrength">
                            <div class="h-full rounded-full transition-all duration-300" id="strengthBar"></div>
                        </div>
                        <p class="text-xs text-white text-opacity-60 mt-1" id="strengthText">Password strength: Weak</p>
                    </div>
                    
                    <!-- Password Requirements -->
                    <div class="mt-3 space-y-1 text-xs">
                        <p class="text-white text-opacity-60 mb-2">Password requirements:</p>
                        <div class="grid grid-cols-1 gap-1">
                            <div id="req-length" class="flex items-center text-red-300">
                                <i class="fas fa-times mr-2"></i>At least 8 characters
                            </div>
                            <div id="req-lowercase" class="flex items-center text-red-300">
                                <i class="fas fa-times mr-2"></i>One lowercase letter
                            </div>
                            <div id="req-uppercase" class="flex items-center text-red-300">
                                <i class="fas fa-times mr-2"></i>One uppercase letter
                            </div>
                            <div id="req-number" class="flex items-center text-red-300">
                                <i class="fas fa-times mr-2"></i>One number
                            </div>
                            <div id="req-special" class="flex items-center text-red-300">
                                <i class="fas fa-times mr-2"></i>One special character
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                        <i class="fas fa-lock mr-2"></i>Confirm New Password
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all pr-12"
                               placeholder="Confirm your new password">
                        <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white text-opacity-60 hover:text-opacity-100">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div id="passwordMatch" class="mt-1 text-xs hidden">
                        <span class="text-red-300 flex items-center">
                            <i class="fas fa-times mr-1"></i>Passwords don't match
                        </span>
                    </div>
                    <div id="passwordMatchSuccess" class="mt-1 text-xs hidden">
                        <span class="text-green-300 flex items-center">
                            <i class="fas fa-check mr-1"></i>Passwords match
                        </span>
                    </div>
                </div>

                <!-- Security Tips -->
                <div class="glass-effect rounded-xl p-4">
                    <h4 class="text-white font-semibold mb-3 flex items-center">
                        <i class="fas fa-shield-alt mr-2 text-green-300"></i>Security Tips
                    </h4>
                    <div class="space-y-2 text-xs text-white text-opacity-80">
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-300 mr-2 mt-0.5"></i>
                            <span>Use a unique password you haven't used elsewhere</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-300 mr-2 mt-0.5"></i>
                            <span>Include a mix of letters, numbers, and symbols</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-300 mr-2 mt-0.5"></i>
                            <span>Avoid personal information like names or dates</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-300 mr-2 mt-0.5"></i>
                            <span>Consider using a password manager</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" disabled
                        class="w-full bg-gradient-to-r from-purple-400 to-pink-500 text-white font-semibold py-3 px-6 rounded-xl hover:from-purple-500 hover:to-pink-600 transform hover:scale-105 transition-all duration-200 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <i class="fas fa-save mr-2"></i>Update Password
                </button>
            </form>

            <!-- Divider -->
            <div class="my-8 flex items-center">
                <div class="flex-grow h-px bg-white bg-opacity-30"></div>
                <span class="mx-4 text-white text-opacity-60 text-sm">or</span>
                <div class="flex-grow h-px bg-white bg-opacity-30"></div>
            </div>

            <!-- Back to Login -->
            <div class="text-center">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center px-6 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-xl transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Login
                </a>
            </div>

            <!-- Session Info -->
            <div class="mt-6 glass-effect rounded-xl p-4">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-info-circle text-blue-300"></i>
                    <div class="text-sm text-white text-opacity-80">
                        <p class="font-semibold text-white">Security Notice</p>
                        <p>After updating your password, you'll be automatically signed in to your account.</p>
                    </div>
                </div>
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
        // Password visibility toggles
        document.getElementById('togglePassword').addEventListener('click', function() {
            togglePasswordVisibility('password', this);
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            togglePasswordVisibility('password_confirmation', this);
        });

        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            checkPasswordStrength(password);
            checkPasswordMatch();
            updateSubmitButton();
        });

        document.getElementById('password_confirmation').addEventListener('input', function() {
            checkPasswordMatch();
            updateSubmitButton();
        });

        function checkPasswordStrength(password) {
            const requirements = {
                length: password.length >= 8,
                lowercase: /[a-z]/.test(password),
                uppercase: /[A-Z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[^A-Za-z0-9]/.test(password)
            };

            // Update requirement indicators
            Object.keys(requirements).forEach(req => {
                const element = document.getElementById(`req-${req}`);
                const icon = element.querySelector('i');
                
                if (requirements[req]) {
                    element.classList.remove('text-red-300');
                    element.classList.add('text-green-300');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-check');
                } else {
                    element.classList.remove('text-green-300');
                    element.classList.add('text-red-300');
                    icon.classList.remove('fa-check');
                    icon.classList.add('fa-times');
                }
            });

            // Calculate overall strength
            const score = Object.values(requirements).reduce((acc, curr) => acc + (curr ? 1 : 0), 0);
            updatePasswordStrength(score);
            
            return score === 5; // Return true if all requirements are met
        }

        function updatePasswordStrength(score) {
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            const colors = ['#ef4444', '#f59e0b', '#eab308', '#22c55e', '#16a34a'];
            const labels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
            
            const width = (score / 5) * 100;
            strengthBar.style.width = width + '%';
            strengthBar.style.backgroundColor = colors[score - 1] || colors[0];
            strengthText.textContent = `Password strength: ${labels[score - 1] || labels[0]}`;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchIndicator = document.getElementById('passwordMatch');
            const matchSuccess = document.getElementById('passwordMatchSuccess');
            
            if (confirmPassword && password !== confirmPassword) {
                matchIndicator.classList.remove('hidden');
                matchSuccess.classList.add('hidden');
                return false;
            } else if (confirmPassword && password === confirmPassword) {
                matchIndicator.classList.add('hidden');
                matchSuccess.classList.remove('hidden');
                return true;
            } else {
                matchIndicator.classList.add('hidden');
                matchSuccess.classList.add('hidden');
                return false;
            }
        }

        function updateSubmitButton() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const submitBtn = document.getElementById('submitBtn');
            
            const isPasswordStrong = checkPasswordStrength(password);
            const passwordsMatch = checkPasswordMatch();
            
            if (isPasswordStrong && passwordsMatch && password && confirmPassword) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }

        // Form submission
        document.getElementById('resetForm').addEventListener('submit', function() {
            const button = document.getElementById('submitBtn');
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating Password...';
            button.disabled = true;
        });

        // Initialize password strength on page load
        const initialPassword = document.getElementById('password').value;
        if (initialPassword) {
            checkPasswordStrength(initialPassword);
        }
    </script>
</body>
</html>