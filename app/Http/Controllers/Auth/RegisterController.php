<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Valider les données
        $this->validator($request->all())->validate();

        // Créer l'utilisateur
        event(new Registered($user = $this->create($request->all())));

        // Envoyer l'email de bienvenue
        try {
            config(['mail.default' => 'log']);
            $user->notify(new WelcomeNotification());
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        // Connecter l'utilisateur automatiquement
        $this->guard()->login($user);

        // Vérifier que l'utilisateur est bien connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Erreur lors de la connexion. Veuillez vous connecter manuellement.');
        }

        // Redirection selon le rôle avec messages appropriés
        if ($user->role === 'provider') {
            return redirect()->route('services.my')
                ->with('success', 'Bienvenue prestataire ! Votre compte a été créé avec succès. Commencez par créer vos services.');
        } else {
            return redirect()->route('services.index')
                ->with('success', 'Bienvenue client ! Votre compte a été créé avec succès. Découvrez nos services.');
        }
    }

    /**
     * Where to redirect users after registration.
     * Note: La redirection est gérée dans la méthode register() selon le rôle
     *
     * @var string
     */
    // protected $redirectTo = '/home'; // Désactivé pour éviter les conflits

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[\+]?[0-9\s\-\(\)]+$/'],
            'whatsapp_number' => ['nullable', 'string', 'max:20', 'regex:/^[\+]?[0-9\s\-\(\)]+$/'],
            'whatsapp_notifications' => ['sometimes', 'accepted'],
            'role' => ['required', 'in:client,provider'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'name.required' => 'Le nom complet est obligatoire.',
            'name.min' => 'Le nom doit contenir au moins 2 caractères.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'phone.regex' => 'Veuillez entrer un numéro de téléphone valide.',
            'whatsapp_number.regex' => 'Veuillez entrer un numéro WhatsApp valide.',
            'role.required' => 'Veuillez choisir votre rôle.',
            'role.in' => 'Le rôle sélectionné n\'est pas valide. Choisissez entre Client et Prestataire.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'whatsapp_number' => $data['whatsapp_number'] ?? $data['phone'], // Utiliser le téléphone par défaut
            'whatsapp_notifications' => isset($data['whatsapp_notifications']) ? true : false,
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'is_active' => true, // Activer par défaut
            'is_verified' => false, // Nécessite vérification email
            'email_verified_at' => null, // Sera vérifié plus tard
        ]);
    }
}
