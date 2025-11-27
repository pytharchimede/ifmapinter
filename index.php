<?php
// Front controller + bootstrap minimal
// Chargement autoload et helpers
session_start();
require __DIR__ . '/app/Core/Autoload.php';
require __DIR__ . '/app/functions.php';

use App\Core\Router;
use App\Core\Database;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;

// Config + BDD
$cfg = config();
Database::init($cfg);
Database::migrate();

// Seed initial carousels if empty (supports local assets URLs)
try {
  $exists = db()->query("SHOW TABLES LIKE 'carousels'")->fetchColumn();
  if ($exists) {
    $count = (int)db()->query('SELECT COUNT(*) FROM carousels')->fetchColumn();
    if ($count === 0) {
      $stmt = db()->prepare('INSERT INTO carousels (position, title, caption, description, button_text, button_url, background_url) VALUES (?,?,?,?,?,?,?)');
      $stmt->execute([1, 'Institut IFMAP', 'Excellence académique', 'Nous formons les compétences de demain avec excellence, innovation et impact.', 'Découvrir nos Programmes', base_url('programmes'), base_url('assets/img/hero1.jpg')]);
      $stmt->execute([2, 'Formations d’excellence', 'Parcours pro', 'Des parcours professionnalisants alignés sur les besoins des entreprises.', 'Voir les Formations', base_url('formations'), base_url('assets/img/hero2.jpg')]);
      $stmt->execute([3, 'Entreprises partenaires', 'Réseau fort', 'Un réseau actif pour l’insertion et l’employabilité de nos diplômés.', 'Nos Partenaires', base_url('partenaires'), base_url('assets/img/hero3.jpg')]);
    }
  }
} catch (Throwable $e) {
  // ignore seeding errors
}

// Router
$router = new Router();

// Routes (GET)
$router->get('/', fn() => (new HomeController())->index());

// Auth
$router->get('/login', fn() => (new AuthController())->loginForm());
$router->post('/login', fn() => (new AuthController())->login());
$router->get('/logout', fn() => (new AuthController())->logout());

// Admin (protégé)
$router->get('/admin', fn() => require_auth(fn() => (new AdminController())->dashboard()));
$router->get('/admin/news', fn() => require_auth(fn() => (new AdminController())->newsIndex()));
$router->get('/admin/news/create', fn() => require_auth(fn() => (new AdminController())->newsForm()));
$router->post('/admin/news/create', fn() => require_auth(fn() => (new AdminController())->newsStore()));
$router->get('/admin/news/edit', fn() => require_auth(fn() => (new AdminController())->newsForm()));
$router->post('/admin/news/edit', fn() => require_auth(fn() => (new AdminController())->newsUpdate()));
$router->get('/admin/news/delete', fn() => require_auth(fn() => (new AdminController())->newsDelete()));

$router->get('/admin/programmes', fn() => require_auth(fn() => (new AdminController())->programmesIndex()));
$router->get('/admin/programmes/create', fn() => require_auth(fn() => (new AdminController())->programmesForm()));
$router->post('/admin/programmes/create', fn() => require_auth(fn() => (new AdminController())->programmesStore()));
$router->get('/admin/programmes/edit', fn() => require_auth(fn() => (new AdminController())->programmesForm()));
$router->post('/admin/programmes/edit', fn() => require_auth(fn() => (new AdminController())->programmesUpdate()));
$router->get('/admin/programmes/delete', fn() => require_auth(fn() => (new AdminController())->programmesDelete()));

$router->get('/admin/formations', fn() => require_auth(fn() => (new AdminController())->formationsIndex()));
$router->get('/admin/formations/create', fn() => require_auth(fn() => (new AdminController())->formationsForm()));
$router->post('/admin/formations/create', fn() => require_auth(fn() => (new AdminController())->formationsStore()));
$router->get('/admin/formations/edit', fn() => require_auth(fn() => (new AdminController())->formationsForm()));
$router->post('/admin/formations/edit', fn() => require_auth(fn() => (new AdminController())->formationsUpdate()));
$router->get('/admin/formations/delete', fn() => require_auth(fn() => (new AdminController())->formationsDelete()));

