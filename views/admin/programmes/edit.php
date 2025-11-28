<?php include __DIR__ . '/../../partials/admin_header.php'; ?>

<div class="admin-hero">
    <h1>Éditer l’article</h1>
    <div class="actions">
        <a class="btn" href="<?= base_url('admin/programmes') ?>">⬅️ Retour</a>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="admin-card" style="margin:.6rem 0;background:#2b0e0e;border-color:#7c2d12;color:#ffedd5"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<section class="admin-card" style="margin-bottom:1rem;">
    <h2>Contenu de l’article</h2>
    <form method="post" action="<?= base_url('admin/programmes/update') ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="id" value="<?= (int)($item['id'] ?? 0) ?>">

        <div class="field">
            <label>Image (vignette)</label>
            <input type="file" name="image" accept="image/*">
            <?php if (!empty($item['image_url'])): ?>
                <div style="margin-top:.5rem">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="vignette" style="max-width:220px;border-radius:10px;border:1px solid #e3edf7;">
                </div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label>Titre</label>
            <input name="title" value="<?= htmlspecialchars($item['title'] ?? '') ?>" placeholder="Titre de l'article">
        </div>

        <div class="field">
            <label>Résumé / description courte</label>
            <input name="excerpt" value="<?= htmlspecialchars($item['excerpt'] ?? '') ?>" placeholder="Quelques lignes de description">
        </div>

        <div class="field">
            <label>Contenu riche</label>
            <textarea id="editor" name="content" rows="12" placeholder="Rédigez votre article..."><?= htmlspecialchars($item['content'] ?? '') ?></textarea>
        </div>

        <div class="field">
            <label>Lien vers l'article complet</label>
            <input name="url" value="<?= htmlspecialchars($item['url'] ?? '') ?>" placeholder="https://...">
        </div>

        <div class="actions">
            <button class="btn-admin" type="submit" name="action" value="save">💾 Enregistrer</button>
            <button class="btn-admin" type="submit" name="action" value="draft">📝 Enregistrer brouillon</button>
            <button class="btn-admin" type="button" id="previewBtn">👁️ Aperçu</button>
        </div>
    </form>
</section>

<section class="admin-card" id="previewCard" style="display:none;margin-bottom:1rem;">
    <h2>Aperçu</h2>
    <div id="previewBox" style="background:#fff;color:#1f2d3d;border-radius:12px;border:1px solid #e3edf7;padding:1rem"></div>
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
        height: 420,
        images_upload_url: '<?= base_url('admin/upload') ?>',
        automatic_uploads: true,
        convert_urls: false
    });

    document.getElementById('previewBtn').addEventListener('click', () => {
        const title = document.querySelector('input[name="title"]').value;
        const excerpt = document.querySelector('input[name="excerpt"]').value;
        const content = tinymce.get('editor').getContent();
        const box = document.getElementById('previewBox');
        document.getElementById('previewCard').style.display = '';
        box.innerHTML = `
      <h3 style="color:#0b3b8f;margin:0 0 .5rem">${title || 'Sans titre'}</h3>
      <p style="color:#4b5b6b">${excerpt || ''}</p>
      <div style="margin-top:.8rem">${content}</div>
    `;
    });
</script>

<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>