<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section" id="galerie">
    <div class="container">
        <div class="section-title">
            <h2>Galerie</h2>
            <p>Découvrez nos images et vidéos.</p>
        </div>

        <div class="gallery-filters">
            <button class="active" data-filter="all">Tous</button>
            <button data-filter="image">Images</button>
            <button data-filter="video">Vidéos</button>
            <select id="gallery-category" style="padding:.6rem 1rem;border-radius:var(--radius-sm);border:1px solid var(--color-border);background:var(--color-surface);color:var(--color-text-light)">
                <option value="">Catégorie (toutes)</option>
                <?php
                $cats = array_unique(array_filter(array_map(fn($m) => $m['category'] ?? '', $media)));
                foreach ($cats as $c): ?>
                    <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="gallery-grid" id="gallery-grid" data-page="1">
            <?php if (!empty($media)): ?>
                <?php foreach (array_slice($media, 0, 24) as $m): ?>
                    <div class="gallery-item" data-type="<?= htmlspecialchars($m['type']) ?>" title="<?= htmlspecialchars($m['title']) ?>">
                        <?php if ($m['type'] === 'video'): ?>
                            <iframe loading="lazy" src="<?= htmlspecialchars($m['url']) ?>" frameborder="0" allowfullscreen></iframe>
                        <?php else: ?>
                            <img loading="lazy" src="<?= htmlspecialchars($m['url']) ?>" alt="<?= htmlspecialchars($m['title']) ?>">
                        <?php endif; ?>
                        <div class="overlay"></div>
                        <div class="caption"><?= htmlspecialchars($m['title']) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun média pour le moment.</p>
            <?php endif; ?>
        </div>
        <div id="gallery-loader" style="text-align:center;margin-top:1.6rem;display:none;">
            <span style="display:inline-block;padding:.6rem 1.2rem;border-radius:var(--radius-sm);background:var(--color-surface);box-shadow:var(--shadow-sm);">Chargement…</span>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>