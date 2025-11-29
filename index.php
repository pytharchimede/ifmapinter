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

// Ensure 'news' table has status column for draft/published and image_url
try {
  $newsTable = db()->query("SHOW TABLES LIKE 'news'")->fetchColumn();
  if ($newsTable) {
    $cols = db()->query("SHOW COLUMNS FROM news")->fetchAll(PDO::FETCH_COLUMN);
    $addN = function ($sql) {
      db()->exec($sql);
    };
    if (!in_array('status', $cols)) $addN("ALTER TABLE news ADD COLUMN status VARCHAR(20) NULL DEFAULT 'published' AFTER image_url");
    if (!in_array('image_url', $cols)) $addN("ALTER TABLE news ADD COLUMN image_url VARCHAR(500) NULL AFTER body");
    if (!in_array('published_at', $cols)) $addN("ALTER TABLE news ADD COLUMN published_at DATETIME NULL AFTER status");
    if (!in_array('source', $cols)) $addN("ALTER TABLE news ADD COLUMN source VARCHAR(200) NULL AFTER published_at");
    if (!in_array('article_url', $cols)) $addN("ALTER TABLE news ADD COLUMN article_url VARCHAR(500) NULL AFTER source");
  }
} catch (Throwable $e) {
  // ignore news migration errors
}

