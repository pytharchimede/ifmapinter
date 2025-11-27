<?php
// Front controller + bootstrap minimal
// Chargement autoload et helpers
require __DIR__ . '/app/Core/Autoload.php';
require __DIR__ . '/app/functions.php';

use App\Core\Router;
use App\Core\Database;
use App\Controllers\HomeController;

// Config + BDD
$cfg = config();
Database::init($cfg);
Database::migrate();

// Router
$router = new Router();

// Routes (GET)
$router->get('/', fn() => (new HomeController())->index());

// Exemple: autres pages statiques réutilisables si besoin
// $router->get('/formations', fn () => view('formations', ['title' => 'Formations']));

// Dispatch
$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
