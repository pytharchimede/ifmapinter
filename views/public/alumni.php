<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="page-hero gradient" id="alumni">
    <div class="inner">
        <span class="badge-soft">IFMAP</span>
        <h1>Alumni</h1>
        <p class="lead">Réseau d’anciens engagés, ambassadeurs des valeurs IFMAP et moteurs de diffusion des compétences dans les organisations.</p>
        <div class="actions">
            <a href="#reseau-alumni" class="btn-outline">Réseau</a>
            <a href="#cvs-alumni" class="btn-outline">Modèles CV</a>
            <a href="<?= base_url('temoignages') ?>" class="btn-outline">Témoignages</a>
        </div>
        <div class="stats-grid">
            <div class="stat-box">
                <h3>250+</h3><small>Diplômés</small>
            </div>
            <div class="stat-box">
                <h3>35%</h3><small>Femmes</small>
            </div>
            <div class="stat-box">
                <h3>18</h3><small>Secteurs</small>
            </div>
            <div class="stat-box">
                <h3>92%</h3><small>Insertion 6m</small>
            </div>
        </div>
    </div>
</div>
<section class="section section-tight" id="reseau-alumni">
    <div class="container">
        <div class="section-title">
            <h2>Quelques profils</h2>
            <p class="muted">Diversité des parcours et des domaines d’expertise.</p>
        </div>
        <div class="alumni-grid">
            <?php $sample = [
                ['name' => 'Awa Koné', 'role' => 'Analyste Data', 'summary' => 'Business Intelligence & visualisation, 5 ans d’expérience.', 'cv_url' => base_url('alumni/cv-template')],
                ['name' => 'Yao N’Guessan', 'role' => 'Chef de Projet', 'summary' => 'Pilotage transformation digitale secteur public.', 'cv_url' => base_url('alumni/cv-template')],
                ['name' => 'Fatou Traoré', 'role' => 'Ingénieure Énergie', 'summary' => 'Systèmes solaires & efficacité énergétique.', 'cv_url' => base_url('alumni/cv-template')],
                ['name' => 'Ismaël Diabaté', 'role' => 'Technicien Réseaux', 'summary' => 'Déploiement infrastructures & supervision.', 'cv_url' => base_url('alumni/cv-template')],
                ['name' => 'Clarisse Kouassi', 'role' => 'Resp. Qualité', 'summary' => 'Démarches qualité & amélioration continue.', 'cv_url' => base_url('alumni/cv-template')],
                ['name' => 'Mariam Sylla', 'role' => 'Consultante RH', 'summary' => 'Accompagnement talents & organisation.', 'cv_url' => base_url('alumni/cv-template')],
            ];
            foreach ($sample as $al): ?>
                <div class="alumni-card">
                    <h3><?= htmlspecialchars($al['name']) ?></h3>
                    <div class="role"><?= htmlspecialchars($al['role']) ?></div>
                    <p><?= htmlspecialchars($al['summary']) ?></p>
                    <a class="btn-outline" href="<?= htmlspecialchars($al['cv_url']) ?>">Modèle CV</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section bg-light section-tight" id="cvs-alumni">
    <div class="container">
        <div class="section-title">
            <h2>Modèles de CV</h2>
            <p class="muted">Standardisation pour valoriser compétences et projets de manière claire.</p>
        </div>
        <div class="grid-3">
            <div class="card-elevated">
                <h3>CV Complet</h3>
                <p>Structure riche pour profils expérimentés ou polyvalents.</p><a class="btn-outline" href="<?= base_url('alumni/cv-template') ?>">Voir</a>
            </div>
            <div class="card-elevated">
                <h3>CV Compact</h3>
                <p>Version synthétique A4 pour candidatures ciblées.</p><a class="btn-outline" href="<?= base_url('alumni/cv-template/compact') ?>">Voir</a>
            </div>
            <div class="card-elevated">
                <h3>Personnalisation</h3>
                <p>Adaptation spécifique (secteur, poste, projets stratégiques).</p><a class="btn-outline" href="<?= base_url('contact') ?>">Demander</a>
            </div>
        </div>
        <div class="cta-banner">
            <h3>Participer à la communauté Alumni</h3>
            <p>Rencontres thématiques, mentorat, interventions en cours : devenez un acteur du rayonnement IFMAP.</p>
            <div class="actions">
                <a class="btn-outline" href="<?= base_url('temoignages') ?>">Partager un témoignage</a>
                <a class="btn-outline" href="<?= base_url('contact') ?>">Proposer une intervention</a>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>