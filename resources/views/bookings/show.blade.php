@extends('layouts.app')

@section('title', 'Détails de la Réservation')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Détails de la réservation #{{ $booking->booking_number }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Informations du service</h6>
                            <div class="d-flex align-items-center mb-3">
                                @if($booking->service->images && count($booking->service->images) > 0)
                                    <img src="{{ $booking->service->images[0] }}" alt="{{ $booking->service->title }}" 
                                         class="me-3" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                @else
                                    <div class="bg-primary d-flex align-items-center justify-content-center me-3" 
                                         style="width: 80px; height: 80px; border-radius: 8px;">
                                        <span class="text-white h4">{{ strtoupper(substr($booking->service->title, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-1">{{ $booking->service->title }}</h5>
                                    <p class="text-muted mb-0">{{ Str::limit($booking->service->description, 100) }}</p>
                                    <small class="text-primary">
                                        <i class="fas fa-user me-1"></i>{{ $booking->service->user->name }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Date et heure</h6>
                            <p class="mb-2">
                                <i class="fas fa-calendar me-2"></i>
                                <strong>{{ $booking->booking_date->format('d/m/Y') }}</strong>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-clock me-2"></i>
                                <strong>{{ $booking->booking_date->format('H:i') }}</strong>
                            </p>
                            @if($booking->duration)
                                <p class="mb-2">
                                    <i class="fas fa-hourglass-half me-2"></i>
                                    Durée : {{ $booking->duration }}h
                                </p>
                            @endif
                        </div>
                    </div>

                    @if($booking->notes)
                        <div class="mb-4">
                            <h6>Notes</h6>
                            <p class="text-muted">{{ $booking->notes }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Client</h6>
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary d-flex align-items-center justify-content-center me-3" 
                                     style="width: 40px; height: 40px; border-radius: 50%;">
                                    <span class="text-white">{{ strtoupper(substr($booking->client->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <strong>{{ $booking->client->name }}</strong><br>
                                    <small class="text-muted">{{ $booking->client->email }}</small><br>
                                    @if($booking->client->phone)
                                        <small class="text-muted">{{ $booking->client->phone }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Prestataire</h6>
                            <div class="d-flex align-items-center">
                                <div class="bg-info d-flex align-items-center justify-content-center me-3" 
                                     style="width: 40px; height: 40px; border-radius: 50%;">
                                    <span class="text-white">{{ strtoupper(substr($booking->service->user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <strong>{{ $booking->service->user->name }}</strong><br>
                                    <small class="text-muted">{{ $booking->service->user->email }}</small><br>
                                    @if($booking->service->user->phone)
                                        <small class="text-muted">{{ $booking->service->user->phone }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Statut et Actions -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Statut</h6>
                </div>
                <div class="card-body">
                    @switch($booking->status)
                        @case('pending')
                            <span class="badge bg-warning fs-6">
                                <i class="fas fa-clock me-1"></i>En attente
                            </span>
                            @break
                        @case('confirmed')
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check me-1"></i>Confirmée
                            </span>
                            @break
                        @case('cancelled')
                            <span class="badge bg-danger fs-6">
                                <i class="fas fa-times me-1"></i>Annulée
                            </span>
                            @break
                        @case('completed')
                            <span class="badge bg-primary fs-6">
                                <i class="fas fa-check-circle me-1"></i>Terminée
                            </span>
                            @break
                        @default
                            <span class="badge bg-secondary fs-6">{{ $booking->status }}</span>
                    @endswitch

                    <div class="mt-3">
                        <h6>Prix total</h6>
                        <h4 class="text-primary">{{ number_format($booking->total_amount, 0) }} FCFA</h4>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        @if(auth()->user()->isProvider() && $booking->status === 'pending')
                            <form action="{{ route('bookings.confirm', $booking) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i>Confirmer
                                </button>
                            </form>
                        @endif

                        @if(auth()->user()->isProvider() && $booking->status === 'confirmed')
                            <form action="{{ route('bookings.complete', $booking) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check-circle me-2"></i>Marquer terminée
                                </button>
                            </form>
                        @endif

                        @if((auth()->user()->isClient() && $booking->status === 'pending') || (auth()->user()->isAdmin() && in_array($booking->status, ['pending', 'confirmed'])))
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="fas fa-times me-2"></i>Annuler
                            </button>
                        @endif

                        <a href="{{ route('messages.show', $booking->client_id == auth()->id() ? $booking->service->user : $booking->client) }}" 
                           class="btn btn-outline-info">
                            <i class="fas fa-envelope me-2"></i>Contacter
                        </a>
                    </div>
                </div>
            </div>

            <!-- Paiement -->
            @if($booking->payment)
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Paiement</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Statut :</strong> 
                            @switch($booking->payment->status)
                                @case('pending')
                                    <span class="badge bg-warning">En attente</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success">Payé</span>
                                    @break
                                @case('failed')
                                    <span class="badge bg-danger">Échoué</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $booking->payment->status }}</span>
                            @endswitch
                        </p>
                        <p class="mb-2">
                            <strong>Montant :</strong> {{ number_format($booking->payment->amount, 0) }} FCFA
                        </p>
                        <p class="mb-0">
                            <strong>Date :</strong> {{ $booking->payment->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal d'annulation -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                    Annuler la réservation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('bookings.cancel', $booking) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>Important
                        </h6>
                        <p class="mb-0">
                            L'annulation de cette réservation est <strong>irréversible</strong>. 
                            Veuillez indiquer la raison de cette annulation.
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">
                            <i class="fas fa-comment me-1"></i>Raison de l'annulation *
                        </label>
                        <textarea class="form-control" 
                                  id="cancellation_reason" 
                                  name="cancellation_reason" 
                                  rows="4" 
                                  required
                                  placeholder="Veuillez expliquer pourquoi vous annulez cette réservation..."></textarea>
                        <div class="form-text">
                            Soyez précis dans votre explication pour aider le prestataire à s'améliorer.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-danger" id="confirmCancelBtn">
                        <i class="fas fa-check me-2"></i>Confirmer l'annulation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cancelModal = document.getElementById('cancelModal');
    const confirmCancelBtn = document.getElementById('confirmCancelBtn');
    const cancellationReason = document.getElementById('cancellation_reason');
    
    // Réinitialiser le modal à la fermeture
    cancelModal.addEventListener('hidden.bs.modal', function () {
        cancellationReason.value = '';
        confirmCancelBtn.disabled = false;
        confirmCancelBtn.innerHTML = '<i class="fas fa-check me-2"></i>Confirmer l\'annulation';
    });
    
    // Validation du formulaire
    const cancelForm = cancelModal.querySelector('form');
    cancelForm.addEventListener('submit', function(e) {
        const reason = cancellationReason.value.trim();
        
        if (reason.length < 10) {
            e.preventDefault();
            alert('Veuillez fournir une raison d\'annulation plus détaillée (minimum 10 caractères).');
            return;
        }
        
        // Désactiver le bouton et afficher le chargement
        confirmCancelBtn.disabled = true;
        confirmCancelBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Annulation en cours...';
    });
    
    // Validation en temps réel
    cancellationReason.addEventListener('input', function() {
        const charCount = this.value.trim().length;
        const formText = this.parentElement.querySelector('.form-text');
        
        if (charCount < 10) {
            formText.innerHTML = '<span class="text-danger">Minimum 10 caractères requis (' + charCount + '/10)</span>';
            confirmCancelBtn.disabled = true;
        } else {
            formText.innerHTML = 'Soyez précis dans votre explication pour aider le prestataire à s\'améliorer.';
            confirmCancelBtn.disabled = false;
        }
    });
});
</script>
@endpush
