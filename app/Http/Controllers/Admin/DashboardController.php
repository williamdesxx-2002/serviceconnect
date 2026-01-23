<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_users' => User::count(),
            'total_providers' => User::where('role', 'provider')->count(),
            'total_services' => Service::count(),
            'pending_services' => Service::where('status', 'pending')->count(),
            'total_bookings' => Booking::count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'monthly_revenue' => Payment::where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount'),
        ];

        // Graphique des revenus (12 derniers mois)
        $revenueChart = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top catégories
        $topCategories = DB::table('services')
            ->join('categories', 'services.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('COUNT(*) as count'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Dernières réservations
        $recentBookings = Booking::with(['service', 'client', 'provider'])
            ->latest()
            ->limit(10)
            ->get();

        // Top prestataires
        $topProviders = User::withCount(['providerBookings' => function($q) {
                $q->where('status', 'completed');
            }])
            ->where('role', 'provider')
            ->orderByDesc('provider_bookings_count')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'revenueChart', 
            'topCategories', 
            'recentBookings',
            'topProviders'
        ));
    }

    public function users()
    {
        $query = User::withCount(['services', 'clientBookings', 'providerBookings']);

        // Filtrage par recherche
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtrage par rôle
        if (request('role')) {
            $query->where('role', request('role'));
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function toggleUser(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        
        return back()->with('success', 'Statut utilisateur modifié');
    }

    public function verifyUser(User $user)
    {
        $user->update(['is_verified' => true]);
        
        // Envoyer notification email au prestataire
        // TODO: Implémenter l'envoi d'email de vérification
        
        return back()->with('success', 'Le compte du prestataire a été vérifié avec succès');
    }

    public function destroyUser(User $user)
    {
        try {
            // Empêcher la suppression de son propre compte
            if ($user->id === auth()->id()) {
                return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
            }

            // Supprimer toutes les données associées à l'utilisateur
            DB::beginTransaction();

            // Supprimer les services de l'utilisateur
            $user->services()->each(function ($service) {
                // Supprimer les images des services
                if ($service->images) {
                    foreach ($service->images as $image) {
                        $imagePath = public_path('storage/services/' . basename($image));
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                }
                $service->delete();
            });

            // Supprimer les réservations où l'utilisateur est client
            $user->clientBookings()->delete();
            
            // Supprimer les réservations où l'utilisateur est prestataire
            $user->providerBookings()->delete();

            // Supprimer les messages envoyés/reçus
            $user->sentMessages()->delete();
            $user->receivedMessages()->delete();

            // Supprimer les avis laissés par l'utilisateur
            $user->reviews()->delete();

            // Supprimer les paiements associés
            $user->payments()->delete();

            // Supprimer l'avatar si existe
            if ($user->avatar) {
                $avatarPath = public_path('storage/avatars/' . basename($user->avatar));
                if (file_exists($avatarPath)) {
                    unlink($avatarPath);
                }
            }

            // Supprimer l'utilisateur
            $user->delete();

            DB::commit();

            return back()->with('success', 'L\'utilisateur et toutes ses données ont été supprimés définitivement');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    // Méthodes pour la gestion des réservations
    public function bookings()
    {
        $query = Booking::with(['service', 'client', 'provider']);

        // Filtrage par recherche
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('client', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                })->orWhereHas('provider', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                })->orWhereHas('service', function($subQ) use ($search) {
                    $subQ->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Filtrage par statut
        if (request('status')) {
            $query->where('status', request('status'));
        }

        $bookings = $query->latest()->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function showBooking(Booking $booking)
    {
        $booking->load(['service', 'client', 'provider', 'review', 'payment']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function cancelBooking(Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Réservation annulée avec succès');
    }

    public function reportBooking(Booking $booking)
    {
        $request = request();
        
        $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);
        
        $booking->update([
            'is_reported' => true,
            'report_reason' => $request->reason,
            'report_description' => $request->description,
            'reported_at' => now(),
            'reported_by' => auth()->id()
        ]);
        
        return back()->with('success', 'Réservation signalée avec succès');
    }

    public function providerBookings(User $user)
    {
        $query = Booking::with(['service', 'client'])
            ->where('provider_id', $user->id);

        // Filtrage par recherche
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('client', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                })->orWhereHas('service', function($subQ) use ($search) {
                    $subQ->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Filtrage par statut
        if (request('status')) {
            $query->where('status', request('status'));
        }

        $bookings = $query->latest()->paginate(20);

        return view('admin.bookings.provider', compact('bookings', 'user'));
    }

    // Méthodes pour la gestion des services
    public function services()
    {
        $query = Service::with(['user', 'category']);

        // Filtrage par recherche
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrage par statut
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Filtrage par catégorie
        if (request('category')) {
            $query->where('category_id', request('category'));
        }

        $services = $query->latest()->paginate(20);

        return view('admin.services.index', compact('services'));
    }

    public function showService(Service $service)
    {
        $service->load(['user', 'category', 'reviews', 'bookings']);
        return view('admin.services.show', compact('service'));
    }

    public function approveService(Service $service)
    {
        $service->update(['status' => 'approved']);
        return back()->with('success', 'Service approuvé avec succès');
    }

    public function rejectService(Service $service)
    {
        $service->update(['status' => 'rejected']);
        return back()->with('success', 'Service rejeté avec succès');
    }

    public function toggleService(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        
        return back()->with('success', 'Statut du service modifié');
    }

    public function reportService(Service $service)
    {
        $request = request();
        
        $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);
        
        $service->update([
            'is_reported' => true,
            'report_reason' => $request->reason,
            'report_description' => $request->description,
            'reported_at' => now(),
            'reported_by' => auth()->id()
        ]);
        
        return back()->with('success', 'Service signalé avec succès');
    }

    public function deleteService(Service $service)
    {
        // Supprimer les images du service
        if ($service->images) {
            foreach ($service->images as $image) {
                $imagePath = public_path('storage/services/' . basename($image));
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        
        $service->delete();
        return back()->with('success', 'Service supprimé avec succès');
    }

    // Méthodes supplémentaires pour les utilisateurs
    public function showUser(User $user)
    {
        $user->load(['services', 'clientBookings', 'providerBookings', 'reviews']);
        return view('admin.users.show', compact('user'));
    }

    public function blockUser(User $user)
    {
        $user->update([
            'is_blocked' => true,
            'blocked_at' => now(),
            'blocked_by' => auth()->id(),
            'block_reason' => request('reason', 'Violation des conditions d\'utilisation')
        ]);
        return back()->with('success', 'Utilisateur bloqué avec succès');
    }

    public function unblockUser(User $user)
    {
        $user->update([
            'is_blocked' => false,
            'blocked_at' => null,
            'blocked_by' => null,
            'block_reason' => null
        ]);
        return back()->with('success', 'Utilisateur débloqué avec succès');
    }

    public function deleteUser(User $user)
    {
        return $this->destroyUser($user);
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,provider,client',
            'phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'whatsapp_number' => $request->whatsapp_number,
            'bio' => $request->bio,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'is_verified' => true,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }
}
