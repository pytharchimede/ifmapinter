<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="container" style="max-width:860px;">
        <article class="blog-article card" style="padding:0; overflow:hidden;">
            <?php $img = trim($article['image_url'] ?? '');
            if ($img === '') {
                $img = 'https://images.unsplash.com/photo-1498079022511-d15614cb1c02?auto=format&fit=crop&w=1600&q=60';
            } ?>
            <img loading="lazy" decoding="async" src="<?= htmlspecialchars($img) ?>" alt="Illustration" style="width:100%;height:320px;object-fit:cover;display:block;">
            <div style="padding:1.2rem 1.3rem 1.6rem;">
                <h1 style="margin:0 0 .4rem; font-size: clamp(1.6rem,3.2vw,2.2rem); color: var(--color-primary-accent);"><?= htmlspecialchars($article['title'] ?? '') ?></h1>
                <?php
                $date = $article['published_at'] ?? $article['created_at'] ?? null;
                $dateTxt = $date ? date('d/m/Y', strtotime($date)) : '';
                ?>
                <p style="color:#666;">
                    <?= $dateTxt ? ('Publié le ' . htmlspecialchars($dateTxt)) : '' ?>
                    <?php if (!empty($article['source'])): ?> • Source: <?= htmlspecialchars($article['source']) ?><?php endif; ?>
                </p>
                <div class="blog-body content" style="line-height:1.8; font-size:1.02rem; color: var(--color-text);">
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
            </div>
        </article>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>