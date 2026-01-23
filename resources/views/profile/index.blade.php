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
                            <i class="fas fa-user-tag"></i> 
                            {{ $user->isProvider() ? 'Prestataire' : ($user->isAdmin() ? 'Admin' : 'Client') }}
                        </span>
                    </div>
                    
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary w-100">
                        <i class="fas fa-edit me-2"></i>Modifier mon profil
                    </a>
                </div>
            </div>
            
            <!-- Statistiques -->
            @if($user->isProvider())
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Mes statistiques</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <h5 class="text-primary">{{ $user->services->count() }}</h5>
                                <small class="text-muted">Services</small>
                            </div>
                            <div class="col-4">
                                <h5 class="text-success">{{ $user->isProvider() ? $user->providerBookings()->count() : ($user->isClient() ? $user->clientBookings()->count() : 0) }}</h5>
                                <small class="text-muted">Réservations</small>
                            </div>
                            <div class="col-4">
                                <h5 class="text-warning">{{ number_format($user->averageRating(), 1) }}</h5>
                                <small class="text-muted">Note</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-md-8">
            <!-- Informations Personnelles -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nom complet :</strong> {{ $user->name }}</p>
                            <p><strong>Email :</strong> {{ $user->email }}</p>
                            <p><strong>Téléphone :</strong> {{ $user->phone ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>WhatsApp :</strong> {{ $user->whatsapp_number ?? 'Non configuré' }}</p>
                            <p><strong>Ville :</strong> {{ $user->city ?? 'Non renseignée' }}</p>
                            <p><strong>Pays :</strong> {{ $user->country ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                    
                    @if($user->bio)
                        <div class="mt-3">
                            <strong>Biographie :</strong>
                            <p class="mt-2">{{ $user->bio }}</p>
                        </div>
                    @endif
                    
                    @if($user->address)
                        <div class="mt-3">
                            <strong>Adresse :</strong>
                            <p class="mt-2">{{ $user->address }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($user->isProvider())
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('services.create') }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-plus me-2"></i>Ajouter un service
                                </a>
                            </div>
                        @endif
                        
                        <div class="col-md-6 mb-2">
                            <a href="{{ route('bookings.index') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-calendar me-2"></i>Mes réservations
                            </a>
                        </div>
                        
                        <div class="col-md-6 mb-2">
                            <a href="{{ route('messages.index') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-envelope me-2"></i>Mes messages
                            </a>
                        </div>
                        
                        @if($user->isAdmin())
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
