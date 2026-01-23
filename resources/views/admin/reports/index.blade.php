@extends('layouts.app')

@section('title', 'Tableau de Bord Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            @include('admin.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <!-- Statistiques principales -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['total_users'] }}</h4>
                                    <p class="mb-0">Utilisateurs</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['total_services'] }}</h4>
                                    <p class="mb-0">Services</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-briefcase fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['total_bookings'] }}</h4>
                                    <p class="mb-0">Réservations</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['total_categories'] }}</h4>
                                    <p class="mb-0">Catégories</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-tags fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques détaillées -->
            <div class="row mb-4">
                <div class="col-md-2 mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="text-primary">{{ $stats['active_users'] }}</h5>
                            <small class="text-muted">Utilisateurs actifs</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2 mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="text-success">{{ $stats['verified_users'] }}</h5>
                            <small class="text-muted">Utilisateurs vérifiés</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2 mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="text-danger">{{ $stats['blocked_users'] }}</h5>
                            <small class="text-muted">Utilisateurs bloqués</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2 mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="text-warning">{{ $stats['reported_services'] }}</h5>
                            <small class="text-muted">Services signalés</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2 mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="text-info">{{ $stats['reported_bookings'] }}</h5>
                            <small class="text-muted">Réservations signalées</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques et tableaux -->
            <div class="row">
                <!-- Revenus mensuels -->
                <div class="col-md-8 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>Revenus Mensuels
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($monthlyRevenue->count() > 0)
                                <canvas id="revenueChart" height="100"></canvas>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune donnée de revenus disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Services par catégorie -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-tags me-2"></i>Top Catégories
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($servicesByCategory->count() > 0)
                                @foreach($servicesByCategory as $category)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            @if($category->icon)
                                                <span class="me-2">{{ $category->icon }}</span>
                                            @endif
                                            <span>{{ $category->name }}</span>
                                        </div>
                                        <span class="badge bg-primary">{{ $category->services_count }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-tags fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune catégorie trouvée</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activités récentes -->
            <div class="row">
                <!-- Utilisateurs récents -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-users me-2"></i>Utilisateurs Récents
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($recentUsers->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentUsers as $user)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-info d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 30px; height: 30px; border-radius: 50%;">
                                                    <span class="text-white small">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $user->name }}</div>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'provider' ? 'primary' : 'success') }}">
                                                    {{ $user->role }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-users fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun utilisateur récent</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Réservations récentes -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar me-2"></i>Réservations Récentes
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($recentBookings->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentBookings as $booking)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="fw-bold">{{ $booking->service->title }}</div>
                                                    <small class="text-muted">
                                                        {{ $booking->client->name }} → {{ $booking->service->user->name }}
                                                    </small>
                                                </div>
                                                <div>
                                                    <span class="badge bg-{{ $booking->status == 'completed' ? 'success' : ($booking->status == 'confirmed' ? 'primary' : 'warning') }}">
                                                        {{ $booking->status }}
                                                    </span>
                                                    <small class="text-muted d-block">{{ number_format($booking->total_amount, 0) }} FCFA</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune réservation récente</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($monthlyRevenue->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Préparer les données
        const labels = {!! json_encode($monthlyRevenue->pluck('month')->map(function($month) {
            $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
            return $months[$month - 1] ?? 'Mois ' . $month;
        })) !!};
        
        const data = {!! json_encode($monthlyRevenue->pluck('revenue')) !!};
        
        // Créer le graphique
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenus (FCFA)',
                    data: data,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
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
    });
</script>
@endif
@endsection
