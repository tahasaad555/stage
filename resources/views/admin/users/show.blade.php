@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('admin.users.index') }}" 
                   class="text-gray-500 hover:text-gray-700 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $user->full_name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                    @if($user->role === 'admin') bg-purple-100 text-purple-800
                    @elseif($user->role === 'client') bg-blue-100 text-blue-800
                    @else bg-green-100 text-green-800 @endif">
                    {{ ucfirst($user->role) }}
                </span>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                    <dd class="text-sm text-gray-900">{{ $user->full_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="text-sm text-gray-900">{{ $user->phone ?: 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                    <dd class="text-sm text-gray-900">{{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                    <dd class="text-sm text-gray-900">{{ ucfirst($user->role) }}</dd>
                </div>
            </dl>
        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="text-sm text-gray-900">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                    <dd class="text-sm text-gray-900">
                        @if($user->email_verified_at)
                            <span class="text-green-600">
                                <i class="fas fa-check-circle mr-1"></i>
                                Verified on {{ $user->email_verified_at->format('M d, Y') }}
                            </span>
                        @else
                            <span class="text-red-600">
                                <i class="fas fa-times-circle mr-1"></i>
                                Not verified
                            </span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                    <dd class="text-sm text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                    <dd class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Role-Specific Information -->
    @if($user->role === 'client' && $user->client)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Client Information</h3>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Specialization Type</dt>
                <dd class="text-sm text-gray-900">{{ $user->client->specialization_type ?: 'Not specified' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Preferences</dt>
                <dd class="text-sm text-gray-900">{{ $user->client->preferences ?: 'Not specified' }}</dd>
            </div>
        </dl>
    </div>
    @endif

    @if($user->role === 'fournisseur' && $user->fournisseur)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Supplier Information</h3>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                <dd class="text-sm text-gray-900">{{ $user->fournisseur->company_name }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Business Registration</dt>
                <dd class="text-sm text-gray-900">{{ $user->fournisseur->business_registration }}</dd>
            </div>
            <div class="md:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Address</dt>
                <dd class="text-sm text-gray-900">{{ $user->fournisseur->address }}</dd>
            </div>
        </dl>
    </div>
    @endif

    <!-- Actions -->
    @if($user->id !== auth()->id())
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
        <div class="flex space-x-4">
            <button onclick="toggleUserStatus({{ $user->id }})" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                <i class="fas fa-toggle-{{ $user->is_active ? 'off' : 'on' }} mr-2"></i>
                {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
            </button>
            
            <button onclick="deleteUser({{ $user->id }})" 
                    class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                <i class="fas fa-trash mr-2"></i>
                Delete User
            </button>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
async function toggleUserStatus(userId) {
    if (!confirm('Are you sure you want to toggle this user\'s status?')) return;
    
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
            showAlert(result.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(result.message, 'error');
        }
    } catch (error) {
        showAlert('An error occurred', 'error');
    }
}

async function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) return;
    
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
            showAlert(result.message, 'success');
            setTimeout(() => window.location.href = '{{ route("admin.users.index") }}', 1000);
        } else {
            showAlert(result.message, 'error');
        }
    } catch (error) {
        showAlert('An error occurred', 'error');
    }
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-md ${
        type === 'success' ? 'bg-green-100 text-green-700 border border-green-400' :
        'bg-red-100 text-red-700 border border-red-400'
    }`;
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
            ${message}
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-lg">&times;</button>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endpush
@endsection