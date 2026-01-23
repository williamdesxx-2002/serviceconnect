@extends('layouts.app')

@section('title', 'Inscription - ServiceConnect')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-6">
            <div class="auth-card shadow-lg">
                <div class="card-body p-4 p-lg-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-user-plus fa-4x text-primary"></i>
                        </div>
                        <h2 class="fw-bold mb-2">Rejoignez ServiceConnect</h2>
                        <p class="text-muted">Créez votre compte et accédez aux meilleurs services locaux</p>
                    </div>

                    <!-- Messages d'erreur et de succès -->
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <div>
                                    <strong>Erreur(s) détectée(s) :</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    <!-- Formulaire d'inscription -->
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        
                        <div class="row">
                            <!-- Nom complet -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="fas fa-user me-2 text-primary"></i>Nom complet
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Votre nom complet"
                                           required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-2 text-primary"></i>Adresse email
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="votre@email.com"
                                           required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Téléphone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-semibold">
                                    <i class="fas fa-phone me-2 text-primary"></i>Téléphone
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           placeholder="+241 XX XX XX XX"
                                           required>
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Rôle -->
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label fw-semibold">
                                    <i class="fas fa-user-tag me-2 text-primary"></i>Je suis un
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" 
                                            name="role" 
                                            required onchange="updateRoleInfo()">
                                        <option value="">Choisissez votre rôle</option>
                                        <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>
                                            <i class="fas fa-shopping-cart me-2"></i>Client (je cherche des services)
                                        </option>
                                        <option value="provider" {{ old('role') == 'provider' ? 'selected' : '' }}>
                                            <i class="fas fa-briefcase me-2"></i>Prestataire (j'offre des services)
                                        </option>
                                    </select>
                                </div>
                                @error('role')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Informations sur le rôle -->
                        <div id="roleInfo" class="mb-3" style="display: none;">
                            <div id="clientInfo" class="alert alert-info d-none">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Client :</strong> Accédez à tous les services disponibles et réservez en ligne.
                            </div>
                            <div id="providerInfo" class="alert alert-success d-none">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Prestataire :</strong> Proposez vos services et gérez vos réservations.
                            </div>
                        </div>

                        <!-- WhatsApp (optionnel) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="whatsapp_number" class="form-label fw-semibold">
                                    <i class="fab fa-whatsapp me-2 text-success"></i>Numéro WhatsApp
                                    <small class="text-muted">(Optionnel)</small>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fab fa-whatsapp text-success"></i>
                                    </span>
                                    <input type="tel" 
                                           class="form-control @error('whatsapp_number') is-invalid @enderror" 
                                           id="whatsapp_number" 
                                           name="whatsapp_number" 
                                           value="{{ old('whatsapp_number') }}" 
                                           placeholder="+241 XX XX XX XX">
                                </div>
                                @error('whatsapp_number')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Les clients pourront vous contacter directement via WhatsApp
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fab fa-whatsapp me-2 text-success"></i>Notifications WhatsApp
                                </label>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="whatsapp_notifications" id="whatsapp_notifications" value="1" checked>
                                    <label class="form-check-label" for="whatsapp_notifications">
                                        Recevoir les notifications sur WhatsApp
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Mot de passe -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2 text-primary"></i>Mot de passe
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="••••••••"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="progress mt-2" style="height: 6px;">
                                    <div id="passwordStrength" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small id="passwordStrengthText" class="form-text text-muted"></small>
                            </div>

                            <!-- Confirmation du mot de passe -->
                            <div class="col-md-6 mb-3">
                                <label for="password-confirm" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2 text-primary"></i>Confirmer le mot de passe
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password-confirm" 
                                           name="password_confirmation" 
                                           placeholder="••••••••"
                                           required>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Conditions d'utilisation -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    J'accepte les <a href="#" class="text-primary">conditions d'utilisation</a> et la <a href="#" class="text-primary">politique de confidentialité</a>
                                </label>
                            </div>
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="registerBtn">
                                <i class="fas fa-user-plus me-2"></i>
                                <span id="registerBtnText">Créer mon compte</span>
                            </button>
                        </div>
                    </form>

                    <!-- Lien de connexion -->
                    <div class="text-center">
                        <p class="mb-0">
                            Vous avez déjà un compte ?
                            <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold">
                                Connectez-vous ici
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'affichage des informations sur le rôle
    function updateRoleInfo() {
        const role = document.getElementById('role').value;
        const roleInfo = document.getElementById('roleInfo');
        const clientInfo = document.getElementById('clientInfo');
        const providerInfo = document.getElementById('providerInfo');
        
        if (role === 'client') {
            roleInfo.style.display = 'block';
            clientInfo.classList.remove('d-none');
            providerInfo.classList.add('d-none');
        } else if (role === 'provider') {
            roleInfo.style.display = 'block';
            clientInfo.classList.add('d-none');
            providerInfo.classList.remove('d-none');
        } else {
            roleInfo.style.display = 'none';
        }
    }

    // Gestion de la visibilité du mot de passe
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        });
    }

    // Vérification de la force du mot de passe
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordStrengthText = document.getElementById('passwordStrengthText');
    
    if (password) {
        password.addEventListener('input', function() {
            const pwd = this.value;
            let strength = 0;
            let text = '';
            let color = '';
            
            if (pwd.length >= 8) strength++;
            if (pwd.match(/[a-z]/)) strength++;
            if (pwd.match(/[A-Z]/)) strength++;
            if (pwd.match(/[0-9]/)) strength++;
            if (pwd.match(/[^a-zA-Z0-9]/)) strength++;
            
            switch(strength) {
                case 0:
                case 1:
                    text = 'Très faible';
                    color = 'bg-danger';
                    break;
                case 2:
                    text = 'Faible';
                    color = 'bg-warning';
                    break;
                case 3:
                    text = 'Moyen';
                    color = 'bg-info';
                    break;
                case 4:
                    text = 'Fort';
                    color = 'bg-success';
                    break;
                case 5:
                    text = 'Très fort';
                    color = 'bg-success';
                    break;
            }
            
            passwordStrength.style.width = (strength * 20) + '%';
            passwordStrength.className = 'progress-bar ' + color;
            passwordStrengthText.textContent = text;
        });
    }

    // Gestion de la soumission du formulaire
    const registerForm = document.getElementById('registerForm');
    const registerBtn = document.getElementById('registerBtn');
    const registerBtnText = document.getElementById('registerBtnText');
    
    if (registerForm && registerBtn) {
        registerForm.addEventListener('submit', function(e) {
            // Ajouter l'indicateur de chargement
            if (registerBtnText) {
                registerBtnText.textContent = 'Création du compte...';
            }
            if (registerBtn) {
                registerBtn.disabled = true;
                registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i><span id="registerBtnText">Création du compte...</span>';
            }
            // NE PAS bloquer la soumission - laisser le formulaire se soumettre normalement
        });
    }

    // Auto-focus sur le champ nom
    document.getElementById('name').focus();
});
</script>
@endsection
