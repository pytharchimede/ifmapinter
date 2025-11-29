<?php include __DIR__ . '/partials/header.php'; ?>

<!-- ================= HERO (PARALLAX) ================= -->
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-carousel" id="hero-carousel">
        <div class="hero-track">
            <?php $settings = null;
            try {
                $settings = db()->query('SELECT logo_url FROM settings WHERE id=1')->fetch();
            } catch (Throwable $e) {
            } ?>
            <?php
            $carousels = [];
            try {
                $carousels = db()->query('SELECT * FROM carousels ORDER BY position ASC')->fetchAll();
            } catch (Throwable $e) {
                $carousels = [];
            }
            ?>
            <?php if (!empty($carousels)): ?>
                <?php foreach ($carousels as $i => $c): ?>
                    <?php
                    $bgClass = 'bg-' . (($i % 3) + 1);
                    $hasBg = !empty($c['background_url']);
                    ?>
                    <div class="hero-slide <?= $hasBg ? '' : $bgClass ?>" <?= $hasBg ? "style=\"--bg-image:url('" . htmlspecialchars($c['background_url']) . "')\"" : '' ?>>
                        <div class="hero-content">
                            <?php if (!empty($settings['logo_url'])): ?>
                                <div style="margin-bottom:12px;">
                                    <img src="<?= htmlspecialchars($settings['logo_url']) ?>" alt="IFMAP" style="max-height:56px; filter: drop-shadow(0 2px 8px rgba(0,0,0,.25));" />
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($c['title'])): ?><h1 data-anim="fade"><?= htmlspecialchars($c['title']) ?></h1><?php endif; ?>
                            <?php if (!empty($c['description'])): ?><p data-anim="fade-delayed"><?= htmlspecialchars($c['description']) ?></p><?php endif; ?>
                            <?php
                            $btnText = !empty($c['caption']) ? $c['caption'] : ($c['button_text'] ?? '');
                            $btnUrl = !empty($c['button_url']) ? $c['button_url'] : '#';
                            ?>
                            <?php if (!empty($btnText)): ?>
                                <a href="<?= htmlspecialchars($btnUrl) ?>" class="btn-primary" data-anim="fade-delayed2"><?= htmlspecialchars($btnText) ?></a>
                            <?php endif; ?>
                        </div>
                        <?php /* caption now used as button text; no extra caption badge */ ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback statique si aucun carrousel configuré -->
                <div class="hero-slide bg-1">
                    <div class="hero-content">
                        <h1 data-anim="fade">Institut IFMAP</h1>
                        <p data-anim="fade-delayed">Nous formons les compétences de demain avec excellence, innovation et impact.</p>
                        <a href="<?= base_url('programmes') ?>" class="btn-primary" data-anim="fade-delayed2">Découvrir nos Programmes</a>
                    </div>
                </div>
                <div class="hero-slide bg-2">
                    <div class="hero-content">
                        <h1 data-anim="fade">Formations d’excellence</h1>
                        <p data-anim="fade-delayed">Des parcours professionnalisants alignés sur les besoins des entreprises.</p>
                        <a href="<?= base_url('formations') ?>" class="btn-primary" data-anim="fade-delayed2">Voir les Formations</a>
                    </div>
                </div>
                <div class="hero-slide bg-3">
                    <div class="hero-content">
                        <h1 data-anim="fade">Entreprises partenaires</h1>
                        <p data-anim="fade-delayed">Un réseau actif pour l’insertion et l’employabilité de nos diplômés.</p>
                        <a href="<?= base_url('partenaires') ?>" class="btn-primary" data-anim="fade-delayed2">Nos Partenaires</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <button class="hero-arrow left" aria-label="Précédent">❮</button>
        <button class="hero-arrow right" aria-label="Suivant">❯</button>
        <div class="hero-dots"></div>
    </div>
</section>


