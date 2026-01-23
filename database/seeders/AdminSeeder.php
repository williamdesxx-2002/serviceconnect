<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer un administrateur par défaut
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@serviceconnect.com',
            'phone' => '+24107000000',
            'whatsapp_number' => '+24107000000',
            'whatsapp_notifications' => true,
            'role' => 'admin',
            'password' => Hash::make('Admin123!'), // Mot de passe sécurisé
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Administrateur par défaut créé avec succès.');
        $this->command->info('Email: admin@serviceconnect.com');
        $this->command->info('Mot de passe: Admin123!');
    }
}
