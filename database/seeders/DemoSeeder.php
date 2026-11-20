<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Demande;
use App\Models\Formation;
use App\Models\Lieu;
use App\Models\Presence;
use App\Models\Sessionpro;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Jeu de données fictif et réaliste pour la démonstration publique.
 *
 * Les dates sont relatives à aujourd'hui : la démo reste crédible même
 * plusieurs mois après le déploiement. Le cron `demo:reset` relance ce
 * seeder chaque nuit.
 */
class DemoSeeder extends Seeder
{
    private string $password;

    private string $passwordHash;

    private User $admin;

    private User $supervisor;

    private User $participant;

    /** @var Collection<int, User> */
    private Collection $agents;

    /** @var array<string, Categorie> */
    private array $categories = [];

    /** @var array<string, Lieu> */
    private array $lieux = [];

    /** @var array<string, Formation> */
    private array $formations = [];

    /** @var array<string, Sessionpro> */
    private array $sessions = [];

    /**
     * Les sessions de démonstration. Les clés servent ensuite à créer les
     * présences et les demandes d'attestation.
     *
     * @var array<string, array<string, mixed>>
     */
    private array $sessionSpecs = [];

    public function run(): void
    {
        $this->password = (string) config('demo.password', 'Demo1234!');
        $this->passwordHash = Hash::make($this->password);

        $this->resetDemoTables();

        DB::transaction(function (): void {
            $this->seedUsers();
            $this->seedReferentials();
            $this->seedSessions();
            $this->seedEnrollments();
            $this->seedPresences();
            $this->seedDemandes();
        });

        $this->command?->info('Démo prête : admin@demo.certifs.test / superviseur@demo.certifs.test / participant@demo.certifs.test');
        $this->command?->info('Mot de passe : '.$this->password);
    }

