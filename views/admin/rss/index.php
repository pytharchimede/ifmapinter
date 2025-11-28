<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<section class="section">
    <div class="container">
        <h2>Admin – Sources RSS</h2>
        <div class="actions-bar" style="margin:12px 0; display:flex; gap:8px;">
            <a class="btn btn-success" href="<?= base_url('/admin/rss-sources/create') ?>">Ajouter une source</a>
            <a class="btn" href="<?= base_url('/admin/news') ?>">Retour aux actualités</a>
            <form method="post" action="<?= base_url('/admin/rss-sources/ingest') ?>">
                <?= csrf_field() ?>
                <button class="btn btn-primary" type="submit">Importer maintenant (RSS → Actualités)</button>
            </form>
        </div>
        <?php if (!empty($success ?? '')): ?>
            <div class="alert success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <table class="table" border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>URL</th>
                    <th>Activée</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($items ?? []) as $it): ?>
                    <tr>
                        <td><?= (int)$it['id'] ?></td>
                        <td><?= htmlspecialchars($it['name']) ?></td>
                        <td><a href="<?= htmlspecialchars($it['url']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($it['url']) ?></a></td>
                        <td><?= ((int)$it['enabled'] === 1) ? 'Oui' : 'Non' ?></td>
                        <td>
                            <a href="<?= base_url('/admin/rss-sources/edit?id=' . (int)$it['id']) ?>">Modifier</a> |
                            <a href="<?= base_url('/admin/rss-sources/toggle?id=' . (int)$it['id']) ?>">Basculer</a> |
                            <a href="<?= base_url('/admin/rss-sources/delete?id=' . (int)$it['id']) ?>" onclick="return confirm('Supprimer cette source ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>