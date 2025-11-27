<?php

spl_autoload_register(function ($class) {
    // Namespaces: App\Core, App\Controllers, App\Models
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/..' . DIRECTORY_SEPARATOR; // .../app/

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len); // Core\Router
    $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