    private function resetDemoTables(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ([
            'demandes',
            'presence_user',
            'presences',
            'sessionpro_user',
            'sessionpros',
            'formations',
            'lieus',
            'categories',
            'sessions',
            'password_reset_tokens',
            'users',
        ] as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();
    }

    private function seedUsers(): void
    {
        $this->admin = $this->createUser(
            'Awa', 'ADJOVI', 'F', 'admin', 'ADM-001', 'admin@demo.certifs.test'
        );

        $this->supervisor = $this->createUser(
            'Koffi', 'HOUNKPATIN', 'M', 'superviseur', 'SUP-001', 'superviseur@demo.certifs.test'
        );

        $this->participant = $this->createUser(
            'Ruth', 'DOSSOU', 'F', 'participant', 'AGT-001', 'participant@demo.certifs.test'
        );

        // Signataires utilisés par la vue des attestations.
        $this->createUser('Marc', 'AGBODJAN', 'M', 'superviseur', 'DGTCP-001', 'dgtcp@demo.certifs.test', 'DGTCP');
        $this->createUser('Clarisse', 'HOUNSA', 'F', 'superviseur', 'DFPT-001', 'dfpt@demo.certifs.test', 'DFPT');

        $pool = [
            ['Afi', 'AGOSSOU', 'F'],
            ['Boris', 'AHOUANSOU', 'M'],
            ['Céline', 'AKAKPO', 'F'],
            ['Désiré', 'ALAPINI', 'M'],
            ['Estelle', 'AMOUSSOU', 'F'],
            ['Fabrice', 'ASSANI', 'M'],
            ['Grâce', 'ATCHADE', 'F'],
            ['Hervé', 'AVLESSI', 'M'],
            ['Inès', 'BADA', 'F'],
            ['Joël', 'BAGNAN', 'M'],
            ['Katia', 'BEHANZIN', 'F'],
            ['Léon', 'BIAOU', 'M'],
            ['Mireille', 'BOSSOU', 'F'],
            ['Nicolas', 'CHABI', 'M'],
            ['Olga', 'CODO', 'F'],
            ['Pascal', 'DANSOU', 'M'],
            ['Quentin', 'DJIMASSI', 'M'],
            ['Rolande', 'DOSSOU', 'F'],
            ['Serge', 'GBAGUIDI', 'M'],
            ['Tania', 'GNAMMI', 'F'],
            ['Urbain', 'GODONOU', 'M'],
            ['Valérie', 'HOUNKPE', 'F'],
            ['Wilfried', 'IDOHOU', 'M'],
            ['Yasmine', 'KORA', 'F'],
        ];

        $this->agents = collect($pool)->map(function (array $person, int $index): User {
            return $this->createUser(
                $person[0],
                $person[1],
                $person[2],
                'participant',
                sprintf('AGT-%03d', $index + 2),
                Str::slug($person[0].'.'.$person[1], '.').'@demo.certifs.test'
            );
        });
    }

    private function createUser(
        string $firstName,
        string $lastName,
        string $sexe,
        string $role,
        string $matricule,
        string $email,
        ?string $titre = null
    ): User {
        $user = User::create([
            'name' => $lastName,
            'surname' => $firstName,
            'sexe' => $sexe,
            'role' => $role,
            'matricule' => $matricule,
            'titre' => $titre,
            'email' => $email,
            'password' => $this->passwordHash,
        ]);

        // email_verified_at n'est pas mass-assignable dans le modèle User.
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    private function seedReferentials(): void
    {
        $categories = [
            ['Comptabilité publique', 'Règles, principes et pratiques de la comptabilité publique.'],
            ['Marchés publics', 'Passation, exécution et contrôle des marchés publics.'],
            ['Fiscalité', 'Fiscalité des entreprises et recouvrement des créances publiques.'],
            ['Management & leadership', 'Encadrement d\'équipe et pilotage de la performance.'],
            ['Informatique & bureautique', 'Outils numériques, Excel et sécurité de l\'information.'],
            ['Audit & contrôle', 'Contrôle interne, audit et gestion des risques financiers.'],
        ];

        foreach ($categories as [$nom, $description]) {
            $this->categories[$nom] = Categorie::create([
                'nom' => $nom,
                'description' => $description,
            ]);
        }

        $lieux = [
            ['Cotonou', 'Direction Générale du Trésor, Route de l\'Aéroport'],
            ['Porto-Novo', 'Centre de Formation Professionnelle, Avenue Jean Bayol'],
            ['Parakou', 'Trésor Public, Quartier Zongo'],
            ['Abomey-Calavi', 'Salle de formation, Mairie'],
            ['Natitingou', 'Centre de formation, Quartier Hannoukpa'],
            ['Lokossa', 'Trésor Public, Centre-ville'],
        ];

        foreach ($lieux as [$ville, $adresse]) {
            $this->lieux[$ville] = Lieu::create([
                'ville' => $ville,
                'adresse' => $adresse,
            ]);
        }

        $formations = [
            ['compta1', 'Comptabilité générale — niveau 1', 'Les fondamentaux de la comptabilité générale appliqués au secteur public.', 'Comptabilité publique'],
            ['compta2', 'Comptabilité publique et SYSCOHADA', 'Opérations comptables, états financiers et référentiel SYSCOHADA.', 'Comptabilité publique'],
            ['marches1', 'Passation des marchés publics', 'Procédures de passation, seuils et bonnes pratiques.', 'Marchés publics'],
            ['marches2', 'Exécution et contrôle des marchés publics', 'Suivi de l\'exécution, réception et contrôle des marchés.', 'Marchés publics'],
            ['fiscal1', 'Fiscalité des entreprises', 'Principaux impôts et obligations déclaratives des entreprises.', 'Fiscalité'],
            ['fiscal2', 'Recouvrement des créances publiques', 'Techniques de recouvrement amiable et contentieux.', 'Fiscalité'],
            ['management1', 'Management d\'équipe et leadership', 'Animer une équipe et développer son leadership au quotidien.', 'Management & leadership'],
            ['management2', 'Pilotage de la performance', 'Indicateurs, objectifs et évaluation de la performance.', 'Management & leadership'],
            ['excel1', 'Excel avancé pour la gestion publique', 'Tableaux croisés dynamiques, fonctions avancées et automatisation.', 'Informatique & bureautique'],
            ['cyber1', 'Cybersécurité et protection des données', 'Hygiène numérique, mots de passe et protection des données sensibles.', 'Informatique & bureautique'],
            ['audit1', 'Audit interne et contrôle des comptes', 'Méthodologie d\'audit et contrôle interne des comptes publics.', 'Audit & contrôle'],
            ['audit2', 'Gestion des risques financiers', 'Identification, évaluation et maîtrise des risques financiers.', 'Audit & contrôle'],
        ];

        foreach ($formations as [$key, $theme, $description, $categorie]) {
            $this->formations[$key] = Formation::create([
                'theme' => $theme,
                'description' => $description,
                'categorie_id' => $this->categories[$categorie]->id,
            ]);
        }
    }

    private function seedSessions(): void
    {
        $today = Carbon::today();

        $this->addSession('compta1', 'compta1', 'Cotonou', $today->copy()->subMonths(3), 4, '08:30:00', '16:30:00', false, true, 14, 3, true, false);
        $this->addSession('compta2', 'compta2', 'Porto-Novo', $today->copy()->subMonths(2), 3, '08:00:00', '17:00:00', false, true, 12, 3, true, false);
        $this->addSession('marches1', 'marches1', 'Cotonou', $today->copy()->subWeeks(6), 2, '09:00:00', '16:00:00', false, false, 12, 2, true, false);
        $this->addSession('fiscal1', 'fiscal1', 'Parakou', $today->copy()->subMonths(4), 2, '08:30:00', '16:00:00', false, true, 10, 2, false, false);
        $this->addSession('management1', 'management1', 'Cotonou', $today->copy()->subWeeks(5), 1, '08:00:00', '17:00:00', false, true, 15, 1, false, false);
        $this->addSession('excel1', 'excel1', 'Cotonou', $today->copy()->subDay(), 3, '08:30:00', '16:30:00', false, false, 10, 1, true, true);
        $this->addSession('marches2', 'marches2', 'Porto-Novo', $today->copy()->addWeeks(2), 3, '08:30:00', '16:30:00', true, false, 4, 0, false, false);
        $this->addSession('audit1', 'audit1', 'Cotonou', $today->copy()->addMonth(), 2, '08:00:00', '17:00:00', true, false, 6, 0, true, false);
        $this->addSession('cyber1', 'cyber1', 'Abomey-Calavi', $today->copy()->addWeeks(6), 1, '09:00:00', '16:00:00', false, false, 3, 0, false, false);
        $this->addSession('fiscal2', 'fiscal2', 'Natitingou', $today->copy()->addMonths(2), 3, '08:30:00', '16:30:00', true, false, 0, 0, false, false);
        $this->addSession('compta3', 'compta1', 'Lokossa', $today->copy()->subDays(10), 1, '08:00:00', '17:00:00', false, true, 8, 1, false, false);
        $this->addSession('management2', 'management2', 'Porto-Novo', $today->copy()->subWeeks(8), 2, '08:30:00', '16:00:00', false, true, 12, 2, false, false);
    }

    /**
     * @param  array<string, mixed>  $spec
     */
    private function addSession(
        string $key,
        string $formationKey,
        string $lieu,
        Carbon $start,
        int $days,
        string $heureDebut,
        string $heureFin,
        bool $peutInscrire,
        bool $peutDemander,
        int $participantCount,
        int $presenceCount,
        bool $withDemoParticipant,
        bool $ongoing
    ): void {
        $end = $start->copy()->addDays(max(0, $days - 1));

        $session = Sessionpro::create([
            'date_debut' => $start->toDateString(),
            'date_fin' => $end->toDateString(),
            'heure_debut' => $heureDebut,
            'heure_fin' => $heureFin,
            'peut_inscrire' => $peutInscrire,
            'peut_demander' => $peutDemander,
            'code' => $this->uniqueCode(),
            'formation_id' => $this->formations[$formationKey]->id,
            'lieu_id' => $this->lieux[$lieu]->id,
        ]);

        $this->sessions[$key] = $session;
        $this->sessionSpecs[$key] = [
            'participant_count' => $participantCount,
            'presence_count' => $presenceCount,
            'with_demo_participant' => $withDemoParticipant,
            'ongoing' => $ongoing,
            'past' => $end->isBefore(Carbon::today()),
        ];

        // La session a été créée quelques semaines avant son démarrage : cela
        // donne un ordre chronologique naturel aux tableaux de bord.
        DB::table('sessionpros')->where('id', $session->id)->update([
            'created_at' => $start->copy()->subWeeks(2),
            'updated_at' => $start->copy()->subWeeks(2),
        ]);
    }

    private function uniqueCode(): string
    {
        do {
            $code = (string) random_int(100000, 999999);
        } while (Sessionpro::where('code', $code)->exists());

        return $code;
    }

    private function seedEnrollments(): void
    {
        foreach ($this->sessions as $key => $session) {
            $spec = $this->sessionSpecs[$key];
            $target = (int) $spec['participant_count'];

            $selected = collect();

            if ($spec['with_demo_participant']) {
                $selected->push($this->participant);
            }

            $remaining = max(0, $target - $selected->count());
            $selected = $selected->merge(
                $this->agents->shuffle()->take($remaining)
            )->unique('id');

            $rows = [];
            foreach ($selected as $user) {
                $rows[] = [
                    'sessionpro_id' => $session->id,
                    'user_id' => $user->id,
                    'nb_presence' => 0,
                    'note' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if ($rows !== []) {
                DB::table('sessionpro_user')->insert($rows);
            }
        }
    }

    private function seedPresences(): void
    {
        foreach ($this->sessions as $key => $session) {
            $spec = $this->sessionSpecs[$key];
            $presenceCount = (int) $spec['presence_count'];

            if ($presenceCount === 0) {
                continue;
            }

            $start = Carbon::parse($session->date_debut);
            $end = Carbon::parse($session->date_fin);
            $users = $session->users()->get();

            for ($i = 0; $i < $presenceCount; $i++) {
                $date = $spec['ongoing']
                    ? Carbon::today()
                    : $start->copy()->addDays($i);

                if ($date->gt($end)) {
                    $date = $end->copy();
                }

                // Les participants sont majoritairement présents. Le compte
                // de démonstration est toujours présent pour pouvoir tester
                // la demande d'attestation.
                $attendees = $users->shuffle()->take(
                    max(1, (int) round($users->count() * 0.8))
                );

                if ($spec['with_demo_participant']) {
                    $attendees = $attendees->push($this->participant)->unique('id');
                }

                $presence = Presence::create([
                    'date' => $date->toDateString(),
                    'est_actif' => $spec['ongoing'] && $i === 0,
                    'nb_participants' => $attendees->count(),
                    'sessionpro_id' => $session->id,
                ]);

                $attendanceRows = [];
                $number = 1;
                foreach ($attendees as $user) {
                    $attendanceRows[] = [
                        'presence_id' => $presence->id,
                        'user_id' => $user->id,
                        'num_presence' => $number++,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if ($attendanceRows !== []) {
                    DB::table('presence_user')->insert($attendanceRows);
                }
            }

            // Nombre de présences suivi et note, recalculés en une requête.
            $counts = DB::table('presence_user')
                ->join('presences', 'presences.id', '=', 'presence_user.presence_id')
                ->where('presences.sessionpro_id', $session->id)
                ->select('presence_user.user_id', DB::raw('count(*) as total'))
                ->groupBy('presence_user.user_id')
                ->pluck('total', 'user_id');

            $pivotRows = [];
            foreach ($users as $user) {
                $attended = (int) ($counts[$user->id] ?? 0);

                $note = null;
                if ($spec['past']) {
                    if ($user->is($this->participant) && $spec['with_demo_participant']) {
                        $note = 15;
                    } else {
                        $note = random_int(9, 19);
                    }
                }

                $pivotRows[] = [
                    'sessionpro_id' => $session->id,
                    'user_id' => $user->id,
                    'nb_presence' => $attended,
                    'note' => $note === null ? null : (string) $note,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('sessionpro_user')->where('sessionpro_id', $session->id)->delete();
            if ($pivotRows !== []) {
                DB::table('sessionpro_user')->insert($pivotRows);
            }
        }
    }

    private function seedDemandes(): void
    {
        $today = Carbon::today();

        // File d'attente du superviseur : demandes envoyées aujourd'hui.
        $this->createDemandeForSession('compta1', 'envoyée', $today->copy()->setTime(9, 15), 3);
        $this->createDemandeForSession('compta1', 'traitement en cours', $today->copy()->subDay()->setTime(15, 40), 1);

        // Réponses déjà disponibles pour le participant de démonstration.
        $treated = $this->createDemandeForSession('compta2', 'traitée', $today->copy()->subDays(12)->setTime(10, 0), 1, true);
        if ($treated) {
            $treated->updated_at = $today->copy()->subDays(10)->setTime(11, 30);
            $treated->save();
        }

        // Historique de traitement.
        $this->createDemandeForSession('fiscal1', 'traitement en cours', $today->copy()->subDays(2)->setTime(11, 0), 2);
        $this->createDemandeForSession('management1', 'traitée', $today->copy()->subDays(20)->setTime(14, 0), 2);
        $this->createDemandeForSession('compta3', 'traitée', $today->copy()->subDays(8)->setTime(9, 30), 2);
    }

    private function createDemandeForSession(
        string $sessionKey,
        string $statut,
        Carbon $createdAt,
        int $limit = 1,
        bool $onlyDemoParticipant = false
    ): ?Demande {
        $session = $this->sessions[$sessionKey] ?? null;
        if (! $session) {
            return null;
        }

        $query = $session->users()->get();
        if ($onlyDemoParticipant) {
            $query = $query->filter(fn (User $user) => $user->is($this->participant));
        } else {
            $query = $query->filter(fn (User $user) => ! $user->is($this->participant));
        }

        $created = null;

        foreach ($query->take($limit) as $user) {
            $pivotId = DB::table('sessionpro_user')
                ->where('sessionpro_id', $session->id)
                ->where('user_id', $user->id)
                ->value('id');

            if (! $pivotId) {
                continue;
            }

            $demande = Demande::create([
                'libelle_demande' => '«'.$session->formation->theme.'»',
                'statut' => $statut,
                'sessionpro_user_id' => $pivotId,
            ]);

            $demande->created_at = $createdAt;
            $demande->updated_at = $createdAt;
            $demande->save();

            $created ??= $demande;
        }

        return $created;
    }
}
