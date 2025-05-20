@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="container-fluid p-0">
    <!-- Hero Section -->
    <div class="position-relative">
        <div class="bg-image" style="background-image: url('https://source.unsplash.com/1600x900/?agriculture,farm'); height: 500px; background-size: cover; background-position: center;"></div>
        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background-color: rgba(0,0,0,0.5);">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 text-white">
                        <h1 class="display-4 fw-bold mb-4">Trouvez votre Terre Agricole Idéale</h1>
                        <p class="lead mb-4">La plateforme française dédiée aux agriculteurs et aux investisseurs dans le domaine agricole.</p>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                            <a href="{{ route('terres.index') }}" class="btn btn-primary btn-lg px-4 me-md-2">Parcourir les terres</a>
                            <a href="{{ route('annonces.index') }}" class="btn btn-outline-light btn-lg px-4">Voir les annonces</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <!-- Recherche rapide -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h4 class="text-center mb-4">Recherche rapide de terres agricoles</h4>
                    <form action="{{ route('terres.search') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="region" class="form-label">Région</label>
                                <select class="form-select" id="region" name="region">
                                    <option value="">Toutes les régions</option>
                                    <option value="Auvergne-Rhône-Alpes">Auvergne-Rhône-Alpes</option>
                                    <option value="Bourgogne-Franche-Comté">Bourgogne-Franche-Comté</option>
                                    <option value="Bretagne">Bretagne</option>
                                    <option value="Centre-Val de Loire">Centre-Val de Loire</option>
                                    <option value="Corse">Corse</option>
                                    <option value="Grand Est">Grand Est</option>
                                    <option value="Hauts-de-France">Hauts-de-France</option>
                                    <option value="Île-de-France">Île-de-France</option>
                                    <option value="Normandie">Normandie</option>
                                    <option value="Nouvelle-Aquitaine">Nouvelle-Aquitaine</option>
                                    <option value="Occitanie">Occitanie</option>
                                    <option value="Pays de la Loire">Pays de la Loire</option>
                                    <option value="Provence-Alpes-Côte d'Azur">Provence-Alpes-Côte d'Azur</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="surface_min" class="form-label">Surface minimum (ha)</label>
                                <input type="number" class="form-control" id="surface_min" name="surface_min" min="0" step="0.1">
                            </div>
                            <div class="col-md-3">
                                <label for="prix_max" class="form-label">Prix maximum (€)</label>
                                <input type="number" class="form-control" id="prix_max" name="prix_max" min="0">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Rechercher</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured listings -->
    <h2 class="mb-4">Annonces en vedette</h2>
    <div class="row">
        @forelse($featuredAnnonces as $annonce)
            <div class="col-md-4 mb-4">
                <div class="card h-100 hover-scale">
                    <div style="height: 200px; overflow: hidden;">
                        @if($annonce->terreAgricole->photos && count($annonce->terreAgricole->photos) > 0)
                            <img src="{{ asset('storage/' . $annonce->terreAgricole->photos[0]) }}" class="card-img-top" alt="{{ $annonce->titre }}" style="object-fit: cover; height: 100%; width: 100%;">
                        @else
                            <img src="https://source.unsplash.com/800x600/?agriculture,field" class="card-img-top" alt="Image par défaut" style="object-fit: cover; height: 100%; width: 100%;">
                        @endif
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $annonce->titre }}</h5>
                        <p class="card-text text-muted mb-1">{{ $annonce->terreAgricole->surface }} hectares - {{ $annonce->terreAgricole->region }}</p>
                        <h6 class="fw-bold text-primary mb-3">{{ number_format($annonce->terreAgricole->prix, 0, ',', ' ') }} €</h6>
                        <p class="card-text">{{ Str::limit($annonce->description, 100) }}</p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <a href="{{ route('annonces.show', $annonce) }}" class="btn btn-outline-primary w-100">Voir les détails</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Aucune annonce en vedette pour le moment.
                </div>
            </div>
        @endforelse
    </div>
    <div class="text-center mt-3 mb-5">
        <a href="{{ route('annonces.index') }}" class="btn btn-outline-primary">Voir toutes les annonces</a>
    </div>

    <!-- Services section -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-4">
            <h2>Nos services</h2>
            <p class="lead">Découvrez comment nous pouvons vous aider dans vos projets agricoles</p>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 text-center p-4 bg-light-green border-0">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="fas fa-search fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Recherche de terres</h5>
                    <p class="card-text">Trouvez facilement des terres agricoles correspondant à vos critères spécifiques.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 text-center p-4 bg-light-green border-0">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="fas fa-handshake fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Mise en relation</h5>
                    <p class="card-text">Nous vous mettons en contact avec les vendeurs et facilitons vos transactions.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 text-center p-4 bg-light-green border-0">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="fas fa-calculator fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Estimation de valeur</h5>
                    <p class="card-text">Obtenez une estimation précise de la valeur de vos terres agricoles.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Testimonials -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-4">
            <h2>Témoignages</h2>
            <p class="lead">Ce que nos utilisateurs disent de nous</p>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 p-4 shadow-sm">
                <div class="card-body">
                    <div class="mb-3 text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="card-text font-italic">"J'ai trouvé ma terre idéale en moins de deux semaines. Le système est facile à utiliser et les annonces sont détaillées."</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <span>JD</span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Jean Dupont</h6>
                            <small class="text-muted">Agriculteur, Bretagne</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 p-4 shadow-sm">
                <div class="card-body">
                    <div class="mb-3 text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <p class="card-text font-italic">"En tant que vendeur, j'ai pu mettre en avant mes terres et trouver rapidement un acheteur sérieux. La commission est raisonnable."</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <span>ML</span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Marie Lefevre</h6>
                            <small class="text-muted">Propriétaire, Occitanie</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 p-4 shadow-sm">
                <div class="card-body">
                    <div class="mb-3 text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="card-text font-italic">"Le service client est excellent et la plateforme est très intuitive. J'ai pu financer mon projet agricole sans difficulté."</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <span>PD</span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Pierre Dubois</h6>
                            <small class="text-muted">Jeune agriculteur, Normandie</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection