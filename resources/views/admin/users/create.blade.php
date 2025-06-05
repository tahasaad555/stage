@extends('layouts.admin')

@section('title', 'Create New User')

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-green-600 via-blue-600 to-purple-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <a href="{{ route('admin.users.index') }}" class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4 hover:bg-opacity-30 transition-all">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">👤 Create New User</h1>
                        <p class="text-xl opacity-90">Add a new member to the AgriTerre platform</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-user-plus mr-2"></i>
                        <span>New Account Creation</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <span>Secure Registration</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-envelope mr-2"></i>
                        <span>Email Verification</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-plus text-6xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- User Creation Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-8">
                @csrf
                
                <!-- Personal Information Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-user text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Personal Information</h3>
                            <p class="text-gray-600">Basic user details and identification</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-blue-500"></i>First Name *
                            </label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                   placeholder="Enter first name..."
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('first_name') border-red-300 ring-red-100 @enderror">
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-blue-500"></i>Last Name *
                            </label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                   placeholder="Enter last name..."
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('last_name') border-red-300 ring-red-100 @enderror">
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-green-500"></i>Email Address *
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   placeholder="user@example.com"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('email') border-red-300 ring-red-100 @enderror">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone mr-2 text-purple-500"></i>Phone Number
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                   placeholder="+212 6 12 34 56 78"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('phone') border-red-300 ring-red-100 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar mr-2 text-indigo-500"></i>Date of Birth
                            </label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('date_of_birth') border-red-300 ring-red-100 @enderror">
                            @error('date_of_birth')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-tag mr-2 text-yellow-500"></i>User Role *
                            </label>
                            <select name="role" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all @error('role') border-red-300 ring-red-100 @enderror">
                                <option value="">Select user role...</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>👑 Administrator</option>
                                <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>👤 Client (Land Buyer)</option>
                                <option value="fournisseur" {{ old('role') === 'fournisseur' ? 'selected' : '' }}>🏢 Supplier (Land Seller)</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Security Settings Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-lock text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Security Settings</h3>
                            <p class="text-gray-600">Password and account security configuration</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-key mr-2 text-red-500"></i>Password *
                            </label>
                            <div class="relative">
                                <input type="password" name="password" required id="password"
                                       placeholder="Enter secure password..."
                                       class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all @error('password') border-red-300 ring-red-100 @enderror">
                                <button type="button" onclick="togglePassword('password')" 
                                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="password-eye"></i>
                                </button>
                            </div>
                            <div class="mt-2 text-xs text-gray-600">
                                <ul class="space-y-1">
                                    <li>• At least 8 characters long</li>
                                    <li>• Include uppercase and lowercase letters</li>
                                    <li>• Include at least one number</li>
                                </ul>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-key mr-2 text-red-500"></i>Confirm Password *
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" required id="password_confirmation"
                                       placeholder="Confirm password..."
                                       class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
                                <button type="button" onclick="togglePassword('password_confirmation')" 
                                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="password_confirmation-eye"></i>
                                </button>
                            </div>
                            <div class="mt-2">
                                <div id="password-match-indicator" class="text-xs text-gray-500">
                                    Passwords will be validated when you type
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Settings Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-cog text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Account Settings</h3>
                            <p class="text-gray-600">Initial account configuration and preferences</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Account Status -->
                        <div>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" checked
                                       class="w-5 h-5 text-green-600 border-2 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">
                                        <i class="fas fa-toggle-on mr-2 text-green-500"></i>Active Account
                                    </span>
                                    <p class="text-xs text-gray-500">User can log in and use the platform</p>
                                </div>
                            </label>
                        </div>

                        <!-- Email Verification -->
                        <div>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="email_verified" value="1"
                                       class="w-5 h-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">
                                        <i class="fas fa-envelope-check mr-2 text-blue-500"></i>Pre-verify Email
                                    </span>
                                    <p class="text-xs text-gray-500">Mark email as verified (skip verification step)</p>
                                </div>
                            </label>
                        </div>

                        <!-- Send Welcome Email -->
                        <div>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="send_welcome_email" value="1" checked
                                       class="w-5 h-5 text-purple-600 border-2 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">
                                        <i class="fas fa-paper-plane mr-2 text-purple-500"></i>Send Welcome Email
                                    </span>
                                    <p class="text-xs text-gray-500">Send account creation notification to user</p>
                                </div>
                            </label>
                        </div>

                        <!-- Generate Random Password -->
                        <div>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" id="generate_password"
                                       class="w-5 h-5 text-yellow-600 border-2 border-gray-300 rounded focus:ring-yellow-500 focus:ring-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">
                                        <i class="fas fa-random mr-2 text-yellow-500"></i>Generate Random Password
                                    </span>
                                    <p class="text-xs text-gray-500">Auto-generate a secure password for the user</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.users.index') }}" 
                       class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl hover:bg-gray-300 transition-all flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Cancel & Go Back
                    </a>
                    
                    <div class="flex items-center space-x-4">
                        <button type="button" onclick="resetForm()" 
                                class="bg-yellow-500 text-white px-6 py-3 rounded-xl hover:bg-yellow-600 transition-all flex items-center">
                            <i class="fas fa-undo mr-2"></i>Reset Form
                        </button>
                        <button type="submit" 
                                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-3 rounded-xl hover:from-green-600 hover:to-green-700 transition-all flex items-center shadow-lg">
                            <i class="fas fa-user-plus mr-2"></i>Create User Account
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar - Instructions & Help -->
        <div class="space-y-6">
            <!-- User Creation Tips -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-lightbulb text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Creation Tips</h4>
                </div>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Use real email addresses for proper notifications</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Strong passwords protect user accounts</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Phone numbers help with account recovery</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Choose appropriate roles for platform access</p>
                    </div>
                </div>
            </div>

            <!-- Role Descriptions -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">User Roles</h4>
                </div>
                <div class="space-y-4">
                    <div class="border-l-4 border-red-400 pl-4">
                        <h5 class="font-semibold text-red-700">👑 Administrator</h5>
                        <p class="text-xs text-gray-600">Full system access, user management, and platform configuration</p>
                    </div>
                    <div class="border-l-4 border-blue-400 pl-4">
                        <h5 class="font-semibold text-blue-700">👤 Client</h5>
                        <p class="text-xs text-gray-600">Can browse and purchase agricultural land properties</p>
                    </div>
                    <div class="border-l-4 border-green-400 pl-4">
                        <h5 class="font-semibold text-green-700">🏢 Supplier</h5>
                        <p class="text-xs text-gray-600">Can list and sell agricultural land properties</p>
                    </div>
                </div>
            </div>

            <!-- Security Guidelines -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-orange-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-shield-alt text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Security Guidelines</h4>
                </div>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-key text-orange-500 mt-1"></i>
                        <p>Passwords should be at least 8 characters with mixed case, numbers, and symbols</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-envelope text-blue-500 mt-1"></i>
                        <p>Email verification helps prevent unauthorized access</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-user-shield text-green-500 mt-1"></i>
                        <p>Only activate accounts for verified users</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Platform Stats</h4>
                </div>
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-blue-600">{{ \App\Models\User::count() }}</div>
                        <div class="text-xs text-gray-600">Total Users</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-green-600">{{ \App\Models\User::where('is_active', true)->count() }}</div>
                        <div class="text-xs text-gray-600">Active Users</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-purple-600">{{ \App\Models\User::where('role', 'client')->count() }}</div>
                        <div class="text-xs text-gray-600">Clients</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-yellow-600">{{ \App\Models\User::where('role', 'fournisseur')->count() }}</div>
                        <div class="text-xs text-gray-600">Suppliers</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Password toggle functionality
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');
    
    if (field.type === 'password') {
        field.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}

