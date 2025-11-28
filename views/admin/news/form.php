<?php include __DIR__ . '/../../partials/admin_header.php'; ?>

<section class="section">
    <div class="container" style="max-width:720px;">
        <h2><?= htmlspecialchars($title) ?></h2>
        <p><a href="<?= base_url('/admin/news') ?>">Retour</a></p>
        <form method="post" action="<?= base_url($item ? '/admin/news/edit' : '/admin/news/create') ?>" class="card" style="padding:20px;" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= (int)$item['id'] ?>" />
            <?php endif; ?>
            <div class="admin-form">
                <div class="panel">
                    <label>Titre</label>
                    <input class="form-control" type="text" name="title" required value="<?= htmlspecialchars($item['title'] ?? '') ?>" />
                    <label>Statut</label>
                    <?php $st = $item['status'] ?? 'published'; ?>
                    <select class="form-control" name="status">
                        <option value="published" <?= $st === 'published' ? 'selected' : '' ?>>Publié</option>
                        <option value="draft" <?= $st === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                    </select>
                    <label>Publié le</label>
                    <input class="form-control" type="text" name="published_at" value="<?= htmlspecialchars($item['published_at'] ?? '') ?>" placeholder="YYYY-MM-DD HH:MM:SS" />
                </div>
                <div class="panel">
                    <label>Image illustrative</label>
                    <input class="form-control" type="file" name="image_file" accept="image/*" />
                    <input type="hidden" name="image_url" value="<?= htmlspecialchars($item['image_url'] ?? '') ?>" />
                    <div class="image-preview" style="margin-top:8px;border:1px dashed #ccc;padding:8px;">
                        <?php if (!empty($item['image_url'])): ?>
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Preview" style="max-width:100%;height:auto;" />
                        <?php else: ?>
                            <small>Prévisualisation après sélection...</small>
                        <?php endif; ?>
                    </div>
                    <label style="margin-top:10px;">Source</label>
                    <input class="form-control" type="text" name="source" value="<?= htmlspecialchars($item['source'] ?? '') ?>" placeholder="Ex: IFMAP, Reuters..." />
                    <label>URL de l'article</label>
                    <input class="form-control" type="url" name="article_url" value="<?= htmlspecialchars($item['article_url'] ?? '') ?>" placeholder="https://..." />
                </div>
            </div>
            <div class="form-group">
                <label>Contenu (Rich text)</label>
                <textarea id="editor" class="form-control" name="body" rows="10"><?= htmlspecialchars($item['body'] ?? '') ?></textarea>
            </div>
            <div class="admin-actions" style="margin-top:12px;display:flex;gap:8px;">
                <button class="btn btn-primary" type="submit">Enregistrer</button>
                <a class="btn" href="<?= base_url('/admin/news') ?>">Annuler</a>
            </div>
        </form>
    </div>
</section>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
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