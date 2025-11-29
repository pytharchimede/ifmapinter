<?php
$item = $item ?? null;
$title = $title ?? ($item ? 'Modifier Centre' : 'Ajouter Centre');
?>
<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<div class="container">
    <h1><?= htmlspecialchars($title) ?></h1>
    <form method="post" action="<?= base_url($item ? '/admin/centres/edit' : '/admin/centres/create') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if ($item): ?>
            <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
        <?php endif; ?>
        <div class="admin-form">
            <div class="panel">
                <label>Nom du centre</label>
                <input class="form-control" type="text" name="name" value="<?= htmlspecialchars($item['name'] ?? '') ?>" required>
                <label>Sous-titre</label>
                <input class="form-control" type="text" name="subtitle" value="<?= htmlspecialchars($item['subtitle'] ?? '') ?>">
                <label>Extrait</label>
                <textarea class="form-control" name="excerpt" rows="3" placeholder="Bref résumé du centre..."><?= htmlspecialchars($item['excerpt'] ?? '') ?></textarea>
                <label>Lien (URL)</label>
                <input class="form-control" type="text" name="url" value="<?= htmlspecialchars($item['url'] ?? '') ?>" placeholder="https://...">
                <label>Statut</label>
                <select class="form-control" name="status">
                    <?php $st = $item['status'] ?? 'published'; ?>
                    <option value="published" <?= $st === 'published' ? 'selected' : '' ?>>Publié</option>
                    <option value="draft" <?= $st === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                </select>
            </div>
            <div class="panel">
                <label>Image</label>
                <input class="form-control" type="file" name="image_file" accept="image/*">
                <input type="hidden" name="image_url" value="<?= htmlspecialchars($item['image_url'] ?? '') ?>">
                <div class="image-preview" style="margin-top:8px;border:1px dashed #ccc;padding:8px;">
                    <?php if (!empty($item['image_url'])): ?>
                        <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Preview" style="max-width:100%;height:auto;">
                    <?php else: ?>
                        <small>Prévisualisation après sélection...</small>
                    <?php endif; ?>
                </div>
                <label>Contenu riche</label>
                <textarea id="editor" class="form-control" name="content" rows="10"><?= htmlspecialchars($item['content'] ?? '') ?></textarea>
            </div>
        </div>
        <div class="admin-actions" style="margin-top:12px;display:flex;gap:8px;">
            <button class="btn btn-primary" type="submit"><?= $item ? 'Mettre à jour' : 'Créer' ?></button>
            <a class="btn" href="<?= base_url('/admin/centres') ?>">Annuler</a>
        </div>
    </form>
</div>
<?php
// Récupération clé TinyMCE avec fallback si config_value() indisponible
if (function_exists('config_value')) {
    $tm_key = htmlspecialchars(config_value('tinymce_api_key', 'no-api-key'));
} else {
    $cfgPath = dirname(__DIR__, 3) . '/config.php';
    $cfg = is_file($cfgPath) ? include $cfgPath : [];
    $tm_key = htmlspecialchars($cfg['tinymce_api_key'] ?? 'no-api-key');
}
?>
<script src="https://cdn.tiny.cloud/1/<?= $tm_key ?>/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#editor',
        height: 400,
        plugins: 'link image lists table code',
        toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | bullist numlist | link image table | code',
        images_upload_url: '<?= base_url('/admin/upload') ?>',
        images_upload_credentials: true,
        convert_urls: false
    });
</script>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>