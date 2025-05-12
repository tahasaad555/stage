<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="card">
        <div class="card-title">System Overview</div>
        <div class="dashboard-stats">
            <div class="stat-item">
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $totalAnnouncements }}</div>
                <div class="stat-label">Total Announcements</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $totalTransactions }}</div>
                <div class="stat-label">Total Transactions</div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-title">Recent Transactions</div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentTransactions as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->client->nom }}</td>
                    <td>{{ $transaction->dateTransaction }}</td>
                    <td>{{ $transaction->montant }}</td>
                    <td>{{ $transaction->statut }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="card">
        <div class="card-title">Pending Announcements</div>
        @livewire('pending-announcements')
    </div>
</div>