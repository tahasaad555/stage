@extends('layouts.app')

@section('title', 'Résultats de recherche')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h3">Résultats de recherche</h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Filtres de recherche</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('terres.search') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="keyword" class="form-label">Mot-clé</label>
                            <input type="text" class="form-control" id="keyword" name="keyword" value="{{ request('keyword') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="region" class="form-label">Région</label>
                            <select class="form-select" id="region" name="region">
                                <option value="">Toutes les régions</option>
                                <option value="Auvergne-Rhône-Alpes" {{ request('region') == 'Auvergne-Rhône-Alpes' ? 'selected' : '' }}>Auvergne-Rhône-Alpes</option>
                                <option value="Bourgogne-Franche-Comté" {{ request('region') == 'Bourgogne-Franche-Comté' ? 'selected' : '' }}>Bourgogne-Franche-Comté</option>
                                <option value="Bretagne" {{ request('region') == 'Bretagne' ? 'selected' : '' }}>Bretagne</option>
                                <!-- Add more regions -->
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
                        <div class="col-md-2 d-flex align-items-end">
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
                <div style="height: 200px; overflow: hidden;">
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
                <div class="card-footer bg-white border-top-0">
                    <a href="{{ route('terres.show', $terre) }}" class="btn btn-outline-primary w-100">Voir les détails</a>
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