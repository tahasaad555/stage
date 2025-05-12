<div>
    <div class="card">
        <div class="card-title">Welcome, {{ $client->prenom }}</div>
        <p>Here's your activity overview:</p>
        
        <div class="dashboard-stats">
            <div class="stat-item">
                <div class="stat-value">{{ count($myAnnouncements) }}</div>
                <div class="stat-label">My Announcements</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ count($myTransactions) }}</div>
                <div class="stat-label">My Transactions</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $unreadMessages }}</div>
                <div class="stat-label">Unread Messages</div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-title">My Recent Announcements</div>
        <div class="grid">
            @foreach($myAnnouncements->take(3) as $announcement)
            <div class="item-card">
                <div class="item-image" style="background-image: url('{{ asset('storage/' . $announcement->image) }}')"></div>
                <div class="item-details">
                    <h3 class="item-title">{{ $announcement->titre }}</h3>
                    <div class="item-price">{{ $announcement->prix }} €</div>
                    <p class="item-status">Status: {{ $announcement->estActif ? 'Active' : 'Pending' }}</p>
                    <a href="/announcements/{{ $announcement->id }}/edit" class="button">Edit</a>
                </div>
            </div>
            @endforeach
        </div>
        <a href="/my-announcements" class="button">View All My Announcements</a>
    </div>
    
    <div class="card">
        <div class="card-title">Agricultural Lands of Interest</div>
        @livewire('land-search-results', ['clientId' => $client->id])
    </div>
</div>