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
<table class="table" style="width:100%;margin-top:1rem;">
    <tr>
        <th>Date</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Téléphone</th>
        <th>Message</th>
    </tr>
    <?php foreach ($items as $m): ?>
        <tr>
            <td><?= htmlspecialchars($m['created_at']) ?></td>
            <td><?= htmlspecialchars($m['name']) ?></td>
            <td><?= htmlspecialchars($m['email'] ?? '') ?></td>
            <td><?= htmlspecialchars($m['phone'] ?? '') ?></td>
            <td><?= nl2br(htmlspecialchars($m['message'])) ?></td>
            <td style="white-space:nowrap">
                <?php if (!empty($m['email'])): ?>
                    <a class="btn-admin" href="mailto:<?= htmlspecialchars($m['email']) ?>?subject=Re:%20Votre%20message%20IFMAP">Répondre</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>