@extends('layouts.app')

@section('title', 'Modifier Terre Agricole')

@section('content')
<div class="row mb-4">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('terres.index') }}">Terres Agricoles</a></li>
                <li class="breadcrumb-item"><a href="{{ route('terres.show', $terre) }}">{{ $terre->titre }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Modifier</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h1 class="h3 mb-0">Modifier la Terre Agricole</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('terres.update', $terre) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre', $terre->titre) }}" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="typeSol" class="form-label">Type de sol</label>
                            <select class="form-select @error('typeSol') is-invalid @enderror" id="typeSol" name="typeSol">
                                <option value="">Sélectionnez un type de sol</option>
                                <option value="argileux" {{ old('typeSol', $terre->typeSol) == 'argileux' ? 'selected' : '' }}>Argileux</option>
                                <option value="limoneux" {{ old('typeSol', $terre->typeSol) == 'limoneux' ? 'selected' : '' }}>Limoneux</option>
                                <option value="sableux" {{ old('typeSol', $terre->typeSol) == 'sableux' ? 'selected' : '' }}>Sableux</option>
                                <option value="calcaire" {{ old('typeSol', $terre->typeSol) == 'calcaire' ? 'selected' : '' }}>Calcaire</option>
                                <option value="humifere" {{ old('typeSol', $terre->typeSol) == 'humifere' ? 'selected' : '' }}>Humifère</option>
                                <option value="mixte" {{ old('typeSol', $terre->typeSol) == 'mixte' ? 'selected' : '' }}>Mixte</option>
                            </select>
                            @error('typeSol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $terre->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="surface" class="form-label">Surface (hectares)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('surface') is-invalid @enderror" id="surface" name="surface" value="{{ old('surface', $terre->surface) }}" required>
                            @error('surface')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="prix" class="form-label">Prix (€)</label>
                            <input type="number" min="0" class="form-control @error('prix') is-invalid @enderror" id="prix" name="prix" value="{{ old('prix', $terre->prix) }}" required>
                            @error('prix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="region" class="form-label">Région</label>
                            <select class="form-select @error('region') is-invalid @enderror" id="region" name="region" required>
                                <option value="">Sélectionnez une région</option>
                                <option value="Auvergne-Rhône-Alpes" {{ old('region', $terre->region) == 'Auvergne-Rhône-Alpes' ? 'selected' : '' }}>Auvergne-Rhône-Alpes</option>
                                <option value="Bourgogne-Franche-Comté" {{ old('region', $terre->region) == 'Bourgogne-Franche-Comté' ? 'selected' : '' }}>Bourgogne-Franche-Comté</option>
                                <option value="Bretagne" {{ old('region', $terre->region) == 'Bretagne' ? 'selected' : '' }}>Bretagne</option>
                                <option value="Centre-Val de Loire" {{ old('region', $terre->region) == 'Centre-Val de Loire' ? 'selected' : '' }}>Centre-Val de Loire</option>
                                <option value="Corse" {{ old('region', $terre->region) == 'Corse' ? 'selected' : '' }}>Corse</option>
                                <option value="Grand Est" {{ old('region', $terre->region) == 'Grand Est' ? 'selected' : '' }}>Grand Est</option>
                                <option value="Hauts-de-France" {{ old('region', $terre->region) == 'Hauts-de-France' ? 'selected' : '' }}>Hauts-de-France</option>
                                <option value="Île-de-France" {{ old('region', $terre->region) == 'Île-de-France' ? 'selected' : '' }}>Île-de-France</option>
                                <option value="Normandie" {{ old('region', $terre->region) == 'Normandie' ? 'selected' : '' }}>Normandie</option>
                                <option value="Nouvelle-Aquitaine" {{ old('region', $terre->region) == 'Nouvelle-Aquitaine' ? 'selected' : '' }}>Nouvelle-Aquitaine</option>
                                <option value="Occitanie" {{ old('region', $terre->region) == 'Occitanie' ? 'selected' : '' }}>Occitanie</option>
                                <option value="Pays de la Loire" {{ old('region', $terre->region) == 'Pays de la Loire' ? 'selected' : '' }}>Pays de la Loire</option>
                                <option value="Provence-Alpes-Côte d'Azur" {{ old('region', $terre->region) == 'Provence-Alpes-Côte d\'Azur' ? 'selected' : '' }}>Provence-Alpes-Côte d'Azur</option>
                            </select>
                            @error('region')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="pays" class="form-label">Pays</label>
                            <select class="form-select @error('pays') is-invalid @enderror" id="pays" name="pays" required>
                                <option value="France" {{ old('pays', $terre->pays) == 'France' ? 'selected' : '' }}>France</option>
                                <option value="Belgique" {{ old('pays', $terre->pays) == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                                <option value="Suisse" {{ old('pays', $terre->pays) == 'Suisse' ? 'selected' : '' }}>Suisse</option>
                                <option value="Luxembourg" {{ old('pays', $terre->pays) == 'Luxembourg' ? 'selected' : '' }}>Luxembourg</option>
                                <option value="Espagne" {{ old('pays', $terre->pays) == 'Espagne' ? 'selected' : '' }}>Espagne</option>
                                <option value="Italie" {{ old('pays', $terre->pays) == 'Italie' ? 'selected' : '' }}>Italie</option>
                                <option value="Allemagne" {{ old('pays', $terre->pays) == 'Allemagne' ? 'selected' : '' }}>Allemagne</option>
                            </select>
                            @error('pays')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="disponible" {{ old('status', $terre->status) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="reserve" {{ old('status', $terre->status) == 'reserve' ? 'selected' : '' }}>Réservé</option>
                                <option value="vendu" {{ old('status', $terre->status) == 'vendu' ? 'selected' : '' }}>Vendu</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="coordonneesGPS" class="form-label">Coordonnées GPS (latitude,longitude)</label>
                        <input type="text" class="form-control @error('coordonneesGPS') is-invalid @enderror" id="coordonneesGPS" name="coordonneesGPS" value="{{ old('coordonneesGPS', $terre->coordonneesGPS) }}" placeholder="Ex: 48.8566,2.3522">
                        <small class="form-text text-muted">Format: latitude,longitude (ex: 48.8566,2.3522 pour Paris)</small>
                        @error('coordonneesGPS')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="zoomLevel" class="form-label">Niveau de zoom sur la carte</label>
                            <input type="number" min="1" max="18" class="form-control @error('zoomLevel') is-invalid @enderror" id="zoomLevel" name="zoomLevel" value="{{ old('zoomLevel', $terre->systemCartographie->zoomLevel ?? 12) }}">
                            <small class="form-text text-muted">Valeur entre 1 (vue mondiale) et 18 (vue très rapprochée)</small>
                            @error('zoomLevel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="typeMap" class="form-label">Type de carte</label>
                            <select class="form-select @error('typeMap') is-invalid @enderror" id="typeMap" name="typeMap">
                                <option value="satellite" {{ old('typeMap', $terre->systemCartographie->typeMap ?? 'satellite') == 'satellite' ? 'selected' : '' }}>Satellite</option>
                                <option value="terrain" {{ old('typeMap', $terre->systemCartographie->typeMap ?? 'terrain') == 'terrain' ? 'selected' : '' }}>Terrain</option>
                                <option value="hybrid" {{ old('typeMap', $terre->systemCartographie->typeMap ?? 'hybrid') == 'hybrid' ? 'selected' : '' }}>Hybride</option>
                            </select>
                            @error('typeMap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    @if($terre->photos && count($terre->photos) > 0)
                        <div class="mb-4">
                            <label class="form-label">Photos existantes</label>
                            <div class="row">
                                @foreach($terre->photos as $index => $photo)
                                    <div class="col-md-3 mb-3">
                                        <div class="card">
                                            <img src="{{ asset('storage/' . $photo) }}" class="card-img-top" alt="Photo {{ $index + 1 }}" style="height: 150px; object-fit: cover;">
                                            <div class="card-body text-center">
                                                <button type="button" class="btn btn-sm btn-danger photo-delete" data-index="{{ $index }}" data-bs-toggle="modal" data-bs-target="#deletePhotoModal">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="photos" class="form-label">Ajouter de nouvelles photos</label>
                        <input type="file" class="form-control @error('photos.*') is-invalid @enderror" id="photos" name="photos[]" multiple accept="image/*">
                        <small class="form-text text-muted">Vous pouvez sélectionner plusieurs photos (formats: JPEG, PNG, GIF)</small>
                        @error('photos.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('terres.show', $terre) }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour la suppression de photo -->
<div class="modal fade" id="deletePhotoModal" tabindex="-1" aria-labelledby="deletePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePhotoModalLabel">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cette photo ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deletePhotoForm" action="{{ route('fournisseur.terres.remove-photo', $terre) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="photo_index" id="photoIndexInput" value="">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestionnaire pour les boutons de suppression de photo
        const photoDeleteButtons = document.querySelectorAll('.photo-delete');
        photoDeleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const photoIndex = this.getAttribute('data-index');
                document.getElementById('photoIndexInput').value = photoIndex;
            });
        });
    });
</script>
@endsection