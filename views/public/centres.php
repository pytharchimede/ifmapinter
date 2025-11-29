<?php
$title = $title ?? t('page.centres.title');
$subtitle = $subtitle ?? t('page.centres.subtitle');
$items = $items ?? [];
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="page-hero gradient" id="centres">
    <div class="inner">
        <span class="badge-soft">IFMAP</span>
        <h1><?= htmlspecialchars($title) ?></h1>
        <p class="lead"><?= htmlspecialchars($subtitle) ?></p>
        <div class="actions">
            <a href="#liste-centres" class="btn-outline">Voir les centres</a>
            <a href="<?= base_url('programmes') ?>" class="btn-outline">Programmes reliés</a>
        </div>
    </div>
</div>
<section class="section section-tight" id="liste-centres">
    <div class="container">
        <div class="section-title">
            <h2 style="margin-bottom:.4rem;">Nos pôles</h2>
            <p class="muted">Chacun spécialisé pour favoriser l'excellence technique et l'innovation.</p>
        </div>
        <div class="grid-3">
            <?php foreach ($items as $it): ?>
                <div class="card-elevated">
                    <?php if (!empty($it['image_url'])): ?>
                        <img src="<?= htmlspecialchars($it['image_url']) ?>" alt="<?= htmlspecialchars($it['name']) ?>" style="width:100%;height:160px;object-fit:cover;border-radius:10px;margin:-4px 0 10px;">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($it['name']) ?></h3>
                    <?php if (!empty($it['excerpt'])): ?><p><?= htmlspecialchars(mb_strimwidth($it['excerpt'], 0, 180, '…')) ?></p><?php endif; ?>
                    <div style="margin-top:.6rem;display:flex;gap:.5rem;flex-wrap:wrap;">
                        <?php if (!empty($it['url'])): ?><a class="btn-outline" target="_blank" href="<?= htmlspecialchars($it['url']) ?>">Site / doc</a><?php endif; ?>
                        <a class="btn-outline" href="<?= base_url('contact') ?>">Contact</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <p class="muted">Aucun centre publié.</p>
            <?php endif; ?>
        </div>
        <div class="stats-grid" style="margin-top:2.4rem;">
            <div class="stat-box">
                <h3>4</h3><small>Pôles actifs</small>
            </div>
            <div class="stat-box">
                <h3>20+</h3><small>Labos & ateliers</small>
            </div>
            <div class="stat-box">
                <h3>3</h3><small>Partenariats acad.</small>
            </div>
            <div class="stat-box">
                <h3>Innovation</h3><small>Projets étudiants</small>
            </div>
        </div>
        <div class="cta-banner">
            <h3>Créer un nouveau centre thématique ?</h3>
            <p>Nous développons des pôles en co‑construction avec les acteurs publics et privés. Vos besoins peuvent orienter le prochain centre.</p>
            <div class="actions">
                <a class="btn-outline" href="<?= base_url('contact') ?>">Proposer un partenariat</a>
                <a class="btn-outline" href="<?= base_url('campus') ?>">Voir le campus</a>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>