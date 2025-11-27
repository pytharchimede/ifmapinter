<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<h2><?= htmlspecialchars($title) ?></h2>
<form method="post" enctype="multipart/form-data" action="<?= empty($item) ? base_url('admin/carousels/create') : base_url('admin/carousels/edit') ?>" class="admin-card" style="display:grid;gap:1rem;max-width:1000px">
    <?= csrf_field() ?>
    <?php if (!empty($item['id'])): ?><input type="hidden" name="id" value="<?= (int)$item['id'] ?>"><?php endif; ?>
    <div>
        <label>Ordre (#1 premier)</label>
        <input type="number" min="1" max="3" name="position" value="<?= (int)($item['position'] ?? 1) ?>" style="width:100%;padding:.6rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
    </div>
    <div>
        <label>Titre (≤120)</label>
        <input name="title" maxlength="120" value="<?= htmlspecialchars($item['title'] ?? '') ?>" style="width:100%;padding:.6rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
    </div>
    <div>
        <label>Caption (≤200)</label>
        <input name="caption" maxlength="200" value="<?= htmlspecialchars($item['caption'] ?? '') ?>" style="width:100%;padding:.6rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
    </div>
    <div>
        <label>Description (≤240)</label>
        <textarea name="description" maxlength="240" rows="3" style="width:100%;padding:.6rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
    </div>
    <div>
        <label>Texte du bouton (≤60)</label>
        <input name="button_text" maxlength="60" value="<?= htmlspecialchars($item['button_text'] ?? '') ?>" style="width:100%;padding:.6rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
    </div>
    <div>
        <label>Lien du bouton (URL)</label>
        <input name="button_url" value="<?= htmlspecialchars($item['button_url'] ?? '') ?>" style="width:100%;padding:.6rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
    </div>
    <div>
        <label>Image d'arrière-plan (URL)</label>
        <input name="background_url" value="<?= htmlspecialchars($item['background_url'] ?? '') ?>" required style="width:100%;padding:.6rem;border-radius:10px;border:1px solid var(--admin-border);background:#0c1220;color:#e5e7eb">
        <small style="color:#94a3b8">Formats recommandés: JPG/PNG/WebP, dimensions 1920x1080, &lt; 1 Mo.</small>
    </div>
    <div>
        <label>Ou upload local (JPG/PNG/WebP ≤ 2 Mo)</label>
        <input type="file" name="background_file" accept="image/*" style="color:#e5e7eb">
    </div>
    <div class="preview" style="display:grid;grid-template-columns:1.4fr 1fr;gap:1rem">
        <div class="hero-preview" style="position:relative;height:320px;border-radius:14px;overflow:hidden;background:#0b1220">
            <img id="bgPreview" src="<?= htmlspecialchars($item['background_url'] ?? '') ?>" alt="preview" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.9">
            <div class="overlay" style="position:absolute;inset:0;background:linear-gradient(0deg,rgba(0,0,0,.45),transparent 60%)"></div>
            <div class="caption" id="captionPreview" style="position:absolute;bottom:1rem;left:1rem;background:rgba(15,21,36,.7);color:#e5e7eb;padding:.6rem 1rem;border-radius:999px"><?= htmlspecialchars($item['caption'] ?? 'Caption overlay') ?></div>
            <div class="text" style="position:absolute;top:1.2rem;left:1.2rem;max-width:60%">
                <h3 id="titlePreview" style="margin:0 0 .3rem 0;font-size:1.6rem;color:#fff"><?= htmlspecialchars($item['title'] ?? 'Titre du carrousel') ?></h3>
                <p id="descPreview" style="margin:0;color:#cbd5e1;max-width:560px"><?= htmlspecialchars($item['description'] ?? 'Description courte (≤240)') ?></p>
                <a id="btnPreview" href="#" class="btn-admin" style="display:inline-block;margin-top:.6rem;background:#0d9488"><?= htmlspecialchars($item['button_text'] ?? 'Découvrir') ?></a>
            </div>
        </div>
        <div class="tips admin-card" style="background:#0f1524">
            <h3 style="margin-top:0">Guidelines</h3>
            <ul style="color:#94a3b8">
                <li>Image 1920x1080, compression WebP conseillée.</li>
                <li>Titre ≤ 120, Caption ≤ 200, Description ≤ 240, Bouton ≤ 60.</li>
                <li>Contraste du texte avec overlay pour lisibilité.</li>
            </ul>
        </div>
    </div>
    <div style="display:flex;gap:.6rem">
        <button class="btn-admin" type="submit">Enregistrer</button>
        <a class="btn-admin" href="<?= base_url('admin/carousels') ?>" style="background:#253042">Annuler</a>
    </div>
</form>
<script>
    const bind = (sel, cb) => {
        const el = document.querySelector(sel);
        if (el) el.addEventListener('input', cb);
    };
    bind('input[name=background_url]', e => {
        const v = e.target.value;
        const img = document.getElementById('bgPreview');
        if (img) img.src = v;
    });
    bind('input[name=caption]', e => {
        document.getElementById('captionPreview').textContent = e.target.value;
    });
    bind('input[name=title]', e => {
        document.getElementById('titlePreview').textContent = e.target.value;
    });
    bind('textarea[name=description]', e => {
        document.getElementById('descPreview').textContent = e.target.value;
    });
    bind('input[name=button_text]', e => {
        document.getElementById('btnPreview').textContent = e.target.value;
    });
</script>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>