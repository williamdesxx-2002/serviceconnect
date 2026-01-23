@extends('layouts.app')

@section('title', 'Détails du Service - ' . $service->title)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            <div class="sidebar">
                <div class="p-3">
                    <h6 class="text-uppercase fw-bold mb-3">Admin</h6>
                    <nav class="nav flex-column">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="nav-link">
                            <i class="fas fa-users me-2"></i>Utilisateurs
                        </a>
                        <a href="{{ route('admin.services.index') }}" class="nav-link">
                            <i class="fas fa-briefcase me-2"></i>Services
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" class="nav-link">
                            <i class="fas fa-calendar me-2"></i>Réservations
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="nav-link">
                            <i class="fas fa-tags me-2"></i>Catégories
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="nav-link">
                            <i class="fas fa-chart-bar me-2"></i>Rapports
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <!-- Informations du service -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-briefcase me-2"></i>{{ $service->title }}
                        @if($service->is_reported)
                            <span class="badge bg-danger ms-2">Signalé</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Prestataire :</strong>
                                </div>
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info d-flex align-items-center justify-content-center me-2" 
                                             style="width: 30px; height: 30px; border-radius: 50%;">
                                            <span class="text-white small">{{ strtoupper(substr($service->user->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <div>{{ $service->user->name }}</div>
                                            <small class="text-muted">{{ $service->user->email }}</small>
                                            @if($service->user->phone)
                                                <br><small class="text-muted">{{ $service->user->phone }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Catégorie :</strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="badge bg-info">{{ $service->category->name ?? 'Non catégorisé' }}</span>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Prix :</strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="h4 text-primary">{{ number_format($service->price, 0) }} FCFA</span>
                                    @if($service->duration)
                                        <span class="text-muted">/ {{ $service->duration }}h</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Statut :</strong>
                                </div>
                                <div class="col-md-8">
                                    @switch($service->status)
                                        @case('active')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Actif
                                            </span>
                                            @break
                                        @case('inactive')
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-pause me-1"></i>Inactif
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>En attente
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $service->status }}</span>
                                    @endswitch
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Description :</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $service->description }}
                                </div>
                            </div>
                            
                            @if($service->tags && is_array($service->tags) && count($service->tags) > 0)
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Tags :</strong>
                                    </div>
                                    <div class="col-md-8">
                                        @foreach($service->tags as $tag)
                                            <span class="badge bg-light text-dark me-1">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-4">
                            @if($service->images && count($service->images) > 0)
                                <div id="serviceCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach($service->images as $index => $image)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <img src="{{ $image }}" class="d-block w-100" alt="Image {{ $index + 1 }}" 
                                                     style="height: 200px; object-fit: cover;">
                                            </div>
                                        @endforeach
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#serviceCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#serviceCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                </div>
                            @else
                                <div class="bg-primary d-flex align-items-center justify-content-center" 
                                     style="height: 200px; border-radius: 8px;">
                                    <span class="text-white h1">{{ strtoupper(substr($service->title, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Signalement -->
            @if($service->is_reported)
                <div class="card mb-4 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>Signalement en cours
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Raison :</strong>
                                <p class="text-danger">{{ $service->report_reason }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Date du signalement :</strong>
                                <p>{{ $service->reported_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @if($service->report_description)
                            <div class="mt-3">
                                <strong>Description :</strong>
                                <p>{{ $service->report_description }}</p>
                            </div>
                        @endif
                        
                        <div class="mt-3">
                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce service pour récidive ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-2"></i>Supprimer pour récidive
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <form action="{{ route('admin.services.toggle', $service) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-power-off me-2"></i>
                                    {{ $service->status === 'active' ? 'Désactiver' : 'Activer' }} le service
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6 mb-2">
                            <a href="{{ route('admin.bookings.provider', $service->user) }}" class="btn btn-info w-100">
                                <i class="fas fa-calendar me-2"></i>Voir les réservations du prestataire
                            </a>
                        </div>
                        @if(!$service->is_reported)
                            <div class="col-md-12 mb-2">
                                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#reportModal">
                                    <i class="fas fa-flag me-2"></i>Signaler ce service
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques du service
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 class="text-primary">{{ $service->bookings->count() }}</h4>
                            <small class="text-muted">Réservations totales</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-success">{{ $service->bookings->where('status', 'completed')->count() }}</h4>
                            <small class="text-muted">Réservations terminées</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info">{{ $service->reviews->count() }}</h4>
                            <small class="text-muted">Avis clients</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning">{{ $service->averageRating() > 0 ? number_format($service->averageRating(), 1) : 'N/A' }}</h4>
                            <small class="text-muted">Note moyenne</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de signalement -->
@if(!$service->is_reported)
    <div class="modal fade" id="reportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-flag me-2"></i>Signaler le service
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.services.report', $service) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Raison du signalement</label>
                            <select class="form-select" name="reason" required>
                                <option value="">Choisir une raison...</option>
                                <option value="Contenu inapproprié">Contenu inapproprié</option>
                                <option value="Prix excessif">Prix excessif</option>
                                <option value="Service illégal">Service illégal</option>
                                <option value="Fausses informations">Fausses informations</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description détaillée</label>
                            <textarea class="form-control" name="description" rows="4" 
                                      placeholder="Décrivez en détail le problème..."></textarea>
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Attention :</strong> Ce signalement sera visible par le prestataire et pourra entraîner des sanctions en cas de récidive.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-flag me-2"></i>Signaler le service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
