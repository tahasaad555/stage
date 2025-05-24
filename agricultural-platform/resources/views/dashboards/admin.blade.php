@extends('layouts.app')

@section('title', 'Tableau de Bord Administrateur')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h3">Tableau de Bord Administrateur</h1>
        <p class="text-muted">Bienvenue, {{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50">Clients</h6>
                        <h2 class="card-text mb-0">{{ $stats['clients'] }}</h2>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('clients.index') }}" class="text-white text-decoration-none small">Gérer les clients <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50">Fournisseurs</h6>
                        <h2 class="card-text mb-0">{{ $stats['fournisseurs'] }}</h2>
                    </div>
                    <i class="fas fa-store fa-2x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('fournisseurs.index') }}" class="text-white text-decoration-none small">Gérer les fournisseurs <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white h-100">
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
                <a href="{{ route('annonces.index') }}" class="text-white text-decoration-none small">Voir les annonces <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-dark-50">Terres</h6>
                        <h2 class="card-text mb-0">{{ $stats['terres'] }}</h2>
                    </div>
                    <i class="fas fa-map-marked-alt fa-2x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-dark">
                <a href="{{ route('terres.index') }}" class="text-dark text-decoration-none small">Gérer les terres <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Transactions récentes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Fournisseur</th>
                                <th>Montant</th>
                                <th>Commission</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->dateTransaction->format('d/m/Y') }}</td>
                                    <td>{{ $transaction->client->utilisateur->prenom }} {{ $transaction->client->utilisateur->nom }}</td>
                                    <td>{{ $transaction->fournisseur->entreprise }}</td>
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
                                        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary">Détails</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-3">
                    <a href="{{ route('transactions.index') }}" class="btn btn-link">Voir toutes les transactions</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                <h5 class="mb-0">Statistiques des transactions</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Transactions totales</span>
                        <span>{{ $transactionStats['total'] }}</span>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Complétées</span>
                        <span>{{ $transactionStats['completees'] }}</span>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $transactionStats['total'] > 0 ? ($transactionStats['completees'] / $transactionStats['total'] * 100) : 0 }}%;" aria-valuenow="{{ $transactionStats['total'] > 0 ? ($transactionStats['completees'] / $transactionStats['total'] * 100) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>En attente</span>
                        <span>{{ $transactionStats['enAttente'] }}</span>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $transactionStats['total'] > 0 ? ($transactionStats['enAttente'] / $transactionStats['total'] * 100) : 0 }}%;" aria-valuenow="{{ $transactionStats['total'] > 0 ? ($transactionStats['enAttente'] / $transactionStats['total'] * 100) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Annulées</span>
                        <span>{{ $transactionStats['annulees'] }}</span>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $transactionStats['total'] > 0 ? ($transactionStats['annulees'] / $transactionStats['total'] * 100) : 0 }}%;" aria-valuenow="{{ $transactionStats['total'] > 0 ? ($transactionStats['annulees'] / $transactionStats['total'] * 100) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Montant total</h6>
                    <h5 class="mb-0 text-success">{{ number_format($transactionStats['montantTotal'], 2, ',', ' ') }} €</h5>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Commissions totales</h6>
                    <h5 class="mb-0 text-primary">{{ number_format($transactionStats['commissionsTotal'], 2, ',', ' ') }} €</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Annonces récentes</h5>
                <a href="{{ route('annonces.index') }}" class="btn btn-sm btn-outline-primary">Voir toutes</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Fournisseur</th>
                                <th>Terre agricole</th>
                                <th>Date de création</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAnnonces as $annonce)
                                <tr>
                                    <td>{{ $annonce->id }}</td>
                                    <td>{{ $annonce->titre }}</td>
                                    <td>{{ $annonce->fournisseur->entreprise }}</td>
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
                                        <a href="{{ route('annonces.edit', $annonce) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Actions rapides</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('clients.create') }}" class="card text-decoration-none h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                                <h5>Ajouter un client</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('fournisseurs.create') }}" class="card text-decoration-none h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-store-alt fa-3x text-success mb-3"></i>
                                <h5>Ajouter un fournisseur</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('terres.create') }}" class="card text-decoration-none h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-map-marker-alt fa-3x text-danger mb-3"></i>
                                <h5>Ajouter une terre</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('administrateurs.create') }}" class="card text-decoration-none h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-user-shield fa-3x text-info mb-3"></i>
                                <h5>Ajouter un administrateur</h5>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Statistiques des Transactions</h5>
            </div>
            <div class="card-body">
                <canvas id="transactionChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Statistiques des Utilisateurs</h5>
            </div>
            <div class="card-body">
                <canvas id="userChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Transaction Chart
    const transactionCtx = document.getElementById('transactionChart').getContext('2d');
    const transactionChart = new Chart(transactionCtx, {
        type: 'bar',
        data: {
            labels: ['Complétées', 'En attente', 'Annulées'],
            datasets: [{
                label: 'Transactions',
                data: [
                    {{ $transactionStats['completees'] }}, 
                    {{ $transactionStats['enAttente'] }}, 
                    {{ $transactionStats['annulees'] }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.6)',
                    'rgba(255, 193, 7, 0.6)',
                    'rgba(220, 53, 69, 0.6)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
    
    // User Chart
    const userCtx = document.getElementById('userChart').getContext('2d');
    const userChart = new Chart(userCtx, {
        type: 'pie',
        data: {
            labels: ['Clients', 'Fournisseurs', 'Administrateurs'],
            datasets: [{
                data: [
                    {{ $stats['clients'] }}, 
                    {{ $stats['fournisseurs'] }}, 
                    {{ \App\Models\Administrateur::count() }}
                ],
                backgroundColor: [
                    'rgba(0, 123, 255, 0.6)',
                    'rgba(40, 167, 69, 0.6)',
                    'rgba(108, 117, 125, 0.6)'
                ],
                borderColor: [
                    'rgba(0, 123, 255, 1)',
                    'rgba(40, 167, 69, 1)',
                    'rgba(108, 117, 125, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
        }
    });
</script>
@endsection
