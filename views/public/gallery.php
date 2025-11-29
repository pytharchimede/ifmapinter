<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="page-hero dark" id="galerie">
    <div class="inner">
        <span class="badge-soft">IFMAP</span>
        <h1>Galerie</h1>
        <p class="lead">Moments de formation, projets étudiants, ateliers techniques & vie de campus.</p>
        <div class="actions">
            <a href="#media" class="btn-outline">Explorer</a>
            <a href="#upload" class="btn-outline">Contribuer</a>
        </div>
    </div>
</div>
<section class="section section-tight" id="media">
    <div class="container">
        <?php $cats = array_unique(array_filter(array_map(fn($m) => $m['category'] ?? '', $media))); ?>
        <div class="segmented-filters" data-gallery-filters>
            <button class="active" data-filter="all">Tous</button>
            <button data-filter="image">Images</button>
            <button data-filter="video">Vidéos</button>
            <?php foreach ($cats as $c): ?>
                <button data-filter="<?= htmlspecialchars(strtolower($c)) ?>"><?= htmlspecialchars($c) ?></button>
            <?php endforeach; ?>
        </div>
        <div class="gallery-grid" id="gallery-grid">
            <?php if (!empty($media)): ?>
                <?php foreach ($media as $m): ?>
                    <?php $cat = strtolower($m['category'] ?? 'campus'); ?>
                    <div class="gm-item" data-type="<?= htmlspecialchars($m['type']) ?>" data-category="<?= htmlspecialchars($cat) ?>" title="<?= htmlspecialchars($m['title']) ?>">
                        <div class="gm-media">
                            <?php if ($m['type'] === 'video'): ?>
                                <iframe loading="lazy" src="<?= htmlspecialchars($m['url']) ?>" frameborder="0" allowfullscreen></iframe>
                            <?php else: ?>
                                <img loading="lazy" src="<?= htmlspecialchars($m['url']) ?>" alt="<?= htmlspecialchars($m['title']) ?>" data-lightbox-src="<?= htmlspecialchars($m['url']) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="gm-caption"><?= htmlspecialchars($m['title']) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="muted">Aucun média pour le moment.</p>
            <?php endif; ?>
        </div>
        <div class="cta-banner" id="upload">
            <h3>Vous avez un média à partager ?</h3>
            <p>Projets étudiants, démonstrations techniques, événements… proposez vos photos afin d’enrichir la galerie.</p>
            <div class="actions">
                <a class="btn-outline" href="<?= base_url('contact') ?>">Envoyer un média</a>
                <a class="btn-outline" href="<?= base_url('campus') ?>">Voir le campus</a>
            </div>
        </div>
    </div>
</section>
<div class="lightbox" id="lightbox" aria-hidden="true">
    <div class="lightbox-content">
        <img id="lightbox-img" alt="preview">
        <button class="lightbox-close" type="button" aria-label="Fermer">×</button>
    </div>
</div>
<script>
    // Filtrage
    document.querySelectorAll('[data-gallery-filters] button').forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.dataset.filter;
            btn.parentElement.querySelectorAll('button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('#gallery-grid .gm-item').forEach(item => {
                const type = item.dataset.type;
                const cat = item.dataset.category;
                const show = filter === 'all' || filter === type || filter === cat;
                item.style.display = show ? 'block' : 'none';
            });
        });
    });
    // Lightbox simple
    const lb = document.getElementById('lightbox');
    const lbImg = document.getElementById('lightbox-img');
    const openLightbox = (src) => {
        lbImg.src = src;
        lb.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    };
    const closeLightbox = () => {
        lb.setAttribute('aria-hidden', 'true');
        lbImg.removeAttribute('src');
        document.body.style.overflow = '';
    };
    document.querySelectorAll('[data-lightbox-src]').forEach(el => {
        el.addEventListener('click', () => {
            const src = el.getAttribute('data-lightbox-src');
            if (!src) return;
            openLightbox(src);
        });
    });
    document.querySelector('.lightbox-close').addEventListener('click', closeLightbox);
    lb.addEventListener('click', e => {
        if (e.target === lb) closeLightbox();
    });
    window.addEventListener('keyup', e => {
        if (e.key === 'Escape') closeLightbox();
    });
</script>
<?php include __DIR__ . '/../partials/footer.php'; ?>