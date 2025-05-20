@extends('layouts.app')

@section('title', 'Mon Panier')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h3">Mon Panier</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Articles dans votre panier</h5>
            </div>
            <div class="card-body">
                @if($panier->ligneCommandes->isEmpty())
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i> Votre panier est vide.
                        <div class="mt-2">
                            <a href="{{ route('terres.index') }}" class="btn btn-primary btn-sm">Parcourir les terres agricoles</a>
                            <a href="{{ route('annonces.index') }}" class="btn btn-outline-primary btn-sm">Voir les annonces</a>
                        </div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 120px;">Image</th>
                                    <th>Article</th>
                                    <th>Prix unitaire</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($panier->ligneCommandes as $ligne)
                                    <tr>
                                        <td>
                                            @if($ligne->terre_id)
                                                @if($ligne->terre->photos && count($ligne->terre->photos) > 0)
                                                    <img src="{{ asset('storage/' . $ligne->terre->photos[0]) }}" alt="{{ $ligne->terre->titre }}" class="img-thumbnail" style="width: 100px; height: 70px; object-fit: cover;">
                                                @else
                                                    <img src="https://source.unsplash.com/300x200/?agriculture,field" alt="Image par défaut" class="img-thumbnail" style="width: 100px; height: 70px; object-fit: cover;">
                                                @endif
                                            @elseif($ligne->produit_id)
                                                <div class="bg-light d-flex justify-content-center align-items-center" style="width: 100px; height: 70px;">
                                                    <i class="fas fa-box fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ligne->terre_id)
                                                <strong>{{ $ligne->terre->titre }}</strong>
                                                <div class="small text-muted">{{ $ligne->terre->surface }} hectares - {{ $ligne->terre->region }}</div>
                                                <div>
                                                    <a href="{{ route('terres.show', $ligne->terre_id) }}" class="small">Voir détails</a>
                                                </div>
                                            @elseif($ligne->produit_id)
                                                <strong>{{ $ligne->produit->nom }}</strong>
                                                <div class="small text-muted">{{ $ligne->produit->categorie }}</div>
                                            @endif
                                        </td>
                                        <td>{{ number_format($ligne->prixUnitaire, 2, ',', ' ') }} €</td>
                                        <td>
                                            @if($ligne->terre_id)
                                                1
                                            @else
                                                <form action="{{ route('panier.update-quantity', $ligne) }}" method="POST" class="quantity-form">
                                                    @csrf
                                                    <div class="input-group input-group-sm" style="width: 100px;">
                                                        <button type="button" class="btn btn-outline-secondary quantity-decrease">-</button>
                                                        <input type="number" name="quantite" class="form-control text-center quantity-input" value="{{ $ligne->quantite }}" min="1" max="100">
                                                        <button type="button" class="btn btn-outline-secondary quantity-increase">+</button>
                                                    </div>
                                                </form>
                                            @endif
                                        </td>
                                        <td>{{ number_format($ligne->prixUnitaire * $ligne->quantite, 2, ',', ' ') }} €</td>
                                        <td>
                                            <form action="{{ route('panier.remove-item', $ligne) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-3">
                        <form action="{{ route('panier.empty') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir vider votre panier ?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash-alt me-2"></i> Vider le panier
                            </button>
                        </form>
                        <a href="{{ route('annonces.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-shopping-basket me-2"></i> Continuer les achats
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Résumé de la commande</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Sous-total</span>
                    <span>{{ number_format($panier->total, 2, ',', ' ') }} €</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Frais de service (2%)</span>
                    <span>{{ number_format($panier->total * 0.02, 2, ',', ' ') }} €</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total</strong>
                    <strong>{{ number_format($panier->total * 1.02, 2, ',', ' ') }} €</strong>
                </div>
                
                @if(!$panier->ligneCommandes->isEmpty())
                    <div class="d-grid">
                        <a href="{{ route('client.transactions.create') }}" class="btn btn-success">
                            <i class="fas fa-check-circle me-2"></i> Procéder au paiement
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informations</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-info-circle me-2 text-primary"></i> Des frais de service de 2% sont appliqués à chaque transaction.
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-truck me-2 text-primary"></i> Pour les terres agricoles, une visite peut être organisée avant la finalisation de l'achat.
                    </li>
                    <li>
                        <i class="fas fa-shield-alt me-2 text-primary"></i> Toutes les transactions sont sécurisées et garanties par notre système.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle quantity buttons
        const quantityForms = document.querySelectorAll('.quantity-form');
        
        quantityForms.forEach(form => {
            const decreaseBtn = form.querySelector('.quantity-decrease');
            const increaseBtn = form.querySelector('.quantity-increase');
            const input = form.querySelector('.quantity-input');
            
            decreaseBtn.addEventListener('click', function() {
                let value = parseInt(input.value, 10);
                value = value > 1 ? value - 1 : 1;
                input.value = value;
                form.submit();
            });
            
            increaseBtn.addEventListener('click', function() {
                let value = parseInt(input.value, 10);
                value = value < 100 ? value + 1 : 100;
                input.value = value;
                form.submit();
            });
            
            input.addEventListener('change', function() {
                form.submit();
            });
        });
    });
</script>
@endsection