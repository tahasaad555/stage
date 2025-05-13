<div>
    <div class="card">
        <div class="card-title">Create New Announcement</div>
        
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        
        <form wire:submit.prevent="submit">
            <div class="form-group">
                <label for="titre">Title</label>
                <input type="text" id="titre" wire:model="titre">
                @error('titre') <span class="error">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" wire:model="type">
                    <option value="">Select Type</option>
                    <option value="land">Agricultural Land</option>
                    <option value="equipment">Farm Equipment</option>
                </select>
                @error('type') <span class="error">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" wire:model="description" rows="5"></textarea>
                @error('description') <span class="error">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="prix">Price (€)</label>
                <input type="number" id="prix" wire:model="prix">
                @error('prix') <span class="error">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" wire:model="image">
                @error('image') <span class="error">{{ $message }}</span> @enderror
                
                <div wire:loading wire:target="image">Uploading...</div>
                
                @if ($image)
                    <img src="{{ $image->temporaryUrl() }}" class="preview-image" alt="Preview">
                @endif
            </div>
            
            <button type="submit" class="button">Create Announcement</button>
        </form>
    </div>
</div>