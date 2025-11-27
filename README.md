# IFMAP Site

Structure modernisée avec routeur, vues, partials et initialisation automatique de la base MySQL.

## Structure

- `index.php`: Front controller (routes + bootstrap)
- `app/config.php`: Configuration (base de données, nom d'app)
- `app/Core/Autoload.php`: Autoloader du namespace `App\\`
- `app/Core/Router.php`: Routeur minimal (GET)
- `app/Core/Database.php`: Connexion PDO + création automatique de la DB et tables
- `app/functions.php`: Helpers (`config()`, `view()`, `base_url()`, `db()`)
- `app/Controllers/HomeController.php`: Contrôleur d'accueil
- `views/partials/header.php`, `views/partials/footer.php`: Partials réutilisables
- `views/home.php`: Vue d'accueil (contenu existant)
- `views/errors/404.php`, `views/errors/500.php`: Pages d'erreur
- `assets/css/style.css`, `assets/js/app.js`: CSS/JS existants
- `.htaccess`: Réécriture d'URL (URLs sans `.php`)

## Prérequis

- Apache (WAMP) avec `mod_rewrite` activé
- PHP 8+ recommandé
- MySQL/MariaDB accessible avec les identifiants fournis

## Configuration BDD

Éditer `app/config.php` si besoin. Valeurs par défaut:

```
host = localhost
user = ifmapci_ulrich
pass = @Succes2019
name = ifmapci_website_db
charset = utf8mb4
```

Au premier chargement, l'appli:
- crée la base si elle n'existe pas
- exécute des migrations idempotentes (`news`, `programmes`, `formations`, `partners`, `pages`)

## URLs

- Accueil: `/`
- Les assets restent servis depuis `assets/...`

### Admin

- Connexion: `/login`
- Tableau de bord: `/admin`
- Modules: `/admin/news`, `/admin/programmes`, `/admin/formations`, `/admin/partners`

Compte par défaut (créé automatiquement si vide):
- Email: `admin@ifmap.ci`
- Mot de passe: `admin123` (à modifier rapidement)

### Pages publiques

- Actualités: `/actualites`
- Programmes: `/programmes`
- Formations: `/formations`
- Partenaires: `/partenaires`

## Sécurité

- CSRF: Tous les formulaires d'administration et de connexion intègrent un jeton (`csrf_field()`), vérifié côté serveur (`require_csrf()`).
- Mot de passe: Hashé avec `password_hash()` (BCRYPT). Modification via `/admin/password`.
- Sessions: Stockent uniquement `id` + `email` utilisateur.
- Échappement: Les sorties HTML dynamiques passent par `htmlspecialchars()`.
- À renforcer ensuite: Rôles utilisateurs, pagination, logs d'audit, rate limiting sur `/login`.

## Ajout de routes

Dans `index.php`:

```php
$router->get('/votre-chemin', fn () => view('votre-vue', ['title' => 'Titre']));
```

Créer ensuite `views/votre-vue.php` en réutilisant `views/partials/header.php` et `views/partials/footer.php`.

## Notes

- Le serveur fourni comportait une coquille `locahost` -> corrigée en `localhost`.
- Si WAMP ne pointe pas sur ce dossier, vérifiez que `.htaccess` est pris en compte (AllowOverride All).
