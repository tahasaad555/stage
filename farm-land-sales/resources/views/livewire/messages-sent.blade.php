@if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="messages-tabs">
        <a href="{{ route('messages.inbox') }}" class="tab">Inbox</a>
        <a href="{{ route('messages.sent') }}" class="tab active">Sent</a>
    </div>
    
    <div class="search-box">
        <input type="text" placeholder="Search messages..." wire:model.debounce.300ms="search">
    </div>
    
    <div class="messages-list">
        @if(count($messages) > 0)
            @foreach($messages as $message)
            <div class="message-item">
                <div class="message-details">
                    <div class="message-recipient">To: {{ $message->destinataire->nom }}</div>
                    <div class="message-preview">{{ \Illuminate\Support\Str::limit($message->contenu, 100) }}</div>
                    <div class="message-date">{{ $message->dateEnvoi->format('M d, Y H:i') }}</div>
                </div>
                
                <div class="message-actions">
                    <button class="button button-small" wire:click="viewMessage({{ $message->id }})">View</button>
                    <button class="button button-small button-danger" 
                            wire:click="deleteMessage({{ $message->id }})"
                            onclick="confirm('Are you sure you want to delete this message?') || event.stopImmediatePropagation()">
                        Delete
                    </button>
                </div>
            </div>
            @endforeach
            
            <div class="pagination-container">
                {{ $messages->links() }}
            </div>
        @else
            <div class="empty-messages">
                <p>You haven't sent any messages yet.</p>
                <a href="{{ route('lands.search') }}" class="button">Browse Agricultural Lands</a>
                <a href="{{ route('equipment.search') }}" class="button">Browse Farm Equipment</a>
            </div>
        @endif
    </div>
</div>