<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirection vers le fournisseur OAuth
     */
    public function redirectToProvider($provider)
    {
        // Valider le fournisseur
        if (!in_array($provider, ['google', 'facebook'])) {
            return redirect()->route('login')
                ->with('error', 'Fournisseur non supporté.');
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Erreur de connexion avec ' . ucfirst($provider) . '. Veuillez réessayer.');
        }
    }

    /**
     * Callback du fournisseur OAuth
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Vérifier si l'utilisateur existe déjà
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // L'utilisateur existe, le connecter
                if (!$user->provider || $user->provider_id !== $socialUser->getId()) {
                    return redirect()->route('login')
                        ->with('error', 'Cet email est déjà utilisé avec une autre méthode de connexion.');
                }

                // Mettre à jour les informations sociales
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                    'last_login_at' => now(),
                ]);

                Auth::login($user);
                return $this->redirectUser($user);

            } else {
                // Créer un nouvel utilisateur avec des valeurs par défaut réalistes
                $newUser = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'phone' => '+24100000000', // À compléter par l'utilisateur
                    'whatsapp_number' => '+24100000000', // À compléter par l'utilisateur
                    'whatsapp_notifications' => true,
                    'role' => 'client', // Rôle par défaut
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                    'email_verified_at' => now(), // Email vérifié via OAuth
                    'password' => Hash::make(Str::random(32)), // Mot de passe aléatoire
                    'is_active' => true,
                    'is_verified' => true, // Vérifié via OAuth
                ]);

                Auth::login($newUser);
                return $this->redirectUser($newUser);
            }

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Erreur lors de la connexion avec ' . ucfirst($provider) . ': ' . $e->getMessage());
        }
    }

    /**
     * Rediriger l'utilisateur selon son rôle
     */
    private function redirectUser($user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Bienvenue administrateur ! Connexion via ' . ucfirst($user->provider) . ' réussie.');
        } elseif ($user->role === 'provider') {
            return redirect()->route('services.my')
                ->with('success', 'Bienvenue prestataire ! Connexion via ' . ucfirst($user->provider) . ' réussie. Complétez votre profil.');
        } else {
            return redirect()->route('services.index')
                ->with('success', 'Bienvenue client ! Connexion via ' . ucfirst($user->provider) . ' réussie.');
        }
    }
}
