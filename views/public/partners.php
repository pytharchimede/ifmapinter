<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="page-hero gradient" id="partenaires">
    <div class="inner">
        <span class="badge-soft">IFMAP</span>
        <h1><?= htmlspecialchars(t('page.partners.title')) ?></h1>
        <p class="lead">Ils renforcent l’insertion professionnelle et contribuent à la qualité des parcours. Un réseau actif, engagé et évolutif.</p>
        <div class="actions">
            <a href="#reseau-partenaires" class="btn-outline">Réseau</a>
            <a href="#appels-partenaires" class="btn-outline">Devenir partenaire</a>
        </div>
        <div class="stats-grid">
            <div class="stat-box">
                <h3><?= count($items) ?></h3><small>Actifs</small>
            </div>
            <div class="stat-box">
                <h3>5</h3><small>Secteurs clés</small>
            </div>
            <div class="stat-box">
                <h3>3</h3><small>Projets communs</small>
            </div>
            <div class="stat-box">
                <h3>+ Impact</h3><small>Insertion</small>
            </div>
        </div>
    </div>
</div>
<section class="section section-tight" id="reseau-partenaires">
    <div class="container">
        <div class="section-title">
            <h2>Réseau partenaires</h2>
            <p class="muted">Industrie, services, énergie, distribution, innovation.</p>
        </div>
        <div class="partners-grid">
            <?php foreach ($items as $p): ?>
                <div class="partner-card">
                    <img loading="lazy" src="<?= htmlspecialchars($p['logo_url'] ?? 'https://dummyimage.com/240x120/004b9a/ffffff&text=Logo') ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                    <span class="name"><?= htmlspecialchars(mb_strimwidth($p['name'] ?? 'Partenaire', 0, 22, '…')) ?></span>
                </div>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <p class="muted">Aucun partenaire pour le moment.</p>
            <?php endif; ?>
        </div>
        <div class="cta-banner" id="appels-partenaires">
            <h3>Vous souhaitez rejoindre le réseau ?</h3>
            <p>Co‑construction de contenus, accueil de stagiaires, projets pilotes, équipement des ateliers… Contactez-nous pour explorer un partenariat gagnant.</p>
            <div class="actions">
                <a class="btn-outline" href="<?= base_url('contact') ?>">Proposer une collaboration</a>
                <a class="btn-outline" href="<?= base_url('programmes') ?>">Voir les programmes</a>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>