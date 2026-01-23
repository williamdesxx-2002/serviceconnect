@extends('layouts.app')

@section('title', 'Réservations du Prestataire')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            <div class="sidebar">
                <div class="p-3">
                    <h6 class="text-uppercase fw-bold mb-3">Admin</h6>
                    <nav class="nav flex-column">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="nav-link">
                            <i class="fas fa-users me-2"></i>Utilisateurs
                        </a>
                        <a href="{{ route('admin.services.index') }}" class="nav-link">
                            <i class="fas fa-briefcase me-2"></i>Services
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" class="nav-link active">
                            <i class="fas fa-calendar me-2"></i>Réservations
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="nav-link">
                            <i class="fas fa-tags me-2"></i>Catégories
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="nav-link">
                            <i class="fas fa-chart-bar me-2"></i>Rapports
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-calendar me-2"></i>Réservations du Prestataire
                            <span class="badge bg-primary ms-2">{{ $bookings->total() }}</span>
                        </h5>
                        <p class="text-muted mb-0 mt-1">
                            <i class="fas fa-user me-1"></i>{{ $provider->name }} ({{ $provider->email }})
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>N° Réservation</th>
                                        <th>Service</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Signalement</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td>
                                                <code>{{ $booking->booking_number }}</code>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($booking->service->images && count($booking->service->images) > 0)
                                                        <img src="{{ $booking->service->images[0] }}" alt="{{ $booking->service->title }}" 
                                                             class="me-2" style="width: 30px; height: 30px; object-fit: cover; border-radius: 3px;">
                                                    @else
                                                        <div class="bg-primary d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 30px; height: 30px; border-radius: 3px;">
                                                            <span class="text-white" style="font-size: 10px;">{{ strtoupper(substr($booking->service->title, 0, 1)) }}</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ Str::limit($booking->service->title, 25) }}</div>
                                                        <small class="text-muted">{{ $booking->service->category->name ?? 'Non catégorisé' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($booking->client->avatar)
                                                        <img src="{{ $booking->client->avatar }}" alt="{{ $booking->client->name }}" 
                                                             class="me-2" style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%;">
                                                    @else
                                                        <div class="bg-secondary d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 30px; height: 30px; border-radius: 50%;">
                                                            <span class="text-white" style="font-size: 10px;">{{ strtoupper(substr($booking->client->name, 0, 1)) }}</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $booking->client->name }}</div>
                                                        <small class="text-muted">{{ $booking->client->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</div>
                                                    <small class="text-muted">{{ $booking->booking_time ?? 'Heure non spécifiée' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">{{ number_format($booking->total_amount, 0, ',', ' ') }} XAF</span>
                                            </td>
                                            <td>
                                                @switch($booking->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">En attente</span>
                                                        @break
                                                    @case('confirmed')
                                                        <span class="badge bg-info">Confirmée</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-success">Terminée</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger">Annulée</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $booking->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($booking->is_reported)
                                                    <span class="badge bg-danger" title="{{ $booking->report_reason }}">
                                                        <i class="fas fa-exclamation-triangle"></i> Signalé
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-outline-primary" title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if(!$booking->is_reported)
                                                        <button type="button" class="btn btn-outline-danger" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#reportModal{{ $booking->id }}" 
                                                                title="Signaler">
                                                            <i class="fas fa-flag"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune réservation trouvée</h5>
                            <p class="text-muted">Ce prestataire n'a aucune réservation pour le moment.</p>
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals de signalement -->
@foreach($bookings as $booking)
    @if(!$booking->is_reported)
        <div class="modal fade" id="reportModal{{ $booking->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.bookings.report', $booking) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-flag me-2"></i>Signaler la réservation
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="reason{{ $booking->id }}" class="form-label">Motif du signalement *</label>
                                <select name="reason" id="reason{{ $booking->id }}" class="form-select" required>
                                    <option value="">Sélectionnez un motif</option>
                                    <option value="fraud">Tentative de fraude</option>
                                    <option value="inappropriate">Contenu inapproprié</option>
                                    <option value="spam">Spam</option>
                                    <option value="violence">Menaces ou violence</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="description{{ $booking->id }}" class="form-label">Description</label>
                                <textarea name="description" id="description{{ $booking->id }}" class="form-control" rows="3" 
                                          placeholder="Décrivez en détail le problème..."></textarea>
                            </div>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Attention :</strong> Ce signalement sera visible par les administrateurs et pourra entraîner des sanctions contre le prestataire.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-flag me-1"></i>Signaler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
