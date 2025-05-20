@extends('layouts.app')

@section('title', 'Tableau de Bord Fournisseur')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h3">Tableau de Bord Fournisseur</h1>
        <p class="text-muted">Bienvenue, {{ auth()->user()->prenom }} {{ auth()->user()->nom }} - {{ auth()->user()->fournisseur->entreprise }}</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50">Annonces</h6>
                        <h2 class="card-text mb-0">{{ $stats['annonces'] }}</h2>
                    </div>
                    <i class="fas fa-bullhorn fa-2x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('fournisseur.annonces') }}" class="text-white text-decoration-none small">Gérer les annonces <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50">Transactions</h6>
                        <h2 class="card-text mb-0">{{ $stats['transactions'] }}</h2>
                    </div>
                    <i class="fas fa-exchange-alt fa-2x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('fournisseur.transactions') }}" class="text-white text-decoration-none small">Voir les transactions <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50">Messages</h6>
                        <h2 class="card-text mb-0">{{ $stats['messages'] }}</h2>
                    </div>
                    <i class="fas fa-envelope fa-2x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('fournisseur.messages') }}" class="text-white text-decoration-none small">Voir les messages <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-dark-50">Évaluations</h6>
                        <h2 class="card-text mb-0">{{ $stats['avis'] }}</h2>
                    </div>
                    <i class="fas fa-star fa-2x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-dark">
                <span class="text-dark small">Note moyenne: {{ number_format($averageRating, 1) }}/5</span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Mes annonces</h5>
                <div>
                    <a href="{{ route('fournisseur.terres.create') }}" class="btn btn-sm btn-outline-success me-2">Ajouter une terre</a>
                    <a href="{{ route('fournisseur.annonces.create') }}" class="btn btn-sm btn-primary">Créer une annonce</a>
                </div>
            </div>
            <div class="card-body">
                @if($recentAnnonces->isEmpty())
                    <div class="alert alert-info mb-0">
                        Vous n'avez pas encore créé d'annonces.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Terre agricole</th>
                                    <th>Date de création</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAnnonces as $annonce)
                                    <tr>
                                        <td>{{ $annonce->titre }}</td>
                                        <td>{{ $annonce->terreAgricole->titre }}</td>
                                        <td>{{ $annonce->dateCreation->format('d/m/Y') }}</td>
                                        <td>
                                            @if($annonce->estActive)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('annonces.show', $annonce) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                            <a href="{{ route('fournisseur.annonces.edit', $annonce) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="{{ route('fournisseur.annonces') }}" class="btn btn-link">Voir toutes mes annonces</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Messages non lus</h5>
                <a href="{{ route('fournisseur.messages') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body">
                @if($unreadMessages->isEmpty())
                    <div class="alert alert-info mb-0">
                        Vous n'avez pas de messages non lus.
                    </div>
                @else
                    <div class="list-group">
                        @foreach($unreadMessages as $message)
                            <a href="{{ route('fournisseur.messages.conversation', $message->client_id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $message->client->utilisateur->prenom }} {{ $message->client->utilisateur->nom }}</h6>
                                    <small>{{ $message->dateHeure->format('d/m/Y H:i') }}</small>
                                </div>
                                <p class="mb-1 text-truncate">{{ $message->contenu }}</p>
                                <small class="text-muted">Client</small>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Transactions récentes</h5>
                <a href="{{ route('fournisseur.transactions') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body">
                @if($recentTransactions->isEmpty())
                    <div class="alert alert-info mb-0">
                        Vous n'avez pas encore effectué de transactions.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Commission</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->dateTransaction->format('d/m/Y') }}</td>
                                        <td>{{ $transaction->client->utilisateur->prenom }} {{ $transaction->client->utilisateur->nom }}</td>
                                        <td>{{ number_format($transaction->montant, 2, ',', ' ') }} €</td>
                                        <td>{{ number_format($transaction->commission, 2, ',', ' ') }} €</td>
                                        <td>
                                            @if($transaction->statusTransaction == 'completee')
                                                <span class="badge bg-success">Complétée</span>
                                            @elseif($transaction->statusTransaction == 'en_attente')
                                                <span class="badge bg-warning text-dark">En attente</span>
                                            @else
                                                <span class="badge bg-danger">Annulée</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('fournisseur.transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary">Détails</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Statistiques des ventes</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <h5 class="text-muted">Ventes totales</h5>
                                <h3 class="text-primary">{{ number_format($transactionStats['montantTotal'], 2, ',', ' ') }} €</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <h5 class="text-muted">Transactions complétées</h5>
                                <h3 class="text-success">{{ $transactionStats['completees'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <h5 class="text-muted">Transactions en attente</h5>
                                <h3 class="text-warning">{{ $transactionStats['enAttente'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Les statistiques détaillées et les rapports de vente seront disponibles prochainement.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection