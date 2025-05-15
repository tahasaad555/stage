<div class="grid-layout">
        <div class="card transactions-list">
            <div class="card-title">Transactions</div>
            
            <div class="filter-section">
                <div class="search-filter">
                    <input type="text" placeholder="Search by ID or client name..." wire:model.debounce.300ms="search">
                    
                    <select wire:model="status">
                        <option value="">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Processing">Processing</option>
                        <option value="Shipped">Shipped</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                    
                    <select wire:model="dateRange">
                        <option value="">All Dates</option>
                        <option value="today">Today</option>
                        <option value="week">Last Week</option>
                        <option value="month">Last Month</option>
                        <option value="custom">Custom Range</option>
                    </select>
                    
                    @if($dateRange === 'custom')
                    <div class="date-range-inputs">
                        <input type="date" wire:model="startDate">
                        <span>to</span>
                        <input type="date" wire:model="endDate">
                        <button class="button button-small" wire:click="applyCustomDateFilter">Apply</button>
                    </div>
                    @endif
                    
                    <button class="button button-small" wire:click="resetFilters">Reset</button>
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr class="{{ $selectedTransaction && $selectedTransaction->id === $transaction->id ? 'selected-row' : '' }}">
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->dateTransaction->format('M d, Y') }}</td>
                        <td>{{ $transaction->client->nom }} {{ $transaction->client->prenom }}</td>
                        <td>{{ $transaction->montant }} €</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($transaction->statut) }}">
                                {{ $transaction->statut }}
                            </span>
                        </td>
                        <td>
                            <button class="button button-small" wire:click="viewTransaction({{ $transaction->id }})">
                                View Details
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if(count($transactions) === 0)
                    <tr>
                        <td colspan="6" class="empty-table">No transactions found.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            
            <div class="pagination-container">
                {{ $transactions->links() }}
            </div>
        </div>
        
        <div class="card transaction-details">
            <div class="card-title">Transaction Details</div>
            
            @if($selectedTransaction)
                <div class="transaction-header">
                    <h3>Transaction #{{ $selectedTransaction->id }}</h3>
                    <span class="status-badge status-{{ strtolower($selectedTransaction->statut) }}">
                        {{ $selectedTransaction->statut }}
                    </span>
                </div>
                
                <div class="transaction-info">
                    <div class="info-section">
                        <h4>Client Information</h4>
                        <div class="info-row">
                            <div class="info-label">Name:</div>
                            <div class="info-value">{{ $selectedTransaction->client->nom }} {{ $selectedTransaction->client->prenom }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email:</div>
                            <div class="info-value">{{ $selectedTransaction->client->user->email }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Phone:</div>
                            <div class="info-value">{{ $selectedTransaction->client->telephone }}</div>
                        </div>
                    </div>
                    
                    <div class="info-section">
                        <h4>Transaction Details</h4>
                        <div class="info-row">
                            <div class="info-label">Date:</div>
                            <div class="info-value">{{ $selectedTransaction->dateTransaction->format('F d, Y - H:i') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Amount:</div>
                            <div class="info-value">{{ $selectedTransaction->montant }} €</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Commission:</div>
                            <div class="info-value">{{ $selectedTransaction->commission }} €</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Payment Method:</div>
                            <div class="info-value">{{ $selectedTransaction->methodePaiement }}</div>
                        </div>
                    </div>
                    
                    @if($selectedTransaction->annonce)
                    <div class="info-section">
                        <h4>Announcement Information</h4>
                        <div class="info-row">
                            <div class="info-label">Title:</div>
                            <div class="info-value">{{ $selectedTransaction->annonce->titre }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Type:</div>
                            <div class="info-value">{{ ucfirst($selectedTransaction->annonce->type) }}</div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="info-section">
                        <h4>Update Status</h4>
                        <form wire:submit.prevent="updateTransactionStatus">
                            <div class="form-group">
                                <select wire:model="newStatus">
                                    <option value="Pending">Pending</option>
                                    <option value="Processing">Processing</option>
                                    <option value="Shipped">Shipped</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="button">Update Status</button>
                        </form>
                    </div>
                </div>
                
                <div class="payments-section">
                    <h4>Payment History</h4>
                    
                    @if(count($selectedTransaction->paiements) > 0)
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedTransaction->paiements as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ $payment->dateTime->format('M d, Y') }}</td>
                                    <td>{{ $payment->methode }}</td>
                                    <td>{{ $payment->total }} €</td>
                                    <td>
                                        <span class="status-badge status-{{ $payment->verified ? 'completed' : 'pending' }}">
                                            {{ $payment->verified ? 'Verified' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(!$payment->verified)
                                            <button class="button button-small button-success" 
                                                    wire:click="verifyPayment({{ $payment->id }})"
                                                    onclick="confirm('Verify this payment?') || event.stopImmediatePropagation()">
                                                Verify
                                            </button>
                                        @else
                                            <span class="verified-icon">✓</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="empty-info">No payments recorded for this transaction.</p>
                    @endif
                </div>
            @else
                <div class="empty-details">
                    <p>Select a transaction to view details.</p>
                </div>
            @endif
        </div>
    </div>
</div>