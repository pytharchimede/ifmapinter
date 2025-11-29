<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<div class="admin-content">
    <h1 style="margin-top:0;display:flex;align-items:center;justify-content:space-between;gap:1rem;">
        <span><?= htmlspecialchars($title) ?></span>
    </h1>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin/languages/create') ?>" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.6rem;margin:0 0 1rem 0;align-items:end;background:var(--admin-surface);padding:1rem;border:1px solid var(--admin-border);border-radius:12px;">
        <?= csrf_field() ?>
        <div class="field">
            <label for="code">Code</label>
            <input id="code" class="form-control" type="text" name="code" placeholder="fr, en, en-US" />
        </div>
        <div class="field">
            <label for="name">Nom</label>
            <input id="name" class="form-control" type="text" name="name" placeholder="Français, English" />
        </div>
        <div class="field">
            <label for="flag">Drapeau</label>
            <input id="flag" class="form-control" type="text" name="flag" placeholder="fr, gb, us..." />
        </div>
        <div class="field">
            <label for="flag_url">URL du drapeau</label>
            <input id="flag_url" class="form-control" type="url" name="flag_url" placeholder="https://.../flag.png (optionnel)" />
        </div>
        <div>
            <button class="btn-admin" type="submit">Ajouter</button>
        </div>
    </form>

    <div class="panel" style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:14px;padding:1rem;">
        <h2 style="display:flex;align-items:center;gap:.6rem;">Langues disponibles <small style="color:var(--admin-muted);font-weight:400;">(cartes avec drapeau)</small></h2>
        <div class="cards" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">
            <?php foreach (($rows ?? []) as $r): ?>
                <?php $flagCode = strtolower($r['flag'] ?: $r['code']); ?>
                <div class="card" style="border:1px solid var(--admin-border);border-radius:12px;padding:0.8rem;background:var(--admin-bg);display:flex;flex-direction:column;gap:.6rem;">
                    <div style="display:flex;align-items:center;gap:.6rem;">
                        <?php if (!empty($r['flag_url'])): ?>
                            <img src="<?= htmlspecialchars($r['flag_url']) ?>" alt="<?= htmlspecialchars($r['code']) ?>" style="width:36px;height:auto;border-radius:4px;box-shadow:0 2px 6px rgba(0,0,0,.15);object-fit:cover;" />
                        <?php else: ?>
                            <img src="https://flagcdn.com/96x72/<?= htmlspecialchars($flagCode) ?>.png" alt="<?= htmlspecialchars($r['code']) ?>" style="width:36px;height:auto;border-radius:4px;box-shadow:0 2px 6px rgba(0,0,0,.15);" />
                        <?php endif; ?>
                        <div>
                            <div style="font-weight:600;"><?= htmlspecialchars($r['name']) ?></div>
                            <div style="color:var(--admin-muted);font-size:.9em;"><?= htmlspecialchars(strtoupper($r['code'])) ?></div>
                        </div>
                        <?php if (!empty($r['is_default'])): ?>
                            <span style="margin-left:auto;background:#2563eb;color:#fff;padding:.2rem .5rem;border-radius:999px;font-size:.75em;">Par défaut</span>
                        <?php endif; ?>
                    </div>
                    <div style="display:flex;align-items:center;gap:.5rem;">
                        <span style="background:<?= ((int)$r['enabled']) ? '#10b981' : '#ef4444' ?>;color:#fff;padding:.15rem .5rem;border-radius:999px;font-size:.75em;">
                            <?= ((int)$r['enabled']) ? 'Activée' : 'Désactivée' ?>
                        </span>
                    </div>
                    <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                        <a class="btn" href="<?= base_url('admin/languages/toggle?id=' . (int)$r['id']) ?>">Basculer</a>
                        <a class="btn" href="<?= base_url('admin/languages/default?id=' . (int)$r['id']) ?>">Définir par défaut</a>
                        <a class="btn" href="<?= base_url('admin/languages/edit?id=' . (int)$r['id']) ?>">Modifier</a>
                        <a class="btn" href="<?= base_url('admin/translations?lang=' . urlencode($r['code'])) ?>">Gérer traductions</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>