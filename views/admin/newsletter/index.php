<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<div class="admin-main">
    <div class="admin-hero">
        <h1><?= htmlspecialchars($title) ?></h1>
        <div class="actions">
            <a class="btn" href="<?= base_url('admin/settings') ?>">Paramètres</a>
            <a class="btn" href="<?= base_url('admin/newsletter/export.csv') ?>">Exporter CSV</a>
        </div>
    </div>
    <div class="admin-card">
        <?php if (empty($rows)): ?>
            <p style="opacity:.7;">Aucune inscription pour l'instant.</p>
        <?php else: ?>
            <table class="table" style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="text-align:left;">
                        <th>Email</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r): ?>
                        <tr style="border-bottom:1px solid #1f2940;">
                            <td style="padding:.55rem .6rem; font-size:.9rem; font-weight:600;"><?= htmlspecialchars($r['email']) ?></td>
                            <td style="padding:.55rem .6rem; font-size:.8rem; color:#94a3b8;"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($r['created_at']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>