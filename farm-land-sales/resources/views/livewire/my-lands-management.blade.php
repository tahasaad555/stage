<div>
    <div class="card">
        <div class="card-title">{{ $isEditing ? 'Edit Land' : 'Add New Land' }}</div>
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
    
    <form wire:submit.prevent="saveLand">
        <div class="form-group">
            <label for="titre">Title</label>
            <input type="text" id="titre" wire:model="titre" placeholder="Fertile farmland...">
            @error('titre') <span class="error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" wire:model="description" rows="4" placeholder="Detailed description of the land..."></textarea>
            @error('description') <span class="error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-group">
            <label for="adresse">Address</label>
            <input type="text" id="adresse" wire:model="adresse" placeholder="Full address of the land...">
            @error('adresse') <span class="error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-group">
            <label for="region">Region</label>
            <input type="text" id="region" wire:model="region" placeholder="Region name...">
            @error('region') <span class="error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-group">
            <label for="coordonneesGPS">GPS Coordinates</label>
            <input type="text" id="coordonneesGPS" wire:model="coordonneesGPS" placeholder="Latitude, Longitude">
            @error('coordonneesGPS') <span class="error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="superficie">Area (sqm)</label>
                <input type="number" id="superficie" wire:model="superficie" min="0">
                @error('superficie') <span class="error">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="prix">Price (€)</label>
                <input type="number" step="0.01" id="prix" wire:model="prix" min="0">
                @error('prix') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label for="type">Land Type</label>
            <select id="type" wire:model="type">
                <option value="">Select Type</option>
                <option value="arable">Arable Land</option>
                <option value="pasture">Pasture Land</option>
                <option value="vineyard">Vineyard</option>
                <option value="orchard">Orchard</option>
                <option value="forest">Forest</option>
                <option value="mixed">Mixed Use</option>
            </select>
            @error('type') <span class="error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-group">
            <label for="images">Land Image</label>
            <input type="file" id="images" wire:model="images">
            @error('images') <span class="error">{{ $message }}</span> @enderror
            
            <div wire:loading wire:target="images">Uploading...</div>
            
            @if ($images)
                <img src="{{ $images->temporaryUrl() }}" class="preview-image" alt="Preview">
            @elseif ($isEditing)
                <p>Current image will be kept. Upload a new one to replace it.</p>
            @endif
        </div>
        
        <div class="button-group">
            <button type="submit" class="button">{{ $isEditing ? 'Update Land' : 'Add Land' }}</button>
            @if($isEditing)
                <button type="button" class="button button-secondary" wire:click="cancelEdit">Cancel</button>
            @endif
        </div>
    </form>
</div>

<div class="card">
    <div class="card-title">My Land Listings</div>
    
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Location</th>
                <th>Area</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lands as $land)
            <tr>
                <td>
                    @if($land->images)
                        <img src="{{ asset('storage/' . $land->images) }}" class="table-image" alt="{{ $land->titre }}">
                    @else
                        <div class="no-image">No Image</div>
                    @endif
                </td>
                <td>{{ $land->titre }}</td>
                <td>{{ $land->adresse }}</td>
                <td>{{ $land->superficie }} sqm</td>
                <td>{{ $land->prix }} €</td>
                <td>{{ $land->statut }}</td>
                <td>
                    <div class="button-group">
                        <button class="button button-small" wire:click="editLand({{ $land->id }})">Edit</button>
                        <button class="button button-small button-danger" 
                                wire:click="deleteLand({{ $land->id }})"
                                onclick="confirm('Are you sure you want to delete this listing?') || event.stopImmediatePropagation()">
                            Delete
                        </button>
                        <a href="{{ route('lands.detail', $land->id) }}" class="button button-small">View</a>
                    </div>
                </td>
            </tr>
            @endforeach
            
            @if(count($lands) === 0)
            <tr>
                <td colspan="7" class="empty-table">You don't have any land listings yet.</td>
            </tr>
            @endif
        </tbody>
    </table>
    
    <div class="pagination-container">
        {{ $lands->links() }}
    </div>
</div>
</div>