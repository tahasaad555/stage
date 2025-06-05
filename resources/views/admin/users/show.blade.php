@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.users.index') }}" 
                   class="text-white hover:text-gray-200 mr-6 bg-white bg-opacity-20 rounded-lg p-3 hover:bg-opacity-30 transition-all">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div class="flex items-center space-x-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-{{ $user->role === 'admin' ? 'red' : ($user->role === 'client' ? 'blue' : 'green') }}-400 to-{{ $user->role === 'admin' ? 'pink' : ($user->role === 'client' ? 'purple' : 'yellow') }}-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-2xl">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">{{ $user->full_name }}</h1>
                        <p class="text-xl opacity-90 flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            {{ $user->email }}
                        </p>
                        <div class="flex items-center space-x-4 mt-2">
                            @if($user->role === 'admin')
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">👑 Administrator</span>
                            @elseif($user->role === 'client')
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">👤 Client</span>
                            @else
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">🏢 Supplier</span>
                            @endif
                            
                            <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $user->is_active ? '✅ Active' : '❌ Inactive' }}
                            </span>
                            
                            @if($user->email_verified_at)
                                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-semibold">
                                    ✓ Verified
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-circle text-6xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced User Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Basic Information Card -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Basic Information</h3>
                    <p class="text-gray-600">Personal details and contact info</p>
                </div>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-id-card text-blue-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $user->full_name }}</dd>
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-envelope text-green-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                        <dd class="text-lg font-semibold text-gray-900">
                            <a href="mailto:{{ $user->email }}" class="hover:text-blue-600 transition-colors">{{ $user->email }}</a>
                        </dd>
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-phone text-purple-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $user->phone ?: 'Not provided' }}</dd>
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-birthday-cake text-pink-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'Not provided' }}</dd>
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-user-tag text-indigo-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">User Role</dt>
                        <dd class="text-lg font-semibold text-gray-900">
                            @if($user->role === 'admin') 👑 Administrator
                            @elseif($user->role === 'client') 👤 Client
                            @else 🏢 Supplier @endif
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Status Card -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-shield-alt text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Account Status</h3>
                    <p class="text-gray-600">Security and verification details</p>
                </div>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-center p-4 bg-gradient-to-r from-{{ $user->is_active ? 'green' : 'red' }}-50 to-{{ $user->is_active ? 'green' : 'red' }}-100 rounded-xl">
                    <i class="fas fa-toggle-{{ $user->is_active ? 'on' : 'off' }} text-{{ $user->is_active ? 'green' : 'red' }}-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                        <dd class="text-lg font-semibold text-{{ $user->is_active ? 'green' : 'red' }}-600">
                            {{ $user->is_active ? '✅ Active Account' : '❌ Inactive Account' }}
                        </dd>
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gradient-to-r from-{{ $user->email_verified_at ? 'blue' : 'yellow' }}-50 to-{{ $user->email_verified_at ? 'blue' : 'yellow' }}-100 rounded-xl">
                    <i class="fas fa-{{ $user->email_verified_at ? 'check-circle' : 'exclamation-triangle' }} text-{{ $user->email_verified_at ? 'blue' : 'yellow' }}-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Verification</dt>
                        <dd class="text-lg font-semibold text-{{ $user->email_verified_at ? 'blue' : 'yellow' }}-600">
                            @if($user->email_verified_at)
                                ✓ Verified on {{ $user->email_verified_at->format('M d, Y') }}
                            @else
                                ⚠ Not verified
                            @endif
                        </dd>
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl" data-last-login>
                    <i class="fas fa-clock text-indigo-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                        <dd class="text-lg font-semibold text-gray-900">
                            {{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never logged in' }}
                        </dd>
                        @if($user->last_login_at)
                            <p class="text-sm text-gray-500">{{ $user->last_login_at->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <i class="fas fa-calendar-plus text-green-500 mr-4 text-lg"></i>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                        <p class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                
                @if($user->last_login_at && $user->last_login_at->isToday())
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-white rounded-full mr-3 animate-pulse"></div>
                            <div>
                                <div class="font-semibold">Online Status</div>
                                <div class="text-sm opacity-90">Active today</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Activity & Stats Card -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Activity & Stats</h3>
                    <p class="text-gray-600">User engagement metrics</p>
                </div>
            </div>
            
            <div class="space-y-6">
                <div class="text-center p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl">
                    <div class="text-3xl font-bold text-blue-600 mb-2">{{ $user->id }}</div>
                    <div class="text-sm text-gray-600">User ID</div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                        <div class="text-2xl font-bold text-green-600">
                            {{ $user->created_at->diffInDays(now()) }}
                        </div>
                        <div class="text-xs text-gray-600">Days Active</div>
                    </div>
                    
                    <div class="text-center p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl">
                        <div class="text-2xl font-bold text-yellow-600">
                            {{ $user->last_login_at ? $user->last_login_at->diffInDays($user->created_at) : 0 }}
                        </div>
                        <div class="text-xs text-gray-600">Login Streak</div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Account Completion</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>Profile Info</span>
                            <span class="font-semibold">{{ ($user->phone ? 25 : 0) + ($user->date_of_birth ? 25 : 0) + 50 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full" style="width: {{ ($user->phone ? 25 : 0) + ($user->date_of_birth ? 25 : 0) + 50 }}%"></div>
                        </div>
                    </div>
                </div>
                
                @if($user->email_verified_at)
                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl p-4 text-white">
                        <div class="flex items-center">
                            <i class="fas fa-shield-check mr-3 text-xl"></i>
                            <div>
                                <div class="font-semibold">Verified Account</div>
                                <div class="text-sm opacity-90">Email verified and secure</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Role-Specific Information -->
    @if($user->role === 'client' && $user->client)
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-user text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">👤 Client Information</h3>
                <p class="text-gray-600">Land buyer specific details</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-seedling text-blue-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Specialization Type</h4>
                </div>
                <p class="text-gray-700 text-lg">{{ $user->client->specialization_type ?: 'Not specified' }}</p>
            </div>
            
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-heart text-purple-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Preferences</h4>
                </div>
                <p class="text-gray-700 text-lg">{{ $user->client->preferences ?: 'Not specified' }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($user->role === 'fournisseur' && $user->fournisseur)
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-building text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">🏢 Supplier Information</h3>
                <p class="text-gray-600">Business and company details</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-building text-green-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Company Name</h4>
                </div>
                <p class="text-gray-700 text-lg font-semibold">{{ $user->fournisseur->company_name }}</p>
            </div>
            
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-certificate text-blue-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Business Registration</h4>
                </div>
                <p class="text-gray-700 text-lg font-mono">{{ $user->fournisseur->business_registration }}</p>
            </div>
            
            <div class="md:col-span-2 bg-gradient-to-r from-yellow-50 to-orange-100 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-map-marker-alt text-yellow-500 mr-3 text-lg"></i>
                    <h4 class="text-lg font-semibold text-gray-800">Business Address</h4>
                </div>
                <p class="text-gray-700 text-lg">{{ $user->fournisseur->address }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    @if($user->id !== auth()->id())
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-cogs text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">User Management Actions</h3>
                <p class="text-gray-600">Administrative controls and options</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Toggle Status Button -->
            <button onclick="toggleUserStatus({{ $user->id }})" 
                    class="bg-gradient-to-r from-{{ $user->is_active ? 'red' : 'green' }}-500 to-{{ $user->is_active ? 'red' : 'green' }}-600 text-white p-6 rounded-xl hover:from-{{ $user->is_active ? 'red' : 'green' }}-600 hover:to-{{ $user->is_active ? 'red' : 'green' }}-700 transition-all transform hover:scale-105 group">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-toggle-{{ $user->is_active ? 'off' : 'on' }} text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Action</span>
                </div>
                <h4 class="font-bold text-lg mb-2">{{ $user->is_active ? 'Deactivate' : 'Activate' }} User</h4>
                <p class="text-sm opacity-90">{{ $user->is_active ? 'Suspend user access' : 'Restore user access' }}</p>
            </button>
            
            <!-- Edit User Button -->
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-105 group block">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-edit text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Edit</span>
                </div>
                <h4 class="font-bold text-lg mb-2">Edit User</h4>
                <p class="text-sm opacity-90">Modify user information</p>
            </a>
            
            <!-- Send Message Button -->
            <button onclick="openMessageModal()" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all transform hover:scale-105 group">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-envelope text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Message</span>
                </div>
                <h4 class="font-bold text-lg mb-2">Send Message</h4>
                <p class="text-sm opacity-90">Contact this user directly</p>
            </button>
            
            <!-- Delete User Button -->
            <button onclick="deleteUser({{ $user->id }})" 
                    class="bg-gradient-to-r from-red-500 to-red-600 text-white p-6 rounded-xl hover:from-red-600 hover:to-red-700 transition-all transform hover:scale-105 group">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-trash text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Delete</span>
                </div>
                <h4 class="font-bold text-lg mb-2">Delete User</h4>
                <p class="text-sm opacity-90">Permanently remove user</p>
            </button>
        </div>
    </div>
    @endif
</div>

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

/* Loading animation */
.loading {
    position: relative;
    opacity: 0.6;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #ccc;
    border-top: 2px solid #333;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Notification styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    padding: 16px;
    border-radius: 12px;
    color: white;
    font-weight: 500;
    transform: translateX(100%);
    transition: transform 0.3s ease-in-out;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.notification.show {
    transform: translateX(0);
}

.notification.success {
    background: linear-gradient(135deg, #10b981, #059669);
}

.notification.error {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

/* Modal styles */
.modal-overlay {
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

.modal-content {
    max-height: 90vh;
    overflow-y: auto;
}
</style>
@endpush

@push('scripts')
<script>
// Get CSRF token
window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

// Toggle User Status
async function toggleUserStatus(userId) {
    if (!confirm('Are you sure you want to toggle this user\'s status?')) return;
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-3xl mb-4"></i><h4 class="font-bold text-lg mb-2">Processing...</h4><p class="text-sm opacity-90">Please wait</p>';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/users/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(result.message, 'error');
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Delete User
async function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) return;
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-3xl mb-4"></i><h4 class="font-bold text-lg mb-2">Deleting...</h4><p class="text-sm opacity-90">Please wait</p>';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => window.location.href = '{{ route("admin.users.index") }}', 1000);
        } else {
            showNotification(result.message, 'error');
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Send Message Modal
function openMessageModal() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 modal-overlay flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 transform transition-all modal-content">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Send Message to {{ $user->full_name }}</h3>
                <p class="text-gray-600">Send a direct message to this user via email</p>
            </div>
            
            <form id="messageForm" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-2 text-purple-500"></i>Subject
                    </label>
                    <input type="text" name="subject" required placeholder="Enter message subject..."
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment mr-2 text-purple-500"></i>Message
                    </label>
                    <textarea name="message" rows="6" required placeholder="Type your message here..."
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-none"></textarea>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <p class="text-blue-700 text-sm">This message will be sent to <strong>{{ $user->email }}</strong></p>
                    </div>
                </div>
                
                <div class="flex space-x-3 pt-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-purple-500 to-purple-600 text-white py-3 px-6 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all">
                        <i class="fas fa-paper-plane mr-2"></i>Send Message
                    </button>
                    <button type="button" onclick="this.closest('.fixed').remove()" class="bg-gray-200 text-gray-700 py-3 px-6 rounded-lg hover:bg-gray-300 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Handle form submission
    document.getElementById('messageForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const formData = new FormData(e.target);
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
        submitBtn.disabled = true;
        
        try {
            const response = await fetch(`/admin/users/{{ $user->id }}/send-message`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({
                    subject: formData.get('subject'),
                    message: formData.get('message')
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                modal.remove();
                showNotification('Message sent successfully!', 'success');
            } else {
                showNotification(result.message || 'Failed to send message', 'error');
                submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Send Message';
                submitBtn.disabled = false;
            }
        } catch (error) {
            showNotification('An error occurred while sending the message', 'error');
            submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Send Message';
            submitBtn.disabled = false;
        }
    });
    
    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.remove();
    });
    
    // Close modal with Escape key
    const escapeHandler = (e) => {
        if (e.key === 'Escape' && modal.parentNode) {
            modal.remove();
            document.removeEventListener('keydown', escapeHandler);
        }
    };
    document.addEventListener('keydown', escapeHandler);
    
    // Focus first input
    setTimeout(() => {
        modal.querySelector('input[name="subject"]').focus();
    }, 100);
}

// Show Notification Function
function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="${icon} mr-3 text-xl"></i>
            <div class="flex-1">
                <div class="font-semibold">${type === 'success' ? 'Success' : 'Error'}</div>
                <div class="text-sm opacity-90">${message}</div>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 hover:bg-white hover:bg-opacity-20 rounded-full p-1 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Show notification with animation
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

// Enhanced loading states and error handling
function setLoading(element, isLoading) {
    if (isLoading) {
        element.classList.add('loading');
        element.setAttribute('disabled', 'disabled');
    } else {
        element.classList.remove('loading');
        element.removeAttribute('disabled');
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + E to edit user
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
        e.preventDefault();
        window.location.href = '{{ route("admin.users.edit", $user) }}';
    }
    
    // Ctrl/Cmd + M to send message
    if ((e.ctrlKey || e.metaKey) && e.key === 'm') {
        e.preventDefault();
        openMessageModal();
    }
    
    // Ctrl/Cmd + Backspace to go back
    if ((e.ctrlKey || e.metaKey) && e.key === 'Backspace') {
        e.preventDefault();
        window.location.href = '{{ route("admin.users.index") }}';
    }
});

// Add tooltips for action buttons
document.addEventListener('DOMContentLoaded', function() {
    const actionButtons = document.querySelectorAll('[onclick], a[href*="edit"]');
    actionButtons.forEach(button => {
        const title = button.querySelector('h4')?.textContent || button.title;
        if (title) button.setAttribute('title', title);
    });
    
    // Animate cards on load
    const cards = document.querySelectorAll('.glass-effect');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Auto-refresh user data every 30 seconds (optional)
let autoRefreshInterval;
function startAutoRefresh() {
    autoRefreshInterval = setInterval(() => {
        // Check if user is still on the page
        if (document.hidden) return;
        
        // Refresh only specific dynamic elements
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Update last login time if changed
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newLastLogin = doc.querySelector('[data-last-login]');
            const currentLastLogin = document.querySelector('[data-last-login]');
            
            if (newLastLogin && currentLastLogin && 
                newLastLogin.innerHTML !== currentLastLogin.innerHTML) {
                currentLastLogin.innerHTML = newLastLogin.innerHTML;
                showNotification('User data updated', 'success');
            }
        })
        .catch(error => {
            console.warn('Auto-refresh failed:', error);
        });
    }, 30000);
}

// Start auto-refresh when page loads
document.addEventListener('DOMContentLoaded', startAutoRefresh);

// Stop auto-refresh when page is hidden
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        clearInterval(autoRefreshInterval);
    } else {
        startAutoRefresh();
    }
});

// Cleanup when page unloads
window.addEventListener('beforeunload', function() {
    clearInterval(autoRefreshInterval);
});

// Print user details function (bonus feature)
function printUserDetails() {
    const printWindow = window.open('', '_blank');
    const userDetails = document.querySelector('.space-y-8').innerHTML;
    
    printWindow.document.write(`
        <html>
            <head>
                <title>User Details - {{ $user->full_name }}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .no-print { display: none; }
                    .glass-effect { background: #f9f9f9; border: 1px solid #ddd; padding: 20px; margin: 10px 0; }
                    h1, h2, h3 { color: #333; }
                </style>
            </head>
            <body>
                <h1>User Details Report</h1>
                <p>Generated on: ${new Date().toLocaleString()}</p>
                <hr>
                ${userDetails}
            </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}

// Add print shortcut
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault();
        printUserDetails();
    }
});
</script>
@endpush
@endsection