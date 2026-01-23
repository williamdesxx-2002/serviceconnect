<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $providers = User::where('role', 'provider')->get();
        $categories = Category::all();

        $services = [
            [
                'title' => 'Réparation de fuite d\'eau',
                'description' => 'Intervention rapide pour toute fuite d\'eau, robinetterie, tuyauterie. Devis gratuit.',
                'price' => 15000,
                'category' => 'Plomberie',
                'tags' => ['urgence', 'fuite', 'robinetterie'],
            ],
            [
                'title' => 'Installation électrique complète',
                'description' => 'Installation électrique neuve ou rénovation. Mise aux normes garantie.',
                'price' => 50000,
                'category' => 'Électricité',
                'tags' => ['installation', 'normes', 'rénovation'],
            ],
            [
                'title' => 'Ménage complet maison',
                'description' => 'Nettoyage complet de votre maison: sols, vitres, sanitaires. Produits écologiques.',
                'price' => 20000,
                'price_type' => 'daily',
                'category' => 'Ménage',
                'tags' => ['écologique', 'nettoyage', 'maison'],
            ],
            [
                'title' => 'Entretien de jardin',
                'description' => 'Tonte, taille de haies, arrosage, plantations. Forfait mensuel disponible.',
                'price' => 25000,
                'category' => 'Jardinage',
                'tags' => ['tonte', 'haies', 'entretien'],
            ],
            [
                'title' => 'Peinture intérieure',
                'description' => 'Peinture de vos pièces avec préparation des murs. Large choix de couleurs.',
                'price' => 35000,
                'category' => 'Peinture',
                'tags' => ['intérieur', 'décoration', 'qualité'],
            ],
        ];

        foreach ($services as $index => $serviceData) {
            $provider = $providers[$index % $providers->count()];
            $category = $categories->where('name', $serviceData['category'])->first();

            Service::create([
                'user_id' => $provider->id,
                'category_id' => $category->id,
                'title' => $serviceData['title'],
                'description' => $serviceData['description'],
                'price' => $serviceData['price'],
                'price_type' => $serviceData['price_type'] ?? 'fixed',
                'duration' => rand(60, 240),
                'tags' => $serviceData['tags'],
                'latitude' => $provider->latitude,
                'longitude' => $provider->longitude,
                'address' => $provider->address,
                'status' => 'approved',
                'is_active' => true,
                'rating' => rand(40, 50) / 10,
                'reviews_count' => rand(5, 50),
            ]);
        }
    }
}