// RSS sources table
try {
  $rssTable = db()->query("SHOW TABLES LIKE 'rss_sources'")->fetchColumn();
  if (!$rssTable) {
    db()->exec(
      "CREATE TABLE rss_sources (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(200) NOT NULL,
        url VARCHAR(500) NOT NULL,
        enabled TINYINT(1) NOT NULL DEFAULT 1,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
  }
} catch (Throwable $e) {
  // ignore rss_sources migration errors
}

// RSS items cache table
try {
  $rssCache = db()->query("SHOW TABLES LIKE 'rss_items_cache'")->fetchColumn();
  if (!$rssCache) {
    db()->exec(
      "CREATE TABLE rss_items_cache (
        id INT AUTO_INCREMENT PRIMARY KEY,
        source_url VARCHAR(500) NOT NULL,
        title VARCHAR(500) NOT NULL,
        link VARCHAR(500) NOT NULL,
        pub_date DATETIME NULL,
        description TEXT NULL,
        fetched_at DATETIME NOT NULL,
        expires_at DATETIME NOT NULL,
        INDEX idx_source_url (source_url),
        INDEX idx_expires (expires_at)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
  }
} catch (Throwable $e) {
  // ignore rss cache migration errors
}

// Ensure partners table has enabled column
try {
  $partnersTable = db()->query("SHOW TABLES LIKE 'partners'")->fetchColumn();
  if ($partnersTable) {
    $cols = db()->query("SHOW COLUMNS FROM partners")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('enabled', $cols)) {
      db()->exec("ALTER TABLE partners ADD COLUMN enabled TINYINT(1) NOT NULL DEFAULT 1");
    }
  }
} catch (Throwable $e) {
  // ignore partners migration errors
}

// Ensure event registrations table exists (for internal inscription fallback)
try {
  $regTable = db()->query("SHOW TABLES LIKE 'event_registrations'")->fetchColumn();
  if (!$regTable) {
    db()->exec(
      "CREATE TABLE event_registrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        event_id INT NOT NULL,
        name VARCHAR(200) NOT NULL,
        email VARCHAR(200) NULL,
        phone VARCHAR(60) NULL,
        message TEXT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_event (event_id),
        CONSTRAINT fk_event_reg FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
  }
  // Add missing columns (status, consent) progressively
  $regCols = db()->query("SHOW COLUMNS FROM event_registrations")->fetchAll(PDO::FETCH_COLUMN);
  if (!in_array('status', $regCols)) {
    db()->exec("ALTER TABLE event_registrations ADD COLUMN status VARCHAR(20) NULL DEFAULT 'pending' AFTER message");
  }
  if (!in_array('consent', $regCols)) {
    db()->exec("ALTER TABLE event_registrations ADD COLUMN consent TINYINT(1) NOT NULL DEFAULT 0 AFTER status");
  }
} catch (Throwable $e) {
  // ignore registrations migration errors
}

// Ensure events table has capacity column
try {
  $evtTable = db()->query("SHOW TABLES LIKE 'events'")->fetchColumn();
  if ($evtTable) {
    $evtCols = db()->query("SHOW COLUMNS FROM events")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('capacity', $evtCols)) {
      db()->exec("ALTER TABLE events ADD COLUMN capacity INT NULL AFTER enabled");
    }
  }
} catch (Throwable $e) {
  // ignore events capacity migration errors
}

// Seed default reliable French RSS sources if none exist
try {
  $rssTable = db()->query("SHOW TABLES LIKE 'rss_sources'")->fetchColumn();
  if ($rssTable) {
    $rcount = (int)db()->query('SELECT COUNT(*) FROM rss_sources')->fetchColumn();
    if ($rcount === 0) {
      $sources = [
        ['Gouvernement français', 'https://www.gouvernement.fr/actualites.rss'],
        ['France 24', 'https://www.france24.com/fr/rss'],
        ['France Info', 'https://www.francetvinfo.fr/titres.rss'],
        ['Le Monde', 'https://www.lemonde.fr/rss/une.xml'],
        ['TV5 Monde', 'https://information.tv5monde.com/rss'],
      ];
      $ins = db()->prepare('INSERT INTO rss_sources(name, url, enabled, created_at) VALUES(?,?,1,NOW())');
      foreach ($sources as $s) {
        $ins->execute([$s[0], $s[1]]);
      }
    }
  }
} catch (Throwable $e) {
  // ignore rss seed errors
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

// Visits analytics table (création progressive)
try {
  $visTable = db()->query("SHOW TABLES LIKE 'visits'")->fetchColumn();
  if (!$visTable) {
    db()->exec(
      "CREATE TABLE visits (
        id INT AUTO_INCREMENT PRIMARY KEY,
        path VARCHAR(500) NOT NULL,
        ip VARCHAR(64) NULL,
        port INT NULL,
        user_agent VARCHAR(500) NULL,
        referrer VARCHAR(500) NULL,
        country VARCHAR(100) NULL,
        city VARCHAR(150) NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_path (path),
        INDEX idx_created (created_at)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
  } else {
    // Add missing columns if we evolve schema later
    $cols = db()->query("SHOW COLUMNS FROM visits")->fetchAll(PDO::FETCH_COLUMN);
    $addV = function ($sql) {
      db()->exec($sql);
    };
    if (!in_array('country', $cols)) $addV("ALTER TABLE visits ADD COLUMN country VARCHAR(100) NULL AFTER referrer");
    if (!in_array('city', $cols)) $addV("ALTER TABLE visits ADD COLUMN city VARCHAR(150) NULL AFTER country");
  }
} catch (Throwable $e) { /* ignore visits migration */
}

// Translations table (multilingue)
try {
  $trTable = db()->query("SHOW TABLES LIKE 'translations'")->fetchColumn();
  if (!$trTable) {
    db()->exec(
      "CREATE TABLE translations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        lang VARCHAR(10) NOT NULL,
        `key` VARCHAR(200) NOT NULL,
        `value` TEXT NOT NULL,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_lang_key (lang, `key`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
    // Seed basic keys for FR/EN
    $seed = db()->prepare('INSERT INTO translations(lang, `key`, `value`, updated_at) VALUES(?,?,?,NOW())');
    $pairs = [
      ['fr', 'nav.home', 'Accueil'],
      ['fr', 'nav.programmes', 'Programmes'],
      ['fr', 'nav.formations', 'Formations'],
      ['fr', 'nav.centres', 'Instituts & Centres'],
      ['fr', 'nav.campus', 'Campus'],
      ['fr', 'nav.partners', 'Partenaires'],
      ['fr', 'nav.news', 'Actualités'],
      ['fr', 'nav.alumni', 'Alumni'],
      ['fr', 'nav.contact', 'Contact'],
      ['fr', 'nav.gallery', 'Galerie'],
      ['en', 'nav.home', 'Home'],
      ['en', 'nav.programmes', 'Programs'],
      ['en', 'nav.formations', 'Trainings'],
      ['en', 'nav.centres', 'Institutes & Centers'],
      ['en', 'nav.campus', 'Campus'],
      ['en', 'nav.partners', 'Partners'],
      ['en', 'nav.news', 'News'],
      ['en', 'nav.alumni', 'Alumni'],
      ['en', 'nav.contact', 'Contact'],
      ['en', 'nav.gallery', 'Gallery'],
    ];
    foreach ($pairs as $p) {
      try {
        $seed->execute($p);
      } catch (Throwable $e) { /* ignore dup */
      }
    }
  }
} catch (Throwable $e) { /* ignore translations migration */
}

// Languages table (enabled locales managed in admin)
try {
  $lgTable = db()->query("SHOW TABLES LIKE 'languages'")->fetchColumn();
  if (!$lgTable) {
    db()->exec(
      "CREATE TABLE languages (
      id INT AUTO_INCREMENT PRIMARY KEY,
      code VARCHAR(10) NOT NULL,
      name VARCHAR(100) NOT NULL,
      flag VARCHAR(50) NULL,
      flag_url VARCHAR(300) NULL,
      enabled TINYINT(1) NOT NULL DEFAULT 1,
      is_default TINYINT(1) NOT NULL DEFAULT 0,
      UNIQUE KEY uniq_code (code)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
    // seed FR/EN
    $ins = db()->prepare('INSERT INTO languages(code,name,flag,flag_url,enabled,is_default) VALUES(?,?,?,?,1,?)');
    $ins->execute(['fr', 'Français', 'fr', 'https://flagcdn.com/96x72/fr.png', 1]);
    $ins->execute(['en', 'English', 'gb', 'https://flagcdn.com/96x72/gb.png', 0]);
  }
  // Ensure columns and seed/update FR/EN flags if missing
  $cols = db()->query("SHOW COLUMNS FROM languages")->fetchAll(PDO::FETCH_COLUMN);
  if ($cols && !in_array('flag_url', $cols)) {
    db()->exec("ALTER TABLE languages ADD COLUMN flag_url VARCHAR(300) NULL AFTER flag");
  }
  if ($cols && !in_array('is_default', $cols)) {
    db()->exec("ALTER TABLE languages ADD COLUMN is_default TINYINT(1) NOT NULL DEFAULT 0 AFTER enabled");
  }
  // Upsert FR/EN with flag_url and defaults
  $up = db()->prepare('INSERT INTO languages(code,name,flag,flag_url,enabled,is_default) VALUES(?,?,?,?,1,?) ON DUPLICATE KEY UPDATE name=VALUES(name), flag=VALUES(flag), flag_url=IF(VALUES(flag_url) IS NOT NULL, VALUES(flag_url), flag_url), enabled=1');
  $up->execute(['fr', 'Français', 'fr', 'https://flagcdn.com/96x72/fr.png', 1]);
  $up->execute(['en', 'English', 'gb', 'https://flagcdn.com/96x72/gb.png', 0]);
  // Set default FR
  db()->exec("UPDATE languages SET is_default=1 WHERE code='fr'");
} catch (Throwable $e) { /* ignore languages migration */
}

// Enregistrer la visite courante (avant instanciation Router pour simplicité)
// Assurer les clés de traduction essentielles FR/EN (idempotent)
try {
  $pairs = [
    ['fr', 'nav.institut', 'L’Institut'],
    ['fr', 'nav.programmes', 'Programmes'],
    ['fr', 'nav.formations', 'Formations'],
    ['fr', 'nav.centres', 'Centres'],
    ['fr', 'nav.campus', 'Campus'],
    ['fr', 'nav.partners', 'Partenaires'],
    ['fr', 'nav.news', 'Actualités'],
    ['fr', 'nav.alumni', 'Alumni'],
    ['fr', 'nav.contact', 'Contact'],
    ['fr', 'nav.gallery', 'Galerie'],
    ['fr', 'btn.login', 'Se connecter'],
    ['fr', 'btn.newsletter', 'Newsletter'],
    ['en', 'nav.institut', 'Institute'],
    ['en', 'nav.programmes', 'Programs'],
    ['en', 'nav.formations', 'Trainings'],
    ['en', 'nav.centres', 'Centers'],
    ['en', 'nav.campus', 'Campus'],
    ['en', 'nav.partners', 'Partners'],
    ['en', 'nav.news', 'News'],
    ['en', 'nav.alumni', 'Alumni'],
    ['en', 'nav.contact', 'Contact'],
    ['en', 'nav.gallery', 'Gallery'],
    ['en', 'btn.login', 'Sign in'],
    ['en', 'btn.newsletter', 'Newsletter'],
    // Pages titles & subtitles
    ['fr', 'page.news.title', 'Actualités'],
    ['en', 'page.news.title', 'News'],
    ['fr', 'page.programmes.title', 'Programmes'],
    ['en', 'page.programmes.title', 'Programs'],
    ['fr', 'page.centres.title', 'Instituts & Centres IFMAP'],
    ['en', 'page.centres.title', 'Institutes & Centers IFMAP'],
    ['fr', 'page.centres.subtitle', "Découvrez nos pôles d'excellence et d'innovation."],
    ['en', 'page.centres.subtitle', 'Discover our hubs of excellence and innovation.'],
    ['fr', 'page.formations.title', 'Formations IFMAP'],
    ['en', 'page.formations.title', 'IFMAP Trainings'],
    ['fr', 'page.formations.subtitle', 'Des formations professionnalisantes adaptées au marché africain.'],
    ['en', 'page.formations.subtitle', 'Professional trainings adapted to the African market.'],
    ['fr', 'page.partners.title', 'Partenaires'],
    ['en', 'page.partners.title', 'Partners'],
    ['fr', 'page.gallery.title', 'Galerie'],
    ['en', 'page.gallery.title', 'Gallery'],
    ['fr', 'page.institut.title', "L'Institut IFMAP"],
    ['en', 'page.institut.title', 'IFMAP Institute'],
    ['fr', 'page.campus.title', 'Campus'],
    ['en', 'page.campus.title', 'Campus'],
    ['fr', 'page.campus.status', 'Projet en cours'],
    ['en', 'page.campus.status', 'Work in progress'],
    ['fr', 'page.alumni.title', 'Alumni'],
    ['en', 'page.alumni.title', 'Alumni'],
    ['fr', 'page.contact.title', 'Contact'],
    ['en', 'page.contact.title', 'Contact'],
    ['fr', 'page.event_register.title', 'Inscription'],
    ['en', 'page.event_register.title', 'Registration'],
    ['fr', 'page.event_register.title_prefix', 'Inscription –'],
    ['en', 'page.event_register.title_prefix', 'Registration –'],
    // Forms & common labels/messages
    ['fr', 'form.contact.success', 'Message envoyé. Merci !'],
    ['en', 'form.contact.success', 'Message sent. Thank you!'],
    ['fr', 'form.event.name_required', 'Veuillez renseigner votre nom.'],
    ['en', 'form.event.name_required', 'Please enter your name.'],
    ['fr', 'form.event.full', 'Désolé, cet événement est complet.'],
    ['en', 'form.event.full', 'Sorry, this event is full.'],
    ['fr', 'form.event.success', 'Inscription enregistrée. Merci !'],
    ['en', 'form.event.success', 'Registration submitted. Thank you!'],
    ['fr', 'form.common.yes', 'Oui'],
    ['en', 'form.common.yes', 'Yes'],
    ['fr', 'form.common.no', 'Non'],
    ['en', 'form.common.no', 'No'],
    // Newsletter messages
    ['fr', 'newsletter.success', 'Inscription réussie'],
    ['en', 'newsletter.success', 'Subscription successful'],
    ['fr', 'newsletter.already', 'Cette adresse est déjà inscrite'],
    ['en', 'newsletter.already', 'This email is already subscribed'],
    ['fr', 'newsletter.invalid', 'Adresse email invalide'],
    ['en', 'newsletter.invalid', 'Invalid email address'],
    // Errors
    ['fr', 'errors.article_not_found', 'Article introuvable'],
    ['en', 'errors.article_not_found', 'Article not found'],
    ['fr', 'errors.event_not_found', 'Événement introuvable'],
    ['en', 'errors.event_not_found', 'Event not found'],
    // Emails templates
    ['fr', 'email.event.confirmation_subject', 'Confirmation inscription –'],
    ['en', 'email.event.confirmation_subject', 'Registration confirmation –'],
    ['fr', 'email.event.confirmation_body', "Bonjour {{name}},\n\nVotre inscription à l'événement \"{{event}}\" est enregistrée (statut: en attente).\nNous vous confirmerons prochainement.\n\nCordialement,\nIFMAP"],
    ['en', 'email.event.confirmation_body', "Hello {{name}},\n\nYour registration to the event \"{{event}}\" has been recorded (status: pending).\nWe will confirm shortly.\n\nBest regards,\nIFMAP"],
    ['fr', 'email.event.admin_subject', 'Nouvelle inscription événement'],
    ['en', 'email.event.admin_subject', 'New event registration'],
    ['fr', 'email.event.admin_body', "Événement: {{event}}\nNom: {{name}}\nEmail: {{email}}\nTéléphone: {{phone}}\nConsentement: {{consent}}\nMessage:\n{{message}}"],
    ['en', 'email.event.admin_body', "Event: {{event}}\nName: {{name}}\nEmail: {{email}}\nPhone: {{phone}}\nConsent: {{consent}}\nMessage:\n{{message}}"],
  ];
  $up = db()->prepare('INSERT INTO translations(lang, `key`, `value`, updated_at) VALUES(?,?,?,NOW()) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)');
  foreach ($pairs as $p) {
    try {
      $up->execute($p);
    } catch (Throwable $e) { /* ignore */
    }
  }
} catch (Throwable $e) { /* ignore seed */
}

try {
  $reqUri = $_SERVER['REQUEST_URI'] ?? '/';
  $path = parse_url($reqUri, PHP_URL_PATH) ?: '/';
  $skip = false;
  // Exclure assets statiques & endpoints sensibles
  if (preg_match('#^/(assets|uploads)/#', $path)) $skip = true;
  if (preg_match('#\.(css|js|png|jpe?g|webp|gif|svg|ico)$#i', $path)) $skip = true;
  if (strpos($path, '/admin') === 0) { /* on garde les pages admin pour analytics globales ? */
  }
  if (!$skip) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $port = isset($_SERVER['REMOTE_PORT']) ? (int)$_SERVER['REMOTE_PORT'] : null;
    $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 490);
    $ref = substr($_SERVER['HTTP_REFERER'] ?? '', 0, 490);
    // Détection pays basique: Cloudflare header ou 1er code langue
    $country = $_SERVER['HTTP_CF_IPCOUNTRY'] ?? null;
    if (!$country) {
      $al = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
      $cc = substr($al, 0, 2);
      if ($cc) $country = strtoupper($cc);
    }
    $city = null; // Pas de géolocalisation fine pour l'instant
    $ins = db()->prepare('INSERT INTO visits(path, ip, port, user_agent, referrer, country, city) VALUES(?,?,?,?,?,?,?)');
    $ins->execute([$path, $ip, $port, $ua, $ref, $country, $city]);
  }
} catch (Throwable $e) { /* ignore visit logging */
}

// Router
$router = new Router();

// Routes (GET)
$router->get('/', fn() => (new HomeController())->index());
$router->get('/temoignages', fn() => view('public/testimonials', [
  'title' => 'Témoignages',
  'rows' => db()->query("SELECT * FROM testimonials WHERE COALESCE(status,'pending')='approved' ORDER BY id DESC")->fetchAll()
]));
$router->get('/evenements/ics', function () {
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id <= 0) {
    http_response_code(400);
    echo 'Invalid event id';
    return '';
  }
  $st = db()->prepare('SELECT * FROM events WHERE id = ?');
  $st->execute([$id]);
  $e = $st->fetch();
  if (!$e) {
    http_response_code(404);
    echo 'Event not found';
    return '';
  }

  $title = $e['title'] ?? 'Événement IFMAP';
  $desc = trim(($e['description'] ?? ''));
  $loc  = trim(($e['location'] ?? 'IFMAP'));
  $date = strtotime($e['event_date']);
  if (!$date) $date = time();

  // Événement journée entière : DTSTART/DTEND en format DATE
  $dtStart = gmdate('Ymd', $date);
  $dtEnd   = gmdate('Ymd', strtotime('+1 day', $date));
  $dtStamp = gmdate('Ymd\THis\Z');
  $uidHost = parse_url(base_url('/'), PHP_URL_HOST) ?: 'ifmap.ci';
  $uid = 'ifmap-' . $id . '@' . $uidHost;

  $lines = [
    'BEGIN:VCALENDAR',
    'VERSION:2.0',
    'PRODID:-//IFMAP//Events//FR',
    'CALSCALE:GREGORIAN',
    'METHOD:PUBLISH',
    'BEGIN:VEVENT',
    'DTSTAMP:' . $dtStamp,
    'UID:' . $uid,
    'SUMMARY:' . str_replace(["\n", "\r"], ' ', $title),
    'DTSTART;VALUE=DATE:' . $dtStart,
    'DTEND;VALUE=DATE:' . $dtEnd,
    'DESCRIPTION:' . str_replace(["\n", "\r"], ' ', $desc),
    'LOCATION:' . str_replace(["\n", "\r"], ' ', $loc),
  ];
  if (!empty($e['cta_url'])) {
    $lines[] = 'URL:' . $e['cta_url'];
  }
  $lines[] = 'END:VEVENT';
  $lines[] = 'END:VCALENDAR';
  $ics = implode("\r\n", $lines) . "\r\n";

  header('Content-Type: text/calendar; charset=utf-8');
  header('Content-Disposition: attachment; filename="event-' . $id . '.ics"');
  echo $ics;
  return '';
});
$router->get('/evenements/inscription', function () {
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id <= 0) return view('errors/404', ['title' => t('errors.event_not_found')]);
  $st = db()->prepare('SELECT * FROM events WHERE id=?');
  $st->execute([$id]);
  $event = $st->fetch();
  if (!$event) return view('errors/404', ['title' => t('errors.event_not_found')]);
  return view('public/event_register', ['title' => t('page.event_register.title_prefix') . ' ' . $event['title'], 'event' => $event]);
});
$router->post('/evenements/inscription', function () {
  require_csrf();
  $id = (int)($_POST['event_id'] ?? 0);
  $st = db()->prepare('SELECT id,title FROM events WHERE id=?');
  $st->execute([$id]);
  $event = $st->fetch();
  if (!$event) {
    $error = t('errors.event_not_found');
    return view('public/event_register', ['title' => t('page.event_register.title'), 'error' => $error, 'event' => null]);
  }
  $name = substr(trim($_POST['name'] ?? ''), 0, 190);
  $email = substr(trim($_POST['email'] ?? ''), 0, 190);
  $phone = substr(trim($_POST['phone'] ?? ''), 0, 60);
  $message = trim($_POST['message'] ?? '');
  $consent = isset($_POST['consent']) ? 1 : 0;
  if ($name === '') {
    $error = t('form.event.name_required');
    return view('public/event_register', ['title' => t('page.event_register.title_prefix') . ' ' . $event['title'], 'error' => $error, 'event' => $event]);
  }
  // Capacity check
  $evtInfo = db()->prepare('SELECT capacity FROM events WHERE id=?');
  $evtInfo->execute([$event['id']]);
  $capRow = $evtInfo->fetch();
  $capacity = $capRow && $capRow['capacity'] !== null ? (int)$capRow['capacity'] : null;
  if ($capacity !== null) {
    $cntStmt = db()->prepare("SELECT COUNT(*) FROM event_registrations WHERE event_id=? AND status!='cancelled'");
    $cntStmt->execute([$event['id']]);
    $current = (int)$cntStmt->fetchColumn();
    if ($current >= $capacity) {
      $error = t('form.event.full');
      return view('public/event_register', ['title' => t('page.event_register.title_prefix') . ' ' . $event['title'], 'error' => $error, 'event' => $event]);
    }
  }
  $ins = db()->prepare('INSERT INTO event_registrations(event_id,name,email,phone,message,status,consent) VALUES(?,?,?,?,?,?,?)');
  $ins->execute([$event['id'], $name, $email, $phone, $message, 'pending', $consent]);
  $regId = db()->lastInsertId();
  // Emails (best effort)
  if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $subject = t('email.event.confirmation_subject') . ' ' . $event['title'];
    $body = str_replace(['{{name}}', '{{event}}'], [$name, $event['title']], t('email.event.confirmation_body'));
    @mail($email, $subject, $body);
  }
  $adminEmail = (function () {
    $cfg = config();
    return $cfg['admin_email'] ?? '';
  })();
  if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
    $subjectA = t('email.event.admin_subject') . ' #' . $event['id'];
    $bodyA = str_replace(['{{event}}', '{{name}}', '{{email}}', '{{phone}}', '{{consent}}', '{{message}}'], [
      $event['title'],
      $name,
      $email,
      $phone,
      ($consent ? t('form.common.yes') : t('form.common.no')),
      $message
    ], t('email.event.admin_body'));
    @mail($adminEmail, $subjectA, $bodyA);
  }
  $success = t('form.event.success');
  return view('public/event_register', ['title' => t('page.event_register.title_prefix') . ' ' . $event['title'], 'success' => $success, 'event' => $event]);
});
$router->post('/temoignages/soumettre', fn() => (new HomeController())->submitTestimonial());

