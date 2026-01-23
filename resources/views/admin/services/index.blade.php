@extends('layouts.app')

@section('title', 'Gestion des Services')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            @include('admin.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-briefcase me-2"></i>Gestion des Services
                        <span class="badge bg-primary ms-2">{{ $services->total() }}</span>
                    </h5>
                    <div>
                        <form class="d-inline" method="GET" action="{{ route('admin.services.index') }}">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher un service..." value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @if($services->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Prestataire</th>
                                        <th>Catégorie</th>
                                        <th>Prix</th>
                                        <th>Statut</th>
                                        <th>Signalement</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($service->images && count($service->images) > 0)
                                                        <img src="{{ $service->images[0] }}" alt="{{ $service->title }}" 
                                                             class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;">
                                                    @else
                                                        <div class="bg-primary d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 40px; height: 40px; border-radius: 5px;">
                                                            <span class="text-white small">{{ strtoupper(substr($service->title, 0, 1)) }}</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ Str::limit($service->title, 30) }}</h6>
                                                        <small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 30px; height: 30px; border-radius: 50%;">
                                                        <span class="text-white small">{{ strtoupper(substr($service->user->name, 0, 1)) }}</span>
                                                    </div>
                                                    <div>
                                                        <div>{{ $service->user->name }}</div>
                                                        <small class="text-muted">{{ $service->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $service->category->name ?? 'Non catégorisé' }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($service->price, 0) }} FCFA</strong>
                                            </td>
                                            <td>
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
                                            </td>
                                            <td>
                                                @if($service->is_reported)
                                                    <span class="badge bg-danger" title="{{ $service->report_reason }}">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>Signalé
                                                    </span>
                                                    @if($service->reported_at)
                                                        <small class="text-muted d-block">{{ $service->reported_at->format('d/m/Y') }}</small>
                                                    @endif
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-shield-alt me-1"></i>Conforme
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.services.show', $service) }}" class="btn btn-sm btn-outline-primary" title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('admin.services.toggle', $service) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Activer/Désactiver">
                                                            <i class="fas fa-power-off"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    @if(!$service->is_reported)
                                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                data-bs-toggle="modal" data-bs-target="#reportModal{{ $service->id }}" title="Signaler">
                                                            <i class="fas fa-flag"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($service->is_reported)
                                                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline" 
                                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce service pour récidive ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer pour récidive">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $services->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun service trouvé</h5>
                            <p class="text-muted">Aucun service n'a été proposé pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de signalement -->
@foreach($services as $service)
    @if(!$service->is_reported)
        <div class="modal fade" id="reportModal{{ $service->id }}" tabindex="-1">
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
                        @method('PUT')
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
@endforeach
@endsection
