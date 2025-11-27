<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="container">
        <h2>Actualités</h2>
        <div class="grid-3">
            <?php foreach ($items as $n): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($n['image_url'] ?? 'https://images.unsplash.com/photo-1498079022511-d15614cb1c02') ?>" alt="">
                    <div class="card-body">
                        <h3><?= htmlspecialchars($n['title']) ?></h3>
                        <p><?= htmlspecialchars(mb_strimwidth($n['body'] ?? '', 0, 140, '…')) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <p>Aucune actualité pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>