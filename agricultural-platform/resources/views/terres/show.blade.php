@extends('layouts.app')

@section('title', 'Détails Terre Agricole')

@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <style>
        #map {
            height: 400px;
        }
        .carousel-item img {
            height: 400px;
            object-fit: cover;
        }
    </style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('terres.index') }}">Terres Agricoles</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $terre->titre }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">{{ $terre->titre }}</h1>
                <span class="badge {{ $terre->status == 'disponible' ? 'bg-success' : ($terre->status == 'reserve' ? 'bg-warning' : 'bg-danger') }}">
                    {{ ucfirst($terre->status) }}
                </span>
            </div>
            <div class="card-body">
                @if($terre->photos && count($terre->photos) > 0)
                    <div id="carouselTerre" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($terre->photos as $key => $photo)
                                <button type="button" data-bs-target="#carouselTerre" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $key + 1 }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach($terre->photos as $key => $photo)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $photo) }}" class="d-block w-100" alt="Photo {{ $key + 1 }}">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselTerre" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Précédent</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselTerre" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Suivant</span>
                        </button>
                    </div>
                @else
                    <div class="mb-4">
                        <img src="https://source.unsplash.com/1200x800/?agriculture,field" class="img-fluid" alt="Image par défaut">
                    </div>
                @endif

                <h5 class="mb-3">Description</h5>
                <p>{{ $terre->description }}</p>

                <h5 class="mb-3 mt-4">Caractéristiques</h5>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-ruler-combined me-2"></i> Surface</span>
                                <span class="fw-bold">{{ $terre->surface }} hectares</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-map-marker-alt me-2"></i> Région</span>
                                <span class="fw-bold">{{ $terre->region }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-flag me-2"></i> Pays</span>
                                <span class="fw-bold">{{ $terre->pays }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-euro-sign me-2"></i> Prix</span>
                                <span class="fw-bold">{{ number_format($terre->prix, 0, ',', ' ') }} €</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-layer-group me-2"></i> Type de sol</span>
                                <span class="fw-bold">{{ $terre->typeSol ?? 'Non spécifié' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-calendar-alt me-2"></i> Date de création</span>
                                <span class="fw-bold">{{ $terre->created_at->format('d/m/Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

               @if($terre->systemCartographie && $terre->coordonneesGPS)
    <h5 class="mb-3 mt-4">Localisation</h5>
    <div id="map" class="mb-3 border rounded" style="height: 400px;"></div>
    <div class="mb-3">
        <div class="row g-2">
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="mapType" id="mapTypeSatellite" value="satellite" checked>
                    <label class="form-check-label" for="mapTypeSatellite">
                        Vue satellite
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="mapType" id="mapTypeStreet" value="street">
                    <label class="form-check-label" for="mapTypeStreet">
                        Vue rue
                    </label>
                </div>
            </div>
        </div>
    </div>
@endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Annonce</h5>
            </div>
            <div class="card-body">
                @if($terre->annonce)
                    <h4 class="card-title">{{ $terre->annonce->titre }}</h4>
                    <p class="card-text">{{ Str::limit($terre->annonce->description, 150) }}</p>
                    <p class="mb-0"><strong>Date de publication:</strong> {{ $terre->annonce->dateCreation->format('d/m/Y') }}</p>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Statut:</span>
                        @if($terre->annonce->estActive)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('annonces.show', $terre->annonce) }}" class="btn btn-primary">Voir l'annonce</a>
                        
                        @auth
                            @if(auth()->user()->isClient() && $terre->status == 'disponible')
                                <form action="{{ route('panier.add-terre') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="terre_id" value="{{ $terre->id }}">
                                    <button type="submit" class="btn btn-success w-100">Ajouter au panier</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        Aucune annonce n'est associée à cette terre agricole.
                        @auth
                            @if(auth()->user()->isFournisseur())
                                <div class="mt-3">
                                    <a href="{{ route('fournisseur.annonces.create') }}" class="btn btn-primary btn-sm">Créer une annonce</a>
                                </div>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>

        @if($terre->annonce && $terre->annonce->fournisseur)
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Fournisseur</h5>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $terre->annonce->fournisseur->entreprise }}</h5>
                    <p class="card-text"><i class="fas fa-user me-2"></i> {{ $terre->annonce->fournisseur->utilisateur->prenom }} {{ $terre->annonce->fournisseur->utilisateur->nom }}</p>
                    <p class="card-text"><i class="fas fa-map-marker-alt me-2"></i> {{ $terre->annonce->fournisseur->adresseSiege }}</p>
                    <hr>
                    @auth
                        @if(auth()->user()->isClient())
                            <div class="d-grid gap-2">
                                <a href="{{ route('client.messages.conversation', $terre->annonce->fournisseur->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-envelope me-2"></i> Contacter le fournisseur
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i> Connectez-vous en tant que client pour contacter le fournisseur.
                        </div>
                    @endauth
                </div>
            </div>
        @endif
    </div>
</div>

@if(auth()->check() && (auth()->user()->isAdmin() || (auth()->user()->isFournisseur() && $terre->annonce && $terre->annonce->fournisseur_id == auth()->user()->fournisseur->id)))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Actions administratives</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <a href="{{ route('terres.edit', $terre) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Modifier la terre
                        </a>
                        @if($terre->annonce)
                            <a href="{{ route('annonces.edit', $terre->annonce) }}" class="btn btn-info">
                                <i class="fas fa-edit me-2"></i> Modifier l'annonce
                            </a>
                        @else
                            <a href="{{ route('fournisseur.annonces.create') }}" class="btn btn-success">
                                <i class="fas fa-plus-circle me-2"></i> Créer une annonce
                            </a>
                        @endif
                        <form action="{{ route('terres.destroy', $terre) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette terre agricole ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt me-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@section('scripts')
       @if($terre->systemCartographie && $terre->coordonneesGPS)
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Parse coordinates (format: "latitude,longitude")
                const coords = "{{ $terre->coordonneesGPS }}".split(',');
                const lat = parseFloat(coords[0]);
                const lng = parseFloat(coords[1]);
                
                if(!isNaN(lat) && !isNaN(lng)) {
                    // Initialize map
                    const map = L.map('map').setView([lat, lng], {{ $terre->systemCartographie->zoomLevel ?? 13 }});
                    
                    // Default tile layer (satellite)
                    let satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
                    }).addTo(map);
                    
                    // Street layer
                    let streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    });
                    
                    // Add marker
                    const marker = L.marker([lat, lng]).addTo(map)
                        .bindPopup('<strong>{{ $terre->titre }}</strong><br>{{ $terre->surface }} hectares')
                        .openPopup();
                    
                    // Add area polygon if available (simulated here)
                    // In a real app, you would get polygon coordinates from the database
                    const radius = Math.sqrt({{ $terre->surface }} * 10000 / Math.PI); // Rough estimate for circle radius in meters
                    const circle = L.circle([lat, lng], {
                        radius: radius,
                        color: '#2c7d43',
                        fillColor: '#2c7d43',
                        fillOpacity: 0.2
                    }).addTo(map);
                     // Map type toggle
                        const mapTypeRadios = document.querySelectorAll('input[name="mapType"]');
                        mapTypeRadios.forEach(radio => {
                            radio.addEventListener('change', function() {
                                if (this.value === 'satellite') {
                                    map.removeLayer(streetLayer);
                                    satelliteLayer.addTo(map);
                                } else if (this.value === 'street') {
                                    map.removeLayer(satelliteLayer);
                                    streetLayer.addTo(map);
                                }
                            });
                        });
                    }
                });
            </script>
        @endif
                    
@endsection