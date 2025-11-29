<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="page-hero dark" id="formations">
    <div class="inner">
        <span class="badge-soft">IFMAP</span>
        <h1><?= htmlspecialchars($title ?? t('page.formations.title')) ?></h1>
        <p class="lead"><?= htmlspecialchars($subtitle ?? t('page.formations.subtitle')) ?></p>
        <div class="actions">
            <a href="#catalogue-formations" class="btn-outline">Catalogue</a>
            <a href="<?= base_url('programmes') ?>" class="btn-outline">Programmes</a>
        </div>
    </div>
</div>
<section class="section section-tight" id="catalogue-formations">
    <div class="container">
        <div class="section-title">
            <h2 style="margin-bottom:.4rem;">Catalogue des formations</h2>
            <p class="muted">Compétences techniques et transversales indispensables pour une insertion professionnelle rapide.</p>
        </div>
        <div class="grid-3">
            <?php foreach ($items as $f): ?>
                <div class="card-elevated">
                    <?php if (!empty($f['image_url'])): ?>
                        <img src="<?= htmlspecialchars($f['image_url']) ?>" alt="" style="width:100%;height:150px;object-fit:cover;border-radius:10px;margin:-4px 0 10px;">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($f['name'] ?? '') ?></h3>
                    <?php if (!empty($f['description'])): ?><p><?= htmlspecialchars(mb_strimwidth($f['description'], 0, 160, '…')) ?></p><?php endif; ?>
                    <div style="margin-top:.6rem;display:flex;gap:.5rem;flex-wrap:wrap;">
                        <a class="btn-outline" href="#">Détail</a>
                        <a class="btn-outline" href="<?= base_url('contact') ?>">Demander info</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <p class="muted">Aucune formation disponible.</p>
            <?php endif; ?>
        </div>
        <div class="stats-grid" style="margin-top:2.4rem;">
            <div class="stat-box">
                <h3>60%</h3><small>Pratique</small>
            </div>
            <div class="stat-box">
                <h3>30%</h3><small>Théorie</small>
            </div>
            <div class="stat-box">
                <h3>10%</h3><small>Projets</small>
            </div>
            <div class="stat-box">
                <h3>+ Mentorat</h3><small>Professionnels</small>
            </div>
        </div>
        <div class="cta-banner">
            <h3>Construire une formation sur mesure ?</h3>
            <p>Nous adaptons les contenus pour répondre aux besoins spécifiques des entreprises et organisations. Co-construction pédagogique possible.</p>
            <div class="actions">
                <a class="btn-outline" href="<?= base_url('contact') ?>">Nous écrire</a>
                <a class="btn-outline" href="<?= base_url('centres') ?>">Découvrir nos centres</a>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>