<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<?php $isEdit = !empty($item); ?>
<div class="admin-main">
    <div class="admin-hero">
        <h1><?= htmlspecialchars($title) ?></h1>
    </div>

    <form method="post" action="<?= base_url($isEdit ? 'admin/events/edit' : 'admin/events/create') ?>" class="admin-form">
        <?= csrf_field() ?>
        <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= (int)$item['id'] ?>"><?php endif; ?>

        <div class="panel">
            <h3>Informations principales</h3>
            <div class="form-group">
                <label>Titre *</label>
                <input class="form-control" type="text" name="title" value="<?= htmlspecialchars($item['title'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" name="description" rows="8" placeholder="Détails de l'événement..."><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Date & heure *</label>
                    <?php
                    $dtRaw = $item['event_date'] ?? '';
                    $dtVal = '';
                    if ($dtRaw) {
                        $ts = strtotime($dtRaw);
                        if ($ts) $dtVal = date('Y-m-d\\TH:i', $ts);
                    }
                    ?>
                    <input class="form-control" type="datetime-local" name="event_date" value="<?= htmlspecialchars($dtVal) ?>" required>
                </div>
                <div class="form-group">
                    <label>Lieu</label>
                    <input class="form-control" type="text" name="location" value="<?= htmlspecialchars($item['location'] ?? '') ?>">
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Programme</label>
                    <input class="form-control" type="text" name="program" value="<?= htmlspecialchars($item['program'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Langue</label>
                    <input class="form-control" type="text" name="language" value="<?= htmlspecialchars($item['language'] ?? '') ?>">
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Catégorie</label>
                    <input class="form-control" type="text" name="category" value="<?= htmlspecialchars($item['category'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>URL d'inscription (CTA)</label>
                    <input class="form-control" type="url" name="cta_url" value="<?= htmlspecialchars($item['cta_url'] ?? '') ?>">
                </div>
            </div>
        </div>

        <div class="panel">
            <h3>Publication</h3>
            <div class="form-group">
                <label>Statut</label>
                <?php $status = $item['status'] ?? 'draft'; ?>
                <select class="form-control" name="status">
                    <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                    <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Publié</option>
                </select>
            </div>
            <div class="form-group">
                <label>Programmer la publication (optionnel)</label>
                <?php
                $pubRaw = $item['publish_at'] ?? '';
                $pubVal = '';
                if ($pubRaw) {
                    $ps = strtotime($pubRaw);
                    if ($ps) $pubVal = date('Y-m-d\\TH:i', $ps);
                }
                ?>
                <input class="form-control" type="datetime-local" name="publish_at" value="<?= htmlspecialchars($pubVal) ?>">
                <small style="color:#94a3b8;">Si renseigné et futur, l'événement basculera automatiquement en ligne à cette date.</small>
            </div>
            <div class="form-group">
                <?php $enabled = isset($item['enabled']) ? (int)$item['enabled'] : 1; ?>
                <label><input type="checkbox" name="enabled" value="1" <?= $enabled ? 'checked' : '' ?>> Activer l'événement</label>
            </div>

            <div class="admin-actions" style="margin-top:1rem;">
                <button class="btn-admin" type="submit"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
                <a class="btn" href="<?= base_url('admin/events') ?>">Annuler</a>
            </div>
        </div>
    </form>
</div>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>