<div>
    <div class="messages-container">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        
        <div class="messages-sidebar">
            <div class="card-title">Your Messages</div>
            
            <div class="message-list">
                @foreach($messages as $message)
                <div class="message-item {{ $message->lu ? '' : 'unread' }}" 
                     wire:click="viewMessage({{ $message->id }})">
                    <div class="message-sender">From: {{ $message->expediteur->nom }}</div>
                    <div class="message-preview">{{ \Illuminate\Support\Str::limit($message->contenu, 50) }}</div>
                    <div class="message-date">{{ $message->dateEnvoi->format('M d, H:i') }}</div>
                </div>
                @endforeach
                
                @if(count($messages) === 0)
                <div class="empty-messages">
                    No messages in your inbox.
                </div>
                @endif
            </div>
        </div>
        
        <div class="message-content">
            @if($selectedMessage)
                <div class="message-header">
                    <h3>{{ $selectedMessage->expediteur->nom }}</h3>
                    <span>{{ $selectedMessage->dateEnvoi->format('F d, Y - H:i') }}</span>
                </div>
                
                <div class="message-body">
                    {{ $selectedMessage->contenu }}
                </div>
                
                <div class="reply-section">
                    <form wire:submit.prevent="sendReply">
                        <div class="form-group">
                            <label for="replyContent">Reply</label>
                            <textarea id="replyContent" wire:model="replyContent" rows="4"></textarea>
                            @error('replyContent') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        
                        <button type="submit" class="button">Send Reply</button>
                    </form>
                </div>
            @else
                <div class="empty-message-content">
                    Select a message to view its contents.
                </div>
            @endif
        </div>
    </div>
</div>