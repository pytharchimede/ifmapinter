<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="container">
        <h2>Programmes</h2>
        <div class="grid-3">
            <?php foreach ($items as $p): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($p['image_url'] ?? 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0') ?>" alt="">
                    <div class="card-body">
                        <h3><?= htmlspecialchars($p['name']) ?></h3>
                        <p><?= htmlspecialchars(mb_strimwidth($p['description'] ?? '', 0, 140, '…')) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <p>Aucun programme disponible.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>