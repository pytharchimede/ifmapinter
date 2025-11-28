<?php
$title = $title ?? 'Admin – Centres';
$section = $section ?? ['title' => '', 'subtitle' => ''];
$items = $items ?? [];
?>
<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<div class="container">
    <h1><?= htmlspecialchars($title) ?></h1>
    <section class="wow-card">
        <h2>Paramètres de section</h2>
        <form method="post" action="<?= base_url('/admin/centres/section/save') ?>">
            <?= csrf_field() ?>
            <div class="admin-form">
                <div class="panel">
                    <label>Titre</label>
                    <input class="form-control" type="text" name="title" value="<?= htmlspecialchars($section['title'] ?? '') ?>" placeholder="Instituts & Centres IFMAP">
                    <label>Sous-titre</label>
                    <input class="form-control" type="text" name="subtitle" value="<?= htmlspecialchars($section['subtitle'] ?? '') ?>" placeholder="Découvrez nos pôles d'excellence et d'innovation.">
                    <div class="admin-actions">
                        <button class="btn btn-primary" type="submit">Enregistrer</button>
                    </div>
                </div>
                <div class="panel"></div>
            </div>
        </form>
    </section>

    <div class="actions-bar" style="margin:16px 0;">
        <a class="btn btn-success" href="<?= base_url('/admin/centres/create') ?>">Ajouter un centre</a>
    </div>

    <div class="cards-grid">
        <?php foreach ($items as $it): ?>
            <div class="card">
                <?php if (!empty($it['image_url'])): ?>
                    <img src="<?= htmlspecialchars($it['image_url']) ?>" alt="<?= htmlspecialchars($it['name']) ?>" style="width:100%;height:160px;object-fit:cover;">
                <?php endif; ?>
                <div class="card-body">
                    <div class="card-title" style="font-weight:bold;"><?= htmlspecialchars($it['name']) ?></div>
                    <?php if (!empty($it['excerpt'])): ?>
                        <div class="card-text"><?= htmlspecialchars($it['excerpt']) ?></div>
                    <?php endif; ?>
                    <div class="badge" style="display:inline-block;margin-top:8px;padding:4px 8px;border-radius:12px;background:#eee;">
                        <?= $it['status'] === 'draft' ? 'Brouillon' : 'Publié' ?>
                    </div>
                    <div class="card-actions" style="margin-top:12px;display:flex;gap:8px;">
                        <a class="btn btn-secondary" href="<?= base_url('/admin/centres/edit?id=' . $it['id']) ?>">Modifier</a>
                        <a class="btn btn-danger" href="<?= base_url('/admin/centres/delete?id=' . $it['id']) ?>" onclick="return confirm('Supprimer ce centre ?');">Supprimer</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>