<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<h1>Carrousels</h1>
<?php if (!empty($error)): ?>
    <div class="admin-card" style="margin:.6rem 0;background:#2b0e0e;border-color:#7c2d12;color:#ffedd5"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<p><a class="btn-admin" href="<?= base_url('admin/carousels/create') ?>">Ajouter</a></p>

<p style="margin:10px 0; color:#666;">Astuce: glissez-déposez les cartes pour réordonner. L'ordre est sauvegardé instantanément.</p>

<ul id="carousel-list" class="cards-grid" style="list-style:none; padding:0;">
    <?php foreach ($items as $it): ?>
        <li class="card" draggable="true" data-id="<?php echo (int)$it['id']; ?>">
            <div class="card-media" style="background-image:url('<?php echo htmlspecialchars($it['background_url']); ?>');">
                <div class="overlay">
                    <div class="caption">#<?php echo (int)$it['position']; ?> — <?php echo htmlspecialchars($it['caption']); ?></div>
                </div>
            </div>
            <div class="card-body">
                <div class="card-title"><?php echo htmlspecialchars($it['title']); ?></div>
                <div class="card-description"><?php echo htmlspecialchars($it['description']); ?></div>
                <div class="card-actions">
                    <a class="btn" href="<?php echo base_url('/admin/carousels/edit?id=' . $it['id']); ?>">Éditer</a>
                    <a class="btn btn-danger" href="<?php echo base_url('/admin/carousels/delete?id=' . $it['id']); ?>" onclick="return confirm('Supprimer cet élément ?');">Supprimer</a>
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
            let dragEl = null;
            list.addEventListener('dragstart', (e) => {
                const li = e.target.closest('li.card');
                dragEl = li;
                e.dataTransfer.effectAllowed = 'move';
            });
            list.addEventListener('dragover', (e) => {
                e.preventDefault();
                const li = e.target.closest('li.card');
                if (!li || li === dragEl) return;
                const rect = li.getBoundingClientRect();
                const next = (e.clientY - rect.top) / rect.height > 0.5;
                list.insertBefore(dragEl, next ? li.nextSibling : li);
            });
            list.addEventListener('drop', (e) => {
                e.preventDefault();
                saveOrder();
            });
            list.addEventListener('dragend', () => {
                saveOrder();
            });

            function saveOrder() {
                const ids = Array.from(list.querySelectorAll('li.card')).map(li => li.getAttribute('data-id'));
                const form = document.getElementById('orderForm');
                const input = document.getElementById('orderInput');
                input.value = JSON.stringify(ids);
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'csrf_token=' + encodeURIComponent(form.csrf_token.value) + '&order=' + encodeURIComponent(JSON.stringify(ids))
                }).then(r => r.json()).then(() => {
                    // reload to refresh position labels
                    window.location.href = '<?php echo base_url('/admin/carousels'); ?>';
                }).catch(() => {});
            }
        })();
    </script>
    <?php include __DIR__ . '/../../partials/admin_footer.php'; ?>