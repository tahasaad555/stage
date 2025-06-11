<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AgriTerre Platform</title>
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
        
        .role-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .role-card.selected {
            background: rgba(16, 185, 129, 0.2);
            border: 2px solid #10b981;
            transform: scale(1.02);
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

    <div class="relative w-full max-w-3xl">
        <!-- Main Registration Card -->
        <div class="glass-effect rounded-3xl shadow-2xl p-8 backdrop-blur-xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <i class="fas fa-seedling text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Join AgriTerre</h1>
                <p class="text-white text-opacity-80">Create your account and start your agricultural journey</p>
            </div>

            <!-- Error Messages -->
            <div id="errorMessages" class="mb-6 bg-red-500 bg-opacity-20 border border-red-400 border-opacity-30 text-red-100 px-4 py-3 rounded-xl hidden">
                <h4 class="font-semibold mb-2">Please fix the following errors:</h4>
                <ul class="list-disc list-inside space-y-1" id="errorList">
                </ul>
            </div>

            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-6" id="registerForm">
                @csrf
                
                <!-- Step 1: Choose Role -->
                <div class="step-section" id="step1">
                    <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-green-400 rounded-full flex items-center justify-center text-black font-bold mr-3">1</span>
                        Choose Your Role
                    </h3>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Client Card -->
                        <div class="role-card glass-effect rounded-xl p-6 text-center" data-role="client">
                            <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user text-white text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-white mb-2">Client</h4>
                            <p class="text-white text-opacity-70 text-sm mb-4">Browse and purchase agricultural land properties</p>
                            <ul class="text-left space-y-2 text-sm text-white text-opacity-60">
                                <li><i class="fas fa-check text-green-400 mr-2"></i>Search available properties</li>
                                <li><i class="fas fa-check text-green-400 mr-2"></i>Contact suppliers directly</li>
                                <li><i class="fas fa-check text-green-400 mr-2"></i>Save favorite listings</li>
                                <li><i class="fas fa-check text-green-400 mr-2"></i>Get market insights</li>
                            </ul>
                        </div>
                        
                        <!-- Supplier Card -->
                        <div class="role-card glass-effect rounded-xl p-6 text-center" data-role="supplier">
                            <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-store text-white text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-white mb-2">Supplier</h4>
                            <p class="text-white text-opacity-70 text-sm mb-4">List and sell your agricultural land properties</p>
                            <ul class="text-left space-y-2 text-sm text-white text-opacity-60">
                                <li><i class="fas fa-check text-green-400 mr-2"></i>List multiple properties</li>
                                <li><i class="fas fa-check text-green-400 mr-2"></i>Manage inquiries</li>
                                <li><i class="fas fa-check text-green-400 mr-2"></i>Track performance</li>
                                <li><i class="fas fa-check text-green-400 mr-2"></i>Premium visibility</li>
                            </ul>
                        </div>
                    </div>
                    
                    <input type="hidden" id="role" name="role" value="">
                </div>

                <!-- Step 2: Personal Information -->
                <div class="step-section hidden" id="step2">
                    <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-green-400 rounded-full flex items-center justify-center text-black font-bold mr-3">2</span>
                        Personal Information
                    </h3>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                                <i class="fas fa-user mr-2"></i>First Name
                            </label>
                            <input type="text" id="first_name" name="first_name" required 
                                   value="{{ old('first_name') }}"
                                   class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all"
                                   placeholder="Enter your first name">
                        </div>
                        
                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                                <i class="fas fa-user mr-2"></i>Last Name
                            </label>
                            <input type="text" id="last_name" name="last_name" required 
                                   value="{{ old('last_name') }}"
                                   class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all"
                                   placeholder="Enter your last name">
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                            <i class="fas fa-envelope mr-2"></i>Email Address
                        </label>
                        <input type="email" id="email" name="email" required 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all"
                               placeholder="Enter your email address">
                    </div>
                    
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                            <i class="fas fa-phone mr-2"></i>Phone Number <span class="text-white text-opacity-50">(Optional)</span>
                        </label>
                        <input type="tel" id="phone" name="phone" 
                               value="{{ old('phone') }}"
                               class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all"
                               placeholder="Enter your phone number">
                    </div>
                </div>

                <!-- Step 3: Business Information (Suppliers Only) -->
                <div class="step-section hidden" id="step3">
                    <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-green-400 rounded-full flex items-center justify-center text-black font-bold mr-3">3</span>
                        <span id="step3Title">Business Information</span>
                    </h3>
                    
                    <!-- Business fields for suppliers -->
                    <div id="businessFields" class="space-y-4">
                        <!-- Company Name -->
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                                <i class="fas fa-building mr-2"></i>Company Name
                            </label>
                            <input type="text" id="company_name" name="company_name"
                                   value="{{ old('company_name') }}"
                                   class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all"
                                   placeholder="Enter your company name">
                        </div>
                        
                        <!-- Business Registration Number -->
                        <div>
                            <label for="business_registration" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                                <i class="fas fa-certificate mr-2"></i>Business Registration Number
                            </label>
                            <input type="text" id="business_registration" name="business_registration"
                                   value="{{ old('business_registration') }}"
                                   class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all"
                                   placeholder="Enter your business registration number">
                        </div>
                        
                        <!-- Business Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                                <i class="fas fa-map-marker-alt mr-2"></i>Business Address
                            </label>
                            <textarea id="address" name="address" rows="3"
                                      class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all resize-none"
                                      placeholder="Enter your complete business address">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Security -->
                <div class="step-section hidden" id="step4">
                    <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-green-400 rounded-full flex items-center justify-center text-black font-bold mr-3">4</span>
                        Security Setup
                    </h3>
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                            <i class="fas fa-lock mr-2"></i>Password
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                   class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all pr-12"
                                   placeholder="Create a strong password">
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
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-white text-opacity-90 mb-2">
                            <i class="fas fa-lock mr-2"></i>Confirm Password
                        </label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all pr-12"
                                   placeholder="Confirm your password">
                            <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white text-opacity-60 hover:text-opacity-100">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="passwordMatch" class="mt-1 text-xs hidden">
                            <span class="text-red-300"><i class="fas fa-times mr-1"></i>Passwords don't match</span>
                        </div>
                    </div>
                    
                    <!-- Terms and Conditions -->
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" id="terms" name="terms" required 
                               class="w-4 h-4 text-green-400 bg-white bg-opacity-20 border border-white border-opacity-30 rounded focus:ring-green-400 focus:ring-2 mt-1">
                        <label for="terms" class="text-sm text-white text-opacity-80">
                            I agree to the <a href="#" class="text-green-300 hover:text-green-200 underline">Terms of Service</a> 
                            and <a href="#" class="text-green-300 hover:text-green-200 underline">Privacy Policy</a>
                        </label>
                    </div>
                    
                    <!-- Newsletter Subscription -->
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" id="newsletter" name="newsletter" 
                               class="w-4 h-4 text-green-400 bg-white bg-opacity-20 border border-white border-opacity-30 rounded focus:ring-green-400 focus:ring-2 mt-1">
                        <label for="newsletter" class="text-sm text-white text-opacity-80">
                            Subscribe to our newsletter for market updates and agricultural insights
                        </label>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between items-center pt-6">
                    <button type="button" id="prevBtn" class="px-6 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-xl transition-all duration-200 hidden">
                        <i class="fas fa-arrow-left mr-2"></i>Previous
                    </button>
                    
                    <div class="flex space-x-2" id="stepIndicators">
                        <div class="w-3 h-3 rounded-full bg-green-400" id="step-indicator-1"></div>
                        <div class="w-3 h-3 rounded-full bg-white bg-opacity-30" id="step-indicator-2"></div>
                        <div class="w-3 h-3 rounded-full bg-white bg-opacity-30" id="step-indicator-3"></div>
                        <div class="w-3 h-3 rounded-full bg-white bg-opacity-30" id="step-indicator-4"></div>
                    </div>
                    
                    <button type="button" id="nextBtn" class="px-6 py-2 bg-gradient-to-r from-green-400 to-blue-500 text-white font-semibold rounded-xl hover:from-green-500 hover:to-blue-600 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        Next<i class="fas fa-arrow-right ml-2"></i>
                    </button>
                    
                    <button type="submit" id="submitBtn" class="px-6 py-2 bg-gradient-to-r from-green-400 to-blue-500 text-white font-semibold rounded-xl hover:from-green-500 hover:to-blue-600 transform hover:scale-105 transition-all duration-200 shadow-lg hidden">
                        <i class="fas fa-user-plus mr-2"></i>Create Account
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="my-8 flex items-center">
                <div class="flex-grow h-px bg-white bg-opacity-30"></div>
                <span class="mx-4 text-white text-opacity-60 text-sm">Already have an account?</span>
                <div class="flex-grow h-px bg-white bg-opacity-30"></div>
            </div>

            <!-- Login Link -->
            <div class="text-center">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center px-6 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-xl transition-all duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In Instead
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
        let currentStep = 1;
        let totalSteps = 3; // Will be updated based on role
        let selectedRole = '';

        // Role selection
        document.querySelectorAll('.role-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remove selected class from all cards
                document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
                
                // Add selected class to clicked card
                this.classList.add('selected');
                
                // Update selected role and hidden input
                selectedRole = this.dataset.role;
                document.getElementById('role').value = selectedRole;
                
                // Update step configuration based on role
                updateStepConfiguration();
                
                // Enable next button
                document.getElementById('nextBtn').disabled = false;
            });
        });

        function updateStepConfiguration() {
            if (selectedRole === 'supplier') {
                totalSteps = 4;
                // Make business fields required
                document.getElementById('company_name').required = true;
                document.getElementById('business_registration').required = true;
                document.getElementById('address').required = true;
                
                // Update step 3 title
                document.getElementById('step3Title').textContent = 'Business Information';
            } else {
                totalSteps = 3;
                // Remove business fields requirement
                document.getElementById('company_name').required = false;
                document.getElementById('business_registration').required = false;
                document.getElementById('address').required = false;
                
                // Update step 3 title for clients (skip business info)
                document.getElementById('step3Title').textContent = 'Security Setup';
            }
            
            // Update step indicators
            updateStepIndicators();
        }

        function updateStepIndicators() {
            const indicatorsContainer = document.getElementById('stepIndicators');
            indicatorsContainer.innerHTML = '';
            
            for (let i = 1; i <= totalSteps; i++) {
                const indicator = document.createElement('div');
                indicator.className = i === 1 ? 'w-3 h-3 rounded-full bg-green-400' : 'w-3 h-3 rounded-full bg-white bg-opacity-30';
                indicator.id = `step-indicator-${i}`;
                indicatorsContainer.appendChild(indicator);
            }
        }

        // Step navigation
        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.step-section').forEach(s => s.classList.add('hidden'));
            
            // Determine which step to show based on role
            let stepToShow = step;
            if (selectedRole === 'client' && step >= 3) {
                // For clients, skip business info step
                stepToShow = step === 3 ? 4 : step;
            }
            
            // Show current step
            document.getElementById(`step${stepToShow}`).classList.remove('hidden');
            
            // Update indicators
            for (let i = 1; i <= totalSteps; i++) {
                const indicator = document.getElementById(`step-indicator-${i}`);
                if (indicator) {
                    if (i <= step) {
                        indicator.classList.remove('bg-white', 'bg-opacity-30');
                        indicator.classList.add('bg-green-400');
                    } else {
                        indicator.classList.remove('bg-green-400');
                        indicator.classList.add('bg-white', 'bg-opacity-30');
                    }
                }
            }
            
            // Update buttons
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            
            if (step === 1) {
                prevBtn.classList.add('hidden');
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            } else if (step === totalSteps) {
                prevBtn.classList.remove('hidden');
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                prevBtn.classList.remove('hidden');
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
        }

        // Next button
        document.getElementById('nextBtn').addEventListener('click', function() {
            if (validateCurrentStep()) {
                currentStep++;
                showStep(currentStep);
            }
        });

        // Previous button
        document.getElementById('prevBtn').addEventListener('click', function() {
            currentStep--;
            showStep(currentStep);
        });

        // Validation for each step
        function validateCurrentStep() {
            const errors = [];
            
            if (currentStep === 1) {
                if (!selectedRole) {
                    errors.push('Please select your role before continuing.');
                }
            } else if (currentStep === 2) {
                const firstName = document.getElementById('first_name').value.trim();
                const lastName = document.getElementById('last_name').value.trim();
                const email = document.getElementById('email').value.trim();
                
                if (!firstName) errors.push('First name is required.');
                if (!lastName) errors.push('Last name is required.');
                if (!email) errors.push('Email address is required.');
                else if (!isValidEmail(email)) errors.push('Please enter a valid email address.');
                
            } else if (currentStep === 3 && selectedRole === 'supplier') {
                const companyName = document.getElementById('company_name').value.trim();
                const businessReg = document.getElementById('business_registration').value.trim();
                const businessAddress = document.getElementById('address').value.trim();
                
                if (!companyName) errors.push('Company name is required for suppliers.');
                if (!businessReg) errors.push('Business registration number is required for suppliers.');
                if (!businessAddress) errors.push('Business address is required for suppliers.');
            }
            
            if (errors.length > 0) {
                showErrors(errors);
                return false;
            } else {
                hideErrors();
                return true;
            }
        }

        function showErrors(errors) {
            const errorContainer = document.getElementById('errorMessages');
            const errorList = document.getElementById('errorList');
            
            errorList.innerHTML = '';
            errors.forEach(error => {
                const li = document.createElement('li');
                li.textContent = error;
                li.className = 'text-sm';
                errorList.appendChild(li);
            });
            
            errorContainer.classList.remove('hidden');
            
            // Scroll to top to show errors
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function hideErrors() {
            document.getElementById('errorMessages').classList.add('hidden');
        }

        // Email validation
        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

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
            const strength = checkPasswordStrength(password);
            updatePasswordStrength(strength);
            checkPasswordMatch();
        });

        document.getElementById('password_confirmation').addEventListener('input', function() {
            checkPasswordMatch();
        });

        function checkPasswordStrength(password) {
            let score = 0;
            
            if (password.length >= 8) score += 1;
            if (/[a-z]/.test(password)) score += 1;
            if (/[A-Z]/.test(password)) score += 1;
            if (/[0-9]/.test(password)) score += 1;
            if (/[^A-Za-z0-9]/.test(password)) score += 1;
            
            return { score };
        }

        function updatePasswordStrength(strength) {
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            const colors = ['#ef4444', '#f59e0b', '#eab308', '#22c55e', '#16a34a'];
            const labels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
            
            const width = (strength.score / 5) * 100;
            strengthBar.style.width = width + '%';
            strengthBar.style.backgroundColor = colors[strength.score - 1] || colors[0];
            strengthText.textContent = `Password strength: ${labels[strength.score - 1] || labels[0]}`;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchIndicator = document.getElementById('passwordMatch');
            
            if (confirmPassword && password !== confirmPassword) {
                matchIndicator.classList.remove('hidden');
            } else {
                matchIndicator.classList.add('hidden');
            }
        }

        // Form submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            // Final validation before submission
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const terms = document.getElementById('terms').checked;
            const errors = [];

            if (password !== confirmPassword) {
                errors.push('Passwords do not match.');
            }
            
            if (password.length < 8) {
                errors.push('Password must be at least 8 characters long.');
            }
            
            if (!terms) {
                errors.push('You must agree to the Terms of Service and Privacy Policy.');
            }

            // Validate supplier fields if supplier is selected
            if (selectedRole === 'supplier') {
                const companyName = document.getElementById('company_name').value.trim();
                const businessReg = document.getElementById('business_registration').value.trim();
                const businessAddress = document.getElementById('address').value.trim();
                
                if (!companyName) errors.push('Company name is required for suppliers.');
                if (!businessReg) errors.push('Business registration number is required for suppliers.');
                if (!businessAddress) errors.push('Business address is required for suppliers.');
            }

            if (errors.length > 0) {
                e.preventDefault();
                showErrors(errors);
                return false;
            }

            // Show loading state
            const button = document.getElementById('submitBtn');
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...';
            button.disabled = true;
        });

        // Initialize
        showStep(1);
    </script>
</body>
</html>