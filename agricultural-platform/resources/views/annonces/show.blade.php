@extends('layouts.app')

@section('title', $annonce->titre)

@section('content')
<div class="row mb-4">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('annonces.index') }}">Annonces</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $annonce->titre }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">{{ $annonce->titre }}</h1>
                <span class="badge {{ $annonce->estActive ? 'bg-success' : 'bg-secondary' }}">
                    {{ $annonce->estActive ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    @if($annonce->terreAgricole->photos && count($annonce->terreAgricole->photos) > 0)
                        <div id="carouselAnnonce" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach($annonce->terreAgricole->photos as $key => $photo)
                                    <button type="button" data-bs-target="#carouselAnnonce" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $key + 1 }}"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach($annonce->terreAgricole->photos as $key => $photo)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $photo) }}" class="d-block w-100" alt="Photo {{ $key + 1 }}" style="height: 400px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselAnnonce" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Précédent</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselAnnonce" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Suivant</span>
                            </button>
                        </div>
                    @else
                        <img src="https://source.unsplash.com/1200x800/?agriculture,field" class="img-fluid rounded" alt="Image par défaut">
                    @endif
                </div>

                <h5 class="mb-3">Description de l'annonce</h5>
                <p class="mb-4">{{ $annonce->description }}</p>

                <div class="alert alert-light border">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong><i class="fas fa-calendar-alt me-2"></i> Date de publication :</strong> {{ $annonce->dateCreation->format('d/m/Y') }}</p>
                            <p class="mb-1"><strong><i class="fas fa-map-marker-alt me-2"></i> Localisation :</strong> {{ $annonce->terreAgricole->region }}, {{ $annonce->terreAgricole->pays }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong><i class="fas fa-user me-2"></i> Propriétaire :</strong> {{ $annonce->fournisseur->entreprise }}</p>
                            <p class="mb-1"><strong><i class="fas fa-tag me-2"></i> Statut de la terre :</strong> 
                                <span class="badge {{ $annonce->terreAgricole->status == 'disponible' ? 'bg-success' : ($annonce->terreAgricole->status == 'reserve' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($annonce->terreAgricole->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3 mt-4">Détails de la terre agricole</h5>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-ruler-combined me-2"></i> Surface</span>
                                <span class="fw-bold">{{ $annonce->terreAgricole->surface }} hectares</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-layer-group me-2"></i> Type de sol</span>
                                <span class="fw-bold">{{ $annonce->terreAgricole->typeSol ?? 'Non spécifié' }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-euro-sign me-2"></i> Prix</span>
                                <span class="fw-bold">{{ number_format($annonce->terreAgricole->prix, 0, ',', ' ') }} €</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-calculator me-2"></i> Prix par hectare</span>
                                <span class="fw-bold">{{ number_format($annonce->terreAgricole->prix / $annonce->terreAgricole->surface, 0, ',', ' ') }} €/ha</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <a href="{{ route('terres.show', $annonce->terreAgricole) }}" class="btn btn-outline-primary">
                        <i class="fas fa-info-circle me-1"></i> Détails complets de la terre
                    </a>
                    
                    @auth
                        @if(auth()->user()->isClient() && $annonce->terreAgricole->status == 'disponible')
                            <form action="{{ route('panier.add-terre') }}" method="POST">
                                @csrf
                                <input type="hidden" name="terre_id" value="{{ $annonce->terreAgricole->id }}">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-shopping-cart me-1"></i> Ajouter au panier
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>

                @if($annonce->historiqueAnnonces && $annonce->historiqueAnnonces->count() > 0)
                    <h5 class="mb-3">Historique des modifications</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Modification</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($annonce->historiqueAnnonces->sortByDesc('dateModification') as $historique)
                                    <tr>
                                        <td>{{ $historique->dateModification->format('d/m/Y') }}</td>
                                        <td>{{ $historique->modification }}</td>
                                        <td>{{ $historique->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @auth
                        @if(auth()->user()->isClient())
                            @if($annonce->terreAgricole->status == 'disponible')
                                <form action="{{ route('panier.add-terre') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="terre_id" value="{{ $annonce->terreAgricole->id }}">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-shopping-cart me-2"></i> Ajouter au panier
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('annonces.toggle-favorite', $annonce) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    @if(auth()->user()->client->favoris->contains('annonce_id', $annonce->id))
                                        <i class="fas fa-heart me-2"></i> Retirer des favoris
                                    @else
                                        <i class="far fa-heart me-2"></i> Ajouter aux favoris
                                    @endif
                                </button>
                            </form>
                            
                            <a href="{{ route('client.messages.conversation', $annonce->fournisseur->id) }}" class="btn btn-outline-info">
                                <i class="fas fa-envelope me-2"></i> Contacter le fournisseur
                            </a>
                        @elseif(auth()->user()->isFournisseur() && auth()->user()->fournisseur->id == $annonce->fournisseur_id)
                            <a href="{{ route('fournisseur.annonces.edit', $annonce) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i> Modifier l'annonce
                            </a>
                            <form action="{{ route('fournisseur.annonces.destroy', $annonce) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash-alt me-2"></i> Supprimer l'annonce
                                </button>
                            </form>
                        @elseif(auth()->user()->isAdmin())
                            <a href="{{ route('annonces.edit', $annonce) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i> Modifier l'annonce
                            </a>
                            <form action="{{ route('annonces.destroy', $annonce) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash-alt me-2"></i> Supprimer l'annonce
                                </button>
                            </form>
                        @endif
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i> <a href="{{ route('login') }}">Connectez-vous</a> pour ajouter cette terre au panier ou la mettre en favoris.
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Fournisseur</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <span class="h4 mb-0">{{ substr($annonce->fournisseur->utilisateur->prenom, 0, 1) }}{{ substr($annonce->fournisseur->utilisateur->nom, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0">{{ $annonce->fournisseur->entreprise }}</h5>
                        <p class="text-muted mb-0">{{ $annonce->fournisseur->utilisateur->prenom }} {{ $annonce->fournisseur->utilisateur->nom }}</p>
                    </div>
                </div>
                <hr>
                <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> {{ $annonce->fournisseur->adresseSiege }}</p>
                <p class="mb-2"><i class="fas fa-id-card me-2"></i> SIRET: {{ $annonce->fournisseur->siret }}</p>
                @if($annonce->fournisseur->utilisateur->telephone)
                    <p class="mb-0"><i class="fas fa-phone me-2"></i> {{ $annonce->fournisseur->utilisateur->telephone }}</p>
                @endif
                
                @if(isset($averageRating) && $averageRating > 0)
                    <hr>
                    <div class="mb-2">
                        <div class="d-flex align-items-center">
                            <div class="text-warning me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $averageRating)
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $averageRating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span>{{ number_format($averageRating, 1) }}/5</span>
                        </div>
                        <small class="text-muted">Basé sur {{ $ratingCount ?? 0 }} avis</small>
                    </div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Localisation</h5>
            </div>
            <div class="card-body p-0">
                @if($annonce->terreAgricole->coordonneesGPS)
                    <div id="map" style="height: 300px;"></div>
                @else
                    <div class="alert alert-info m-3 mb-0">
                        Aucune coordonnée GPS disponible pour cette terre agricole.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(auth()->check() && (auth()->user()->isAdmin() || (auth()->user()->isFournisseur() && $annonce->fournisseur_id == auth()->user()->fournisseur->id)))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Actions administratives</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <a href="{{ route('annonces.edit', $annonce) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Modifier l'annonce
                        </a>
                        <a href="{{ route('terres.edit', $annonce->terreAgricole) }}" class="btn btn-info">
                            <i class="fas fa-edit me-2"></i> Modifier la terre
                        </a>
                        <form action="{{ route('annonces.destroy', $annonce) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt me-2"></i> Supprimer l'annonce
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
@if($annonce->terreAgricole->coordonneesGPS)
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Parse coordinates (format: "latitude,longitude")
            const coords = "{{ $annonce->terreAgricole->coordonneesGPS }}".split(',');
            const lat = parseFloat(coords[0]);
            const lng = parseFloat(coords[1]);
            
            if(!isNaN(lat) && !isNaN(lng)) {
                // Initialize map
                const map = L.map('map').setView([lat, lng], 13);
                
                // Add OpenStreetMap tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                
                // Add marker
                L.marker([lat, lng]).addTo(map)
                    .bindPopup('<strong>{{ $annonce->titre }}</strong><br>{{ $annonce->terreAgricole->surface }} hectares')
                    .openPopup();
            }
        });
    </script>
@endif
@endsection