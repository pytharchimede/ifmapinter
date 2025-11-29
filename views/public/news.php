<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="container">
        <style>
            .tabs {
                display: flex;
                gap: 0;
                margin-bottom: 16px;
                border-bottom: 2px solid #eee;
            }

            .tab-btn {
                appearance: none;
                border: none;
                background: #f7f7f7;
                color: #333;
                padding: 12px 18px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                border-top-left-radius: 10px;
                border-top-right-radius: 10px;
                margin-right: 8px;
                transition: all .2s ease;
                box-shadow: 0 1px 0 rgba(0, 0, 0, .06);
            }

            .tab-btn.active {
                background: #0055ff;
                color: #fff;
                box-shadow: 0 6px 14px rgba(0, 85, 255, .25);
            }

            .tab-btn:hover {
                transform: translateY(-1px);
            }

            .rss-card {
                position: relative;
            }

            .rss-card a.card-link {
                position: absolute;
                inset: 0;
                z-index: 1;
                text-indent: -9999px;
            }

            .rss-card .card-body {
                position: relative;
                z-index: 2;
            }

            /* News cards */
            .news-card {
                background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: var(--radius-md);
                overflow: hidden;
                box-shadow: var(--shadow-md);
                display: flex;
                flex-direction: column;
            }

            .news-card img {
                width: 100%;
                height: 190px;
                object-fit: cover;
                display: block;
            }

            .news-card .body {
                padding: 1rem 1.1rem 1.2rem;
            }

            .news-card .meta {
                font-size: .8rem;
                color: var(--color-text-light);
                margin: .2rem 0 .6rem;
            }

            .news-card h3 {
                margin: 0 0 .4rem;
                font-size: 1.05rem;
                color: var(--color-primary-accent);
            }
        </style>
        <div class="tabs">
            <button id="tab-ifmap" class="tab-btn active" type="button">IFMAP</button>
            <button id="tab-world" class="tab-btn" type="button">Monde</button>
        </div>
        <div id="panel-ifmap">
            <div class="section-title">
                <h2>Actualités IFMAP</h2>
                <p>Nos publications officielles</p>
            </div>
            <div class="grid-3" style="margin-top:1rem;">
                <?php foreach ($items as $n): ?>
                    <?php $img = trim($n['image_url'] ?? '');
                    if ($img === '') {
                        $img = 'https://images.unsplash.com/photo-1498079022511-d15614cb1c02?auto=format&fit=crop&w=1200&q=60';
                    } ?>
                    <div class="news-card">
                        <img loading="lazy" decoding="async" src="<?= htmlspecialchars($img) ?>" alt="Illustration">
                        <div class="body">
                            <h3><?= htmlspecialchars($n['title']) ?></h3>
                            <?php $dt = $n['published_at'] ?? $n['created_at'] ?? null; ?>
                            <?php if ($dt): ?>
                                <div class="meta">Publié le <?= htmlspecialchars(date('d/m/Y', strtotime($dt))) ?></div>
                            <?php endif; ?>
                            <p><?= htmlspecialchars(mb_strimwidth(strip_tags($n['body'] ?? ''), 0, 160, '…')) ?></p>
                            <a class="btn-outline" href="<?= base_url('actualites/article?id=' . (int)$n['id']) ?>">Lire l’article →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                    <p>Aucune actualité pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($rss ?? [])): ?>
            <div id="panel-world" style="display:none;">
                <div class="section-title" style="margin-top:0;">
                    <h2>Actualités du monde</h2>
                    <p>Flux RSS externes – sélection automatique</p>
                </div>
                <div class="grid-3" style="margin-top:1rem;">
                    <?php foreach ($rss as $r): ?>
                        <?php
                        $img = '';
                        if (!empty($r['image_url'])) {
                            $img = trim($r['image_url']);
                        } elseif (!empty($r['enclosure_url'])) {
                            $img = trim($r['enclosure_url']);
                        } elseif (!empty($r['description'])) {
                            // Extraire la première image de la description RSS
                            if (preg_match('#<img[^>]+src=["\']([^"\']+)["\']#i', $r['description'], $m)) {
                                $img = $m[1];
                            }
                        }
                        if ($img === '') {
                            // Essayer de récupérer la première image de l'article cible si possible
                            $link = $r['link'] ?? '';
                            if ($link) {
                                try {
                                    $ctx = stream_context_create([
                                        'http' => ['timeout' => 2, 'user_agent' => 'IFMAP-NewsBot/1.0'],
                                        'https' => ['timeout' => 2, 'user_agent' => 'IFMAP-NewsBot/1.0']
                                    ]);
                                    $html = @file_get_contents($link, false, $ctx);
                                    if ($html) {
                                        // Essayer d'abord og:image pour une image représentative
                                        if (preg_match('#<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']#i', $html, $mOg)) {
                                            $img = $mOg[1];
                                        }
                                        // Sinon première balise <img>
                                        if ($img === '' && preg_match('#<img[^>]+src=["\']([^"\']+)["\']#i', $html, $m2)) {
                                            $img = $m2[1];
                                        }
                                    }
                                } catch (\Throwable $e) {
                                    // ignore network errors
                                }
                            }
                        }
                        if ($img === '') {
                            $img = 'https://images.unsplash.com/photo-1522199755839-a2f1f1d8b6f9?auto=format&fit=crop&w=1200&q=60';
                        }
                        ?>
                        <div class="news-card rss-card">
                            <img loading="lazy" decoding="async" src="<?= htmlspecialchars($img) ?>" alt="Illustration">
                            <a class="card-link" href="<?= htmlspecialchars($r['link']) ?>" target="_blank" rel="noopener">Ouvrir</a>
                            <div class="body">
                                <h3><?= htmlspecialchars($r['title']) ?></h3>
                                <p><?= htmlspecialchars(mb_strimwidth(strip_tags($r['description'] ?? ''), 0, 160, '…')) ?></p>
                                <div class="meta">Source externe</div>
                                <div style="margin-top:8px;">
                                    <a class="btn-outline" href="<?= htmlspecialchars($r['link']) ?>" target="_blank" rel="noopener">Ouvrir la source →</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <script>
            (function() {
                var tabIfmap = document.getElementById('tab-ifmap');
                var tabWorld = document.getElementById('tab-world');
                var panelIfmap = document.getElementById('panel-ifmap');
                var panelWorld = document.getElementById('panel-world');

                function activate(which) {
                    if (which === 'ifmap') {
                        panelIfmap.style.display = '';
                        if (panelWorld) panelWorld.style.display = 'none';
                        tabIfmap.classList.add('active');
                        tabWorld.classList.remove('active');
                    } else {
                        panelIfmap.style.display = 'none';
                        if (panelWorld) panelWorld.style.display = '';
                        tabIfmap.classList.remove('active');
                        tabWorld.classList.add('active');
                    }
                }
                tabIfmap.addEventListener('click', function() {
                    activate('ifmap');
                });
                tabWorld.addEventListener('click', function() {
                    activate('world');
                });
                // default from localStorage
                var preferred = localStorage.getItem('newsTab');
                activate(preferred === 'world' ? 'world' : 'ifmap');
            })();
        </script>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>