<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isClient()) {
            $bookings = $user->clientBookings()
                ->with(['service.user', 'payment', 'review'])
                ->latest()
                ->paginate(10);
        } elseif ($user->isProvider()) {
            $bookings = $user->providerBookings()
                ->with(['service', 'client', 'payment', 'review'])
                ->latest()
                ->paginate(10);
        } else {
            // Pour les administrateurs, afficher toutes les réservations
            $bookings = Booking::with(['service.user', 'client', 'payment', 'review'])
                ->latest()
                ->paginate(10);
        }

        return view('bookings.index', compact('bookings'));
    }

    public function create(Service $service)
    {
        if ($service->user_id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas réserver votre propre service.');
        }

        return view('bookings.create', compact('service'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        
        $validated['client_id'] = auth()->id();
        $validated['provider_id'] = $service->user_id;
        $validated['total_amount'] = $service->price;
        $validated['status'] = 'pending';

        $booking = Booking::create($validated);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Réservation créée avec succès.');
    }

    public function show(Booking $booking)
    {
        $user = auth()->user();
        
        // Vérifier si l'utilisateur a le droit de voir cette réservation
        // Le client ou le prestataire concerné peut voir la réservation
        if ($user->id !== $booking->client_id && $user->id !== $booking->provider_id && !$user->isAdmin()) {
            abort(403, 'Cette action n\'est pas autorisée.');
        }
        
        $booking->load(['service', 'client', 'provider', 'review', 'payment', 'messages']);

        return view('bookings.show', compact('booking'));
    }

    public function confirm(Booking $booking)
    {
        $user = auth()->user();
        
        // Seul le prestataire concerné peut confirmer la réservation
        if ($user->id !== $booking->provider_id && !$user->isAdmin()) {
            abort(403, 'Cette action n\'est pas autorisée.');
        }
        
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Cette réservation ne peut plus être confirmée.');
        }

        $booking->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        // Notification au client
        // $booking->client->notify(new BookingConfirmed($booking));

        return back()->with('success', 'Réservation confirmée avec succès.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        $user = auth()->user();
        
        // Le client concerné ou l'admin peut annuler la réservation
        if ($user->id !== $booking->client_id && !$user->isAdmin()) {
            abort(403, 'Cette action n\'est pas autorisée.');
        }
        
        if ($booking->status === 'completed') {
            return back()->with('error', 'Une réservation terminée ne peut être annulée.');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string',
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason'],
            'cancelled_at' => now(),
        ]);

        // Notification à l'autre partie
        // $otherParty = $booking->client_id === auth()->id() ? $booking->provider : $booking->client;
        // $otherParty->notify(new BookingCancelled($booking));

        return back()->with('success', 'Réservation annulée avec succès.');
    }

    public function complete(Booking $booking)
    {
        $user = auth()->user();
        
        // Le prestataire concerné ou l'admin peut terminer la réservation
        if ($user->id !== $booking->provider_id && !$user->isAdmin()) {
            abort(403, 'Cette action n\'est pas autorisée.');
        }
        
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Seules les réservations confirmées peuvent être terminées.');
        }

        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Réservation terminée avec succès.');
    }
}
