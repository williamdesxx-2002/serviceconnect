<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

// Créer l'administrateur
$user = new App\Models\User();
$user->name = 'William Des';
$user->email = 'williamdesxx@gmail.com';
$user->password = Hash::make('Z0r02002');
$user->role = 'admin';
$user->is_active = true;
$user->is_verified = true;
$user->phone = '+241123456789';
$user->save();

echo "✅ Administrateur créé avec succès !\n";
echo "ID: {$user->id}\n";
echo "Email: {$user->email}\n";
echo "Rôle: {$user->role}\n";
echo "Statut: " . ($user->is_active ? 'Actif' : 'Inactif') . "\n";
echo "Vérifié: " . ($user->is_verified ? 'Oui' : 'Non') . "\n";
