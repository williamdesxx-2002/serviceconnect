@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            @include('admin.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Tableau de bord administrateur</h2>
                    <p class="text-muted">Vue d'ensemble de la plateforme ServiceConnect</p>
                </div>
                <div>
                    <span class="badge bg-success">
                        <i class="fas fa-circle me-1"></i>En ligne
                    </span>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card border-left-primary card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase text-muted mb-1">Utilisateurs</h6>
                                    <h3 class="mb-0">{{ number_format($stats['total_users']) }}</h3>
                                    <small class="text-success">
                                        <i class="fas fa-arrow-up"></i> +{{ $stats['total_providers'] }} prestataires
                                    </small>
                                </div>
                                <div class="text-primary">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card border-left-success card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase text-muted mb-1">Services</h6>
                                    <h3 class="mb-0">{{ number_format($stats['total_services']) }}</h3>
                                    <small class="text-warning">
                                        <i class="fas fa-clock"></i> {{ $stats['pending_services'] }} en attente
                                    </small>
                                </div>
                                <div class="text-success">
                                    <i class="fas fa-briefcase fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card border-left-info card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase text-muted mb-1">Réservations</h6>
                                    <h3 class="mb-0">{{ number_format($stats['total_bookings']) }}</h3>
                                    <small class="text-success">
                                        <i class="fas fa-check"></i> {{ $stats['completed_bookings'] }} terminées
                                    </small>
                                </div>
                                <div class="text-info">
                                    <i class="fas fa-calendar fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card border-left-warning card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase text-muted mb-1">Revenus</h6>
                                    <h3 class="mb-0">{{ number_format($stats['total_revenue'], 0) }} FCFA</h3>
                                    <small class="text-info">
                                        <i class="fas fa-calendar-alt"></i> {{ number_format($stats['monthly_revenue'], 0) }} ce mois
                                    </small>
                                </div>
                                <div class="text-warning">
                                    <i class="fas fa-money-bill fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <!-- Revenue Chart -->
                <div class="col-xl-8 col-lg-7 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>Revenus mensuels
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Categories -->
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-tags me-2"></i>Catégories populaires
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($topCategories as $category)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-grow-1">
                                        <div class="small text-gray-500">{{ $category->name }}</div>
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar" 
                                                 style="width: {{ ($category->count / $topCategories->max('count')) * 100 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="small font-weight-bold text-gray-800">{{ $category->count }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tables Row -->
            <div class="row">
                <!-- Recent Bookings -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-history me-2"></i>Dernières réservations
                                </h5>
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">
                                    Voir tout
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Service</th>
                                            <th>Client</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentBookings as $booking)
                                            <tr>
                                                <td>{{ Str::limit($booking->service->title, 20) }}</td>
                                                <td>{{ $booking->client->name }}</td>
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Providers -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-trophy me-2"></i>Top prestataires
                                </h5>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                                    Voir tout
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Prestataire</th>
                                            <th>Réservations</th>
                                            <th>Note</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topProviders as $provider)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($provider->avatar)
                                                            <img src="{{ $provider->avatar }}" alt="{{ $provider->name }}" 
                                                                 class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                                        @else
                                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" 
                                                                 style="width: 30px; height: 30px;">
                                                                <span class="text-white small">{{ strtoupper(substr($provider->name, 0, 1)) }}</span>
                                                            </div>
                                                        @endif
                                                        <span>{{ $provider->name }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $provider->provider_bookings_count }}</td>
                                                <td>
                                                    <div class="rating">
                                                        <i class="fas fa-star"></i>
                                                        <span class="ms-1">{{ number_format($provider->averageRating(), 1) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="#" class="btn btn-outline-primary" title="Voir le profil">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if(!$provider->is_verified)
                                                            <a href="{{ route('admin.users.verify', $provider) }}" class="btn btn-outline-success">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($revenueChart->pluck('month')->map(fn($m) => date('F', mktime(0, 0, 0, $m, 1)))) !!},
        datasets: [{
            label: 'Revenus (FCFA)',
            data: {!! json_encode($revenueChart->pluck('total')) !!},
            borderColor: 'rgb(79, 70, 229)',
            backgroundColor: 'rgba(79, 70, 229, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' FCFA';
                    }
                }
            }
        }
    }
});
</script>
@endpush
@endsection
