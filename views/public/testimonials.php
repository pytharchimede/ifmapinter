<?php include __DIR__ . '/../partials/header.php'; ?>
<main class="section" style="padding-top:3rem;">
    <div class="container">
        <div class="section-title">
            <h2><?= htmlspecialchars($title ?? 'Témoignages') ?></h2>
            <p>Les retours d'expérience validés par l'équipe IFMAP.</p>
        </div>
        <?php if (!empty($rows)): ?>
            <div class="testimonials">
                <?php foreach ($rows as $t): ?>
                    <div class="testimonial" data-anim="fade">
                        <div class="author">
                            <img loading="lazy" src="<?= htmlspecialchars($t['avatar_url'] ?: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=200&h=200&fit=crop') ?>" alt="Avatar de <?= htmlspecialchars($t['name']) ?>">
                            <div class="meta">
                                <strong><?= htmlspecialchars($t['name']) ?></strong>
                                <?php if (!empty($t['role'])): ?><small><?= htmlspecialchars($t['role']) ?></small><?php endif; ?>
                            </div>
                        </div>
                        <p>“<?= htmlspecialchars($t['message']) ?>”</p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="opacity:.75;">Aucun témoignage approuvé pour le moment.</p>
        <?php endif; ?>
        <div style="margin-top:2rem;">
            <a class="btn" href="<?= base_url('#temoignages') ?>">Déposer un témoignage →</a>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>