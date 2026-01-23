<?php

use Illuminate\Support\Facades\Route;
use App\Mail\WelcomeEmail;
use App\Models\User;

Route::get('/test-email', function() {
    // Créer un utilisateur de test
    $user = User::first();
    
    if ($user) {
        try {
            // Envoyer l'email de bienvenue
            Mail::to($user->email)->send(new WelcomeEmail($user));
            
            return "Email de bienvenue envoyé avec succès à " . $user->email;
        } catch (\Exception $e) {
            return "Erreur lors de l'envoi de l'email: " . $e->getMessage();
        }
    }
    
    return "Aucun utilisateur trouvé";
});
