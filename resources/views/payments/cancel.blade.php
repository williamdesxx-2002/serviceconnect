@extends('layouts.app')

@section('title', 'Paiement annulé')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center">
                <!-- Cancel Icon -->
                <div class="mb-4">
                    <div class="rounded-circle bg-warning d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-times text-white fa-2x"></i>
                    </div>
                </div>

                <!-- Cancel Message -->
                <h2 class="mb-3">Paiement annulé</h2>
                <p class="text-muted mb-4">
                    Votre paiement a été annulé. Votre réservation est toujours en attente de paiement.
                </p>

                <!-- Booking Details -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Détails de la réservation</h6>
                        <div class="row text-start">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Service:</strong></p>
                                <p class="mb-2"><strong>Prestataire:</strong></p>
                                <p class="mb-2"><strong>Date:</strong></p>
                                <p class="mb-2"><strong>Heure:</strong></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">{{ $booking->service->title }}</p>
                                <p class="mb-2">{{ $booking->service->user->name }}</p>
                                <p class="mb-2">{{ $booking->date->format('d/m/Y') }}</p>
                                <p class="mb-2">{{ $booking->time }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Montant à payer:</span>
                            <strong class="text-warning h5">{{ number_format($booking->service->price, 0) }} FCFA</strong>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <a href="{{ route('payments.pay', $booking) }}" class="btn btn-primary">
                        <i class="fas fa-redo me-2"></i>Réessayer le paiement
                    </a>
                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-eye me-2"></i>Voir la réservation
                    </a>
                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>Mes réservations
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
