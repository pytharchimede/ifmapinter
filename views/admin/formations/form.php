<?php include __DIR__ . '/../../partials/admin_header.php'; ?>

<section class="section">
    <div class="container" style="max-width:1100px;">
        <h2><?= htmlspecialchars($title) ?></h2>
        <p><a href="<?= base_url('/admin/formations') ?>">Retour</a></p>
        <form method="post" action="<?= base_url($item ? '/admin/formations/edit' : '/admin/formations/create') ?>" class="card admin-form" style="padding:24px;" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= (int)$item['id'] ?>" />
            <?php endif; ?>
            <div class="panel">
                <h3>Illustration</h3>
                <div class="form-group">
                    <label>Image (upload)</label>
                    <input class="form-control" type="file" name="image_file" accept="image/*" />
                </div>
                <div class="form-group">
                    <label>Image URL (optionnel)</label>
                    <input class="form-control" type="url" name="image_url" value="<?= htmlspecialchars($item['image_url'] ?? '') ?>" />
                </div>
                <?php $preview = trim($item['image_url'] ?? ''); ?>
                <div class="image-preview">
                    <?php if ($preview !== ''): ?>
                        <img src="<?= htmlspecialchars($preview) ?>" alt="aperçu">
                    <?php else: ?>
                        <span style="color:#60708f">Aperçu de l'image</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel">
                <div class="form-group">
                    <label>Nom</label>
                    <input class="form-control" type="text" name="name" required value="<?= htmlspecialchars($item['name'] ?? '') ?>" />
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="6"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label>Statut</label>
                    <select class="form-control" name="status">
                        <option value="draft" <?= (($item['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Brouillon</option>
                        <option value="published" <?= (($item['status'] ?? 'published') === 'published') ? 'selected' : '' ?>>Publié</option>
                    </select>
                </div>
                <div class="admin-actions">
                    <button class="btn-admin" type="submit">💾 Enregistrer</button>
                    <a class="btn" href="<?= base_url('/admin/formations') ?>">↩️ Annuler</a>
                </div>
            </div>
        </form>
    </div>
</section>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>