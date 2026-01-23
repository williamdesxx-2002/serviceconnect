<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Admin ServiceConnect',
            'email' => 'admin@serviceconnect.ga',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+241 01 02 03 04',
            'whatsapp_number' => '+24101020304',
            'is_verified' => true,
            'is_active' => true,
        ]);

        // Prestataires avec localisation à Libreville
        $providers = [
            [
                'name' => 'Jean-Paul Ngoma',
                'email' => 'jeanpaul@example.com',
                'bio' => 'Plombier professionnel avec 15 ans d\'expérience',
                'latitude' => 0.4162,
                'longitude' => 9.4673,
                'address' => 'Quartier Batterie IV, Libreville',
            ],
            [
                'name' => 'Marie Obame',
                'email' => 'marie@example.com',
                'bio' => 'Électricienne certifiée, interventions rapides',
                'latitude' => 0.3898,
                'longitude' => 9.4542,
                'address' => 'Glass, Libreville',
            ],
            [
                'name' => 'Pierre Moundounga',
                'email' => 'pierre@example.com',
                'bio' => 'Service de ménage professionnel et écologique',
                'latitude' => 0.4330,
                'longitude' => 9.4721,
                'address' => 'Nombakélé, Libreville',
            ],
            [
                'name' => 'Sylvie Ekomi',
                'email' => 'sylvie@example.com',
                'bio' => 'Jardinière paysagiste passionnée',
                'latitude' => 0.4012,
                'longitude' => 9.4589,
                'address' => 'Lalala, Libreville',
            ],
            [
                'name' => 'André Moussavou',
                'email' => 'andre@example.com',
                'bio' => 'Peintre décorateur depuis 10 ans',
                'latitude' => 0.3756,
                'longitude' => 9.4498,
                'address' => 'Akanda, Libreville',
            ],
        ];

        foreach ($providers as $index => $provider) {
            User::create([
                'name' => $provider['name'],
                'email' => $provider['email'],
                'password' => Hash::make('password'),
                'role' => 'provider',
                'phone' => '+241 0' . (7 + $index) . ' 00 00 0' . $index,
                'whatsapp_number' => '+2410' . (7 + $index) . '00000' . $index,
                'bio' => $provider['bio'],
                'latitude' => $provider['latitude'],
                'longitude' => $provider['longitude'],
                'address' => $provider['address'],
                'city' => 'Libreville',
                'is_verified' => true,
                'is_active' => true,
            ]);
        }

        // Clients
        User::factory(20)->create([
            'role' => 'client',
            'city' => 'Libreville',
        ]);
    }
}
