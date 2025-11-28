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

// Ensure 'programmes' table exists and has required columns for new logic
try {
  $table = db()->query("SHOW TABLES LIKE 'programmes'")->fetchColumn();
  if (!$table) {
    db()->exec(
      "CREATE TABLE programmes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(200) NOT NULL,
        description TEXT NULL,
        excerpt VARCHAR(500) NULL,
        content MEDIUMTEXT NULL,
        url VARCHAR(500) NULL,
        image_url VARCHAR(500) NULL,
        status VARCHAR(20) NULL DEFAULT 'published',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
  } else {
    // Add missing columns progressively
    $cols = db()->query("SHOW COLUMNS FROM programmes")->fetchAll(PDO::FETCH_COLUMN);
    $add = function ($sql) {
      db()->exec($sql);
    };
    if (!in_array('excerpt', $cols)) $add("ALTER TABLE programmes ADD COLUMN excerpt VARCHAR(500) NULL AFTER description");
    if (!in_array('content', $cols)) $add("ALTER TABLE programmes ADD COLUMN content MEDIUMTEXT NULL AFTER excerpt");
    if (!in_array('url', $cols)) $add("ALTER TABLE programmes ADD COLUMN url VARCHAR(500) NULL AFTER content");
    if (!in_array('status', $cols)) $add("ALTER TABLE programmes ADD COLUMN status VARCHAR(20) NULL DEFAULT 'published' AFTER image_url");
    if (!in_array('updated_at', $cols)) $add("ALTER TABLE programmes ADD COLUMN updated_at DATETIME NULL AFTER created_at");
  }
} catch (Throwable $e) {
  // ignore programmes migration errors
}
// Ensure 'formations' table has required columns and seed initial items
try {
  $formTable = db()->query("SHOW TABLES LIKE 'formations'")->fetchColumn();
  if ($formTable) {
    $cols = db()->query("SHOW COLUMNS FROM formations")->fetchAll(PDO::FETCH_COLUMN);
    $addF = function ($sql) {
      db()->exec($sql);
    };
    if (!in_array('description', $cols)) $addF("ALTER TABLE formations ADD COLUMN description VARCHAR(500) NULL AFTER name");
    if (!in_array('status', $cols)) $addF("ALTER TABLE formations ADD COLUMN status VARCHAR(20) NULL DEFAULT 'published' AFTER description");
    // Seed from programmes or carousels if empty
    $fcount = (int)db()->query('SELECT COUNT(*) FROM formations')->fetchColumn();
    if ($fcount === 0) {
      // Prefer seed from programmes if available, else carousels
      $progExists = db()->query("SHOW TABLES LIKE 'programmes'")->fetchColumn();
      $seedRows = [];
      if ($progExists) {
        $seedRows = db()->query('SELECT name, excerpt AS description, image_url FROM programmes ORDER BY id DESC')->fetchAll();
      }
      if (empty($seedRows)) {
        $carExists = db()->query("SHOW TABLES LIKE 'carousels'")->fetchColumn();
        if ($carExists) {
          $seedRows = db()->query('SELECT title AS name, caption AS description, background_url AS image_url FROM carousels ORDER BY position ASC')->fetchAll();
        }
      }
      if (!empty($seedRows)) {
        $insF = db()->prepare('INSERT INTO formations(name, description, status, image_url) VALUES(?,?,?,?)');
        foreach ($seedRows as $r) {
          $name = $r['name'] ?? 'Formation';
          $desc = $r['description'] ?? '';
          $img = $r['image_url'] ?? '';
          $insF->execute([$name, $desc, 'published', $img]);
        }
      }
    }
  }
} catch (Throwable $e) {
  // ignore formations migration/seed errors
}

// Seed specific requested formations if missing
try {
  $requiredFormations = [
    ['name' => 'Pompiste / Station-service', 'description' => '', 'status' => 'published'],
    ['name' => 'Caissière & Rayonniste', 'description' => '', 'status' => 'published'],
    ['name' => 'Technicien Solaire', 'description' => '', 'status' => 'published'],
    ['name' => 'Transport & Logistique', 'description' => '', 'status' => 'published'],
  ];
  $existsTable = db()->query("SHOW TABLES LIKE 'formations'")->fetchColumn();
  if ($existsTable) {
    $sel = db()->prepare('SELECT COUNT(*) FROM formations WHERE name = ?');
    $ins = db()->prepare('INSERT INTO formations(name, description, status, image_url) VALUES(?,?,?,?)');
    foreach ($requiredFormations as $f) {
      $sel->execute([$f['name']]);
      $count = (int)$sel->fetchColumn();
      if ($count === 0) {
        $ins->execute([$f['name'], $f['description'], $f['status'], '']);
      }
    }
  }
} catch (Throwable $e) {
  // ignore manual seed errors
}

// Ensure 'sections' table exists (for dynamic section headers)
try {
  $secTable = db()->query("SHOW TABLES LIKE 'sections'")->fetchColumn();
  if (!$secTable) {
    db()->exec(
      "CREATE TABLE sections (
        `key` VARCHAR(50) PRIMARY KEY,
        title VARCHAR(200) NULL,
        subtitle VARCHAR(500) NULL,
        updated_at DATETIME NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
  }
} catch (Throwable $e) {
  // ignore sections migration errors
}

// Ensure 'centres' (Instituts & Centres) table exists with required columns
try {
  $centTable = db()->query("SHOW TABLES LIKE 'centres'")->fetchColumn();
  if (!$centTable) {
    db()->exec(
      "CREATE TABLE centres (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(200) NOT NULL,
        subtitle VARCHAR(300) NULL,
        excerpt VARCHAR(500) NULL,
        content MEDIUMTEXT NULL,
        url VARCHAR(500) NULL,
        image_url VARCHAR(500) NULL,
        status VARCHAR(20) NULL DEFAULT 'published',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
  } else {
    $cols = db()->query("SHOW COLUMNS FROM centres")->fetchAll(PDO::FETCH_COLUMN);
    $addC = function ($sql) {
      db()->exec($sql);
    };
    if (!in_array('subtitle', $cols)) $addC("ALTER TABLE centres ADD COLUMN subtitle VARCHAR(300) NULL AFTER name");
    if (!in_array('excerpt', $cols)) $addC("ALTER TABLE centres ADD COLUMN excerpt VARCHAR(500) NULL AFTER subtitle");
    if (!in_array('content', $cols)) $addC("ALTER TABLE centres ADD COLUMN content MEDIUMTEXT NULL AFTER excerpt");
    if (!in_array('url', $cols)) $addC("ALTER TABLE centres ADD COLUMN url VARCHAR(500) NULL AFTER content");
    if (!in_array('status', $cols)) $addC("ALTER TABLE centres ADD COLUMN status VARCHAR(20) NULL DEFAULT 'published' AFTER image_url");
    if (!in_array('updated_at', $cols)) $addC("ALTER TABLE centres ADD COLUMN updated_at DATETIME NULL AFTER created_at");
  }
} catch (Throwable $e) {
  // ignore centres migration errors
}

// Seed initial centres if empty
try {
  $centExists = db()->query("SHOW TABLES LIKE 'centres'")->fetchColumn();
  if ($centExists) {
    $ccount = (int)db()->query('SELECT COUNT(*) FROM centres')->fetchColumn();
    if ($ccount === 0) {
      $insC = db()->prepare('INSERT INTO centres(name, subtitle, excerpt, content, url, image_url, status, created_at) VALUES(?,?,?,?,?,?,?,?)');
      $now = date('Y-m-d H:i:s');
      $insC->execute([
        'Centre Énergie & Industrie',
        'Innovation & ingénierie',
        'Pôle dédié aux énergies, à l’ingénierie et aux technologies appliquées.',
        '',
        '',
        base_url('assets/img/centre1.jpg'),
        'published',
        $now
      ]);
      $insC->execute([
        'Institut Commerce & Services',
        'Management & Retail',
        'Pôle orienté commerce, services, retail et relation client.',
        '',
        '',
        base_url('assets/img/centre2.jpg'),
        'published',
        $now
      ]);
    }
  }
} catch (Throwable $e) {
  // ignore centres seed errors
}

// Seed initial programmes from existing carousels if empty
try {
  $progExists = db()->query("SHOW TABLES LIKE 'programmes'")->fetchColumn();
  $carExists = db()->query("SHOW TABLES LIKE 'carousels'")->fetchColumn();
  if ($progExists && $carExists) {
    $pcount = (int)db()->query('SELECT COUNT(*) FROM programmes')->fetchColumn();
    if ($pcount === 0) {
      $cars = db()->query('SELECT * FROM carousels ORDER BY position ASC')->fetchAll();
      $ins = db()->prepare('INSERT INTO programmes(name, description, excerpt, content, url, status, image_url, created_at) VALUES(?,?,?,?,?,?,?,?)');
      foreach ($cars as $c) {
        $name = $c['title'] ?? 'Programme';
        $description = $c['caption'] ?? '';
        $excerpt = $c['description'] ?? '';
        $content = '';
        $url = $c['button_url'] ?? '';
        $status = 'published';
        $image = $c['background_url'] ?? '';
        $ins->execute([$name, $description, $excerpt, $content, $url, $status, $image, date('Y-m-d H:i:s')]);
      }
    }
  }
} catch (Throwable $e) {
  // ignore seeding programmes errors
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

// Admin Centres (Instituts & Centres IFMAP)
$router->get('/admin/centres', fn() => require_auth(fn() => (new AdminController())->centresIndex()));
$router->get('/admin/centres/create', fn() => require_auth(fn() => (new AdminController())->centresForm()));
$router->post('/admin/centres/create', fn() => require_auth(fn() => (new AdminController())->centresStore()));
$router->get('/admin/centres/edit', fn() => require_auth(fn() => (new AdminController())->centresForm()));
$router->post('/admin/centres/edit', fn() => require_auth(fn() => (new AdminController())->centresUpdate()));
$router->get('/admin/centres/delete', fn() => require_auth(fn() => (new AdminController())->centresDelete()));
// Section params save for centres
$router->post('/admin/centres/section/save', fn() => require_auth(fn() => (new AdminController())->centresSectionSave()));

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
$router->get('/centres', fn() => view('public/centres', [
  'title' => (function () {
    $st = db()->prepare('SELECT title FROM sections WHERE `key`=?');
    $st->execute(['centres']);
    $t = $st->fetchColumn();
    return $t ?: 'Instituts & Centres IFMAP';
  })(),
  'subtitle' => (function () {
    $st = db()->prepare('SELECT subtitle FROM sections WHERE `key`=?');
    $st->execute(['centres']);
    $t = $st->fetchColumn();
    return $t ?: "Découvrez nos pôles d'excellence et d'innovation.";
  })(),
  'items' => db()->query("SELECT * FROM centres WHERE COALESCE(status,'published')='published' ORDER BY id DESC")->fetchAll()
]));
$router->get('/formations', fn() => view('public/formations', [
  'title' => (function () {
    $st = db()->prepare('SELECT title FROM sections WHERE `key`=?');
    $st->execute(['formations']);
    $t = $st->fetchColumn();
    return $t ?: 'Formations IFMAP';
  })(),
  'subtitle' => (function () {
    $st = db()->prepare('SELECT subtitle FROM sections WHERE `key`=?');
    $st->execute(['formations']);
    $t = $st->fetchColumn();
    return $t ?: 'Des formations professionnalisantes adaptées au marché africain.';
  })(),
  'items' => db()->query("SELECT * FROM formations WHERE COALESCE(status,'published')='published' ORDER BY id DESC")->fetchAll()
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

// TinyMCE upload endpoint
$router->post('/admin/upload', fn() => require_auth(fn() => (new AdminController())->adminUpload()));

// Admin Formations section params save
$router->post('/admin/formations/section/save', fn() => require_auth(fn() => (new AdminController())->formationsSectionSave()));

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