// Auth
$router->get('/login', fn() => (new AuthController())->loginForm());
$router->post('/login', fn() => (new AuthController())->login());
$router->get('/logout', fn() => (new AuthController())->logout());

// Switch language (sets cookie and redirects)
$router->get('/lang', function () {
  $l = trim($_GET['l'] ?? $_GET['lang'] ?? '');
  if ($l !== '' && preg_match('/^[a-z]{2}(-[A-Z]{2})?$/', $l)) {
    set_lang_cookie($l);
  }
  $back = $_GET['back'] ?? '/';
  header('Location: ' . base_url($back));
  return '';
});

// Admin (protégé)
$router->get('/admin', fn() => require_auth(fn() => (new AdminController())->dashboard()));
$router->get('/admin/events', fn() => require_auth(fn() => (new AdminController())->eventsIndex()));
$router->get('/admin/events/create', fn() => require_auth(fn() => (new AdminController())->eventsForm()));
$router->post('/admin/events/create', fn() => require_auth(fn() => (new AdminController())->eventsStore()));
$router->get('/admin/events/edit', fn() => require_auth(fn() => (new AdminController())->eventsForm()));
$router->post('/admin/events/edit', fn() => require_auth(fn() => (new AdminController())->eventsUpdate()));
$router->get('/admin/events/delete', fn() => require_auth(fn() => (new AdminController())->eventsDelete()));
$router->get('/admin/events/toggle', fn() => require_auth(fn() => (new AdminController())->eventsToggle()));
$router->get('/admin/events/registrations', fn() => require_auth(fn() => (new AdminController())->eventRegistrationsIndex()));
$router->get('/admin/events/registrations/export', fn() => require_auth(fn() => (new AdminController())->eventRegistrationsExport()));
$router->get('/admin/events/registrations/status', fn() => require_auth(fn() => (new AdminController())->eventRegistrationStatus()));
$router->get('/admin/events/registrations/create', fn() => require_auth(fn() => (new AdminController())->eventRegistrationCreateForm()));
$router->post('/admin/events/registrations/create', fn() => require_auth(fn() => (new AdminController())->eventRegistrationStore()));
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
$router->get('/admin/partners/toggle', fn() => require_auth(fn() => (new AdminController())->partnersToggle()));
$router->get('/admin/media', fn() => require_auth(fn() => (new AdminController())->mediaIndex()));
$router->get('/admin/media/create', fn() => require_auth(fn() => (new AdminController())->mediaForm()));
$router->post('/admin/media/create', fn() => require_auth(fn() => (new AdminController())->mediaStore()));
$router->get('/admin/media/edit', fn() => require_auth(fn() => (new AdminController())->mediaForm()));
$router->post('/admin/media/edit', fn() => require_auth(fn() => (new AdminController())->mediaUpdate()));
$router->get('/admin/media/delete', fn() => require_auth(fn() => (new AdminController())->mediaDelete()));

