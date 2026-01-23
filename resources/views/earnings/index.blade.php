@extends('layouts.app')

@section('title', 'Mes Revenus')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-3">
                <i class="fas fa-money-bill-wave me-2"></i>Mes Revenus
            </h2>
            <p class="text-muted">Suivez vos revenus et performances</p>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card animate-fade-in-up">
                <div class="card-body text-center">
                    <i class="fas fa-wallet fa-2x text-primary mb-3"></i>
                    <h3 class="mb-0">{{ number_format($totalEarnings, 0) }}</h3>
                    <small class="text-muted">FCFA gagnés</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card animate-fade-in-up delay-200">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                    <h3 class="mb-0">{{ $completedBookings }}</h3>
                    <small class="text-muted">Services complétés</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card animate-fade-in-up delay-400">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-2x text-info mb-3"></i>
                    <h3 class="mb-0">{{ number_format($averageEarningPerBooking, 0) }}</h3>
                    <small class="text-muted">FCFA par service</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card animate-fade-in-up delay-500">
                <div class="card-body text-center">
                    <i class="fas fa-hourglass-half fa-2x text-warning mb-3"></i>
                    <h3 class="mb-0">{{ $pendingPayments->count() }}</h3>
                    <small class="text-muted">Paiements en attente</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique des revenus mensuels -->
    @if($monthlyEarnings->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card animate-fade-in-up">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Revenus des 6 derniers mois
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="earningsChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Paiements en attente -->
    @if($pendingPayments->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card animate-fade-in-up">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>Paiements en attente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingPayments as $payment)
                                        <tr>
                                            <td>{{ $payment->booking->service->title }}</td>
                                            <td>{{ $payment->booking->client->name }}</td>
                                            <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                            <td>{{ number_format($payment->amount, 0) }} FCFA</td>
                                            <td>
                                                <a href="{{ route('bookings.show', $payment->booking) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
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
    @endif

    <!-- Revenus récents -->
    <div class="row">
        <div class="col-12">
            <div class="card animate-fade-in-up">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Revenus récents
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentEarnings->count() > 0)
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
                                    @foreach($recentEarnings as $payment)
                                        <tr>
                                            <td>{{ $payment->booking->service->title }}</td>
                                            <td>{{ $payment->booking->client->name }}</td>
                                            <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="fw-bold text-success">{{ number_format($payment->amount, 0) }} FCFA</td>
                                            <td>
                                                <span class="badge bg-success">Complété</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('earnings.show', $payment) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('messages.show', $payment->booking->client) }}" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun revenu enregistré pour le moment.</p>
                            <a href="{{ route('services.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Créer un service
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($monthlyEarnings->count() > 0)
        // Graphique des revenus mensuels
        const ctx = document.getElementById('earningsChart').getContext('2d');
        const monthlyData = @json($monthlyEarnings->pluck('total'));
        const monthlyLabels = @json($monthlyEarnings->pluck('month')->map(function($month) {
            return \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y');
        }));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Revenus (FCFA)',
                    data: monthlyData,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 2,
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
    @endif
});
</script>
@endsection
