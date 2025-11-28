<?php

namespace App\Controllers;

class AdminController
{
    public function dashboard(): string
    {
        $title = 'Admin – Tableau de bord';
        // Metrics
        $totalEvents = 0;
        $confirmedRegs = 0;
        $remainingPlaces = 0;
        $pendingTestimonials = 0;
        $publishedNews = 0;
        try {
            $totalEvents = (int)db()->query("SELECT COUNT(*) FROM events")->fetchColumn();
        } catch (\Throwable $e) {
        }
        try {
            $confirmedRegs = (int)db()->query("SELECT COUNT(*) FROM event_registrations WHERE status='confirmed'")->fetchColumn();
        } catch (\Throwable $e) {
        }
        try {
            $pendingTestimonials = (int)db()->query("SELECT COUNT(*) FROM testimonials WHERE COALESCE(status,'pending')='pending'")->fetchColumn();
        } catch (\Throwable $e) {
        }
        try {
            $publishedNews = (int)db()->query("SELECT COUNT(*) FROM news WHERE COALESCE(status,'published')='published'")->fetchColumn();
        } catch (\Throwable $e) {
        }
        // Remaining places aggregate: sum over events with capacity of (capacity - non-cancelled registrations)
        try {
            $evtRows = db()->query("SELECT id, capacity FROM events WHERE capacity IS NOT NULL AND capacity > 0")->fetchAll();
            foreach ($evtRows as $er) {
                $cap = (int)$er['capacity'];
                $usedStmt = db()->prepare("SELECT COUNT(*) FROM event_registrations WHERE event_id=? AND status!='cancelled'");
                $usedStmt->execute([$er['id']]);
                $used = (int)$usedStmt->fetchColumn();
                $remainingPlaces += max($cap - $used, 0);
            }
        } catch (\Throwable $e) {
        }
        return view('admin/dashboard', compact('title', 'totalEvents', 'confirmedRegs', 'remainingPlaces', 'pendingTestimonials', 'publishedNews'));
    }

