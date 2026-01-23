@extends('layouts.app')

@section('title', 'Créer un service')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Créer un nouveau service
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data" onsubmit="console.log('Form submitted'); return true;">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Informations principales -->
                                <div class="mb-4">
                                    <label for="title" class="form-label fw-bold">
                                        <i class="fas fa-heading me-1"></i>Titre du service *
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" 
                                           placeholder="Ex: Nettoyage de maison, Cours de maths, Développement web..." required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="form-label fw-bold">
                                        <i class="fas fa-align-left me-1"></i>Description *
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="6" 
                                              placeholder="Décrivez votre service en détail..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="category_id" class="form-label fw-bold">
                                        <i class="fas fa-folder me-1"></i>Catégorie *
                                    </label>
                                    <select class="form-select form-select-lg @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}
                                                    data-name="{{ $category->name }}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                        <option value="other" 
                                                {{ old('category_id') == 'other' ? 'selected' : '' }}
                                                data-name="autre">
                                            Autre (préciser)
                                        </option>
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="price" class="form-label fw-bold">
                                                <i class="fas fa-money-bill-wave me-1"></i>Prix (FCFA) *
                                            </label>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price') }}" 
                                                   min="0" step="100" placeholder="5000" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="price_type" class="form-label fw-bold">
                                                <i class="fas fa-tag me-1"></i>Type de prix *
                                            </label>
                                            <select class="form-select @error('price_type') is-invalid @enderror" 
                                                    id="price_type" name="price_type" required>
                                                <option value="">Sélectionner...</option>
                                                <option value="fixed" {{ old('price_type', 'fixed') == 'fixed' ? 'selected' : '' }}>
                                                    Prix fixe
                                                </option>
                                                <option value="hourly" {{ old('price_type') == 'hourly' ? 'selected' : '' }}>
                                                    Prix par heure
                                                </option>
                                                <option value="daily" {{ old('price_type') == 'daily' ? 'selected' : '' }}>
                                                    Prix par jour
                                                </option>
                                            </select>
                                            @error('price_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="duration" class="form-label fw-bold">
                                                <i class="fas fa-clock me-1"></i>Durée (minutes) *
                                            </label>
                                            <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                                   id="duration" name="duration" value="{{ old('duration') }}" 
                                                   min="15" step="15" placeholder="60" required>
                                            @error('duration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="neighborhood" class="form-label fw-bold">
                                                <i class="fas fa-map-marker-alt me-1"></i>Quartier à Libreville *
                                            </label>
                                            <select class="form-select @error('neighborhood') is-invalid @enderror" 
                                                    id="neighborhood" name="neighborhood" required>
                                                <option value="">Sélectionner un quartier</option>
                                                <option value="centre-ville" {{ old('neighborhood') == 'centre-ville' ? 'selected' : '' }}>Centre-ville</option>
                                                <option value="nkembo" {{ old('neighborhood') == 'nkembo' ? 'selected' : '' }}>Nkembo</option>
                                                <option value="owendo" {{ old('neighborhood') == 'owendo' ? 'selected' : '' }}>Owendo</option>
                                                <option value="akanda" {{ old('neighborhood') == 'akanda' ? 'selected' : '' }}>Akanda</option>
                                                <option value="angondjé" {{ old('neighborhood') == 'angondjé' ? 'selected' : '' }}>Angondjé</option>
                                                <option value="batterie-iv" {{ old('neighborhood') == 'batterie-iv' ? 'selected' : '' }}>Batterie IV</option>
                                                <option value="batterie-viii" {{ old('neighborhood') == 'batterie-viii' ? 'selected' : '' }}>Batterie VIII</option>
                                                <option value="glass" {{ old('neighborhood') == 'glass' ? 'selected' : '' }}>Glass</option>
                                                <option value="mont-bouet" {{ old('neighborhood') == 'mont-bouet' ? 'selected' : '' }}>Mont-Bouët</option>
                                                <option value="nzeng-ayong" {{ old('neighborhood') == 'nzeng-ayong' ? 'selected' : '' }}>Nzeng-Ayong</option>
                                                <option value="sablière" {{ old('neighborhood') == 'sablière' ? 'selected' : '' }}>Sablière</option>
                                                <option value="sogara" {{ old('neighborhood') == 'sogara' ? 'selected' : '' }}>Sogara</option>
                                                <option value="tollé" {{ old('neighborhood') == 'tollé' ? 'selected' : '' }}>Tollé</option>
                                                <option value="autre" {{ old('neighborhood') == 'autre' ? 'selected' : '' }}>Autre</option>
                                            </select>
                                            @error('neighborhood')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Champ pour préciser le quartier si "Autre" est sélectionné -->
                                <div class="mb-4" id="otherNeighborhoodField" style="display: none;">
                                    <label for="other_neighborhood" class="form-label fw-bold">
                                        <i class="fas fa-edit me-1"></i>Préciser ce quartier *
                                    </label>
                                    <input type="text" class="form-control @error('other_neighborhood') is-invalid @enderror" 
                                           id="other_neighborhood" name="other_neighborhood" 
                                           value="{{ old('other_neighborhood') }}" 
                                           placeholder="Entrez le nom du quartier...">
                                    @error('other_neighborhood')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Veuillez préciser le quartier qui n'est pas dans la liste</small>
                                </div>

                                <!-- Champ pour préciser la catégorie si "Autre" est sélectionné -->
                                <div class="mb-4" id="otherCategoryField" style="display: none;">
                                    <label for="other_category" class="form-label fw-bold">
                                        <i class="fas fa-edit me-1"></i>Préciser la catégorie *
                                    </label>
                                    <input type="text" class="form-control @error('other_category') is-invalid @enderror" 
                                           id="other_category" name="other_category" 
                                           value="{{ old('other_category') }}" 
                                           placeholder="Entrez le nom de la catégorie...">
                                    @error('other_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Veuillez préciser la catégorie qui n'est pas dans la liste</small>
                                </div>

                                <div class="mb-4">
                                    <label for="tags" class="form-label fw-bold">
                                        <i class="fas fa-tags me-1"></i>Tags (séparés par des virgules)
                                    </label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                           id="tags" name="tags" value="{{ old('tags') }}" 
                                           placeholder="Ex: professionnel, rapide, qualité">
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Ajoutez des mots-clés pour aider les clients à trouver votre service</small>
                                </div>

                                <div class="mb-4">
                                    <label for="location" class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt me-1"></i>Lieu du service
                                    </label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           id="location" name="location" value="{{ old('location') }}" 
                                           placeholder="Ex: Libreville, Centre-ville, À domicile...">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="images" class="form-label fw-bold">
                                        <i class="fas fa-images me-1"></i>Images du service
                                    </label>
                                    <input type="file" class="form-control @error('images') is-invalid @enderror" 
                                           id="images" name="images[]" multiple accept="image/*">
                                    @error('images')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Vous pouvez télécharger plusieurs images (JPG, PNG, GIF)</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Informations supplémentaires -->
                                <div class="card border-secondary">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Informations supplémentaires
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_active" 
                                                       name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    Service actif
                                                </label>
                                            </div>
                                            <small class="text-muted">Cochez pour rendre le service visible immédiatement</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="availability" class="form-label">Disponibilité</label>
                                            <textarea class="form-control" id="availability" name="availability" rows="3" 
                                                      placeholder="Ex: Lundi-Vendredi 9h-18h">{{ old('availability') }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="requirements" class="form-label">Prérequis</label>
                                            <textarea class="form-control" id="requirements" name="requirements" rows="3" 
                                                      placeholder="Ex: Permis de conduire, Expérience minimale...">{{ old('requirements') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tips -->
                                <div class="card border-info mt-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-lightbulb me-1"></i>
                                            Conseils
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="mb-0 small">
                                            <li>Soyez précis dans votre description</li>
                                            <li>Fixez un prix compétitif</li>
                                            <li>Ajoutez des photos de qualité</li>
                                            <li>Utilisez des tags pertinents</li>
                                            <li>Indiquez votre disponibilité</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('services.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Retour aux services
                                    </a>
                                    <div>
                                        <button type="reset" class="btn btn-outline-danger me-2">
                                            <i class="fas fa-undo me-1"></i>
                                            Réinitialiser
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-lg" onclick="console.log('Submit button clicked');">
                                            <i class="fas fa-save me-1"></i>
                                            Créer le service
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-suggestion de tags
    const tagsInput = document.getElementById('tags');
    const commonTags = ['professionnel', 'rapide', 'qualité', 'expérimenté', 'fiable', 'disponible', 'certifié', 'garantie'];
    
    tagsInput.addEventListener('focus', function() {
        if (!this.value) {
            this.placeholder = 'Ex: ' + commonTags.slice(0, 3).join(', ');
        }
    });
    
    // Validation du prix
    const priceInput = document.getElementById('price');
    priceInput.addEventListener('input', function() {
        if (this.value < 0) this.value = 0;
    });
    
    // Validation de la durée
    const durationInput = document.getElementById('duration');
    durationInput.addEventListener('input', function() {
        if (this.value < 15) this.value = 15;
        if (this.value % 15 !== 0) {
            this.value = Math.round(this.value / 15) * 15;
        }
    });
});
</script>
@endsection
