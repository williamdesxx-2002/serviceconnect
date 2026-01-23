<!-- Admin Sidebar -->
<div class="sidebar">
    <div class="p-3">
        <h6 class="text-uppercase fw-bold mb-3">Admin</h6>
        <nav class="nav flex-column">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i>Utilisateurs
            </a>
            <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <i class="fas fa-briefcase me-2"></i>Services
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="fas fa-calendar me-2"></i>Réservations
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags me-2"></i>Catégories
            </a>
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar me-2"></i>Rapports
            </a>
        </nav>
        
        <hr class="my-3">
        
        <div class="mt-3">
            <h6 class="text-uppercase fw-bold mb-2">Actions Rapides</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-user-plus me-1"></i>Nouvel utilisateur
                </a>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-plus me-1"></i>Nouvelle catégorie
                </a>
                <a href="{{ route('services.create') }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-plus me-1"></i>Nouveau service
                </a>
            </div>
        </div>
        
        <hr class="my-3">
        
        <div class="mt-3">
            <h6 class="text-uppercase fw-bold mb-2">Système</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-home me-1"></i>Retour au site
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.sidebar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: calc(100vh - 2rem);
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.sidebar .nav-link {
    color: rgba(255, 255, 255, 0.8);
    padding: 0.75rem 1rem;
    border-radius: 8px;
    margin-bottom: 0.25rem;
    transition: all 0.3s ease;
}

.sidebar .nav-link:hover {
    color: white;
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
}

.sidebar .nav-link.active {
    color: white;
    background: rgba(255, 255, 255, 0.2);
    font-weight: 600;
}

.sidebar h6 {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.sidebar .btn-sm {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.sidebar hr {
    border-color: rgba(255, 255, 255, 0.2);
}
</style>
