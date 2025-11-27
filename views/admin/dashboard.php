<?php include __DIR__ . '/../partials/admin_header.php'; ?>

<div class="grid-2">
    <div class="admin-card">
        <h3>Bienvenue</h3>
        <p>Bonjour <?= htmlspecialchars(current_user()['email'] ?? '') ?>, voici vos accès rapides.</p>
        <div style="display:flex; gap:.6rem; flex-wrap:wrap; margin-top:.6rem;">
            <a class="btn-admin" href="<?= base_url('admin/news') ?>">Actualités</a>
            <a class="btn-admin" href="<?= base_url('admin/programmes') ?>">Programmes</a>
            <a class="btn-admin" href="<?= base_url('admin/formations') ?>">Formations</a>
            <a class="btn-admin" href="<?= base_url('admin/partners') ?>">Partenaires</a>
        </div>
    </div>
    <div class="admin-card">
        <h3>Statistiques</h3>
        <table class="table">
            <tr>
                <th>Programmes</th>
                <td><?= (int)db()->query('SELECT COUNT(*) FROM programmes')->fetchColumn() ?></td>
            </tr>
            <tr>
                <th>Formations</th>
                <td><?= (int)db()->query('SELECT COUNT(*) FROM formations')->fetchColumn() ?></td>
            </tr>
            <tr>
                <th>Partenaires</th>
                <td><?= (int)db()->query('SELECT COUNT(*) FROM partners')->fetchColumn() ?></td>
            </tr>
            <tr>
                <th>Actualités</th>
                <td><?= (int)db()->query('SELECT COUNT(*) FROM news')->fetchColumn() ?></td>
            </tr>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>