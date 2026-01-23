<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Booking $booking)
    {
        // Vérifier que l'utilisateur est le client de la réservation
        if ($booking->client_id !== auth()->id()) {
            abort(403, 'Non autorisé');
        }

        // Vérifier que la réservation est confirmée
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'La réservation doit être confirmée avant le paiement.');
        }

        // Vérifier qu'il n'y a pas déjà un paiement complété
        if ($booking->payment && $booking->payment->status === 'completed') {
            return back()->with('error', 'Cette réservation a déjà été payée.');
        }

        return view('payments.pay', compact('booking'));
    }

    public function processPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'method' => 'required|in:mobile_money,credit_card,bank_transfer',
            'phone_number' => 'required_if:method,mobile_money',
            'card_number' => 'required_if:method,credit_card',
            'expiry_date' => 'required_if:method,credit_card',
            'cvv' => 'required_if:method,credit_card',
        ]);

        // Créer ou mettre à jour le paiement
        $payment = Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'amount' => $booking->service->price,
                'method' => $request->method,
                'status' => 'completed', // Simuler un paiement réussi
                'transaction_id' => 'TXN_' . strtoupper(uniqid()),
                'notes' => 'Paiement traité le ' . now()->format('d/m/Y H:i'),
            ]
        );

        // Mettre à jour le statut de la réservation
        $booking->update(['status' => 'paid']);

        return redirect()->route('payments.success', $booking);
    }

    public function success(Booking $booking)
    {
        return view('payments.success', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        return view('payments.cancel', compact('booking'));
    }
}
