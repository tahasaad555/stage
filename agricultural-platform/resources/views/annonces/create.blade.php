@extends('layouts.app')

@section('title', 'Créer une Annonce')

@section('content')
<div class="row mb-4">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('annonces.index') }}">Annonces</a></li>
                <li class="breadcrumb-item active" aria-current="page">Créer une annonce</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h1 class="h3 mb-0">Créer une nouvelle annonce</h1>
            </div>
            <div class="card-body">
                @if($terresDisponibles->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Vous n'avez pas de terres agricoles disponibles pour créer une annonce.
                        <div class="mt-3">
                            <a href="{{ route('terres.create') }}" class="btn btn-primary">Ajouter une terre agricole</a>
                        </div>
                    </div>
                @else
                    <form action="{{ route('fournisseur.annonces.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="terre_agricole_id" class="form-label">Terre agricole</label>
                            <select class="form-select @error('terre_agricole_id') is-invalid @enderror" id="terre_agricole_id" name="terre_agricole_id" required>
                                <option value="">Sélectionnez une terre agricole</option>
                                @foreach($terresDisponibles as $terre)
                                    <option value="{{ $terre->id }}" {{ old('terre_agricole_id') == $terre->id ? 'selected' : '' }}>
                                        {{ $terre->titre }} - {{ $terre->surface }} hectares - {{ number_format($terre->prix, 0, ',', ' ') }} €
                                    </option>
                                @endforeach
                            </select>
                            @error('terre_agricole_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Sélectionnez la terre agricole que vous souhaitez mettre en vente.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre de l'annonce</label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre') }}" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Choisissez un titre accrocheur pour votre annonce.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description de l'annonce</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="6" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Fournissez une description détaillée de votre terre agricole et de ses avantages.</small>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('fournisseur.annonces') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Créer l'annonce</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection