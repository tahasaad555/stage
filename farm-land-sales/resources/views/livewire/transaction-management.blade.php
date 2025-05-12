<div>
    <div class="card">
        <div class="card-title">Transaction Management</div>
        
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->dateTransaction->format('M d, Y') }}</td>
                    <td>{{ $transaction->annonceId ? 'Announcement #' . $transaction->annonceId : 'Custom' }}</td>
                    <td>{{ $transaction->montant }} €</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($transaction->statut) }}">
                            {{ $transaction->statut }}
                        </span>
                    </td>
                    <td>
                        <button class="button button-small" wire:click="viewTransaction({{ $transaction->id }})">
                            Details
                        </button>
                        
                        @if($transaction->statut !== 'Completed' && $transaction->statut !== 'Cancelled')
                        <button class="button button-small button-danger" 
                                wire:click="cancelTransaction({{ $transaction->id }})"
                                onclick="confirm('Are you sure you want to cancel this transaction?') || event.stopImmediatePropagation()">
                            Cancel
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($selectedTransaction)
    <div class="card">
        <div class="card-title">Transaction Details #{{ $selectedTransaction->id }}</div>
        
        <div class="transaction-details">
            <div class="detail-row">
                <div class="detail-label">Date:</div>
                <div class="detail-value">{{ $selectedTransaction->dateTransaction->format('F d, Y - H:i') }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Client:</div>
                <div class="detail-value">{{ $selectedTransaction->client->nom }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Amount:</div>
                <div class="detail-value">{{ $selectedTransaction->montant }} €</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Status:</div>
                <div class="detail-value">
                    <span class="status-badge status-{{ strtolower($selectedTransaction->statut) }}">
                        {{ $selectedTransaction->statut }}
                    </span>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Payment Method:</div>
                <div class="detail-value">{{ $selectedTransaction->methodePaiement }}</div>
            </div>
            
            <h3>Payments</h3>
            
            @if(count($selectedTransaction->paiements) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Verified</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($selectedTransaction->paiements as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ $payment->dateTime->format('M d, Y') }}</td>
                            <td>{{ $payment->methode }}</td>
                            <td>{{ $payment->total }} €</td>
                            <td>{{ $payment->verified ? 'Yes' : 'No' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No payments recorded yet.</p>
            @endif
        </div>
    </div>
    @endif
</div>