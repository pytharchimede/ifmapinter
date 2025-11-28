<?php include __DIR__ . '/../../partials/admin_header.php'; ?>

<section class="section">
    <div class="container" style="max-width:720px;">
        <h2><?= htmlspecialchars($title) ?></h2>
        <p><a href="<?= base_url('/admin/partners') ?>">Retour</a></p>
        <form method="post" enctype="multipart/form-data" action="<?= base_url($item ? '/admin/partners/edit' : '/admin/partners/create') ?>" class="card" style="padding:0; overflow:hidden;">
            <?= csrf_field() ?>
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= (int)$item['id'] ?>" />
            <?php endif; ?>
            <style>
                .form-shell {
                    display: grid;
                    grid-template-columns: 1fr 320px;
                    gap: 0;
                }

                .form-body {
                    padding: 20px;
                }

                .form-side {
                    background: #f9fafb;
                    border-left: 1px solid #eee;
                    padding: 20px;
                }

                .form-group {
                    margin-bottom: 16px;
                }

                .form-group label {
                    display: block;
                    font-weight: 600;
                    margin-bottom: 6px;
                }

                .dropzone {
                    border: 2px dashed #cbd5e1;
                    border-radius: 12px;
                    padding: 16px;
                    text-align: center;
                    background: #fff;
                    cursor: pointer;
                }

                .dropzone.drag {
                    background: #f0f7ff;
                    border-color: #93c5fd;
                }

                .preview {
                    margin-top: 12px;
                    display: flex;
                    justify-content: center;
                }

                .preview img {
                    max-width: 100%;
                    max-height: 160px;
                    border-radius: 8px;
                    border: 1px solid #eee;
                    background: #fff;
                }

                .hstack {
                    display: flex;
                    gap: 12px;
                    align-items: center;
                }

                .btn-primary {
                    background: #1677ff;
                    color: #fff;
                    border: 1px solid #1677ff;
                    padding: 10px 16px;
                    border-radius: 8px;
                }

                .switch {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
            </style>
            <div class="form-shell">
                <div class="form-body">
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="name" required value="<?= htmlspecialchars($item['name'] ?? '') ?>" />
                    </div>
                    <div class="form-group">
                        <label>Logo URL (optionnel)</label>
                        <input type="url" name="logo_url" value="<?= htmlspecialchars($item['logo_url'] ?? '') ?>" />
                    </div>
                    <div class="form-group switch">
                        <?php $enabled = (int)($item['enabled'] ?? 1) === 1; ?>
                        <input type="checkbox" id="enabled" name="enabled" value="1" <?= $enabled ? 'checked' : '' ?> />
                        <label for="enabled">Actif</label>
                    </div>
                    <div class="hstack">
                        <button class="btn-primary" type="submit">Enregistrer</button>
                        <a href="<?= base_url('/admin/partners') ?>">Annuler</a>
                    </div>
                </div>
                <div class="form-side">
                    <label>Logo – Drag & Drop</label>
                    <div id="dropzone" class="dropzone">
                        <p>Déposez une image ici ou cliquez pour choisir</p>
                        <input id="file" type="file" name="logo_file" accept="image/*" style="display:none;" />
                    </div>
                    <div class="preview" id="preview">
                        <?php if (!empty($item['logo_url'])): ?>
                            <img src="<?= htmlspecialchars($item['logo_url']) ?>" alt="Logo" />
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <script>
                (function() {
                    const dz = document.getElementById('dropzone');
                    const fi = document.getElementById('file');
                    const pv = document.getElementById('preview');
                    const updatePreview = file => {
                        const reader = new FileReader();
                        reader.onload = e => {
                            pv.innerHTML = '<img src="' + e.target.result + '" alt="Preview" />';
                        };
                        reader.readAsDataURL(file);
                    };
                    dz.addEventListener('click', () => fi.click());
                    dz.addEventListener('dragover', e => {
                        e.preventDefault();
                        dz.classList.add('drag');
                    });
                    dz.addEventListener('dragleave', e => {
                        dz.classList.remove('drag');
                    });
                    dz.addEventListener('drop', e => {
                        e.preventDefault();
                        dz.classList.remove('drag');
                        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                            fi.files = e.dataTransfer.files;
                            updatePreview(fi.files[0]);
                        }
                    });
                    fi.addEventListener('change', e => {
                        if (fi.files[0]) updatePreview(fi.files[0]);
                    });
                })();
            </script>
        </form>
    </div>
</section>

<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>