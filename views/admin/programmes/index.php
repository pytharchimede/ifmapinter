<?php include __DIR__ . '/../../partials/admin_header.php'; ?>

<div class="admin-hero">
    <h1>Nos Programmes</h1>
    <div class="actions">
        <a class="btn" href="<?= base_url('admin/programmes/create') ?>">➕ Ajouter un article</a>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="admin-card" style="margin:.6rem 0;background:#2b0e0e;border-color:#7c2d12;color:#ffedd5"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div>
    <section class="admin-card" style="margin-bottom:1rem;">
        <h2>Paramètres de section</h2>
        <form method="post" action="<?= base_url('admin/programmes/section/save') ?>">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <div class="field">
                <label for="section_title">Titre</label>
                <input id="section_title" name="title" value="<?= htmlspecialchars(($section['title'] ?? 'Nos Programmes')) ?>">
            </div>
            <div class="field">
                <label for="section_subtitle">Sous-titre</label>
                <input id="section_subtitle" name="subtitle" value="<?= htmlspecialchars(($section['subtitle'] ?? 'Une offre académique structurée comme les grandes institutions internationales.')) ?>">
            </div>
            <div class="actions">
                <button class="btn-admin" type="submit">💾 Enregistrer</button>
            </div>
        </form>
    </section>

    <!-- Formulaire d'ajout déplacé vers /admin/programmes/create pour unifier avec édition -->
</div>

<section class="admin-card" style="margin-top:1rem;">
    <h2>Articles existants</h2>
    <div class="cards-grid">
        <?php foreach (($items ?? []) as $it): ?>
            <div class="card">
                <div class="card-media" style="background-image:url('<?= htmlspecialchars($it['image_url'] ?? '') ?>');height:140px"></div>
                <div class="card-body">
                    <div class="card-title"><?= htmlspecialchars($it['title'] ?? 'Sans titre') ?></div>
                    <div class="card-description"><?= htmlspecialchars($it['excerpt'] ?? '') ?></div>
                    <div class="meta">
                        <?php if (!empty($it['url'])): ?><span>🔗 <?= htmlspecialchars($it['url']) ?></span><?php endif; ?>
                    </div>
                    <div class="card-actions">
                        <div class="left">
                            <a class="btn" href="<?= base_url('admin/programmes/edit?id=' . (int)($it['id'] ?? 0)) ?>">✏️ Éditer</a>
                            <a class="btn" target="_blank" rel="noopener" href="<?= htmlspecialchars($it['url'] ?? '#') ?>">👁️ Voir</a>
                        </div>
                        <a class="btn btn-danger" href="<?= base_url('admin/programmes/delete?id=' . (int)($it['id'] ?? 0)) ?>" onclick="return confirm('Supprimer cet article ?');">🗑️ Supprimer</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($items)): ?>
            <div>Aucun article pour l'instant.</div>
        <?php endif; ?>
    </div>
</section>

<?php include_once dirname(__DIR__, 3) . '/app/helpers.php';
$tm_key = htmlspecialchars(config_value('tinymce_api_key', 'no-api-key')); ?>
<!-- TinyMCE CDN (with API key) -->
<script src="https://cdn.tiny.cloud/1/<?= $tm_key ?>/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#editor',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist | emoticons charmap | removeformat',
        menubar: true,
        height: 360,
        images_upload_url: '<?= base_url('admin/upload') ?>',
        automatic_uploads: true,
        convert_urls: false
    });
</script>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>