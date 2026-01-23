@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 animate-fade-in-left">
                <h1 class="display-4 fw-bold mb-4">ServiceConnect</h1>
                <p class="lead mb-4">La marketplace qui connecte les prestataires de services locaux avec des clients de confiance.</p>
                <div class="d-flex gap-3">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 animate-scale-in">
                            <i class="fas fa-user-plus me-2"></i>S'inscrire
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 animate-scale-in delay-200">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </a>
                    @else
                        <a href="{{ route('home') }}" class="btn btn-light btn-lg px-4 animate-scale-in">
                            <i class="fas fa-home me-2"></i>Mon tableau de bord
                        </a>
                        <a href="{{ route('services.index') }}" class="btn btn-outline-light btn-lg px-4 animate-scale-in delay-200">
                            <i class="fas fa-search me-2"></i>Explorer les services
                        </a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6 text-center animate-fade-in-right">
                <i class="fas fa-handshake animate-bounce" style="font-size: 12rem; opacity: 0.8;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold">Pourquoi choisir ServiceConnect ?</h2>
                <p class="lead text-muted">Découvrez notre plateforme complète de services locaux</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4 animate-fade-in-up">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mx-auto mb-3">
                            <i class="fas fa-search"></i>
                        </div>
                        <h5 class="card-title fw-bold">Trouvez des services</h5>
                        <p class="card-text text-muted">Explorez une large gamme de services proposés par des prestataires qualifiés dans votre région.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 animate-fade-in-up delay-200">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mx-auto mb-3">
                            <i class="fas fa-star"></i>
                        </div>
                        <h5 class="card-title fw-bold">Avis vérifiés</h5>
                        <p class="card-text text-muted">Prenez des décisions éclairées grâce aux avis authentiques des clients précédents.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 animate-fade-in-up delay-400">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mx-auto mb-3">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="card-title fw-bold">Paiements sécurisés</h5>
                        <p class="card-text text-muted">Effectuez vos transactions en toute sécurité avec notre système de paiement protégé.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold">Comment ça marche ?</h2>
                <p class="lead text-muted">Trois étapes simples pour trouver le service parfait</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="service-icon mx-auto mb-3">
                        <span class="badge bg-primary fs-4">1</span>
                    </div>
                    <h5 class="fw-bold">Recherchez</h5>
                    <p class="text-muted">Parcourez les services disponibles ou utilisez notre recherche avancée</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="text-center">
                    <div class="service-icon mx-auto mb-3">
                        <span class="badge bg-primary fs-4">2</span>
                    </div>
                    <h5 class="fw-bold">Réservez</h5>
                    <p class="text-muted">Contactez le prestataire et confirmez votre réservation en ligne</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="text-center">
                    <div class="service-icon mx-auto mb-3">
                        <span class="badge bg-primary fs-4">3</span>
                    </div>
                    <h5 class="fw-bold">Évaluez</h5>
                    <p class="text-muted">Partagez votre expérience pour aider la communauté</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-4">Prêt à commencer ?</h2>
                <p class="lead mb-4">Rejoignez des milliers d'utilisateurs qui font confiance à ServiceConnect</p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-rocket me-2"></i>Commencer maintenant
                    </a>
                @else
                    <a href="{{ route('services.index') }}" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-search me-2"></i>Explorer les services
                    </a>
                @endguest
            </div>
        </div>
    </div>
</section>
@endsection
