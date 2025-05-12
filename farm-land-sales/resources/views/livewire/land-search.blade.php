<div>
    <div class="card">
        <div class="card-title">Search Agricultural Lands</div>
        
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
                    <label for="minSize">Min Size (sqm)</label>
                    <input type="number" id="minSize" wire:model="minSize">
                </div>
                
                <div class="form-group">
                    <label for="maxSize">Max Size (sqm)</label>
                    <input type="number" id="maxSize" wire:model="maxSize">
                </div>
            </div>
            
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" wire:model.debounce.300ms="location" placeholder="City, Region...">
            </div>
            
            <button class="button" wire:click="resetFilters">Reset Filters</button>
        </div>
    </div>
    
    <div class="grid">
        @foreach($lands as $land)
        <div class="item-card">
            <div class="item-image" style="background-image: url('{{ asset('storage/' . $land->images) }}')"></div>
            <div class="item-details">
                <h3 class="item-title">{{ $land->description }}</h3>
                <div class="item-price">{{ $land->prix }} €</div>
                <div class="item-location">{{ $land->adresse }}</div>
                <div class="item-specs">
                    <span>{{ $land->superficie }} sqm</span>
                </div>
                <a href="{{ route('lands.detail', $land->id) }}">View Details</a>
            </div>
        </div>
        @endforeach
    </div>
    
    <div>
        {{ $lands->links() }}
    </div>
</div>