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
