<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ajouter les nouvelles catégories
        $categories = [
            // Catégories professionnelles
            ['name' => 'Conseil', 'slug' => 'conseil', 'description' => 'Consulting et expertise professionnelle', 'icon' => '💡', 'is_active' => true],
            ['name' => 'Comptabilité', 'slug' => 'comptabilite', 'description' => 'Services comptables et fiscaux', 'icon' => '📊', 'is_active' => true],
            ['name' => 'Marketing', 'slug' => 'marketing', 'description' => 'Marketing digital et communication', 'icon' => '📈', 'is_active' => true],
            ['name' => 'Informatique', 'slug' => 'informatique', 'description' => 'Support technique et développement', 'icon' => '💻', 'is_active' => true],
            ['name' => 'Formation', 'slug' => 'formation', 'description' => 'Cours et formations professionnelles', 'icon' => '📚', 'is_active' => true],
            ['name' => 'Sécurité', 'slug' => 'securite', 'description' => 'Sécurité privée et professionnelle', 'icon' => '🔒', 'is_active' => true],
            
            // Services personnels
            ['name' => 'Coiffure / Esthétique', 'slug' => 'coiffure-esthetique', 'description' => 'Soins capillaires et beauté', 'icon' => '💇', 'is_active' => true],
            ['name' => 'Transport', 'slug' => 'transport', 'description' => 'Transport de personnes et marchandises', 'icon' => '🚗', 'is_active' => true],
            ['name' => 'Garde d\'enfants', 'slug' => 'garde-enfants', 'description' => 'Garde et babysitting', 'icon' => '👶', 'is_active' => true],
            ['name' => 'Aide aux personnes âgées', 'slug' => 'aide-personnes-agees', 'description' => 'Accompagnement et aide à domicile', 'icon' => '👴', 'is_active' => true],
            ['name' => 'Ménage', 'slug' => 'menage', 'description' => 'Services de nettoyage et entretien', 'icon' => '🏠', 'is_active' => true],
            
            // Éducation et coaching
            ['name' => 'Cours particuliers', 'slug' => 'cours-particuliers', 'description' => 'Soutien scolaire et cours privés', 'icon' => '🎓', 'is_active' => true],
            ['name' => 'Coaching', 'slug' => 'coaching', 'description' => 'Coaching personnel et professionnel', 'icon' => '🎯', 'is_active' => true],
            
            // Événements et loisirs
            ['name' => 'Organisation d\'événements', 'slug' => 'organisation-evenements', 'description' => 'Planning et coordination d\'événements', 'icon' => '🎉', 'is_active' => true],
            ['name' => 'Tourisme', 'slug' => 'tourisme', 'description' => 'Services touristiques et guides', 'icon' => '✈️', 'is_active' => true],
            ['name' => 'Sport', 'slug' => 'sport', 'description' => 'Entraînement et coaching sportif', 'icon' => '⚽', 'is_active' => true],
            ['name' => 'Activités artistiques', 'slug' => 'activites-artistiques', 'description' => 'Ateliers et cours artistiques', 'icon' => '🎭', 'is_active' => true],
            
            // BTP et maintenance
            ['name' => 'Construction', 'slug' => 'construction', 'description' => 'Construction et gros œuvre', 'icon' => '🏗️', 'is_active' => true],
            ['name' => 'Rénovation', 'slug' => 'renovation', 'description' => 'Rénovation et aménagement', 'icon' => '🔨', 'is_active' => true],
            ['name' => 'Maintenance', 'slug' => 'maintenance', 'description' => 'Maintenance préventive et corrective', 'icon' => '🔧', 'is_active' => true],
            ['name' => 'Réparation', 'slug' => 'reparation', 'description' => 'Réparations diverses', 'icon' => '🛠️', 'is_active' => true],
            
            // Industrie et logistique
            ['name' => 'Énergie', 'slug' => 'energie', 'description' => 'Services énergétiques', 'icon' => '⚡', 'is_active' => true],
            ['name' => 'Ingénierie', 'slug' => 'ingenierie', 'description' => 'Ingénierie et études techniques', 'icon' => '🏭', 'is_active' => true],
            ['name' => 'Logistique', 'slug' => 'logistique', 'description' => 'Transport et logistique', 'icon' => '🚚', 'is_active' => true],
            
            // Catégorie personnalisée
            ['name' => 'Autre', 'slug' => 'autre', 'description' => 'Pour les services non listés', 'icon' => '📝', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'],
                'icon' => $category['icon'],
                'is_active' => $category['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Supprimer les nouvelles catégories ajoutées
        $categoryNames = [
            'Conseil', 'Comptabilité', 'Marketing', 'Informatique', 'Formation', 'Sécurité',
            'Coiffure / Esthétique', 'Transport', 'Garde d\'enfants', 'Aide aux personnes âgées', 'Ménage',
            'Cours particuliers', 'Coaching',
            'Organisation d\'événements', 'Tourisme', 'Sport', 'Activités artistiques',
            'Construction', 'Rénovation', 'Maintenance', 'Réparation',
            'Énergie', 'Ingénierie', 'Logistique', 'Autre'
        ];

        DB::table('categories')->whereIn('name', $categoryNames)->delete();
    }
};
