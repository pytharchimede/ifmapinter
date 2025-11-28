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
    <div class="admin-app">
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <div class="logo">IF</div>
                <span>IFMAP Admin</span>
            </div>
            <nav class="admin-side-nav">
                <a href="<?= base_url('admin') ?>">Dashboard</a>
                <span class="nav-label">Accueil</span>
                <a href="<?= base_url('admin/carousels') ?>">Carrousels</a>
                <a href="<?= base_url('admin/programmes') ?>">Nos Programmes</a>
                <a href="<?= base_url('admin/formations') ?>">Formations IFMAP</a>
                <a href="<?= base_url('admin/centres') ?>">Instituts & Centres</a>
                <a href="<?= base_url('admin/partners') ?>">Entreprises & Partenaires</a>
                <span class="nav-label">Actualités</span>
                <a href="<?= base_url('admin/news') ?>">Actualités (articles)</a>
                <a href="<?= base_url('admin/rss-sources') ?>">Flux RSS / APIs</a>
            </nav>
        </aside>
        <main class="admin-main">