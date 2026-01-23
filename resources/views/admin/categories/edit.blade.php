@extends('layouts.app')

@section('title', 'Modifier une Catégorie')

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
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Modifier la Catégorie : {{ $category->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom de la catégorie *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="icon" class="form-label">Icône</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon', $category->icon) }}" 
                                           placeholder="Ex: 💡, 📊, 📈, 💻">
                                    <small class="form-text text-muted">Utilisez des emojis ou des icônes Font Awesome</small>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                            <small class="form-text text-muted">Description détaillée de la catégorie</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order" class="form-label">Ordre d'affichage</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                           id="order" name="order" value="{{ old('order', $category->order ?? 0) }}" min="0">
                                    <small class="form-text text-muted">Ordre d'affichage dans la liste</small>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input @error('is_active') is-invalid @enderror" 
                                               type="checkbox" id="is_active" name="is_active" value="1" 
                                               {{ $category->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Catégorie active
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Les catégories inactives ne seront pas affichées</small>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Icônes suggérées -->
                        <div class="mb-3">
                            <label class="form-label">Icônes suggérées</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('💡')">💡 Conseil</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('📊')">📊 Comptabilité</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('📈')">📈 Marketing</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('💻')">💻 Informatique</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('📚')">📚 Formation</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('🔒')">🔒 Sécurité</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('💇')">💇 Coiffure</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('🚗')">🚗 Transport</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('👶')">👶 Garde d'enfants</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('👴')">👴 Personnes âgées</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('🏠')">🏠 Ménage</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('🎓')">🎓 Cours particuliers</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('🎯')">🎯 Coaching</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('🎉')">🎉 Événements</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('✈️')">✈️ Tourisme</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('⚽')">⚽ Sport</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('🎭')">🎭 Arts</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('🏗️')">🏗️ Construction</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('🔨')">🔨 Rénovation</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('🔧')">🔧 Maintenance</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('🛠️')">🛠️ Réparation</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('⚡')">⚡ Énergie</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('🏭')">🏭 Ingénierie</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('🚚')">🚚 Logistique</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setIcon('📝')">📝 Autre</button>
                            </div>
                        </div>
                        
                        <!-- Statistiques de la catégorie -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-info-circle me-2"></i>Statistiques de la catégorie
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Services associés :</strong> {{ $category->services_count ?? 0 }}
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Créée le :</strong> {{ $category->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Dernière modification :</strong> {{ $category->updated_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setIcon(icon) {
    document.getElementById('icon').value = icon;
}
</script>
@endsection
