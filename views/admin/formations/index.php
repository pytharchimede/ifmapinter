<?php include __DIR__ . '/../../partials/admin_header.php'; ?>

<div class="admin-hero">
    <h1>Formations IFMAP</h1>
    <div class="actions">
        <a class="btn" href="<?= base_url('admin/formations/create') ?>">➕ Ajouter une formation</a>
    </div>
</div>

<section class="admin-card" style="margin-bottom:1rem;">
    <h2>Paramètres de section</h2>
    <form method="post" action="<?= base_url('admin/formations/section/save') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <div class="field">
            <label for="section_title">Titre</label>
            <input id="section_title" name="title" value="<?= htmlspecialchars(($section['title'] ?? 'Formations IFMAP')) ?>">
        </div>
        <div class="field">
            <label for="section_subtitle">Sous-titre</label>
            <input id="section_subtitle" name="subtitle" value="<?= htmlspecialchars(($section['subtitle'] ?? 'Des formations professionnalisantes adaptées au marché africain.')) ?>">
        </div>
        <div class="actions">
            <button class="btn-admin" type="submit">💾 Enregistrer</button>
        </div>
    </form>
</section>

<section class="admin-card" style="margin-top:1rem;">
    <h2>Liste des formations</h2>
    <div class="cards-grid">
        <?php foreach (($items ?? []) as $it): ?>
            <div class="card">
                <div class="card-media" style="background-image:url('<?= htmlspecialchars($it['image_url'] ?? '') ?>');height:140px"></div>
                <div class="card-body">
                    <div class="card-title"><?= htmlspecialchars($it['name'] ?? 'Sans titre') ?></div>
                    <div class="card-description"><?= htmlspecialchars($it['description'] ?? '') ?></div>
                    <div class="meta">
                        <span><?= ($it['status'] ?? 'published') === 'draft' ? '📝 Brouillon' : '✅ Publié' ?></span>
                    </div>
                    <div class="card-actions">
                        <div class="left">
                            <a class="btn" href="<?= base_url('admin/formations/edit?id=' . (int)($it['id'] ?? 0)) ?>">✏️ Éditer</a>
                        </div>
                        <a class="btn btn-danger" href="<?= base_url('admin/formations/delete?id=' . (int)($it['id'] ?? 0)) ?>" onclick="return confirm('Supprimer cette formation ?');">🗑️ Supprimer</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($items)): ?>
            <div>Aucune formation pour l'instant.</div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>