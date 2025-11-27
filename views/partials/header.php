<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'IFMAP' ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

</head>

<body>

    <!-- ================= HEADER ================= -->
    <header id="header">
        <div class="container nav">
            <div class="logo">
                <a href="<?= base_url() ?>">
                    <img src="https://ifmap.ci/uploads/system/1fb9ea08a27e58c71dc6e639284b74eb.png" alt="IFMAP Logo">
                </a>
            </div>

            <nav>
                <ul class="menu">
                    <li><a href="<?= base_url('institut') ?>">L’Institut</a></li>
                    <li><a href="<?= base_url('#programmes') ?>">Programmes</a></li>
                    <li><a href="<?= base_url('#formations') ?>">Formations</a></li>
                    <li><a href="<?= base_url('#centres') ?>">Ecoles</a></li>
                    <li><a href="<?= base_url('campus') ?>">Campus</a></li>
                    <li><a href="<?= base_url('#partenaires') ?>">Partenaires</a></li>
                    <li><a href="<?= base_url('#news') ?>">Actualités</a></li>
                    <li><a href="<?= base_url('alumni') ?>">Alumni</a></li>
                    <li><a href="<?= base_url('contact') ?>">Contact</a></li>
                    <li><a href="<?= base_url('galerie') ?>">Galerie</a></li>
                </ul>
                <div class="actions" style="display:flex; align-items:center; gap:1rem;">
                    <button class="theme-toggle" aria-label="Basculer le thème">🌓</button>
                    <div class="toggle" id="menu-toggle"><i class="fa fa-bars"></i></div>
                </div>
            </nav>
        </div>
    </header>