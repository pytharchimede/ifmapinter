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
            'create_users_table' => function (PDO $pdo) {
                $pdo->exec("CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    email VARCHAR(191) UNIQUE NOT NULL,
                    password_hash VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB");
                // Seed admin par défaut si vide
                $count = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
                if ($count === 0) {
                    $email = 'admin@ifmap.ci';
                    $pass = password_hash('admin123', PASSWORD_BCRYPT);
                    $st = $pdo->prepare('INSERT INTO users(email, password_hash) VALUES(?, ?)');
                    $st->execute([$email, $pass]);
                }
            },
            'create_carousels_table' => function (PDO $pdo) {
                $pdo->exec("CREATE TABLE IF NOT EXISTS carousels (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        position INT NOT NULL DEFAULT 1,
                        title VARCHAR(120) NULL,
                        caption VARCHAR(200) NULL,
                        description VARCHAR(240) NULL,
                        button_text VARCHAR(60) NULL,
                        button_url VARCHAR(255) NULL,
                        background_url VARCHAR(255) NOT NULL,
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
            },
            'create_events_table' => function (PDO $pdo) {
                $pdo->exec("CREATE TABLE IF NOT EXISTS events (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(191) NOT NULL,
                    description TEXT NULL,
                    event_date DATETIME NOT NULL,
                    category VARCHAR(191) NULL,
                    language VARCHAR(64) NULL,
                    program VARCHAR(191) NULL,
                    location VARCHAR(191) NULL,
                    cta_url VARCHAR(255) NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB");
            },
            'create_testimonials_table' => function (PDO $pdo) {
                $pdo->exec("CREATE TABLE IF NOT EXISTS testimonials (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(191) NOT NULL,
                    role VARCHAR(191) NULL,
                    message TEXT NOT NULL,
                    avatar_url VARCHAR(255) NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB");
            },
            'create_media_table' => function (PDO $pdo) {
                $pdo->exec("CREATE TABLE IF NOT EXISTS media (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(191) NOT NULL,
                    type ENUM('image','video') NOT NULL DEFAULT 'image',
                    url VARCHAR(255) NOT NULL,
                    thumb_url VARCHAR(255) NULL,
                    description TEXT NULL,
                    category VARCHAR(191) NULL,
                    tags VARCHAR(255) NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB");
                $count = (int)$pdo->query('SELECT COUNT(*) FROM media')->fetchColumn();
                if ($count === 0) {
                    $seed = $pdo->prepare('INSERT INTO media(title,type,url,thumb_url,description) VALUES (?,?,?,?,?)');
                    $seed->execute(['Campus Énergie', 'image', 'https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=1200&q=80', null, 'Centre technique en activité']);
                    $seed->execute(['Atelier Solaire', 'image', 'https://images.unsplash.com/photo-1509395062183-67c5ad6faff9?auto=format&fit=crop&w=1200&q=80', null, 'Formation panneaux photovoltaïques']);
                    $seed->execute(['Partenariat Entreprise', 'image', 'https://images.unsplash.com/photo-1498079022511-d15614cb1c02?auto=format&fit=crop&w=1200&q=80', null, 'Signature partenariat']);
                    $seed->execute(['Présentation Programme', 'video', 'https://www.youtube.com/embed/dQw4w9WgXcQ', null, 'Capsule vidéo présentation IFMAP']);
                }
            },
            'create_contact_messages_table' => function (PDO $pdo) {
                $pdo->exec("CREATE TABLE IF NOT EXISTS contact_messages (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(191) NOT NULL,
                    email VARCHAR(191) NULL,
                    phone VARCHAR(64) NULL,
                    message TEXT NOT NULL,
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

        // Patch: add publication fields to events (status, publish_at, enabled)
        try {
            $existsStmt = $pdo->prepare('SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?');
            // status
            $existsStmt->execute(['events', 'status']);
            if (!$existsStmt->fetchColumn()) {
                $pdo->exec("ALTER TABLE events ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'draft' AFTER description");
            }
            // publish_at
            $existsStmt->execute(['events', 'publish_at']);
            if (!$existsStmt->fetchColumn()) {
                $pdo->exec("ALTER TABLE events ADD COLUMN publish_at DATETIME NULL AFTER status");
            }
            // enabled
            $existsStmt->execute(['events', 'enabled']);
            if (!$existsStmt->fetchColumn()) {
                $pdo->exec("ALTER TABLE events ADD COLUMN enabled TINYINT(1) NOT NULL DEFAULT 1 AFTER cta_url");
            }
        } catch (\PDOException $e) {
            // ignore
        }

        // Patch: ajouter colonne `read` (lu) sur contact_messages si manquante
        try {
            $existsStmt = $pdo->prepare('SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?');
            $existsStmt->execute(['contact_messages', 'read']);
            $colExists = (bool)$existsStmt->fetchColumn();
            if (!$colExists) {
                $pdo->exec('ALTER TABLE contact_messages ADD COLUMN `read` TINYINT(1) NOT NULL DEFAULT 0');
            }
        } catch (\PDOException $e) {
            // Ignore silently if check fails; do not block migration
        }

        // Patch: ajouter colonne `status` sur testimonials (modération)
        try {
            $existsStmt = $pdo->prepare('SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?');
            $existsStmt->execute(['testimonials', 'status']);
            $colExists = (bool)$existsStmt->fetchColumn();
            if (!$colExists) {
                $pdo->exec("ALTER TABLE testimonials ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'pending' AFTER avatar_url");
            }
        } catch (\PDOException $e) {
            // Ignore silently
        }
    }

    private static function fatal(string $message): void
    {
        http_response_code(500);
        echo \view('errors/500', ['title' => 'Erreur serveur', 'message' => $message]);
        exit;
    }
}
