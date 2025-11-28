<?php
function config_value(string $key, $default = null)
{
    static $cfg;
    if ($cfg === null) {
        $path = __DIR__ . '/../config.php';
        $cfg = file_exists($path) ? include $path : [];
    }
    return $cfg[$key] ?? $default;
}
