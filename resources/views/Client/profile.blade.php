@extends('layouts.client')

@section('title', 'Profile')
@section('page-title', 'Profile Settings')

@section('content')
    <!-- Header Section -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl mb-8 card-hover">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-custom-2 rounded-2xl flex items-center justify-center mr-6">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold mb-2">👤 Profile Settings</h1>
                    <p class="text-xl opacity-90">Manage your account information</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg opacity-75">Account Status</div>
                <div class="text-2xl font-bold text-green-600">Active</div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="glass-effect rounded-xl p-6 border-l-4 border-green-500 shadow-xl mb-8">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center mr-4">
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
                    <div class="w-12 h-12 bg-gradient-custom-1 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Personal Information</h2>
                        <p class="text-gray-600">Update your account details and contact information</p>
                    </div>
                </div>

                <form action="{{ route('client.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-blue-500"></i>First Name
                            </label>
                            <input type="text" 
                                   name="first_name" 
                                   id="first_name" 
                                   value="{{ old('first_name', $user->first_name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror"
                                   required>
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-blue-500"></i>Last Name
                            </label>
                            <input type="text" 
                                   name="last_name" 
                                   id="last_name" 
                                   value="{{ old('last_name', $user->last_name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror"
                                   required>
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-green-500"></i>Email Address
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2 text-purple-500"></i>Phone Number
                        </label>
                        <input type="tel" 
                               name="phone" 
                               id="phone" 
                               value="{{ old('phone', $user->phone) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                               placeholder="+212 6 XX XX XX XX">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end pt-6">
                        <button type="submit" 
                                class="bg-gradient-custom-1 text-white px-8 py-4 rounded-xl hover:shadow-lg transition-all font-bold text-lg">
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
                    <div class="w-24 h-24 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-white font-bold text-3xl">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </span>
                    </div>
                    <h4 class="font-bold text-gray-800 text-xl">{{ $user->full_name }}</h4>
                    <p class="text-gray-600 font-medium">Agricultural Client</p>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium mt-2 inline-block">Active Account</span>
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
                        <span class="text-green-600 font-bold">
                            {{ $user->email && $user->first_name && $user->last_name ? '90%' : '70%' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-custom-3 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Quick Stats</h3>
                </div>
                
                <div class="space-y-4 text-sm">
                    <div class="p-4 bg-white bg-opacity-50 rounded-lg">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-gray-600 font-medium">Saved Properties:</span>
                            <span class="text-blue-600 font-bold text-lg">
                                {{ auth()->user()->savedProperties()->count() }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500">Properties you've bookmarked</div>
                    </div>
                    
                    <div class="p-4 bg-white bg-opacity-50 rounded-lg">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-gray-600 font-medium">Inquiries Sent:</span>
                            <span class="text-green-600 font-bold text-lg">
                                {{ auth()->user()->clientInquiries()->count() }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500">Total inquiries submitted</div>
                    </div>
                    
                    <div class="p-4 bg-white bg-opacity-50 rounded-lg">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-gray-600 font-medium">Last Activity:</span>
                            <span class="text-purple-600 font-bold text-sm">
                                {{ $user->updated_at->diffForHumans() }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500">Profile last updated</div>
                    </div>
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
                    <a href="{{ route('client.settings') }}" 
                       class="w-full bg-gradient-custom-4 text-white py-3 rounded-xl hover:shadow-lg transition-all font-medium text-center block">
                        <i class="fas fa-cog mr-2"></i>Account Settings
                    </a>
                    <button onclick="showChangePasswordModal()" 
                            class="w-full bg-gradient-custom-3 text-white py-3 rounded-xl hover:shadow-lg transition-all font-medium">
                        <i class="fas fa-key mr-2"></i>Change Password
                    </button>
                    <a href="{{ route('client.saved-properties') }}" 
                       class="w-full bg-gradient-custom-5 text-white py-3 rounded-xl hover:shadow-lg transition-all font-medium text-center block">
                        <i class="fas fa-heart mr-2"></i>Saved Properties
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Change Password</h3>
                <button onclick="closeChangePasswordModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('client.settings.change-password') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" 
                           name="current_password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" 
                           name="password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" 
                           name="password_confirmation" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           required>
                </div>
                
                <div class="flex space-x-3 pt-4">
                    <button type="button" 
                            onclick="closeChangePasswordModal()" 
                            class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl hover:bg-gray-300 transition-all font-medium">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-medium">
                        <i class="fas fa-key mr-2"></i>Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showChangePasswordModal() {
            document.getElementById('changePasswordModal').classList.remove('hidden');
        }

        function closeChangePasswordModal() {
            document.getElementById('changePasswordModal').classList.add('hidden');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeChangePasswordModal();
            }
        });
    </script>
@endsection