<!-- ================= PROGRAMMES ================= -->
<section class="section" id="programmes">
    <div class="container">
        <div class="section-title">
            <h2>Nos Programmes</h2>
            <p>Une offre académique structurée comme les grandes institutions internationales.</p>
        </div>

        <div class="grid-3">
            <?php if (!empty($programmes)): ?>
                <?php foreach ($programmes as $p): ?>
                    <div class="card">
                        <img loading="lazy" src="<?= htmlspecialchars($p['image_url'] ?? 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0') ?>" alt="">
                        <div class="card-body">
                            <h3><?= htmlspecialchars($p['name']) ?></h3>
                            <p><?= htmlspecialchars($p['description'] ?? '') ?></p>
                            <a href="#" class="btn-outline open-modal">En savoir plus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card">
                    <img loading="lazy" src="https://images.unsplash.com/photo-1523580846011-d3a5bc25702b" alt="">
                    <div class="card-body">
                        <h3>Programme Fondamental</h3>
                        <p>Bases professionnelles et techniques pour débutants.</p>
                        <a href="#" class="btn-outline">En savoir plus</a>
                    </div>
                </div>
                <div class="card">
                    <img loading="lazy" src="https://images.pexels.com/photos/3184639/pexels-photo-3184639.jpeg" alt="">
                    <div class="card-body">
                        <h3>Programme Technique & Industrie</h3>
                        <p>Énergie, électricité, mécanique, maintenance…</p>
                        <a href="#" class="btn-outline">En savoir plus</a>
                    </div>
                </div>
                <div class="card">
                    <img loading="lazy" src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0" alt="">
                    <div class="card-body">
                        <h3>Management & Filières tertiaires</h3>
                        <p>Commerce, gestion, vente et services.</p>
                        <a href="#" class="btn-outline">En savoir plus</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>


<!-- ================= FORMATIONS ================= -->
<section class="section bg-light" id="formations">
    <div class="container">

        <div class="section-title">
            <h2>Formations IFMAP</h2>
            <p>Des formations professionnalisantes adaptées au marché africain.</p>
        </div>

        <div class="grid-4">
            <?php if (!empty($formations)): ?>
                <?php foreach ($formations as $f): ?>
                    <div class="card-formation">
                        <img loading="lazy" src="<?= htmlspecialchars($f['image_url'] ?? 'https://images.unsplash.com/photo-1509395062183-67c5ad6faff9') ?>">
                        <h3><?= htmlspecialchars($f['name']) ?></h3>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card-formation">
                    <img loading="lazy" src="https://images.pexels.com/photos/9800038/pexels-photo-9800038.jpeg">
                    <h3>Pompiste / Station-service</h3>
                </div>
                <div class="card-formation">
                    <img loading="lazy" src="https://images.pexels.com/photos/7567445/pexels-photo-7567445.jpeg">
                    <h3>Caissière & Rayonniste</h3>
                </div>
                <div class="card-formation">
                    <img loading="lazy" src="https://images.unsplash.com/photo-1509395062183-67c5ad6faff9">
                    <h3>Technicien Solaire</h3>
                </div>
                <div class="card-formation">
                    <img loading="lazy" src="https://images.pexels.com/photos/4484078/pexels-photo-4484078.jpeg">
                    <h3>Transport & Logistique</h3>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>


<!-- ================= INSTITUTS & CENTRES ================= -->
<section class="section" id="centres">
    <div class="container">
        <?php
        // Centres dynamiques depuis la BDD (publiés uniquement)
        $centres = [];
        try {
            $centres = db()->query("SELECT * FROM centres WHERE COALESCE(status,'published')='published' ORDER BY id DESC")->fetchAll();
        } catch (Throwable $e) {
            $centres = [];
        }
        // En-tête dynamique depuis sections
        $centTitle = 'Instituts & Centres IFMAP';
        $centSubtitle = 'Découvrez nos pôles d\'excellence et d\'innovation.';
        try {
            $st = db()->prepare('SELECT title, subtitle FROM sections WHERE `key`=?');
            $st->execute(['centres']);
            $row = $st->fetch();
            if ($row) {
                $centTitle = $row['title'] ?: $centTitle;
                $centSubtitle = $row['subtitle'] ?: $centSubtitle;
            }
        } catch (Throwable $e) {
        }
        ?>
        <div class="section-title">
            <h2><?= htmlspecialchars($centTitle) ?></h2>
            <p><?= htmlspecialchars($centSubtitle) ?></p>
        </div>

        <div class="grid-3">
            <?php if (!empty($centres)): ?>
                <?php foreach ($centres as $c): ?>
                    <div class="centre">
                        <img loading="lazy" src="<?= htmlspecialchars($c['image_url'] ?? 'https://images.pexels.com/photos/3182753/pexels-photo-3182753.jpeg') ?>">
                        <h3><?= htmlspecialchars($c['name']) ?></h3>
                        <?php if (!empty($c['excerpt'])): ?><p><?= htmlspecialchars($c['excerpt']) ?></p><?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="centre">
                    <img loading="lazy" src="https://images.pexels.com/photos/3182753/pexels-photo-3182753.jpeg">
                    <h3>Centre Énergie & Industrie</h3>
                    <p>Spécialiste des métiers techniques, industriels et durables.</p>
                </div>
                <div class="centre">
                    <img loading="lazy" src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f">
                    <h3>Institut Commerce & Services</h3>
                    <p>Commerce, distribution, relation client et gestion.</p>
                </div>
                <div class="centre">
                    <img loading="lazy" src="https://images.pexels.com/photos/590020/pexels-photo-590020.jpeg">
                    <h3>Institut Transport & Sécurité</h3>
                    <p>Logistique, sécurité routière, mobilité.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>


