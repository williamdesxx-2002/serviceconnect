@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="auth-card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-handshake fa-4x"></i>
                        </div>
                        <h3 class="fw-bold">Bon retour !</h3>
                        <p class="text-muted">Connectez-vous à votre compte ServiceConnect</p>
                    </div>

                    <div class="divider">
                        <span>ou</span>
                    </div>

                    <form method="POST" action="{{ route('login') }}" id="loginForm">
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
                                       autofocus
                                       placeholder="votre@email.com">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
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
                                       autocomplete="current-password"
                                       placeholder="••••••••">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Se souvenir de moi
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none" href="{{ route('password.request') }}">
                                    <i class="fas fa-question-circle me-1"></i>Mot de passe oublié ?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="loginBtn">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                <span id="loginBtnText">Se connecter</span>
                            </button>
                        </div>
                    </form>

                    <!-- Register Link -->
                    <div class="text-center mt-4 pt-4 border-top">
                        <p class="mb-2 text-muted">Pas encore de compte ?</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus me-2"></i>Créer un compte
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        eyeIcon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    });
    
    // Form submission avec gestion CSRF
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    const loginBtnText = document.getElementById('loginBtnText');
    
    loginForm.addEventListener('submit', function(e) {
        // Vérifier si le token CSRF est valide
        const token = document.querySelector('meta[name="csrf-token"]');
        const formToken = document.querySelector('input[name="_token"]');
        
        if (!token || !formToken || formToken.value === '') {
            e.preventDefault();
            alert('Erreur de sécurité. Veuillez rafraîchir la page.');
            location.reload();
            return;
        }
        
        loginBtn.classList.add('loading');
        loginBtnText.textContent = 'Connexion en cours...';
        loginBtn.disabled = true;
    });
    
    // Auto-focus on email field
    document.getElementById('email').focus();
    
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
</script>
@endsection
