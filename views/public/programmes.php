<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="page-hero gradient" id="programmes">
    <div class="inner">
        <span class="badge-soft">IFMAP</span>
        <h1><?= htmlspecialchars(t('page.programmes.title')) ?></h1>
        <p class="lead">Des parcours structurés inspirés des standards internationaux, alignés sur les besoins actuels du marché africain et porteurs d'employabilité durable.</p>
        <div class="actions">
            <a href="#liste-programmes" class="btn-outline">Voir la liste</a>
            <a href="<?= base_url('formations') ?>" class="btn-outline">Formations associées</a>
        </div>
        <div class="stats-grid">
            <div class="stat-box">
                <h3>8</h3><small>Domaines</small>
            </div>
            <div class="stat-box">
                <h3>35+</h3><small>Modules</small>
            </div>
            <div class="stat-box">
                <h3>92%</h3><small>Taux insertion</small>
            </div>
            <div class="stat-box">
                <h3>15</h3><small>Partenaires</small>
            </div>
        </div>
    </div>
</div>
<section class="section section-tight" id="liste-programmes">
    <div class="container">
        <div class="section-title">
            <h2 style="margin-bottom:.4rem;">Catalogue</h2>
            <p class="muted">Chaque programme combine fondamentaux, mises en pratique et projets tutorés.</p>
        </div>
        <div class="grid-3">
            <?php foreach ($items as $p): ?>
                <div class="card-elevated">
                    <?php if (!empty($p['image_url'])): ?>
                        <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="" style="width:100%;height:160px;object-fit:cover;border-radius:10px;margin:-4px 0 10px;">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($p['name']) ?></h3>
                    <p><?= htmlspecialchars(mb_strimwidth($p['description'] ?? '', 0, 160, '…')) ?></p>
                    <div style="margin-top:.6rem;display:flex;gap:.5rem;flex-wrap:wrap;">
                        <a class="btn-outline" href="#">Détail</a>
                        <a class="btn-outline" href="<?= base_url('contact') ?>">Contact</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <p class="muted">Aucun programme disponible.</p>
            <?php endif; ?>
        </div>
        <div class="cta-banner">
            <h3>Vous souhaitez un programme sur mesure ?</h3>
            <p>Nos équipes peuvent concevoir un parcours personnalisé pour votre organisation (entreprise, collectivité, ONG). Discutons de votre besoin.</p>
            <div class="actions">
                <a class="btn-outline" href="<?= base_url('contact') ?>">Demander un échange</a>
                <a class="btn-outline" href="<?= base_url('formations') ?>">Explorer les formations</a>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>