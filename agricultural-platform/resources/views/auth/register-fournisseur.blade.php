@extends('layouts.app')

@section('title', 'Inscription Fournisseur')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Inscription Fournisseur</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register.fournisseur.submit') }}">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required autofocus>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="motDePasse" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control @error('motDePasse') is-invalid @enderror" id="motDePasse" name="motDePasse" required>
                            @error('motDePasse')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="motDePasse_confirmation" class="form-label">Confirmation du mot de passe</label>
                            <input type="password" class="form-control" id="motDePasse_confirmation" name="motDePasse_confirmation" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="tel" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone') }}">
                        @error('telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="entreprise" class="form-label">Nom de l'entreprise</label>
                        <input type="text" class="form-control @error('entreprise') is-invalid @enderror" id="entreprise" name="entreprise" value="{{ old('entreprise') }}" required>
                        @error('entreprise')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="siret" class="form-label">Numéro SIRET</label>
                        <input type="text" class="form-control @error('siret') is-invalid @enderror" id="siret" name="siret" value="{{ old('siret') }}" required>
                        @error('siret')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="adresseSiege" class="form-label">Adresse du siège social</label>
                        <textarea class="form-control @error('adresseSiege') is-invalid @enderror" id="adresseSiege" name="adresseSiege" rows="2" required>{{ old('adresseSiege') }}</textarea>
                        @error('adresseSiege')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">Vous avez déjà un compte? <a href="{{ route('login') }}">Connectez-vous</a></p>
            </div>
        </div>
    </div>
</div>
@endsection