<div>
    <div class="card">
        <div class="card-title">My Favorites</div>
        
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
        
        <div class="tabs">
            <div class="tab {{ $type === 'lands' ? 'active' : '' }}" wire:click="switchType('lands')">Agricultural Lands</div>
            <div class="tab {{ $type === 'equipment' ? 'active' : '' }}" wire:click="switchType('equipment')">Farm Equipment</div>
        </div>
        
        @if(count($favorites) > 0)
            <div class="grid">
                @if($type === 'lands')
                    @foreach($favorites as $land)
                    <div class="item-card">
                        <div class="item-image" style="background-image: url('{{ asset('storage/' . ($land->images ?? 'placeholder-land.jpg')) }}')"></div>
                        <div class="item-details">
                            <h3 class="item-title">{{ $land->description }}</h3>
                            <div class="item-price">{{ $land->prix }} €</div>
                            <div class="item-location">{{ $land->adresse }}</div>
                            <div class="item-specs">
                                <span>{{ $land->superficie }} sqm</span>
                            </div>
                            <div class="button-group">
                                <a href="{{ route('lands.detail', $land->id) }}" class="button">View Details</a>
                                <button class="button button-danger" 
                                        wire:click="removeFavorite({{ $land->id }}, 'lands')"
                                        onclick="confirm('Remove from favorites?') || event.stopImmediatePropagation()">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    @foreach($favorites as $equipment)
                    <div class="item-card">
                        <div class="item-image" style="background-image: url('{{ asset('storage/' . ($equipment->documentCatalogue ?? 'placeholder-equipment.jpg')) }}')"></div>
                        <div class="item-details">
                            <h3 class="item-title">{{ $equipment->typeEquipment }}</h3>
                            <div class="item-price">{{ $equipment->prix }} €</div>
                            <div class="item-condition">Condition: {{ $equipment->estNeuf ? 'New' : 'Used' }}</div>
                            <div class="button-group">
                                <a href="/equipment/{{ $equipment->id }}" class="button">View Details</a>
                                <button class="button button-danger" 
                                        wire:click="removeFavorite({{ $equipment->id }}, 'equipment')"
                                        onclick="confirm('Remove from favorites?') || event.stopImmediatePropagation()">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        @else
            <div class="empty-favorites">
                <p>You don't have any favorite {{ $type === 'lands' ? 'agricultural lands' : 'farm equipment' }} yet.</p>
                <a href="{{ $type === 'lands' ? route('lands.search') : route('equipment.search') }}" class="button">
                    Browse {{ $type === 'lands' ? 'Lands' : 'Equipment' }}
                </a>
            </div>
        @endif
    </div>
</div>