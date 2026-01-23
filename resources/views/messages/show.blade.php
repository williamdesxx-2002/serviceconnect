@extends('layouts.app')

@section('title', 'Conversation avec ' . $user->name)

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Informations sur l'utilisateur -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" 
                             class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white mb-3 mx-auto" 
                             style="width: 80px; height: 80px;">
                            <span class="display-4">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    
                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'provider' ? 'success' : 'info') }}">
                        {{ $user->role === 'admin' ? 'Administrateur' : ($user->role === 'provider' ? 'Prestataire' : 'Client') }}
                    </span>
                    
                    @if($user->is_verified)
                        <div class="mt-2">
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Vérifié
                            </span>
                        </div>
                    @endif
                    
                    <div class="mt-3">
                        @if(auth()->user()->isClient() && $user->isProvider())
                            <a href="{{ route('services.index', ['provider' => $user->id]) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-briefcase me-1"></i>Voir ses services
                            </a>
                        @endif
                        
                        @if(auth()->user()->isProvider() && $user->isClient())
                            <a href="{{ route('bookings.index', ['client' => $user->id]) }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-calendar me-1"></i>Voir ses réservations
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Actions rapides -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">Actions rapides</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('profile', $user->id) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-user me-1"></i>Voir le profil
                        </a>
                        @if($user->phone)
                            <a href="tel:{{ $user->phone }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-phone me-1"></i>Appeler
                            </a>
                        @endif
                        @if($user->whatsapp_number)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->whatsapp_number) }}" 
                               target="_blank" class="btn btn-outline-success btn-sm">
                                <i class="fab fa-whatsapp me-1"></i>WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Conversation -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-comments me-2"></i>Conversation
                    </h5>
                    <div>
                        <small class="text-muted me-3">
                            {{ $messages->count() }} message(s)
                        </small>
                        @if(auth()->user()->isAdmin())
                            <button class="btn btn-outline-warning btn-sm" onclick="toggleAdminActions()">
                                <i class="fas fa-shield-alt"></i>
                            </button>
                        @endif
                    </div>
                </div>
                
                <!-- Actions administrateur -->
                @if(auth()->user()->isAdmin())
                    <div id="adminActions" class="alert alert-warning alert-dismissible fade show" role="alert" style="display: none;">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Actions d'administrateur</h6>
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.users.block', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-ban me-1"></i>Bloquer
                                </button>
                            </form>
                            <form action="{{ route('admin.users.verify', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check me-1"></i>Vérifier
                                </button>
                            </form>
                        </div>
                        <button type="button" class="btn-close" onclick="toggleAdminActions()"></button>
                    </div>
                @endif
                
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    @if($messages->count() > 0)
                        <div class="conversation-messages">
                            @foreach($messages as $message)
                                <div class="message {{ $message->sender_id === auth()->id() ? 'message-sent' : 'message-received' }} mb-3">
                                    <div class="d-flex align-items-start {{ $message->sender_id === auth()->id() ? 'justify-content-end' : '' }}">
                                        <div class="message-content {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }} 
                                                     rounded-3 p-3" style="max-width: 70%;">
                                            <!-- En-tête du message -->
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="d-flex align-items-center">
                                                    @if($message->sender->avatar)
                                                        <img src="{{ $message->sender->avatar }}" alt="{{ $message->sender->name }}" 
                                                             class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-{{ $message->sender_id === auth()->id() ? 'light' : 'primary' }} 
                                                                 d-flex align-items-center justify-content-center text-{{ $message->sender_id === auth()->id() ? 'dark' : 'white' }} me-2" 
                                                             style="width: 24px; height: 24px; font-size: 12px;">
                                                            {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <small class="{{ $message->sender_id === auth()->id() ? 'text-white' : 'text-muted' }}">
                                                        {{ $message->sender->name }}
                                                    </small>
                                                </div>
                                                <small class="{{ $message->sender_id === auth()->id() ? 'text-white' : 'text-muted' }}">
                                                    {{ $message->created_at->format('H:i') }}
                                                    @if($message->sender_id !== auth()->id() && !$message->is_read)
                                                        <span class="badge bg-warning ms-1">Nouveau</span>
                                                    @endif
                                                </small>
                                            </div>
                                            
                                            <!-- Contenu du message -->
                                            <div class="message-text">
                                                {{ nl2br(e($message->content)) }}
                                            </div>
                                            
                                            <!-- Pièce jointe si existe -->
                                            @if($message->file_path)
                                                <div class="mt-2">
                                                    <a href="{{ asset($message->file_path) }}" target="_blank" 
                                                       class="btn btn-outline-secondary btn-sm">
                                                        <i class="fas fa-paperclip me-1"></i>
                                                        Pièce jointe
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun message dans cette conversation</p>
                            <p class="text-muted">Soyez le premier à envoyer un message !</p>
                        </div>
                    @endif
                </div>
                
                <!-- Formulaire de réponse rapide -->
                <div class="card-footer bg-light">
                    <form action="{{ route('messages.store') }}" method="POST" id="quickReplyForm">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        
                        <div class="input-group">
                            <textarea class="form-control" 
                                      name="content" 
                                      rows="2" 
                                      placeholder="Tapez votre réponse ici..."
                                      required
                                      maxlength="1000"></textarea>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.message-sent {
    text-align: right;
}

.message-received {
    text-align: left;
}

.message-content {
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.conversation-messages {
    min-height: 300px;
}

.message-text {
    word-wrap: break-word;
}
</style>

<script>
function toggleAdminActions() {
    const adminActions = document.getElementById('adminActions');
    adminActions.style.display = adminActions.style.display === 'none' ? 'block' : 'none';
}

// Auto-scroll vers le bas de la conversation
document.addEventListener('DOMContentLoaded', function() {
    const conversation = document.querySelector('.conversation-messages');
    if (conversation) {
        conversation.scrollTop = conversation.scrollHeight;
    }
});

// Scroll vers le bas après envoi d'un message
document.getElementById('quickReplyForm').addEventListener('submit', function() {
    setTimeout(() => {
        const conversation = document.querySelector('.conversation-messages');
        if (conversation) {
            conversation.scrollTop = conversation.scrollHeight;
        }
    }, 100);
});
</script>
@endsection
