@extends('layouts.supplier')

@section('title', 'Supplier Profile')

@push('styles')
<style>
.glass-effect {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.card-hover:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Animation for cards */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-hover {
    animation: slideUp 0.6s ease-out;
}

/* Form field enhancements */
.form-field {
    transition: all 0.3s ease;
}

.form-field:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Custom gradient backgrounds */
.bg-gradient-custom-1 {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.bg-gradient-custom-2 {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
}

.bg-gradient-custom-3 {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.bg-gradient-custom-4 {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.bg-gradient-custom-5 {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}

.bg-gradient-custom-6 {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}

/* Status indicators */
.status-active {
    background: linear-gradient(135deg, #10b981, #059669);
    animation: pulse-green 2s infinite;
}

@keyframes pulse-green {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
}
</style>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <a href="{{ route('supplier.dashboard') }}" class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-6 hover:bg-opacity-30 transition-all">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">🏢 Supplier Profile</h1>
                        <p class="text-xl opacity-90">Manage your business information and settings</p>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg opacity-75">Profile Settings</div>
                <div class="text-3xl font-bold">{{ now()->format('M d') }}</div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="glass-effect rounded-xl p-6 border-l-4 border-green-500 shadow-xl card-hover">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-custom-1 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Success!</h4>
                    <p class="text-gray-600">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Form -->
        <div class="lg:col-span-2">
            <div class="glass-effect rounded-2xl p-8 shadow-xl card-hover">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-custom-2 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">👤 Personal Information</h2>
                        <p class="text-gray-600">Update your account details and contact information</p>
                    </div>
                </div>

                <form action="{{ route('supplier.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-bold text-gray-700 mb-3">
                                <i class="fas fa-user mr-2 text-gray-400"></i>First Name *
                            </label>
                            <input type="text" id="first_name" name="first_name" 
                                   value="{{ old('first_name', $user->first_name) }}" required
                                   class="form-field w-full px-4 py-4 bg-white bg-opacity-50 border border-white border-opacity-30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 font-medium @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-bold text-gray-700 mb-3">
                                <i class="fas fa-user mr-2 text-gray-400"></i>Last Name *
                            </label>
                            <input type="text" id="last_name" name="last_name" 
                                   value="{{ old('last_name', $user->last_name) }}" required
                                   class="form-field w-full px-4 py-4 bg-white bg-opacity-50 border border-white border-opacity-30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 font-medium @error('last_name') border-red-500 @enderror">
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-3">
                            <i class="fas fa-envelope mr-2 text-gray-400"></i>Email Address *
                        </label>
                        <input type="email" id="email" name="email" 
                               value="{{ old('email', $user->email) }}" required
                               class="form-field w-full px-4 py-4 bg-white bg-opacity-50 border border-white border-opacity-30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 font-medium @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-bold text-gray-700 mb-3">
                            <i class="fas fa-phone mr-2 text-gray-400"></i>Phone Number
                        </label>
                        <input type="tel" id="phone" name="phone" 
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="+1 (555) 123-4567"
                               class="form-field w-full px-4 py-4 bg-white bg-opacity-50 border border-white border-opacity-30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 font-medium @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Optional: Add your phone number for better communication with clients
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6">
                        <button type="submit" class="bg-gradient-custom-2 text-white px-8 py-4 rounded-xl hover:shadow-lg transition-all font-bold text-lg">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Profile Summary -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-custom-2 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-id-card text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Profile Summary</h3>
                </div>
                
                <div class="text-center mb-6">
                    <div class="w-24 h-24 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-white font-bold text-3xl">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </span>
                    </div>
                    <h4 class="font-bold text-gray-800 text-xl">{{ $user->full_name }}</h4>
                    <p class="text-gray-600 font-medium">Agricultural Supplier</p>
                </div>
                
                <div class="space-y-4 text-sm">
                    <div class="flex items-center justify-between p-3 bg-white bg-opacity-50 rounded-lg">
                        <span class="text-gray-600 font-medium">Account Status:</span>
                        <span class="status-active text-white px-3 py-1 rounded-full text-xs font-bold">ACTIVE</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white bg-opacity-50 rounded-lg">
                        <span class="text-gray-600 font-medium">Member Since:</span>
                        <span class="text-gray-800 font-bold">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white bg-opacity-50 rounded-lg">
                        <span class="text-gray-600 font-medium">Role:</span>
                        <span class="text-gray-800 font-bold capitalize">{{ $user->role }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white bg-opacity-50 rounded-lg">
                        <span class="text-gray-600 font-medium">Profile Completion:</span>
                        <span class="text-green-600 font-bold">85%</span>
                    </div>
                </div>
            </div>

            <!-- Business Information -->
            @if($user->fournisseur)
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-custom-1 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-building text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Business Info</h3>
                </div>
                
                <div class="space-y-4 text-sm">
                    <div class="p-4 bg-white bg-opacity-50 rounded-lg">
                        <span class="text-gray-600 font-medium block mb-1">Company Name:</span>
                        <span class="text-gray-800 font-bold text-lg">{{ $user->fournisseur->company_name }}</span>
                    </div>
                    <div class="p-4 bg-white bg-opacity-50 rounded-lg">
                        <span class="text-gray-600 font-medium block mb-1">Registration Number:</span>
                        <span class="text-gray-800 font-mono font-bold">{{ $user->fournisseur->business_registration }}</span>
                    </div>
                    <div class="p-4 bg-white bg-opacity-50 rounded-lg">
                        <span class="text-gray-600 font-medium block mb-1">Business Address:</span>
                        <span class="text-gray-800 font-medium">{{ $user->fournisseur->address }}</span>
                    </div>
                </div>
                
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                        <span class="text-yellow-800 text-sm font-medium">
                            Contact admin to update business information
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Security Settings -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-custom-3 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-lock text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Security Settings</h3>
                </div>
                
                <div class="space-y-3">
                    <button class="w-full bg-gradient-custom-3 text-white py-3 rounded-xl hover:shadow-lg transition-all font-medium">
                        <i class="fas fa-key mr-2"></i>Change Password
                    </button>
                    <button class="w-full bg-gradient-custom-5 text-white py-3 rounded-xl hover:shadow-lg transition-all font-medium">
                        <i class="fas fa-shield-alt mr-2"></i>Two-Factor Authentication
                    </button>
                    <button class="w-full bg-gradient-custom-6 text-white py-3 rounded-xl hover:shadow-lg transition-all font-medium">
                        <i class="fas fa-history mr-2"></i>Login History
                    </button>
                </div>
            </div>

            <!-- Account Management -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-custom-6 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-cog text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Account Management</h3>
                </div>
                
                <div class="space-y-3">
                    <button class="w-full bg-gradient-custom-2 text-white py-3 rounded-xl hover:shadow-lg transition-all font-medium">
                        <i class="fas fa-download mr-2"></i>Export My Data
                    </button>
                    <button class="w-full bg-gradient-custom-6 text-white py-3 rounded-xl hover:shadow-lg transition-all font-medium">
                        <i class="fas fa-bell mr-2"></i>Notification Settings
                    </button>
                    <button class="w-full bg-gradient-custom-4 text-white py-3 rounded-xl hover:shadow-lg transition-all font-medium">
                        <i class="fas fa-user-slash mr-2"></i>Deactivate Account
                    </button>
                </div>
            </div>

            <!-- Help & Support -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-custom-5 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-life-ring text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Help & Support</h3>
                </div>
                
                <div class="space-y-3">
                    <button class="w-full bg-white bg-opacity-50 border border-white border-opacity-30 text-gray-700 py-3 rounded-xl hover:bg-opacity-70 transition-all font-medium">
                        <i class="fas fa-question-circle mr-2"></i>Help Center
                    </button>
                    <button class="w-full bg-white bg-opacity-50 border border-white border-opacity-30 text-gray-700 py-3 rounded-xl hover:bg-opacity-70 transition-all font-medium">
                        <i class="fas fa-envelope mr-2"></i>Contact Support
                    </button>
                    <button class="w-full bg-white bg-opacity-50 border border-white border-opacity-30 text-gray-700 py-3 rounded-xl hover:bg-opacity-70 transition-all font-medium">
                        <i class="fas fa-book mr-2"></i>Documentation
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection