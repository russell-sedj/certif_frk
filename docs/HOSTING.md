# Héberger plusieurs projets sur un compte alwaysdata

Ce document décrit l'organisation commune du compte `test-rvss`. Elle doit rester valable pour les projets PHP, statiques, Node.js, Java, .NET ou Python, sans mélanger les fichiers et sans exposer le code source.

## Principe

Un seul compte, plusieurs sites. Chaque projet garde son code dans son dossier, et seuls les fichiers publics sont accessibles depuis le web.

```text
/home/test-rvss/
├── www/                          racine web du site principal
│   ├── index.html                page d'accueil
│   ├── certif -> ../apps/certif/public
│   ├── projet-html/              fichiers statiques
│   └── autre-php -> ../apps/autre-php/public
├── apps/
│   ├── certif/                   Laravel
│   ├── autre-php/                autre application PHP
│   ├── projet-node/              application Node.js
│   ├── projet-java/              application Java
│   └── projet-dotnet/            application .NET
└── admin/                        logs et configuration alwaysdata
```

Règles de base :

- `~/apps/<projet>` contient le code, les dépendances, le `.env` et les données privées.
- `~/www` ne contient que des fichiers publics ou des liens vers des dossiers publics.
- Un projet PHP est accessible sur `https://test-rvss.alwaysdata.net/<projet>/`.
- Un projet Node.js, Java, .NET ou Python est accessible sur son propre sous-domaine, par exemple `https://projet-java.test-rvss.alwaysdata.net`.
- Chaque projet a sa propre base de données et son propre utilisateur MySQL.
- Chaque projet a son propre script de maintenance et, si besoin, sa propre tâche planifiée.

## Site principal

Le site `test-rvss.alwaysdata.net` est de type **PHP**, avec pour racine `/home/test-rvss/www` et PHP 8.3.

Cette racine sert deux choses :

1. les fichiers statiques et la page d'accueil ;
2. les applications PHP montées dans un sous-dossier, via un lien symbolique.

Apache suit les liens symboliques. C'est ce qui permet d'avoir `www/certif` qui pointe vers `apps/certif/public` tout en gardant le code hors de la racine web.

## Ajouter un projet PHP

Exemple avec un projet nommé `facturation` :

```bash
cd ~/apps
git clone <url-du-depot> facturation
cd facturation
composer install --no-dev --optimize-autoloader
cp .env.example .env
PHP_VERSION=8.3 php artisan key:generate
ln -s ~/apps/facturation/public ~/www/facturation
chmod -R 775 storage bootstrap/cache
```

Pour Laravel, deux réglages sont indispensables quand l'application n'est pas à la racine du domaine :

```env
APP_URL=https://test-rvss.alwaysdata.net/facturation
ASSET_URL=https://test-rvss.alwaysdata.net/facturation
```

Et dans `app/Providers/AppServiceProvider.php` :

```php
if ($this->app->environment('production')) {
    URL::forceScheme('https');
    URL::forceRootUrl(config('app.url'));
}
```

Vérifier ensuite :

- `https://test-rvss.alwaysdata.net/facturation/` répond 200 ;
- les liens du menu et les fichiers CSS/JS contiennent bien `/facturation/` ;
- le health check `https://test-rvss.alwaysdata.net/facturation/up` répond 200.

Pour Symfony, Symfony possède son propre mécanisme de base URL. Le lien symbolique reste le même, mais il faut configurer le préfixe dans `.env` ou dans la configuration du framework.

## Ajouter un site statique

Deux possibilités :

- **Dans le site principal** : copier les fichiers dans `~/www/mon-site/`. Le site sera accessible sur `/mon-site/`.
- **Sur un sous-domaine** : créer un site de type **Fichiers statiques** avec pour racine `~/apps/mon-site` et l'adresse `mon-site.test-rvss.alwaysdata.net`.

La première solution est la plus simple pour une page ou une démo. La seconde isole complètement le projet et évite les problèmes de chemins relatifs.

## Ajouter un projet Node.js, Java, .NET ou Python

Ces projets ne s'installent pas dans un sous-dossier de `www` : ils ont besoin de leur propre serveur. alwaysdata propose un type de site par langage.

1. Mettre le code dans `~/apps/<projet>`.
2. Installer les dépendances avec la version du langage voulue.
3. **Web → Sites → Ajouter un site**.
4. Adresse : `<projet>.test-rvss.alwaysdata.net`.
5. Type : **Node.js**, **Java**, **.NET**, **Python WSGI** ou **Deno** selon le projet.
6. Dossier de travail : `apps/<projet>`.
7. Commande de démarrage : celle du projet.
8. Variables d'environnement : celles du projet, dont la clé de production.
9. Activer HTTPS.

Pour Node.js, alwaysdata fournit les variables `IP` et `PORT`. Le serveur doit écouter sur ces valeurs, pas sur un port fixe :

```js
const port = process.env.PORT || 3000;
server.listen(port, process.env.IP || "0.0.0.0");
```

Pour Java, le type **Java** utilise Tomcat. Un exécutable autonome peut aussi passer par le type **User program** ou **Reverse proxy**.

Pour .NET, le type **.NET** attend une application publiée. La commande doit pointer vers le binaire ou le fichier `.dll` publié.

Points d'attention sur l'offre Free :

- 256 Mo de RAM pour tout le compte. Une JVM ou une application .NET complète peut consommer beaucoup. Réserver ces projets aux petites démos, ou passer à l'offre Plus pour plus de mémoire.
- Les **Services** (programmes qui tournent en permanence) sont réservés aux offres payantes. Les types de site Node.js, Java, .NET et Python sont lancés par alwaysdata et conviennent très bien à des démos.
- Une seule application par site. Pour plusieurs applications non-PHP, créer un sous-domaine par application.

## Ajouter une base de données

Toujours dans **Bases de données → MySQL** :

1. Créer la base `<projet>` : elle sera nommée `test-rvss_<projet>`.
2. Créer un utilisateur dédié `test-rvss_<projet>_user1`.
3. Choisir un mot de passe simple, sans caractères spéciaux, propre à ce projet.
4. Laisser **Adresse IP** vide et **Connexion SSL** décochée.
5. Donner à l'utilisateur tous les droits sur sa base.

Tester la connexion depuis SSH avant de configurer l'application :

```bash
mysql -h mysql-test-rvss.alwaysdata.net -u test-rvss_<projet>_user1 -p test-rvss_<projet>
```

Une erreur 1045 signifie un problème de mot de passe ou de nom d'utilisateur. Une erreur 1044 signifie que l'utilisateur n'a pas les droits sur la base.

## Tâches planifiées

Chaque projet garde son script dans son dossier :

```bash
~/apps/<projet>/<projet>-reset.sh
```

Le script doit être exécutable et fixer lui-même la version du langage et le dossier de travail :

```sh
#!/bin/sh
cd /home/test-rvss/apps/<projet> || exit 1
exec env PHP_VERSION=8.3 php artisan demo:reset --force
```

Dans **Advanced → Scheduled tasks**, utiliser le chemin complet du script, le répertoire `/home/test-rvss/` et l'utilisateur `test-rvss`.

## Conventions

- Un projet, un dossier dans `~/apps`.
- Un projet, un sous-domaine ou un sous-dossier stable.
- Un projet, une base et un utilisateur MySQL.
- Pas d'espace ni d'accent dans les noms de dossiers et de bases.
- Le `.env` reste en `chmod 600` et n'est jamais versionné.
- Les secrets ne passent pas par le dépôt Git.
- Les assets compilés sont envoyés sur le serveur, pas reconstruits à chaque requête.

## Ajouter un projet, résumé

1. Choisir le nom du projet et son URL.
2. Créer `~/apps/<projet>` et y déployer le code.
3. Installer les dépendances.
4. Créer la base et l'utilisateur MySQL si nécessaire.
5. Configurer le `.env`.
6. Pour PHP : créer le lien `~/www/<projet>` vers `public`.
7. Pour les autres langages : créer un site sur un sous-domaine avec le bon type.
8. Lancer les migrations ou le script d'initialisation.
9. Vérifier l'URL, les assets et les formulaires.
10. Ajouter une tâche planifiée si le projet en a besoin.
11. Mettre à jour la page d'accueil et la documentation.

## Limites de l'offre Free

- 1 Go de disque et 256 Mo de RAM pour tout le compte.
- Sites, bases et boîtes mail illimités en nombre, mais soumis aux ressources du compte.
- Pas de domaine personnalisé.
- Pas de Services permanents.
- Usage personnel, pas commercial.
- Les tâches planifiées et HTTPS sont disponibles.

Pour un projet Java ou .NET sérieux, ou pour un vrai domaine, l'offre Plus à 5 €/mois apporte 1 Go de RAM et supprime ces limites.
