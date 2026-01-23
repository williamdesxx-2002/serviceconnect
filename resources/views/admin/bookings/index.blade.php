@extends('layouts.app')

@section('title', 'Gestion des Réservations')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            @include('admin.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar me-2"></i>Gestion des Réservations
                        <span class="badge bg-primary ms-2">{{ $bookings->total() }}</span>
                    </h5>
                    <div>
                        <form class="d-inline" method="GET" action="{{ route('admin.bookings.index') }}">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher une réservation..." value="{{ request('search') }}">
                                <select name="status" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                </select>
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
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
                                        <th>Prestataire</th>
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
                                                    <div class="bg-secondary d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 25px; height: 25px; border-radius: 50%;">
                                                        <span class="text-white" style="font-size: 10px;">{{ strtoupper(substr($booking->client->name, 0, 1)) }}</span>
                                                    </div>
                                                    <div>
                                                        <div>{{ $booking->client->name }}</div>
                                                        <small class="text-muted">{{ $booking->client->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 25px; height: 25px; border-radius: 50%;">
                                                        <span class="text-white" style="font-size: 10px;">{{ strtoupper(substr($booking->service->user->name, 0, 1)) }}</span>
                                                    </div>
                                                    <div>
                                                        <div>{{ $booking->service->user->name }}</div>
                                                        <small class="text-muted">{{ $booking->service->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $booking->booking_date->format('d/m/Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $booking->booking_date->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <strong class="text-primary">{{ number_format($booking->total_amount, 0) }} FCFA</strong>
                                            </td>
                                            <td>
                                                @switch($booking->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>En attente
                                                        </span>
                                                        @break
                                                    @case('confirmed')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check me-1"></i>Confirmée
                                                        </span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times me-1"></i>Annulée
                                                        </span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-primary">
                                                            <i class="fas fa-check-circle me-1"></i>Terminée
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $booking->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($booking->is_reported)
                                                    <span class="badge bg-danger" title="{{ $booking->report_reason }}">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>Signalé
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-shield-alt me-1"></i>Conforme
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary" title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <a href="{{ route('admin.bookings.provider', $booking->service->user) }}" class="btn btn-sm btn-outline-info" title="Voir autres réservations du prestataire">
                                                        <i class="fas fa-user"></i>
                                                    </a>
                                                    
                                                    @if(!$booking->is_reported)
                                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                                data-bs-toggle="modal" data-bs-target="#reportModal{{ $booking->id }}" title="Signaler">
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
                            <p class="text-muted">Aucune réservation n'a été effectuée pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de signalement -->
@foreach($bookings as $booking)
    @if(!$booking->is_reported)
        <div class="modal fade" id="reportModal{{ $booking->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-flag me-2"></i>Signaler la réservation
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.bookings.report', $booking) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Raison du signalement</label>
                                <select class="form-select" name="reason" required>
                                    <option value="">Choisir une raison...</option>
                                    <option value="Service non rendu">Service non rendu</option>
                                    <option value="Paiement frauduleux">Paiement frauduleux</option>
                                    <option value="Comportement inapproprié">Comportement inapproprié</option>
                                    <option value="Annulation abusive">Annulation abusive</option>
                                    <option value="Autre">Autre</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description détaillée</label>
                                <textarea class="form-control" name="description" rows="4" 
                                          placeholder="Décrivez en détail le problème..."></textarea>
                            </div>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Attention :</strong> Ce signalement sera visible par les deux parties et pourra entraîner des sanctions.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-flag me-2"></i>Signaler la réservation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
