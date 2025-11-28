<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<div class="admin-main">
    <div class="admin-hero">
        <h1><?= htmlspecialchars($title) ?></h1>
        <div class="actions">
            <a class="btn" href="<?= base_url('admin/events') ?>">← Retour événements</a>
            <a class="btn" href="<?= base_url('admin/events/registrations/export' . (isset($event['id']) ? ('?event_id=' . (int)$event['id']) : '')) ?>">Exporter CSV</a>
        </div>
    </div>
    <?php if ($event): ?>
        <div class="admin-card" style="margin-bottom:1rem;">
            <strong>Événement:</strong> <?= htmlspecialchars($event['title']) ?><br>
            <small>Date: <?= htmlspecialchars(date('d/m/Y H:i', strtotime($event['event_date']))) ?></small>
        </div>
    <?php endif; ?>
    <div class="admin-card">
        <form method="get" action="" style="display:flex;flex-wrap:wrap;gap:.6rem;margin-bottom:1rem;align-items:flex-end;">
            <?php if ($event): ?><input type="hidden" name="event_id" value="<?= (int)$event['id'] ?>"><?php endif; ?>
            <div>
                <label style="font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8;">Début</label>
                <input type="date" name="start" value="<?= htmlspecialchars($start ?? '') ?>" class="form-control" style="padding:.4rem .6rem;">
            </div>
            <div>
                <label style="font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8;">Fin</label>
                <input type="date" name="end" value="<?= htmlspecialchars($end ?? '') ?>" class="form-control" style="padding:.4rem .6rem;">
            </div>
            <div>
                <label style="font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8;">Statut</label>
                <select name="status" class="form-control" style="padding:.4rem .6rem;">
                    <option value="">Tous</option>
                    <?php $curStatus = $_GET['status'] ?? ''; ?>
                    <option value="pending" <?= $curStatus === 'pending' ? 'selected' : '' ?>>En attente</option>
                    <option value="confirmed" <?= $curStatus === 'confirmed' ? 'selected' : '' ?>>Confirmée</option>
                    <option value="cancelled" <?= $curStatus === 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                </select>
            </div>
            <button class="btn-admin" type="submit" style="height:32px;padding:0 12px;">Filtrer</button>
            <a href="<?= base_url('admin/events/registrations' . ($event ? ('?event_id=' . (int)$event['id']) : '')) ?>" class="btn" style="height:32px;padding:0 12px;display:flex;align-items:center;">Réinitialiser</a>
        </form>
        <?php if (empty($items)): ?>
            <p style="opacity:.7;">Aucune inscription.</p>
        <?php else: ?>
            <table class="table" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="text-align:left;">
                        <?php if (!$event): ?><th>Événement</th><?php endif; ?>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Message</th>
                        <th>Consent</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $r): ?>
                        <tr style="border-bottom:1px solid #1f2940;">
                            <?php if (!$event): ?><td style="padding:.55rem .6rem; font-size:.85rem;"><?= htmlspecialchars($r['title'] ?? '') ?></td><?php endif; ?>
                            <td style="padding:.55rem .6rem; font-size:.85rem; font-weight:600;"><?= htmlspecialchars($r['name']) ?></td>
                            <td style="padding:.55rem .6rem; font-size:.85rem;"><?= htmlspecialchars($r['email']) ?></td>
                            <td style="padding:.55rem .6rem; font-size:.85rem;"><?= htmlspecialchars($r['phone']) ?></td>
                            <td style="padding:.55rem .6rem; font-size:.85rem; max-width:240px;"><?= htmlspecialchars(mb_strimwidth($r['message'] ?? '', 0, 140, '…')) ?></td>
                            <td style="padding:.55rem .6rem; font-size:.7rem;">
                                <?= ((int)($r['consent'] ?? 0) === 1) ? 'Oui' : 'Non' ?>
                            </td>
                            <td style="padding:.55rem .6rem; font-size:.75rem;">
                                <?php $st = $r['status'] ?? 'pending'; ?>
                                <span style="display:inline-block;padding:.25rem .5rem;border-radius:8px;background:<?= $st === 'confirmed' ? '#16a34a' : '#f59e0b' ?>20;color:<?= $st === 'confirmed' ? '#16a34a' : '#d97706' ?>;border:1px solid <?= $st === 'confirmed' ? '#16a34a' : '#f59e0b' ?>30; font-weight:600;font-size:.7rem;">
                                    <?= $st === 'confirmed' ? 'Confirmée' : ($st === 'cancelled' ? 'Annulée' : 'En attente') ?>
                                </span>
                                <div style="margin-top:4px;display:flex;gap:4px;">
                                    <a href="<?= base_url('admin/events/registrations/status?reg_id=' . (int)$r['id'] . '&status=confirmed') ?>" style="font-size:.65rem;">Confirmer</a>
                                    <a href="<?= base_url('admin/events/registrations/status?reg_id=' . (int)$r['id'] . '&status=cancelled') ?>" style="font-size:.65rem; color:#dc2626;">Annuler</a>
                                </div>
                            </td>
                            <td style="padding:.55rem .6rem; font-size:.75rem; color:#94a3b8;"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($r['created_at']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>