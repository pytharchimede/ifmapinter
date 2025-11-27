<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="container">
        <h2>Formations</h2>
        <div class="grid-4">
            <?php foreach ($items as $f): ?>
                <div class="card-formation">
                    <img src="<?= htmlspecialchars($f['image_url'] ?? 'https://images.unsplash.com/photo-1509395062183-67c5ad6faff9') ?>" alt="">
                    <h3><?= htmlspecialchars($f['name']) ?></h3>
                </div>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <p>Aucune formation disponible.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>