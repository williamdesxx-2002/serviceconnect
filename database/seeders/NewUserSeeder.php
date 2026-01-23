<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NewUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer de nouveaux utilisateurs de test avec fonctionnalités complètes
        
        // Client Actif 1 - Pour tester les réservations
        User::create([
            'name' => 'Alice Nouveau',
            'email' => 'alice.client@example.com',
            'phone' => '+24107777777',
            'whatsapp_number' => '+24107777777',
            'whatsapp_notifications' => true,
            'role' => 'client',
            'password' => Hash::make('Password123!'),
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
            'bio' => 'Cliente régulière cherchant des services de qualité',
            'address' => 'Libreville, Gabon',
            'city' => 'Libreville',
            'country' => 'Gabon',
        ]);

        // Client Actif 2 - Pour tester la recherche
        User::create([
            'name' => 'Bob Chercheur',
            'email' => 'bob.client@example.com',
            'phone' => '+24107777778',
            'whatsapp_number' => '+24107777778',
            'whatsapp_notifications' => false,
            'role' => 'client',
            'password' => Hash::make('Password123!'),
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
            'bio' => 'Je recherche des prestataires fiables pour mes projets',
            'address' => 'Port-Gentil, Gabon',
            'city' => 'Port-Gentil',
            'country' => 'Gabon',
        ]);

        // Prestataire Actif 1 - Avec services complets
        User::create([
            'name' => 'Charles Prestataire',
            'email' => 'charles.provider@example.com',
            'phone' => '+24107777779',
            'whatsapp_number' => '+24107777779',
            'whatsapp_notifications' => true,
            'role' => 'provider',
            'password' => Hash::make('Password123!'),
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
            'bio' => 'Prestataire professionnel avec 10 ans d\'expérience',
            'address' => 'Libreville, Gabon',
            'city' => 'Libreville',
            'country' => 'Gabon',
            'is_verified' => true, // Vérifié par admin
        ]);

        // Prestataire Actif 2 - Spécialisé
        User::create([
            'name' => 'Diana Spécialiste',
            'email' => 'diana.provider@example.com',
            'phone' => '+24107777780',
            'whatsapp_number' => '+24107777780',
            'whatsapp_notifications' => true,
            'role' => 'provider',
            'password' => Hash::make('Password123!'),
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
            'bio' => 'Spécialiste en services informatiques et web',
            'address' => 'Libreville, Gabon',
            'city' => 'Libreville',
            'country' => 'Gabon',
            'is_verified' => true,
        ]);

        // Créer des services pour les nouveaux prestataires
        $charles = User::where('email', 'charles.provider@example.com')->first();
        $diana = User::where('email', 'diana.provider@example.com')->first();
        
        // Récupérer des catégories existantes
        $informatique = Category::where('name', 'Informatique')->first();
        $menage = Category::where('name', 'Ménage')->first();
        $plomberie = Category::where('name', 'Plomberie')->first();
        $cours = Category::where('name', 'Cours particuliers')->first();

        // Services pour Charles
        Service::create([
            'user_id' => $charles->id,
            'category_id' => $plomberie->id,
            'title' => 'Réparation plomberie',
            'description' => 'Service professionnel de réparation plomberie. Fuites, débouchage, installation.',
            'price' => 80.00,
            'duration' => 90,
            'status' => 'approved',
            'is_active' => true,
        ]);

        Service::create([
            'user_id' => $charles->id,
            'category_id' => $menage->id,
            'title' => 'Nettoyage professionnel',
            'description' => 'Service de nettoyage complet pour maisons et appartements. Produits écologiques.',
            'price' => 55.00,
            'duration' => 120,
            'status' => 'approved',
            'is_active' => true,
        ]);

        // Services pour Diana
        Service::create([
            'user_id' => $diana->id,
            'category_id' => $informatique->id,
            'title' => 'Développement web',
            'description' => 'Création de sites web professionnels et e-commerce. Technologies modernes et responsive design.',
            'price' => 500.00,
            'duration' => 480,
            'status' => 'approved',
            'is_active' => true,
        ]);

        Service::create([
            'user_id' => $diana->id,
            'category_id' => $informatique->id,
            'title' => 'Support informatique',
            'description' => 'Dépannage et maintenance informatique à domicile ou en entreprise. Intervention rapide.',
            'price' => 75.00,
            'duration' => 60,
            'status' => 'approved',
            'is_active' => true,
        ]);

        Service::create([
            'user_id' => $diana->id,
            'category_id' => $cours->id,
            'title' => 'Formation informatique',
            'description' => 'Cours particuliers d\'informatique pour tous niveaux. Bureautique, programmation, web.',
            'price' => 35.00,
            'duration' => 60,
            'status' => 'pending', // En attente de validation
            'is_active' => false,
        ]);

        $this->command->info('Nouveaux utilisateurs de test créés avec succès !');
        $this->command->info('Nouveaux clients : 2');
        $this->command->info('Nouveaux prestataires : 2');
        $this->command->info('Nouveaux services : 5');
        
        $this->command->info('');
        $this->command->info('Nouveaux comptes créés :');
        $this->command->info('----------------------------------------');
        $this->command->info('Clients :');
        $this->command->info('  - alice.client@example.com / Password123!');
        $this->command->info('  - bob.client@example.com / Password123!');
        $this->command->info('');
        $this->command->info('Prestataires :');
        $this->command->info('  - charles.provider@example.com / Password123!');
        $this->command->info('  - diana.provider@example.com / Password123!');
        $this->command->info('');
        $this->command->info('Services créés :');
        $this->command->info('  - Réparation plomberie (80€)');
        $this->command->info('  - Nettoyage professionnel (55€)');
        $this->command->info('  - Développement web (500€)');
        $this->command->info('  - Support informatique (75€)');
        $this->command->info('  - Formation informatique (35€) - en attente');
        $this->command->info('----------------------------------------');
        
        $this->command->info('');
        $this->command->info('Fonctionnalités testables :');
        $this->command->info('- Inscription et connexion');
        $this->command->info('- Recherche de services');
        $this->command->info('- Réservation de services');
        $this->command->info('- Gestion des services (prestataires)');
        $this->command->info('- Validation admin');
        $this->command->info('- Notifications WhatsApp');
        $this->command->info('- Profils complets avec bio et adresse');
    }
}
