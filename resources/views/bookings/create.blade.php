@extends('layouts.app')

@section('title', 'Réserver un service')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('services.show', $service) }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="mb-1">Réserver un service</h2>
                    <p class="text-muted mb-0">Complétez les informations pour votre réservation</p>
                </div>
            </div>

            <!-- Service Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title mb-2">{{ $service->title }}</h5>
                            <p class="text-muted mb-2">{{ Str::limit($service->description, 100) }}</p>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-primary">{{ $service->category->name }}</span>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <span>{{ number_format($service->rating, 1) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="price-display">
                                <h3 class="text-primary mb-0">{{ number_format($service->price, 0) }} FCFA</h3>
                                <small class="text-muted">Prix total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Informations de réservation
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf
                        
                        <!-- Service ID -->
                        <input type="hidden" name="service_id" value="{{ $service->id }}">

                        <!-- Provider Info -->
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <div class="provider-avatar me-3">
                                    @if($service->user->avatar)
                                        <img src="{{ $service->user->avatar }}" alt="{{ $service->user->name }}" class="rounded-circle">
                                    @else
                                        <div class="avatar-placeholder rounded-circle">
                                            {{ strtoupper(substr($service->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $service->user->name }}</h6>
                                    <p class="text-muted mb-0">Prestataire • 
                                        @if($service->user->is_verified)
                                            <span class="text-success"><i class="fas fa-check-circle"></i> Vérifié</span>
                                        @else
                                            <span class="text-warning"><i class="fas fa-exclamation-circle"></i> Non vérifié</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Date Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="booking_date" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>Date de réservation *
                                </label>
                                <input type="datetime-local" 
                                       class="form-control @error('booking_date') is-invalid @enderror" 
                                       id="booking_date" 
                                       name="booking_date" 
                                       required
                                       min="{{ now()->format('Y-m-d\TH:i') }}"
                                       value="{{ old('booking_date') }}">
                                @error('booking_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="text-muted">Sélectionnez une date future</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-clock me-1"></i>Durée estimée
                                </label>
                                <div class="form-control-plaintext">
                                    {{ $service->duration ?? 'Non spécifiée' }}
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">
                                <i class="fas fa-comment me-1"></i>Notes (optionnel)
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="4"
                                      placeholder="Précisez vos besoins ou questions spécifiques...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">Informations supplémentaires pour le prestataire</small>
                        </div>

                        <!-- Service Details -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Détails du service
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>Catégorie:</strong> {{ $service->category->name }}
                                        </p>
                                        <p class="mb-2">
                                            <strong>Lieu:</strong> {{ $service->location ?? 'Non spécifié' }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>Disponibilité:</strong> {{ $service->availability ?? 'Non spécifié' }}
                                        </p>
                                        <p class="mb-2">
                                            <strong>Équipement:</strong> {{ $service->equipment ?? 'Non spécifié' }}
                                        </p>
                                    </div>
                                </div>
                                @if($service->description)
                                    <div class="mt-3">
                                        <strong>Description:</strong>
                                        <p class="text-muted mb-0">{{ $service->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="alert alert-warning mb-4">
                            <h6 class="alert-heading">
                                <i class="fas fa-exclamation-triangle me-2"></i>Conditions de réservation
                            </h6>
                            <ul class="mb-0">
                                <li>La réservation est confirmée après validation par le prestataire</li>
                                <li>Le paiement s'effectue après confirmation de la réservation</li>
                                <li>Annulation possible jusqu'à 24h avant la date prévue</li>
                                <li>Le prestataire peut refuser la réservation sans préavis</li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('services.show', $service) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fas fa-check me-2"></i>Confirmer la réservation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.provider-avatar {
    width: 50px;
    height: 50px;
    overflow: hidden;
}

.provider-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: #3b82f6;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.price-display h3 {
    font-size: 1.5rem;
    font-weight: bold;
}

.rating {
    color: #f59e0b;
    font-size: 0.875rem;
}

.card {
    border-radius: 15px;
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(59, 130, 246, 0.4);
}

.btn-primary:disabled {
    opacity: 0.6;
    transform: none;
    box-shadow: none;
}

.alert {
    border-radius: 10px;
    border: none;
}

.badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
}

.loading-spinner {
    display: none;
    width: 20px;
    height: 20px;
    border: 2px solid #ffffff;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 0.5rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    const originalContent = submitBtn.innerHTML;
    
    // Form submission
    form.addEventListener('submit', function(e) {
        // Validate date
        const bookingDate = document.getElementById('booking_date').value;
        const selectedDate = new Date(bookingDate);
        const now = new Date();
        
        if (selectedDate <= now) {
            e.preventDefault();
            alert('Veuillez sélectionner une date future.');
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading-spinner"></span>Traitement en cours...';
    });
    
    // Date validation on change
    document.getElementById('booking_date').addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const now = new Date();
        
        if (selectedDate <= now) {
            this.setCustomValidity('Veuillez sélectionner une date future.');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Auto-save notes to localStorage
    const notesTextarea = document.getElementById('notes');
    const savedNotes = localStorage.getItem('booking_notes_' + {{ $service->id }});
    
    if (savedNotes && !notesTextarea.value) {
        notesTextarea.value = savedNotes;
    }
    
    notesTextarea.addEventListener('input', function() {
        localStorage.setItem('booking_notes_' + {{ $service->id }}, this.value);
    });
    
    // Clear localStorage on successful submission
    form.addEventListener('submit', function() {
        localStorage.removeItem('booking_notes_' + {{ $service->id }});
    });
});
</script>
@endpush
