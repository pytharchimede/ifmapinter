<?php include __DIR__ . '/../../partials/header.php'; ?>

<section class="section">
    <div class="container">
        <h2>Partenaires</h2>
        <p><a class="btn-primary" href="<?= base_url('/admin/partners/create') ?>">Nouveau partenaire</a> · <a href="<?= base_url('/admin') ?>">Retour</a></p>
        <table class="table" border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Logo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $it): ?>
                    <tr>
                        <td><?= (int)$it['id'] ?></td>
                        <td><?= htmlspecialchars($it['name']) ?></td>
                        <td><?= htmlspecialchars($it['logo_url'] ?? '') ?></td>
                        <td>
                            <a href="<?= base_url('/admin/partners/edit?id=' . (int)$it['id']) ?>">Modifier</a> |
                            <a href="<?= base_url('/admin/partners/delete?id=' . (int)$it['id']) ?>" onclick="return confirm('Supprimer ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>