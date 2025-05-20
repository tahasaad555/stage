@extends('layouts.app')

@section('title', 'Modifier l\'annonce')

@section('content')
<div class="row mb-4">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('annonces.index') }}">Annonces</a></li>
                <li class="breadcrumb-item"><a href="{{ route('annonces.show', $annonce) }}">{{ $annonce->titre }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Modifier</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h1 class="h3 mb-0">Modifier l'annonce</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('annonces.update', $annonce) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre de l'annonce</label>
                        <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre', $annonce->titre) }}" required>
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description de l'annonce</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="6" required>{{ old('description', $annonce->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="estActive" name="estActive" value="1" {{ $annonce->estActive ? 'checked' : '' }}>
                        <label class="form-check-label" for="estActive">Annonce active</label>
                        <small class="form-text text-muted d-block">Décochez cette case pour désactiver temporairement votre annonce.</small>
                    </div>
                    
                    <div class="mb-4">
                        <div class="alert alert-info">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h5 class="alert-heading">Informations sur la terre agricole</h5>
                                    <p class="mb-0">Cette annonce est associée à la terre agricole : <strong>{{ $annonce->terreAgricole->titre }}</strong></p>
                                    <p class="mb-0">Pour modifier les détails de la terre (prix, surface, caractéristiques, etc.), veuillez <a href="{{ route('terres.edit', $annonce->terreAgricole) }}" class="alert-link">cliquer ici</a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('annonces.show', $annonce) }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection