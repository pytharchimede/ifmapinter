<?php include __DIR__ . '/../../partials/header.php'; ?>

<section class="section">
    <div class="container" style="max-width:720px;">
        <h2><?= htmlspecialchars($title) ?></h2>
        <p><a href="<?= base_url('/admin/news') ?>">Retour</a></p>
        <form method="post" action="<?= base_url($item ? '/admin/news/edit' : '/admin/news/create') ?>" class="card" style="padding:20px;">
            <?= csrf_field() ?>
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= (int)$item['id'] ?>" />
            <?php endif; ?>
            <div class="form-group">
                <label>Titre</label>
                <input type="text" name="title" required value="<?= htmlspecialchars($item['title'] ?? '') ?>" />
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="url" name="image_url" value="<?= htmlspecialchars($item['image_url'] ?? '') ?>" />
            </div>
            <div class="form-group">
                <label>Publié le (YYYY-MM-DD HH:MM:SS)</label>
                <input type="text" name="published_at" value="<?= htmlspecialchars($item['published_at'] ?? '') ?>" />
            </div>
            <div class="form-group">
                <label>Contenu</label>
                <textarea name="body" rows="6"><?= htmlspecialchars($item['body'] ?? '') ?></textarea>
            </div>
            <button class="btn-primary" type="submit">Enregistrer</button>
        </form>
    </div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>