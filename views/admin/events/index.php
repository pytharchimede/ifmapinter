<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<div class="admin-main">
    <div class="admin-hero">
        <h1><?= htmlspecialchars($title) ?></h1>
        <div class="actions">
            <a class="btn" href="<?= base_url('admin/events/create') ?>">Nouvel événement</a>
        </div>
    </div>

    <div class="cards-grid">
        <?php foreach ($items as $it): ?>
            <?php
            $ts = strtotime($it['event_date']);
            $isEnabled = (int)($it['enabled'] ?? 1) === 1;
            $status = $it['status'] ?? 'draft';
            $publishAt = isset($it['publish_at']) && $it['publish_at'] ? strtotime($it['publish_at']) : null;
            $now = time();
            $badge = 'Brouillon';
            $badgeColor = '#ffc107';
            if ($isEnabled && ($status === 'published' || ($publishAt && $publishAt <= $now))) {
                $badge = 'En ligne';
                $badgeColor = '#22c55e';
            } elseif ($publishAt && $publishAt > $now) {
                $badge = 'Programmé';
                $badgeColor = '#3b82f6';
            } elseif (!$isEnabled) {
                $badge = 'Désactivé';
                $badgeColor = '#ef4444';
            }
            ?>
            <div class="card">
                <div class="card-media" style="background-image:linear-gradient(140deg, rgba(0,85,255,.25), rgba(0,201,167,.2)), url('https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1200&q=60');">
                    <div class="badge" style="background:<?= $badgeColor ?>;border:none;"><?= $badge ?></div>
                    <div class="thumb"><img src="https://images.unsplash.com/photo-1522199710521-72d69614c702?auto=format&fit=crop&w=200&q=60" alt=""></div>
                </div>
                <div class="card-body">
                    <div class="card-title"><?= htmlspecialchars($it['title']) ?></div>
                    <div class="card-description"><?= htmlspecialchars(mb_strimwidth($it['description'] ?? '', 0, 120, '…')) ?></div>
                    <div style="margin:.4rem 0 .2rem; font-size:.75rem; color:#0b3b8f; font-weight:600;">
                        Inscriptions: <?= (int)($it['registrations_count'] ?? 0) ?>
                        <?php if (($it['registrations_count'] ?? 0) > 0): ?>
                            • <a href="<?= base_url('admin/events/registrations?event_id=' . (int)$it['id']) ?>" style="text-decoration:none;color:#2563eb;">Voir</a>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($it['capacity']) && (int)$it['capacity'] > 0): ?>
                        <?php $cap = (int)$it['capacity'];
                        $used = (int)($it['registrations_count'] ?? 0);
                        $pct = max(0, min(100, (int)round(($used / max($cap, 1)) * 100))); ?>
                        <div style="margin:.35rem 0 .5rem;">
                            <div style="display:flex;justify-content:space-between;font-size:.7rem;color:#94a3b8;margin-bottom:.25rem;">
                                <span>Occupation</span>
                                <span><?= $used ?>/<?= $cap ?> (<?= $pct ?>%)</span>
                            </div>
                            <div style="height:8px;background:#0b3b8f20;border:1px solid #0b3b8f30;border-radius:999px;overflow:hidden;">
                                <div style="width:<?= $pct ?>%;height:100%;background:<?= ($pct >= 100 ? '#ef4444' : ($pct >= 75 ? '#f59e0b' : '#22c55e')) ?>;"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="meta">
                        <span><i class="fa-regular fa-calendar"></i> <?= $ts ? date('d/m/Y', $ts) : '' ?></span>
                        <?php if (!empty($it['language'])): ?><span><i class="fa-solid fa-globe"></i> <?= htmlspecialchars($it['language']) ?></span><?php endif; ?>
                        <?php if (!empty($it['program'])): ?><span><i class="fa-solid fa-layer-group"></i> <?= htmlspecialchars($it['program']) ?></span><?php endif; ?>
                        <?php if (!empty($it['location'])): ?><span><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($it['location']) ?></span><?php endif; ?>
                    </div>
                    <div class="card-actions">
                        <div class="left">
                            <a class="btn" href="<?= base_url('admin/events/edit?id=' . (int)$it['id']) ?>"><i class="fa-regular fa-pen-to-square"></i> Modifier</a>
                            <a class="btn" href="<?= base_url('admin/events/toggle?id=' . (int)$it['id']) ?>" style="<?= $isEnabled ? '' : 'background:#fff3f3;border-color:#ffcaca;color:#7a0a0a' ?>">
                                <i class="fa-regular fa-power-off"></i> <?= $isEnabled ? 'Désactiver' : 'Activer' ?>
                            </a>
                        </div>
                        <a class="btn btn-danger" href="<?= base_url('admin/events/delete?id=' . (int)$it['id']) ?>" onclick="return confirm('Supprimer cet événement ?')"><i class="fa-regular fa-trash-can"></i> Supprimer</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($items)): ?>
            <div class="admin-card" style="text-align:center; opacity:.8;">
                Aucun événement. Créez le premier !
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>