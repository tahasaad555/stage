@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h3">Mes Notifications</h1>
    </div>
    <div class="col text-end">
        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-check-double me-1"></i> Marquer tout comme lu
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                @if($notifications->isEmpty())
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i> Vous n'avez pas de notifications.
                    </div>
                @else
                    <div class="list-group">
                        @foreach($notifications as $notification)
                            <div class="list-group-item list-group-item-action {{ !$notification->lue ? 'bg-light' : '' }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $notification->titre }}</h5>
                                    <small>{{ $notification->dateCreation->format('d/m/Y H:i') }}</small>
                                </div>
                                <p class="mb-1">{{ $notification->contenu }}</p>
                                @if(!$notification->lue)
                                    <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-check me-1"></i> Marquer comme lu
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection