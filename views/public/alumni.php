<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section" id="alumni-page">
    <div class="container">
        <div class="section-title">
            <h2>Alumni</h2>
            <p>Découvrez nos anciens et leurs parcours.</p>
        </div>

        <div class="alumni-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">
            <?php $sample = [
                ['name' => 'Awa Koné', 'role' => 'Analyste Data', 'summary' => 'Spécialiste BI, 5 ans expérience', 'cv_url' => '#'],
                ['name' => 'Yao N’Guessan', 'role' => 'Chef de projet', 'summary' => 'Transformation digitale, secteur public', 'cv_url' => '#'],
                ['name' => 'Fatou Traoré', 'role' => 'Ingénieure Énergie', 'summary' => 'Solaire et efficacité énergétique', 'cv_url' => '#'],
            ];
            foreach ($sample as $al): ?>
                <div class="card" style="background:var(--color-surface);padding:1rem;border-radius:var(--radius-md);box-shadow:var(--shadow-sm)">
                    <h3 style="margin:0 0 .3rem 0;"><?= htmlspecialchars($al['name']) ?></h3>
                    <p style="color:var(--color-text-light);margin:.2rem 0;"><?= htmlspecialchars($al['role']) ?></p>
                    <p style="margin:.4rem 0 1rem 0;"><?= htmlspecialchars($al['summary']) ?></p>
                    <a class="btn" href="<?= htmlspecialchars($al['cv_url']) ?>" style="display:inline-block;padding:.5rem 1rem;background:var(--color-primary);color:#fff;border-radius:var(--radius-sm);text-decoration:none;">Voir CV (modèle)</a>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top:2rem">
            <h3>Modèle de CV Alumni</h3>
            <p>Nous proposons un modèle de CV standardisé pour nos Alumni. Il inclut: informations personnelles, compétences, expériences, formations et projets.</p>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>