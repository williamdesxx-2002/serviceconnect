@extends('layouts.app')

@section('title', 'Tableau de bord Client')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="sidebar">
                <div class="p-3">
                    <h6 class="text-uppercase fw-bold mb-3">Menu Client</h6>
                    <nav class="nav flex-column">
                        <a href="{{ route('client.dashboard') }}" class="nav-link active">
                            <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                        </a>
                        <a href="{{ route('services.index') }}" class="nav-link">
                            <i class="fas fa-search me-2"></i>Rechercher des services
                        </a>
                        <a href="{{ route('bookings.index') }}" class="nav-link">
                            <i class="fas fa-calendar me-2"></i>Mes réservations
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
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Welcome Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h4>Bienvenue, {{ auth()->user()->name }} !</h4>
                    <p class="text-muted">Retrouvez ici toutes vos activités sur ServiceConnect</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="stat-card border-left-primary card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase text-muted mb-1">Réservations</h6>
                                    <h3 class="mb-0">{{ auth()->user()->clientBookings()->count() }}</h3>
                                </div>
                                <div class="text-primary">
                                    <i class="fas fa-calendar fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card border-left-success card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase text-muted mb-1">Complétées</h6>
                                    <h3 class="mb-0">{{ auth()->user()->clientBookings()->where('status', 'completed')->count() }}</h3>
                                </div>
                                <div class="text-success">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card border-left-info card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase text-muted mb-1">Avis donnés</h6>
                                    <h3 class="mb-0">{{ auth()->user()->reviews()->count() }}</h3>
                                </div>
                                <div class="text-info">
                                    <i class="fas fa-star fa-2x"></i>
                                </div>
                            </div>
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
                        $recentBookings = auth()->user()->clientBookings()->with('service', 'provider')->latest()->limit(5)->get();
                    @endphp
                    
                    @if($recentBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Prestataire</th>
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
                                            <td>{{ $booking->provider->name }}</td>
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
                                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
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
                            <a href="{{ route('services.index') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Découvrir les services
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Popular Services -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-fire me-2"></i>Services populaires
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $popularServices = \App\Models\Service::with('user', 'category')
                            ->where('is_active', true)
                            ->where('status', 'approved')
                            ->orderBy('rating', 'desc')
                            ->limit(6)
                            ->get();
                    @endphp
                    
                    <div class="row">
                        @foreach($popularServices as $service)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ Str::limit($service->title, 30) }}</h6>
                                        <p class="text-muted small">{{ $service->category->name }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-primary fw-bold">
                                                {{ number_format($service->price, 0) }} FCFA
                                            </span>
                                            <div class="rating">
                                                <i class="fas fa-star"></i>
                                                <span class="ms-1">{{ number_format($service->rating, 1) }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-outline-primary mt-2">
                                            Voir détails
                                        </a>
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
