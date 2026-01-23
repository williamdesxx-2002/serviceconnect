@extends('layouts.app')

@section('title', 'Nouveau message')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-envelope me-2"></i>Composer un message
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="receiverSearch" class="form-label">Destinataire</label>
                            <div class="position-relative">
                                <input type="text" 
                                       class="form-control" 
                                       id="receiverSearch" 
                                       placeholder="Rechercher un utilisateur..."
                                       autocomplete="off">
                                <input type="hidden" name="receiver_id" id="receiver_id" required>
                                
                                <!-- Résultats de recherche -->
                                <div id="searchResults" class="position-absolute w-100 bg-white border rounded shadow-sm" style="z-index: 1000; max-height: 200px; overflow-y: auto; display: none;">
                                </div>
                            </div>
                            <div class="form-text">Commencez à taper le nom ou l'email de l'utilisateur</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Message</label>
                            <textarea class="form-control" 
                                      name="content" 
                                      id="content" 
                                      rows="5" 
                                      required
                                      placeholder="Tapez votre message ici..."
                                      maxlength="1000"></textarea>
                            <div class="form-text">
                                <span id="charCount">0</span>/1000 caractères
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="urgent" name="urgent">
                                <label class="form-check-label" for="urgent">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Marquer comme urgent
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                            </button>
                            <a href="{{ route('messages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Guide d'utilisation -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Guide d'utilisation
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-users me-2 text-primary"></i>Communication</h6>
                                    <ul class="small">
                                        <li>Les clients peuvent contacter les prestataires</li>
                                        <li>Les prestataires peuvent contacter les clients</li>
                                        <li>L'administrateur peut contacter tout le monde</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-shield-alt me-2 text-success"></i>Sécurité</h6>
                                    <ul class="small">
                                        <li>Vous ne pouvez pas vous envoyer de messages</li>
                                        <li>Les messages sont privés et sécurisés</li>
                                        <li>Signalez tout abus au support</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('receiverSearch');
    const receiverIdInput = document.getElementById('receiver_id');
    const searchResults = document.getElementById('searchResults');
    const contentTextarea = document.getElementById('content');
    const charCount = document.getElementById('charCount');
    let selectedUser = null;
    
    // Compteur de caractères
    contentTextarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
    
    // Recherche d'utilisateurs
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            fetch(`{{ route('messages.search-users') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(users => {
                    searchResults.innerHTML = '';
                    searchResults.style.display = users.length > 0 ? 'block' : 'none';
                    
                    users.forEach(user => {
                        const userDiv = document.createElement('div');
                        userDiv.className = 'p-2 border-bottom user-result';
                        userDiv.style.cursor = 'pointer';
                        
                        const roleColor = user.role === 'admin' ? 'danger' : (user.role === 'provider' ? 'success' : 'info');
                        const roleText = user.role === 'admin' ? 'Admin' : (user.role === 'provider' ? 'Prestataire' : 'Client');
                        
                        userDiv.innerHTML = `
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    ${user.avatar ? 
                                        `<img src="${user.avatar}" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">` :
                                        `<div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px;">
                                            ${user.name.charAt(0).toUpperCase()}
                                        </div>`
                                    }
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">${user.name}</div>
                                    <div class="small text-muted">${user.email}</div>
                                    <span class="badge bg-${roleColor}">${roleText}</span>
                                </div>
                            </div>
                        `;
                        
                        userDiv.addEventListener('click', function() {
                            selectedUser = user;
                            receiverIdInput.value = user.id;
                            searchInput.value = user.name;
                            searchResults.style.display = 'none';
                        });
                        
                        searchResults.appendChild(userDiv);
                    });
                })
                .catch(error => console.error('Error:', error));
        }, 300);
    });
    
    // Fermer les résultats en cliquant ailleurs
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
    
    // Styles pour les résultats
    const style = document.createElement('style');
    style.textContent = `
        .user-result:hover {
            background-color: #f8f9fa;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection
