@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-orange-600 via-red-600 to-pink-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <a href="{{ route('admin.users.show', $user) }}" class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4 hover:bg-opacity-30 transition-all">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-r from-{{ $user->role === 'admin' ? 'red' : ($user->role === 'client' ? 'blue' : 'green') }}-400 to-{{ $user->role === 'admin' ? 'pink' : ($user->role === 'client' ? 'purple' : 'yellow') }}-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">
                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold mb-2">✏️ Edit User</h1>
                            <p class="text-xl opacity-90">Modify {{ $user->full_name }}'s information</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-user-edit mr-2"></i>
                        <span>User Modification</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <span>Secure Update</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <i class="fas fa-history mr-2"></i>
                        <span>Change Tracking</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-edit text-6xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- User Edit Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Personal Information Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-user text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Personal Information</h3>
                            <p class="text-gray-600">Update basic user details and identification</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-blue-500"></i>First Name *
                            </label>
                            <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
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
                            <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
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
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
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
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
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
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
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
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>👑 Administrator</option>
                                <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>👤 Client (Land Buyer)</option>
                                <option value="fournisseur" {{ old('role', $user->role) === 'fournisseur' ? 'selected' : '' }}>🏢 Supplier (Land Seller)</option>
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

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            <p class="text-blue-700 text-sm">Leave password fields empty to keep the current password unchanged.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- New Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-key mr-2 text-red-500"></i>New Password
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                       placeholder="Enter new password (optional)..."
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
                                <i class="fas fa-key mr-2 text-red-500"></i>Confirm New Password
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       placeholder="Confirm new password..."
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
                            <p class="text-gray-600">Account status and preferences</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Account Status -->
                        <div>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
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
                                <input type="checkbox" name="email_verified" value="1" {{ $user->email_verified_at ? 'checked' : '' }}
                                       class="w-5 h-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">
                                        <i class="fas fa-envelope-check mr-2 text-blue-500"></i>Email Verified
                                    </span>
                                    <p class="text-xs text-gray-500">Mark email as verified or unverified</p>
                                </div>
                            </label>
                        </div>

                        <!-- Send Update Notification -->
                        <div>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="send_update_notification" value="1" checked
                                       class="w-5 h-5 text-purple-600 border-2 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">
                                        <i class="fas fa-bell mr-2 text-purple-500"></i>Send Update Notification
                                    </span>
                                    <p class="text-xs text-gray-500">Notify user about profile changes via email</p>
                                </div>
                            </label>
                        </div>

                        <!-- Force Password Change -->
                        <div>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="force_password_change" value="1"
                                       class="w-5 h-5 text-yellow-600 border-2 border-gray-300 rounded focus:ring-yellow-500 focus:ring-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">
                                        <i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>Force Password Change
                                    </span>
                                    <p class="text-xs text-gray-500">User must change password on next login</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.users.show', $user) }}" 
                       class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl hover:bg-gray-300 transition-all flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Cancel & Go Back
                    </a>
                    
                    <div class="flex items-center space-x-4">
                        <button type="button" onclick="resetForm()" 
                                class="bg-yellow-500 text-white px-6 py-3 rounded-xl hover:bg-yellow-600 transition-all flex items-center">
                            <i class="fas fa-undo mr-2"></i>Reset Changes
                        </button>
                        <button type="submit" 
                                class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-8 py-3 rounded-xl hover:from-orange-600 hover:to-red-700 transition-all flex items-center shadow-lg">
                            <i class="fas fa-save mr-2"></i>Update User
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar - Instructions & User Info -->
        <div class="space-y-6">
            <!-- Current User Info -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Current User Info</h4>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">User ID:</span>
                        <span class="font-semibold">{{ $user->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Current Role:</span>
                        <span class="font-semibold">{{ ucfirst($user->role) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-semibold {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email Verified:</span>
                        <span class="font-semibold {{ $user->email_verified_at ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $user->email_verified_at ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Login:</span>
                        <span class="font-semibold">
                            {{ $user->last_login_at ? $user->last_login_at->format('M d, Y') : 'Never' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Member Since:</span>
                        <span class="font-semibold">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Edit Guidelines -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-lightbulb text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Edit Guidelines</h4>
                </div>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Double-check email addresses to prevent account lockouts</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Role changes affect user permissions immediately</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Leave password empty to keep current password</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p>Account deactivation prevents user login</p>
                    </div>
                </div>
            </div>

            <!-- Change History -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Recent Changes</h4>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="border-l-4 border-blue-400 pl-3">
                        <div class="font-semibold text-blue-700">Profile Updated</div>
                        <div class="text-gray-600">{{ $user->updated_at->diffForHumans() }}</div>
                    </div>
                    @if($user->email_verified_at)
                    <div class="border-l-4 border-green-400 pl-3">
                        <div class="font-semibold text-green-700">Email Verified</div>
                        <div class="text-gray-600">{{ $user->email_verified_at->diffForHumans() }}</div>
                    </div>
                    @endif
                    <div class="border-l-4 border-gray-400 pl-3">
                        <div class="font-semibold text-gray-700">Account Created</div>
                        <div class="text-gray-600">{{ $user->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-orange-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-shield-alt text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Security Notice</h4>
                </div>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-exclamation-triangle text-orange-500 mt-1"></i>
                        <p>All changes are logged for security purposes</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-user-shield text-green-500 mt-1"></i>
                        <p>User will be notified of significant changes</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-lock text-red-500 mt-1"></i>
                        <p>Password changes require confirmation</p>
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
    
    if (password === confirmation && password !== '') {
        indicator.textContent = '✓ Passwords match';
        indicator.className = 'text-xs text-green-600';
    } else if (password !== confirmation) {
        indicator.textContent = '✗ Passwords do not match';
        indicator.className = 'text-xs text-red-600';
    }
}

// Reset form
function resetForm() {
    if (confirm('Are you sure you want to reset all changes? This will restore the original values.')) {
        location.reload();
    }
}

// Form validation and enhancement
document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.getElementById('password');
    const confirmationField = document.getElementById('password_confirmation');
    const form = document.querySelector('form');

    // Password match checking
    if (confirmationField && passwordField) {
        confirmationField.addEventListener('input', checkPasswordMatch);
        passwordField.addEventListener('input', checkPasswordMatch);
    }

    // Form submission enhancement
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating User...';
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
    if (emailField) {
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
    }

    // Phone number formatting (basic)
    const phoneField = form.querySelector('input[type="tel"]');
    if (phoneField) {
        phoneField.addEventListener('input', function() {
            // Remove all non-digit characters except + and spaces
            this.value = this.value.replace(/[^\d\+\s]/g, '');
        });
    }

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

    // Show unsaved changes warning
    let formChanged = false;
    const formInputs = form.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('change', () => {
            formChanged = true;
        });
    });

    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    // Remove warning when form is submitted
    form.addEventListener('submit', function() {
        formChanged = false;
    });
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
        document.querySelector('form').dispatchEvent(new Event('submit', { bubbles: true }));
    }
    
    // Escape to go back
    if (e.key === 'Escape') {
        if (confirm('Are you sure you want to leave? Unsaved changes will be lost.')) {
            window.location.href = '{{ route("admin.users.show", $user) }}';
        }
    }
});

// Auto-save draft functionality (optional)
function saveDraft() {
    const formData = new FormData(document.querySelector('form'));
    const draftData = {};
    formData.forEach((value, key) => {
        draftData[key] = value;
    });
    
    localStorage.setItem('user_edit_draft_{{ $user->id }}', JSON.stringify(draftData));
    
    // Show draft saved indicator
    const indicator = document.createElement('div');
    indicator.className = 'fixed bottom-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg text-sm opacity-0 transition-opacity';
    indicator.textContent = 'Draft saved';
    document.body.appendChild(indicator);
    
    setTimeout(() => indicator.style.opacity = '1', 100);
    setTimeout(() => {
        indicator.style.opacity = '0';
        setTimeout(() => indicator.remove(), 300);
    }, 2000);
}

// Load draft on page load
document.addEventListener('DOMContentLoaded', function() {
    const draftData = localStorage.getItem('user_edit_draft_{{ $user->id }}');
    if (draftData) {
        try {
            const data = JSON.parse(draftData);
            const form = document.querySelector('form');
            
            Object.keys(data).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.checked = data[key] === '1';
                    } else {
                        field.value = data[key];
                    }
                }
            });
            
            // Show restore notification
            const restore = confirm('A draft of your changes was found. Would you like to restore it?');
            if (!restore) {
                localStorage.removeItem('user_edit_draft_{{ $user->id }}');
                location.reload();
            }
        } catch (e) {
            localStorage.removeItem('user_edit_draft_{{ $user->id }}');
        }
    }
});

// Auto-save every 30 seconds
setInterval(saveDraft, 30000);

// Clear draft on successful submission
document.querySelector('form').addEventListener('submit', function() {
    localStorage.removeItem('user_edit_draft_{{ $user->id }}');
});
</script>
@endpush
@endsection