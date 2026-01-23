@extends('layouts.app')

@section('title', 'Modifier un service')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Modifier le service : {{ $service->title }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('services.update', $service) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Informations principales -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Titre du service *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $service->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="5" required>{{ old('description', $service->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Catégorie *</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id', $service->category_id) == $category->id ? 'selected' : '' }}
                                                    data-name="{{ $category->name }}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Champ pour préciser la catégorie si "Autre" est sélectionné -->
                                <div class="mb-3" id="otherCategoryField" style="display: none;">
                                    <label for="other_category" class="form-label">Préciser la catégorie *</label>
                                    <input type="text" class="form-control @error('other_category') is-invalid @enderror" 
                                           id="other_category" name="other_category" 
                                           value="{{ old('other_category', $service->other_category ?? '') }}" 
                                           placeholder="Entrez le nom de la catégorie...">
                                    @error('other_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Veuillez préciser la catégorie qui n'est pas dans la liste</small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Prix (FCFA) *</label>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price', $service->price) }}" min="0" step="100" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price_type" class="form-label">Type de prix *</label>
                                            <select class="form-select @error('price_type') is-invalid @enderror" 
                                                    id="price_type" name="price_type" required>
                                                <option value="fixed" {{ old('price_type', $service->price_type) == 'fixed' ? 'selected' : '' }}>
                                                    Prix fixe
                                                </option>
                                                <option value="hourly" {{ old('price_type', $service->price_type) == 'hourly' ? 'selected' : '' }}>
                                                    Prix par heure
                                                </option>
                                                <option value="daily" {{ old('price_type', $service->price_type) == 'daily' ? 'selected' : '' }}>
                                                    Prix par jour
                                                </option>
                                            </select>
                                            @error('price_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="duration" class="form-label">Durée estimée (minutes)</label>
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                           id="duration" name="duration" value="{{ old('duration', $service->duration) }}" min="1">
                                    <small class="form-text text-muted">Durée estimée pour réaliser ce service</small>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tags</label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                           id="tags" name="tags" value="{{ old('tags', is_array($service->tags) ? implode(', ', $service->tags) : $service->tags) }}" 
                                           placeholder="Ex: plomberie, dépannage, urgence">
                                    <small class="form-text text-muted">Séparez les tags par des virgules</small>
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Images existantes -->
                                @if($service->image_urls && count($service->image_urls) > 0)
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <i class="fas fa-images me-2"></i>Photos actuelles
                                        </label>
                                        <div class="row g-2 mb-3">
                                            @foreach($service->image_urls as $index => $image_url)
                                                <div class="col-3 position-relative">
                                                    <div class="existing-image-container" id="existing-image-{{ $index }}">
                                                        <img src="{{ $image_url }}" class="card-img-top" style="height: 120px; object-fit: cover; width: 100%;">
                                                        <button type="button" class="remove-existing-btn" 
                                                                onclick="removeExistingImage({{ $index }})" title="Supprimer cette photo">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="existing_images" value="{{ json_encode(array_keys($service->image_urls)) }}">
                                        <input type="hidden" id="removed_images" name="removed_images" value="">
                                    </div>
                                @endif

                                <!-- Upload de nouvelles images -->
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="fas fa-plus-circle me-2"></i>Ajouter de nouvelles photos
                                    </label>
                                    <div class="border rounded p-3 bg-light">
                                        <!-- Zone de drag & drop -->
                                        <div id="dropZone" class="text-center p-4 border-2 border-dashed rounded mb-3"
                                             style="border-color: #dee2e6; cursor: pointer;">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                            <h6 class="text-muted">Glissez-déposez vos photos ici</h6>
                                            <p class="text-muted mb-3">ou</p>
                                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('images').click()">
                                                <i class="fas fa-folder-open me-2"></i>Parcourir vos fichiers
                                            </button>
                                            <input type="file" class="d-none" id="images" name="images[]" multiple 
                                                   accept="image/*" onchange="handleFileSelect(event)">
                                            <p class="text-muted small mt-3 mb-0">
                                                Formats acceptés : JPG, PNG, GIF<br>
                                                Taille maximale : 5MB par photo<br>
                                                Maximum 5 photos au total
                                            </p>
                                        </div>
                                        
                                        <!-- Aperçu des nouvelles images -->
                                        <div id="imagePreview" class="row g-2"></div>
                                        
                                        <!-- Champ file caché pour la soumission -->
                                        <input type="file" class="d-none" id="imagesSubmit" name="new_images[]" multiple>
                                        
                                        @error('images')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Localisation -->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            Localisation
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="neighborhood" class="form-label">Quartier à Libreville *</label>
                                            <select class="form-select @error('neighborhood') is-invalid @enderror" 
                                                    id="neighborhood" name="neighborhood" required>
                                                <option value="">Sélectionner un quartier</option>
                                                <option value="centre-ville" {{ old('neighborhood', $service->neighborhood) == 'centre-ville' ? 'selected' : '' }}>Centre-ville</option>
                                                <option value="nkembo" {{ old('neighborhood', $service->neighborhood) == 'nkembo' ? 'selected' : '' }}>Nkembo</option>
                                                <option value="owendo" {{ old('neighborhood', $service->neighborhood) == 'owendo' ? 'selected' : '' }}>Owendo</option>
                                                <option value="akanda" {{ old('neighborhood', $service->neighborhood) == 'akanda' ? 'selected' : '' }}>Akanda</option>
                                                <option value="angondjé" {{ old('neighborhood', $service->neighborhood) == 'angondjé' ? 'selected' : '' }}>Angondjé</option>
                                                <option value="batterie-iv" {{ old('neighborhood', $service->neighborhood) == 'batterie-iv' ? 'selected' : '' }}>Batterie IV</option>
                                                <option value="batterie-viii" {{ old('neighborhood', $service->neighborhood) == 'batterie-viii' ? 'selected' : '' }}>Batterie VIII</option>
                                                <option value="glass" {{ old('neighborhood', $service->neighborhood) == 'glass' ? 'selected' : '' }}>Glass</option>
                                                <option value="mont-bouet" {{ old('neighborhood', $service->neighborhood) == 'mont-bouet' ? 'selected' : '' }}>Mont-Bouët</option>
                                                <option value="nzeng-ayong" {{ old('neighborhood', $service->neighborhood) == 'nzeng-ayong' ? 'selected' : '' }}>Nzeng-Ayong</option>
                                                <option value="sablière" {{ old('neighborhood', $service->neighborhood) == 'sablière' ? 'selected' : '' }}>Sablière</option>
                                                <option value="sogara" {{ old('neighborhood', $service->neighborhood) == 'sogara' ? 'selected' : '' }}>Sogara</option>
                                                <option value="tollé" {{ old('neighborhood', $service->neighborhood) == 'tollé' ? 'selected' : '' }}>Tollé</option>
                                                <option value="autre" {{ old('neighborhood', $service->neighborhood) == 'autre' ? 'selected' : '' }}>Autre</option>
                                            </select>
                                            @error('neighborhood')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Sélectionnez le quartier où vous proposez votre service à Libreville</small>
                                        </div>

                                        <!-- Champ pour préciser le quartier si "Autre" est sélectionné -->
                                        <div class="mb-3" id="otherNeighborhoodField" style="display: none;">
                                            <label for="other_neighborhood" class="form-label">Préciser ce quartier *</label>
                                            <input type="text" class="form-control @error('other_neighborhood') is-invalid @enderror" 
                                                   id="other_neighborhood" name="other_neighborhood" 
                                                   value="{{ old('other_neighborhood', $service->other_neighborhood ?? '') }}" 
                                                   placeholder="Entrez le nom du quartier...">
                                            @error('other_neighborhood')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Veuillez préciser le quartier qui n'est pas dans la liste</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('services.my') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Mettre à jour le service
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedFiles = [];
let removedExistingImages = [];
const maxFiles = 5;
const maxFileSize = 5 * 1024 * 1024; // 5MB
const existingImagesCount = {{ count($service->image_urls ?? []) }};

// Configuration de la zone de drag & drop
const dropZone = document.getElementById('dropZone');

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-primary', 'bg-light');
});

dropZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-primary', 'bg-light');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-primary', 'bg-light');
    handleFiles(e.dataTransfer.files);
});

// Gestion de la sélection de fichiers
function handleFileSelect(event) {
    handleFiles(event.target.files);
}

// Traitement des fichiers
function handleFiles(files) {
    const remainingSlots = maxFiles - existingImagesCount - selectedFiles.length;
    
    if (remainingSlots <= 0) {
        alert('Vous avez déjà atteint le maximum de ' + maxFiles + ' photos.');
        return;
    }
    
    const filesToProcess = Array.from(files).slice(0, remainingSlots);
    
    filesToProcess.forEach(file => {
        if (!file.type.startsWith('image/')) {
            alert('Le fichier "' + file.name + '" n\'est pas une image valide.');
            return;
        }
        
        if (file.size > maxFileSize) {
            alert('Le fichier "' + file.name + '" dépasse la taille maximale de 5MB.');
            return;
        }
        
        selectedFiles.push(file);
        addImagePreview(file);
    });
    
    updateSubmitInput();
}

// Ajout d'un aperçu d'image
function addImagePreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const col = document.createElement('div');
        col.className = 'col-3 position-relative mb-2';
        
        col.innerHTML = `
            <div class="image-preview-container">
                <img src="${e.target.result}" class="card-img-top" style="height: 120px; object-fit: cover; width: 100%;">
                <button type="button" class="remove-image-btn" onclick="removeNewImage('${file.name}')" title="Supprimer cette photo">
                    <i class="fas fa-times"></i>
                </button>
                <div class="image-info">
                    ${(file.size / 1024).toFixed(1)} KB
                </div>
            </div>
        `;
        
        document.getElementById('imagePreview').appendChild(col);
    };
    reader.readAsDataURL(file);
}