// Admin sécurité
$router->get('/admin/password', fn() => require_auth(fn() => (new AdminController())->passwordForm()));
$router->post('/admin/password', fn() => require_auth(fn() => (new AdminController())->passwordUpdate()));

// Site settings (logo, contact, socials)
try {
  $setTable = db()->query("SHOW TABLES LIKE 'settings'")->fetchColumn();
  if (!$setTable) {
    db()->exec(
      "CREATE TABLE settings (
        id INT PRIMARY KEY DEFAULT 1,
        logo_url VARCHAR(500) NULL,
        contact_email VARCHAR(200) NULL,
        contact_phone VARCHAR(60) NULL,
        contact_address VARCHAR(300) NULL,
        link_programmes VARCHAR(300) NULL,
        link_formations VARCHAR(300) NULL,
        link_actualites VARCHAR(300) NULL,
        link_partenaires VARCHAR(300) NULL,
        social_facebook VARCHAR(300) NULL,
        social_linkedin VARCHAR(300) NULL,
        social_youtube VARCHAR(300) NULL,
        newsletter_text VARCHAR(500) NULL,
        newsletter_url VARCHAR(300) NULL,
        platform_url VARCHAR(300) NULL,
        updated_at DATETIME NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
    db()->exec("INSERT INTO settings(id, updated_at) VALUES(1, NOW())");
  }
} catch (Throwable $e) { /* ignore settings migration */

  // Newsletter subscriptions table
  try {
    $subTable = db()->query("SHOW TABLES LIKE 'newsletter_subscriptions'")->fetchColumn();
    if (!$subTable) {
      db()->exec(
        "CREATE TABLE newsletter_subscriptions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(200) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_email (email)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
      );
    }
  } catch (Throwable $e) { /* ignore newsletter migration */
  }
}

// Admin Témoignages (modération)
$router->get('/admin/testimonials', fn() => require_auth(fn() => (new AdminController())->testimonialsIndex()));
$router->get('/admin/testimonials/approve', fn() => require_auth(fn() => (new AdminController())->testimonialsApprove()));
$router->get('/admin/testimonials/reject', fn() => require_auth(fn() => (new AdminController())->testimonialsReject()));
$router->get('/admin/testimonials/delete', fn() => require_auth(fn() => (new AdminController())->testimonialsDelete()));

// Admin RSS sources
$router->get('/admin/rss-sources', fn() => require_auth(fn() => (new AdminController())->rssSourcesIndex()));
$router->get('/admin/rss-sources/create', fn() => require_auth(fn() => (new AdminController())->rssSourcesForm()));
$router->post('/admin/rss-sources/create', fn() => require_auth(fn() => (new AdminController())->rssSourcesStore()));
$router->get('/admin/rss-sources/edit', fn() => require_auth(fn() => (new AdminController())->rssSourcesForm()));
$router->post('/admin/rss-sources/edit', fn() => require_auth(fn() => (new AdminController())->rssSourcesUpdate()));
$router->get('/admin/rss-sources/toggle', fn() => require_auth(fn() => (new AdminController())->rssSourcesToggle()));
$router->get('/admin/rss-sources/delete', fn() => require_auth(fn() => (new AdminController())->rssSourcesDelete()));
// Ingest RSS items into news
$router->post('/admin/rss-sources/ingest', fn() => require_auth(fn() => (new AdminController())->rssIngest()));

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
  'title' => t('page.news.title'),
  'items' => db()->query("SELECT * FROM news WHERE COALESCE(status,'published')='published' ORDER BY COALESCE(published_at, created_at) DESC")->fetchAll(),
  'rss' => (function () {
    // Load enabled RSS sources from DB with cache
    $rows = db()->query('SELECT url FROM rss_sources WHERE enabled=1 ORDER BY id DESC')->fetchAll();
    $items = [];
    $now = new DateTime('now');
    $ttlMinutes = 30; // cache TTL
    foreach ($rows as $r) {
      $url = $r['url'];
      // Try to read cache first
      $stc = db()->prepare('SELECT title, link, pub_date, description FROM rss_items_cache WHERE source_url=? AND expires_at > ? ORDER BY pub_date DESC LIMIT 6');
      $stc->execute([$url, $now->format('Y-m-d H:i:s')]);
      $cached = $stc->fetchAll();
      if (!empty($cached)) {
        foreach ($cached as $c) {
          $items[] = [
            'title' => $c['title'],
            'link' => $c['link'],
            'pubDate' => $c['pub_date'],
            'description' => $c['description'],
          ];
        }
        continue;
      }
      // Fetch and populate cache
      try {
        $xml = @simplexml_load_file($url);
        if ($xml && isset($xml->channel->item)) {
          $ins = db()->prepare('INSERT INTO rss_items_cache(source_url,title,link,pub_date,description,fetched_at,expires_at) VALUES(?,?,?,?,?,?,?)');
          $expires = (clone $now)->modify('+' . $ttlMinutes . ' minutes')->format('Y-m-d H:i:s');
          foreach ($xml->channel->item as $it) {
            $title = (string)$it->title;
            $link = (string)$it->link;
            $pub = (string)$it->pubDate;
            $pubDate = $pub ? date('Y-m-d H:i:s', strtotime($pub)) : null;
            $desc = strip_tags((string)$it->description);
            $ins->execute([$url, $title, $link, $pubDate, $desc, $now->format('Y-m-d H:i:s'), $expires]);
            $items[] = [
              'title' => $title,
              'link' => $link,
              'pubDate' => $pubDate,
              'description' => $desc,
            ];
          }
        }
      } catch (Throwable $e) { /* ignore */
      }
    }
    return array_slice($items, 0, 6);
  })()
]));

