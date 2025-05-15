<div>
    <div class="card">
        <div class="card-title">User Management</div>
        
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        
        <div class="user-management-header">
            <div class="search-filter">
                <input type="text" placeholder="Search users..." wire:model.debounce.300ms="search">
                
                <select wire:model="userType">
                    <option value="all">All Users</option>
                    <option value="client">Clients</option>
                    <option value="fournisseur">Suppliers</option>
                    <option value="admin">Administrators</option>
                    <option value="unassigned">Unassigned</option>
                </select>
            </div>
            
            <button class="button" wire:click="openModal">Add New User</button>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" class="sortable">
                        ID
                        @if ($sortField === 'id')
                            <span class="sort-icon">{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('name')" class="sortable">
                        Name
                        @if ($sortField === 'name')
                            <span class="sort-icon">{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('email')" class="sortable">
                        Email
                        @if ($sortField === 'email')
                            <span class="sort-icon">{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                        @endif
                    </th>
                    <th>Role</th>
                    <th wire:click="sortBy('created_at')" class="sortable">
                        Created At
                        @if ($sortField === 'created_at')
                            <span class="sort-icon">{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                        @endif
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->administrateur)
                            <span class="role-badge admin">Administrator</span>
                        @elseif($user->client)
                            <span class="role-badge client">Client</span>
                        @elseif($user->fournisseur)
                            <span class="role-badge supplier">Supplier</span>
                        @else
                            <span class="role-badge unassigned">Unassigned</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <button class="button button-small" wire:click="openModal({{ $user->id }})">Edit</button>
                        <button class="button button-small button-danger" 
                                wire:click="deleteUser({{ $user->id }})"
                                onclick="confirm('Are you sure you want to delete this user? This action cannot be undone.') || event.stopImmediatePropagation()">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="pagination-container">
            {{ $users->links() }}
        </div>
        
        @if($showModal)
        <div class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>{{ $isEditing ? 'Edit User' : 'Add New User' }}</h3>
                    <button type="button" class="modal-close" wire:click="closeModal">&times;</button>
                </div>
                
                <div class="modal-body">
                    <form wire:submit.prevent="saveUser">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" wire:model="name">
                            @error('name') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" wire:model="email">
                            @error('email') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password {{ $isEditing ? '(Leave blank to keep current password)' : '' }}</label>
                            <input type="password" id="password" wire:model="password">
                            @error('password') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select id="role" wire:model="role">
                                <option value="client">Client</option>
                                <option value="fournisseur">Supplier</option>
                                <option value="admin">Administrator</option>
                            </select>
                            @error('role') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="modal-buttons">
                            <button type="button" class="button button-secondary" wire:click="closeModal">Cancel</button>
                            <button type="submit" class="button">{{ $isEditing ? 'Update User' : 'Create User' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>