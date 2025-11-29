<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="page-hero gradient" id="institut">
    <div class="inner">
        <span class="badge-soft">IFMAP</span>
        <h1><?= htmlspecialchars(t('page.institut.title')) ?></h1>
        <p class="lead">Un modèle académique agile, africain et global : excellence, innovation et ancrage métier.</p>
        <div class="actions">
            <a href="#mission" class="btn-outline">Mission</a>
            <a href="#directeur" class="btn-outline">Mot du Directeur</a>
            <a href="#gouvernance" class="btn-outline">Gouvernance</a>
        </div>
        <div class="stats-grid">
            <div class="stat-box">
                <h3>+15</h3><small>Programmes</small>
            </div>
            <div class="stat-box">
                <h3>+25</h3><small>Formations</small>
            </div>
            <div class="stat-box">
                <h3>+40</h3><small>Partenaires</small>
            </div>
            <div class="stat-box">
                <h3>Impact</h3><small>Employabilité</small>
            </div>
        </div>
    </div>
</div>

<section class="section section-tight" id="mission">
    <div class="container">
        <div class="grid-2" style="align-items:start;gap:2.4rem;">
            <div>
                <h2>Mission & Vision</h2>
                <p>Former les talents qui accélèrent la transformation économique et technologique en Afrique, par des parcours professionnalisants alignés sur les besoins réels des organisations.</p>
                <div class="grid-3" style="margin-top:1.4rem;">
                    <div class="card-elevated" style="padding:1.2rem;">
                        <h3 style="margin-top:0;font-size:1.05rem;">Pedagogie Active</h3>
                        <p style="font-size:.9rem;">Cas réels, ateliers techniques, projets intégrés.</p>
                    </div>
                    <div class="card-elevated" style="padding:1.2rem;">
                        <h3 style="margin-top:0;font-size:1.05rem;">Innovation Durable</h3>
                        <p style="font-size:.9rem;">Énergie, numérique responsable, optimisation.</p>
                    </div>
                    <div class="card-elevated" style="padding:1.2rem;">
                        <h3 style="margin-top:0;font-size:1.05rem;">Ouverture Globale</h3>
                        <p style="font-size:.9rem;">Standards internationaux & partenariats.</p>
                    </div>
                </div>
            </div>
            <aside>
                <div class="card-elevated" style="overflow:hidden;">
                    <img loading="lazy" src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1200&q=60" alt="Campus IFMAP" style="width:100%;height:240px;object-fit:cover;">
                    <div style="padding:1rem 1.1rem;">
                        <h3 style="margin-top:0;font-size:1rem;">Infrastructures</h3>
                        <p style="font-size:.85rem;">Labs techniques, salles collaboratives, espaces d'innovation et pôle carrière au service de l'apprentissage pratique.</p>
                        <a href="<?= base_url('campus') ?>" class="btn-outline" style="margin-top:.4rem;">Explorer le Campus</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<section class="section" id="directeur">
    <div class="container">
        <div class="section-title">
            <h2>Mot du Directeur</h2>
            <p>Leadership académique et vision stratégique.</p>
        </div>
        <div class="grid-2" style="gap:2.2rem;align-items:start;">
            <div class="card-elevated" style="padding:1.6rem;">
                <div style="display:flex;gap:1.2rem;align-items:center;flex-wrap:wrap;">
                    <img loading="lazy" src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400&h=400&fit=crop" alt="Directeur" style="width:120px;height:120px;object-fit:cover;border-radius:50%;box-shadow:var(--shadow-sm);">
                    <div>
                        <h3 style="margin:0;font-size:1.15rem;">Dr. A. Directeur</h3>
                        <p style="margin:.2rem 0 .4rem;font-size:.85rem;opacity:.8;">Directeur Général – IFMAP</p>
                        <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                            <span class="badge-soft">Stratégie</span>
                            <span class="badge-soft">Innovation</span>
                            <span class="badge-soft">Impact</span>
                        </div>
                    </div>
                </div>
                <p style="margin-top:1rem;line-height:1.55;font-size:.95rem;">"Notre engagement est clair : relier formation et performance opérationnelle. Nous co‑construisons avec les entreprises des parcours qui accélèrent l'employabilité et soutiennent une croissance inclusive. L'Institut IFMAP se veut un catalyseur : compétences techniques, management agile et culture d'innovation durable."</p>
                <p style="margin-top:1rem;font-size:.85rem;opacity:.75;">Bienvenue à celles et ceux qui souhaitent bâtir un futur compétent et responsable.</p>
            </div>
            <div class="timeline" style="margin-top:.4rem;">
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h4>2019 – Fondation</h4>
                        <p>Lancement de l'initiative avec un noyau de programmes pilotes.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h4>2021 – Expansion</h4>
                        <p>Ouverture des pôles techniques & renforcement partenariats.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h4>2023 – Accélération</h4>
                        <p>Adoption d'approches data & numérique responsable.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h4>2025 – Impact</h4>
                        <p>Consolidation de l'écosystème carrière & innovation durable.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section bg-light" id="gouvernance">
    <div class="container">
        <div class="section-title">
            <h2>Gouvernance & Qualité</h2>
            <p>Structures, conformité et amélioration continue.</p>
        </div>
        <div class="grid-3" style="gap:1.4rem;">
            <div class="card-elevated" style="padding:1.1rem;">
                <h3 style="margin-top:0;font-size:1rem;">Comité Pédagogique</h3>
                <p style="font-size:.85rem;">Validation des contenus & standards d'évaluation.</p>
            </div>
            <div class="card-elevated" style="padding:1.1rem;">
                <h3 style="margin-top:0;font-size:1rem;">Conseil Partenaires</h3>
                <p style="font-size:.85rem;">Alignement compétences & besoins sectoriels.</p>
            </div>
            <div class="card-elevated" style="padding:1.1rem;">
                <h3 style="margin-top:0;font-size:1rem;">Qualité & Audit</h3>
                <p style="font-size:.85rem;">Boucles d'amélioration continue & retours alumni.</p>
            </div>
        </div>
        <div class="cta-banner" style="margin-top:2rem;">
            <h3>Rejoindre l'aventure IFMAP</h3>
            <p>Partenaire, intervenant expert, mentor ou investisseur : contribuez à l'expansion d'un modèle de formation durable.</p>
            <div class="actions">
                <a class="btn-outline" href="<?= base_url('contact') ?>">Contactez-nous</a>
                <a class="btn-outline" href="<?= base_url('programmes') ?>">Voir les Programmes</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>