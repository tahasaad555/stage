<div>
    <div class="auth-container">
        <div class="auth-card">
            <h2>Create Account</h2>
            
            <form wire:submit.prevent="register">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" type="text" wire:model="name" autocomplete="name" required>
                    @error('name') <span class="error">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" wire:model="email" autocomplete="email" required>
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" wire:model="password" autocomplete="new-password" required>
                    @error('password') <span class="error">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" type="password" wire:model="password_confirmation" autocomplete="new-password" required>
                </div>
                
                <div class="form-group">
                    <label>Account Type</label>
                    <div class="user-type-selector">
                        <div class="user-type-option {{ $userType === 'client' ? 'selected' : '' }}"
                             wire:click="$set('userType', 'client')">
                            <div class="user-type-icon">👨‍🌾</div>
                            <div class="user-type-label">Client</div>
                            <div class="user-type-description">Search and buy agricultural lands and farm equipment</div>
                        </div>
                        
                        <div class="user-type-option {{ $userType === 'fournisseur' ? 'selected' : '' }}"
                             wire:click="$set('userType', 'fournisseur')">
                            <div class="user-type-icon">🏪</div>
                            <div class="user-type-label">Supplier</div>
                            <div class="user-type-description">Sell farm equipment and materials</div>
                        </div>
                    </div>
                </div>
                
                <div class="profile-details">
                    <h3>{{ $userType === 'client' ? 'Client' : 'Supplier' }} Details</h3>
                    
                    <div class="form-group">
                        <label for="nom">Last Name</label>
                        <input id="nom" type="text" wire:model="nom" required>
                        @error('nom') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="prenom">First Name</label>
                        <input id="prenom" type="text" wire:model="prenom" required>
                        @error('prenom') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="telephone">Phone Number</label>
                        <input id="telephone" type="text" wire:model="telephone" required>
                        @error('telephone') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    
                    @if($userType === 'fournisseur')
                        <div class="form-group">
                            <label for="entreprise">Company Name</label>
                            <input id="entreprise" type="text" wire:model="entreprise" required>
                            @error('entreprise') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="adresse">Address</label>
                            <input id="adresse" type="text" wire:model="adresse" required>
                            @error('adresse') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Company Description</label>
                            <textarea id="description" wire:model="description" rows="3" required></textarea>
                            @error('description') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    @endif
                </div>
                
                <button type="submit" class="button">Register</button>
                
                <div class="auth-links">
                    <p>Already have an account? <a href="{{ route('login') }}">Log in</a></p>
                </div>
            </form>
        </div>
    </div>
</div>