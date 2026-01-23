<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class DevServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Démarrer le serveur de développement ServiceConnect';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info('  ServiceConnect - Serveur de Dev');
        $this->info('========================================');
        $this->line('');

        // Vérifier PHP
        $this->info('[1/4] Vérification de PHP...');
        $phpVersion = phpversion();
        if ($phpVersion) {
            $this->info("✅ PHP trouvé: {$phpVersion}");
        } else {
            $this->error('❌ PHP n\'est pas installé');
            return 1;
        }

        // Vérifier Composer
        $this->line('');
        $this->info('[2/4] Vérification de Composer...');
        try {
            $composerProcess = new Process(['composer', '--version']);
            $composerProcess->run();
            if ($composerProcess->isSuccessful()) {
                $this->info('✅ Composer trouvé: ' . trim($composerProcess->getOutput()));
            } else {
                $this->error('❌ Composer n\'est pas installé');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la vérification de Composer: ' . $e->getMessage());
            return 1;
        }

        // Vérifier le projet Laravel
        $this->line('');
        $this->info('[3/4] Vérification du projet Laravel...');
        if (file_exists(base_path('artisan'))) {
            $this->info('✅ Projet Laravel trouvé');
        } else {
            $this->error('❌ Fichier artisan non trouvé');
            $this->error('Veuillez vous assurer d\'être dans le répertoire du projet Laravel');
            return 1;
        }

        // Démarrer le serveur
        $this->line('');
        $this->info('[4/4] Démarrage du serveur de développement...');
        $this->info('Serveur: http://127.0.0.1:8000');
        $this->info('Pour arrêter: Ctrl+C');
        $this->info('========================================');
        $this->line('');

        // Démarrer le serveur Laravel
        $this->call('serve', [
            '--host' => '127.0.0.1',
            '--port' => '8000'
        ]);

        return 0;
    }
}
