<div>
    <div class="card">
        <div class="card-title">{{ $isEditing ? 'Edit Equipment' : 'Add New Equipment' }}</div>
        
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
        
        <form wire:submit.prevent="saveEquipment">
            <div class="form-group">
                <label for="typeEquipment">Equipment Type</label>
                <input type="text" id="typeEquipment" wire:model="typeEquipment" placeholder="Tractor, Seeder, etc.">
                @error('typeEquipment') <span class="error">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" wire:model="description" rows="4" placeholder="Detailed description of the equipment..."></textarea>
                @error('description') <span class="error">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="prix">Price (€)</label>
                <input type="number" step="0.01" id="prix" wire:model="prix">
                @error('prix') <span class="error">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="estNeuf">Condition</label>
                <select id="estNeuf" wire:model="estNeuf">
                    <option value="1">New</option>
                    <option value="0">Used</option>
                </select>
                @error('estNeuf') <span class="error">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="documentCatalogue">Equipment Image</label>
                <input type="file" id="documentCatalogue" wire:model="documentCatalogue">
                @error('documentCatalogue') <span class="error">{{ $message }}</span> @enderror
                
                <div wire:loading wire:target="documentCatalogue">Uploading...</div>
                
                @if ($documentCatalogue)
                    <img src="{{ $documentCatalogue->temporaryUrl() }}" class="preview-image" alt="Preview">
                @elseif ($isEditing && !$documentCatalogue)
                    <p>Current image will be kept. Upload a new one to replace it.</p>
                @endif
            </div>
            
            <div class="button-group">
                <button type="submit" class="button">{{ $isEditing ? 'Update Equipment' : 'Add Equipment' }}</button>
                @if($isEditing)
                    <button type="button" class="button button-secondary" wire:click="cancelEdit">Cancel</button>
                @endif
            </div>
        </form>
    </div>
    
    <div class="card">
        <div class="card-title">My Equipment Inventory</div>
        
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Type</th>
                    <th>Condition</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($equipment as $item)
                <tr>
                    <td>
                        @if($item->documentCatalogue)
                            <img src="{{ asset('storage/' . $item->documentCatalogue) }}" class="table-image" alt="{{ $item->typeEquipment }}">
                        @else
                            <div class="no-image">No Image</div>
                        @endif
                    </td>
                    <td>{{ $item->typeEquipment }}</td>
                    <td>{{ $item->estNeuf ? 'New' : 'Used' }}</td>
                    <td>{{ $item->prix }} €</td>
                    <td>
                        <button class="button button-small" wire:click="editEquipment({{ $item->id }})">Edit</button>
                        <button class="button button-small button-danger" 
                                wire:click="deleteEquipment({{ $item->id }})"
                                onclick="confirm('Are you sure you want to delete this item?') || event.stopImmediatePropagation()">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="pagination-container">
            {{ $equipment->links() }}
        </div>
    </div>
</div>