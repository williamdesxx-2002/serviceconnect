@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <!-- Carte Profil -->
            <div class="card">
                <div class="card-body text-center">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 120px; height: 120px;">
                            <span class="text-white display-4">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        @if($user->is_verified)
                            <span class="badge bg-success"><i class="fas fa-check-circle"></i> Vérifié</span>
                        @endif
                        @if($user->is_active)
                            <span class="badge bg-primary"><i class="fas fa-circle"></i> Actif</span>
                        @endif
                        <span class="badge bg-info">
                            @if($user->role === 'admin') Administrateur
                            @elseif($user->role === 'provider') Prestataire
                            @elseif($user->role === 'client') Client
                            @endif
                        </span>
                    </div>
                    
                    <div class="text-start">
                        @if($user->phone)
                            <p class="mb-2"><i class="fas fa-phone me-2"></i>{{ $user->phone }}</p>
                        @endif
                        @if($user->whatsapp_number)
                            <p class="mb-2"><i class="fas fa-whatsapp me-2"></i>{{ $user->whatsapp_number }}</p>
                        @endif
                        @if($user->address)
                            <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>{{ $user->address }}</p>
                        @endif
                        @if($user->city)
                            <p class="mb-2"><i class="fas fa-city me-2"></i>{{ $user->city }}</p>
                        @endif
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier mon profil
                        </a>
                        
                        @if($user->isProvider())
                            <a href="{{ route('services.my') }}" class="btn btn-outline-primary">
                                <i class="fas fa-briefcase me-2"></i>Mes services
                            </a>
                        @endif
                        
                        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-calendar me-2"></i>Mes réservations
                        </a>
                        
                        <a href="{{ route('messages.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-envelope me-2"></i>Mes messages
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <!-- Informations détaillées -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nom complet:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Téléphone:</strong> {{ $user->phone ?: 'Non renseigné' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>WhatsApp:</strong> {{ $user->whatsapp_number ?: 'Non renseigné' }}</p>
                            <p><strong>Ville:</strong> {{ $user->city ?: 'Non renseignée' }}</p>
                            <p><strong>Pays:</strong> {{ $user->country ?: 'Non renseigné' }}</p>
                        </div>
                    </div>
                    
                    @if($user->bio)
                        <div class="mt-3">
                            <strong>Biographie:</strong>
                            <p class="mt-2">{{ $user->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Statistiques -->
            @if($user->isProvider())
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="stat-card border-left-primary">
                                    <h3>{{ $user->services()->count() }}</h3>
                                    <p class="text-muted mb-0">Services publiés</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card border-left-success">
                                    <h3>{{ $user->receivedBookings()->where('status', 'completed')->count() }}</h3>
                                    <p class="text-muted mb-0">Réservations terminées</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card border-left-info">
                                    <h3>{{ $user->receivedReviews()->count() }}</h3>
                                    <p class="text-muted mb-0">Avis reçus</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card border-left-warning">
                                    <h3>{{ $user->averageRating() ? number_format($user->averageRating(), 1) : 'N/A' }}</h3>
                                    <p class="text-muted mb-0">Note moyenne</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-edit me-2"></i>Modifier mes informations
                            </a>
                        </div>
                        @if($user->isProvider())
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('services.create') }}" class="btn btn-outline-success w-100">
                                    <i class="fas fa-plus me-2"></i>Ajouter un service
                                </a>
                            </div>
                        @endif
                        <div class="col-md-6 mb-2">
                            <a href="{{ route('messages.index') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-comments me-2"></i>Messagerie
                            </a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
