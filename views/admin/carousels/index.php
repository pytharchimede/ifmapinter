<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
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