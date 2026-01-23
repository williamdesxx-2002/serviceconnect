@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="auth-card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-user-plus fa-4x"></i>
                        </div>
                        <h3 class="fw-bold">Rejoignez ServiceConnect</h3>
                        <p class="text-muted">Créez votre compte et accédez aux meilleurs services locaux</p>
                    </div>

                    <!-- Social Registration Options -->
                    <div class="mb-4">
                        <a href="{{ route('social.redirect', 'google') }}" class="btn btn-social btn-google w-100">
                            <i class="fab fa-google me-2"></i>
                            S'inscrire avec Google
                        </a>
                        <a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-social btn-facebook w-100">
                            <i class="fab fa-facebook-f me-2"></i>
                            S'inscrire avec Facebook
                        </a>
                    </div>

                    <div class="divider">
                        <span>ou</span>
                    </div>

                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        @if ($errors->has('csrf'))
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Une erreur de sécurité est survenue. Veuillez rafraîchir la page et réessayer.
                                <button type="button" class="btn btn-sm btn-outline-warning ms-2" onclick="location.reload()">
                                    <i class="fas fa-sync me-1"></i>Rafraîchir
                                </button>
                            </div>
                        @endif

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label">
                                <i class="fas fa-user me-2"></i>Nom complet
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
                                       required 
                                       autocomplete="name" 
                                       autofocus
                                       placeholder="Jean Dupont">
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Adresse email
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
                                       required 
                                       autocomplete="email"
                                       placeholder="votre@email.com">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label for="role" class="form-label">
                                <i class="fas fa-user-tag me-2"></i>Je suis un
                            </label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" 
                                    name="role" 
                                    required onchange="updateRoleInfo()">
                                <option value="">Choisissez votre rôle</option>
                                <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>
                                    <i class="fas fa-user me-2"></i>Client (je cherche des services)
                                </option>
                                <option value="provider" {{ old('role') == 'provider' ? 'selected' : '' }}>
                                    <i class="fas fa-briefcase me-2"></i>Prestataire (j'offre des services)
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            
                            <!-- Role Information -->
                            <div id="roleInfo" class="role-info" style="display: none;">
                                <div id="clientInfo" class="d-none">
                                    <h6>
                                        <i class="fas fa-user me-2"></i>En tant que Client
                                    </h6>
                                    <ul>
                                        <li>Recherchez des services locaux qualifiés</li>
                                        <li>Réservez en ligne avec paiement sécurisé</li>
                                        <li>Suivez vos réservations en temps réel</li>
                                        <li>Laissez des avis pour aider la communauté</li>
                                    </ul>
                                </div>
                                <div id="providerInfo" class="d-none">
                                    <h6>
                                        <i class="fas fa-briefcase me-2"></i>En tant que Prestataire
                                    </h6>
                                    <ul>
                                        <li>Créez votre profil professionnel</li>
                                        <li>Ajoutez vos services avec photos</li>
                                        <li>Gérez vos réservations et planning</li>
                                        <li>Recevez des paiements sécurisés</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-2"></i>Téléphone
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
                                       required
                                       placeholder="+241 XX XX XX XX">
                            </div>
                            @error('phone')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- WhatsApp Number -->
                        <div class="mb-4">
                            <label for="whatsapp_number" class="form-label">
                                <i class="fab fa-whatsapp text-success me-2"></i>Numéro WhatsApp
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
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Les clients pourront vous contacter directement via WhatsApp
                            </div>
                            @error('whatsapp_number')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- WhatsApp Notifications -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="whatsapp_notifications" id="whatsapp_notifications" checked>
                                <label class="form-check-label" for="whatsapp_notifications">
                                    <i class="fab fa-whatsapp text-success me-2"></i>
                                    Recevoir les notifications sur WhatsApp
                                </label>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Mot de passe
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="••••••••"
                                       onkeyup="checkPasswordStrength()">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            <div class="mt-2">
                                <div class="progress" style="height: 6px;">
                                    <div id="passwordStrength" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small id="passwordStrengthText" class="form-text text-muted"></small>
                            </div>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    8+ caractères, majuscule, minuscule et chiffre
                                </small>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">
                                <i class="fas fa-lock me-2"></i>Confirmer le mot de passe
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password-confirm" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="••••••••">
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Terms -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    J'accepte les <a href="#" class="text-decoration-none">conditions d'utilisation</a> et la <a href="#" class="text-decoration-none">politique de confidentialité</a>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="registerBtn">
                                <i class="fas fa-user-plus me-2"></i>
                                <span id="registerBtnText">Créer mon compte</span>
                            </button>
                        </div>
                    </form>

                    <!-- Login Link -->
                    <div class="text-center mt-4 pt-4 border-top">
                        <p class="mb-2 text-muted">Déjà un compte ?</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

// Validation du mot de passe et gestion CSRF
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password-confirm');
    
    function validatePassword() {
        if (password.value !== passwordConfirm.value) {
            passwordConfirm.setCustomValidity('Les mots de passe ne correspondent pas');
        } else {
            passwordConfirm.setCustomValidity('');
        }
    }
    
    password.addEventListener('change', validatePassword);
    passwordConfirm.addEventListener('keyup', validatePassword);
    
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        eyeIcon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    });
    
    // Form submission - Simplifié pour éviter les blocages
    const registerForm = document.getElementById('registerForm');
    const registerBtn = document.getElementById('registerBtn');
    const registerBtnText = document.getElementById('registerBtnText');
    
    if (registerForm && registerBtn) {
        registerForm.addEventListener('submit', function(e) {
            // Ajouter seulement l'indicateur de chargement
            if (registerBtnText) {
                registerBtnText.textContent = 'Création du compte...';
            }
            if (registerBtn) {
                registerBtn.disabled = true;
            }
            // NE PAS bloquer la soumission - laisser le formulaire se soumettre normalement
        });
    }
    
    // Auto-focus sur le champ nom
    document.getElementById('name').focus();
    
    // Rafraîchir automatiquement le token CSRF toutes les 30 minutes
    setInterval(function() {
        fetch('/refresh-csrf')
            .then(response => response.json())
            .then(data => {
                const tokenInput = document.querySelector('input[name="_token"]');
                const metaToken = document.querySelector('meta[name="csrf-token"]');
                if (tokenInput && data.token) {
                    tokenInput.value = data.token;
                    metaToken.setAttribute('content', data.token);
                }
            })
            .catch(error => console.log('CSRF refresh failed:', error));
    }, 30 * 60 * 1000); // 30 minutes
});

// Vérification de la force du mot de passe
function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('passwordStrengthText');
    
    let strength = 0;
    let feedback = '';
    
    // Longueur
    if (password.length >= 8) strength++;
    // Contient une minuscule
    if (/[a-z]/.test(password)) strength++;
    // Contient une majuscule
    if (/[A-Z]/.test(password)) strength++;
    // Contient un chiffre
    if (/\d/.test(password)) strength++;
    // Contient un caractère spécial
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;
    
    const strengthPercentage = (strength / 5) * 100;
    
    // Mise à jour de la barre de progression
    strengthBar.style.width = strengthPercentage + '%';
    
    // Mise à jour du texte et de la couleur
    if (strength <= 2) {
        strengthBar.className = 'progress-bar bg-danger';
        feedback = 'Mot de passe faible';
    } else if (strength <= 3) {
        strengthBar.className = 'progress-bar bg-warning';
        feedback = 'Mot de passe moyen';
    } else if (strength <= 4) {
        strengthBar.className = 'progress-bar bg-info';
        feedback = 'Mot de passe fort';
    } else {
        strengthBar.className = 'progress-bar bg-success';
        feedback = 'Mot de passe très fort';
    }
    
    strengthText.textContent = feedback;
}
</script>
@endsection