// Suppression d'une nouvelle image
function removeNewImage(fileName) {
    selectedFiles = selectedFiles.filter(file => file.name !== fileName);
    updateImagePreview();
    updateSubmitInput();
}

// Suppression d'une image existante
function removeExistingImage(index) {
    removedExistingImages.push(index);
    
    // Masquer l'image existante
    const existingImages = document.querySelectorAll('.col-3');
    if (existingImages[index]) {
        existingImages[index].style.display = 'none';
    }
    
    // Mettre à jour le champ caché
    document.getElementById('removed_images').value = JSON.stringify(removedExistingImages);
}

// Mise à jour des aperçus
function updateImagePreview() {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    selectedFiles.forEach(file => addImagePreview(file));
}

// Mise à jour du champ de soumission
function updateSubmitInput() {
    const submitInput = document.getElementById('imagesSubmit');
    const dt = new DataTransfer();
    
    selectedFiles.forEach(file => dt.items.add(file));
    submitInput.files = dt.files;
}

// Gestion du champ "Préciser ce quartier"
document.addEventListener('DOMContentLoaded', function() {
    const neighborhoodSelect = document.getElementById('neighborhood');
    const otherNeighborhoodField = document.getElementById('otherNeighborhoodField');
    const otherNeighborhoodInput = document.getElementById('other_neighborhood');
    
    function toggleOtherNeighborhoodField() {
        if (neighborhoodSelect.value === 'autre') {
            otherNeighborhoodField.style.display = 'block';
            otherNeighborhoodInput.required = true;
        } else {
            otherNeighborhoodField.style.display = 'none';
            otherNeighborhoodInput.required = false;
            otherNeighborhoodInput.value = '';
        }
    }
    
    // Écouter les changements sur la liste déroulante
    neighborhoodSelect.addEventListener('change', toggleOtherNeighborhoodField);
    
    // Vérifier au chargement de la page
    toggleOtherNeighborhoodField();
    
    // Gestion du champ "Préciser la catégorie"
    const categorySelect = document.getElementById('category_id');
    const otherCategoryField = document.getElementById('otherCategoryField');
    const otherCategoryInput = document.getElementById('other_category');
    
    function toggleOtherCategoryField() {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const categoryName = selectedOption.getAttribute('data-name');
        
        if (categoryName && categoryName.toLowerCase() === 'autre') {
            otherCategoryField.style.display = 'block';
            otherCategoryInput.required = true;
        } else {
            otherCategoryField.style.display = 'none';
            otherCategoryInput.required = false;
            otherCategoryInput.value = '';
        }
    }
    
    // Écouter les changements sur la liste des catégories
    categorySelect.addEventListener('change', toggleOtherCategoryField);
    
    // Vérifier au chargement de la page
    toggleOtherCategoryField();
});
</script>
@endsection
