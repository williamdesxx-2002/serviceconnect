@extends('layouts.app')

@section('title', 'Mes avis')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-star me-2"></i>
                        Mes avis
                    </h5>
                </div>
                <div class="card-body">
                    @if($reviews->count() > 0)
                        <div class="row">
                            @foreach($reviews as $review)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    @if(auth()->user()->isClient())
                                                        <h6 class="mb-1">{{ $review->provider->name }}</h6>
                                                    @else
                                                        <h6 class="mb-1">{{ $review->client->name }}</h6>
                                                    @endif
                                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                                </div>
                                                <div class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            
                                            <p class="mb-2">{{ $review->comment }}</p>
                                            
                                            <small class="text-muted">
                                                <i class="fas fa-briefcase me-1"></i>
                                                {{ $review->service->title }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun avis pour le moment</h5>
                            <p class="text-muted">Vous n'avez pas encore reçu d'avis de vos clients.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
