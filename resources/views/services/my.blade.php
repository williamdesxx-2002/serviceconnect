@extends('layouts.app')

@section('title', 'Mes Services')

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

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-briefcase me-2"></i>
                        Mes Services
                    </h5>
                </div>
                <div class="card-body">
                    @if($services->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Catégorie</th>
                                        <th>Prix</th>
                                        <th>Statut</th>
                                        <th>Réservations</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($service->first_image)
                                                        <img src="{{ $service->first_image }}" alt="{{ $service->title }}" 
                                                             class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                                    @else
                                                        <div class="bg-primary d-flex align-items-center justify-content-center me-3" 
                                                             style="width: 50px; height: 50px; border-radius: 8px;">
                                                            <span class="text-white">{{ strtoupper(substr($service->title, 0, 1)) }}</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ Str::limit($service->title, 30) }}</h6>
                                                        <small class="text-muted">{{ $service->category->name ?? 'Non catégorisé' }}</small>
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
                                                    @case('pending')
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>En attente
                                                        </span>
                                                        @break
                                                    @case('approved')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check me-1"></i>Approuvé
                                                        </span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times me-1"></i>Rejeté
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $service->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $service->bookings->count() }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
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
                            <h5 class="text-muted">Aucun service</h5>
                            <p class="text-muted">
                                Vous n'avez pas encore créé de service. 
                                Commencez dès maintenant à offrir vos prestations !
                            </p>
                            <a href="{{ route('services.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Créer un service
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
