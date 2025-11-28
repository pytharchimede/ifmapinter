<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Entreprises & Partenaires</h2>
            <p>Ils nous accompagnent dans l’insertion professionnelle.</p>
        </div>
        <div class="partners">
            <?php foreach ($items as $p): ?>
                <img loading="lazy" src="<?= htmlspecialchars($p['logo_url'] ?? 'https://dummyimage.com/140x70/004b9a/ffffff&text=Logo') ?>" alt="<?= htmlspecialchars($p['name']) ?>">
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <p>Aucun partenaire pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>