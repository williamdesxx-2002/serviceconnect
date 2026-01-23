<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Message;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        // Statistiques du client
        $totalBookings = $user->clientBookings()->count();
        $pendingBookings = $user->clientBookings()->where('status', 'pending')->count();
        $completedBookings = $user->clientBookings()->where('status', 'completed')->count();
        $unreadMessages = $user->receivedMessages()->where('is_read', false)->count();
        
        // Réservations récentes
        $recentBookings = $user->clientBookings()
            ->with(['service.user', 'payment'])
            ->latest()
            ->take(5)
            ->get();
        
        // Messages récents
        $recentMessages = $user->receivedMessages()
            ->with('sender')
            ->latest()
            ->take(5)
            ->get();
        
        return view('client.dashboard', compact(
            'totalBookings',
            'pendingBookings', 
            'completedBookings',
            'unreadMessages',
            'recentBookings',
            'recentMessages'
        ));
    }
}
