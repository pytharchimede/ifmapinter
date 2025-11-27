<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<h2>Messages de contact</h2>
<form method="get" action="<?= base_url('admin/contacts/export.csv') ?>" class="admin-card" style="display:flex;gap:.6rem;align-items:flex-end;">
    <div>
        <label>Début</label>
        <input type="date" name="start" style="padding:.4rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
    </div>
    <div>
        <label>Fin</label>
        <input type="date" name="end" style="padding:.4rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
    </div>
    <div style="flex:1">
        <label>Recherche</label>
        <input type="text" name="q" placeholder="Nom, email, téléphone, message" style="width:100%;padding:.4rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
    </div>
    <div>
        <button class="btn-admin" type="submit">Exporter CSV</button>
    </div>
</form>
<div class="admin-card" style="margin-top:1rem">
    <div style="display:flex;gap:.6rem;align-items:center;margin-bottom:.6rem">
        <input id="live-q" type="text" placeholder="Filtrer…" style="flex:1;padding:.5rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
        <select id="live-read" style="padding:.5rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
            <option value="all">Tous</option>
            <option value="unread">Non lus</option>
            <option value="read">Lus</option>
        </select>
    </div>
    <table class="table" id="contacts-table" style="width:100%">
        <tr>
            <th>Date</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Message</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
        <?php foreach ($items as $m): ?>
            <tr data-read="<?= (int)($m['read'] ?? 0) ?>">
                <td><?= htmlspecialchars($m['created_at']) ?></td>
                <td><?= htmlspecialchars($m['name']) ?></td>
                <td><?= htmlspecialchars($m['email'] ?? '') ?></td>
                <td><?= htmlspecialchars($m['phone'] ?? '') ?></td>
                <td><?= nl2br(htmlspecialchars($m['message'])) ?></td>
                <td>
                    <?php if (!empty($m['read'])): ?>
                        <span class="badge" style="background:#064e3b;color:#d1fae5;padding:.2rem .5rem;border-radius:999px">Lu</span>
                    <?php else: ?>
                        <span class="badge" style="background:#7c2d12;color:#ffedd5;padding:.2rem .5rem;border-radius:999px">Non lu</span>
                    <?php endif; ?>
                </td>
                <td style="white-space:nowrap">
                    <?php if (!empty($m['email'])): ?>
                        <a class="btn-admin" href="mailto:<?= htmlspecialchars($m['email']) ?>?subject=Re:%20Votre%20message%20IFMAP">Répondre</a>
                    <?php endif; ?>
                    <form method="post" action="<?= base_url('admin/contacts/mark') ?>" style="display:inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
                        <input type="hidden" name="read" value="<?= empty($m['read']) ? 1 : 0 ?>">
                        <button class="btn-admin" type="submit" style="background:#253042">
                            <?= empty($m['read']) ? 'Marquer lu' : 'Marquer non lu' ?>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<script>
    const q = document.getElementById('live-q');
    const sel = document.getElementById('live-read');
    const rows = Array.from(document.querySelectorAll('#contacts-table tr')).slice(1);

    function applyFilter() {
        const term = (q.value || '').toLowerCase();
        const readFilter = sel.value;
        rows.forEach(tr => {
            const text = tr.textContent.toLowerCase();
            const isRead = tr.getAttribute('data-read') === '1';
            const matchText = term === '' || text.indexOf(term) !== -1;
            const matchRead = readFilter === 'all' || (readFilter === 'read' && isRead) || (readFilter === 'unread' && !isRead);
            tr.style.display = (matchText && matchRead) ? '' : 'none';
        });
    }
    q.addEventListener('input', applyFilter);
    sel.addEventListener('change', applyFilter);
</script>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>