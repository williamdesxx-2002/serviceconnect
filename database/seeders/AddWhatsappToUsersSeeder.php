<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddWhatsappToUsersSeeder extends Seeder
{
    public function run()
    {
        // Mettre à jour les utilisateurs existants avec des numéros WhatsApp
        $users = User::all();
        
        foreach ($users as $user) {
            if (!$user->whatsapp_number) {
                // Générer un numéro WhatsApp basé sur le téléphone
                $whatsappNumber = null;
                
                if ($user->phone) {
                    // Nettoyer le numéro de téléphone et le formater pour WhatsApp
                    $cleanPhone = preg_replace('/[^0-9]/', '', $user->phone);
                    if (strlen($cleanPhone) >= 8) {
                        $whatsappNumber = '+241' . substr($cleanPhone, -8);
                    }
                }
                
                // Si pas de téléphone, générer un numéro fictif
                if (!$whatsappNumber) {
                    $userId = $user->id;
                    $whatsappNumber = '+2410' . str_pad($userId, 8, '0', STR_PAD_LEFT);
                }
                
                $user->update([
                    'whatsapp_number' => $whatsappNumber,
                    'whatsapp_notifications' => true,
                ]);
                
                $this->command->info("Added WhatsApp number {$whatsappNumber} for user {$user->name}");
            }
        }
    }
}
