@extends('layouts.app')

@section('title', 'Mot de passe oublié')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="auth-card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-key fa-4x"></i>
                        </div>
                        <h3 class="fw-bold">Mot de passe oublié ?</h3>
                        <p class="text-muted">Entrez votre email pour recevoir un lien de réinitialisation</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success mb-4">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" id="resetForm">
                        @csrf

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Adresse email
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input id="email" 
                                       type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
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

                        <!-- Submit Button -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="resetBtn">
                                <i class="fas fa-paper-plane me-2"></i>
                                <span id="resetBtnText">Envoyer le lien de réinitialisation</span>
                            </button>
                        </div>

                        <!-- Back to Login -->
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>Retour à la connexion
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission loading state
    const resetForm = document.getElementById('resetForm');
    const resetBtn = document.getElementById('resetBtn');
    const resetBtnText = document.getElementById('resetBtnText');
    
    resetForm.addEventListener('submit', function() {
        resetBtn.classList.add('loading');
        resetBtnText.textContent = 'Envoi en cours...';
        resetBtn.disabled = true;
    });
    
    // Auto-focus on email field
    document.getElementById('email').focus();
});
</script>
@endsection
