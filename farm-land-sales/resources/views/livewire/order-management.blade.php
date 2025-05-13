<div>
    <div class="order-management-container">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="order-list-section">
            <div class="card">
                <div class="card-title">Orders</div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $orderItem)
                        <tr class="{{ $order && $orderItem->id == $order->id ? 'selected-row' : '' }}">
                            <td>{{ $orderItem->id }}</td>
                            <td>{{ $orderItem->dateTransaction->format('M d, Y') }}</td>
                            <td>{{ $orderItem->client->nom }}</td>
                            <td>{{ $orderItem->montant }} €</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($orderItem->statut) }}">
                                    {{ $orderItem->statut }}
                                </span>
                            </td>
                            <td>
                                <button class="button button-small" wire:click="viewOrder({{ $orderItem->id }})">
                                    View
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="pagination-container">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
        
        <div class="order-detail-section">
            @if($order)
            <div class="card">
                <div class="card-title">Order #{{ $order->id }} Details</div>
                
                <div class="order-overview">
                    <div class="order-info-section">
                        <h3>Order Information</h3>
                        <div class="info-row">
                            <div class="info-label">Date:</div>
                            <div class="info-value">{{ $order->dateTransaction->format('F d, Y') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Customer:</div>
                            <div class="info-value">{{ $order->client->nom }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email:</div>
                            <div class="info-value">{{ $order->client->user->email }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Phone:</div>
                            <div class="info-value">{{ $order->client->telephone }}</div>
                        </div>
                    </div>
                    
                    <div class="order-status-section">
                        <h3>Order Status</h3>
                        <form wire:submit.prevent="updateOrderStatus">
                            <div class="form-group">
                                <label for="newStatus">Status</label>
                                <select id="newStatus" wire:model="newStatus">
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
                
                <div class="order-items">
                    <h3>Ordered Items</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Type</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @if($order->annonceId)
                                    <td>{{ $order->annonce->titre }}</td>
                                    <td>{{ $order->annonce->type }}</td>
                                    <td>{{ $order->montant }} €</td>
                                @else
                                    <td colspan="3">Item details not available</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="order-payments">
                    <h3>Payment Information</h3>
                    
                    <div class="payment-summary">
                        <div class="info-row">
                            <div class="info-label">Total Amount:</div>
                            <div class="info-value">{{ $order->montant }} €</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Payment Method:</div>
                            <div class="info-value">{{ $order->methodePaiement }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Commission:</div>
                            <div class="info-value">{{ $order->commission }} €</div>
                        </div>
                    </div>
                    
                    <h4>Payment History</h4>
                    @if(count($order->paiements) > 0)
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->paiements as $payment)
                                <tr>
                                    <td>{{ $payment->dateTime->format('M d, Y') }}</td>
                                    <td>{{ $payment->methode }}</td>
                                    <td>{{ $payment->total }} €</td>
                                    <td>{{ $payment->verified ? 'Verified' : 'Pending' }}</td>
                                    <td>
                                        @if(!$payment->verified)
                                            <button class="button button-small" 
                                                    wire:click="verifyPayment({{ $payment->id }})"
                                                    onclick="confirm('Verify this payment?') || event.stopImmediatePropagation()">
                                                Verify
                                            </button>
                                        @else
                                            <span class="verified-badge">✓</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No payments recorded yet.</p>
                    @endif
                </div>
            </div>
            @else
                <div class="card">
                    <div class="empty-state">
                        <p>Select an order to view details</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>