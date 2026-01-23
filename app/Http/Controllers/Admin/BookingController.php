<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['service.user', 'client', 'payment'])
            ->latest()
            ->paginate(20);
            
        return view('admin.bookings.index', compact('bookings'));
    }
    
    public function show(Booking $booking)
    {
        $booking->load(['service.user', 'client', 'payment', 'review']);
        
        return view('admin.bookings.show', compact('booking'));
    }
    
    public function byProvider(User $provider)
    {
        $bookings = Booking::whereHas('service', function($query) use ($provider) {
            $query->where('user_id', $provider->id);
        })
        ->with(['service', 'client', 'payment'])
        ->latest()
        ->paginate(20);
        
        return view('admin.bookings.provider', compact('bookings', 'provider'));
    }
    
    public function report(Request $request, Booking $booking)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'description' => 'nullable|string|max:1000',
        ]);
        
        // Signaler la réservation comme problématique
        $booking->update([
            'is_reported' => true,
            'report_reason' => $request->reason,
            'report_description' => $request->description,
            'reported_at' => now(),
            'reported_by' => auth()->id(),
        ]);
        
        return back()->with('success', 'Réservation signalée avec succès');
    }
}