<!-- ================= ACTUALITÉS (CARROUSEL) ================= -->
<section class="section bg-light" id="news">
    <div class="container">

        <div class="section-title">
            <h2>Actualités</h2>
            <p>Les dernières nouvelles de l’Institut IFMAP</p>
        </div>

        <div class="carousel" id="carousel">
            <?php foreach ($news ?? [] as $n): ?>
                <div class="carousel-item">
                    <img loading="lazy" src="<?= htmlspecialchars($n['image_url'] ?? 'https://images.unsplash.com/photo-1498079022511-d15614cb1c02') ?>">
                    <div class="info">
                        <h3><?= htmlspecialchars($n['title']) ?></h3>
                        <p><?= htmlspecialchars(mb_strimwidth($n['body'] ?? '', 0, 180, '…')) ?></p>
                        <a class="btn-outline" href="<?= base_url('actualites/article?id=' . (int)$n['id']) ?>">Lire l’article →</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($news ?? [])): ?>
                <div class="info" style="text-align:center;padding:16px;">
                    <p>Aucune actualité pour le moment. Revenez bientôt.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php
        // Aperçu des actualités du monde (RSS cache) pour inciter à consulter
        $rssPreview = [];
        try {
            $rows = db()->query("SELECT title, link, description FROM rss_items_cache WHERE expires_at > NOW() ORDER BY pub_date DESC LIMIT 6")->fetchAll();
            $rssPreview = $rows ?: [];
        } catch (Throwable $e) {
        }
        ?>
        <?php if (!empty($rssPreview)): ?>
            <div class="section-title" style="margin-top:2rem;">
                <h3>Actualités du monde</h3>
                <p>Extrait des flux RSS externes</p>
            </div>
            <div class="grid-3">
                <?php foreach ($rssPreview as $r): ?>
                    <?php
                    $img = '';
                    // Essayer description d'abord
                    if (!empty($r['description'])) {
                        if (preg_match('#<img[^>]+src=["\']([^"\']+)["\']#i', $r['description'], $m)) {
                            $img = $m[1];
                        }
                    }
                    // Sinon tenter depuis la page (og:image puis <img>)
                    if ($img === '') {
                        $link = $r['link'] ?? '';
                        if ($link) {
                            try {
                                $ctx = stream_context_create([
                                    'http' => ['timeout' => 2, 'user_agent' => 'IFMAP-HomeNewsBot/1.0'],
                                    'https' => ['timeout' => 2, 'user_agent' => 'IFMAP-HomeNewsBot/1.0']
                                ]);
                                $html = @file_get_contents($link, false, $ctx);
                                if ($html) {
                                    if (preg_match('#<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']#i', $html, $mOg)) {
                                        $img = $mOg[1];
                                    }
                                    if ($img === '' && preg_match('#<img[^>]+src=["\']([^"\']+)["\']#i', $html, $m2)) {
                                        $img = $m2[1];
                                    }
                                }
                            } catch (\Throwable $e) {
                                // ignore
                            }
                        }
                    }
                    if ($img === '') {
                        $img = 'https://images.unsplash.com/photo-1522199755839-a2f1f1d8b6f9?auto=format&fit=crop&w=1200&q=60';
                    }
                    ?>
                    <div class="card">
                        <img loading="lazy" src="<?= htmlspecialchars($img) ?>" alt="Illustration" style="width:100%;height:180px;object-fit:cover;display:block;">
                        <div class="card-body">
                            <h4><?= htmlspecialchars($r['title']) ?></h4>
                            <p><?= htmlspecialchars(mb_strimwidth($r['description'] ?? '', 0, 160, '…')) ?></p>
                            <a class="btn-outline" href="<?= htmlspecialchars($r['link']) ?>" target="_blank" rel="noopener">Ouvrir la source →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="margin-top:12px;">
                <a class="btn" href="<?= base_url('actualites') ?>" onclick="localStorage.setItem('newsTab','world');">Voir plus d'actualités du monde</a>
            </div>
        <?php endif; ?>

    </div>
