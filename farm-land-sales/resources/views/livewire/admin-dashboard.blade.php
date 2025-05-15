@if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        
        <div class="dashboard-stats">
            <div class="stat-item">
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $totalClients }}</div>
                <div class="stat-label">Clients</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $totalSuppliers }}</div>
                <div class="stat-label">Suppliers</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $totalAnnouncements }}</div>
                <div class="stat-label">Announcements</div>
            </div>
            <div class="stat-item highlight">
                <div class="stat-value">{{ $pendingAnnouncements }}</div>
                <div class="stat-label">Pending Approvals</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $totalTransactions }}</div>
                <div class="stat-label">Transactions</div>
            </div>
            <div class="stat-item highlight">
                <div class="stat-value">{{ number_format($totalRevenue, 2) }} €</div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-item highlight">
                <div class="stat-value">{{ number_format($totalCommissions, 2) }} €</div>
                <div class="stat-label">Commission Earnings</div>
            </div>
        </div>
    </div>
    
    <div class="grid-container">
        <div class="card">
            <div class="card-title">Recent Transactions</div>
            
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
                    @foreach($recentTransactions as $transaction)
                    <tr>
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
                            <a href="{{ route('admin.transactions') }}?transaction={{ $transaction->id }}" class="button button-small">View</a>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if(count($recentTransactions) === 0)
                    <tr>
                        <td colspan="6" class="empty-table">No transactions yet.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            
            <div class="card-footer">
                <a href="{{ route('admin.transactions') }}" class="button">View All Transactions</a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-title">Pending Announcements</div>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingAnnouncementsPreview as $announcement)
                    <tr>
                        <td>{{ $announcement->id }}</td>
                        <td>{{ $announcement->titre }}</td>
                        <td>{{ ucfirst($announcement->type) }}</td>
                        <td>{{ $announcement->client->nom }} {{ $announcement->client->prenom }}</td>
                        <td>{{ $announcement->dateCreation->format('M d, Y') }}</td>
                        <td>
                            <div class="button-group">
                                <button class="button button-small button-success" 
                                        wire:click="approveAnnouncement({{ $announcement->id }})">
                                    Approve
                                </button>
                                <button class="button button-small button-danger" 
                                        wire:click="rejectAnnouncement({{ $announcement->id }})">
                                    Reject
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if(count($pendingAnnouncementsPreview) === 0)
                    <tr>
                        <td colspan="6" class="empty-table">No pending announcements.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            
            <div class="card-footer">
                <a href="{{ route('admin.announcements') }}" class="button">View All Pending Announcements</a>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-title">Quick Actions</div>
        
        <div class="admin-quick-actions">
            <a href="{{ route('admin.users') }}" class="action-card">
                <div class="action-icon">👥</div>
                <div class="action-title">Manage Users</div>
            </a>
            <a href="{{ route('admin.announcements') }}" class="action-card">
                <div class="action-icon">📢</div>
                <div class="action-title">Review Announcements</div>
            </a>
            <a href="{{ route('admin.transactions') }}" class="action-card">
                <div class="action-icon">💰</div>
                <div class="action-title">View Transactions</div>
            </a>
            <a href="{{ route('admin.reports') }}" class="action-card">
                <div class="action-icon">📊</div>
                <div class="action-title">Generate Reports</div>
            </a>
            <a href="{{ route('admin.commissions') }}" class="action-card">
                <div class="action-icon">💸</div>
                <div class="action-title">Manage Commissions</div>
            </a>
            <a href="{{ route('admin.settings') }}" class="action-card">
                <div class="action-icon">⚙️</div>
                <div class="action-title">System Settings</div>
            </a>
        </div>
    </div>
</div>