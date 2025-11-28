<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="container">
        <div class="section-header" style="margin-bottom:1rem;">
            <h2 style="margin:0;"><?= htmlspecialchars($title ?? 'Formations IFMAP') ?></h2>
            <?php if (!empty($subtitle)): ?>
                <p style="color:#60708f;max-width:860px;"><?= htmlspecialchars($subtitle) ?></p>
            <?php endif; ?>
        </div>
        <div class="cards-grid">
            <?php foreach ($items as $f): ?>
                <div class="card">
                    <div class="card-media" style="background-image:url('<?= htmlspecialchars($f['image_url'] ?? '') ?>');height:160px"></div>
                    <div class="card-body">
                        <div class="card-title"><?= htmlspecialchars($f['name'] ?? '') ?></div>
                        <?php if (!empty($f['description'])): ?>
                            <div class="card-description"><?= htmlspecialchars($f['description']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <div class="admin-card">Aucune formation disponible.</div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>