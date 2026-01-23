@extends('layouts.app')

@section('title', 'Donner un avis')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Header -->
            <div class="text-center mb-4">
                <h2 class="mb-3">
                    <i class="fas fa-star me-2"></i>Donner votre avis
                </h2>
                <p class="text-muted">Partagez votre expérience avec ce service</p>
            </div>

            <!-- Service Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="card-title">{{ $booking->service->title }}</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Prestataire:</span>
                        <span>{{ $booking->service->user->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Date du service:</span>
                        <span>{{ $booking->date->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <input type="hidden" name="service_id" value="{{ $booking->service_id }}">
                <input type="hidden" name="provider_id" value="{{ $booking->service->user_id }}">

                <!-- Rating -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Note générale</h6>
                        <div class="text-center">
                            <div id="star-rating" class="mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="btn btn-link p-0 star-btn" data-rating="{{ $i }}">
                                        <i class="fas fa-star fa-2x text-muted"></i>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-value" value="5" required>
                            <small class="text-muted">Cliquez sur les étoiles pour noter</small>
                        </div>
                    </div>
                </div>

                <!-- Review Categories -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Évaluation détaillée</h6>
                        
                        <!-- Quality -->
                        <div class="mb-3">
                            <label class="form-label">Qualité du service</label>
                            <div class="d-flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="btn btn-link p-0 category-star" data-category="quality" data-rating="{{ $i }}">
                                        <i class="fas fa-star text-muted"></i>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="quality_rating" id="quality-rating" value="5">
                        </div>

                        <!-- Communication -->
                        <div class="mb-3">
                            <label class="form-label">Communication</label>
                            <div class="d-flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="btn btn-link p-0 category-star" data-category="communication" data-rating="{{ $i }}">
                                        <i class="fas fa-star text-muted"></i>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="communication_rating" id="communication-rating" value="5">
                        </div>

                        <!-- Punctuality -->
                        <div class="mb-3">
                            <label class="form-label">Pontualité</label>
                            <div class="d-flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="btn btn-link p-0 category-star" data-category="punctuality" data-rating="{{ $i }}">
                                        <i class="fas fa-star text-muted"></i>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="punctuality_rating" id="punctuality-rating" value="5">
                        </div>
                    </div>
                </div>

                <!-- Comment -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Votre commentaire</h6>
                        <div class="mb-3">
                            <textarea class="form-control" name="comment" rows="4" required
                                      placeholder="Décrivez votre expérience avec ce service..."></textarea>
                        </div>
                        
                        <!-- Quick Tags -->
                        <div class="mb-3">
                            <label class="form-label">Mots-clés (optionnel)</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm tag-btn">Professionnel</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm tag-btn">Ponctuel</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm tag-btn">Courtois</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm tag-btn">Qualité</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm tag-btn">Rapide</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm tag-btn">Recommandé</button>
                            </div>
                            <input type="hidden" name="tags" id="review-tags" value="">
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer mon avis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Star rating system
    const starButtons = document.querySelectorAll('.star-btn');
    const ratingValue = document.getElementById('rating-value');
    
    starButtons.forEach(button => {
        button.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            ratingValue.value = rating;
            
            // Update stars
            starButtons.forEach((star, index) => {
                const icon = star.querySelector('i');
                if (index < rating) {
                    icon.classList.remove('text-muted');
                    icon.classList.add('text-warning');
                } else {
                    icon.classList.remove('text-warning');
                    icon.classList.add('text-muted');
                }
            });
        });
    });

    // Category ratings
    const categoryStars = document.querySelectorAll('.category-star');
    categoryStars.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            const rating = parseInt(this.dataset.rating);
            
            // Update hidden input
            document.getElementById(`${category}-rating`).value = rating;
            
            // Update stars for this category
            const categoryButtons = document.querySelectorAll(`[data-category="${category}"]`);
            categoryButtons.forEach((star, index) => {
                const icon = star.querySelector('i');
                if (index < rating) {
                    icon.classList.remove('text-muted');
                    icon.classList.add('text-warning');
                } else {
                    icon.classList.remove('text-warning');
                    icon.classList.add('text-muted');
                }
            });
        });
    });

    // Tag selection
    const tagButtons = document.querySelectorAll('.tag-btn');
    const tagsInput = document.getElementById('review-tags');
    const selectedTags = new Set();
    
    tagButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tag = this.textContent.trim();
            
            if (selectedTags.has(tag)) {
                selectedTags.delete(tag);
                this.classList.remove('btn-primary');
                this.classList.add('btn-outline-secondary');
            } else {
                selectedTags.add(tag);
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-primary');
            }
            
            tagsInput.value = Array.from(selectedTags).join(',');
        });
    });
});
</script>
@endsection
