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

    <?php $isEdit = !empty($item) && isset($item['id']); ?>
    <form method="post" action="<?= base_url($isEdit ? 'admin/languages/edit' : 'admin/languages/create') ?>" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;background:var(--admin-surface);padding:1rem;border:1px solid var(--admin-border);border-radius:12px;">
        <?= csrf_field() ?>
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= (int)$item['id'] ?>" />
        <?php endif; ?>
        <div class="panel" style="grid-column:1 / -1; display:grid; grid-template-columns: 1fr 320px; gap:1rem; align-items:start;">
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:1rem;">
                <div class="field">
                    <label for="code">Code</label>
                    <input id="code" class="form-control" type="text" name="code" value="<?= htmlspecialchars($item['code'] ?? '') ?>" placeholder="fr, en, en-US" />
                </div>
                <div class="field">
                    <label for="name">Nom</label>
                    <input id="name" class="form-control" type="text" name="name" value="<?= htmlspecialchars($item['name'] ?? '') ?>" placeholder="Français, English" />
                </div>
                <div class="field">
                    <label for="flag">Drapeau (code)</label>
                    <input id="flag" class="form-control" type="text" name="flag" value="<?= htmlspecialchars($item['flag'] ?? '') ?>" placeholder="fr, gb, us..." />
                </div>
                <div class="field">
                    <label for="flag_url">URL du drapeau</label>
                    <input id="flag_url" class="form-control" type="url" name="flag_url" value="<?= htmlspecialchars($item['flag_url'] ?? '') ?>" placeholder="https://.../flag.png" />
                </div>
            </div>
            <div>
                <div class="image-preview" id="flagPreview" style="border-radius:10px; background:#0e1628; border:1px dashed #2a3b64; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                    <?php
                    $initialFlagUrl = '';
                    if (!empty($item['flag_url'])) {
                        $initialFlagUrl = $item['flag_url'];
                    } elseif (!empty($item['flag'])) {
                        $code = strtolower($item['flag']);
                        $initialFlagUrl = "https://flagcdn.com/w160/{$code}.png";
                    }
                    ?>
                    <?php if (!empty($initialFlagUrl)): ?>
                        <img src="<?= htmlspecialchars($initialFlagUrl) ?>" alt="Flag preview" style="width:100%;height:100%;object-fit:cover;" />
                    <?php else: ?>
                        <span style="color:#94a3b8; font-size:.9rem;">Aucune image à afficher</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div>
            <button class="btn-admin" type="submit"><?= $isEdit ? 'Mettre à jour' : 'Ajouter' ?></button>
        </div>
    </form>
    <script>
        (function() {
            const flagInput = document.getElementById('flag');
            const urlInput = document.getElementById('flag_url');
            const preview = document.getElementById('flagPreview');

            function setPreview(src) {
                if (!preview) return;
                preview.innerHTML = '';
                if (src) {
                    const img = document.createElement('img');
                    img.src = src;
                    img.alt = 'Flag preview';
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    img.onerror = function() {
                        preview.innerHTML = '<span style="color:#94a3b8;font-size:.9rem;">Impossible de charger l\'image</span>';
                    };
                    preview.appendChild(img);
                } else {
                    preview.innerHTML = '<span style="color:#94a3b8;font-size:.9rem;">Aucune image à afficher</span>';
                }
            }

            function updatePreview() {
                const direct = (urlInput && urlInput.value.trim()) || '';
                if (direct) {
                    setPreview(direct);
                    return;
                }
                const code = (flagInput && flagInput.value.trim().toLowerCase()) || '';
                if (code) {
                    setPreview('https://flagcdn.com/w160/' + code + '.png');
                    return;
                }
                setPreview('');
            }
            if (flagInput) flagInput.addEventListener('input', updatePreview);
            if (urlInput) urlInput.addEventListener('input', updatePreview);
        })();
    </script>
</div>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>