@extends('layouts.app')

@section('title', 'Gestion des Catégories')

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
                        <i class="fas fa-tags me-2"></i>Gestion des Catégories
                        <span class="badge bg-primary ms-2">{{ $categories->total() }}</span>
                    </h5>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nouvelle catégorie
                    </a>
                </div>
                <div class="card-body">
                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Icône</th>
                                        <th>Nom</th>
                                        <th>Description</th>
                                        <th>Services</th>
                                        <th>Statut</th>
                                        <th>Ordre</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>
                                                @if($category->icon)
                                                    <span style="font-size: 1.5rem;">{{ $category->icon }}</span>
                                                @else
                                                    <i class="fas fa-tag text-muted"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $category->name }}</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $category->services_count ?? 0 }}</span>
                                            </td>
                                            <td>
                                                @if($category->is_active)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-pause me-1"></i>Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $category->order ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('admin.categories.toggle', $category) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" 
                                                                class="btn btn-sm {{ $category->is_active ? 'btn-warning' : 'btn-success' }}" 
                                                                title="{{ $category->is_active ? 'Désactiver' : 'Activer' }}">
                                                            <i class="fas fa-power-off"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    @if($category->services_count == 0)
                                                        <form action="{{ route('admin.categories.destroy', $category) }}" 
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-sm btn-outline-secondary" 
                                                                title="Non supprimable (services associés)" disabled>
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
                            {{ $categories->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune catégorie trouvée</h5>
                            <p class="text-muted">Aucune catégorie n'a été créée pour le moment.</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Créer la première catégorie
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
