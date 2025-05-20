@extends('layouts.app')

@section('title', 'Tableau de Bord Client')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h3">Tableau de Bord Client</h1>
        <p class="text-muted">Bienvenue, {{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50">Favoris</h6>
                        <h2 class="card-text mb-0">{{ $stats['favoris'] }}</h2>
                    </div>
                    <i class="fas fa-heart fa-2x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="#favoris" class="text-white text-decoration-none small">Voir les favoris <i class="fas fa-arrow-right ms-1"></i></a>
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
                <a href="{{ route('client.transactions') }}" class="text-white text-decoration-none small">Voir les transactions <i class="fas fa-arrow-right ms-1"></i></a>
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
                <a href="{{ route('client.messages') }}" class="text-white text-decoration-none small">Voir les messages <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-dark-50">Recherches</h6>
                        <h2 class="card-text mb-0">{{ $stats['recherches'] }}</h2>
                    </div>
                    <i class="fas fa-search fa-2x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-dark">
                <a href="{{ route('terres.search') }}" class="text-dark text-decoration-none small">Nouvelle recherche <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Transactions récentes</h5>
                <a href="{{ route('client.transactions') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
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
                                    <th>Fournisseur</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->dateTransaction->format('d/m/Y') }}</td>
                                        <td>{{ $transaction->fournisseur->entreprise }}</td>
                                        <td>{{ number_format($transaction->montant, 2, ',', ' ') }} €</td>
                                        <td>
                                            @if($transaction->statusTransaction == 'completee')
                                                <span class="badge bg-success">Complétée</span>
                                            @elseif($transaction->statusTransaction == 'en_attente')
                                                <span class="badge bg-warning text-dark">En attente</span>
                                            @else
                                                <span class="badge bg-danger">Annulée</span>
                                            @endif
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
    
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Messages non lus</h5>
                <a href="{{ route('client.messages') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body">
                @if($unreadMessages->isEmpty())
                    <div class="alert alert-info mb-0">
                        Vous n'avez pas de messages non lus.
                    </div>
                @else
                    <div class="list-group">
                        @foreach($unreadMessages as $message)
                            <a href="{{ route('client.messages.conversation', $message->fournisseur_id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $message->fournisseur->utilisateur->prenom }} {{ $message->fournisseur->utilisateur->nom }}</h6>
                                    <small>{{ $message->dateHeure->format('d/m/Y H:i') }}</small>
                                </div>
                                <p class="mb-1 text-truncate">{{ $message->contenu }}</p>
                                <small class="text-muted">{{ $message->fournisseur->entreprise }}</small>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mb-4" id="favoris">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Mes favoris</h5>
            </div>
            <div class="card-body">
                @if($favoris->isEmpty())
                    <div class="alert alert-info mb-0">
                        Vous n'avez pas encore ajouté d'annonces à vos favoris.
                    </div>
                @else
                    <div class="row">
                        @foreach($favoris as $favori)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div style="height: 150px; overflow: hidden;">
                                        @if($favori->annonce->terreAgricole->photos && count($favori->annonce->terreAgricole->photos) > 0)
                                            <img src="{{ asset('storage/' . $favori->annonce->terreAgricole->photos[0]) }}" class="card-img-top" alt="{{ $favori->annonce->titre }}" style="object-fit: cover; height: 100%; width: 100%;">
                                        @else
                                            <img src="https://source.unsplash.com/800x600/?agriculture,field" class="card-img-top" alt="Image par défaut" style="object-fit: cover; height: 100%; width: 100%;">
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $favori->annonce->titre }}</h6>
                                        <p class="card-text text-muted mb-1">{{ $favori->annonce->terreAgricole->surface }} hectares - {{ $favori->annonce->terreAgricole->region }}</p>
                                        <h6 class="fw-bold text-primary mb-2">{{ number_format($favori->annonce->terreAgricole->prix, 0, ',', ' ') }} €</h6>
                                    </div>
                                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                                        <a href="{{ route('annonces.show', $favori->annonce) }}" class="btn btn-sm btn-outline-primary">Voir les détails</a>
                                        <form action="{{ route('annonces.toggle-favorite', $favori->annonce) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Dernières annonces</h5>
                <a href="{{ route('annonces.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($recentAnnonces as $annonce)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div style="height: 150px; overflow: hidden;">
                                    @if($annonce->terreAgricole->photos && count($annonce->terreAgricole->photos) > 0)
                                        <img src="{{ asset('storage/' . $annonce->terreAgricole->photos[0]) }}" class="card-img-top" alt="{{ $annonce->titre }}" style="object-fit: cover; height: 100%; width: 100%;">
                                    @else
                                        <img src="https://source.unsplash.com/800x600/?agriculture,field" class="card-img-top" alt="Image par défaut" style="object-fit: cover; height: 100%; width: 100%;">
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title">{{ $annonce->titre }}</h6>
                                    <p class="card-text text-muted mb-1">{{ $annonce->terreAgricole->surface }} hectares - {{ $annonce->terreAgricole->region }}</p>
                                    <h6 class="fw-bold text-primary mb-2">{{ number_format($annonce->terreAgricole->prix, 0, ',', ' ') }} €</h6>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <a href="{{ route('annonces.show', $annonce) }}" class="btn btn-sm btn-outline-primary w-100">Voir les détails</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection