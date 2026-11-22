# Déployer la démo sur alwaysdata (offre Free)

Ce guide décrit le déploiement de **Certifs (démo)** sur l'offre **Free** d'alwaysdata : 0 €, sans carte bancaire, 1 Go de disque, 256 Mo de RAM, PHP 8.3, MySQL et tâches planifiées.

> La démo utilise uniquement des **données fictives**. Elle est remise à zéro chaque nuit par un cron. Elle ne doit jamais contenir de données réelles d'agents.

## État du déploiement actuel

| | |
|---|---|
| URL | [https://test-rvss.alwaysdata.net/certif/](https://test-rvss.alwaysdata.net/certif/) |
| Compte | `test-rvss` |
| Racine du site | `/home/test-rvss/www` |
| Application | `/home/test-rvss/apps/certif` |
| Lien public | `/home/test-rvss/www/certif` -> `/home/test-rvss/apps/certif/public` |
| PHP du site | 8.3.37 |
| INI CLI PHP 8.3 | `/home/test-rvss/php83.ini` |
| Base MySQL | `mysql-test-rvss.alwaysdata.net` / `test-rvss_certif` / `test-rvss_certif_user1` |
| Script de reset | `/home/test-rvss/apps/certif/demo-reset.sh` |

Le compte héberge plusieurs projets. La structure et les règles communes sont décrites dans [HOSTING.md](HOSTING.md). Certif occupe le chemin `/certif/`, le reste de `www` reste libre pour les autres sites.

## Organisation des dossiers

```text
/home/test-rvss/
├── www/                       racine web du site principal
│   ├── index.html             page d'accueil provisoire
│   └── certif -> ../apps/certif/public
├── apps/
│   └── certif/                code Laravel, .env, vendor, storage
└── admin/                     logs et configuration alwaysdata
```

Règles :

- Le code et les fichiers sensibles restent dans `~/apps`, jamais dans `~/www`.
- Seul le dossier `public` de Laravel est exposé, via un lien symbolique.
- Un nouveau projet PHP suit le même schéma : `~/apps/<projet>` et `~/www/<projet>` -> `~/apps/<projet>/public`.
- Un projet Node.js, Java, .NET ou Python utilise un site séparé sur un sous-domaine, avec `~/apps/<projet>` comme dossier de travail. Voir [HOSTING.md](HOSTING.md).

## PHP en ligne de commande

Le PHP par défaut du compte est en 8.4, et son `php.ini` charge les extensions 8.4. Lancer un binaire 8.3 avec ce fichier provoque des erreurs (`undefined symbol`, extensions non chargées). Le compte dispose donc d'un fichier dédié, copié depuis la configuration PHP 8.3 du site :

```text
/home/test-rvss/php83.ini
```

Toutes les commandes Artisan passent par :

```bash
/usr/alwaysdata/php/8.3.37/bin/php -c /home/test-rvss/php83.ini artisan <commande>
```

Ne pas utiliser `PHP_VERSION=8.3 php` seul : le binaire change, mais le `php.ini` reste celui de 8.4.

Pour le cron, préférer le script `demo-reset.sh`, qui fixe déjà la version, le fichier INI et le dossier.

## 1. Créer le compte

1. Aller sur [admin.alwaysdata.com](https://admin.alwaysdata.com/) et créer un compte.
2. Dans **Choix de l'offre**, sélectionner **Free** (0 €), et non l'offre *Small* qui est payante.
3. Choisir un nom de compte, par exemple `test-rvss` : le site sera accessible à l'adresse `https://test-rvss.alwaysdata.net`.
4. Accepter les conditions et valider la création.

> L'offre Free est réservée à un usage personnel (portfolio, démonstration). Elle n'autorise pas les sites commerciaux ni les domaines personnalisés. Pour un domaine perso, il faut l'offre Plus (à partir de 5 €/mois).

## 2. Créer la base MySQL

Dans l'interface d'administration :

1. **Bases de données → MySQL → Ajouter une base**.
2. Créer une base `certif` (elle sera nommée `test-rvss_certif`).
3. Créer un utilisateur MySQL `test-rvss_certif_user1` avec le mot de passe `CertifDemo2026`.
4. Laisser **Adresse IP** vide et **Connexion SSL** décochée.
5. Donner à cet utilisateur tous les droits sur `test-rvss_certif`.
6. Noter :
   - hôte : `mysql-test-rvss.alwaysdata.net`
   - base : `test-rvss_certif`
   - utilisateur : `test-rvss_certif_user1`
   - mot de passe : `CertifDemo2026`

> Un mot de passe sans caractères spéciaux évite les erreurs de parsing dans `.env`. Ce mot de passe ne sert qu'à la base de démonstration.

## 3. Créer le site

1. **Web → Sites → Ajouter un site** (ou modifier le site créé par défaut).
2. Adresse : `test-rvss.alwaysdata.net`.
3. Type : **PHP**.
4. Version de PHP : **8.3** (les dépendances verrouillées ne sont pas compatibles avec PHP 8.4).
5. Racine du site : `/home/test-rvss/www`.
6. Activer **HTTPS** et la redirection HTTP vers HTTPS.
7. Valider.

Le dossier racine n'est pas créé automatiquement : il faut le faire en SSH (étape 5).

## 4. Accès SSH

1. **Accès distant → SSH/SFTP**.
2. Ajouter la clé SSH publique utilisée pour le déploiement.
3. L'hôte est `ssh-test-rvss.alwaysdata.net`, l'utilisateur est `test-rvss`.

```bash
ssh test-rvss@ssh-test-rvss.alwaysdata.net
```

## 5. Déployer le code

Le plus simple est de cloner le dépôt public :

```bash
mkdir -p ~/apps ~/www
git clone https://github.com/russell-sedj/certif_frk.git ~/apps/certif
ln -s ~/apps/certif/public ~/www/certif
```

Alternative : envoyer une archive ZIP via SFTP, puis la décompresser dans `~/apps/certif`.

> `public/build` et `vendor` ne sont pas versionnés. Voir les étapes 6 et 7.

## 6. Installer les dépendances PHP

Toujours en SSH, dans le dossier du projet :

```bash
cd ~/apps/certif
composer install --no-dev --optimize-autoloader
```

Si `composer` n'est pas disponible :

```bash
cd ~/apps/certif
curl -sS https://getcomposer.org/installer | /usr/alwaysdata/php/8.3.37/bin/php -c /home/test-rvss/php83.ini
/usr/alwaysdata/php/8.3.37/bin/php -c /home/test-rvss/php83.ini composer.phar install --no-dev --optimize-autoloader
```

## 7. Construire les assets

Le plus fiable est de construire les assets en local (Node 20+) puis d'envoyer `public/build` :

```bash
npm ci
npm run build
```

Puis, depuis la machine locale :

```bash
scp -r public/build test-rvss@ssh-test-rvss.alwaysdata.net:~/apps/certif/public/
```

> Sans `public/build`, les pages qui utilisent `@vite` renvoient une erreur « Vite manifest not found ».

## 8. Configurer `.env`

Toujours dans `~/apps/certif` :

```bash
cp .env.example .env
```

Éditer `.env` avec les valeurs de production :

```env
APP_NAME="Certifs"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://test-rvss.alwaysdata.net/certif
ASSET_URL=https://test-rvss.alwaysdata.net/certif
APP_TIMEZONE=Africa/Porto-Novo

APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
APP_FAKER_LOCALE=fr_FR

LOG_CHANNEL=daily
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=mysql-test-rvss.alwaysdata.net
DB_PORT=3306
DB_DATABASE=test-rvss_certif
DB_USERNAME=test-rvss_certif_user1
DB_PASSWORD=CertifDemo2026

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_SECURE_COOKIE=true
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MAIL_MAILER=log

DEMO_MODE=true
DEMO_PASSWORD=Demo1234!
```

Générer la clé applicative :

```bash
/usr/alwaysdata/php/8.3.37/bin/php -c /home/test-rvss/php83.ini artisan key:generate
```

> `APP_URL` et `ASSET_URL` contiennent le chemin `/certif` : c'est ce qui permet à Laravel de générer des URL et des liens d'assets corrects quand l'application n'est pas à la racine du domaine.
>
> `MAIL_MAILER=log` est volontaire : la démo n'envoie aucun email. Les mots de passe oubliés ne fonctionnent pas, c'est normal.

## 9. Migrations et données de démonstration

```bash
/usr/alwaysdata/php/8.3.37/bin/php -c /home/test-rvss/php83.ini artisan migrate:fresh --seed --force
```

`DatabaseSeeder` appelle `DemoSeeder` : catégories, formations, lieux, sessions passées/en cours/à venir, inscriptions, présences, demandes et comptes de démonstration.

## 10. Optimiser pour la production

```bash
/usr/alwaysdata/php/8.3.37/bin/php -c /home/test-rvss/php83.ini artisan config:cache
/usr/alwaysdata/php/8.3.37/bin/php -c /home/test-rvss/php83.ini artisan view:cache
```

> **Ne pas lancer `php artisan optimize`** : il inclut `route:cache`, qui échoue à cause des routes déclarées avec des closures dans `routes/web.php`. `config:cache` et `view:cache` suffisent.
>
> Après chaque modification de `.env`, relancer `config:clear` puis `config:cache`.

## 11. Permissions

```bash
chmod -R 775 ~/apps/certif/storage ~/apps/certif/bootstrap/cache
```

## 12. Remise à zéro automatique

Le script `~/apps/certif/demo-reset.sh` encapsule la commande :

```bash
#!/bin/sh
cd /home/test-rvss/apps/certif || exit 1
PHP83=$(ls -d /usr/alwaysdata/php/8.3.*/bin/php 2>/dev/null | sort -V | tail -1)
[ -n "$PHP83" ] || PHP83=/usr/alwaysdata/php/8.3.37/bin/php
exec "$PHP83" -c /home/test-rvss/php83.ini artisan demo:reset --force
```

Dans **Advanced → Scheduled tasks**, ajouter une tâche :

- Commande : `/home/test-rvss/apps/certif/demo-reset.sh`
- Répertoire de travail : `/home/test-rvss/`
- Utilisateur SSH : `test-rvss`
- Fréquence : tous les jours à 04:00

La commande :

1. vérifie que `DEMO_MODE=true` ;
2. exécute `migrate:fresh` + `DemoSeeder` ;
3. réinitialise la base et les sessions.

Les caches de configuration et de vues ne sont pas vidés : ils restent valides tant que `.env` et les vues ne changent pas.

Les logs de la tâche sont dans `/home/test-rvss/admin/logs/jobs/`. La remise à zéro réinitialise aussi les sessions : les visiteurs connectés sont déconnectés à 4 h du matin. C'est acceptable pour une démo.

## 13. Vérifier

1. Ouvrir `https://test-rvss.alwaysdata.net/certif/` : la page de connexion doit proposer les trois boutons de démo.
2. Tester les trois rôles.
3. Vérifier `/certif/up` (health check Laravel) : doit répondre 200.
4. Cliquer sur **Réinitialiser** dans la bannière : les données doivent revenir à l'état initial.
5. Se connecter en superviseur, ouvrir les demandes, télécharger une attestation : le PDF doit porter le filigrane **SPÉCIMEN - DÉMO**.

Comptes :

| Rôle | Email | Mot de passe |
|---|---|---|
| Administrateur | `admin@demo.certifs.test` | `Demo1234!` |
| Superviseur | `superviseur@demo.certifs.test` | `Demo1234!` |
| Participant | `participant@demo.certifs.test` | `Demo1234!` |

## 14. Mettre à jour la démo

```bash
cd ~/apps/certif
git pull
composer install --no-dev --optimize-autoloader
/usr/alwaysdata/php/8.3.37/bin/php -c /home/test-rvss/php83.ini artisan migrate --force
/usr/alwaysdata/php/8.3.37/bin/php -c /home/test-rvss/php83.ini artisan config:cache
/usr/alwaysdata/php/8.3.37/bin/php -c /home/test-rvss/php83.ini artisan view:cache
```

Si les seeders ont changé, relancer `/usr/alwaysdata/php/8.3.37/bin/php -c /home/test-rvss/php83.ini artisan demo:reset --force` (ou attendre le cron). Si les assets ont changé, reconstruire et renvoyer `public/build`.

## 15. Dépannage

| Symptôme | Piste |
|---|---|
| Page 500 | Lire `storage/logs/laravel.log`, vérifier `APP_KEY`, les permissions et la connexion MySQL. |
| `Vite manifest not found` | `public/build` manquant : `npm run build` puis envoyer le dossier. |
| 419 « Page expirée » | Cookie de session / HTTPS. Vérifier `APP_URL`, `SESSION_SECURE_COOKIE` et que le site est bien ouvert en HTTPS. |
| `route:cache` échoue | Routes avec closures : ne pas utiliser `php artisan optimize`. |
| Connexion MySQL refusée (1045) | Le mot de passe ou le nom d'utilisateur ne correspond pas. Tester la connexion directement en SSH avant de toucher à Laravel. |
| Connexion MySQL refusée (1044) | L'utilisateur n'a pas les droits sur la base : donner `GRANT ALL` sur la base dans la fiche MySQL. |
| Assets en HTTP (mixed content) | `APP_URL` et `ASSET_URL` en HTTPS, avec le chemin `/certif`. |
| Liens sans `/certif` | `ASSET_URL` manquant ou `config:cache` non relancé. |
| Page blanche après reset | Le reset vide les sessions : se reconnecter via les boutons de démo. |
| Trop de monde / RAM | 256 Mo sur l'offre Free. Pour un vrai lancement, passer à l'offre Plus (1 Go RAM, domaine perso, 5 €/mois). |

## 16. Sécurité et limites

- La démo est publique : les trois comptes le sont aussi. **Ne jamais y mettre de données réelles.**
- Les données sont fictives, réinitialisées chaque nuit, et les attestations portent un filigrane « SPÉCIMEN - DÉMO ».
- `APP_DEBUG=false`, `.env` non versionné, `robots.txt`/meta `noindex` pour ne pas indexer la démo.
- L'offre Free est personnelle et sans domaine perso. Pour un domaine et plus de confort, prévoir 5 €/mois.
- Les offres gratuites peuvent évoluer : garder ce guide et un plan B (offre Plus ou petit VPS) sous la main.