    // Events (Événements)
    public function eventsIndex(): string
    {
        $items = db()->query('SELECT * FROM events ORDER BY event_date DESC, id DESC')->fetchAll();
        // Attach registration counts for quick overview
        try {
            $counts = db()->query('SELECT event_id, COUNT(*) AS c FROM event_registrations GROUP BY event_id')->fetchAll();
            $map = [];
            foreach ($counts as $r) {
                $map[$r['event_id']] = $r['c'];
            }
            foreach ($items as &$it) {
                $it['registrations_count'] = $map[$it['id']] ?? 0;
            }
            unset($it);
        } catch (\Throwable $e) { /* ignore */
        }
        $title = 'Admin – Événements';
        return view('admin/events/index', compact('title', 'items'));
    }
    public function eventsForm(): string
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $item = null;
        if ($id) {
            $st = db()->prepare('SELECT * FROM events WHERE id=?');
            $st->execute([$id]);
            $item = $st->fetch();
        }
        $title = $id ? 'Modifier Événement' : 'Créer Événement';
        return view('admin/events/form', compact('title', 'item'));
    }
    public function eventsStore(): string
    {
        require_csrf();
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_date = trim($_POST['event_date'] ?? '');
        $category = substr(trim($_POST['category'] ?? ''), 0, 191);
        $language = substr(trim($_POST['language'] ?? ''), 0, 64);
        $program = substr(trim($_POST['program'] ?? ''), 0, 191);
        $location = substr(trim($_POST['location'] ?? ''), 0, 191);
        $cta_url = substr(trim($_POST['cta_url'] ?? ''), 0, 255);
        $status = in_array($_POST['status'] ?? 'draft', ['draft', 'published']) ? $_POST['status'] : 'draft';
        $publish_at = trim($_POST['publish_at'] ?? '') ?: null;
        $enabled = isset($_POST['enabled']) ? 1 : 0;
        $capacity = (int)($_POST['capacity'] ?? 0);
        $st = db()->prepare('INSERT INTO events(title, description, status, publish_at, event_date, category, language, program, location, cta_url, enabled, capacity) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)');
        $st->execute([$title, $description, $status, $publish_at, $event_date, $category, $language, $program, $location, $cta_url, $enabled, $capacity]);
        header('Location: ' . base_url('/admin/events'));
        return '';
    }
    public function eventsUpdate(): string
    {
        require_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_date = trim($_POST['event_date'] ?? '');
        $category = substr(trim($_POST['category'] ?? ''), 0, 191);
        $language = substr(trim($_POST['language'] ?? ''), 0, 64);
        $program = substr(trim($_POST['program'] ?? ''), 0, 191);
        $location = substr(trim($_POST['location'] ?? ''), 0, 191);
        $cta_url = substr(trim($_POST['cta_url'] ?? ''), 0, 255);
        $status = in_array($_POST['status'] ?? 'draft', ['draft', 'published']) ? $_POST['status'] : 'draft';
        $publish_at = trim($_POST['publish_at'] ?? '') ?: null;
        $enabled = isset($_POST['enabled']) ? 1 : 0;
        $capacity = (int)($_POST['capacity'] ?? 0);
        if ($id) {
            $st = db()->prepare('UPDATE events SET title=?, description=?, status=?, publish_at=?, event_date=?, category=?, language=?, program=?, location=?, cta_url=?, enabled=?, capacity=? WHERE id=?');
            $st->execute([$title, $description, $status, $publish_at, $event_date, $category, $language, $program, $location, $cta_url, $enabled, $capacity, $id]);
        }
        header('Location: ' . base_url('/admin/events'));
        return '';
    }
    public function eventRegistrationStatus(): string
    {
        $regId = (int)($_GET['reg_id'] ?? 0);
        $status = $_GET['status'] ?? '';
        if ($regId > 0 && in_array($status, ['confirmed', 'cancelled'])) {
            $st = db()->prepare('UPDATE event_registrations SET status=? WHERE id=?');
            $st->execute([$status, $regId]);
            // Notify admin on confirmation
            if ($status === 'confirmed') {
                try {
                    $rowSt = db()->prepare('SELECT er.*, e.title FROM event_registrations er JOIN events e ON e.id=er.event_id WHERE er.id=?');
                    $rowSt->execute([$regId]);
                    $r = $rowSt->fetch();
                    $adminEmail = (function () {
                        $cfg = config();
                        return $cfg['admin_email'] ?? '';
                    })();
                    if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                        $subject = 'Inscription confirmée – ' . ($r['title'] ?? 'Événement');
                        $body = "Événement: " . ($r['title'] ?? '') . "\nNom: " . ($r['name'] ?? '') . "\nEmail: " . ($r['email'] ?? '') . "\nTéléphone: " . ($r['phone'] ?? '') . "\nMessage:\n" . ($r['message'] ?? '');
                        @mail($adminEmail, $subject, $body);
                    }
                } catch (\Throwable $e) { /* ignore */
                }
            }
        }
        $back = $_SERVER['HTTP_REFERER'] ?? base_url('admin/events/registrations');
        header('Location: ' . $back);
        return '';
    }
    public function eventRegistrationCreateForm(): string
    {
        $events = [];
        try {
            $events = db()->query("SELECT id,title,event_date FROM events ORDER BY event_date DESC LIMIT 200")->fetchAll();
        } catch (\Throwable $e) {
        }
        $title = 'Ajouter une inscription';
        return view('admin/events/registration_create', compact('title', 'events'));
    }
    public function eventRegistrationStore(): string
    {
        require_csrf();
        $event_id = (int)($_POST['event_id'] ?? 0);
        $name = substr(trim($_POST['name'] ?? ''), 0, 190);
        $email = substr(trim($_POST['email'] ?? ''), 0, 190);
        $phone = substr(trim($_POST['phone'] ?? ''), 0, 60);
        $message = trim($_POST['message'] ?? '');
        $status = in_array($_POST['status'] ?? 'pending', ['pending', 'confirmed', 'cancelled']) ? $_POST['status'] : 'pending';
        $consent = isset($_POST['consent']) ? 1 : 0;
        // Basic validation
        $evtStmt = db()->prepare('SELECT id,title,capacity FROM events WHERE id=?');
        $evtStmt->execute([$event_id]);
        $evt = $evtStmt->fetch();
        if (!$evt || $name === '') {
            $error = 'Événement invalide ou nom requis.';
            $events = db()->query("SELECT id,title,event_date FROM events ORDER BY event_date DESC LIMIT 200")->fetchAll();
            $title = 'Ajouter une inscription';
            return view('admin/events/registration_create', compact('title', 'events', 'error'));
        }
        // Capacity check if confirming directly
        if ($evt['capacity'] !== null && $evt['capacity'] !== '' && $status !== 'cancelled') {
            $cap = (int)$evt['capacity'];
            $usedStmt = db()->prepare("SELECT COUNT(*) FROM event_registrations WHERE event_id=? AND status!='cancelled'");
            $usedStmt->execute([$evt['id']]);
            $used = (int)$usedStmt->fetchColumn();
            if ($used >= $cap) {
                $error = 'Capacité atteinte pour cet événement.';
                $events = db()->query("SELECT id,title,event_date FROM events ORDER BY event_date DESC LIMIT 200")->fetchAll();
                $title = 'Ajouter une inscription';
                return view('admin/events/registration_create', compact('title', 'events', 'error'));
            }
        }
        $ins = db()->prepare('INSERT INTO event_registrations(event_id,name,email,phone,message,status,consent) VALUES(?,?,?,?,?,?,?)');
        $ins->execute([$evt['id'], $name, $email, $phone, $message, $status, $consent]);
        header('Location: ' . base_url('admin/events/registrations?event_id=' . $evt['id']));
        return '';
    }
    public function eventsDelete(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare('DELETE FROM events WHERE id=?');
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/events'));
        return '';
    }

    public function eventsToggle(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare('UPDATE events SET enabled=1-enabled WHERE id=?');
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/events'));
        return '';
    }

    // Event registrations listing with optional date filters
    public function eventRegistrationsIndex(): string
    {
        $eventId = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
        $start = trim($_GET['start'] ?? '');
        $end = trim($_GET['end'] ?? '');
        $event = null;
        $items = [];
        if ($eventId > 0) {
            $st = db()->prepare('SELECT * FROM events WHERE id=?');
            $st->execute([$eventId]);
            $event = $st->fetch();
            if ($event) {
                $sql = 'SELECT * FROM event_registrations WHERE event_id=?';
                $params = [$eventId];
                if ($start !== '') {
                    $sql .= ' AND DATE(created_at) >= ?';
                    $params[] = $start;
                }
                if ($end !== '') {
                    $sql .= ' AND DATE(created_at) <= ?';
                    $params[] = $end;
                }
                $sql .= ' ORDER BY created_at DESC';
                $st2 = db()->prepare($sql);
                $st2->execute($params);
                $items = $st2->fetchAll();
            }
        } else {
            $sql = 'SELECT er.*, e.title FROM event_registrations er JOIN events e ON e.id=er.event_id WHERE 1';
            $params = [];
            if ($start !== '') {
                $sql .= ' AND DATE(er.created_at) >= ?';
                $params[] = $start;
            }
            if ($end !== '') {
                $sql .= ' AND DATE(er.created_at) <= ?';
                $params[] = $end;
            }
            $sql .= ' ORDER BY er.created_at DESC';
            $st = db()->prepare($sql);
            $st->execute($params);
            $items = $st->fetchAll();
        }
        $title = 'Admin – Inscriptions Événements';
        return view('admin/events/registrations', compact('title', 'items', 'event', 'start', 'end'));
    }

    public function eventRegistrationsExport(): string
    {
        $eventId = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
        $rows = [];
        if ($eventId > 0) {
            $st = db()->prepare('SELECT er.*, e.title FROM event_registrations er JOIN events e ON e.id=er.event_id WHERE event_id=? ORDER BY er.created_at DESC');
            $st->execute([$eventId]);
            $status = trim($_GET['status'] ?? '');
            $rows = $st->fetchAll();
        } else {
            $rows = db()->query('SELECT er.*, e.title FROM event_registrations er JOIN events e ON e.id=er.event_id ORDER BY er.created_at DESC')->fetchAll();
        }
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="inscriptions_evenements.csv"');
        $out = fopen('php://output', 'w');
        $sql = 'SELECT * FROM event_registrations WHERE event_id=?';
        fputcsv($out, ['Événement', 'Nom', 'Email', 'Téléphone', 'Message', 'Statut', 'Consentement', 'Date']);
        foreach ($rows as $r) {
            $consent = ((int)($r['consent'] ?? 0) === 1) ? 'Oui' : 'Non';
            if ($status !== '' && in_array($status, ['pending', 'confirmed', 'cancelled'])) {
                $sql .= ' AND status=?';
                $params[] = $status;
            }
            fputcsv($out, [
                $r['title'] ?? '',
                $r['name'],
                $r['email'],
                $r['phone'],
                $r['message'],
                $r['status'] ?? 'pending',
                $consent,
                $r['created_at']
            ]);
            if ($status !== '' && in_array($status, ['pending', 'confirmed', 'cancelled'])) {
                $sql .= ' AND er.status=?';
                $params[] = $status;
            }
        }
        fclose($out);
        return '';
    }

    // NEWS
    public function newsIndex(): string
    {
        $items = db()->query('SELECT * FROM news ORDER BY id DESC')->fetchAll();
        $title = 'Admin – Actualités';
        return view('admin/news/index', compact('title', 'items'));
    }
    public function newsForm(): string
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $item = null;
        if ($id) {
            $st = db()->prepare('SELECT * FROM news WHERE id=?');
            $st->execute([$id]);
            $item = $st->fetch();
        }
        $title = $id ? 'Modifier Actualité' : 'Créer Actualité';
        return view('admin/news/form', compact('title', 'item'));
    }
    public function newsStore(): string
    {
        require_csrf();
        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? '');
        $status = in_array($_POST['status'] ?? 'published', ['draft', 'published']) ? $_POST['status'] : 'published';
        $published_at = trim($_POST['published_at'] ?? null) ?: null;
        $source = substr(trim($_POST['source'] ?? ''), 0, 200);
        $article_url = substr(trim($_POST['article_url'] ?? ''), 0, 500);
        $image_url = trim($_POST['image_url'] ?? '');
        if (!empty($_FILES['image_file']['name'])) {
            $upl = $_FILES['image_file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024;
                if (in_array($ext, $allowedImg) && $upl['size'] <= $maxSize) {
                    $baseDir = __DIR__ . '/../../uploads/news';
                    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                    $fname = uniqid('n_') . '.' . $ext;
                    $dest = $baseDir . '/' . $fname;
                    if (move_uploaded_file($upl['tmp_name'], $dest)) {
                        $image_url = base_url('uploads/news/' . $fname);
                        $this->convertToWebpIfPossibleGeneric($dest, $ext, $baseDir, $image_url, 'uploads/news');
                    }
                }
            }
        }
        $st = db()->prepare('INSERT INTO news(title, body, image_url, status, published_at, source, article_url) VALUES(?,?,?,?,?,?,?)');
        $st->execute([$title, $body, $image_url, $status, $published_at, $source, $article_url]);
        header('Location: ' . base_url('/admin/news'));
        return '';
    }
    public function newsUpdate(): string
    {
        require_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? '');
        $status = in_array($_POST['status'] ?? 'published', ['draft', 'published']) ? $_POST['status'] : 'published';
        $published_at = trim($_POST['published_at'] ?? null) ?: null;
        $source = substr(trim($_POST['source'] ?? ''), 0, 200);
        $article_url = substr(trim($_POST['article_url'] ?? ''), 0, 500);
        $image_url = trim($_POST['image_url'] ?? '');
        if (!empty($_FILES['image_file']['name'])) {
            $upl = $_FILES['image_file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024;
                if (in_array($ext, $allowedImg) && $upl['size'] <= $maxSize) {
                    $baseDir = __DIR__ . '/../../uploads/news';
                    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                    $fname = uniqid('n_') . '.' . $ext;
                    $dest = $baseDir . '/' . $fname;
                    if (move_uploaded_file($upl['tmp_name'], $dest)) {
                        $image_url = base_url('uploads/news/' . $fname);
                        $this->convertToWebpIfPossibleGeneric($dest, $ext, $baseDir, $image_url, 'uploads/news');
                    }
                }
            }
        }
        $st = db()->prepare('UPDATE news SET title=?, body=?, image_url=?, status=?, published_at=?, source=?, article_url=? WHERE id=?');
        $st->execute([$title, $body, $image_url, $status, $published_at, $source, $article_url, $id]);
        header('Location: ' . base_url('/admin/news'));
        return '';
    }
    public function newsDelete(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare('DELETE FROM news WHERE id=?');
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/news'));
        return '';
    }

    // RSS Sources management
    public function rssSourcesIndex(): string
    {
        $items = db()->query('SELECT * FROM rss_sources ORDER BY id DESC')->fetchAll();
        $title = 'Admin – Sources RSS';
        return view('admin/rss/index', compact('title', 'items'));
    }
    public function rssIngest(): string
    {
        require_csrf();
        // Fetch enabled sources and insert top items into news
        $rows = db()->query('SELECT name, url FROM rss_sources WHERE enabled=1 ORDER BY id DESC')->fetchAll();
        $inserted = 0;
        foreach ($rows as $r) {
            $name = $r['name'];
            $url = $r['url'];
            try {
                $xml = @simplexml_load_file($url, "SimpleXMLElement", LIBXML_NOCDATA);
                if ($xml && isset($xml->channel->item)) {
                    foreach ($xml->channel->item as $it) {
                        $title = (string)$it->title;
                        $link = (string)$it->link;
                        $pub = (string)$it->pubDate;
                        $pubDate = $pub ? date('Y-m-d H:i:s', strtotime($pub)) : null;
                        $desc = trim(strip_tags((string)$it->description));
                        // Skip if already exists by link
                        $chk = db()->prepare('SELECT COUNT(*) FROM news WHERE article_url=?');
                        $chk->execute([$link]);
                        if ((int)$chk->fetchColumn() > 0) continue;
                        $st = db()->prepare('INSERT INTO news(title, body, image_url, status, published_at, source, article_url) VALUES(?,?,?,?,?,?,?)');
                        $st->execute([$title, $desc, '', 'published', $pubDate, $name, $link]);
                        $inserted++;
                    }
                }
            } catch (\Throwable $e) { /* ignore */
            }
        }
        $title = 'Admin – Sources RSS';
        $items = db()->query('SELECT * FROM rss_sources ORDER BY id DESC')->fetchAll();
        $success = $inserted > 0 ? ("$inserted articles importés.") : 'Aucun nouvel article.';
        return view('admin/rss/index', compact('title', 'items', 'success'));
    }
    public function rssSourcesForm(): string
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $item = null;
        if ($id) {
            $st = db()->prepare('SELECT * FROM rss_sources WHERE id=?');
            $st->execute([$id]);
            $item = $st->fetch();
        }
        $title = $id ? 'Modifier Source RSS' : 'Ajouter Source RSS';
        return view('admin/rss/form', compact('title', 'item'));
    }
    public function rssSourcesStore(): string
    {
        require_csrf();
        $name = trim($_POST['name'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $enabled = (int)($_POST['enabled'] ?? 1) ? 1 : 0;
        $st = db()->prepare('INSERT INTO rss_sources(name, url, enabled, created_at) VALUES(?,?,?,NOW())');
        $st->execute([$name, $url, $enabled]);
        header('Location: ' . base_url('/admin/rss-sources'));
        return '';
    }
    public function rssSourcesUpdate(): string
    {
        require_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $enabled = (int)($_POST['enabled'] ?? 1) ? 1 : 0;
        $st = db()->prepare('UPDATE rss_sources SET name=?, url=?, enabled=? WHERE id=?');
        $st->execute([$name, $url, $enabled, $id]);
        header('Location: ' . base_url('/admin/rss-sources'));
        return '';
    }
    public function rssSourcesToggle(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare('UPDATE rss_sources SET enabled=1-enabled WHERE id=?');
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/rss-sources'));
        return '';
    }
    public function rssSourcesDelete(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare('DELETE FROM rss_sources WHERE id=?');
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/rss-sources'));
        return '';
    }

    // Programmes
    public function programmesIndex(): string
    {
        $items = db()->query('SELECT * FROM programmes ORDER BY id DESC')->fetchAll();
        $title = 'Admin – Programmes';
        return view('admin/programmes/index', compact('title', 'items'));
    }
    public function programmesForm(): string
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $item = null;
        if ($id) {
            $st = db()->prepare('SELECT * FROM programmes WHERE id=?');
            $st->execute([$id]);
            $item = $st->fetch();
        }
        $title = $id ? 'Modifier Programme' : 'Créer Programme';
        return view('admin/programmes/form', compact('title', 'item'));
    }
    public function programmesStore(): string
    {
        require_csrf();
        $name = substr(trim($_POST['name'] ?? ''), 0, 160);
        $description = substr(trim($_POST['description'] ?? ''), 0, 300);
        $excerpt = substr(trim($_POST['excerpt'] ?? ''), 0, 300);
        $content = trim($_POST['content'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $status = in_array($_POST['status'] ?? 'draft', ['draft', 'published']) ? $_POST['status'] : 'draft';
        $image_url = trim($_POST['image_url'] ?? '');
        // Upload image with WebP conversion
        if (!empty($_FILES['image_file']['name'])) {
            $upl = $_FILES['image_file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024; // 2MB
                if (in_array($ext, $allowedImg) && $upl['size'] <= $maxSize) {
                    $baseDir = __DIR__ . '/../../uploads/programmes';
                    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                    $fname = uniqid('p_') . '.' . $ext;
                    $dest = $baseDir . '/' . $fname;
                    if (move_uploaded_file($upl['tmp_name'], $dest)) {
                        $image_url = base_url('uploads/programmes/' . $fname);
                        $this->convertToWebpIfPossibleGeneric($dest, $ext, $baseDir, $image_url, 'uploads/programmes');
                    }
                }
            }
        }
        $st = db()->prepare('INSERT INTO programmes(name, description, excerpt, content, url, status, image_url, created_at, updated_at) VALUES(?,?,?,?,?,?,?,?,NOW())');
        $st->execute([$name, $description, $excerpt, $content, $url, $status, $image_url, date('Y-m-d H:i:s')]);
        header('Location: ' . base_url('/admin/programmes'));
        return '';
    }
    public function programmesUpdate(): string
    {
        require_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $name = substr(trim($_POST['name'] ?? ''), 0, 160);
        $description = substr(trim($_POST['description'] ?? ''), 0, 300);
        $excerpt = substr(trim($_POST['excerpt'] ?? ''), 0, 300);
        $content = trim($_POST['content'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $status = in_array($_POST['status'] ?? 'draft', ['draft', 'published']) ? $_POST['status'] : 'draft';
        $image_url = trim($_POST['image_url'] ?? '');
        if (!empty($_FILES['image_file']['name'])) {
            $upl = $_FILES['image_file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024;
                if (in_array($ext, $allowedImg) && $upl['size'] <= $maxSize) {
                    $baseDir = __DIR__ . '/../../uploads/programmes';
                    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                    $fname = uniqid('p_') . '.' . $ext;
                    $dest = $baseDir . '/' . $fname;
                    if (move_uploaded_file($upl['tmp_name'], $dest)) {
                        $image_url = base_url('uploads/programmes/' . $fname);
                        $this->convertToWebpIfPossibleGeneric($dest, $ext, $baseDir, $image_url, 'uploads/programmes');
                    }
                }
            }
        }
        $st = db()->prepare('UPDATE programmes SET name=?, description=?, excerpt=?, content=?, url=?, status=?, image_url=?, updated_at=NOW() WHERE id=?');
        $st->execute([$name, $description, $excerpt, $content, $url, $status, $image_url, $id]);
        header('Location: ' . base_url('/admin/programmes'));
        return '';
    }
    public function programmesDelete(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare('DELETE FROM programmes WHERE id=?');
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/programmes'));
        return '';
    }

    // Formations
    public function formationsIndex(): string
    {
        $items = db()->query('SELECT * FROM formations ORDER BY id DESC')->fetchAll();
        // Load section params
        $st = db()->prepare('SELECT title, subtitle FROM sections WHERE `key`=?');
        $st->execute(['formations']);
        $section = $st->fetch() ?: ['title' => 'Formations IFMAP', 'subtitle' => 'Des formations professionnalisantes adaptées au marché africain.'];
        $title = 'Admin – Formations';
        return view('admin/formations/index', compact('title', 'items', 'section'));
    }
    public function formationsForm(): string
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $item = null;
        if ($id) {
            $st = db()->prepare('SELECT * FROM formations WHERE id=?');
            $st->execute([$id]);
            $item = $st->fetch();
        }
        $title = $id ? 'Modifier Formation' : 'Créer Formation';
        return view('admin/formations/form', compact('title', 'item'));
    }
    public function formationsStore(): string
    {
        require_csrf();
        $name = substr(trim($_POST['name'] ?? ''), 0, 160);
        $description = substr(trim($_POST['description'] ?? ''), 0, 300);
        $status = in_array($_POST['status'] ?? 'published', ['draft', 'published']) ? $_POST['status'] : 'published';
        $image_url = trim($_POST['image_url'] ?? '');
        if (!empty($_FILES['image_file']['name'])) {
            $upl = $_FILES['image_file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024;
                if (in_array($ext, $allowedImg) && $upl['size'] <= $maxSize) {
                    $baseDir = __DIR__ . '/../../uploads/formations';
                    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                    $fname = uniqid('f_') . '.' . $ext;
                    $dest = $baseDir . '/' . $fname;
                    if (move_uploaded_file($upl['tmp_name'], $dest)) {
                        $image_url = base_url('uploads/formations/' . $fname);
                        $this->convertToWebpIfPossibleGeneric($dest, $ext, $baseDir, $image_url, 'uploads/formations');
                    }
                }
            }
        }
        $st = db()->prepare('INSERT INTO formations(name, description, status, image_url) VALUES(?,?,?,?)');
        $st->execute([$name, $description, $status, $image_url]);
        header('Location: ' . base_url('/admin/formations'));
        return '';
    }
    public function formationsUpdate(): string
    {
        require_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $name = substr(trim($_POST['name'] ?? ''), 0, 160);
        $description = substr(trim($_POST['description'] ?? ''), 0, 300);
        $status = in_array($_POST['status'] ?? 'published', ['draft', 'published']) ? $_POST['status'] : 'published';
        $image_url = trim($_POST['image_url'] ?? '');
        if (!empty($_FILES['image_file']['name'])) {
            $upl = $_FILES['image_file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024;
                if (in_array($ext, $allowedImg) && $upl['size'] <= $maxSize) {
                    $baseDir = __DIR__ . '/../../uploads/formations';
                    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                    $fname = uniqid('f_') . '.' . $ext;
                    $dest = $baseDir . '/' . $fname;
                    if (move_uploaded_file($upl['tmp_name'], $dest)) {
                        $image_url = base_url('uploads/formations/' . $fname);
                        $this->convertToWebpIfPossibleGeneric($dest, $ext, $baseDir, $image_url, 'uploads/formations');
                    }
                }
            }
        }
        $st = db()->prepare('UPDATE formations SET name=?, description=?, status=?, image_url=? WHERE id=?');
        $st->execute([$name, $description, $status, $image_url, $id]);
        header('Location: ' . base_url('/admin/formations'));
        return '';
    }
    public function formationsDelete(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare('DELETE FROM formations WHERE id=?');
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/formations'));
        return '';
    }

    // Save formations section params
    public function formationsSectionSave(): string
    {
        require_csrf();
        $title = trim($_POST['title'] ?? '') ?: null;
        $subtitle = trim($_POST['subtitle'] ?? '') ?: null;
        // Upsert into sections
        $exists = db()->prepare('SELECT COUNT(*) FROM sections WHERE `key`=?');
        $exists->execute(['formations']);
        if ((int)$exists->fetchColumn() > 0) {
            $upd = db()->prepare('UPDATE sections SET title=?, subtitle=?, updated_at=NOW() WHERE `key`=?');
            $upd->execute([$title, $subtitle, 'formations']);
        } else {
            $ins = db()->prepare('INSERT INTO sections(`key`, title, subtitle, updated_at) VALUES(?,?,?,NOW())');
            $ins->execute(['formations', $title, $subtitle]);
        }
        header('Location: ' . base_url('/admin/formations'));
        return '';
    }

    // Centres (Instituts & Centres IFMAP)
    public function centresIndex(): string
    {
        $items = db()->query('SELECT * FROM centres ORDER BY id DESC')->fetchAll();
        $st = db()->prepare('SELECT title, subtitle FROM sections WHERE `key`=?');
        $st->execute(['centres']);
        $section = $st->fetch() ?: ['title' => 'Instituts & Centres IFMAP', "subtitle" => "Découvrez nos pôles d'excellence et d'innovation."];
        $title = 'Admin – Centres';
        return view('admin/centres/index', compact('title', 'items', 'section'));
    }
    public function centresForm(): string
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $item = null;
        if ($id) {
            $st = db()->prepare('SELECT * FROM centres WHERE id=?');
            $st->execute([$id]);
            $item = $st->fetch();
        }
        $title = $id ? 'Modifier Centre' : 'Créer Centre';
        return view('admin/centres/form', compact('title', 'item'));
    }
    public function centresStore(): string
    {
        require_csrf();
        $name = substr(trim($_POST['name'] ?? ''), 0, 160);
        $subtitle = substr(trim($_POST['subtitle'] ?? ''), 0, 300);
        $excerpt = substr(trim($_POST['excerpt'] ?? ''), 0, 500);
        $content = trim($_POST['content'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $status = in_array($_POST['status'] ?? 'published', ['draft', 'published']) ? $_POST['status'] : 'published';
        $image_url = trim($_POST['image_url'] ?? '');
        if (!empty($_FILES['image_file']['name'])) {
            $upl = $_FILES['image_file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024;
                if (in_array($ext, $allowedImg) && $upl['size'] <= $maxSize) {
                    $baseDir = __DIR__ . '/../../uploads/centres';
                    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                    $fname = uniqid('c_') . '.' . $ext;
                    $dest = $baseDir . '/' . $fname;
                    if (move_uploaded_file($upl['tmp_name'], $dest)) {
                        $image_url = base_url('uploads/centres/' . $fname);
                        $this->convertToWebpIfPossibleGeneric($dest, $ext, $baseDir, $image_url, 'uploads/centres');
                    }
                }
            }
        }
        $st = db()->prepare('INSERT INTO centres(name, subtitle, excerpt, content, url, image_url, status, created_at, updated_at) VALUES(?,?,?,?,?,?,?,?,NOW())');
        $st->execute([$name, $subtitle, $excerpt, $content, $url, $image_url, $status, date('Y-m-d H:i:s')]);
        header('Location: ' . base_url('/admin/centres'));
        return '';
    }
    public function centresUpdate(): string
    {
        require_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $name = substr(trim($_POST['name'] ?? ''), 0, 160);
        $subtitle = substr(trim($_POST['subtitle'] ?? ''), 0, 300);
        $excerpt = substr(trim($_POST['excerpt'] ?? ''), 0, 500);
        $content = trim($_POST['content'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $status = in_array($_POST['status'] ?? 'published', ['draft', 'published']) ? $_POST['status'] : 'published';
        $image_url = trim($_POST['image_url'] ?? '');
        if (!empty($_FILES['image_file']['name'])) {
            $upl = $_FILES['image_file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024;
                if (in_array($ext, $allowedImg) && $upl['size'] <= $maxSize) {
                    $baseDir = __DIR__ . '/../../uploads/centres';
                    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                    $fname = uniqid('c_') . '.' . $ext;
                    $dest = $baseDir . '/' . $fname;
                    if (move_uploaded_file($upl['tmp_name'], $dest)) {
                        $image_url = base_url('uploads/centres/' . $fname);
                        $this->convertToWebpIfPossibleGeneric($dest, $ext, $baseDir, $image_url, 'uploads/centres');
                    }
                }
            }
        }
        $st = db()->prepare('UPDATE centres SET name=?, subtitle=?, excerpt=?, content=?, url=?, image_url=?, status=?, updated_at=NOW() WHERE id=?');
        $st->execute([$name, $subtitle, $excerpt, $content, $url, $image_url, $status, $id]);
        header('Location: ' . base_url('/admin/centres'));
        return '';
    }
    public function centresDelete(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare('DELETE FROM centres WHERE id=?');
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/centres'));
        return '';
    }
    public function centresSectionSave(): string
    {
        require_csrf();
        $title = trim($_POST['title'] ?? '') ?: null;
        $subtitle = trim($_POST['subtitle'] ?? '') ?: null;
        $exists = db()->prepare('SELECT COUNT(*) FROM sections WHERE `key`=?');
        $exists->execute(['centres']);
        if ((int)$exists->fetchColumn() > 0) {
            $upd = db()->prepare('UPDATE sections SET title=?, subtitle=?, updated_at=NOW() WHERE `key`=?');
            $upd->execute([$title, $subtitle, 'centres']);
        } else {
            $ins = db()->prepare('INSERT INTO sections(`key`, title, subtitle, updated_at) VALUES(?,?,?,NOW())');
            $ins->execute(['centres', $title, $subtitle]);
        }
        header('Location: ' . base_url('/admin/centres'));
        return '';
    }

    // Partenaires
    public function partnersIndex(): string
    {
        $items = db()->query('SELECT * FROM partners ORDER BY id DESC')->fetchAll();
        $title = 'Admin – Partenaires';
        return view('admin/partners/index', compact('title', 'items'));
    }
    public function partnersForm(): string
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $item = null;
        if ($id) {
            $st = db()->prepare('SELECT * FROM partners WHERE id=?');
            $st->execute([$id]);
            $item = $st->fetch();
        }
        $title = $id ? 'Modifier Partenaire' : 'Créer Partenaire';
        return view('admin/partners/form', compact('title', 'item'));
    }
    public function partnersStore(): string
    {
        require_csrf();
        $name = trim($_POST['name'] ?? '');
        $logo_url = trim($_POST['logo_url'] ?? '');
        $enabled = (int)(($_POST['enabled'] ?? '1') ? 1 : 0);
        // Optional drag-n-drop upload
        if (!empty($_FILES['logo_file']['name'])) {
            $upl = $_FILES['logo_file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024;
                if (in_array($ext, $allowedImg) && $upl['size'] <= $maxSize) {
                    $baseDir = __DIR__ . '/../../uploads/partners';
                    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                    $fname = uniqid('pr_') . '.' . $ext;
                    $dest = $baseDir . '/' . $fname;
                    if (move_uploaded_file($upl['tmp_name'], $dest)) {
                        $logo_url = base_url('uploads/partners/' . $fname);
                    }
                }
            }
        }
        $st = db()->prepare('INSERT INTO partners(name, logo_url, enabled) VALUES(?,?,?)');
        $st->execute([$name, $logo_url, $enabled]);
        header('Location: ' . base_url('/admin/partners'));
        return '';
    }
    public function partnersUpdate(): string
    {
        require_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $logo_url = trim($_POST['logo_url'] ?? '');
        $enabled = (int)(($_POST['enabled'] ?? '1') ? 1 : 0);
        if (!empty($_FILES['logo_file']['name'])) {
            $upl = $_FILES['logo_file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024;
                if (in_array($ext, $allowedImg) && $upl['size'] <= $maxSize) {
                    $baseDir = __DIR__ . '/../../uploads/partners';
                    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                    $fname = uniqid('pr_') . '.' . $ext;
                    $dest = $baseDir . '/' . $fname;
                    if (move_uploaded_file($upl['tmp_name'], $dest)) {
                        $logo_url = base_url('uploads/partners/' . $fname);
                    }
                }
            }
        }
        $st = db()->prepare('UPDATE partners SET name=?, logo_url=?, enabled=? WHERE id=?');
        $st->execute([$name, $logo_url, $enabled, $id]);
        header('Location: ' . base_url('/admin/partners'));
        return '';
    }
    public function partnersDelete(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare('DELETE FROM partners WHERE id=?');
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/partners'));
        return '';
    }
    public function partnersToggle(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare('UPDATE partners SET enabled=1-enabled WHERE id=?');
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/partners'));
        return '';
    }
    // Testimonials (modération)
    public function testimonialsIndex(): string
    {
        $items = db()->query('SELECT * FROM testimonials ORDER BY id DESC')->fetchAll();
        $title = 'Admin – Témoignages';
        return view('admin/testimonials/index', compact('title', 'items'));
    }
    public function testimonialsApprove(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare("UPDATE testimonials SET status='approved' WHERE id=?");
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/testimonials'));
        return '';
    }
    public function testimonialsReject(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare("UPDATE testimonials SET status='rejected' WHERE id=?");
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/testimonials'));
        return '';
    }
    public function testimonialsDelete(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare('DELETE FROM testimonials WHERE id=?');
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/testimonials'));
        return '';
    }
    // Media (Galerie)
    public function mediaIndex(): string
    {
        $items = db()->query('SELECT * FROM media ORDER BY id DESC')->fetchAll();
        $title = 'Admin – Médias';
        return view('admin/media/index', compact('title', 'items'));
    }
    public function mediaForm(): string
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $item = null;
        if ($id) {
            $st = db()->prepare('SELECT * FROM media WHERE id=?');
            $st->execute([$id]);
            $item = $st->fetch();
        }
        $title = $id ? 'Modifier Média' : 'Ajouter Média';
        return view('admin/media/form', compact('title', 'item'));
    }
    public function mediaStore(): string
    {
        require_csrf();
        $title = trim($_POST['title'] ?? '');
        $type = $_POST['type'] === 'video-file' ? 'video' : ($_POST['type'] === 'video' ? 'video' : 'image');
        $category = trim($_POST['category'] ?? '');
        $tags = trim($_POST['tags'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $poster = trim($_POST['poster_url'] ?? '');
        // Upload fichier si présent
        if (!empty($_FILES['file']['name'])) {
            $upl = $_FILES['file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $allowedVid = ['mp4'];
                $baseDir = __DIR__ . '/../../uploads/media';
                if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                $fname = uniqid('m_') . '.' . $ext;
                $dest = $baseDir . '/' . $fname;
                if (move_uploaded_file($upl['tmp_name'], $dest)) {
                    $publicPath = base_url('uploads/media/' . $fname);
                    if (in_array($ext, $allowedVid)) {
                        $type = 'video';
                        $url = $publicPath;
                    } elseif (in_array($ext, $allowedImg)) {
                        $type = 'image';
                        $url = $publicPath;
                    }
                }
            }
        }
        $st = db()->prepare('INSERT INTO media(title,type,url,thumb_url,description,category,tags) VALUES(?,?,?,?,?,?,?)');
        $st->execute([$title, $type, $url, $poster, $description, $category, $tags]);
        header('Location: ' . base_url('/admin/media'));
        return '';
    }
    public function mediaUpdate(): string
    {
        require_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $type = $_POST['type'] === 'video-file' ? 'video' : ($_POST['type'] === 'video' ? 'video' : 'image');
        $category = trim($_POST['category'] ?? '');
        $tags = trim($_POST['tags'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $poster = trim($_POST['poster_url'] ?? '');
        if (!empty($_FILES['file']['name'])) {
            $upl = $_FILES['file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $allowedVid = ['mp4'];
                $baseDir = __DIR__ . '/../../uploads/media';
                if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                $fname = uniqid('m_') . '.' . $ext;
                $dest = $baseDir . '/' . $fname;
                if (move_uploaded_file($upl['tmp_name'], $dest)) {
                    $publicPath = base_url('uploads/media/' . $fname);
                    if (in_array($ext, $allowedVid)) {
                        $type = 'video';
                        $url = $publicPath;
                    } elseif (in_array($ext, $allowedImg)) {
                        $type = 'image';
                        $url = $publicPath;
                    }
                }
            }
        }
        $st = db()->prepare('UPDATE media SET title=?, type=?, url=?, thumb_url=?, description=?, category=?, tags=? WHERE id=?');
        $st->execute([$title, $type, $url, $poster, $description, $category, $tags, $id]);
        header('Location: ' . base_url('/admin/media'));
        return '';
    }
    public function mediaDelete(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare('DELETE FROM media WHERE id=?');
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/media'));
        return '';
    }
    // Contacts (messages du formulaire)
    public function contactsIndex(): string
    {
        $items = db()->query('SELECT * FROM contact_messages ORDER BY created_at DESC')->fetchAll();
        $title = 'Admin – Messages de contact';
        return view('admin/contacts/index', compact('title', 'items'));
    }
    public function contactsMark(): string
    {
        require_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $val = (int)($_POST['read'] ?? 0);
        if ($id) {
            $st = db()->prepare('UPDATE contact_messages SET `read`=? WHERE id=?');
            $st->execute([$val ? 1 : 0, $id]);
        }
        header('Location: ' . base_url('/admin/contacts'));
        return '';
    }

    // Carousels CRUD (max 3 items enforced in store)
    public function carouselsIndex(): string
    {
        $items = db()->query('SELECT * FROM carousels ORDER BY position ASC, id ASC')->fetchAll();
        $title = 'Admin – Carrousels';
        return view('admin/carousels/index', compact('title', 'items'));
    }
    public function carouselsForm(): string
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $item = null;
        if ($id) {
            $st = db()->prepare('SELECT * FROM carousels WHERE id=?');
            $st->execute([$id]);
            $item = $st->fetch();
        }
        $title = $id ? 'Modifier carrousel' : 'Créer carrousel';
        return view('admin/carousels/form', compact('title', 'item'));
    }
    public function carouselsStore(): string
    {
        require_csrf();
        // Enforce max 3
        $count = (int)db()->query('SELECT COUNT(*) FROM carousels')->fetchColumn();
        if ($count >= 3) {
            $title = 'Admin – Carrousels';
            $items = db()->query('SELECT * FROM carousels ORDER BY position ASC')->fetchAll();
            $error = 'Maximum de 3 carrousels atteint.';
            return view('admin/carousels/index', compact('title', 'items', 'error'));
        }
        $position = max(1, (int)($_POST['position'] ?? 1));
        $titleTxt = substr(trim($_POST['title'] ?? ''), 0, 120);
        $caption = substr(trim($_POST['caption'] ?? ''), 0, 200);
        $description = substr(trim($_POST['description'] ?? ''), 0, 240);
        $button_text = substr(trim($_POST['button_text'] ?? ''), 0, 60);
        $button_url = trim($_POST['button_url'] ?? '');
        $background_url = trim($_POST['background_url'] ?? '');
        // Upload local image (validate type/size)
        if (!empty($_FILES['background_file']['name'])) {
            $upl = $_FILES['background_file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024; // 2MB
                if (in_array($ext, $allowedImg) && $upl['size'] <= $maxSize) {
                    $baseDir = __DIR__ . '/../../uploads/carousels';
                    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                    $fname = uniqid('c_') . '.' . $ext;
                    $dest = $baseDir . '/' . $fname;
                    if (move_uploaded_file($upl['tmp_name'], $dest)) {
                        // Convert to WebP for jpg/png if possible
                        $background_url = base_url('uploads/carousels/' . $fname);
                        $this->convertToWebpIfPossible($dest, $ext, $baseDir, $background_url);
                    }
                }
            }
        }
        $st = db()->prepare('INSERT INTO carousels(position,title,caption,description,button_text,button_url,background_url) VALUES(?,?,?,?,?,?,?)');
        $st->execute([$position, $titleTxt, $caption, $description, $button_text, $button_url, $background_url]);
        header('Location: ' . base_url('/admin/carousels'));
        return '';
    }
    public function carouselsUpdate(): string
    {
        require_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $position = max(1, (int)($_POST['position'] ?? 1));
        $titleTxt = substr(trim($_POST['title'] ?? ''), 0, 120);
        $caption = substr(trim($_POST['caption'] ?? ''), 0, 200);
        $description = substr(trim($_POST['description'] ?? ''), 0, 240);
        $button_text = substr(trim($_POST['button_text'] ?? ''), 0, 60);
        $button_url = trim($_POST['button_url'] ?? '');
        $background_url = trim($_POST['background_url'] ?? '');
        if (!empty($_FILES['background_file']['name'])) {
            $upl = $_FILES['background_file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024; // 2MB
                if (in_array($ext, $allowedImg) && $upl['size'] <= $maxSize) {
                    $baseDir = __DIR__ . '/../../uploads/carousels';
                    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                    $fname = uniqid('c_') . '.' . $ext;
                    $dest = $baseDir . '/' . $fname;
                    if (move_uploaded_file($upl['tmp_name'], $dest)) {
                        $background_url = base_url('uploads/carousels/' . $fname);
                    }
                }
            }
        }
        $st = db()->prepare('UPDATE carousels SET position=?, title=?, caption=?, description=?, button_text=?, button_url=?, background_url=? WHERE id=?');
        $st->execute([$position, $titleTxt, $caption, $description, $button_text, $button_url, $background_url, $id]);
        header('Location: ' . base_url('/admin/carousels'));
        return '';
    }
    public function carouselsDelete(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $st = db()->prepare('DELETE FROM carousels WHERE id=?');
            $st->execute([$id]);
        }
        header('Location: ' . base_url('/admin/carousels'));
        return '';
    }

    private function convertToWebpIfPossible(string $sourcePath, string $ext, string $baseDir, string &$backgroundUrl): void
    {
        $lower = strtolower($ext);
        if ($lower === 'webp') return;
        // Try GD
        try {
            $img = null;
            if ($lower === 'jpg' || $lower === 'jpeg') {
                if (function_exists('imagecreatefromjpeg')) $img = @imagecreatefromjpeg($sourcePath);
            } elseif ($lower === 'png') {
                if (function_exists('imagecreatefrompng')) $img = @imagecreatefrompng($sourcePath);
            }
            if ($img && function_exists('imagewebp')) {
                $webpName = pathinfo($sourcePath, PATHINFO_FILENAME) . '.webp';
                $webpPath = $baseDir . '/' . $webpName;
                // quality ~80
                if (@imagepalettetotruecolor($img)) { /* make sure true color */
                }
                @imagealphablending($img, true);
                @imagesavealpha($img, true);
                if (@imagewebp($img, $webpPath, 80)) {
                    $backgroundUrl = base_url('uploads/carousels/' . $webpName);
                }
                imagedestroy($img);
            }
        } catch (\Throwable $e) {
            // silently ignore conversion errors
        }
    }

    private function convertToWebpIfPossibleGeneric(string $sourcePath, string $ext, string $baseDir, string &$urlField, string $publicSubdir): void
    {
        $lower = strtolower($ext);
        if ($lower === 'webp') return;
        try {
            $img = null;
            if ($lower === 'jpg' || $lower === 'jpeg') {
                if (function_exists('imagecreatefromjpeg')) $img = @imagecreatefromjpeg($sourcePath);
            } elseif ($lower === 'png') {
                if (function_exists('imagecreatefrompng')) $img = @imagecreatefrompng($sourcePath);
            }
            if ($img && function_exists('imagewebp')) {
                $webpName = pathinfo($sourcePath, PATHINFO_FILENAME) . '.webp';
                $webpPath = $baseDir . '/' . $webpName;
                @imagepalettetotruecolor($img);
                @imagealphablending($img, true);
                @imagesavealpha($img, true);
                if (@imagewebp($img, $webpPath, 80)) {
                    $urlField = base_url($publicSubdir . '/' . $webpName);
                }
                imagedestroy($img);
            }
        } catch (\Throwable $e) {
        }
    }

    // AJAX ordering
    public function carouselsOrder(): string
    {
        require_csrf();
        $order = $_POST['order'] ?? [];
        if (is_array($order)) {
            $pos = 1;
            foreach ($order as $id) {
                $idInt = (int)$id;
                $st = db()->prepare('UPDATE carousels SET position=? WHERE id=?');
                $st->execute([$pos, $idInt]);
                $pos++;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        return '';
    }

    // TinyMCE upload endpoint
    public function adminUpload(): string
    {
        require_csrf();
        header('Content-Type: application/json');
        if (empty($_FILES['file']['name'])) {
            echo json_encode(['error' => 'Aucun fichier']);
            return '';
        }
        $upl = $_FILES['file'];
        if ($upl['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['error' => 'Erreur upload']);
            return '';
        }
        $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
        $allowedImg = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($ext, $allowedImg)) {
            echo json_encode(['error' => 'Type non supporté']);
            return '';
        }
        $baseDir = __DIR__ . '/../../uploads/editor';
        if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
        $fname = uniqid('e_') . '.' . $ext;
        $dest = $baseDir . '/' . $fname;
        if (!move_uploaded_file($upl['tmp_name'], $dest)) {
            echo json_encode(['error' => 'Échec sauvegarde']);
            return '';
        }
        $location = base_url('uploads/editor/' . $fname);
        // Try webp conversion for jpg/png
        $this->convertToWebpIfPossibleGeneric($dest, $ext, $baseDir, $location, 'uploads/editor');
        echo json_encode(['location' => $location]);
        return '';
    }
    // Settings
    public function settingsForm(): string
    {
        $title = 'Paramètres du site';
        $row = db()->query('SELECT * FROM settings WHERE id=1')->fetch();
        return view('admin/settings/form', compact('title', 'row'));
    }
    public function settingsSave(): string
    {
        require_csrf();
        $logo_url = trim($_POST['logo_url'] ?? '');
        if (!empty($_FILES['logo_file']['name'])) {
            $upl = $_FILES['logo_file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024;
                if (in_array($ext, $allowedImg) && $upl['size'] <= $maxSize) {
                    $baseDir = __DIR__ . '/../../uploads/site';
                    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                    $fname = uniqid('logo_') . '.' . $ext;
                    $dest = $baseDir . '/' . $fname;
                    if (move_uploaded_file($upl['tmp_name'], $dest)) {
                        $logo_url = base_url('uploads/site/' . $fname);
                        $this->convertToWebpIfPossibleGeneric($dest, $ext, $baseDir, $logo_url, 'uploads/site');
                    }
                }
            }
        }
        $contact_email = substr(trim($_POST['contact_email'] ?? ''), 0, 200);
        $contact_phone = substr(trim($_POST['contact_phone'] ?? ''), 0, 60);
        $contact_address = substr(trim($_POST['contact_address'] ?? ''), 0, 300);
        $link_programmes = substr(trim($_POST['link_programmes'] ?? ''), 0, 300);
        $link_formations = substr(trim($_POST['link_formations'] ?? ''), 0, 300);
        $link_actualites = substr(trim($_POST['link_actualites'] ?? ''), 0, 300);
        $link_partenaires = substr(trim($_POST['link_partenaires'] ?? ''), 0, 300);
        $social_facebook = substr(trim($_POST['social_facebook'] ?? ''), 0, 300);
        $social_linkedin = substr(trim($_POST['social_linkedin'] ?? ''), 0, 300);
        $social_youtube = substr(trim($_POST['social_youtube'] ?? ''), 0, 300);
        $newsletter_text = substr(trim($_POST['newsletter_text'] ?? ''), 0, 500);
        $newsletter_url = substr(trim($_POST['newsletter_url'] ?? ''), 0, 300);
        $platform_url = substr(trim($_POST['platform_url'] ?? ''), 0, 300);

        $st = db()->prepare('UPDATE settings SET logo_url=?, contact_email=?, contact_phone=?, contact_address=?, link_programmes=?, link_formations=?, link_actualites=?, link_partenaires=?, social_facebook=?, social_linkedin=?, social_youtube=?, newsletter_text=?, newsletter_url=?, platform_url=?, updated_at=NOW() WHERE id=1');
        $st->execute([$logo_url, $contact_email, $contact_phone, $contact_address, $link_programmes, $link_formations, $link_actualites, $link_partenaires, $social_facebook, $social_linkedin, $social_youtube, $newsletter_text, $newsletter_url, $platform_url]);
        header('Location: ' . base_url('/admin/settings'));
        return '';
    }
    public function contactsExportCsv(): string
    {
        // Filters: start, end (YYYY-MM-DD), q (search)
        $start = trim($_GET['start'] ?? '');
        $end = trim($_GET['end'] ?? '');
        $q = trim($_GET['q'] ?? '');
        $sql = 'SELECT name,email,phone,message,created_at FROM contact_messages WHERE 1';
        $params = [];
        if ($start !== '') {
            $sql .= ' AND DATE(created_at) >= ?';
            $params[] = $start;
        }
        if ($end !== '') {
            $sql .= ' AND DATE(created_at) <= ?';
            $params[] = $end;
        }
        if ($q !== '') {
            $sql .= ' AND (name LIKE ? OR email LIKE ? OR phone LIKE ? OR message LIKE ?)';
            $params[] = "%$q%";
            $params[] = "%$q%";
            $params[] = "%$q%";
            $params[] = "%$q%";
        }
        $sql .= ' ORDER BY created_at DESC';
        $st = db()->prepare($sql);
        $st->execute($params);
        $items = $st->fetchAll();
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="contacts_ifmap.csv"');
        $out = fopen('php://output', 'w');
        // BOM UTF-8 for Excel compatibility
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, ['Nom', 'Email', 'Téléphone', 'Message', 'Date']);
        foreach ($items as $row) {
            fputcsv($out, [$row['name'], $row['email'], $row['phone'], $row['message'], $row['created_at']]);
        }
        fclose($out);
        return '';
    }
    // Changement mot de passe
    public function passwordForm(): string
    {
        $title = 'Changer mot de passe';
        return view('admin/security/password', compact('title'));
    }

    public function passwordUpdate(): string
    {
        require_csrf();
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $user = current_user();
        if (!$user) {
            header('Location: ' . base_url('/login'));
            return '';
        }
        if ($new === '' || $confirm === '' || $current === '') {
            $error = 'Tous les champs sont requis';
            $title = 'Changer mot de passe';
            return view('admin/security/password', compact('title', 'error'));
        }
        if ($new !== $confirm) {
            $error = 'Confirmation différente';
            $title = 'Changer mot de passe';
            return view('admin/security/password', compact('title', 'error'));
        }
        $st = db()->prepare('SELECT * FROM users WHERE id=?');
        $st->execute([$user['id']]);
        $row = $st->fetch();
        if (!$row || !password_verify($current, $row['password_hash'])) {
            $error = 'Mot de passe actuel incorrect';
            $title = 'Changer mot de passe';
            return view('admin/security/password', compact('title', 'error'));
        }
        $newHash = password_hash($new, PASSWORD_BCRYPT);
        $up = db()->prepare('UPDATE users SET password_hash=? WHERE id=?');
        $up->execute([$newHash, $user['id']]);
        $success = 'Mot de passe mis à jour';
        $title = 'Changer mot de passe';
        return view('admin/security/password', compact('title', 'success'));
    }
}
