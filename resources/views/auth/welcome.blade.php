@extends('layouts.app')

@section('title', 'Bienvenue sur ServiceConnect')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body text-center p-5">
                    <!-- Icône de succès -->
                    <div class="mb-4">
                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-check fa-2x text-white"></i>
                        </div>
                    </div>
                    
                    <!-- Message de bienvenue -->
                    <h2 class="mb-3">Bienvenue {{ auth()->user()->name }} !</h2>
                    
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif
                    
                    <!-- Message selon le rôle -->
                    <div class="mb-4">
                        @if(auth()->user()->isAdmin())
                            <p class="lead text-muted">
                                Votre compte administrateur a été créé avec succès. 
                                Vous pouvez maintenant accéder au tableau de bord pour gérer la plateforme.
                            </p>
                        @elseif(auth()->user()->isProvider())
                            <p class="lead text-muted">
                                Bienvenue parmi nos prestataires ! 
                                Complétez votre profil et commencez à offrir vos services dès maintenant.
                            </p>
                        @else
                            <p class="lead text-muted">
                                Bienvenue sur ServiceConnect ! 
                                Découvrez nos services et faites vos premières réservations.
                            </p>
                        @endif
                    </div>
                    
                    <!-- Actions rapides selon le rôle -->
                    <div class="row">
                        @if(auth()->user()->isAdmin())
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    Tableau de bord admin
                                </a>
                            </div>
                        @endif
                        
                        @if(auth()->user()->isProvider())
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-edit me-2"></i>
                                    Compléter mon profil
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('services.create') }}" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-plus me-2"></i>
                                    Ajouter un service
                                </a>
                            </div>
                        @endif
                        
                        @if(auth()->user()->isClient())
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('services.index') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-search me-2"></i>
                                    Découvrir les services
                                </a>
                            </div>
                        @endif
                        
                        <!-- Actions communes -->
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('profile') }}" class="btn btn-outline-secondary btn-lg w-100">
                                <i class="fas fa-user me-2"></i>
                                Mon profil
                            </a>
                        </div>
                    </div>
                    
                    <!-- Étapes suivantes -->
                    <div class="mt-5">
                        <h5 class="mb-3">Prochaines étapes</h5>
                        <div class="row">
                            @if(auth()->user()->isAdmin())
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-users fa-2x text-primary mb-3"></i>
                                            <h6>Gérer les utilisateurs</h6>
                                            <small class="text-muted">Supervisez les comptes utilisateurs</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-briefcase fa-2x text-success mb-3"></i>
                                            <h6>Modérer les services</h6>
                                            <small class="text-muted">Validez les offres de services</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-chart-bar fa-2x text-info mb-3"></i>
                                            <h6>Voir les statistiques</h6>
                                            <small class="text-muted">Consultez les rapports d'activité</small>
                                        </div>
                                    </div>
                                </div>
                            @elseif(auth()->user()->isProvider())
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-edit fa-2x text-primary mb-3"></i>
                                            <h6>Compléter le profil</h6>
                                            <small class="text-muted">Ajoutez vos informations</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-plus fa-2x text-success mb-3"></i>
                                            <h6>Créer des services</h6>
                                            <small class="text-muted">Proposez vos prestations</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-calendar fa-2x text-info mb-3"></i>
                                            <h6>Gérer les réservations</h6>
                                            <small class="text-muted">Acceptez les demandes</small>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-search fa-2x text-primary mb-3"></i>
                                            <h6>Explorer</h6>
                                            <small class="text-muted">Découvrez les services</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-calendar fa-2x text-success mb-3"></i>
                                            <h6>Réserver</h6>
                                            <small class="text-muted">Réservez des services</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-comment fa-2x text-info mb-3"></i>
                                            <h6>Contacter</h6>
                                            <small class="text-muted">Communiquez avec les prestataires</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
