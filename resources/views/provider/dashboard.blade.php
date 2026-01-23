@extends('layouts.app')

@section('title', 'Tableau de bord Prestataire')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="sidebar">
                <div class="p-3">
                    <h6 class="text-uppercase fw-bold mb-3">Menu Prestataire</h6>
                    <nav class="nav flex-column">
                        <a href="{{ route('provider.dashboard') }}" class="nav-link active">
                            <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                        </a>
                        <a href="{{ route('services.my') }}" class="nav-link">
                            <i class="fas fa-briefcase me-2"></i>Mes services
                        </a>
                        <a href="{{ route('services.create') }}" class="nav-link">
                            <i class="fas fa-plus me-2"></i>Ajouter un service
                        </a>
                        <a href="{{ route('bookings.index') }}" class="nav-link">
                            <i class="fas fa-calendar me-2"></i>Réservations
                        </a>
                        <a href="{{ route('messages.index') }}" class="nav-link">
                            <i class="fas fa-envelope me-2"></i>Messages
                        </a>
                        <a href="{{ route('profile') }}" class="nav-link">
                            <i class="fas fa-user me-2"></i>Mon profil
                        </a>
                        <a href="{{ route('reviews.my') }}" class="nav-link">
                            <i class="fas fa-star me-2"></i>Mes avis
                        </a>
                        <a href="{{ route('earnings') }}" class="nav-link">
                            <i class="fas fa-money-bill me-2"></i>Revenus
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Welcome Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Bienvenue, {{ auth()->user()->name }} !</h4>
                            <p class="text-muted">Gérez vos services et vos réservations</p>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('services.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus-circle me-2"></i>Ajouter un service
                            </a>
                            <div class="mt-2">
                                @if(auth()->user()->is_verified)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Compte vérifié
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>En attente de vérification
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="stat-card border-left-primary card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase text-muted mb-1">Services</h6>
                                    <h3 class="mb-0">{{ auth()->user()->services()->count() }}</h3>
                                </div>
                                <div class="text-primary">
                                    <i class="fas fa-briefcase fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card border-left-success card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase text-muted mb-1">Réservations</h6>
                                    <h3 class="mb-0">{{ auth()->user()->providerBookings()->count() }}</h3>
                                </div>
                                <div class="text-success">
                                    <i class="fas fa-calendar fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card border-left-info card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase text-muted mb-1">Note moyenne</h6>
                                    <h3 class="mb-0">{{ number_format(auth()->user()->averageRating(), 1) }}</h3>
                                </div>
                                <div class="text-info">
                                    <i class="fas fa-star fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card border-left-warning card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase text-muted mb-1">Revenus</h6>
                                    <h3 class="mb-0">{{ number_format($totalRevenue, 0) }}</h3>
                                </div>
                                <div class="text-warning">
                                    <i class="fas fa-money-bill fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('services.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-2"></i>Ajouter un nouveau service
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-calendar me-2"></i>Voir les réservations
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Réservations récentes
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $recentBookings = auth()->user()->providerBookings()->with('service', 'client')->latest()->limit(5)->get();
                    @endphp
                    
                    @if($recentBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBookings as $booking)
                                        <tr>
                                            <td>{{ $booking->service->title }}</td>
                                            <td>{{ $booking->client->name }}</td>
                                            <td>{{ $booking->booking_date->format('d/m/Y') }}</td>
                                            <td>{{ number_format($booking->total_amount, 0) }} FCFA</td>
                                            <td>
                                                <span class="badge 
                                                    @if($booking->status === 'completed') bg-success
                                                    @elseif($booking->status === 'confirmed') bg-primary
                                                    @elseif($booking->status === 'cancelled') bg-danger
                                                    @else bg-warning
                                                    @endif">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @if($booking->status === 'pending')
                                                        <a href="{{ route('bookings.confirm', $booking) }}" class="btn btn-outline-success">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                            <h6>Aucune réservation</h6>
                            <p class="text-muted">Vous n'avez pas encore de réservations</p>
                            <a href="{{ route('services.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Créer un service
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- My Services -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-briefcase me-2"></i>Mes services
                        </h5>
                        <a href="{{ route('services.my') }}" class="btn btn-sm btn-outline-primary">
                            Voir tout
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $myServices = auth()->user()->services()->with('category')->latest()->limit(3)->get();
                    @endphp
                    
                    <div class="row">
                        @foreach($myServices as $service)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <span class="badge-category">
                                                {{ $service->category->name }}
                                            </span>
                                        </div>
                                        <h6 class="card-title">{{ Str::limit($service->title, 30) }}</h6>
                                        <p class="text-muted small">{{ Str::limit($service->description, 50) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-primary fw-bold">
                                                {{ number_format($service->price, 0) }} FCFA
                                            </span>
                                            <span class="badge 
                                                @if($service->status === 'approved') bg-success
                                                @elseif($service->status === 'pending') bg-warning
                                                @else bg-danger
                                                @endif">
                                                {{ ucfirst($service->status) }}
                                            </span>
                                        </div>
                                        <div class="mt-2">
                                            <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
