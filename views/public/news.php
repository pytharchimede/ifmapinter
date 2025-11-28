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
                padding: 14px 24px;
                font-size: 18px;
                font-weight: 600;
                cursor: pointer;
                border-top-left-radius: 10px;
                border-top-right-radius: 10px;
                margin-right: 8px;
                transition: all .2s ease;
                box-shadow: 0 1px 0 rgba(0, 0, 0, 0.06);
            }

            .tab-btn.active {
                background: #004b9a;
                color: #fff;
                box-shadow: 0 6px 14px rgba(0, 75, 154, 0.25);
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
            <div class="grid-3">
                <?php foreach ($items as $n): ?>
                    <div class="card">
                        <img src="<?= htmlspecialchars($n['image_url'] ?? 'https://images.unsplash.com/photo-1498079022511-d15614cb1c02') ?>" alt="">
                        <div class="card-body">
                            <h3><?= htmlspecialchars($n['title']) ?></h3>
                            <?php $dt = $n['published_at'] ?? $n['created_at'] ?? null; ?>
                            <?php if ($dt): ?>
                                <small style="color:#777;display:block;margin-bottom:6px;">Publié le <?= htmlspecialchars(date('d/m/Y', strtotime($dt))) ?></small>
                            <?php endif; ?>
                            <p><?= htmlspecialchars(mb_strimwidth(strip_tags($n['body'] ?? ''), 0, 140, '…')) ?></p>
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
                <div class="grid-3">
                    <?php foreach ($rss as $r): ?>
                        <div class="card rss-card">
                            <a class="card-link" href="<?= htmlspecialchars($r['link']) ?>" target="_blank" rel="noopener">Ouvrir</a>
                            <div class="card-body">
                                <h4 style="margin-top:0;"><?= htmlspecialchars($r['title']) ?></h4>
                                <p><?= htmlspecialchars(mb_strimwidth($r['description'] ?? '', 0, 160, '…')) ?></p>
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