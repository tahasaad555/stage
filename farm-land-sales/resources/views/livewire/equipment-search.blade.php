<div>
    <div class="card">
        <div class="card-title">Search Farm Equipment</div>
        
        <div class="search-filters">
            <div class="form-group">
                <label for="search">Search Keywords</label>
                <input type="text" id="search" wire:model.debounce.300ms="search" placeholder="Search...">
            </div>
            
            <div class="filter-row">
                <div class="form-group">
                    <label for="minPrice">Min Price (€)</label>
                    <input type="number" id="minPrice" wire:model="minPrice">
                </div>
                
                <div class="form-group">
                    <label for="maxPrice">Max Price (€)</label>
                    <input type="number" id="maxPrice" wire:model="maxPrice">
                </div>
            </div>
            
            <div class="filter-row">
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" wire:model="category">
                        <option value="">All Categories</option>
                        <option value="tractors">Tractors</option>
                        <option value="harvesters">Harvesters</option>
                        <option value="seeders">Seeders</option>
                        <option value="irrigation">Irrigation</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="condition">Condition</label>
                    <select id="condition" wire:model="condition">
                        <option value="">All Conditions</option>
                        <option value="new">New</option>
                        <option value="used">Used</option>
                    </select>
                </div>
            </div>
            
            <button class="button" wire:click="resetFilters">Reset Filters</button>
        </div>
    </div>
    
    <div class="grid">
        @foreach($equipment as $item)
        <div class="item-card">
            <div class="item-image" style="background-image: url('{{ asset('storage/' . $item->documentCatalogue) }}')"></div>
            <div class="item-details">
                <h3 class="item-title">{{ $item->typeEquipment }}</h3>
                <div class="item-price">{{ $item->prix }} €</div>
                <div class="item-condition">Condition: {{ $item->estNeuf ? 'New' : 'Used' }}</div>
                <div class="item-description">{{ \Illuminate\Support\Str::limit($item->description, 100) }}</div>
                <a href="/equipment/{{ $item->id }}" class="button">View Details</a>
            </div>
        </div>
        @endforeach
    </div>
    
    <div>
        {{ $equipment->links() }}
    </div>
</div>