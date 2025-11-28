<?php
$title = $title ?? 'Instituts & Centres IFMAP';
$subtitle = $subtitle ?? "Découvrez nos pôles d'excellence et d'innovation.";
$items = $items ?? [];
?>
<?php include __DIR__ . '/../partials/public_header.php'; ?>
<section class="section-header" style="padding:24px 0;text-align:center;">
    <h1><?= htmlspecialchars($title) ?></h1>
    <?php if ($subtitle): ?><p style="color:#555;max-width:720px;margin:8px auto;"><?= htmlspecialchars($subtitle) ?></p><?php endif; ?>
</section>
<div class="container">
    <div class="cards-grid">
        <?php foreach ($items as $it): ?>
            <div class="card">
                <?php if (!empty($it['image_url'])): ?>
                    <img src="<?= htmlspecialchars($it['image_url']) ?>" alt="<?= htmlspecialchars($it['name']) ?>" style="width:100%;height:180px;object-fit:cover;">
                <?php endif; ?>
                <div class="card-body">
                    <div class="card-title" style="font-weight:bold;font-size:1.1rem;"><?= htmlspecialchars($it['name']) ?></div>
                    <?php if (!empty($it['excerpt'])): ?>
                        <div class="card-text" style="color:#555;"><?= htmlspecialchars($it['excerpt']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($it['url'])): ?>
                        <div style="margin-top:8px;"><a class="btn btn-link" href="<?= htmlspecialchars($it['url']) ?>" target="_blank">En savoir plus</a></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../partials/public_footer.php'; ?>