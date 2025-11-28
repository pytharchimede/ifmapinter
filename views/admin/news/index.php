<?php include __DIR__ . '/../../partials/admin_header.php'; ?>

<section class="section">
    <div class="container">
        <h2>Admin – Actualités</h2>
        <div class="actions-bar" style="margin:12px 0; display:flex; gap:8px;">
            <a class="btn btn-success" href="<?= base_url('/admin/news/create') ?>">Créer une actualité</a>
            <a class="btn" href="<?= base_url('/admin') ?>">Retour</a>
            <a class="btn" href="<?= base_url('/admin/rss-sources') ?>">Sources RSS</a>
        </div>
        <table class="table" border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Source</th>
                    <th>URL</th>
                    <th>Statut</th>
                    <th>Publié le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $it): ?>
                    <tr>
                        <td><?= (int)$it['id'] ?></td>
                        <td><?= htmlspecialchars($it['title']) ?></td>
                        <td><?= htmlspecialchars($it['source'] ?? 'IFMAP') ?></td>
                        <td>
                            <?php if (!empty($it['article_url'])): ?>
                                <a href="<?= htmlspecialchars($it['article_url']) ?>" target="_blank" rel="noopener">Lien</a>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="display:inline-block;padding:4px 8px;border-radius:12px;background:<?= ($it['status'] ?? 'published') === 'published' ? '#e6ffed' : '#fff5e6' ?>;color:#333;">
                                <?= ($it['status'] ?? 'published') === 'published' ? 'Publié' : 'Brouillon' ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($it['published_at'] ?? '') ?></td>
                        <td>
                            <a href="<?= base_url('/admin/news/edit?id=' . (int)$it['id']) ?>">Modifier</a> |
                            <a href="<?= base_url('/admin/news/delete?id=' . (int)$it['id']) ?>" onclick="return confirm('Supprimer ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>