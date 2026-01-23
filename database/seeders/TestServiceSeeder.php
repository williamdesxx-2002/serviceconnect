<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Récupérer les prestataires et catégories
        $pierre = \App\Models\User::where('email', 'pierre.provider@example.com')->first();
        $sophie = \App\Models\User::where('email', 'sophie.provider@example.com')->first();
        
        $plomberie = Category::where('name', 'Plomberie')->first();
        $electricite = Category::where('name', 'Électricité')->first();
        $menage = Category::where('name', 'Ménage')->first();
        $jardinage = Category::where('name', 'Jardinage')->first();

        // Services pour Pierre Prestataire
        Service::create([
            'user_id' => $pierre->id,
            'category_id' => $plomberie->id,
            'title' => 'Débouchage canalisation',
            'description' => 'Service professionnel de débouchage de canalisation pour cuisine et salle de bain. Intervention rapide et efficace.',
            'price' => 50.00,
            'duration' => 60,
            'status' => 'approved',
            'is_active' => true,
        ]);

        Service::create([
            'user_id' => $pierre->id,
            'category_id' => $electricite->id,
            'title' => 'Installation électrique',
            'description' => 'Installation complète de système électrique pour neuf et rénovation. Certifié et assuré.',
            'price' => 150.00,
            'duration' => 180,
            'status' => 'approved',
            'is_active' => true,
        ]);

        // Services pour Sophie Prestataire
        Service::create([
            'user_id' => $sophie->id,
            'category_id' => $menage->id,
            'title' => 'Nettoyage complet',
            'description' => 'Nettoyage en profondeur de votre maison ou appartement. Produits écologiques.',
            'price' => 80.00,
            'duration' => 120,
            'status' => 'approved',
            'is_active' => true,
        ]);

        Service::create([
            'user_id' => $sophie->id,
            'category_id' => $jardinage->id,
            'title' => 'Entretien jardin',
            'description' => 'Tonte de pelouse, taille de haies et entretien général du jardin.',
            'price' => 60.00,
            'duration' => 90,
            'status' => 'approved',
            'is_active' => true,
        ]);

        Service::create([
            'user_id' => $sophie->id,
            'category_id' => $menage->id,
            'title' => 'Grand ménage',
            'description' => 'Grand ménage de printemps ou avant déménagement. Nettoyage complet.',
            'price' => 200.00,
            'duration' => 240,
            'status' => 'pending', // En attente de validation
            'is_active' => false,
        ]);

        $this->command->info('Services de test créés avec succès !');
        $this->command->info('Services créés : 4');
        $this->command->info('Services actifs : 3');
        $this->command->info('Services en attente : 1');
        
        $this->command->info('');
        $this->command->info('Services créés :');
        $this->command->info('----------------------------------------');
        $this->command->info('Pierre Prestataire :');
        $this->command->info('  - Débouchage canalisation (50€)');
        $this->command->info('  - Installation électrique (150€)');
        $this->command->info('');
        $this->command->info('Sophie Prestataire :');
        $this->command->info('  - Nettoyage complet (80€)');
        $this->command->info('  - Entretien jardin (60€)');
        $this->command->info('  - Grand ménage (200€) - en attente');
        $this->command->info('----------------------------------------');
    }
}
