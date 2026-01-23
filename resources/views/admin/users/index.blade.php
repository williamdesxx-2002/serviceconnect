@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

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
                        <i class="fas fa-users me-2"></i>Gestion des Utilisateurs
                        <span class="badge bg-primary ms-2">{{ $users->total() }}</span>
                    </h5>
                    <div>
                        <form class="d-inline" method="GET" action="{{ route('admin.users.index') }}">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher un utilisateur..." value="{{ request('search') }}">
                                <select name="role" class="form-select">
                                    <option value="">Tous les rôles</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="provider" {{ request('role') == 'provider' ? 'selected' : '' }}>Prestataire</option>
                                    <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Client</option>
                                </select>
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Utilisateur</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Services</th>
                                        <th>Réservations</th>
                                        <th>Statut</th>
                                        <th>Vérifié</th>
                                        <th>Inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 40px; height: 40px; border-radius: 50%;">
                                                        <span class="text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $user->name }}</div>
                                                        <small class="text-muted">{{ $user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @switch($user->role)
                                                    @case('admin')
                                                        <span class="badge bg-danger">Admin</span>
                                                        @break
                                                    @case('provider')
                                                        <span class="badge bg-primary">Prestataire</span>
                                                        @break
                                                    @case('client')
                                                        <span class="badge bg-success">Client</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $user->role }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $user->services_count ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ $user->client_bookings_count ?? 0 }}</span>
                                            </td>
                                            <td>
                                                @if($user->is_active)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Actif
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times me-1"></i>Inactif
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->is_verified)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>Vérifié
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>Non vérifié
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $user->created_at->format('d/m/Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="#" class="btn btn-sm btn-outline-primary" title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }}" 
                                                                title="{{ $user->is_active ? 'Désactiver' : 'Activer' }} l'utilisateur">
                                                            <i class="fas fa-power-off"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    @if(!$user->is_verified && $user->role === 'provider')
                                                        <form action="{{ route('admin.users.verify', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-sm btn-success" title="Vérifier le prestataire">
                                                                <i class="fas fa-check-circle"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if($user->id !== auth()->id())
                                                        <button type="button" 
                                                                class="btn btn-sm btn-danger" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteUserModal{{ $user->id }}"
                                                                title="Supprimer l'utilisateur">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
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
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun utilisateur trouvé</h5>
                            <p class="text-muted">Aucun utilisateur n'a été trouvé pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals de suppression -->
@foreach($users as $user)
    @if($user->id !== auth()->id())
        <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Confirmation de Suppression
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Attention !</strong> Cette action est irréversible.
                        </div>
                        
                        <p>Êtes-vous sûr de vouloir supprimer l'utilisateur suivant ?</p>
                        
                        <div class="bg-light p-3 rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Nom :</strong> {{ $user->name }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Email :</strong> {{ $user->email }}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <strong>Rôle :</strong> 
                                    @switch($user->role)
                                        @case('admin')
                                            <span class="badge bg-danger">Admin</span>
                                            @break
                                        @case('provider')
                                            <span class="badge bg-primary">Prestataire</span>
                                            @break
                                        @case('client')
                                            <span class="badge bg-success">Client</span>
                                            @break
                                    @endswitch
                                </div>
                                <div class="col-md-6">
                                    <strong>Inscription :</strong> {{ $user->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <strong>Services :</strong> {{ $user->services_count ?? 0 }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Réservations client :</strong> {{ $user->client_bookings_count ?? 0 }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Réservations prestataire :</strong> {{ $user->provider_bookings_count ?? 0 }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <h6 class="text-danger">
                                <i class="fas fa-trash me-2"></i>
                                Les données suivantes seront définitivement supprimées :
                            </h6>
                            <ul class="mb-0">
                                <li>Tous les services publiés par l'utilisateur</li>
                                <li>Toutes les réservations (client et prestataire)</li>
                                <li>Tous les messages envoyés/reçus</li>
                                <li>Tous les avis laissés</li>
                                <li>Toutes les transactions de paiement</li>
                                <li>L'avatar et les images de services</li>
                                <li>Le compte utilisateur</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>
                                Supprimer Définitivement
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
