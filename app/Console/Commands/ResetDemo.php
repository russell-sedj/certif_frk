<?php

namespace App\Console\Commands;

use Database\Seeders\DemoSeeder;
use Illuminate\Console\Command;

class ResetDemo extends Command
{
    /**
     * @var string
     */
    protected $signature = 'demo:reset {--force : Ne pas demander de confirmation}';

    /**
     * @var string
     */
    protected $description = 'Réinitialise les données de démonstration (migrations + jeu de données fictif)';

    public function handle(): int
    {
        if (! config('demo.enabled')) {
            $this->error('DEMO_MODE n\'est pas activé : aucune donnée n\'a été modifiée.');

            return self::FAILURE;
        }

        if (! $this->option('force') && ! $this->confirm('Réinitialiser toutes les données de démonstration ?')) {
            return self::FAILURE;
        }

        $this->call('migrate:fresh', [
            '--force' => true,
            '--seed' => true,
            '--seeder' => DemoSeeder::class,
        ]);

        $this->call('config:clear');
        $this->call('view:clear');

        $this->info('Démo réinitialisée.');

        return self::SUCCESS;
    }
}
