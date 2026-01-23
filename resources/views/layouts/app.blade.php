<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ServiceConnect') }} - @yield('title', 'Marketplace de Services Locaux')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/image-upload.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animations.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --secondary-color: #06b6d4;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-color);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 80px 0 60px;
        }

        .service-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .service-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .service-icon {
            width: 60px;
            height: 60px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            font-weight: 500;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-primary-nav {
            background: var(--primary-color);
            color: white !important;
            border-radius: 6px;
            font-weight: 500;
            padding: 8px 16px;
            transition: all 0.3s ease;
            border: 1px solid var(--primary-color);
            display: inline-block;
            text-decoration: none;
        }

        .btn-primary-nav:hover {
            background: var(--primary-dark);
            color: white !important;
            border-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(79, 70, 229, 0.2);
        }

        .btn-primary-nav:focus {
            background: var(--primary-dark);
            color: white !important;
            border-color: var(--primary-dark);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .filter-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 1.5rem;
        }

        .stat-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .border-left-primary {
            border-left: 4px solid var(--primary-color) !important;
        }

        .border-left-success {
            border-left: 4px solid var(--success-color) !important;
        }

        .border-left-info {
            border-left: 4px solid var(--secondary-color) !important;
        }

        .border-left-warning {
            border-left: 4px solid var(--warning-color) !important;
        }

        .auth-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 12px 15px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sidebar {
            background: white;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
            min-height: calc(100vh - 56px);
        }

        .sidebar .nav-link {
            color: var(--dark-color);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: var(--primary-color);
            color: white;
        }

        .rating {
            color: #fbbf24;
        }

        .badge-category {
            background: var(--primary-color);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding-left: 45px;
            border-radius: 25px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }

        /* Styles pour le menu utilisateur amélioré */
        .dropdown-menu {
            border: none;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-radius: 12px;
            padding: 8px;
            margin-top: 8px;
        }

        .dropdown-header {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 12px;
            color: #6b7280;
            background: transparent;
            border: none;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 10px 12px;
            margin: 2px 0;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }

        .dropdown-item:hover {
            background: #f8fafc;
            transform: translateX(2px);
        }

        .dropdown-item i {
            width: 16px;
            text-align: center;
        }

        .dropdown-item .badge {
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
        }

        .dropdown-divider {
            margin: 8px 0;
            border-color: #e5e7eb;
        }

        .user-avatar-circle {
            transition: all 0.2s ease;
        }

        .user-avatar-circle:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-handshake me-2"></i>ServiceConnect
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('services.index') }}">
                                <i class="fas fa-search me-1"></i>Services
                            </a>
                        </li>
                        
                        @if(auth()->check())
                            @if(auth()->user()->isClient())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('bookings.index') }}">
                                        <i class="fas fa-calendar me-1"></i>Mes Réservations
                                    </a>
                                </li>
                            @elseif(auth()->user()->isProvider())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('services.my') }}">
                                        <i class="fas fa-briefcase me-1"></i>Mes Services
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('bookings.index') }}">
                                        <i class="fas fa-calendar me-1"></i>Réservations
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link btn-primary-nav" href="{{ route('services.create') }}">
                                        <i class="fas fa-plus me-1"></i>Ajouter un service
                                    </a>
                                </li>
                            @elseif(auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                    </a>
                                </li>
                            @endif
                        @endif
                    </ul>

                    <ul class="navbar-nav">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i>Connexion
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-primary ms-2" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i>Inscription
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" id="userDropdown">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="rounded-circle me-2 user-avatar-circle" width="32" height="32">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2 user-avatar-circle" style="width: 32px; height: 32px; font-size: 14px; font-weight: 600;">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span>{{ auth()->user()->name }}</span>
                                    @if(auth()->user()->is_verified)
                                        <i class="fas fa-check-circle text-success ms-1"></i>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown" style="min-width: 220px;">
                                    <!-- Section Compte -->
                                    <li>
                                        <h6 class="dropdown-header d-flex align-items-center">
                                            <i class="fas fa-user-circle me-2 text-primary"></i>
                                            Mon Compte
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('profile') }}">
                                        <span><i class="fas fa-user me-2 text-primary"></i>Profil</span>
                                        <i class="fas fa-chevron-right text-muted"></i>
                                    </a></li>
                                    <li><a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('profile.edit') }}">
                                        <span><i class="fas fa-edit me-2 text-secondary"></i>Modifier</span>
                                        <i class="fas fa-chevron-right text-muted"></i>
                                    </a></li>
                                    <li><a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('messages.index') }}">
                                        <span><i class="fas fa-envelope me-2 text-info"></i>Messages</span>
                                        @if(auth()->user()->receivedMessages()->where('is_read', false)->count() > 0)
                                            <span class="badge bg-danger">{{ auth()->user()->receivedMessages()->where('is_read', false)->count() }}</span>
                                        @endif
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    <!-- Section Dashboard -->
                                    <li>
                                        <h6 class="dropdown-header d-flex align-items-center">
                                            <i class="fas fa-tachometer-alt me-2 text-success"></i>
                                            Dashboard
                                        </h6>
                                    </li>
                                    @if(auth()->user()->isClient())
                                        <li><a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('client.dashboard') }}">
                                            <span><i class="fas fa-home me-2 text-primary"></i>Client</span>
                                            <i class="fas fa-chevron-right text-muted"></i>
                                        </a></li>
                                    @elseif(auth()->user()->isProvider())
                                        <li><a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('provider.dashboard') }}">
                                            <span><i class="fas fa-briefcase me-2 text-warning"></i>Prestataire</span>
                                            <i class="fas fa-chevron-right text-muted"></i>
                                        </a></li>
                                        <li><a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('services.my') }}">
                                            <span><i class="fas fa-box me-2 text-info"></i>Mes Services</span>
                                            <i class="fas fa-chevron-right text-muted"></i>
                                        </a></li>
                                    @elseif(auth()->user()->isAdmin())
                                        <li><a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                                            <span><i class="fas fa-cog me-2 text-danger"></i>Admin</span>
                                            <i class="fas fa-chevron-right text-muted"></i>
                                        </a></li>
                                    @endif
                                    <li><a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('bookings.index') }}">
                                        <span><i class="fas fa-calendar me-2 text-secondary"></i>Réservations</span>
                                        <i class="fas fa-chevron-right text-muted"></i>
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    <!-- Section Déconnexion -->
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center justify-content-between w-100">
                                                <span><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</span>
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-dark text-white py-4 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-handshake me-2"></i>ServiceConnect</h5>
                        <p class="text-muted">Marketplace de services locaux pour connecter prestataires et clients.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0">&copy; {{ date('Y') }} ServiceConnect. Tous droits réservés.</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bouton flottant "Ajouter un service" pour les prestataires -->
    @if(auth()->check() && auth()->user()->isProvider())
        <a href="{{ route('services.create') }}" 
           class="floating-add-service-btn" 
           title="Ajouter un nouveau service">
            <i class="fas fa-plus"></i>
        </a>
        
        <style>
        .floating-add-service-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            z-index: 1000;
            text-decoration: none;
        }
        
        .floating-add-service-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.6);
            color: white;
            text-decoration: none;
        }
        
        .floating-add-service-btn:active {
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .floating-add-service-btn {
                bottom: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }
        </style>
    @endif

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/enhancements.js') }}"></script>
    @stack('scripts')
</body>
</html>
