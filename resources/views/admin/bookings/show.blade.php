@extends('layouts.app')

@section('title', 'Détails de la Réservation')

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
                    <h4 class="mb-1">
                        <i class="fas fa-calendar me-2"></i>Détails de la Réservation
                    </h4>
                    <p class="text-muted mb-0">
                        N° <code>{{ $booking->booking_number }}</code>
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Retour
                    </a>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="row">
                <!-- Service Info -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-briefcase me-2"></i>Service
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                @if($booking->service->images && count($booking->service->images) > 0)
                                    <img src="{{ $booking->service->images[0] }}" alt="{{ $booking->service->title }}" 
                                         class="me-3" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                @else
                                    <div class="bg-primary d-flex align-items-center justify-content-center me-3" 
                                         style="width: 80px; height: 80px; border-radius: 8px;">
                                        <span class="text-white" style="font-size: 24px;">{{ strtoupper(substr($booking->service->title, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-2">{{ $booking->service->title }}</h6>
                                    <p class="text-muted mb-2">{{ $booking->service->description }}</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-light text-dark">{{ $booking->service->category->name ?? 'Non catégorisé' }}</span>
                                        <span class="badge bg-primary">{{ number_format($booking->service->price, 0) }} XAF</span>
                                        @if($booking->service->status === 'pending')
                                            <span class="badge bg-warning">En attente</span>
                                        @elseif($booking->service->status === 'approved')
                                            <span class="badge bg-success">Approuvé</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status & Amount -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Statut & Montant
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <label class="text-muted">Statut</label>
                                    <div>
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
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted">Montant total</label>
                                    <div>
                                        <h5 class="text-primary mb-0">{{ number_format($booking->total_amount, 0) }} XAF</h5>
                                    </div>
                                </div>
                            </div>
                            @if($booking->is_reported)
                                <hr>
                                <div class="alert alert-danger">
                                    <h6 class="mb-2">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Réservation signalée
                                    </h6>
                                    <p class="mb-1"><strong>Motif :</strong> {{ $booking->report_reason }}</p>
                                    @if($booking->report_description)
                                        <p class="mb-0"><strong>Description :</strong> {{ $booking->report_description }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- People Info -->
            <div class="row">
                <!-- Client Info -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-user me-2"></i>Client
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px; border-radius: 50%;">
                                    <span class="text-white" style="font-size: 20px;">{{ strtoupper(substr($booking->client->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $booking->client->name }}</h6>
                                    <p class="text-muted mb-0">{{ $booking->client->email }}</p>
                                    @if($booking->client->phone)
                                        <p class="text-muted mb-0">{{ $booking->client->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Provider Info -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-user-tie me-2"></i>Prestataire
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-info d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px; border-radius: 50%;">
                                    <span class="text-white" style="font-size: 20px;">{{ strtoupper(substr($booking->service->user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $booking->service->user->name }}</h6>
                                    <p class="text-muted mb-0">{{ $booking->service->user->email }}</p>
                                    @if($booking->service->user->phone)
                                        <p class="text-muted mb-0">{{ $booking->service->user->phone }}</p>
                                    @endif
                                    <div class="mt-2">
                                        @if($booking->service->user->is_verified)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Vérifié
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-exclamation me-1"></i>Non vérifié
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date & Time Info -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt me-2"></i>Date & Heure
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <label class="text-muted">Date de réservation</label>
                                    <div>
                                        <h6 class="mb-0">{{ $booking->booking_date->format('d/m/Y') }}</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted">Heure</label>
                                    <div>
                                        <h6 class="mb-0">{{ $booking->booking_date->format('H:i') }}</h6>
                                    </div>
                                </div>
                            </div>
                            @if($booking->duration)
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="text-muted">Durée</label>
                                        <div>
                                            <h6 class="mb-0">{{ $booking->duration }}</h6>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-credit-card me-2"></i>Paiement
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($booking->payment)
                                <div class="row">
                                    <div class="col-6">
                                        <label class="text-muted">Statut</label>
                                        <div>
                                            @switch($booking->payment->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">En attente</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-success">Complété</span>
                                                    @break
                                                @case('failed')
                                                    <span class="badge bg-danger">Échoué</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $booking->payment->status }}</span>
                                            @endswitch
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-muted">Montant</label>
                                        <div>
                                            <h6 class="mb-0">{{ number_format($booking->payment->amount, 0) }} XAF</h6>
                                        </div>
                                    </div>
                                </div>
                                @if($booking->payment->paid_at)
                                    <hr>
                                    <div class="row">
                                        <div class="col-12">
                                            <label class="text-muted">Date de paiement</label>
                                            <div>
                                                <h6 class="mb-0">{{ $booking->payment->paid_at->format('d/m/Y H:i') }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <p class="text-muted mb-0">Aucun paiement enregistré</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Info -->
            @if($booking->review)
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-star me-2"></i>Avis du Client
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="text-muted">Note</label>
                                        <div class="mb-3">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $booking->review->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-2">{{ $booking->review->rating }}/5</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted">Date de l'avis</label>
                                        <div class="mb-3">
                                            <h6 class="mb-0">{{ $booking->review->created_at->format('d/m/Y H:i') }}</h6>
                                        </div>
                                    </div>
                                </div>
                                @if($booking->review->comment)
                                    <hr>
                                    <div>
                                        <label class="text-muted">Commentaire</label>
                                        <p class="mb-0">{{ $booking->review->comment }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Notes -->
            @if($booking->notes)
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-sticky-note me-2"></i>Notes
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $booking->notes }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                                </a>
                                @if(!$booking->is_reported)
                                    <button type="button" class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#reportModal">
                                        <i class="fas fa-flag me-1"></i>Signaler
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Modal -->
@if(!$booking->is_reported)
    <div class="modal fade" id="reportModal" tabindex="-1">
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
                            <label for="reason" class="form-label">Motif du signalement *</label>
                            <select name="reason" id="reason" class="form-select" required>
                                <option value="">Sélectionnez un motif</option>
                                <option value="fraud">Tentative de fraude</option>
                                <option value="inappropriate">Contenu inapproprié</option>
                                <option value="spam">Spam</option>
                                <option value="violence">Menaces ou violence</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" 
                                      placeholder="Décrivez en détail le problème..."></textarea>
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Attention :</strong> Ce signalement sera visible par les administrateurs et pourra entraîner des sanctions.
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
@endsection
