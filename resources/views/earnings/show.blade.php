@extends('layouts.app')

@section('title', 'Détails du paiement')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('provider.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('earnings') }}">Revenus</a></li>
                    <li class="breadcrumb-item active">Détails du paiement</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Détails du paiement #{{ $payment->id }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Service</h6>
                            <p class="text-muted">{{ $payment->booking->service->title }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Client</h6>
                            <p class="text-muted">{{ $payment->booking->client->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Date du paiement</h6>
                            <p class="text-muted">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Statut</h6>
                            <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ $payment->status === 'completed' ? 'Complété' : ($payment->status === 'pending' ? 'En attente' : 'Annulé') }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Montant total</h6>
                            <h4 class="text-primary">{{ number_format($payment->amount, 0) }} FCFA</h4>
                        </div>
                        <div class="col-md-6">
                            <h6>Méthode de paiement</h6>
                            <p class="text-muted">{{ $payment->method ?? 'Non spécifiée' }}</p>
                        </div>
                    </div>

                    @if($payment->notes)
                        <div class="mb-4">
                            <h6>Notes</h6>
                            <p class="text-muted">{{ $payment->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('bookings.show', $payment->booking) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                        <i class="fas fa-eye"></i> Voir la réservation
                    </a>
                    
                    <a href="{{ route('messages.show', $payment->booking->client) }}" class="btn btn-outline-success btn-sm w-100 mb-2">
                        <i class="fas fa-envelope"></i> Contacter le client
                    </a>
                    
                    <a href="{{ route('earnings') }}" class="btn btn-secondary btn-sm w-100">
                        <i class="fas fa-arrow-left"></i> Retour aux revenus
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
