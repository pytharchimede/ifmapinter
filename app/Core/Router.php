<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$this->normalize($path)] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$this->normalize($path)] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        // Détecte le sous-dossier (ex: /ifmap_site_web) et le retire du chemin
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = str_replace('\\', '/', dirname($scriptName));
        $basePath = rtrim($basePath, '/');
        if ($basePath !== '' && $basePath !== '/') {
            $len = strlen($basePath);
            if (substr($path, 0, $len) === $basePath) {
                $path = substr($path, $len) ?: '/';
            }
        }

        $path = $this->normalize($path);

        $handler = $this->routes[$method][$path] ?? null;
        if (!$handler) {
            http_response_code(404);
            echo view('errors/404', ['title' => 'Page non trouvée']);
            return;
        }
        echo call_user_func($handler);
    }

    private function normalize(string $path): string
    {
        if ($path === '') return '/';
        if ($path[0] !== '/') $path = '/' . $path;
        return rtrim($path, '/') ?: '/';
    }
}
