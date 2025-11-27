<?php

namespace App\Controllers;

class AdminController
{
    public function dashboard(): string
    {
        $title = 'Admin – Tableau de bord';
        return view('admin/dashboard', compact('title'));
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
        $st = db()->prepare('INSERT INTO news(title, body, image_url, published_at) VALUES(?,?,?,?)');
        $st->execute([
            trim($_POST['title'] ?? ''),
            trim($_POST['body'] ?? ''),
            trim($_POST['image_url'] ?? ''),
            trim($_POST['published_at'] ?? null) ?: null,
        ]);
        header('Location: ' . base_url('/admin/news'));
        return '';
    }
    public function newsUpdate(): string
    {
        require_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $st = db()->prepare('UPDATE news SET title=?, body=?, image_url=?, published_at=? WHERE id=?');
        $st->execute([
            trim($_POST['title'] ?? ''),
            trim($_POST['body'] ?? ''),
            trim($_POST['image_url'] ?? ''),
            trim($_POST['published_at'] ?? null) ?: null,
            $id
        ]);
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
        $st = db()->prepare('INSERT INTO programmes(name, description, image_url) VALUES(?,?,?)');
        $st->execute([
            trim($_POST['name'] ?? ''),
            trim($_POST['description'] ?? ''),
            trim($_POST['image_url'] ?? ''),
        ]);
        header('Location: ' . base_url('/admin/programmes'));
        return '';
    }
    public function programmesUpdate(): string
    {
        require_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $st = db()->prepare('UPDATE programmes SET name=?, description=?, image_url=? WHERE id=?');
        $st->execute([
            trim($_POST['name'] ?? ''),
            trim($_POST['description'] ?? ''),
            trim($_POST['image_url'] ?? ''),
            $id
        ]);
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
        $title = 'Admin – Formations';
        return view('admin/formations/index', compact('title', 'items'));
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
        $st = db()->prepare('INSERT INTO formations(name, image_url) VALUES(?,?)');
        $st->execute([
            trim($_POST['name'] ?? ''),
            trim($_POST['image_url'] ?? ''),
        ]);
        header('Location: ' . base_url('/admin/formations'));
        return '';
    }
    public function formationsUpdate(): string
    {
        require_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $st = db()->prepare('UPDATE formations SET name=?, image_url=? WHERE id=?');
        $st->execute([
            trim($_POST['name'] ?? ''),
            trim($_POST['image_url'] ?? ''),
            $id
        ]);
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
        $st = db()->prepare('INSERT INTO partners(name, logo_url) VALUES(?,?)');
        $st->execute([
            trim($_POST['name'] ?? ''),
            trim($_POST['logo_url'] ?? ''),
        ]);
        header('Location: ' . base_url('/admin/partners'));
        return '';
    }
    public function partnersUpdate(): string
    {
        require_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $st = db()->prepare('UPDATE partners SET name=?, logo_url=? WHERE id=?');
        $st->execute([
            trim($_POST['name'] ?? ''),
            trim($_POST['logo_url'] ?? ''),
            $id
        ]);
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
