<div>
    <h2>Dashboard</h2>
    
    @if($userType == 'admin')
        @livewire('admin-dashboard')
    @elseif($userType == 'client')
        @livewire('client-dashboard')
    @elseif($userType == 'fournisseur')
        @livewire('supplier-dashboard')
    @else
        @livewire('user-dashboard')
    @endif
</div>