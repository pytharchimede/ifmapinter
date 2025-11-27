<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<style>
    /* Admin Carousels WOW UI */
    .admin-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin: 1rem 0 1.2rem;
    }

    .admin-hero h1 {
        font-size: 1.6rem;
        margin: 0;
    }

    .admin-hero .actions {
        display: flex;
        gap: .6rem;
    }

    .admin-tip {
        margin: .6rem 0;
        color: #667;
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1rem;
    }

    .card {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        background: #0b1e33;
        color: #fff;
        box-shadow: 0 8px 28px rgba(10, 25, 40, .25);
        border: 1px solid rgba(255, 255, 255, .08);
    }

    .card-media {
        position: relative;
        height: 180px;
        background-size: cover;
        background-position: center;
    }

    .card-media::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(140deg, rgba(0, 85, 255, .35), rgba(0, 201, 167, .25));
        mix-blend-mode: multiply;
        opacity: .85;
    }

    .card .badge {
        position: absolute;
        top: .6rem;
        left: .6rem;
        background: rgba(255, 255, 255, .18);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        color: #fff;
        padding: .25rem .55rem;
        border-radius: 999px;
        font-size: .8rem;
        border: 1px solid rgba(255, 255, 255, .28);
    }

    .card .thumb {
        position: absolute;
        right: .6rem;
        top: .6rem;
        width: 54px;
        height: 54px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(0, 0, 0, .25);
        border: 1px solid rgba(255, 255, 255, .22);
    }

    .card .thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .card-body {
        background: #fff;
        color: #1f2d3d;
        padding: 1rem 1rem 1.1rem;
    }

    .card-title {
        font-weight: 700;
        color: #0b3b8f;
        margin-bottom: .35rem;
        font-size: 1.05rem;
    }

    .card-description {
        font-size: .9rem;
        color: #4b5b6b;
        min-height: 48px;
    }

    .meta {
        display: flex;
        flex-wrap: wrap;
        gap: .4rem .8rem;
        margin: .6rem 0;
        font-size: .82rem;
        color: #58697a;
    }

    .meta span {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: #f4f7fb;
        border: 1px solid #e3edf7;
        padding: .25rem .5rem;
        border-radius: 999px;
    }

    .card-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: .8rem;
    }

    .card-actions .left {
        display: flex;
        gap: .6rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .5rem .8rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        border: 1px solid #d9e2ef;
        color: #0b3b8f;
        background: #eef4ff;
    }

    .btn:hover {
        background: #dbe8ff;
    }

    .btn-danger {
        color: #8f0b0b;
        background: #ffefef;
        border-color: #ffc8c8;
    }

    .btn-danger:hover {
        background: #ffd9d9;
    }

    .handle {
        cursor: grab;
        user-select: none;
        padding: .4rem .6rem;
        border-radius: 8px;
        background: #f0f4fa;
        color: #334;
        border: 1px solid #e0e9f6;
    }

    .handle:active {
        cursor: grabbing;
    }

    /* Drag placeholder */
    .drag-placeholder {
        outline: 2px dashed #8fb4ff;
        outline-offset: -6px;
    }

    @media (max-width: 640px) {
        .card-media {
            height: 160px;
        }

        .card .thumb {
            display: none;
        }
    }
</style>
<div class="admin-hero">
    <h1>Carrousels</h1>
    <div class="actions">
        <a class="btn" href="<?= base_url('admin/carousels/create') ?>">➕ Ajouter</a>
    </div>
</div>
<?php if (!empty($error)): ?>
    <div class="admin-card" style="margin:.6rem 0;background:#2b0e0e;border-color:#7c2d12;color:#ffedd5"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<p class="admin-tip">Astuce: glissez-déposez les cartes via la poignée pour réordonner. Sauvegarde instantanée.</p>

