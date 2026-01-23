@extends('layouts.app')

@section('title', 'Messagerie')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Liste des conversations -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-comments me-2"></i>Conversations
                    </h5>
                    <a href="{{ route('messages.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($conversations->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($conversations as $conversation)
                                <?php 
                                $otherUser = $conversation->sender_id === auth()->id() ? $conversation->receiver : $conversation->sender;
                                $unreadCount = $otherUser->receivedMessages()->where('sender_id', auth()->id())->where('is_read', false)->count();
                                ?>
                                <a href="{{ route('messages.show', $otherUser->id) }}" 
                                   class="list-group-item list-group-item-action d-flex align-items-center {{ $unreadCount > 0 ? 'bg-light' : '' }}">
                                    <div class="flex-shrink-0 me-3">
                                        @if($otherUser->avatar)
                                            <img src="{{ $otherUser->avatar }}" alt="{{ $otherUser->name }}" 
                                                 class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white" 
                                                 style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $otherUser->name }}</h6>
                                            <small class="text-muted">{{ $conversation->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-0 text-muted small">
                                            {{ Str::limit($conversation->content, 50) }}
                                        </p>
                                    </div>
                                    @if($unreadCount > 0)
                                        <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune conversation</p>
                            <a href="{{ route('messages.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Commencer une conversation
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Détails de la conversation ou liste des messages -->
        <div class="col-md-8">
            <!-- Onglets pour les messages reçus/envoyés -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="messagesTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="received-tab" data-bs-toggle="tab" data-bs-target="#received" type="button" role="tab">
                                <i class="fas fa-inbox me-2"></i>Messages reçus
                                @if($receivedMessages->where('is_read', false)->count() > 0)
                                    <span class="badge bg-danger ms-1">{{ $receivedMessages->where('is_read', false)->count() }}</span>
                                @endif
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" type="button" role="tab">
                                <i class="fas fa-paper-plane me-2"></i>Messages envoyés
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="messagesTabContent">
                        <!-- Messages reçus -->
                        <div class="tab-pane fade show active" id="received" role="tabpanel">
                            @if($receivedMessages->count() > 0)
                                <div class="list-group">
                                    @foreach($receivedMessages as $message)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-shrink-0 me-3">
                                                        @if($message->sender->avatar)
                                                            <img src="{{ $message->sender->avatar }}" alt="{{ $message->sender->name }}" 
                                                                 class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white" 
                                                                 style="width: 40px; height: 40px;">
                                                                {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">
                                                            {{ $message->sender->name }}
                                                            <span class="badge bg-{{ $message->sender->role === 'admin' ? 'danger' : ($message->sender->role === 'provider' ? 'success' : 'info') }} ms-2">
                                                                {{ $message->sender->role === 'admin' ? 'Admin' : ($message->sender->role === 'provider' ? 'Prestataire' : 'Client') }}
                                                            </span>
                                                        </h6>
                                                        <p class="mb-1">{{ $message->content }}</p>
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>{{ $message->created_at->format('d/m/Y H:i') }}
                                                            @if(!$message->is_read)
                                                                <span class="badge bg-warning ms-2">Non lu</span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="{{ route('messages.show', $message->sender->id) }}" class="dropdown-item">
                                                                <i class="fas fa-reply me-2"></i>Répondre
                                                            </a>
                                                        </li>
                                                        @if(!$message->is_read)
                                                            <li>
                                                                <form action="{{ route('messages.read', $message->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="fas fa-check me-2"></i>Marquer comme lu
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun message reçu</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Messages envoyés -->
                        <div class="tab-pane fade" id="sent" role="tabpanel">
                            @if($sentMessages->count() > 0)
                                <div class="list-group">
                                    @foreach($sentMessages as $message)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-shrink-0 me-3">
                                                        @if($message->receiver->avatar)
                                                            <img src="{{ $message->receiver->avatar }}" alt="{{ $message->receiver->name }}" 
                                                                 class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" 
                                                                 style="width: 40px; height: 40px;">
                                                                {{ strtoupper(substr($message->receiver->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">
                                                            À: {{ $message->receiver->name }}
                                                            <span class="badge bg-{{ $message->receiver->role === 'admin' ? 'danger' : ($message->receiver->role === 'provider' ? 'success' : 'info') }} ms-2">
                                                                {{ $message->receiver->role === 'admin' ? 'Admin' : ($message->receiver->role === 'provider' ? 'Prestataire' : 'Client') }}
                                                            </span>
                                                        </h6>
                                                        <p class="mb-1">{{ $message->content }}</p>
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>{{ $message->created_at->format('d/m/Y H:i') }}
                                                            <span class="badge bg-{{ $message->is_read ? 'success' : 'warning' }} ms-2">
                                                                {{ $message->is_read ? 'Lu' : 'Non lu' }}
                                                            </span>
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="{{ route('messages.show', $message->receiver->id) }}" class="dropdown-item">
                                                                <i class="fas fa-reply me-2"></i>Continuer la conversation
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-paper-plane fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun message envoyé</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script pour la recherche d'utilisateurs -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonctionnalité de recherche si nécessaire
    const searchInput = document.getElementById('userSearch');
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const query = this.value.trim();
                if (query.length > 2) {
                    fetch(`{{ route('messages.search-users') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            // Afficher les résultats de recherche
                            console.log(data);
                        });
                }
            }, 300);
        });
    }
});
</script>
@endsection
