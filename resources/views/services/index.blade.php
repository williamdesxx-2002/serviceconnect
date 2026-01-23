@extends('layouts.app')

@section('title', 'Services')

@section('content')
<!-- Messages Flash -->
@if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Trouvez les meilleurs services locaux</h1>
                <p class="lead mb-4">Connectez-vous avec des prestataires qualifiés à Libreville et ses environs</p>
                
                <!-- Search Box -->
                <div class="search-box mb-4">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control form-control-lg" 
                           placeholder="Rechercher un service..." 
                           value="{{ request('search') }}">
                </div>
                
                <!-- Auth Buttons -->
                @if(!auth()->check())
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>S'inscrire
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </a>
                    </div>
                @endif
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-handshake" style="font-size: 12rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-white">
    <div class="container">
        <h3 class="text-center mb-4">Catégories populaires</h3>
        <div class="row">
            @foreach($categories->take(8) as $category)
                <div class="col-md-3 col-sm-6 mb-3">
                    <a href="{{ route('services.index', ['category_id' => $category->id]) }}" class="text-decoration-none">
                        <div class="text-center">
                            <div class="service-icon mx-auto">
                                {{ $category->icon }}
                            </div>
                            <h6 class="mb-0">{{ $category->name }}</h6>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- How it Works Section -->
@if(!auth()->check())
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="text-center mb-5">Comment ça marche ?</h3>
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-user-plus fa-2x text-white"></i>
                </div>
                <h5>1. Inscrivez-vous</h5>
                <p class="text-muted">Créez votre compte gratuitement en quelques minutes. Choisissez d'être client ou prestataire.</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-search fa-2x text-white"></i>
                </div>
                <h5>2. Trouvez des services</h5>
                <p class="text-muted">Parcourez notre catalogue de services locaux qualifiés à Libreville et ses environs.</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-handshake fa-2x text-white"></i>
                </div>
                <h5>3. Connectez-vous</h5>
                <p class="text-muted">Réservez en ligne, suivez vos réservations et bénéficiez de paiements sécurisés.</p>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-rocket me-2"></i>Commencer maintenant
            </a>
        </div>
    </div>
</section>
@endif

<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3">
                <div class="filter-card sticky-top" style="top: 20px;">
                    <h5 class="mb-4">
                        <i class="fas fa-filter me-2"></i>Filtres
                    </h5>
                    
                    <form method="GET" action="{{ route('services.index') }}">
                        <!-- Search -->
                        <div class="mb-3">
                            <label for="search" class="form-label">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Rechercher...">
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Catégorie</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->icon }} {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label">Prix</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="min_price" 
                                           value="{{ request('min_price') }}" placeholder="Min">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="max_price" 
                                           value="{{ request('max_price') }}" placeholder="Max">
                                </div>
                            </div>
                        </div>

                        <!-- Sort -->
                        <div class="mb-4">
                            <label for="sort" class="form-label">Trier par</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="">Plus récents</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                    Prix croissant
                                </option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                    Prix décroissant
                                </option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>
                                    Meilleure note
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Appliquer les filtres
                        </button>
                    </form>
                </div>
            </div>

            <!-- Services Grid -->
            <div class="col-lg-9">
                @if($services->count() > 0)
                    <div class="row">
                        @foreach($services as $service)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="service-card card h-100">
                                    @if($service->first_image)
                                        <img src="{{ $service->first_image }}" class="card-img-top" 
                                             alt="{{ $service->title }}">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center">
                                            <span class="display-4">{{ $service->category->icon ?? '📋' }}</span>
                                        </div>
                                    @endif

                                    <div class="card-body d-flex flex-column">
                                        <div class="mb-2">
                                            <span class="badge-category me-2">
                                                {{ $service->category->name ?? '' }}
                                            </span>
                                            
                                            <!-- Badge de statut -->
                                            @if($service->status === 'pending')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock me-1"></i>En attente
                                                </span>
                                            @elseif($service->status === 'approved')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Approuvé
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <h5 class="card-title">{{ Str::limit($service->title, 50) }}</h5>
                                        <p class="card-text text-muted">{{ Str::limit($service->description, 80) }}</p>
                                        
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="fw-bold text-primary">
                                                    {{ number_format($service->price, 0) }} FCFA
                                                    @if($service->price_type === 'hourly')
                                                        <small>/h</small>
                                                    @elseif($service->price_type === 'daily')
                                                        <small>/jour</small>
                                                    @endif
                                                </span>
                                                <div class="rating">
                                                    @if($service->rating > 0)
                                                        <i class="fas fa-star"></i>
                                                        <span class="ms-1">{{ number_format($service->rating, 1) }}</span>
                                                        <small class="text-muted ms-1">({{ $service->reviews_count }})</small>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>{{ $service->user->name }}
                                                </small>
                                                @if(isset($service->distance))
                                                    <small class="text-muted">
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ number_format($service->distance, 1) }} km
                                                    </small>
                                                @endif
                                            </div>

                                            <div class="d-grid gap-2">
                                                <a href="{{ route('services.show', $service) }}" 
                                                   class="btn btn-outline-primary">
                                                    <i class="fas fa-eye me-2"></i>Voir détails
                                                </a>
                                                
                                                @if($service->user->hasWhatsapp())
                                                    <a href="{{ $service->user->whatsapp_link }}" 
                                                       target="_blank" 
                                                       class="btn btn-success">
                                                        <i class="fab fa-whatsapp me-2"></i>Contacter via WhatsApp
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $services->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4>Aucun service trouvé</h4>
                        <p class="text-muted">Essayez de modifier vos filtres de recherche.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
