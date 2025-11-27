<?php include __DIR__ . '/../../partials/header.php'; ?>

<section class="section">
    <div class="container" style="max-width:720px;">
        <h2><?= htmlspecialchars($title) ?></h2>
        <p><a href="<?= base_url('/admin/formations') ?>">Retour</a></p>
        <form method="post" action="<?= base_url($item ? '/admin/formations/edit' : '/admin/formations/create') ?>" class="card" style="padding:20px;">
            <?= csrf_field() ?>
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= (int)$item['id'] ?>" />
            <?php endif; ?>
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="name" required value="<?= htmlspecialchars($item['name'] ?? '') ?>" />
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="url" name="image_url" value="<?= htmlspecialchars($item['image_url'] ?? '') ?>" />
            </div>
            <button class="btn-primary" type="submit">Enregistrer</button>
        </form>
    </div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>