</section>


<!-- ================= PARTENAIRES ================= -->
<section class="section" id="partenaires">
    <div class="container">

        <div class="section-title">
            <h2>Entreprises & Partenaires</h2>
            <p>Ils nous accompagnent dans l’insertion professionnelle.</p>
        </div>

        <div class="partners">
            <?php if (!empty($partners)): ?>
                <?php foreach ($partners as $p): ?>
                    <?php if (!empty($p['logo_url'])): ?>
                        <img loading="lazy" src="<?= htmlspecialchars($p['logo_url']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun partenaire pour le moment.</p>
            <?php endif; ?>
        </div>

    </div>
</section>


<!-- ================= TÉMOIGNAGES ================= -->
<section class="section bg-light" id="temoignages">
    <div class="container">
        <div class="section-title">
            <h2>Témoignages</h2>
            <p>Ils partagent leur expérience IFMAP.</p>
        </div>
        <div class="testimonials">
            <?php if (!empty($testimonials)): ?>
                <?php foreach ($testimonials as $t): ?>
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
            <?php else: ?>
                <p style="opacity:.75; font-size:.95rem;">Aucun témoignage validé pour l’instant.</p>
            <?php endif; ?>
        </div>
        <?php if (!empty($testimonials)): ?>
            <div style="margin-top:1.2rem; text-align:right;">
                <a class="btn-outline" href="<?= base_url('temoignages') ?>">Voir tous les témoignages →</a>
            </div>
        <?php endif; ?>

        <div class="card" style="margin-top:1.25rem;">
            <div class="card-body">
                <h3 style="margin-top:0;">Vous aussi, témoignez</h3>
                <style>
                    .t-shell {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 16px;
                        align-items: start;
                        position: relative;
                        z-index: 10;
                    }

                    @media (max-width: 900px) {
                        .t-shell {
                            grid-template-columns: 1fr;
                        }
                    }

                    .preview-card {
                        background: #fff;
                        border: 1px solid #eee;
                        border-radius: 12px;
                        padding: 16px;
                        box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
                    }

                    .preview-author {
                        display: flex;
                        gap: 12px;
                        align-items: center;
                        margin-bottom: 8px;
                    }

                    .preview-avatar {
                        width: 54px;
                        height: 54px;
                        border-radius: 50%;
                        object-fit: cover;
                        background: #f5f5f5;
                        border: 1px solid #eee;
                        box-shadow: var(--shadow-sm);
                        cursor: pointer;
                    }

                    .preview-name {
                        font-weight: 600;
                        margin: 0;
                    }

                    .preview-role {
                        color: #666;
                        font-size: 14px;
                        margin: 0;
                    }

                    .preview-msg {
                        margin-top: 8px;
                        font-size: 16px;
                        line-height: 1.5;
                    }

                    .form-stack {
                        display: grid;
                        gap: 12px;
                    }

                    .form-row {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 12px;
                    }

                    @media (max-width: 600px) {
                        .form-row {
                            grid-template-columns: 1fr;
                        }
                    }

                    .form-control,
                    .textarea-control {
                        width: 100%;
                        padding: 10px 12px;
                        border: 1px solid #d9d9d9;
                        border-radius: 10px;
                        outline: none;
                        background: #fff;
                    }

                    .form-control:focus,
                    .textarea-control:focus {
                        border-color: #1677ff;
                        box-shadow: 0 0 0 3px rgba(22, 119, 255, .12);
                    }

                    .textarea-control {
                        resize: vertical;
                        min-height: 100px;
                    }

                    .file-input {
                        padding: 10px;
                        border: 1px dashed #cbd5e1;
                        border-radius: 10px;
                        background: #fafafa;
                        display: block;
                        cursor: pointer;
                    }

                    .muted {
                        color: #666;
                    }

                    .alert-success {
                        background: #f6ffed;
                        border: 1px solid #b7eb8f;
                        color: #237804;
                        padding: 10px 12px;
                        border-radius: 8px;
                        margin-bottom: 8px;
                    }

                    .alert-error {
                        background: #fff1f0;
                        border: 1px solid #ffa39e;
                        color: #a8071a;
                        padding: 10px 12px;
                        border-radius: 8px;
                        margin-bottom: 8px;
                    }

                    #pv-avatar {
                        height: 56px;
                        width: 56px;
                    }
                </style>
                <?php if (isset($_GET['tks']) && $_GET['tks'] == '1'): ?>
                    <div class="alert-success">Merci ! Votre témoignage sera publié après validation.</div>
                <?php elseif (isset($_GET['tks']) && $_GET['tks'] == '0'): ?>
                    <div class="alert-error">Veuillez renseigner au moins votre nom et votre témoignage.</div>
                <?php endif; ?>

                <div class="t-shell">
                    <!-- Aperçu en temps réel -->
                    <div class="preview-card" aria-live="polite">
                        <div class="preview-author">
                            <img id="pv-avatar" class="preview-avatar" src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=200&h=200&fit=crop" alt="Avatar">
                            <div>
                                <p id="pv-name" class="preview-name">Votre nom</p>
                                <p id="pv-role" class="preview-role">Rôle (ex: Alumni – Management)</p>
                            </div>
                        </div>
                        <p id="pv-message" class="preview-msg">“Partagez votre expérience IFMAP…”</p>
                    </div>

                    <!-- Formulaire -->
                    <form class="t-form" method="post" enctype="multipart/form-data" action="<?= base_url('/temoignages/soumettre') ?>">
                        <?= csrf_field() ?>
                        <div class="form-stack">
                            <div class="form-row">
                                <div>
                                    <label>Nom *</label>
                                    <input id="t-name" class="form-control" type="text" name="name" required placeholder="Votre nom" />
                                </div>
                                <div>
                                    <label>Rôle</label>
                                    <input id="t-role" class="form-control" type="text" name="role" placeholder="Alumni – Management" />
                                </div>
                            </div>
                            <div>
                                <label>Message *</label>
                                <textarea id="t-message" class="textarea-control" name="message" rows="3" required placeholder="Partagez votre expérience IFMAP…"></textarea>
                            </div>
                            <div>
                                <label>Photo (optionnel)</label>
                                <input id="t-avatar" class="file-input" type="file" name="avatar_file" accept="image/*" />
                                <div class="muted" style="margin-top:6px; font-size:12px;">JPG/PNG/WebP, 2 Mo max.</div>
                            </div>
                            <div style="display:flex; gap:8px; align-items:center; margin-top:4px;">
                                <button class="btn-primary" type="submit">Envoyer</button>
                                <small class="muted">Votre témoignage sera publié après modération.</small>
                            </div>
                        </div>
                    </form>
                </div>

                <script>
                    (function() {
                        const $ = s => document.querySelector(s);
                        const nameI = $('#t-name');
                        const roleI = $('#t-role');
                        const msgI = $('#t-message');
                        const avatarI = $('#t-avatar');
                        const pvName = $('#pv-name');
                        const pvRole = $('#pv-role');
                        const pvMsg = $('#pv-message');
                        const pvAvatar = $('#pv-avatar');
                        const sync = () => {
                            pvName.textContent = nameI.value.trim() || 'Votre nom';
                            pvRole.textContent = roleI.value.trim() || 'Rôle (ex: Alumni – Management)';
                            const t = msgI.value.trim();
                            pvMsg.textContent = t ? '“' + t + '”' : '“Partagez votre expérience IFMAP…”';
                        };
                        nameI.addEventListener('input', sync);
                        roleI.addEventListener('input', sync);
                        msgI.addEventListener('input', sync);
                        // Clicking the avatar preview opens the file picker
                        pvAvatar.addEventListener('click', function() {
                            avatarI.click();
                        });
                        avatarI.addEventListener('change', function() {
                            if (this.files && this.files[0]) {
                                const file = this.files[0];
                                const reader = new FileReader();
                                reader.onload = e => {
                                    pvAvatar.src = e.target.result;
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    })();
                </script>
            </div>
        </div>
    </div>
</section>


<!-- ================= ÉVÉNEMENTS ================= -->
<section class="section" id="evenements">
    <div class="container">
        <div class="section-title">
            <h2>Venez nous rencontrer</h2>
            <p>Participez à nos événements et découvrez IFMAP.</p>
        </div>
        <div class="events">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $e): ?>
                    <?php $ts = strtotime($e['event_date']); ?>
                    <div class="event-card">
                        <div class="event-date">
                            <div class="day"><?= date('d', $ts) ?></div>
                            <?php
                            $m = (int)date('n', $ts);
                            $monthsFr = [1 => 'jan', 2 => 'févr', 3 => 'mars', 4 => 'avr', 5 => 'mai', 6 => 'juin', 7 => 'juil', 8 => 'août', 9 => 'sept', 10 => 'oct', 11 => 'nov', 12 => 'déc'];
                            $monthShort = $monthsFr[$m] ?? date('M', $ts);
                            ?>
                            <div class="month"><?= htmlspecialchars($monthShort) ?></div>
                        </div>
                        <div class="event-info">
                            <h4><?= htmlspecialchars($e['title']) ?></h4>
                            <div class="meta">
                                <span><?= htmlspecialchars(date('Y', $ts)) ?></span>
                                <?php if (!empty($e['language'])): ?><span><?= htmlspecialchars($e['language']) ?></span><?php endif; ?>
                                <?php if (!empty($e['program'])): ?><span><?= htmlspecialchars($e['program']) ?></span><?php endif; ?>
                            </div>
                            <p><?= htmlspecialchars(mb_strimwidth($e['description'] ?? '', 0, 120, '…')) ?></p>
                            <div class="event-actions">
                                <?php
                                $cta = trim($e['cta_url'] ?? '');
                                $isExternal = preg_match('~^https?://~i', $cta);
                                if ($cta !== '' && $isExternal): ?>
                                    <a class="btn-outline" href="<?= htmlspecialchars($cta) ?>" target="_blank" rel="noopener">Inscription</a>
                                <?php else: ?>
                                    <a class="btn-outline" href="<?= base_url('evenements/inscription?id=' . (int)$e['id']) ?>">Inscription</a>
                                <?php endif; ?>
                                <?php
                                $title = $e['title'] ?? 'Événement IFMAP';
                                $desc  = $e['description'] ?? '';
                                $start = date('Ymd', $ts);
                                $end   = date('Ymd', strtotime('+1 day', $ts));
                                $gcal  = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
                                    . '&text=' . urlencode($title)
                                    . '&dates=' . $start . '/' . $end
                                    . '&details=' . urlencode($desc)
                                    . '&location=' . urlencode('IFMAP');
                                $ics   = base_url('evenements/ics?id=' . (int)$e['id']);
                                ?>
                                <span class="muted" style="margin-left:.4rem;">Ajouter à mon agenda:</span>
                                <a class="btn-outline" href="<?= $gcal ?>" target="_blank" rel="noopener" title="Ajouter à Google Calendar">
                                    <i class="fa-brands fa-google"></i>
                                    <span style="margin-left:.35rem;">Google</span>
                                </a>
                                <a class="btn-outline" href="<?= $ics ?>" title="Télécharger un fichier ICS (Apple/Outlook)">
                                    <i class="fa-regular fa-calendar-plus"></i>
                                    <span style="margin-left:.35rem;">ICS</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="info" style="text-align:center;padding:16px;">
                    <p>Aucun événement à venir pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div style="margin:.6rem 0">
        <?php $host = $_SERVER['HTTP_HOST'] ?? '';
        if (stripos($host, 'localhost') === false) : ?>
            <!-- Tawk.to widget activé (désactivé en localhost) -->
            <script type="text/javascript">
                var Tawk_API = Tawk_API || {},
                    Tawk_LoadStart = new Date();
                (function() {
                    var s1 = document.createElement("script"),
                        s0 = document.getElementsByTagName("script")[0];
                    s1.async = true;
                    s1.src = 'https://embed.tawk.to/6928cda30d02891959544218/1jb3m6i8h';
                    s1.charset = 'UTF-8';
                    s1.setAttribute('crossorigin', '*');
                    s0.parentNode.insertBefore(s1, s0);
                })();
            </script>
        <?php endif; ?>
        <!-- Crisp chat (optionnel) -->
        <!--
                        <script type="text/javascript">
                        window.$crisp=[];window.CRISP_WEBSITE_ID="YOUR_WEBSITE_ID";
                        (function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();
                        </script>
                        -->
    </div>
</section>

<?php include __DIR__ . '/partials/modal.php'; ?>
<?php include __DIR__ . '/partials/footer.php'; ?>