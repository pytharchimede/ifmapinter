<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<h2><?= htmlspecialchars($title) ?></h2>
<form method="post" enctype="multipart/form-data" action="<?= empty($item) ? base_url('admin/media/create') : base_url('admin/media/edit') ?>" class="admin-card" style="margin-top:1rem;display:grid;gap:1rem;">
    <?= csrf_field() ?>
    <?php if (!empty($item['id'])): ?><input type="hidden" name="id" value="<?= (int)$item['id'] ?>"><?php endif; ?>
    <div>
        <label>Titre</label>
        <input style="width:100%;padding:.6rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb" name="title" value="<?= htmlspecialchars($item['title'] ?? '') ?>" required>
    </div>
    <div>
        <label>Type</label>
        <select name="type" style="width:100%;padding:.6rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
            <option value="image" <?= (isset($item['type']) && $item['type'] === 'image') ? 'selected' : '' ?>>Image</option>
            <option value="video" <?= (isset($item['type']) && $item['type'] === 'video') ? 'selected' : '' ?>>Vidéo (embed ou fichier)</option>
            <option value="video-file">Fichier MP4 upload</option>
        </select>
    </div>
    <div>
        <label>Catégorie</label>
        <input name="category" value="<?= htmlspecialchars($item['category'] ?? '') ?>" style="width:100%;padding:.6rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
    </div>
    <div>
        <label>Tags (séparés par des virgules)</label>
        <input name="tags" value="<?= htmlspecialchars($item['tags'] ?? '') ?>" style="width:100%;padding:.6rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
    </div>
    <div>
        <label>URL (embed vidéo ou image distante)</label>
        <input name="url" value="<?= htmlspecialchars($item['url'] ?? '') ?>" style="width:100%;padding:.6rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
    </div>
    <div>
        <label>Poster / vignette (vidéo)</label>
        <input name="poster_url" value="<?= htmlspecialchars($item['thumb_url'] ?? '') ?>" style="width:100%;padding:.6rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
    </div>
    <div>
        <label>Upload fichier (image ou mp4)</label>
        <input type="file" name="file" accept="image/*,video/mp4" style="color:#e5e7eb">
    </div>
    <div>
        <label>Description</label>
        <textarea name="description" rows="4" style="width:100%;padding:.6rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
    </div>
    <div style="display:flex;gap:.6rem;">
        <button class="btn-admin" type="submit">Enregistrer</button>
        <a class="btn-admin" href="<?= base_url('admin/media') ?>" style="background:#253042">Annuler</a>
    </div>
</form>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>