<?php include __DIR__ . '/../partials/header.php'; ?>

<section class="section">
    <div class="container">
        <h2>Tableau de bord</h2>
        <p>Bienvenue <?= htmlspecialchars(current_user()['email'] ?? '') ?>.</p>

        <div class="grid-4">
            <a class="card" href="<?= base_url('/admin/news') ?>" style="padding:20px;display:block;">
                <h3>Actualités</h3>
                <p>Gérer les news</p>
            </a>
            <a class="card" href="<?= base_url('/admin/programmes') ?>" style="padding:20px;display:block;">
                <h3>Programmes</h3>
                <p>Gérer les programmes</p>
            </a>
            <a class="card" href="<?= base_url('/admin/formations') ?>" style="padding:20px;display:block;">
                <h3>Formations</h3>
                <p>Gérer les formations</p>
            </a>
            <a class="card" href="<?= base_url('/admin/partners') ?>" style="padding:20px;display:block;">
                <h3>Partenaires</h3>
                <p>Gérer les partenaires</p>
            </a>
        </div>

        <p style="margin-top:20px;"><a href="<?= base_url('/logout') ?>">Se déconnecter</a></p>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>