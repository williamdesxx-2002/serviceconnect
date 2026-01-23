@extends('layouts.app')

@section('title', 'Créer un service')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Créer un nouveau service
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('services.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre du service *</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Catégorie *</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Prix (FCFA) *</label>
                                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price_type" class="form-label">Type de prix *</label>
                                    <select class="form-select" id="price_type" name="price_type" required>
                                        <option value="fixed" {{ old('price_type', 'fixed') == 'fixed' ? 'selected' : '' }}>Prix fixe</option>
                                        <option value="hourly" {{ old('price_type') == 'hourly' ? 'selected' : '' }}>Prix par heure</option>
                                        <option value="daily" {{ old('price_type') == 'daily' ? 'selected' : '' }}>Prix par jour</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="neighborhood" class="form-label">Quartier *</label>
                            <select class="form-select" id="neighborhood" name="neighborhood" required>
                                <option value="">Sélectionner un quartier</option>
                                <option value="centre-ville" {{ old('neighborhood') == 'centre-ville' ? 'selected' : '' }}>Centre-ville</option>
                                <option value="nkembo" {{ old('neighborhood') == 'nkembo' ? 'selected' : '' }}>Nkembo</option>
                                <option value="owendo" {{ old('neighborhood') == 'owendo' ? 'selected' : '' }}>Owendo</option>
                                <option value="akanda" {{ old('neighborhood') == 'akanda' ? 'selected' : '' }}>Akanda</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags') }}" placeholder="Ex: professionnel, rapide, qualité">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('services.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Créer le service
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
