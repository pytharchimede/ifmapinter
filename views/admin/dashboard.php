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
            <a class="btn-admin" href="<?= base_url('admin/media') ?>">Médias</a>
            <a class="btn-admin" href="<?= base_url('admin/contacts') ?>">Contacts</a>
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
            <tr>
                <th>Messages de contact</th>
                <td><?= (int)db()->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn() ?></td>
            </tr>
        </table>
    </div>
    <div class="admin-card">
        <h3>Derniers messages de contact</h3>
        <table class="table">
            <tr>
                <th>Date</th>
                <th>Nom</th>
                <th>Message</th>
            </tr>
            <?php
            $latest = db()->query('SELECT name,message,created_at FROM contact_messages ORDER BY created_at DESC LIMIT 5')->fetchAll();
            foreach ($latest as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['created_at']) ?></td>
                    <td><?= htmlspecialchars($m['name']) ?></td>
                    <td><?= htmlspecialchars(mb_strimwidth($m['message'], 0, 80, '…', 'UTF-8')) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p style="margin-top:.6rem"><a class="btn-admin" href="<?= base_url('admin/contacts') ?>">Voir tous</a></p>
    </div>
</div>

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>