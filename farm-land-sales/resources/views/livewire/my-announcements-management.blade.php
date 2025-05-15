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
    
    <div class="filter-section">
        <div class="search-filter">
            <input type="text" placeholder="Search announcements..." wire:model.debounce.300ms="search">
            
            <select wire:model="status">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            
            <select wire:model="type">
                <option value="">All Types</option>
                <option value="land">Agricultural Land</option>
                <option value="equipment">Farm Equipment</option>
            </select>
            
            <button class="button button-small" wire:click="resetFilters">Reset</button>
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('announcements.create') }}" class="button">Create New Announcement</a>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Type</th>
                <th>Price</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($announcements as $announcement)
            <tr>
                <td>
                    @if($announcement->image)
                        <img src="{{ asset('storage/' . $announcement->image) }}" class="table-image" alt="{{ $announcement->titre }}">
                    @else
                        <div class="no-image">No Image</div>
                    @endif
                </td>
                <td>{{ $announcement->titre }}</td>
                <td>{{ ucfirst($announcement->type) }}</td>
                <td>{{ $announcement->prix }} €</td>
                <td>{{ $announcement->dateCreation->format('M d, Y') }}</td>
                <td>
                    <span class="status-badge status-{{ $announcement->estActif ? 'active' : 'inactive' }}">
                        {{ $announcement->estActif ? 'Active' : 'Pending Approval' }}
                    </span>
                </td>
                <td>
                    <div class="button-group">
                        <a href="{{ route('announcements.edit', $announcement->id) }}" class="button button-small">Edit</a>
                        <button class="button button-small {{ $announcement->estActif ? 'button-warning' : 'button-success' }}" 
                                wire:click="toggleStatus({{ $announcement->id }})"
                                onclick="confirm('Are you sure you want to {{ $announcement->estActif ? 'deactivate' : 'activate' }} this announcement?') || event.stopImmediatePropagation()">
                            {{ $announcement->estActif ? 'Deactivate' : 'Activate' }}
                        </button>
                        <button class="button button-small button-danger" 
                                wire:click="deleteAnnouncement({{ $announcement->id }})"
                                onclick="confirm('Are you sure you want to delete this announcement?') || event.stopImmediatePropagation()">
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
            
            @if(count($announcements) === 0)
            <tr>
                <td colspan="7" class="empty-table">You don't have any announcements yet.</td>
            </tr>
            @endif
        </tbody>
    </table>
    
    <div class="pagination-container">
        {{ $announcements->links() }}
    </div>
</div>

<div class="info-card">
    <h3>Announcement Tips</h3>
    <ul>
        <li>Add high-quality images to make your announcement stand out.</li>
        <li>Provide detailed descriptions to attract more potential buyers.</li>
        <li>Set a competitive price based on market values.</li>
        <li>Keep your announcements updated with the latest information.</li>
    </ul>
</div>