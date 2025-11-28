<?php

use App\Core\Database;

function config(): array
{
    static $cfg;
    if (!$cfg) {
        $cfg = require __DIR__ . '/config.php';
    }
    return $cfg;
}

function base_url(string $path = ''): string
{
    $cfgBase = config()['app']['base_url'] ?? '';
    $cfgBase = rtrim($cfgBase, '/');

    // Auto-détection du sous-dossier si base_url non défini explicitement
    if ($cfgBase === '' || $cfgBase === '/') {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $detected = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        $base = $detected;
    } else {
        $base = $cfgBase;
    }

    if ($base === '/') $base = '';

    $path = ltrim($path, '/');
    return ($base ?: '') . ($path ? '/' . $path : '/');
}

function view(string $name, array $data = []): string
{
    $file = __DIR__ . '/../views/' . str_replace(['..', '\\'], '', $name) . '.php';
    if (!file_exists($file)) {
        return "<h1>Vue introuvable: {$name}</h1>";
    }
    extract($data);
    ob_start();
    include $file;
    return (string) ob_get_clean();
}

function db(): PDO
{
    return Database::pdo();
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_authenticated(): bool
{
    return current_user() !== null;
}

function login_user(array $user): void
{
    $_SESSION['user'] = ['id' => $user['id'], 'email' => $user['email']];
}

function logout_user(): void
{
    unset($_SESSION['user']);
}

function require_auth(callable $next)
{
    if (!is_authenticated()) {
        header('Location: ' . base_url('/login'));
        return '';
    }
    return $next();
}

// ================= CSRF ================= //
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

function verify_csrf(): bool
{
    $sent = $_POST['_token'] ?? '';
    return hash_equals($_SESSION['csrf_token'] ?? '', $sent);
}

function require_csrf(): void
{
    if (!verify_csrf()) {
        http_response_code(419);
        echo view('errors/500', ['title' => 'Erreur sécurité', 'message' => 'Jeton CSRF invalide']);
        exit;
    }
}

// ================= i18n ================= //
function resolve_lang(): string
{
    // 1) query param ?lang=xx
    if (isset($_GET['lang']) && preg_match('/^[a-z]{2}(-[A-Z]{2})?$/', $_GET['lang'])) {
        return $_GET['lang'];
    }
    // 2) cookie
    if (!empty($_COOKIE['lang']) && preg_match('/^[a-z]{2}(-[A-Z]{2})?$/', $_COOKIE['lang'])) {
        return $_COOKIE['lang'];
    }
    // 3) Accept-Language
    $al = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    if ($al) {
        $code = substr($al, 0, 2);
        if (preg_match('/^[a-z]{2}$/', $code)) return $code;
    }
    // 4) fallback config
    $def = config()['app']['default_lang'] ?? 'fr';
    return $def;
}

function t(string $key, ?string $lang = null): string
{
    static $cache = [];
    $lang = $lang ?: resolve_lang();
    if (!isset($cache[$lang])) {
        try {
            $st = db()->prepare('SELECT `key`, `value` FROM translations WHERE lang=?');
            $st->execute([$lang]);
            $rows = $st->fetchAll();
            $cache[$lang] = [];
            foreach ($rows as $r) {
                $cache[$lang][$r['key']] = $r['value'];
            }
        } catch (Throwable $e) {
            $cache[$lang] = [];
        }
    }
    if (isset($cache[$lang][$key])) return (string)$cache[$lang][$key];
    // fallback: try default lang, else return key
    $def = config()['app']['default_lang'] ?? 'fr';
    if ($def !== $lang) {
        if (!isset($cache[$def])) {
            try {
                $st = db()->prepare('SELECT `key`, `value` FROM translations WHERE lang=?');
                $st->execute([$def]);
                $rows = $st->fetchAll();
                $cache[$def] = [];
                foreach ($rows as $r) {
                    $cache[$def][$r['key']] = $r['value'];
                }
            } catch (Throwable $e) {
                $cache[$def] = [];
            }
        }
        if (isset($cache[$def][$key])) return (string)$cache[$def][$key];
    }
    return $key;
}

function set_lang_cookie(string $lang): void
{
    if (!preg_match('/^[a-z]{2}(-[A-Z]{2})?$/', $lang)) return;
    setcookie('lang', $lang, time() + 365 * 24 * 3600, '/');
}
