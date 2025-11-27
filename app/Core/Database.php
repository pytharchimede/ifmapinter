<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;
    private static array $config;

    public static function init(array $config): void
    {
        self::$config = $config['db'];

        $host = self::$config['host'];
        $user = self::$config['user'];
        $pass = self::$config['pass'];
        $dbName = self::$config['name'];
        $charset = self::$config['charset'] ?? 'utf8mb4';

        try {
            // Connexion sans base pour créer la base si besoin
            $dsnNoDb = "mysql:host={$host};charset={$charset}";
            $pdo = new PDO($dsnNoDb, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET {$charset} COLLATE {$charset}_general_ci");
        } catch (PDOException $e) {
            self::fatal('Erreur de connexion (création DB): ' . $e->getMessage());
        }

        try {
            $dsn = "mysql:host={$host};dbname={$dbName};charset={$charset}";
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            self::fatal('Erreur de connexion (sélection DB): ' . $e->getMessage());
        }
    }

    public static function pdo(): PDO
    {
        if (!self::$pdo) {
            self::fatal('Database non initialisée.');
        }
        return self::$pdo;
    }

    public static function migrate(): void
    {
        $pdo = self::pdo();
        // Table migrations pour éviter de répéter
        $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(191) UNIQUE, run_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");

        $migrations = [
            'create_news_table' => function (PDO $pdo) {
                $pdo->exec("CREATE TABLE IF NOT EXISTS news (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(191) NOT NULL,
                    body TEXT NULL,
                    image_url VARCHAR(255) NULL,
                    published_at DATETIME NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB");
            },
            'create_programmes_table' => function (PDO $pdo) {
                $pdo->exec("CREATE TABLE IF NOT EXISTS programmes (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(191) NOT NULL,
                    description TEXT NULL,
                    image_url VARCHAR(255) NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB");
            },
            'create_formations_table' => function (PDO $pdo) {
                $pdo->exec("CREATE TABLE IF NOT EXISTS formations (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(191) NOT NULL,
                    image_url VARCHAR(255) NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB");
            },
            'create_partners_table' => function (PDO $pdo) {
                $pdo->exec("CREATE TABLE IF NOT EXISTS partners (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(191) NOT NULL,
                    logo_url VARCHAR(255) NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB");
            },
            'create_pages_table' => function (PDO $pdo) {
                $pdo->exec("CREATE TABLE IF NOT EXISTS pages (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    slug VARCHAR(191) UNIQUE NOT NULL,
                    title VARCHAR(191) NOT NULL,
                    content LONGTEXT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB");
            }
        ];

        foreach ($migrations as $name => $fn) {
            $stmt = $pdo->prepare('SELECT 1 FROM migrations WHERE name = ? LIMIT 1');
            $stmt->execute([$name]);
            if (!$stmt->fetchColumn()) {
                $fn($pdo);
                $ins = $pdo->prepare('INSERT INTO migrations (name) VALUES (?)');
                $ins->execute([$name]);
            }
        }
    }

    private static function fatal(string $message): void
    {
        http_response_code(500);
        echo view('errors/500', ['title' => 'Erreur serveur', 'message' => $message]);
        exit;
    }
}