// English alias for news
$router->get('/news', fn() => view('public/news', [
  'title' => t('page.news.title'),
  'items' => db()->query("SELECT * FROM news WHERE COALESCE(status,'published')='published' ORDER BY COALESCE(published_at, created_at) DESC")->fetchAll(),
  'rss' => (function () {
    // Reuse same RSS block
    $rows = db()->query('SELECT url FROM rss_sources WHERE enabled=1 ORDER BY id DESC')->fetchAll();
    $items = [];
    $now = new DateTime('now');
    $ttlMinutes = 30;
    foreach ($rows as $r) {
      $url = $r['url'];
      $stc = db()->prepare('SELECT title, link, pub_date, description FROM rss_items_cache WHERE source_url=? AND expires_at > ? ORDER BY pub_date DESC LIMIT 6');
      $stc->execute([$url, $now->format('Y-m-d H:i:s')]);
      $cached = $stc->fetchAll();
      if (!empty($cached)) {
        foreach ($cached as $c) {
          $items[] = [
            'title' => $c['title'],
            'link' => $c['link'],
            'pubDate' => $c['pub_date'],
            'description' => $c['description'],
          ];
        }
        continue;
      }
      try {
        $xml = @simplexml_load_file($url);
        if ($xml && isset($xml->channel->item)) {
          $ins = db()->prepare('INSERT INTO rss_items_cache(source_url,title,link,pub_date,description,fetched_at,expires_at) VALUES(?,?,?,?,?,?,?)');
          $expires = (clone $now)->modify('+' . $ttlMinutes . ' minutes')->format('Y-m-d H:i:s');
          foreach ($xml->channel->item as $it) {
            $title = (string)$it->title;
            $link = (string)$it->link;
            $pub = (string)$it->pubDate;
            $pubDate = $pub ? date('Y-m-d H:i:s', strtotime($pub)) : null;
            $desc = strip_tags((string)$it->description);
            $ins->execute([$url, $title, $link, $pubDate, $desc, $now->format('Y-m-d H:i:s'), $expires]);
            $items[] = [
              'title' => $title,
              'link' => $link,
              'pubDate' => $pubDate,
              'description' => $desc,
            ];
          }
        }
      } catch (Throwable $e) {
      }
    }
    return array_slice($items, 0, 6);
  })()
]));
$router->get('/actualites/article', function () {
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id <= 0) return view('errors/404', ['title' => t('errors.article_not_found')]);
  $stmt = db()->prepare('SELECT * FROM news WHERE id = ?');
  $stmt->execute([$id]);
  $article = $stmt->fetch();
  if (!$article) return view('errors/404', ['title' => t('errors.article_not_found')]);
  return view('public/news_show', ['title' => $article['title'], 'article' => $article]);
});
$router->get('/programmes', fn() => view('public/programmes', [
  'title' => t('page.programmes.title'),
  'items' => db()->query('SELECT * FROM programmes ORDER BY id DESC')->fetchAll()
]));
$router->get('/programs', fn() => view('public/programmes', [
  'title' => t('page.programmes.title'),
  'items' => db()->query('SELECT * FROM programmes ORDER BY id DESC')->fetchAll()
]));
$router->get('/centres', fn() => view('public/centres', [
  'title' => (function () {
    $st = db()->prepare('SELECT title FROM sections WHERE `key`=?');
    $st->execute(['centres']);
    $t = $st->fetchColumn();
    return $t ?: t('page.centres.title');
  })(),
  'subtitle' => (function () {
    $st = db()->prepare('SELECT subtitle FROM sections WHERE `key`=?');
    $st->execute(['centres']);
    $t = $st->fetchColumn();
    return $t ?: t('page.centres.subtitle');
  })(),
  'items' => db()->query("SELECT * FROM centres WHERE COALESCE(status,'published')='published' ORDER BY id DESC")->fetchAll()
]));
$router->get('/centers', fn() => view('public/centres', [
  'title' => (function () {
    $st = db()->prepare('SELECT title FROM sections WHERE `key`=?');
    $st->execute(['centres']);
    $t = $st->fetchColumn();
    return $t ?: t('page.centres.title');
  })(),
  'subtitle' => (function () {
    $st = db()->prepare('SELECT subtitle FROM sections WHERE `key`=?');
    $st->execute(['centres']);
    $t = $st->fetchColumn();
    return $t ?: t('page.centres.subtitle');
  })(),
  'items' => db()->query("SELECT * FROM centres WHERE COALESCE(status,'published')='published' ORDER BY id DESC")->fetchAll()
]));
$router->get('/formations', fn() => view('public/formations', [
  'title' => (function () {
    $st = db()->prepare('SELECT title FROM sections WHERE `key`=?');
    $st->execute(['formations']);
    $t = $st->fetchColumn();
    return $t ?: t('page.formations.title');
  })(),
  'subtitle' => (function () {
    $st = db()->prepare('SELECT subtitle FROM sections WHERE `key`=?');
    $st->execute(['formations']);
    $t = $st->fetchColumn();
    return $t ?: t('page.formations.subtitle');
  })(),
  'items' => db()->query("SELECT * FROM formations WHERE COALESCE(status,'published')='published' ORDER BY id DESC")->fetchAll()
]));
$router->get('/trainings', fn() => view('public/formations', [
  'title' => (function () {
    $st = db()->prepare('SELECT title FROM sections WHERE `key`=?');
    $st->execute(['formations']);
    $t = $st->fetchColumn();
    return $t ?: t('page.formations.title');
  })(),
  'subtitle' => (function () {
    $st = db()->prepare('SELECT subtitle FROM sections WHERE `key`=?');
    $st->execute(['formations']);
    $t = $st->fetchColumn();
    return $t ?: t('page.formations.subtitle');
  })(),
  'items' => db()->query("SELECT * FROM formations WHERE COALESCE(status,'published')='published' ORDER BY id DESC")->fetchAll()
]));
$router->get('/partenaires', fn() => view('public/partners', [
  'title' => t('page.partners.title'),
  'items' => db()->query('SELECT * FROM partners WHERE COALESCE(enabled,1)=1 ORDER BY id DESC')->fetchAll()
]));
$router->get('/partners', fn() => view('public/partners', [
  'title' => t('page.partners.title'),
  'items' => db()->query('SELECT * FROM partners WHERE COALESCE(enabled,1)=1 ORDER BY id DESC')->fetchAll()
]));
$router->get('/galerie', fn() => view('public/gallery', [
  'title' => t('page.gallery.title'),
  'media' => db()->query('SELECT * FROM media ORDER BY id DESC')->fetchAll()
]));
$router->get('/gallery', fn() => view('public/gallery', [
  'title' => t('page.gallery.title'),
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
  'title' => t('page.institut.title')
]));
$router->get('/institute', fn() => view('public/institut', [
  'title' => t('page.institut.title')
]));

