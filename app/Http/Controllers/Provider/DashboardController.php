<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Message;
use App\Models\Review;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        // Statistiques du prestataire
        $totalServices = $user->services()->count();
        $activeServices = $user->services()->where('is_active', true)->count();
        $totalBookings = $user->providerBookings()->count();
        $pendingBookings = $user->providerBookings()->where('status', 'pending')->count();
        $completedBookings = $user->providerBookings()->where('status', 'completed')->count();
        $unreadMessages = $user->receivedMessages()->where('is_read', false)->count();
        $totalReviews = $user->reviews()->count();
        $averageRating = $user->averageRating();
        
        // Services récents
        $recentServices = $user->services()
            ->with('category')
            ->latest()
            ->take(5)
            ->get();
        
        // Réservations récentes
        $recentBookings = $user->providerBookings()
            ->with(['service', 'client', 'payment'])
            ->latest()
            ->take(5)
            ->get();
        
        // Messages récents
        $recentMessages = $user->receivedMessages()
            ->with('sender')
            ->latest()
            ->take(5)
            ->get();
        
        // Revenus totaux
        $totalRevenue = $user->providerBookings()
            ->where('status', 'completed')
            ->with('payment')
            ->get()
            ->sum(function($booking) {
                return $booking->payment?->amount ?? 0;
            });
        
        return view('provider.dashboard', compact(
            'totalServices',
            'activeServices',
            'totalBookings',
            'pendingBookings', 
            'completedBookings',
            'unreadMessages',
            'totalReviews',
            'averageRating',
            'recentServices',
            'recentBookings',
            'recentMessages',
            'totalRevenue'
        ));
    }
}
