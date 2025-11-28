<?php include __DIR__ . '/../../partials/admin_header.php'; ?>

<section class="section">
    <div class="container" style="max-width:1100px;">
        <h2><?= htmlspecialchars($title) ?></h2>
        <p><a href="<?= base_url('/admin/programmes') ?>">Retour</a></p>
        <form method="post" action="<?= base_url($item ? '/admin/programmes/edit' : '/admin/programmes/create') ?>" class="card admin-form" style="padding:24px;" enctype="multipart/form-data">
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
                    <label>Résumé</label>
                    <input class="form-control" type="text" name="excerpt" value="<?= htmlspecialchars($item['excerpt'] ?? '') ?>" />
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="6"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label>Contenu (riche)</label>
                    <textarea id="editor" class="form-control" name="content" rows="12"><?= htmlspecialchars($item['content'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label>Lien</label>
                    <input class="form-control" type="url" name="url" value="<?= htmlspecialchars($item['url'] ?? '') ?>" />
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
                    <a class="btn" href="<?= base_url('/admin/programmes') ?>">↩️ Annuler</a>
                </div>
            </div>
        </form>
    </div>
</section>
<?php include_once dirname(__DIR__, 3) . '/app/helpers.php';
$tm_key = htmlspecialchars(config_value('tinymce_api_key', 'no-api-key')); ?>
<script src="https://cdn.tiny.cloud/1/<?= $tm_key ?>/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#editor',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist | emoticons charmap | removeformat',
        menubar: true,
        height: 500,
        images_upload_url: '<?= base_url('admin/upload') ?>',
        automatic_uploads: true,
        convert_urls: false
    });
</script>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>