@extends('layouts.app')

@section('title', 'Détails de la Transaction')

@section('content')
<div class="row mb-4">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @if(auth()->user()->isAdmin())
                    <li class="breadcrumb-item"><a href="{{ route('transactions.index') }}">Transactions</a></li>
                @elseif(auth()->user()->isClient())
                    <li class="breadcrumb-item"><a href="{{ route('client.transactions') }}">Mes Transactions</a></li>
                @elseif(auth()->user()->isFournisseur())
                    <li class="breadcrumb-item"><a href="{{ route('fournisseur.transactions') }}">Mes Transactions</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">Transaction #{{ $transaction->id }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <h5 class="mb-0">Détails de la transaction #{{ $transaction->id }}</h5>
                <span class="badge {{ $transaction->statusTransaction == 'completee' ? 'bg-success' : ($transaction->statusTransaction == 'en_attente' ? 'bg-warning text-dark' : 'bg-danger') }}">
                    {{ $transaction->statusTransaction == 'completee' ? 'Complétée' : ($transaction->statusTransaction == 'en_attente' ? 'En attente' : 'Annulée') }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="mb-2">Informations générales</h6>
                        <p class="mb-1"><strong>Date de transaction :</strong> {{ $transaction->dateTransaction->format('d/m/Y') }}</p>
                        <p class="mb-1"><strong>Montant :</strong> {{ number_format($transaction->montant, 2, ',', ' ') }} €</p>
                        <p class="mb-1"><strong>Commission (5%) :</strong> {{ number_format($transaction->commission, 2, ',', ' ') }} €</p>
                        <p class="mb-1"><strong>Méthode de paiement :</strong> 
                            @if($transaction->methodePaiement == 'carte')
                                <i class="fas fa-credit-card me-1"></i> Carte bancaire
                            @elseif($transaction->methodePaiement == 'virement')
                                <i class="fas fa-university me-1"></i> Virement bancaire
                            @elseif($transaction->methodePaiement == 'paypal')
                                <i class="fab fa-paypal me-1"></i> PayPal
                            @endif
                        </p>
                        <p class="mb-0"><strong>Référence de paiement :</strong> {{ $transaction->referencePaiement }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-2">Statut</h6>
                        <p class="mb-1"><strong>Statut de la transaction :</strong> 
                            <span class="badge {{ $transaction->statusTransaction == 'completee' ? 'bg-success' : ($transaction->statusTransaction == 'en_attente' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ $transaction->statusTransaction == 'completee' ? 'Complétée' : ($transaction->statusTransaction == 'en_attente' ? 'En attente' : 'Annulée') }}
                            </span>
                        </p>
                        <p class="mb-1"><strong>Vérifiée :</strong> 
                            @if($transaction->estVerifiee)
                                <span class="text-success"><i class="fas fa-check-circle me-1"></i> Oui</span>
                            @else
                                <span class="text-danger"><i class="fas fa-times-circle me-1"></i> Non</span>
                            @endif
                        </p>
                        @if($transaction->paiement)
                            <p class="mb-1"><strong>Statut du paiement :</strong> {{ ucfirst($transaction->paiement->status) }}</p>
                            <p class="mb-1"><strong>Validé :</strong> 
                                @if($transaction->paiement->valide)
                                    <span class="text-success"><i class="fas fa-check-circle me-1"></i> Oui</span>
                                @else
                                    <span class="text-danger"><i class="fas fa-times-circle me-1"></i> Non</span>
                                @endif
                            </p>
                        @endif
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="mb-2">Client</h6>
                        <p class="mb-1"><strong>Nom :</strong> {{ $transaction->client->utilisateur->prenom }} {{ $transaction->client->utilisateur->nom }}</p>
                        <p class="mb-1"><strong>Email :</strong> {{ $transaction->client->utilisateur->email }}</p>
                        @if($transaction->client->utilisateur->telephone)
                            <p class="mb-0"><strong>Téléphone :</strong> {{ $transaction->client->utilisateur->telephone }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-2">Fournisseur</h6>
                        <p class="mb-1"><strong>Entreprise :</strong> {{ $transaction->fournisseur->entreprise }}</p>
                        <p class="mb-1"><strong>Contact :</strong> {{ $transaction->fournisseur->utilisateur->prenom }} {{ $transaction->fournisseur->utilisateur->nom }}</p>
                        <p class="mb-1"><strong>Email :</strong> {{ $transaction->fournisseur->utilisateur->email }}</p>
                        @if($transaction->fournisseur->utilisateur->telephone)
                            <p class="mb-0"><strong>Téléphone :</strong> {{ $transaction->fournisseur->utilisateur->telephone }}</p>
                        @endif
                    </div>
                </div>

                @if($transaction->statusTransaction == 'en_attente' && auth()->user()->isClient() && $transaction->client_id == auth()->user()->client->id)
                    <div class="alert alert-warning mb-4">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i> Paiement en attente</h6>
                        <p class="mb-0">Votre paiement est en attente de traitement. Veuillez procéder au paiement pour finaliser votre transaction.</p>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <form action="{{ route('client.transactions.process-payment', $transaction) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-credit-card me-2"></i> Procéder au paiement
                            </button>
                        </form>
                        
                        <form action="{{ route('client.transactions.cancel', $transaction) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette transaction ?');">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times-circle me-2"></i> Annuler la transaction
                            </button>
                        </form>
                    </div>
                @elseif($transaction->statusTransaction == 'completee')
                    <div class="alert alert-success mb-4">
                        <h6 class="alert-heading"><i class="fas fa-check-circle me-2"></i> Transaction complétée</h6>
                        <p class="mb-0">Cette transaction a été complétée avec succès. Merci pour votre achat !</p>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i> Imprimer le reçu
                        </a>
                        @if(auth()->user()->isClient())
                            <a href="{{ route('client.messages.conversation', $transaction->fournisseur_id) }}" class="btn btn-outline-success">
                                <i class="fas fa-envelope me-2"></i> Contacter le fournisseur
                            </a>
                        @elseif(auth()->user()->isFournisseur())
                            <a href="{{ route('fournisseur.messages.conversation', $transaction->client_id) }}" class="btn btn-outline-success">
                                <i class="fas fa-envelope me-2"></i> Contacter le client
                            </a>
                        @endif
                    </div>
                @elseif($transaction->statusTransaction == 'annulee')
                    <div class="alert alert-danger mb-4">
                        <h6 class="alert-heading"><i class="fas fa-times-circle me-2"></i> Transaction annulée</h6>
                        <p class="mb-0">Cette transaction a été annulée.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Articles achetés</h5>
            </div>
            <div class="card-body">
                <!-- This part would normally load from the actual items in the transaction -->
                <!-- For demonstration, we'll show placeholder content -->
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
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light d-flex justify-content-center align-items-center me-3" style="width: 60px; height: 40px;">
                                            <i class="fas fa-map me-1"></i>
                                        </div>
                                        <div>
                                            <strong>Terre agricole - [Description]</strong>
                                            <div class="small text-muted">Surface - Région</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($transaction->montant, 2, ',', ' ') }} €</td>
                                <td>1</td>
                                <td>{{ number_format($transaction->montant, 2, ',', ' ') }} €</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Sous-total</strong></td>
                                <td>{{ number_format($transaction->montant, 2, ',', ' ') }} €</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Commission (5%)</strong></td>
                                <td>{{ number_format($transaction->commission, 2, ',', ' ') }} €</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total</strong></td>
                                <td><strong>{{ number_format($transaction->montant + $transaction->commission, 2, ',', ' ') }} €</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Actions administratives</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        @if($transaction->statusTransaction == 'en_attente')
                            <form action="{{ route('transactions.process-payment', $transaction) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle me-2"></i> Marquer comme payée
                                </button>
                            </form>
                            
                            <form action="{{ route('transactions.cancel', $transaction) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette transaction ?');">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times-circle me-2"></i> Annuler la transaction
                                </button>
                            </form>
                        @elseif($transaction->statusTransaction == 'completee')
                            <form action="{{ route('transactions.cancel', $transaction) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette transaction ? Cette action est irréversible.');">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times-circle me-2"></i> Annuler et rembourser
                                </button>
                            </form>
                        @elseif($transaction->statusTransaction == 'annulee')
                            <form action="{{ route('transactions.process-payment', $transaction) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-redo me-2"></i> Réactiver et marquer comme payée
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Résumé</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Montant</span>
                    <span>{{ number_format($transaction->montant, 2, ',', ' ') }} €</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Commission (5%)</span>
                    <span>{{ number_format($transaction->commission, 2, ',', ' ') }} €</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total</strong>
                    <strong>{{ number_format($transaction->montant + $transaction->commission, 2, ',', ' ') }} €</strong>
                </div>
                
                <div class="alert {{ $transaction->statusTransaction == 'completee' ? 'alert-success' : ($transaction->statusTransaction == 'en_attente' ? 'alert-warning' : 'alert-danger') }} mb-0">
                    <div class="d-flex">
                        <div class="me-3">
                            @if($transaction->statusTransaction == 'completee')
                                <i class="fas fa-check-circle fa-2x"></i>
                            @elseif($transaction->statusTransaction == 'en_attente')
                                <i class="fas fa-clock fa-2x"></i>
                            @else
                                <i class="fas fa-times-circle fa-2x"></i>
                            @endif
                        </div>
                        <div>
                            <h6 class="alert-heading">
                                @if($transaction->statusTransaction == 'completee')
                                    Transaction complétée
                                @elseif($transaction->statusTransaction == 'en_attente')
                                    En attente de paiement
                                @else
                                    Transaction annulée
                                @endif
                            </h6>
                            <p class="mb-0">
                                @if($transaction->statusTransaction == 'completee')
                                    Le paiement a été reçu et vérifié.
                                @elseif($transaction->statusTransaction == 'en_attente')
                                    Le paiement est en cours de traitement.
                                @else
                                    Cette transaction a été annulée.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informations de paiement</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Méthode :</strong> 
                    @if($transaction->methodePaiement == 'carte')
                        <i class="fas fa-credit-card me-1"></i> Carte bancaire
                    @elseif($transaction->methodePaiement == 'virement')
                        <i class="fas fa-university me-1"></i> Virement bancaire
                    @elseif($transaction->methodePaiement == 'paypal')
                        <i class="fab fa-paypal me-1"></i> PayPal
                    @endif
                </p>
                <p class="mb-1"><strong>Date :</strong> {{ $transaction->dateTransaction->format('d/m/Y') }}</p>
                <p class="mb-0"><strong>Référence :</strong> {{ $transaction->referencePaiement }}</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Contacts</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Client</h6>
                    <p class="mb-1"><strong>{{ $transaction->client->utilisateur->prenom }} {{ $transaction->client->utilisateur->nom }}</strong></p>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i> {{ $transaction->client->utilisateur->email }}</p>
                    @if($transaction->client->utilisateur->telephone)
                        <p class="mb-0"><i class="fas fa-phone me-2"></i> {{ $transaction->client->utilisateur->telephone }}</p>
                    @endif
                </div>
                <hr>
                <div>
                    <h6>Fournisseur</h6>
                    <p class="mb-1"><strong>{{ $transaction->fournisseur->entreprise }}</strong></p>
                    <p class="mb-1"><i class="fas fa-user me-2"></i> {{ $transaction->fournisseur->utilisateur->prenom }} {{ $transaction->fournisseur->utilisateur->nom }}</p>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i> {{ $transaction->fournisseur->utilisateur->email }}</p>
                    @if($transaction->fournisseur->utilisateur->telephone)
                        <p class="mb-0"><i class="fas fa-phone me-2"></i> {{ $transaction->fournisseur->utilisateur->telephone }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection