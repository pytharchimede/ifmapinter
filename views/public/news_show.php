<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="container">
        <div class="section-title">
            <h2><?= htmlspecialchars($article['title']) ?></h2>
            <?php if (!empty($article['published_at'])): ?>
                <p>Publié le <?= htmlspecialchars(date('d/m/Y', strtotime($article['published_at']))) ?></p>
            <?php endif; ?>
        </div>
        <article class="blog-article">
            <?php if (!empty($article['image_url'])): ?>
                <img src="<?= htmlspecialchars($article['image_url']) ?>" alt="" class="blog-cover">
            <?php endif; ?>
            <div class="blog-body">
                <?= nl2br(htmlspecialchars($article['body'] ?? '')) ?>
            </div>
            <div style="margin-top:1.2rem;">
                <a class="btn-outline" href="<?= base_url('actualites') ?>">← Retour aux actualités</a>
            </div>
        </article>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>