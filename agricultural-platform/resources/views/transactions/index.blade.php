@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h3">
            @if(auth()->user()->isAdmin())
                Toutes les Transactions
            @elseif(auth()->user()->isClient())
                Mes Transactions
            @elseif(auth()->user()->isFournisseur())
                Mes Transactions
            @endif
        </h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Filtres</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url()->current() }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Tous les statuts</option>
                                <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="completee" {{ request('status') == 'completee' ? 'selected' : '' }}>Complétée</option>
                                <option value="annulee" {{ request('status') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_debut" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="d-grid gap-2 w-100">
                                <button type="submit" class="btn btn-primary">Filtrer</button>
                                @if(request()->anyFilled(['status', 'date_debut', 'date_fin']))
                                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Réinitialiser</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Liste des transactions</h5>
            </div>
            <div class="card-body">
                @if($transactions->isEmpty())
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i> Aucune transaction trouvée.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isFournisseur())
                                        <th>Client</th>
                                    @endif
                                    @if(auth()->user()->isAdmin() || auth()->user()->isClient())
                                        <th>Fournisseur</th>
                                    @endif
                                    <th>Montant</th>
                                    <th>Commission</th>
                                    <th>Méthode</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>{{ $transaction->dateTransaction->format('d/m/Y') }}</td>
                                        @if(auth()->user()->isAdmin() || auth()->user()->isFournisseur())
                                            <td>{{ $transaction->client->utilisateur->prenom }} {{ $transaction->client->utilisateur->nom }}</td>
                                        @endif
                                        @if(auth()->user()->isAdmin() || auth()->user()->isClient())
                                            <td>{{ $transaction->fournisseur->entreprise }}</td>
                                        @endif
                                        <td>{{ number_format($transaction->montant, 2, ',', ' ') }} €</td>
                                        <td>{{ number_format($transaction->commission, 2, ',', ' ') }} €</td>
                                        <td>
                                            @if($transaction->methodePaiement == 'carte')
                                                <i class="fas fa-credit-card" title="Carte bancaire"></i>
                                            @elseif($transaction->methodePaiement == 'virement')
                                                <i class="fas fa-university" title="Virement bancaire"></i>
                                            @elseif($transaction->methodePaiement == 'paypal')
                                                <i class="fab fa-paypal" title="PayPal"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $transaction->statusTransaction == 'completee' ? 'bg-success' : ($transaction->statusTransaction == 'en_attente' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                {{ $transaction->statusTransaction == 'completee' ? 'Complétée' : ($transaction->statusTransaction == 'en_attente' ? 'En attente' : 'Annulée') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(auth()->user()->isAdmin())
                                                <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary">Détails</a>
                                            @elseif(auth()->user()->isClient())
                                                <a href="{{ route('client.transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary">Détails</a>
                                            @elseif(auth()->user()->isFournisseur())
                                                <a href="{{ route('fournisseur.transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary">Détails</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Affichage de {{ $transactions->firstItem() ?? 0 }} à {{ $transactions->lastItem() ?? 0 }} sur {{ $transactions->total() }} transactions
                        </div>
                        <div>
                            {{ $transactions->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Résumé des transactions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <h6 class="card-title text-muted">Total des transactions</h6>
                                <h3 class="card-text">{{ $transactions->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <h6 class="card-title text-muted">Montant total</h6>
                                <h3 class="card-text">{{ number_format($transactions->sum('montant'), 2, ',', ' ') }} €</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <h6 class="card-title text-muted">Commissions totales</h6>
                                <h3 class="card-text">{{ number_format($transactions->sum('commission'), 2, ',', ' ') }} €</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <h6 class="card-title text-muted">Transactions complétées</h6>
                                <h3 class="card-text">{{ $transactions->where('statusTransaction', 'completee')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection