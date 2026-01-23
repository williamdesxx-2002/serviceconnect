@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Services</a></li>
            <li class="breadcrumb-item active">{{ $service->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                @if($service->image_urls && count($service->image_urls) > 0)
                    <div id="serviceCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($service->image_urls as $index => $image_url)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $image_url }}" class="d-block w-100" alt="{{ $service->title }}" style="height: 400px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                        @if(count($service->image_urls) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#serviceCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#serviceCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                        <span class="display-1">{{ $service->category->icon ?? '📋' }}</span>
                    </div>
                @endif

                <div class="card-body">
                    <h1 class="card-title">{{ $service->title }}</h1>
                    
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-primary me-2">{{ $service->category->name }}</span>
                        @if($service->rating > 0)
                            <span class="text-warning me-2">
                                ⭐ {{ number_format($service->rating, 1) }}
                            </span>
                            <small class="text-muted">({{ $service->reviews_count }} avis)</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h3 class="text-primary">
                            {{ number_format($service->price, 0) }} FCFA
                            @if($service->price_type === 'hourly')
                                /heure
                            @elseif($service->price_type === 'daily')
                                /jour
                            @endif
                        </h3>
                        @if($service->duration)
                            <small class="text-muted">Durée estimée: {{ $service->duration }} minutes</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h5>Description</h5>
                        <p>{{ nl2br($service->description) }}</p>
                    </div>

                    @if($service->tags && !empty($service->tags))
                        <div class="mb-3">
                            <h5>Tags</h5>
                            @if(is_array($service->tags))
                                @foreach($service->tags as $tag)
                                    <span class="badge bg-secondary me-1">{{ $tag }}</span>
                                @endforeach
                            @else
                                @foreach(explode(',', str_replace('"', '', $service->tags)) as $tag)
                                    <span class="badge bg-secondary me-1">{{ trim($tag) }}</span>
                                @endforeach
                            @endif
                        </div>
                    @endif

                    <div class="mb-3">
                        <h5>Localisation</h5>
                        <p><i class="fas fa-map-marker-alt"></i> {{ $service->address }}</p>
                    </div>

                    @if(auth()->check() && auth()->user()->isClient() && $service->user_id !== auth()->id())
                        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                            <a href="{{ route('bookings.create', $service) }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-calendar-plus"></i> Réserver ce service
                            </a>
                            
                            @if($service->user->hasWhatsapp())
                                <a href="{{ $service->user->whatsapp_link }}" 
                                   target="_blank" 
                                   class="btn btn-success btn-lg">
                                    <i class="fab fa-whatsapp"></i> Contacter via WhatsApp
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Prestataire -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5>Prestataire</h5>
                </div>
                <div class="card-body text-center">
                    @if($service->user->avatar)
                        <img src="{{ $service->user->avatar }}" alt="{{ $service->user->name }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 100px; height: 100px;">
                            <span class="text-white display-4">{{ strtoupper(substr($service->user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    
                    <h5>{{ $service->user->name }}</h5>
                    @if($service->user->is_verified)
                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Vérifié</span>
                    @endif
                    
                    @if($service->user->bio)
                        <p class="text-muted mt-2">{{ $service->user->bio }}</p>
                    @endif

                    <div class="mt-3">
                        @if($service->user->hasWhatsapp())
                            <a href="{{ $service->user->whatsapp_link }}" 
                               target="_blank" 
                               class="btn btn-success btn-sm w-100 mb-2">
                                <i class="fab fa-whatsapp"></i> Contacter via WhatsApp
                            </a>
                        @endif
                        
                        <small class="text-muted">
                            <i class="fas fa-briefcase"></i> {{ $service->user->services->count() }} services
                        </small><br>
                        @if($service->user->rating > 0)
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <div>{!! $service->user->rating_stars !!}</div>
                                <span class="ms-2 text-muted">
                                    {{ number_format($service->user->rating, 1) }} ({{ $service->user->reviews_count }} avis)
                                </span>
                            </div>
                        @else
                            <small class="text-muted">
                                <i class="fas fa-star"></i> Pas encore noté
                            </small>
                        @endif
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('messages.show', $service->user) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope"></i> Envoyer un message
                        </a>
                        
                        @if($service->user->phone)
                            <a href="tel:{{ $service->user->phone }}" class="btn btn-info btn-sm">
                                <i class="fas fa-phone"></i> Appeler
                            </a>
                        @endif
                        
                        @if($service->user->hasWhatsapp())
                            <a href="{{ $service->user->whatsapp_link }}" 
                               target="_blank" 
                               class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Services similaires -->
            @if($relatedServices->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5>Services similaires</h5>
                    </div>
                    <div class="card-body">
                        @foreach($relatedServices as $relatedService)
                            <div class="d-flex mb-3">
                                @if($relatedService->images && count($relatedService->images) > 0)
                                    <img src="{{ $relatedService->images[0] }}" alt="{{ $relatedService->title }}" class="me-3" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                @else
                                    <div class="bg-light me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 8px;">
                                        <span>{{ $relatedService->category->icon ?? '📋' }}</span>
                                    </div>
                                @endif
                                
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($relatedService->title, 30) }}</h6>
                                    <small class="text-primary fw-bold">
                                        {{ number_format($relatedService->price, 0) }} FCFA
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
