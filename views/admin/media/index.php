<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<h2>Médias</h2>
<p><a class="btn-admin" href="<?= base_url('admin/media/create') ?>">Ajouter</a></p>
<table class="table" style="width:100%;margin-top:1rem;">
    <tr>
        <th>Titre</th>
        <th>Type</th>
        <th>Catégorie</th>
        <th>Tags</th>
        <th></th>
    </tr>
    <?php foreach ($items as $m): ?>
        <tr>
            <td><?= htmlspecialchars($m['title']) ?></td>
            <td><?= htmlspecialchars($m['type']) ?></td>
            <td><?= htmlspecialchars($m['category'] ?? '') ?></td>
            <td><?= htmlspecialchars($m['tags'] ?? '') ?></td>
            <td style="white-space:nowrap;">
                <a class="btn-admin" href="<?= base_url('admin/media/edit?id=' . (int)$m['id']) ?>">Éditer</a>
                <a class="btn-admin" href="<?= base_url('admin/media/delete?id=' . (int)$m['id']) ?>" onclick="return confirm('Supprimer ?')">×</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>