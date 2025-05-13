<div>
    <div class="card">
        <div class="card-title">Manage Your Profile</div>
        
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        
        <form wire:submit.prevent="updateProfile">
            <div class="profile-header">
                <div class="profile-image-container">
                    @if(Auth::user()->profile_image)
                        <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile Image" class="profile-image">
                    @else
                        <div class="profile-image-placeholder">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <div class="profile-image-upload">
                        <label for="profileImage" class="button button-small">Change Image</label>
                        <input type="file" id="profileImage" wire:model="profileImage" class="hidden-input">
                        @error('profileImage') <span class="error">{{ $message }}</span> @enderror
                        
                        <div wire:loading wire:target="profileImage">Uploading...</div>
                        
                        @if ($profileImage)
                            <img src="{{ $profileImage->temporaryUrl() }}" class="preview-image" alt="Preview">
                        @endif
                    </div>
                </div>
                
                <div class="profile-title">
                    <h3>{{ Auth::user()->name }}</h3>
                    <p>{{ Auth::user()->hasRole('client') ? 'Client' : (Auth::user()->hasRole('fournisseur') ? 'Supplier' : 'User') }}</p>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-section">
                    <h3>Account Information</h3>
                    
                    <div class="form-group">
                        <label for="name">Username</label>
                        <input type="text" id="name" wire:model="name">
                        @error('name') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" wire:model="email">
                        @error('email') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Personal Information</h3>
                    
                    <div class="form-group">
                        <label for="nom">Last Name</label>
                        <input type="text" id="nom" wire:model="nom">
                        @error('nom') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="prenom">First Name</label>
                        <input type="text" id="prenom" wire:model="prenom">
                        @error('prenom') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="telephone">Phone Number</label>
                        <input type="text" id="telephone" wire:model="telephone">
                        @error('telephone') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            
            @if(Auth::user()->fournisseur)
            <div class="form-section">
                <h3>Supplier Information</h3>
                
                <div class="form-group">
                    <label for="entreprise">Company Name</label>
                    <input type="text" id="entreprise" wire:model="entreprise">
                    @error('entreprise') <span class="error">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group">
                    <label for="adresse">Address</label>
                    <input type="text" id="adresse" wire:model="adresse">
                    @error('adresse') <span class="error">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group">
                    <label for="description">Company Description</label>
                    <textarea id="description" wire:model="description" rows="4"></textarea>
                    @error('description') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            @endif
            
            <div class="form-section">
                <h3>Change Password</h3>
                <p class="form-hint">Leave blank if you don't want to change your password</p>
                
                <div class="form-group">
                    <label for="currentPassword">Current Password</label>
                    <input type="password" id="currentPassword" wire:model="currentPassword">
                    @error('currentPassword') <span class="error">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" wire:model="newPassword">
                    @error('newPassword') <span class="error">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group">
                    <label for="newPassword_confirmation">Confirm New Password</label>
                    <input type="password" id="newPassword_confirmation" wire:model="newPassword_confirmation">
                </div>
            </div>
            
            <button type="submit" class="button">Update Profile</button>
        </form>
    </div>
</div>