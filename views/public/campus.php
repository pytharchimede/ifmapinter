<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="page-hero dark" id="campus">
    <div class="inner">
        <span class="badge-soft">IFMAP</span>
        <h1><?= htmlspecialchars(t('page.campus.title')) ?></h1>
        <p class="lead">Espaces modernes, laboratoires et lieux de vie pour une pédagogie immersive de nouvelle génération.</p>
        <div class="actions">
            <a href="#visions-campus" class="btn-outline">Vision</a>
            <a href="#galerie-campus" class="btn-outline">Galerie</a>
            <a href="#phases-campus" class="btn-outline">Phases</a>
        </div>
        <div class="stats-grid" style="margin-top:1.2rem;">
            <div class="stat-box">
                <h3>6</h3><small>ZONES prévues</small>
            </div>
            <div class="stat-box">
                <h3>12</h3><small>LABORATOIRES</small>
            </div>
            <div class="stat-box">
                <h3>3</h3><small>ATELIERS techniques</small>
            </div>
            <div class="stat-box">
                <h3>1</h3><small>Espace innovation</small>
            </div>
        </div>
    </div>
</div>
<section class="section section-tight" id="visions-campus">
    <div class="container">
        <div class="section-title">
            <h2>Vision pédagogique</h2>
            <p class="muted">Un écosystème physique qui soutient la pratique intensive, l'expérimentation et la co‑création.</p>
        </div>
        <div class="grid-3" style="margin-top:1.6rem;">
            <div class="card-elevated">
                <h3>Apprentissage actif</h3>
                <p>Méthodes par projets, études de cas contextualisées et simulation.</p>
            </div>
            <div class="card-elevated">
                <h3>Technologies</h3>
                <p>Équipements énergie, data, industrie légère et maintenance.</p>
            </div>
            <div class="card-elevated">
                <h3>Ouverture</h3>
                <p>Espace d’accueil pour workshops partenaires & bootcamps.</p>
            </div>
        </div>
    </div>
</section>
<section class="section bg-light section-tight" id="galerie-campus">
    <div class="container">
        <div class="section-title">
            <h2>Galerie conceptuelle</h2>
            <p class="muted">Rendus / inspirations préfigurant l'esthétique générale des espaces.</p>
        </div>
        <div class="grid-3" style="margin-top:1.6rem;">
            <div class="card-elevated"><img loading="lazy" src="https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=800&q=60" alt="Centre technique" style="width:100%;height:160px;object-fit:cover;border-radius:10px;margin:-4px 0 10px;">
                <h3>Centre technique</h3>
                <p>Espace dédié industrie légère & électrotechnique.</p>
            </div>
            <div class="card-elevated"><img loading="lazy" src="https://images.unsplash.com/photo-1509395062183-67c5ad6faff9?auto=format&fit=crop&w=800&q=60" alt="Atelier solaire" style="width:100%;height:160px;object-fit:cover;border-radius:10px;margin:-4px 0 10px;">
                <h3>Atelier solaire</h3>
                <p>Montage, diagnostic et maintenance systèmes photovoltaïques.</p>
            </div>
            <div class="card-elevated"><img loading="lazy" src="https://images.unsplash.com/photo-1498079022511-d15614cb1c02?auto=format&fit=crop&w=800&q=60" alt="Bâtiment principal" style="width:100%;height:160px;object-fit:cover;border-radius:10px;margin:-4px 0 10px;">
                <h3>Bâtiment principal</h3>
                <p>Salles modulaires, coworking & espace innovation.</p>
            </div>
        </div>
    </div>
</section>
<section class="section section-tight" id="phases-campus">
    <div class="container">
        <div class="section-title">
            <h2>Phases du projet</h2>
            <p class="muted">Progression structurée pour garantir qualité et impact.</p>
        </div>
        <div class="timeline compact" style="margin-top:1.6rem;">
            <div class="tl-item">
                <h4>Phase 1 – Études & conception</h4>
                <p>Analyse des besoins, master plan fonctionnel, specs techniques.</p>
            </div>
            <div class="tl-item">
                <h4>Phase 2 – Construction initiale</h4>
                <p>Modules essentiels (ateliers, salles polyvalentes, énergie).</p>
            </div>
            <div class="tl-item">
                <h4>Phase 3 – Équipements pédagogiques</h4>
                <p>Installation matériel spécialisé, intégration IoT & data.</p>
            </div>
            <div class="tl-item">
                <h4>Phase 4 – Ouverture & montée en charge</h4>
                <p>Accueil premières cohortes, ajustements opérationnels.</p>
            </div>
        </div>
        <div class="cta-banner">
            <h3>Contribuer au développement du campus</h3>
            <p>Investisseurs, partenaires techniques ou institutionnels : rejoignez l’initiative et participez à un modèle de formation durable.</p>
            <div class="actions">
                <a class="btn-outline" href="<?= base_url('contact') ?>">Entrer en contact</a>
                <a class="btn-outline" href="<?= base_url('centres') ?>">Voir les centres</a>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>