$router->get('/admin/partners', fn() => require_auth(fn() => (new AdminController())->partnersIndex()));
$router->get('/admin/partners/create', fn() => require_auth(fn() => (new AdminController())->partnersForm()));
$router->post('/admin/partners/create', fn() => require_auth(fn() => (new AdminController())->partnersStore()));
$router->get('/admin/partners/edit', fn() => require_auth(fn() => (new AdminController())->partnersForm()));
$router->post('/admin/partners/edit', fn() => require_auth(fn() => (new AdminController())->partnersUpdate()));
$router->get('/admin/partners/delete', fn() => require_auth(fn() => (new AdminController())->partnersDelete()));
$router->get('/admin/media', fn() => require_auth(fn() => (new AdminController())->mediaIndex()));
$router->get('/admin/media/create', fn() => require_auth(fn() => (new AdminController())->mediaForm()));
$router->post('/admin/media/create', fn() => require_auth(fn() => (new AdminController())->mediaStore()));
$router->get('/admin/media/edit', fn() => require_auth(fn() => (new AdminController())->mediaForm()));
$router->post('/admin/media/edit', fn() => require_auth(fn() => (new AdminController())->mediaUpdate()));
$router->get('/admin/media/delete', fn() => require_auth(fn() => (new AdminController())->mediaDelete()));

// Admin sécurité
$router->get('/admin/password', fn() => require_auth(fn() => (new AdminController())->passwordForm()));
$router->post('/admin/password', fn() => require_auth(fn() => (new AdminController())->passwordUpdate()));

// Pages publiques listes
$router->get('/actualites', fn() => view('public/news', [
  'title' => 'Actualités',
  'items' => db()->query('SELECT * FROM news ORDER BY COALESCE(published_at, created_at) DESC')->fetchAll()
]));
$router->get('/actualites/article', function () {
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id <= 0) return view('errors/404', ['title' => 'Article introuvable']);
  $stmt = db()->prepare('SELECT * FROM news WHERE id = ?');
  $stmt->execute([$id]);
  $article = $stmt->fetch();
  if (!$article) return view('errors/404', ['title' => 'Article introuvable']);
  return view('public/news_show', ['title' => $article['title'], 'article' => $article]);
});
$router->get('/programmes', fn() => view('public/programmes', [
  'title' => 'Programmes',
  'items' => db()->query('SELECT * FROM programmes ORDER BY id DESC')->fetchAll()
]));
$router->get('/formations', fn() => view('public/formations', [
  'title' => 'Formations',
  'items' => db()->query('SELECT * FROM formations ORDER BY id DESC')->fetchAll()
]));
$router->get('/partenaires', fn() => view('public/partners', [
  'title' => 'Partenaires',
  'items' => db()->query('SELECT * FROM partners ORDER BY id DESC')->fetchAll()
]));
$router->get('/galerie', fn() => view('public/gallery', [
  'title' => 'Galerie',
  'media' => db()->query('SELECT * FROM media ORDER BY id DESC')->fetchAll()
]));
$router->get('/galerie/data', function () {
  $page = max(1, (int)($_GET['page'] ?? 1));
  $type = $_GET['type'] ?? 'all';
  $category = trim($_GET['category'] ?? '');
  $limit = 12;
  $offset = ($page - 1) * $limit;
  $sql = 'SELECT * FROM media WHERE 1';
  $params = [];
  if ($type !== 'all') {
    $sql .= ' AND type=?';
    $params[] = $type;
  }
  if ($category !== '') {
    $sql .= ' AND category=?';
    $params[] = $category;
  }
  $sql .= ' ORDER BY id DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
  $st = db()->prepare($sql);
  $st->execute($params);
  $rows = $st->fetchAll();
  header('Content-Type: application/json');
  echo json_encode($rows);
  return '';
});

