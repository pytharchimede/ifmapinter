<?php

namespace App\Controllers;

class HomeController
{
    protected function convertToWebpIfPossibleGeneric(string $sourcePath, string $ext, string $baseDir, string &$urlField, string $publicSubdir): void
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
        } catch (\Throwable $e) { /* ignore */
        }
    }
    public function index(): string
    {
        $title = 'IFMAP – Accueil';
        // Chargement des données BDD
        $news = db()->query('SELECT * FROM news ORDER BY COALESCE(published_at, created_at) DESC LIMIT 3')->fetchAll();
        $programmes = db()->query('SELECT * FROM programmes ORDER BY id DESC LIMIT 3')->fetchAll();
        $formations = db()->query('SELECT * FROM formations ORDER BY id DESC LIMIT 4')->fetchAll();
        $partners = db()->query('SELECT * FROM partners WHERE COALESCE(enabled,1)=1 ORDER BY id DESC LIMIT 8')->fetchAll();
        $events = db()->query("SELECT * FROM events 
                        WHERE COALESCE(enabled,1)=1 
                            AND (COALESCE(status,'draft')='published' OR (publish_at IS NOT NULL AND publish_at <= NOW()))
                            AND event_date >= NOW()
                        ORDER BY event_date ASC LIMIT 4")->fetchAll();
        $testimonials = db()->query("SELECT * FROM testimonials WHERE COALESCE(status,'pending')='approved' ORDER BY id DESC LIMIT 6")->fetchAll();
        // Stats
        $stats = [
            'programmes' => (int)db()->query('SELECT COUNT(*) FROM programmes')->fetchColumn(),
            'formations' => (int)db()->query('SELECT COUNT(*) FROM formations')->fetchColumn(),
            'partners'   => (int)db()->query('SELECT COUNT(*) FROM partners')->fetchColumn(),
            'news'       => (int)db()->query('SELECT COUNT(*) FROM news')->fetchColumn(),
        ];
        return view('home', compact('title', 'news', 'programmes', 'formations', 'partners', 'events', 'testimonials', 'stats'));
    }

    public function submitTestimonial(): string
    {
        require_csrf();
        $name = substr(trim($_POST['name'] ?? ''), 0, 191);
        $role = substr(trim($_POST['role'] ?? ''), 0, 191);
        $message = trim($_POST['message'] ?? '');
        $avatar_url = trim($_POST['avatar_url'] ?? '');
        if ($name === '' || $message === '') {
            header('Location: ' . base_url('/?tks=0#temoignages'));
            return '';
        }
        if (!empty($_FILES['avatar_file']['name'])) {
            $upl = $_FILES['avatar_file'];
            if ($upl['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($upl['name'], PATHINFO_EXTENSION));
                $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024;
                if (in_array($ext, $allowedImg) && $upl['size'] <= $maxSize) {
                    $baseDir = __DIR__ . '/../../uploads/testimonials';
                    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
                    $fname = uniqid('t_') . '.' . $ext;
                    $dest = $baseDir . '/' . $fname;
                    if (move_uploaded_file($upl['tmp_name'], $dest)) {
                        $avatar_url = base_url('uploads/testimonials/' . $fname);
                        // Optional WebP conversion using generic helper if present
                        if (method_exists($this, 'convertToWebpIfPossibleGeneric')) {
                            $tmp = $avatar_url;
                            $this->convertToWebpIfPossibleGeneric($dest, $ext, $baseDir, $tmp, 'uploads/testimonials');
                            $avatar_url = $tmp;
                        }
                    }
                }
            }
        }
        $st = db()->prepare("INSERT INTO testimonials(name, role, message, avatar_url, status, created_at) VALUES(?,?,?,?, 'pending', NOW())");
        $st->execute([$name, $role, $message, $avatar_url]);
        header('Location: ' . base_url('/?tks=1#temoignages'));
        return '';
    }
}
