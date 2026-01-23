<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer des utilisateurs de test avec différents rôles
        
        // Client 1
        User::create([
            'name' => 'Jean Client',
            'email' => 'jean.client@example.com',
            'phone' => '+24107000001',
            'whatsapp_number' => '+24107000001',
            'whatsapp_notifications' => true,
            'role' => 'client',
            'password' => Hash::make('Password123!'),
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Client 2
        User::create([
            'name' => 'Marie Client',
            'email' => 'marie.client@example.com',
            'phone' => '+24107000002',
            'whatsapp_number' => '+24107000002',
            'whatsapp_notifications' => true,
            'role' => 'client',
            'password' => Hash::make('Password123!'),
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Prestataire 1
        User::create([
            'name' => 'Pierre Prestataire',
            'email' => 'pierre.provider@example.com',
            'phone' => '+24107000003',
            'whatsapp_number' => '+24107000003',
            'whatsapp_notifications' => true,
            'role' => 'provider',
            'password' => Hash::make('Password123!'),
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Prestataire 2
        User::create([
            'name' => 'Sophie Prestataire',
            'email' => 'sophie.provider@example.com',
            'phone' => '+24107000004',
            'whatsapp_number' => '+24107000004',
            'whatsapp_notifications' => false,
            'role' => 'provider',
            'password' => Hash::make('Password123!'),
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Client 3 (non vérifié)
        User::create([
            'name' => 'Paul Client',
            'email' => 'paul.client@example.com',
            'phone' => '+24107000005',
            'whatsapp_number' => '+24107000005',
            'whatsapp_notifications' => true,
            'role' => 'client',
            'password' => Hash::make('Password123!'),
            'is_active' => true,
            'is_verified' => false, // Non vérifié
            'email_verified_at' => null,
        ]);

        // Prestataire 3 (inactif)
        User::create([
            'name' => 'Claire Prestataire',
            'email' => 'claire.provider@example.com',
            'phone' => '+24107000006',
            'whatsapp_number' => '+24107000006',
            'whatsapp_notifications' => true,
            'role' => 'provider',
            'password' => Hash::make('Password123!'),
            'is_active' => false, // Inactif
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Utilisateurs de test créés avec succès !');
        $this->command->info('Clients créés : 3');
        $this->command->info('Prestataires créés : 3');
        $this->command->info('Total utilisateurs : 6');
        
        $this->command->info('');
        $this->command->info('Identifiants de connexion :');
        $this->command->info('----------------------------------------');
        $this->command->info('Clients :');
        $this->command->info('  - jean.client@example.com / Password123!');
        $this->command->info('  - marie.client@example.com / Password123!');
        $this->command->info('  - paul.client@example.com / Password123! (non vérifié)');
        $this->command->info('');
        $this->command->info('Prestataires :');
        $this->command->info('  - pierre.provider@example.com / Password123!');
        $this->command->info('  - sophie.provider@example.com / Password123!');
        $this->command->info('  - claire.provider@example.com / Password123! (inactif)');
        $this->command->info('----------------------------------------');
    }
}