// Pages: Institut, Campus, Alumni, Contact
$router->get('/institut', fn() => view('public/institut', [
  'title' => "L'Institut IFMAP"
]));

$router->get('/campus', fn() => view('public/campus', [
  'title' => 'Campus',
  'status' => 'Projet en cours',
]));

$router->get('/alumni', fn() => view('public/alumni', [
  'title' => 'Alumni'
]));

$router->get('/contact', fn() => view('public/contact', [
  'title' => 'Contact'
]));
$router->post('/contact', function () {
  require_csrf();
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $message = trim($_POST['message'] ?? '');
  if ($name !== '' && $message !== '') {
    $st = db()->prepare('INSERT INTO contact_messages(name,email,phone,message) VALUES(?,?,?,?)');
    $st->execute([$name, $email, $phone, $message]);
  }
  $success = 'Message envoyé. Merci !';
  return view('public/contact', ['title' => 'Contact', 'success' => $success]);
});

// Admin: Messages de contact
$router->get('/admin/contacts', fn() => require_auth(fn() => (new AdminController())->contactsIndex()));
$router->get('/admin/contacts/export.csv', fn() => require_auth(fn() => (new AdminController())->contactsExportCsv()));
$router->post('/admin/contacts/mark', fn() => require_auth(fn() => (new AdminController())->contactsMark()));

// Admin Carousels
$router->get('/admin/carousels', fn() => require_auth(fn() => (new AdminController())->carouselsIndex()));
$router->get('/admin/carousels/create', fn() => require_auth(fn() => (new AdminController())->carouselsForm()));
$router->post('/admin/carousels/create', fn() => require_auth(fn() => (new AdminController())->carouselsStore()));
$router->get('/admin/carousels/edit', fn() => require_auth(fn() => (new AdminController())->carouselsForm()));
$router->post('/admin/carousels/edit', fn() => require_auth(fn() => (new AdminController())->carouselsUpdate()));
$router->get('/admin/carousels/delete', fn() => require_auth(fn() => (new AdminController())->carouselsDelete()));
$router->post('/admin/carousels/order', fn() => require_auth(fn() => (new AdminController())->carouselsOrder()));

// Alumni: modèle de CV téléchargeable (HTML simple pour l'instant)
$router->get('/alumni/cv-template', fn() => view('public/alumni_cv', [
  'title' => 'Modèle de CV Alumni'
]));

// Export PDF du modèle CV via Dompdf si disponible
$router->get('/alumni/cv-template/pdf', function () {
  if (class_exists('Dompdf\\Dompdf')) {
    ob_start();
    echo view('public/alumni_cv', ['title' => 'Modèle de CV Alumni']);
    $html = ob_get_clean();
    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('cv_alumni_ifmap.pdf', ['Attachment' => true]);
    return '';
  }
  return view('public/alumni_cv', ['title' => 'Modèle de CV Alumni', 'dompdf_missing' => true]);
});

// Variante compacte A4 du CV
$router->get('/alumni/cv-template/compact', fn() => view('public/alumni_cv_compact', [
  'title' => 'CV Alumni – Compact A4'
]));

// Export PDF compact via Dompdf
$router->get('/alumni/cv-template/compact/pdf', function () {
  if (class_exists('Dompdf\\Dompdf')) {
    ob_start();
    echo view('public/alumni_cv_compact', ['title' => 'CV Alumni – Compact A4']);
    $html = ob_get_clean();
    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('cv_alumni_ifmap_compact.pdf', ['Attachment' => true]);
    return '';
  }
  return view('public/alumni_cv_compact', ['title' => 'CV Alumni – Compact A4', 'dompdf_missing' => true]);
});

// Exemple: autres pages statiques réutilisables si besoin
// $router->get('/formations', fn () => view('formations', ['title' => 'Formations']));

// Dispatch
$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
