<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<section class="section">
    <div class="container" style="max-width:720px;">
        <h2><?= htmlspecialchars($title ?? 'Source RSS') ?></h2>
        <p><a href="<?= base_url('/admin/rss-sources') ?>">Retour</a></p>
        <form method="post" action="<?= base_url(($item ?? null) ? '/admin/rss-sources/edit' : '/admin/rss-sources/create') ?>" class="card" style="padding:20px;">
            <?= csrf_field() ?>
            <?php if (!empty($item)): ?>
                <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
            <?php endif; ?>
            <div class="form-group">
                <label>Nom</label>
                <input class="form-control" type="text" name="name" value="<?= htmlspecialchars($item['name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>URL du flux RSS</label>
                <input class="form-control" type="url" name="url" value="<?= htmlspecialchars($item['url'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Activée</label>
                <?php $en = (int)($item['enabled'] ?? 1); ?>
                <select class="form-control" name="enabled">
                    <option value="1" <?= $en === 1 ? 'selected' : '' ?>>Oui</option>
                    <option value="0" <?= $en === 0 ? 'selected' : '' ?>>Non</option>
                </select>
            </div>
            <div class="admin-actions" style="margin-top:12px;display:flex;gap:8px;">
                <button class="btn btn-primary" type="submit">Enregistrer</button>
                <a class="btn" href="<?= base_url('/admin/rss-sources') ?>">Annuler</a>
            </div>
        </form>
    </div>
</section>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>