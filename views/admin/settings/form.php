<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<div class="admin-main">
    <div class="admin-hero">
        <h1><?= htmlspecialchars($title) ?></h1>
    </div>
    <div class="admin-card" style="max-width:900px;">
        <form method="post" enctype="multipart/form-data" action="" style="display:grid;gap:1rem;">
            <?= csrf_field() ?>
            <div style="display:flex;gap:1rem;align-items:center;">
                <div>
                    <label class="form-label">Logo actuel</label>
                    <div style="width:140px;height:60px;border:1px dashed #3b4256;border-radius:8px;display:flex;align-items:center;justify-content:center;background:#0b1220;">
                        <?php if (!empty($row['logo_url'])): ?>
                            <img src="<?= htmlspecialchars($row['logo_url']) ?>" alt="Logo" style="max-width:100%;max-height:60px;">
                        <?php else: ?>
                            <span style="font-size:.75rem;opacity:.7;">Aucun</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div style="flex:1;">
                    <label class="form-label">Téléverser un logo</label>
                    <input type="file" name="logo_file" accept="image/*" class="form-control">
                    <input type="hidden" name="logo_url" value="<?= htmlspecialchars($row['logo_url'] ?? '') ?>">
                </div>
            </div>
            <hr style="border-color:#1f2940;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:.8rem;">
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="contact_email" class="form-control" value="<?= htmlspecialchars($row['contact_email'] ?? '') ?>">
                </div>
                <div>
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="contact_phone" class="form-control" value="<?= htmlspecialchars($row['contact_phone'] ?? '') ?>">
                </div>
                <div style="grid-column:1/-1;">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="contact_address" class="form-control" value="<?= htmlspecialchars($row['contact_address'] ?? '') ?>">
                </div>
            </div>
            <hr style="border-color:#1f2940;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:.8rem;">
                <div>
                    <label class="form-label">Lien Programmes</label>
                    <input type="text" name="link_programmes" class="form-control" value="<?= htmlspecialchars($row['link_programmes'] ?? '') ?>">
                </div>
                <div>
                    <label class="form-label">Lien Formations</label>
                    <input type="text" name="link_formations" class="form-control" value="<?= htmlspecialchars($row['link_formations'] ?? '') ?>">
                </div>
                <div>
                    <label class="form-label">Lien Actualités</label>
                    <input type="text" name="link_actualites" class="form-control" value="<?= htmlspecialchars($row['link_actualites'] ?? '') ?>">
                </div>
                <div>
                    <label class="form-label">Lien Partenaires</label>
                    <input type="text" name="link_partenaires" class="form-control" value="<?= htmlspecialchars($row['link_partenaires'] ?? '') ?>">
                </div>
            </div>
            <hr style="border-color:#1f2940;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:.8rem;">
                <div>
                    <label class="form-label">Facebook</label>
                    <input type="text" name="social_facebook" class="form-control" value="<?= htmlspecialchars($row['social_facebook'] ?? '') ?>">
                </div>
                <div>
                    <label class="form-label">LinkedIn</label>
                    <input type="text" name="social_linkedin" class="form-control" value="<?= htmlspecialchars($row['social_linkedin'] ?? '') ?>">
                </div>
                <div>
                    <label class="form-label">YouTube</label>
                    <input type="text" name="social_youtube" class="form-control" value="<?= htmlspecialchars($row['social_youtube'] ?? '') ?>">
                </div>
            </div>
            <div>
                <label class="form-label">Texte Newsletter</label>
                <textarea name="newsletter_text" class="form-control" rows="3"><?= htmlspecialchars($row['newsletter_text'] ?? '') ?></textarea>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:.8rem;">
                <div>
                    <label class="form-label">Lien Newsletter (URL)</label>
                    <input type="text" name="newsletter_url" class="form-control" value="<?= htmlspecialchars($row['newsletter_url'] ?? '') ?>">
                </div>
                <div>
                    <label class="form-label">Lien Plateforme (URL)</label>
                    <input type="text" name="platform_url" class="form-control" value="<?= htmlspecialchars($row['platform_url'] ?? '') ?>">
                </div>
            </div>
            <div style="display:flex;gap:.6rem;">
                <button class="btn-admin" type="submit">Enregistrer</button>
                <a class="btn" href="<?= base_url('admin') ?>">Annuler</a>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>