@extends('layouts.app')

@section('title', 'Liste des Terres Agricoles')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h3">Liste des Terres Agricoles</h1>
    </div>
    <div class="col text-end">
        @auth
            @if(auth()->user()->isAdmin() || auth()->user()->isFournisseur())
                <a href="{{ route('terres.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Ajouter une terre
                </a>
            @endif
        @endauth
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Filtres de recherche</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('terres.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="region" class="form-label">Région</label>
                            <select class="form-select" id="region" name="region">
                                <option value="">Toutes les régions</option>
                                <option value="Auvergne-Rhône-Alpes" {{ request('region') == 'Auvergne-Rhône-Alpes' ? 'selected' : '' }}>Auvergne-Rhône-Alpes</option>
                                <option value="Bourgogne-Franche-Comté" {{ request('region') == 'Bourgogne-Franche-Comté' ? 'selected' : '' }}>Bourgogne-Franche-Comté</option>
                                <option value="Bretagne" {{ request('region') == 'Bretagne' ? 'selected' : '' }}>Bretagne</option>
                                <option value="Centre-Val de Loire" {{ request('region') == 'Centre-Val de Loire' ? 'selected' : '' }}>Centre-Val de Loire</option>
                                <option value="Corse" {{ request('region') == 'Corse' ? 'selected' : '' }}>Corse</option>
                                <option value="Grand Est" {{ request('region') == 'Grand Est' ? 'selected' : '' }}>Grand Est</option>
                                <option value="Hauts-de-France" {{ request('region') == 'Hauts-de-France' ? 'selected' : '' }}>Hauts-de-France</option>
                                <option value="Île-de-France" {{ request('region') == 'Île-de-France' ? 'selected' : '' }}>Île-de-France</option>
                                <option value="Normandie" {{ request('region') == 'Normandie' ? 'selected' : '' }}>Normandie</option>
                                <option value="Nouvelle-Aquitaine" {{ request('region') == 'Nouvelle-Aquitaine' ? 'selected' : '' }}>Nouvelle-Aquitaine</option>
                                <option value="Occitanie" {{ request('region') == 'Occitanie' ? 'selected' : '' }}>Occitanie</option>
                                <option value="Pays de la Loire" {{ request('region') == 'Pays de la Loire' ? 'selected' : '' }}>Pays de la Loire</option>
                                <option value="Provence-Alpes-Côte d'Azur" {{ request('region') == 'Provence-Alpes-Côte d\'Azur' ? 'selected' : '' }}>Provence-Alpes-Côte d'Azur</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="prix_min" class="form-label">Prix min (€)</label>
                            <input type="number" class="form-control" id="prix_min" name="prix_min" value="{{ request('prix_min') }}" min="0">
                        </div>
                        <div class="col-md-2">
                            <label for="prix_max" class="form-label">Prix max (€)</label>
                            <input type="number" class="form-control" id="prix_max" name="prix_max" value="{{ request('prix_max') }}" min="0">
                        </div>
                        <div class="col-md-2">
                            <label for="surface_min" class="form-label">Surface min (ha)</label>
                            <input type="number" class="form-control" id="surface_min" name="surface_min" value="{{ request('surface_min') }}" min="0" step="0.1">
                        </div>
                        <div class="col-md-2">
                            <label for="surface_max" class="form-label">Surface max (ha)</label>
                            <input type="number" class="form-control" id="surface_max" name="surface_max" value="{{ request('surface_max') }}" min="0" step="0.1">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @forelse($terresAgricoles as $terre)
        <div class="col-md-4 mb-4">
            <div class="card h-100 hover-scale">
                <div style="height: 250px; overflow: hidden;">
                    @if($terre->photos && count($terre->photos) > 0)
                        <img src="{{ asset('storage/' . $terre->photos[0]) }}" class="card-img-top" alt="{{ $terre->titre }}" style="object-fit: cover; height: 100%; width: 100%;">
                    @else
                        <img src="https://source.unsplash.com/800x600/?agriculture,field" class="card-img-top" alt="Image par défaut" style="object-fit: cover; height: 100%; width: 100%;">
                    @endif
                    <div class="position-absolute top-0 end-0 p-2">
                        <span class="badge {{ $terre->status == 'disponible' ? 'bg-success' : ($terre->status == 'reserve' ? 'bg-warning' : 'bg-danger') }}">
                            {{ ucfirst($terre->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $terre->titre }}</h5>
                    <p class="card-text text-muted mb-1">{{ $terre->surface }} hectares - {{ $terre->region }}, {{ $terre->pays }}</p>
                    <h6 class="fw-bold text-primary mb-3">{{ number_format($terre->prix, 0, ',', ' ') }} €</h6>
                    <p class="card-text">{{ Str::limit($terre->description, 100) }}</p>
                </div>
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                    <a href="{{ route('terres.show', $terre) }}" class="btn btn-outline-primary">Voir les détails</a>
                    @auth
                        @if(auth()->user()->isAdmin() || (auth()->user()->isFournisseur() && $terre->annonce && $terre->annonce->fournisseur_id == auth()->user()->fournisseur->id))
                            <a href="{{ route('terres.edit', $terre) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                Aucune terre agricole ne correspond à vos critères de recherche.
            </div>
        </div>
    @endforelse
</div>

<div class="row">
    <div class="col-12">
        {{ $terresAgricoles->appends(request()->query())->links() }}
    </div>
</div>
@endsection