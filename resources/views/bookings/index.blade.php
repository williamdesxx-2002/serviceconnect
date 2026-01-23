@extends('layouts.app')

@section('title', 'Mes Réservations')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        @if(auth()->user()->isAdmin())
                            Toutes les réservations
                        @elseif(auth()->user()->isProvider())
                            Mes réservations (Prestataire)
                        @else
                            Mes réservations (Client)
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if($bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>@if(auth()->user()->isAdmin() || auth()->user()->isProvider()) Client @endif</th>
                                        @if(auth()->user()->isAdmin())<th>Prestataire</th>@endif
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Prix</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($booking->service->images && count($booking->service->images) > 0)
                                                        <img src="{{ $booking->service->images[0] }}" alt="{{ $booking->service->title }}" 
                                                             class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                                    @else
                                                        <div class="bg-primary d-flex align-items-center justify-content-center me-3" 
                                                             style="width: 50px; height: 50px; border-radius: 8px;">
                                                            <span class="text-white">{{ strtoupper(substr($booking->service->title, 0, 1)) }}</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ Str::limit($booking->service->title, 30) }}</h6>
                                                        <small class="text-muted">{{ $booking->service->category->name ?? 'Non catégorisé' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if(auth()->user()->isAdmin() || auth()->user()->isProvider())
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-secondary d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 30px; height: 30px; border-radius: 50%;">
                                                            <span class="text-white">{{ strtoupper(substr($booking->client->name, 0, 1)) }}</span>
                                                        </div>
                                                        <span>{{ $booking->client->name }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                            @if(auth()->user()->isAdmin())
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-info d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 30px; height: 30px; border-radius: 50%;">
                                                            <span class="text-white">{{ strtoupper(substr($booking->service->user->name, 0, 1)) }}</span>
                                                        </div>
                                                        <span>{{ $booking->service->user->name }}</span>
                                                    </div>
                                                </td>
                                            @endif
                                            <td>
                                                <div>
                                                    <i class="fas fa-calendar me-1"></i>{{ $booking->booking_date->format('d/m/Y H:i') }}
                                                </div>
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
                                                <strong>{{ number_format($booking->total_amount, 0) }} FCFA</strong>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @if(auth()->user()->isProvider() && $booking->status === 'pending')
                                                        <form action="{{ route('bookings.confirm', $booking) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-sm btn-success" title="Confirmer">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if(auth()->user()->isProvider() && $booking->status === 'confirmed')
                                                        <form action="{{ route('bookings.complete', $booking) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-sm btn-primary" title="Marquer comme terminée">
                                                                <i class="fas fa-check-circle"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if(auth()->user()->isClient() && $booking->status === 'completed')
                                                        @if(!$booking->review)
                                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $booking->id }}" title="Noter le prestataire">
                                                                <i class="fas fa-star"></i>
                                                            </button>
                                                        @else
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check-circle me-1"></i>Noté
                                                            </span>
                                                        @endif
                                                    @endif
                                                    
                                                    @if((auth()->user()->isClient() || auth()->user()->isProvider()) && $booking->status === 'pending')
                                                        <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Annuler">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
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
                            <h5 class="text-muted">Aucune réservation</h5>
                            <p class="text-muted">
                                @if(auth()->user()->isAdmin())
                                    Aucune réservation n'a été effectuée pour le moment.
                                @elseif(auth()->user()->isProvider())
                                    Vous n'avez pas encore reçu de réservations.
                                @else
                                    Vous n'avez pas encore effectué de réservations.
                                @endif
                            </p>
                            @if(auth()->user()->isClient())
                                <a href="{{ route('services.index') }}" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Découvrir les services
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour noter les prestataires -->
@foreach($bookings as $booking)
    @if(auth()->user()->isClient() && $booking->status === 'completed' && !$booking->review)
        <div class="modal fade" id="reviewModal{{ $booking->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-star me-2 text-warning"></i>
                            Noter le prestataire
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                            <input type="hidden" name="service_id" value="{{ $booking->service_id }}">
                            <input type="hidden" name="provider_id" value="{{ $booking->service->user_id }}">
                            <input type="hidden" name="client_id" value="{{ auth()->id() }}">
                            
                            <div class="mb-3">
                                <label class="form-label">Prestataire</label>
                                <div class="d-flex align-items-center">
                                    @if($booking->service->user->avatar)
                                        <img src="{{ $booking->service->user->avatar }}" alt="{{ $booking->service->user->name }}" 
                                             class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" 
                                             style="width: 40px; height: 40px; font-size: 14px;">
                                            {{ substr($booking->service->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $booking->service->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $booking->service->title }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Note</label>
                                <div class="d-flex gap-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $booking->id }}{{ $i }}" class="d-none" required>
                                        <label for="star{{ $booking->id }}{{ $i }}" class="star-rating fs-3 text-warning cursor-pointer" data-rating="{{ $i }}">
                                            <i class="far fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="comment" class="form-label">Commentaire (optionnel)</label>
                                <textarea name="comment" id="comment" class="form-control" rows="3" 
                                          placeholder="Partagez votre expérience avec ce prestataire..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-star me-2"></i>Envoyer la note
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

<style>
.star-rating {
    transition: all 0.2s ease;
}

.star-rating:hover,
.star-rating:hover ~ .star-rating {
    color: #ffc107 !important;
}

input[type="radio"]:checked + .star-rating {
    color: #ffc107 !important;
}

input[type="radio"]:checked + .star-rating ~ .star-rating {
    color: #ffc107 !important;
}

.cursor-pointer {
    cursor: pointer;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gérer l'interaction des étoiles pour chaque modal
    document.querySelectorAll('.star-rating').forEach(function(star) {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            const modal = this.closest('.modal');
            const stars = modal.querySelectorAll('.star-rating');
            
            // Mettre à jour l'affichage des étoiles
            stars.forEach(function(s, index) {
                const icon = s.querySelector('i');
                if (index < rating) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                }
            });
        });
        
        star.addEventListener('mouseenter', function() {
            const rating = this.dataset.rating;
            const modal = this.closest('.modal');
            const stars = modal.querySelectorAll('.star-rating');
            
            // Effet hover
            stars.forEach(function(s, index) {
                const icon = s.querySelector('i');
                if (index < rating) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                }
            });
        });
    });
    
    // Réinitialiser les étoiles quand on quitte la zone
    document.querySelectorAll('.modal-body').forEach(function(modalBody) {
        modalBody.addEventListener('mouseleave', function() {
            const modal = this.closest('.modal');
            const checkedRadio = modal.querySelector('input[type="radio"]:checked');
            const stars = modal.querySelectorAll('.star-rating');
            
            stars.forEach(function(s, index) {
                const icon = s.querySelector('i');
                if (checkedRadio && index < checkedRadio.value) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                }
            });
        });
    });
});
</script>
</div>
@endsection
