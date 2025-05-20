@extends('layouts.app')

@section('title', 'Paiement')

@section('content')
<div class="row mb-4">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('panier.show') }}">Panier</a></li>
                <li class="breadcrumb-item active" aria-current="page">Paiement</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Récapitulatif de la commande</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Prix unitaire</th>
                                <th>Quantité</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($panier->ligneCommandes as $ligne)
                                <tr>
                                    <td>
                                        @if($ligne->terre_id)
                                            <div class="d-flex align-items-center">
                                                @if($ligne->terre->photos && count($ligne->terre->photos) > 0)
                                                    <img src="{{ asset('storage/' . $ligne->terre->photos[0]) }}" alt="{{ $ligne->terre->titre }}" class="img-thumbnail me-3" style="width: 60px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex justify-content-center align-items-center me-3" style="width: 60px; height: 40px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $ligne->terre->titre }}</strong>
                                                    <div class="small text-muted">{{ $ligne->terre->surface }} hectares - {{ $ligne->terre->region }}</div>
                                                </div>
                                            </div>
                                        @elseif($ligne->produit_id)
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light d-flex justify-content-center align-items-center me-3" style="width: 60px; height: 40px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $ligne->produit->nom }}</strong>
                                                    <div class="small text-muted">{{ $ligne->produit->categorie }}</div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ number_format($ligne->prixUnitaire, 2, ',', ' ') }} €</td>
                                    <td>{{ $ligne->quantite }}</td>
                                    <td>{{ number_format($ligne->prixUnitaire * $ligne->quantite, 2, ',', ' ') }} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Sous-total</strong></td>
                                <td>{{ number_format($panier->total, 2, ',', ' ') }} €</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Frais de service (2%)</strong></td>
                                <td>{{ number_format($panier->total * 0.02, 2, ',', ' ') }} €</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total</strong></td>
                                <td><strong>{{ number_format($panier->total * 1.02, 2, ',', ' ') }} €</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Détails du paiement</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('client.transactions.store') }}" method="POST" id="payment-form">
                    @csrf
                    <input type="hidden" name="panier_id" value="{{ $panier->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Choisissez une méthode de paiement</label>
                        <div class="d-flex gap-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="methodePaiement" id="carte" value="carte" checked>
                                <label class="form-check-label" for="carte">
                                    <i class="fas fa-credit-card me-2"></i> Carte bancaire
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="methodePaiement" id="virement" value="virement">
                                <label class="form-check-label" for="virement">
                                    <i class="fas fa-university me-2"></i> Virement bancaire
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="methodePaiement" id="paypal" value="paypal">
                                <label class="form-check-label" for="paypal">
                                    <i class="fab fa-paypal me-2"></i> PayPal
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="carte-details" class="payment-details active">
                        <div class="mb-3">
                            <label for="card-number" class="form-label">Numéro de carte</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                <input type="text" class="form-control" id="card-number" placeholder="1234 5678 9012 3456">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="card-expiry" class="form-label">Date d'expiration</label>
                                <input type="text" class="form-control" id="card-expiry" placeholder="MM/AA">
                            </div>
                            <div class="col-md-6">
                                <label for="card-cvc" class="form-label">Code de sécurité (CVC)</label>
                                <input type="text" class="form-control" id="card-cvc" placeholder="123">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="card-name" class="form-label">Nom sur la carte</label>
                            <input type="text" class="form-control" id="card-name" placeholder="Prénom NOM">
                        </div>
                    </div>
                    
                    <div id="virement-details" class="payment-details">
                        <div class="alert alert-info">
                            <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i> Instructions pour le virement bancaire</h6>
                            <p class="mb-1">Veuillez effectuer un virement bancaire vers le compte suivant :</p>
                            <p class="mb-1"><strong>Banque :</strong> Banque AgriTerre</p>
                            <p class="mb-1"><strong>IBAN :</strong> FR76 1234 5678 9012 3456 7890 123</p>
                            <p class="mb-1"><strong>BIC :</strong> AGRIFRPP</p>
                            <p class="mb-1"><strong>Bénéficiaire :</strong> AgriTerre SAS</p>
                            <p class="mb-0"><strong>Référence :</strong> Votre prénom et nom + numéro de commande (sera fourni après confirmation)</p>
                        </div>
                    </div>
                    
                    <div id="paypal-details" class="payment-details">
                        <div class="alert alert-info">
                            <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i> Paiement via PayPal</h6>
                            <p class="mb-0">Vous serez redirigé vers le site PayPal pour finaliser votre paiement après avoir cliqué sur "Payer maintenant".</p>
                        </div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            J'accepte les <a href="#">conditions générales de vente</a> et la <a href="#">politique de confidentialité</a>
                        </label>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg" id="payment-button">
                            <i class="fas fa-lock me-2"></i> Payer maintenant
                        </button>
                        <a href="{{ route('panier.show') }}" class="btn btn-outline-secondary">Retour au panier</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Résumé</h5>
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
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Coordonnées de facturation</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</strong></p>
                <p class="mb-1"><i class="fas fa-envelope me-2"></i> {{ auth()->user()->email }}</p>
                @if(auth()->user()->telephone)
                    <p class="mb-0"><i class="fas fa-phone me-2"></i> {{ auth()->user()->telephone }}</p>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Sécurité des paiements</h5>
            </div>
            <div class="card-body">
                <p class="mb-3">
                    <i class="fas fa-shield-alt me-2 text-success"></i>
                    Toutes les informations de paiement sont cryptées avec une connexion SSL.
                </p>
                <p class="mb-0">
                    <i class="fas fa-lock me-2 text-success"></i>
                    Nous ne stockons jamais les détails complets de votre carte bancaire sur nos serveurs.
                </p>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <img src="https://via.placeholder.com/50x30?text=Visa" alt="Visa" height="30">
                    <img src="https://via.placeholder.com/50x30?text=MC" alt="Mastercard" height="30">
                    <img src="https://via.placeholder.com/50x30?text=Amex" alt="American Express" height="30">
                    <img src="https://via.placeholder.com/50x30?text=PayPal" alt="PayPal" height="30">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethods = document.querySelectorAll('input[name="methodePaiement"]');
        const paymentDetails = document.querySelectorAll('.payment-details');
        
        // Show/hide payment details based on selected method
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                paymentDetails.forEach(detail => {
                    detail.classList.remove('active');
                });
                
                document.getElementById(`${this.value}-details`).classList.add('active');
            });
        });
        
        // For demo purposes, set the payment button to show processing state
        const paymentForm = document.getElementById('payment-form');
        const paymentButton = document.getElementById('payment-button');
        
        paymentForm.addEventListener('submit', function(e) {
            // In a real implementation, this would be where you'd validate the form
            // and process the payment with a payment gateway
            
            paymentButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Traitement en cours...';
            paymentButton.disabled = true;
            
            // Allow the form to submit (in a real implementation, you might prevent default and handle via AJAX)
        });
    });
</script>

<style>
    .payment-details {
        display: none;
    }
    
    .payment-details.active {
        display: block;
    }
</style>
@endsection