<ul id="carousel-list" class="cards-grid" style="list-style:none; padding:0;">
    <?php foreach ($items as $it): ?>
        <li class="card" data-id="<?php echo (int)$it['id']; ?>">
            <div class="card-media" style="background-image:url('<?php echo htmlspecialchars($it['background_url']); ?>');">
                <span class="badge">#<span class="pos"><?php echo (int)$it['position']; ?></span></span>
                <div class="thumb"><img src="<?php echo htmlspecialchars($it['background_url']); ?>" alt="thumb"></div>
            </div>
            <div class="card-body">
                <div class="card-title"><?php echo htmlspecialchars($it['title'] ?: 'Sans titre'); ?></div>
                <div class="card-description"><?php echo htmlspecialchars($it['description'] ?: '—'); ?></div>
                <div class="meta">
                    <?php if (!empty($it['caption'])): ?><span>🏷️ <?php echo htmlspecialchars($it['caption']); ?></span><?php endif; ?>
                    <?php if (!empty($it['button_text'])): ?><span>🔘 <?php echo htmlspecialchars($it['button_text']); ?></span><?php endif; ?>
                    <?php if (!empty($it['button_url'])): ?><span>🔗 <?php echo htmlspecialchars($it['button_url']); ?></span><?php endif; ?>
                </div>
                <div class="card-actions">
                    <div class="left">
                        <button class="handle" title="Glisser pour déplacer">↕️ Réordonner</button>
                        <a class="btn" href="<?php echo base_url('/admin/carousels/edit?id=' . $it['id']); ?>">✏️ Éditer</a>
                    </div>
                    <a class="btn btn-danger" href="<?php echo base_url('/admin/carousels/delete?id=' . $it['id']); ?>" onclick="return confirm('Supprimer cet élément ?');">🗑️ Supprimer</a>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
    <?php if (empty($items)) : ?>
        <li>Aucun carrousel pour l'instant.</li>
    <?php endif; ?>
    <form id="orderForm" method="post" action="<?php echo base_url('/admin/carousels/order'); ?>" style="display:none;">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" />
        <input type="hidden" name="order" id="orderInput" />
    </form>
    <script>
        (function() {
            const list = document.getElementById('carousel-list');
            let dragging = null;
            let startY = 0;
            let placeholder = null;

            list.addEventListener('pointerdown', (e) => {
                const handle = e.target.closest('.handle');
                if (!handle) return;
                const card = handle.closest('li.card');
                dragging = card;
                startY = e.clientY;
                placeholder = document.createElement('li');
                placeholder.className = 'card drag-placeholder';
                placeholder.style.height = card.offsetHeight + 'px';
                list.insertBefore(placeholder, card.nextSibling);
                card.style.opacity = '.6';
                card.style.transform = 'scale(.98)';
                card.style.position = 'relative';
                card.style.zIndex = '10';
                card.setPointerCapture && card.setPointerCapture(e.pointerId);
            });

            list.addEventListener('pointermove', (e) => {
                if (!dragging) return;
                e.preventDefault();
                const card = dragging;
                const dy = e.clientY - startY;
                card.style.top = dy + 'px';

                const siblings = Array.from(list.querySelectorAll('li.card')).filter(li => li !== card && li !== placeholder);
                for (const li of siblings) {
                    const rect = li.getBoundingClientRect();
                    if (e.clientY < rect.top + rect.height / 2) {
                        list.insertBefore(placeholder, li);
                        break;
                    } else {
                        list.appendChild(placeholder);
                    }
                }
            });

            function finalizeOrder() {
                if (!dragging) return;
                dragging.style.opacity = '';
                dragging.style.transform = '';
                dragging.style.position = '';
                dragging.style.top = '';
                placeholder && list.insertBefore(dragging, placeholder);
                placeholder && placeholder.remove();
                placeholder = null;
                const ids = Array.from(list.querySelectorAll('li.card')).map(li => li.getAttribute('data-id'));
                saveOrder(ids);
                // Met à jour les badges sans reload
                list.querySelectorAll('li.card .badge .pos').forEach((el, i) => el.textContent = (i + 1));
                dragging = null;
            }

            list.addEventListener('pointerup', finalizeOrder);
            list.addEventListener('pointercancel', finalizeOrder);

            function saveOrder(ids) {
                const form = document.getElementById('orderForm');
                const input = document.getElementById('orderInput');
                input.value = JSON.stringify(ids);
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'csrf_token=' + encodeURIComponent(form.csrf_token.value) + '&order=' + encodeURIComponent(JSON.stringify(ids))
                }).then(r => r.json()).catch(() => {});
            }
        })();
    </script>
    <?php include __DIR__ . '/../../partials/admin_footer.php'; ?>