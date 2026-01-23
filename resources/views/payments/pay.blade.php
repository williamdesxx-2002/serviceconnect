@extends('layouts.app')

@section('title', 'Paiement de la réservation')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Header -->
            <div class="text-center mb-4">
                <h2 class="mb-3">
                    <i class="fas fa-credit-card me-2"></i>Payer votre réservation
                </h2>
                <p class="text-muted">Choisissez votre méthode de paiement</p>
            </div>

            <!-- Service Summary -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="card-title">{{ $booking->service->title }}</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Prestataire:</span>
                        <span>{{ $booking->service->user->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Date:</span>
                        <span>{{ $booking->date->format('d/m/Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Heure:</span>
                        <span>{{ $booking->time }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Total à payer:</strong>
                        <strong class="text-primary h5">{{ number_format($booking->service->price, 0) }} FCFA</strong>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <form action="{{ route('payments.pay', $booking) }}" method="POST">
                @csrf
                
                <!-- Payment Method Selection -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Méthode de paiement</h6>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="method" id="mobile_money" value="mobile_money" checked>
                            <label class="form-check-label" for="mobile_money">
                                <i class="fas fa-mobile-alt me-2"></i>Mobile Money
                            </label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="method" id="credit_card" value="credit_card">
                            <label class="form-check-label" for="credit_card">
                                <i class="fas fa-credit-card me-2"></i>Carte bancaire
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="method" id="bank_transfer" value="bank_transfer">
                            <label class="form-check-label" for="bank_transfer">
                                <i class="fas fa-university me-2"></i>Virement bancaire
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Mobile Money Fields -->
                <div id="mobile_money_fields" class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Informations Mobile Money</h6>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Numéro de téléphone</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                   placeholder="+241 XX XX XX XX" required>
                        </div>
                        <div class="mb-3">
                            <label for="operator" class="form-label">Opérateur</label>
                            <select class="form-select" id="operator" name="operator">
                                <option value="airtel">Airtel Money</option>
                                <option value="moov">Moov Money</option>
                                <option value="orange">Orange Money</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Credit Card Fields -->
                <div id="credit_card_fields" class="card border-0 shadow-sm mb-4" style="display: none;">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Informations de carte bancaire</h6>
                        <div class="mb-3">
                            <label for="card_number" class="form-label">Numéro de carte</label>
                            <input type="text" class="form-control" id="card_number" name="card_number" 
                                   placeholder="1234 5678 9012 3456" maxlength="19">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="expiry_date" class="form-label">Date d'expiration</label>
                                <input type="text" class="form-control" id="expiry_date" name="expiry_date" 
                                       placeholder="MM/AA" maxlength="5">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control" id="cvv" name="cvv" 
                                       placeholder="123" maxlength="3">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bank Transfer Fields -->
                <div id="bank_transfer_fields" class="card border-0 shadow-sm mb-4" style="display: none;">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Informations de virement</h6>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Coordonnées bancaires:</strong><br>
                            Banque: BGFIBank<br>
                            Compte: 1234567890<br>
                            Titulaire: ServiceConnect SARL
                        </div>
                        <div class="mb-3">
                            <label for="reference" class="form-label">Référence de virement</label>
                            <input type="text" class="form-control" id="reference" name="reference" 
                                   placeholder="Référence du virement">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-lock me-2"></i>Payer {{ number_format($booking->service->price, 0) }} FCFA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Afficher/cacher les champs selon la méthode de paiement
    const paymentMethods = document.querySelectorAll('input[name="method"]');
    const mobileFields = document.getElementById('mobile_money_fields');
    const cardFields = document.getElementById('credit_card_fields');
    const bankFields = document.getElementById('bank_transfer_fields');

    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            mobileFields.style.display = 'none';
            cardFields.style.display = 'none';
            bankFields.style.display = 'none';

            // Masquer les champs requis
            document.querySelectorAll('#mobile_money_fields input, #credit_card_fields input, #bank_transfer_fields input').forEach(input => {
                input.removeAttribute('required');
            });

            switch(this.value) {
                case 'mobile_money':
                    mobileFields.style.display = 'block';
                    document.querySelector('#phone_number').setAttribute('required', 'required');
                    break;
                case 'credit_card':
                    cardFields.style.display = 'block';
                    document.querySelector('#card_number').setAttribute('required', 'required');
                    document.querySelector('#expiry_date').setAttribute('required', 'required');
                    document.querySelector('#cvv').setAttribute('required', 'required');
                    break;
                case 'bank_transfer':
                    bankFields.style.display = 'block';
                    break;
            }
        });
    });

    // Formatage du numéro de carte
    const cardNumber = document.getElementById('card_number');
    if (cardNumber) {
        cardNumber.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }

    // Formatage de la date d'expiration
    const expiryDate = document.getElementById('expiry_date');
    if (expiryDate) {
        expiryDate.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });
    }

    // Uniquement des chiffres pour CVV
    const cvv = document.getElementById('cvv');
    if (cvv) {
        cvv.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }
});
</script>
@endsection
