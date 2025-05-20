@extends('layouts.app')

@section('title', 'Liste des Annonces')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h3">Liste des Annonces</h1>
    </div>
    <div class="col text-end">
        @auth
            @if(auth()->user()->isFournisseur())
                <a href="{{ route('fournisseur.annonces.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Créer une annonce
                </a>
            @endif
        @endauth
    </div>
</div>

<div class="row">
    @forelse($annonces as $annonce)
        <div class="col-md-4 mb-4">
            <div class="card h-100 hover-scale">
                <div style="height: 200px; overflow: hidden;">
                    @if($annonce->terreAgricole->photos && count($annonce->terreAgricole->photos) > 0)
                        <img src="{{ asset('storage/' . $annonce->terreAgricole->photos[0]) }}" class="card-img-top" alt="{{ $annonce->titre }}" style="object-fit: cover; height: 100%; width: 100%;">
                    @else
                        <img src="https://source.unsplash.com/800x600/?agriculture,field" class="card-img-top" alt="Image par défaut" style="object-fit: cover; height: 100%; width: 100%;">
                    @endif
                    <div class="position-absolute top-0 end-0 p-2">
                        <span class="badge {{ $annonce->terreAgricole->status == 'disponible' ? 'bg-success' : ($annonce->terreAgricole->status == 'reserve' ? 'bg-warning' : 'bg-danger') }}">
                            {{ ucfirst($annonce->terreAgricole->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $annonce->titre }}</h5>
                    <p class="card-text text-muted mb-1">
                        <i class="fas fa-map-marker-alt me-1"></i> {{ $annonce->terreAgricole->region }}, {{ $annonce->terreAgricole->pays }}
                    </p>
                    <p class="card-text text-muted mb-1">
                        <i class="fas fa-ruler-combined me-1"></i> {{ $annonce->terreAgricole->surface }} hectares
                    </p>
                    <h6 class="fw-bold text-primary mb-3">{{ number_format($annonce->terreAgricole->prix, 0, ',', ' ') }} €</h6>
                    <p class="card-text">{{ Str::limit($annonce->description, 100) }}</p>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt me-1"></i> {{ $annonce->dateCreation->format('d/m/Y') }}
                        </small>
                    </div>
                    <a href="{{ route('annonces.show', $annonce) }}" class="btn btn-outline-primary">Voir l'annonce</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                Aucune annonce disponible pour le moment.
            </div>
        </div>
    @endforelse
</div>

<div class="row">
    <div class="col-12">
        {{ $annonces->links() }}
    </div>
</div>
@endsection