<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="container" style="max-width:860px;">
        <article class="blog-article card" style="padding:20px;">
            <h1 style="margin-top:0;"><?= htmlspecialchars($article['title'] ?? '') ?></h1>
            <?php
            $date = $article['published_at'] ?? $article['created_at'] ?? null;
            $dateTxt = $date ? date('d/m/Y', strtotime($date)) : '';
            ?>
            <p style="color:#666;">
                <?= $dateTxt ? ('Publié le ' . htmlspecialchars($dateTxt)) : '' ?>
                <?php if (!empty($article['source'])): ?> • Source: <?= htmlspecialchars($article['source']) ?><?php endif; ?>
            </p>
            <?php if (!empty($article['image_url'])): ?>
                <img src="<?= htmlspecialchars($article['image_url']) ?>" alt="" class="blog-cover" style="width:100%;height:auto;border-radius:6px;">
            <?php endif; ?>
            <div class="blog-body content" style="line-height:1.7;">
                <?= $article['body'] ?? '' ?>
            </div>
            <?php if (!empty($article['article_url'])): ?>
                <div style="margin-top:1rem;">
                    <a class="btn" target="_blank" rel="noopener" href="<?= htmlspecialchars($article['article_url']) ?>">Lire l'article d'origine</a>
                </div>
            <?php endif; ?>
            <div style="margin-top:1.2rem;">
                <a class="btn-outline" href="<?= base_url('actualites') ?>">← Retour aux actualités</a>
            </div>
        </article>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>