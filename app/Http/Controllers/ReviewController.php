<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'service_id' => 'required|exists:services,id',
            'provider_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
            'quality_rating' => 'nullable|integer|min:1|max:5',
            'communication_rating' => 'nullable|integer|min:1|max:5',
            'punctuality_rating' => 'nullable|integer|min:1|max:5',
            'tags' => 'nullable|string',
        ]);

        // Vérifier que la réservation appartient à l'utilisateur
        $booking = Booking::findOrFail($request->booking_id);
        if ($booking->client_id !== auth()->id()) {
            abort(403, 'Non autorisé');
        }

        // Vérifier que la réservation est complétée
        if ($booking->status !== 'completed') {
            return back()->with('error', 'Vous pouvez seulement noter les services complétés.');
        }

        // Vérifier qu'il n'y a pas déjà d'avis
        if (Review::where('booking_id', $booking->id)->exists()) {
            return back()->with('error', 'Vous avez déjà donné un avis pour cette réservation.');
        }

        // Créer l'avis
        $review = Review::create([
            'booking_id' => $booking->id,
            'service_id' => $request->service_id,
            'provider_id' => $request->provider_id,
            'client_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'quality_rating' => $request->quality_rating ?? $request->rating,
            'communication_rating' => $request->communication_rating ?? $request->rating,
            'punctuality_rating' => $request->punctuality_rating ?? $request->rating,
            'tags' => $request->tags,
        ]);

        // Mettre à jour la note moyenne du prestataire
        $provider = $review->provider;
        $avgRating = $provider->reviews()->avg('rating');
        $provider->update(['rating' => $avgRating]);

        // Mettre à jour la note moyenne du service
        $service = $review->service;
        $avgServiceRating = $service->reviews()->avg('rating');
        $service->update(['rating' => $avgServiceRating]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Votre avis a été enregistré avec succès !');
    }

    public function myReviews()
    {
        $user = auth()->user();
        
        if ($user->isClient()) {
            // Avis donnés par le client
            $reviews = $user->givenReviews()
                ->with(['service', 'provider'])
                ->latest()
                ->paginate(10);
        } elseif ($user->isProvider()) {
            // Avis reçus par le prestataire
            $reviews = $user->reviews()
                ->with(['client', 'service'])
                ->latest()
                ->paginate(10);
        } else {
            $reviews = collect();
        }

        return view('reviews.my', compact('reviews'));
    }

    public function update(Request $request, Review $review)
    {
        // Vérifier que l'utilisateur est l'auteur de l'avis
        if ($review->client_id !== auth()->id()) {
            abort(403, 'Non autorisé');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Mettre à jour les notes moyennes
        $provider = $review->provider;
        $avgRating = $provider->reviews()->avg('rating');
        $provider->update(['rating' => $avgRating]);

        return back()->with('success', 'Votre avis a été mis à jour.');
    }

    public function destroy(Review $review)
    {
        // Vérifier que l'utilisateur est l'auteur de l'avis
        if ($review->client_id !== auth()->id()) {
            abort(403, 'Non autorisé');
        }

        $bookingId = $review->booking_id;
        $review->delete();

        // Mettre à jour les notes moyennes
        $provider = $review->provider;
        $avgRating = $provider->reviews()->avg('rating') ?: 0;
        $provider->update(['rating' => $avgRating]);

        return redirect()->route('bookings.show', $bookingId)
            ->with('success', 'Votre avis a été supprimé.');
    }
}
