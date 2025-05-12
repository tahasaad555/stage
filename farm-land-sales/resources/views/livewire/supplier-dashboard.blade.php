<div>
    <div class="card">
        <div class="card-title">Welcome, {{ $fournisseur->nom }}</div>
        <p>Manage your farming equipment and supplies inventory:</p>
        
        <div class="dashboard-stats">
            <div class="stat-item">
                <div class="stat-value">{{ count($myEquipment) }}</div>
                <div class="stat-label">Equipment Items</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ count($pendingOrders) }}</div>
                <div class="stat-label">Pending Orders</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $unreadMessages }}</div>
                <div class="stat-label">Unread Messages</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($totalSales, 2) }} €</div>
                <div class="stat-label">Total Sales</div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-title">Pending Orders</div>
        @if(count($pendingOrders) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Equipment</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->dateTransaction->format('M d, Y') }}</td>
                        <td>{{ $order->client->nom }}</td>
                        <td>{{ $order->materiel->typeEquipment }}</td>
                        <td>{{ $order->montant }} €</td>
                        <td>
                            <a href="/orders/{{ $order->id }}" class="button button-small">Manage</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No pending orders at this time.</p>
        @endif
    </div>
    
    <div class="card">
        <div class="card-title">My Equipment Inventory</div>
        <div class="grid">
            @foreach($myEquipment->take(3) as $equipment)
            <div class="item-card">
                <div class="item-image" style="background-image: url('{{ asset('storage/' . $equipment->documentCatalogue) }}')"></div>
                <div class="item-details">
                    <h3 class="item-title">{{ $equipment->typeEquipment }}</h3>
                    <div class="item-price">{{ $equipment->prix }} €</div>
                    <p class="item-condition">Condition: {{ $equipment->estNeuf ? 'New' : 'Used' }}</p>
                    <a href="/equipment/{{ $equipment->id }}/edit" class="button">Edit</a>
                </div>
            </div>
            @endforeach
        </div>
        <a href="/my-equipment" class="button">View All Equipment</a>
    </div>
    
    <div class="card">
        <div class="card-title">Quick Actions</div>
        <div class="button-group">
            <a href="/equipment/create" class="button">Add New Equipment</a>
            <a href="/messages" class="button">Check Messages</a>
            <a href="/orders" class="button">View All Orders</a>
        </div>
    </div>
</div>