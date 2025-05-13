<div>
    <div class="card">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="equipment-detail">
            <div class="equipment-images">
                @if($equipment->documentCatalogue)
                    <img src="{{ asset('storage/' . $equipment->documentCatalogue) }}" alt="{{ $equipment->typeEquipment }}">
                @else
                    <div class="placeholder-image">No image available</div>
                @endif
            </div>
            
            <div class="equipment-info">
                <h2>{{ $equipment->typeEquipment }}</h2>
                <div class="equipment-price">{{ $equipment->prix }} €</div>
                <div class="equipment-condition">Condition: {{ $equipment->estNeuf ? 'New' : 'Used' }}</div>
                
                <div class="equipment-description">
                    <h3>Description</h3>
                    <p>{{ $equipment->description }}</p>
                </div>
                
                <div class="supplier-info">
                    <h3>Supplier Information</h3>
                    <p><strong>Company:</strong> {{ $equipment->fournisseur->entreprise }}</p>
                    <p><strong>Contact:</strong> {{ $equipment->fournisseur->nom }} {{ $equipment->fournisseur->prenom }}</p>
                </div>
                
                <div class="equipment-actions">
                    <button class="button" wire:click="addToFavorites">Add to Favorites</button>
                    <a href="{{ route('equipment.search') }}" class="button button-secondary">Back to Search</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-title">Contact the Supplier</div>
        
        <form wire:submit.prevent="sendMessage">
            <div class="form-group">
                <label for="message">Your Message</label>
                <textarea id="message" wire:model="message" rows="4" placeholder="Enter your message to the supplier..."></textarea>
                @error('message') <span class="error">{{ $message }}</span> @enderror
            </div>
            
            <button type="submit" class="button">Send Message</button>
        </form>
    </div>
</div>