@if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    
    <div class="filter-section">
        <div class="search-filter">
            <input type="text" placeholder="Search by title or client..." wire:model.debounce.300ms="search">
            
            <select wire:model="type">
                <option value="">All Types</option>
                <option value="land">Agricultural Land</option>
                <option value="equipment">Farm Equipment</option>
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
                <th>Title</th>
                <th>Type</th>
                <th>Price</th>
                <th>Client</th>
                <th>Submission Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingAnnouncements as $announcement)
            <tr>
                <td>{{ $announcement->id }}</td>
                <td>{{ $announcement->titre }}</td>
                <td>{{ ucfirst($announcement->type) }}</td>
                <td>{{ $announcement->prix }} €</td>
                <td>{{ $announcement->client->nom }} {{ $announcement->client->prenom }}</td>
                <td>{{ $announcement->dateCreation->format('M d, Y H:i') }}</td>
                <td>
                    <div class="button-group">
                        <button class="button button-small button-success" 
                                wire:click="approve({{ $announcement->id }})"
                                onclick="confirm('Are you sure you want to approve this announcement?') || event.stopImmediatePropagation()">
                            Approve
                        </button>
                        <button class="button button-small button-warning" 
                                wire:click="reject({{ $announcement->id }})"
                                onclick="confirm('Are you sure you want to reject this announcement?') || event.stopImmediatePropagation()">
                            Reject
                        </button>
                        <button class="button button-small button-danger" 
                                wire:click="delete({{ $announcement->id }})"
                                onclick="confirm('Are you sure you want to delete this announcement? This action cannot be undone.') || event.stopImmediatePropagation()">
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
            
            @if(count($pendingAnnouncements) === 0)
            <tr>
                <td colspan="7" class="empty-table">No pending announcements found.</td>
            </tr>
            @endif
        </tbody>
    </table>
    
    <div class="pagination-container">
        {{ $pendingAnnouncements->links() }}
    </div>
</div>

<div class="card">
    <div class="card-title">Review Guidelines</div>
    
    <div class="guidelines-content">
        <p>When reviewing announcements, please ensure they comply with the following guidelines:</p>
        
        <ul>
            <li>Content should not include offensive language or inappropriate material</li>
            <li>Images should be clear and properly represent the listed item</li>
            <li>Prices should be reasonable and within market ranges</li>
            <li>Descriptions should be detailed and accurate</li>
            <li>Contact information should not be included in the announcement (the platform's messaging system should be used)</li>
        </ul>
        
        <p>If any announcement violates these guidelines, it should be rejected with an appropriate explanation.</p>
    </div>
</div>