// Password strength indicator
function checkPasswordStrength(password) {
    let score = 0;
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    
    return score;
}

// Password match indicator
function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    const indicator = document.getElementById('password-match-indicator');
    
    if (confirmation === '') {
        indicator.textContent = 'Passwords will be validated when you type';
        indicator.className = 'text-xs text-gray-500';
        return;
    }
    
    if (password === confirmation) {
        indicator.textContent = '✓ Passwords match';
        indicator.className = 'text-xs text-green-600';
    } else {
        indicator.textContent = '✗ Passwords do not match';
        indicator.className = 'text-xs text-red-600';
    }
}

// Generate random password
function generateRandomPassword() {
    const length = 12;
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    let password = "";
    for (let i = 0; i < length; i++) {
        password += charset.charAt(Math.floor(Math.random() * charset.length));
    }
    return password;
}

// Reset form
function resetForm() {
    if (confirm('Are you sure you want to reset all form data?')) {
        document.querySelector('form').reset();
        document.getElementById('password-match-indicator').textContent = 'Passwords will be validated when you type';
        document.getElementById('password-match-indicator').className = 'text-xs text-gray-500';
    }
}

// Form validation and enhancement
document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.getElementById('password');
    const confirmationField = document.getElementById('password_confirmation');
    const generatePasswordCheckbox = document.getElementById('generate_password');
    const form = document.querySelector('form');

    // Password match checking
    confirmationField.addEventListener('input', checkPasswordMatch);
    passwordField.addEventListener('input', checkPasswordMatch);

    // Generate password functionality
    generatePasswordCheckbox.addEventListener('change', function() {
        if (this.checked) {
            const newPassword = generateRandomPassword();
            passwordField.value = newPassword;
            confirmationField.value = newPassword;
            checkPasswordMatch();
            
            // Show generated password in alert
            alert(`Generated password: ${newPassword}\n\nPlease share this password securely with the user.`);
        }
    });

    // Form submission enhancement
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating User...';
        submitBtn.disabled = true;
        
        // Re-enable if form submission fails
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }, 10000);
    });

    // Real-time form validation
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('border-red-300', 'ring-red-100');
                this.classList.remove('border-green-300', 'ring-green-100');
            } else {
                this.classList.add('border-green-300', 'ring-green-100');
                this.classList.remove('border-red-300', 'ring-red-100');
            }
        });
    });

    // Email validation
    const emailField = form.querySelector('input[type="email"]');
    emailField.addEventListener('blur', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailRegex.test(this.value)) {
            this.classList.add('border-green-300', 'ring-green-100');
            this.classList.remove('border-red-300', 'ring-red-100');
        } else if (this.value !== '') {
            this.classList.add('border-red-300', 'ring-red-100');
            this.classList.remove('border-green-300', 'ring-green-100');
        }
    });

    // Phone number formatting (basic)
    const phoneField = form.querySelector('input[type="tel"]');
    phoneField.addEventListener('input', function() {
        // Remove all non-digit characters except + and spaces
        this.value = this.value.replace(/[^\d\+\s]/g, '');
    });

    // Animate form sections on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    // Observe all form cards
    const formCards = document.querySelectorAll('.glass-effect');
    formCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });

    // Auto-focus first field
    const firstField = form.querySelector('input[name="first_name"]');
    if (firstField) {
        setTimeout(() => firstField.focus(), 500);
    }
});

// Show success message function
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed top-4 right-4 z-50 bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl shadow-2xl transform transition-all duration-500';
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="flex-1">
                <div class="font-semibold">Success!</div>
                <div class="text-sm opacity-90">${message}</div>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 w-6 h-6 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-all">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.style.transform = 'translateX(100%)';
            setTimeout(() => alertDiv.remove(), 500);
        }
    }, 5000);
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save (submit form)
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.querySelector('form').dispatchEvent(new Event('submit'));
    }
    
    // Escape to go back
    if (e.key === 'Escape') {
        if (confirm('Are you sure you want to leave? Unsaved changes will be lost.')) {
            window.location.href = '{{ route("admin.users.index") }}';
        }
    }
});
</script>
@endpush
@endsection