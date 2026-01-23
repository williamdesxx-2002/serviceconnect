<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Plomberie', 'icon' => '🔧', 'description' => 'Services de plomberie et réparations'],
            ['name' => 'Électricité', 'icon' => '⚡', 'description' => 'Installations et dépannages électriques'],
            ['name' => 'Ménage', 'icon' => '🧹', 'description' => 'Nettoyage et entretien de maison'],
            ['name' => 'Jardinage', 'icon' => '🌱', 'description' => 'Entretien de jardins et espaces verts'],
            ['name' => 'Peinture', 'icon' => '🎨', 'description' => 'Travaux de peinture intérieure et extérieure'],
            ['name' => 'Déménagement', 'icon' => '📦', 'description' => 'Services de déménagement et transport'],
            ['name' => 'Informatique', 'icon' => '💻', 'description' => 'Dépannage et installation informatique'],
            ['name' => 'Menuiserie', 'icon' => '🪚', 'description' => 'Travaux de menuiserie et ébénisterie'],
            ['name' => 'Mécanique', 'icon' => '🔩', 'description' => 'Réparation et entretien de véhicules'],
            ['name' => 'Cours particuliers', 'icon' => '📚', 'description' => 'Soutien scolaire et formation'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'icon' => $category['icon'],
            ]);
        }
    }
}
