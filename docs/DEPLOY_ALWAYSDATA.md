# Déployer la démo sur alwaysdata (offre Free)

Ce guide décrit le déploiement de **Certifs — Démo** sur l'offre **Free** d'alwaysdata : 0 €, sans carte bancaire, 1 Go de disque, 256 Mo de RAM, PHP 8.3, MySQL et tâches planifiées.

> La démo utilise uniquement des **données fictives**. Elle est remise à zéro chaque nuit par un cron. Elle ne doit jamais contenir de données réelles d'agents.

## 1. Créer le compte

1. Aller sur [admin.alwaysdata.com](https://admin.alwaysdata.com/) et créer un compte.
2. Dans **Choix de l'offre**, sélectionner **Free** (0 €), et non l'offre *Small* qui est payante.
3. Choisir un nom de compte, par exemple `certif-frk` : le site sera accessible à l'adresse `https://certif-frk.alwaysdata.net`.
4. Accepter les conditions et valider la création.

> L'offre Free est réservée à un usage personnel (portfolio, démonstration). Elle n'autorise pas les sites commerciaux ni les domaines personnalisés. Pour un domaine perso, il faut l'offre Plus (à partir de 5 €/mois).

## 2. Créer la base MySQL

Dans l'interface d'administration :

1. **Bases de données → MySQL → Ajouter une base**.
2. Créer une base `certif` (ticket : elle sera nommée `<compte>_certif`).
3. Créer un utilisateur MySQL avec un mot de passe solide, ou utiliser l'utilisateur par défaut.
4. Noter :
   - hôte : `mysql-<compte>.alwaysdata.net`
   - base : `<compte>_certif`
   - utilisateur : `<compte>`
   - mot de passe : celui choisi

## 3. Créer le site

1. **Web → Sites → Ajouter un site**.
2. Adresse : `<compte>.alwaysdata.net`.
3. Type : **PHP**.
4. Version de PHP : **8.3** (les dépendances verrouillées ne sont pas compatibles avec PHP 8.4).
5. Racine du site : `/home/<compte>/www/certif/public`.
6. Activer **HTTPS** (certificat Let's Encrypt).
7. Valider.

## 4. Accès SSH

1. **Accès distant → SSH/SFTP**.
2. Ajouter une clé SSH publique (recommandé) ou utiliser le mot de passe du compte.
3. L'hôte est `ssh-<compte>.alwaysdata.net`, l'utilisateur est `<compte>`.

```bash
ssh <compte>@ssh-<compte>.alwaysdata.net
```

## 5. Déployer le code

Le plus simple est de cloner le dépôt public :

```bash
mkdir -p ~/www/certif
git clone https://github.com/russell-sedj/certif_frk.git ~/www/certif
```

Alternative : envoyer une archive ZIP via SFTP, puis la décompresser dans `~/www/certif`.

> `public/build` et `vendor` ne sont pas versionnés. Voir les étapes 6 et 7.

## 6. Installer les dépendances PHP

Toujours en SSH, dans le dossier du projet :

```bash
cd ~/www/certif
composer install --no-dev --optimize-autoloader
```

Si `composer` n'est pas disponible :

```bash
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev --optimize-autoloader
```

## 7. Construire les assets

Le plus fiable est de construire les assets en local (Node 20+) puis d'envoyer `public/build` :

```bash
npm ci
npm run build
```

Puis, depuis la machine locale :

```bash
scp -r public/build <compte>@ssh-<compte>.alwaysdata.net:~/www/certif/public/
```

> Sans `public/build`, les pages qui utilisent `@vite` renvoient une erreur « Vite manifest not found ».

## 8. Configurer `.env`

Toujours dans `~/www/certif` :

```bash
cp .env.example .env
```

Éditer `.env` avec les valeurs de production :

```env
APP_NAME="Certifs — Démo"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://<compte>.alwaysdata.net
APP_TIMEZONE=Africa/Porto-Novo

APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
APP_FAKER_LOCALE=fr_FR

LOG_CHANNEL=daily
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=mysql-<compte>.alwaysdata.net
DB_PORT=3306
DB_DATABASE=<compte>_certif
DB_USERNAME=<compte>
DB_PASSWORD=<mot_de_passe_mysql>

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
php artisan key:generate
```

> `MAIL_MAILER=log` est volontaire : la démo n'envoie aucun email. Les mots de passe oubliés ne fonctionnent pas, c'est normal.

## 9. Migrations et données de démonstration

```bash
php artisan migrate:fresh --seed --force
```

`DatabaseSeeder` appelle `DemoSeeder` : catégories, formations, lieux, sessions passées/en cours/à venir, inscriptions, présences, demandes et comptes de démonstration.

## 10. Optimiser pour la production

```bash
php artisan config:cache
php artisan view:cache
```

> **Ne pas lancer `php artisan optimize`** : il inclut `route:cache`, qui échoue à cause des routes déclarées avec des closures dans `routes/web.php`. `config:cache` et `view:cache` suffisent.

## 11. Permissions

```bash
chmod -R 775 storage bootstrap/cache
```

## 12. Remise à zéro automatique

Dans **Advanced → Scheduled tasks**, ajouter une tâche :

- Commande : `cd /home/<compte>/www/certif && php artisan demo:reset --force`
- Fréquence : tous les jours à 04:00
- Version de PHP : 8.3 (dans **Environnement**)

La commande :

1. vérifie que `DEMO_MODE=true` ;
2. exécute `migrate:fresh` + `DemoSeeder` ;
3. vide les caches de config et de vues.

Les logs de la tâche sont dans `/home/<compte>/admin/logs/jobs/`. La remise à zéro réinitialise aussi les sessions : les visiteurs connectés sont déconnectés à 4 h du matin. C'est acceptable pour une démo.

## 13. Vérifier

1. Ouvrir `https://<compte>.alwaysdata.net` : la page de connexion doit proposer les trois boutons de démo.
2. Tester les trois rôles.
3. Vérifier `/up` (health check Laravel) : doit répondre 200.
4. Cliquer sur **Réinitialiser** dans la bannière : les données doivent revenir à l'état initial.
5. Se connecter en superviseur, ouvrir les demandes, télécharger une attestation : le PDF doit porter le filigrane **SPÉCIMEN — DÉMO**.

Comptes :

| Rôle | Email | Mot de passe |
|---|---|---|
| Administrateur | `admin@demo.certifs.test` | `Demo1234!` |
| Superviseur | `superviseur@demo.certifs.test` | `Demo1234!` |
| Participant | `participant@demo.certifs.test` | `Demo1234!` |

## 14. Mettre à jour la démo

```bash
cd ~/www/certif
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan view:cache
```

Si les seeders ont changé, relancer `php artisan demo:reset --force` (ou attendre le cron). Si les assets ont changé, reconstruire et renvoyer `public/build`.

## 15. Dépannage

| Symptôme | Piste |
|---|---|
| Page 500 | Lire `storage/logs/laravel.log`, vérifier `APP_KEY`, les permissions et la connexion MySQL. |
| `Vite manifest not found` | `public/build` manquant : `npm run build` puis envoyer le dossier. |
| 419 « Page expirée » | Cookie de session / HTTPS. Vérifier `APP_URL`, `SESSION_SECURE_COOKIE` et que le site est bien ouvert en HTTPS. |
| `route:cache` échoue | Routes avec closures : ne pas utiliser `php artisan optimize`. |
| Connexion MySQL refusée | L'hôte est `mysql-<compte>.alwaysdata.net`, la base et l'utilisateur sont préfixés par `<compte>`. |
| Assets en HTTP (mixed content) | `APP_URL` en HTTPS ; `TrustProxies` est configuré et `URL::forceScheme('https')` est actif en production. |
| Page blanche après reset | Le reset vide les sessions : se reconnecter via les boutons de démo. |
| Trop de monde / RAM | 256 Mo sur l'offre Free. Pour un vrai lancement, passer à l'offre Plus (1 Go RAM, domaine perso, 5 €/mois). |

## 16. Sécurité et limites

- La démo est publique : les trois comptes le sont aussi. **Ne jamais y mettre de données réelles.**
- Les données sont fictives, réinitialisées chaque nuit, et les attestations portent un filigrane « SPÉCIMEN — DÉMO ».
- `APP_DEBUG=false`, `.env` non versionné, `robots.txt`/meta `noindex` pour ne pas indexer la démo.
- L'offre Free est personnelle et sans domaine perso. Pour un domaine et plus de confort, prévoir 5 €/mois.
- Les offres gratuites peuvent évoluer : garder ce guide et un plan B (offre Plus ou petit VPS) sous la main.
