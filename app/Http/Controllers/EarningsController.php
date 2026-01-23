<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Payment;

class EarningsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Statistiques générales
        $totalEarnings = $user->totalEarnings();
        $completedBookings = $user->bookings()
            ->where('status', 'completed')
            ->count();
        
        $averageEarningPerBooking = $completedBookings > 0 ? $totalEarnings / $completedBookings : 0;
        
        // Revenus mensuels (6 derniers mois)
        $monthlyEarnings = $user->payments()
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Réservations récentes avec paiements
        $recentEarnings = $user->payments()
            ->with('booking.service')
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Paiements en attente
        $pendingPayments = $user->payments()
            ->with('booking.service')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('earnings.index', compact(
            'totalEarnings',
            'completedBookings',
            'averageEarningPerBooking',
            'monthlyEarnings',
            'recentEarnings',
            'pendingPayments'
        ));
    }
    
    public function show($paymentId)
    {
        $payment = Payment::with(['booking.service', 'booking.client'])
            ->where('id', $paymentId)
            ->whereHas('booking', function($query) {
                $query->where('provider_id', Auth::id());
            })
            ->firstOrFail();
            
        return view('earnings.show', compact('payment'));
    }
}
