<div>
    <div class="card">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        
        <div class="land-detail">
            <div class="land-images">
                <img src="{{ asset('storage/' . $land->images) }}" alt="{{ $land->description }}">
            </div>
            
            <div class="land-info">
                <h2>{{ $land->description }}</h2>
                <div class="land-price">{{ $land->prix }} €</div>
                <div class="land-location">{{ $land->adresse }}</div>
                <div class="land-specs">
                    <div class="spec-item">
                        <span class="spec-label">Size:</span>
                        <span class="spec-value">{{ $land->superficie }} sqm</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Type:</span>
                        <span class="spec-value">{{ $land->type }}</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Coordinates:</span>
                        <span class="spec-value">{{ $land->coordonneesGPS }}</span>
                    </div>
                </div>
                
                <div class="land-description">
                    <h3>Description</h3>
                    <p>{{ $land->description }}</p>
                </div>
                
                <div class="land-actions">
                    <button class="button" wire:click="addToFavorites">Add to Favorites</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-title">Contact the Seller</div>
        
        <form wire:submit.prevent="sendMessage">
            <div class="form-group">
                <label for="message">Your Message</label>
                <textarea id="message" wire:model="message" rows="4" placeholder="Enter your message to the seller..."></textarea>
                @error('message') <span class="error">{{ $message }}</span> @enderror
            </div>
            
            <button type="submit" class="button">Send Message</button>
        </form>
    </div>
</div>