<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'IFMAP – Admin' ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="admin">
    <header class="admin-header">
        <div class="wrap">
            <div class="admin-brand">
                <div class="logo">IF</div>
                <span>IFMAP Admin</span>
            </div>
            <nav class="admin-nav">
                <a href="<?= base_url('admin') ?>" class="<?= ($_SERVER['REQUEST_URI'] ?? '') === base_url('admin') ? 'active' : '' ?>">Dashboard</a>
                <a href="<?= base_url('admin/news') ?>">Actualités</a>
                <a href="<?= base_url('admin/programmes') ?>">Programmes</a>
                <a href="<?= base_url('admin/formations') ?>">Formations</a>
                <a href="<?= base_url('admin/partners') ?>">Partenaires</a>
                <a href="<?= base_url('admin/media') ?>">Médias</a>
                <a href="<?= base_url('admin/contacts') ?>">Contacts</a>
                <a href="<?= base_url('admin/password') ?>">Sécurité</a>
                <a href="<?= base_url('logout') ?>">Déconnexion</a>
            </nav>
        </div>
    </header>
    <main class="admin-main">