$router->get('/campus', fn() => view('public/campus', [
  'title' => t('page.campus.title'),
  'status' => t('page.campus.status'),
]));

$router->get('/alumni', fn() => view('public/alumni', [
  'title' => t('page.alumni.title')
]));
$router->get('/contact', fn() => view('public/contact', [
  'title' => t('page.contact.title')
]));
$router->get('/home', fn() => (new HomeController())->index());

$router->get('/contact', fn() => view('public/contact', [
  'title' => t('page.contact.title')
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
  $success = t('form.contact.success');
  return view('public/contact', ['title' => t('page.contact.title'), 'success' => $success]);
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

// Admin Settings
$router->get('/admin/settings', fn() => require_auth(fn() => (new AdminController())->settingsForm()));
$router->post('/admin/settings', fn() => require_auth(fn() => (new AdminController())->settingsSave()));

// Admin Languages
$router->get('/admin/languages', fn() => require_auth(fn() => (new AdminController())->languagesIndex()));
$router->post('/admin/languages/create', fn() => require_auth(fn() => (new AdminController())->languagesStore()));
$router->get('/admin/languages/edit', fn() => require_auth(fn() => (new AdminController())->languagesForm()));
$router->post('/admin/languages/edit', fn() => require_auth(fn() => (new AdminController())->languagesUpdate()));
$router->get('/admin/languages/toggle', fn() => require_auth(fn() => (new AdminController())->languagesToggle()));
$router->get('/admin/languages/default', fn() => require_auth(fn() => (new AdminController())->languagesSetDefault()));

// Admin Analytics (visites)
$router->get('/admin/analytics/visits', fn() => require_auth(fn() => (new AdminController())->analyticsVisits()));
$router->get('/admin/analytics/visits/export.csv', fn() => require_auth(fn() => (new AdminController())->analyticsVisitsExport()));

// Admin Translations
$router->get('/admin/translations', fn() => require_auth(fn() => (new AdminController())->translationsIndex()));
$router->post('/admin/translations/create', fn() => require_auth(fn() => (new AdminController())->translationsStore()));
$router->post('/admin/translations/update', fn() => require_auth(fn() => (new AdminController())->translationsUpdate()));
$router->get('/admin/translations/delete', fn() => require_auth(fn() => (new AdminController())->translationsDelete()));
$router->get('/admin/translations/export.csv', fn() => require_auth(fn() => (new AdminController())->translationsExportCsv()));
$router->post('/admin/translations/import.csv', fn() => require_auth(fn() => (new AdminController())->translationsImportCsv()));

// Newsletter subscribe endpoint
$router->post('/newsletter/subscribe', function () {
  require_csrf();
  $email = substr(trim($_POST['email'] ?? ''), 0, 200);
  $ok = false;
  $msg = '';
  if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    try {
      $st = db()->prepare('INSERT INTO newsletter_subscriptions(email) VALUES(?)');
      $st->execute([$email]);
      $ok = true;
      $msg = t('newsletter.success');
    } catch (Throwable $e) {
      $msg = t('newsletter.already');
    }
  } else {
    $msg = t('newsletter.invalid');
  }
  header('Content-Type: application/json');
  echo json_encode(['ok' => $ok, 'message' => $msg]);
  return '';
});

// Admin: Newsletter listing & export
$router->get('/admin/newsletter', fn() => require_auth(fn() => (new AdminController())->newsletterIndex()));
$router->get('/admin/newsletter/export.csv', fn() => require_auth(fn() => (new AdminController())->newsletterExportCsv()));

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

// i18n JSON endpoint: expose translations for a given lang
$router->get('/i18n.json', function () {
  $lang = trim($_GET['lang'] ?? resolve_lang());
  header('Content-Type: application/json');
  try {
    $st = db()->prepare('SELECT `key`, `value` FROM translations WHERE lang=? ORDER BY `key` ASC');
    $st->execute([$lang]);
    $rows = $st->fetchAll();
    $out = [];
    foreach ($rows as $r) {
      $out[$r['key']] = $r['value'];
    }
    echo json_encode(['lang' => $lang, 'translations' => $out]);
  } catch (Throwable $e) {
    echo json_encode(['lang' => $lang, 'translations' => []]);
  }
  return '';
});

// Dispatch
$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
