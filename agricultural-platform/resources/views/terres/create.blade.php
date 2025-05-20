@extends('layouts.app')

@section('title', 'Créer une Terre Agricole')

@section('content')
<div class="row mb-4">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('terres.index') }}">Terres Agricoles</a></li>
                <li class="breadcrumb-item active" aria-current="page">Créer une Terre Agricole</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h1 class="h3 mb-0">Créer une Terre Agricole</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('terres.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre') }}" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="typeSol" class="form-label">Type de sol</label>
                            <select class="form-select @error('typeSol') is-invalid @enderror" id="typeSol" name="typeSol">
                                <option value="">Sélectionnez un type de sol</option>
                                <option value="argileux" {{ old('typeSol') == 'argileux' ? 'selected' : '' }}>Argileux</option>
                                <option value="limoneux" {{ old('typeSol') == 'limoneux' ? 'selected' : '' }}>Limoneux</option>
                                <option value="sableux" {{ old('typeSol') == 'sableux' ? 'selected' : '' }}>Sableux</option>
                                <option value="calcaire" {{ old('typeSol') == 'calcaire' ? 'selected' : '' }}>Calcaire</option>
                                <option value="humifere" {{ old('typeSol') == 'humifere' ? 'selected' : '' }}>Humifère</option>
                                <option value="mixte" {{ old('typeSol') == 'mixte' ? 'selected' : '' }}>Mixte</option>
                            </select>
                            @error('typeSol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="surface" class="form-label">Surface (hectares)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('surface') is-invalid @enderror" id="surface" name="surface" value="{{ old('surface') }}" required>
                            @error('surface')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="prix" class="form-label">Prix (€)</label>
                            <input type="number" min="0" class="form-control @error('prix') is-invalid @enderror" id="prix" name="prix" value="{{ old('prix') }}" required>
                            @error('prix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="region" class="form-label">Région</label>
                            <select class="form-select @error('region') is-invalid @enderror" id="region" name="region" required>
                                <option value="">Sélectionnez une région</option>
                                <option value="Auvergne-Rhône-Alpes" {{ old('region') == 'Auvergne-Rhône-Alpes' ? 'selected' : '' }}>Auvergne-Rhône-Alpes</option>
                                <option value="Bourgogne-Franche-Comté" {{ old('region') == 'Bourgogne-Franche-Comté' ? 'selected' : '' }}>Bourgogne-Franche-Comté</option>
                                <option value="Bretagne" {{ old('region') == 'Bretagne' ? 'selected' : '' }}>Bretagne</option>
                                <option value="Centre-Val de Loire" {{ old('region') == 'Centre-Val de Loire' ? 'selected' : '' }}>Centre-Val de Loire</option>
                                <option value="Corse" {{ old('region') == 'Corse' ? 'selected' : '' }}>Corse</option>
                                <option value="Grand Est" {{ old('region') == 'Grand Est' ? 'selected' : '' }}>Grand Est</option>
                                <option value="Hauts-de-France" {{ old('region') == 'Hauts-de-France' ? 'selected' : '' }}>Hauts-de-France</option>
                                <option value="Île-de-France" {{ old('region') == 'Île-de-France' ? 'selected' : '' }}>Île-de-France</option>
                                <option value="Normandie" {{ old('region') == 'Normandie' ? 'selected' : '' }}>Normandie</option>
                                <option value="Nouvelle-Aquitaine" {{ old('region') == 'Nouvelle-Aquitaine' ? 'selected' : '' }}>Nouvelle-Aquitaine</option>
                                <option value="Occitanie" {{ old('region') == 'Occitanie' ? 'selected' : '' }}>Occitanie</option>
                                <option value="Pays de la Loire" {{ old('region') == 'Pays de la Loire' ? 'selected' : '' }}>Pays de la Loire</option>
                                <option value="Provence-Alpes-Côte d'Azur" {{ old('region') == 'Provence-Alpes-Côte d\'Azur' ? 'selected' : '' }}>Provence-Alpes-Côte d'Azur</option>
                            </select>
                            @error('region')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="pays" class="form-label">Pays</label>
                            <select class="form-select @error('pays') is-invalid @enderror" id="pays" name="pays" required>
                                <option value="France" {{ old('pays') == 'France' ? 'selected' : '' }}>France</option>
                                <option value="Belgique" {{ old('pays') == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                                <option value="Suisse" {{ old('pays') == 'Suisse' ? 'selected' : '' }}>Suisse</option>
                                <option value="Luxembourg" {{ old('pays') == 'Luxembourg' ? 'selected' : '' }}>Luxembourg</option>
                                <option value="Espagne" {{ old('pays') == 'Espagne' ? 'selected' : '' }}>Espagne</option>
                                <option value="Italie" {{ old('pays') == 'Italie' ? 'selected' : '' }}>Italie</option>
                                <option value="Allemagne" {{ old('pays') == 'Allemagne' ? 'selected' : '' }}>Allemagne</option>
                            </select>
                            @error('pays')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="coordonneesGPS" class="form-label">Coordonnées GPS (latitude,longitude)</label>
                        <input type="text" class="form-control @error('coordonneesGPS') is-invalid @enderror" id="coordonneesGPS" name="coordonneesGPS" value="{{ old('coordonneesGPS') }}" placeholder="Ex: 48.8566,2.3522">
                        <small class="form-text text-muted">Format: latitude,longitude (ex: 48.8566,2.3522 pour Paris)</small>
                        @error('coordonneesGPS')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="zoomLevel" class="form-label">Niveau de zoom sur la carte</label>
                            <input type="number" min="1" max="18" class="form-control @error('zoomLevel') is-invalid @enderror" id="zoomLevel" name="zoomLevel" value="{{ old('zoomLevel', 12) }}">
                            <small class="form-text text-muted">Valeur entre 1 (vue mondiale) et 18 (vue très rapprochée)</small>
                            @error('zoomLevel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="typeMap" class="form-label">Type de carte</label>
                            <select class="form-select @error('typeMap') is-invalid @enderror" id="typeMap" name="typeMap">
                                <option value="satellite" {{ old('typeMap') == 'satellite' ? 'selected' : '' }}>Satellite</option>
                                <option value="terrain" {{ old('typeMap') == 'terrain' ? 'selected' : '' }}>Terrain</option>
                                <option value="hybrid" {{ old('typeMap') == 'hybrid' ? 'selected' : '' }}>Hybride</option>
                            </select>
                            @error('typeMap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="photos" class="form-label">Photos</label>
                        <input type="file" class="form-control @error('photos.*') is-invalid @enderror" id="photos" name="photos[]" multiple accept="image/*">
                        <small class="form-text text-muted">Vous pouvez sélectionner plusieurs photos (formats: JPEG, PNG, GIF)</small>
                        @error('photos.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('terres.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Créer la terre agricole</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection