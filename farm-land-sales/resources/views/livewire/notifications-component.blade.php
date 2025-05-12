<div>
    <div class="card">
        <div class="card-title">Notifications</div>
        
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        
        <div class="notifications-header">
            <div class="unread-counter">
                <span>{{ $unreadCount }}</span> unread notifications
            </div>
            
            @if($unreadCount > 0)
                <button class="button button-small" wire:click="markAllAsRead">
                    Mark All as Read
                </button>
            @endif
        </div>
        
        <div class="notifications-list">
            @if(count($notifications) > 0)
                @foreach($notifications as $notification)
                <div class="notification-item {{ $notification->lu ? '' : 'unread' }}">
                    <div class="notification-icon">
                        @if($notification->type === 'message')
                            <i class="notification-icon-message">✉️</i>
                        @elseif($notification->type === 'transaction')
                            <i class="notification-icon-transaction">💰</i>
                        @elseif($notification->type === 'system')
                            <i class="notification-icon-system">🔔</i>
                        @endif
                    </div>
                    
                    <div class="notification-content">
                        <div class="notification-message">{{ $notification->contenu }}</div>
                        <div class="notification-time">{{ $notification->dateCreation->diffForHumans() }}</div>
                    </div>
                    
                    <div class="notification-actions">
                        @if(!$notification->lu)
                            <button class="button button-small button-transparent" wire:click="markAsRead({{ $notification->id }})">
                                Mark as Read
                            </button>
                        @endif
                        
                        <button class="button button-small button-transparent button-danger" 
                                wire:click="deleteNotification({{ $notification->id }})"
                                onclick="confirm('Delete this notification?') || event.stopImmediatePropagation()">
                            Delete
                        </button>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-notifications">
                    <p>You don't have any notifications.</p>
                </div>
            @endif
        </div>
    </div>
</div>