<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<div class="admin-main">
    <div class="admin-hero">
        <h1><?= htmlspecialchars($title) ?></h1>
        <div class="actions">
            <a class="btn" href="<?= base_url('admin/events/registrations') ?>">← Retour inscriptions</a>
        </div>
    </div>
    <div class="admin-card" style="max-width:640px;">
        <?php if (!empty($error)): ?>
            <div style="background:#dc262630;color:#dc2626;padding:.6rem .8rem;border:1px solid #dc262655;border-radius:8px;margin-bottom:.8rem;font-size:.8rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form method="post" action="" style="display:grid;gap:.9rem;">
            <?= csrf_field() ?>
            <div>
                <label class="form-label">Événement</label>
                <select name="event_id" class="form-control" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($events as $e): ?>
                        <option value="<?= (int)$e['id'] ?>">#<?= (int)$e['id'] ?> • <?= htmlspecialchars($e['title']) ?> (<?= htmlspecialchars(date('d/m', strtotime($e['event_date']))) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="display:flex;gap:.8rem;flex-wrap:wrap;">
                <div style="flex:1;min-width:160px;">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div style="flex:1;min-width:160px;">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
            </div>
            <div style="display:flex;gap:.8rem;flex-wrap:wrap;">
                <div style="flex:1;min-width:160px;">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div style="flex:1;min-width:160px;">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-control">
                        <option value="pending">En attente</option>
                        <option value="confirmed">Confirmée</option>
                        <option value="cancelled">Annulée</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="form-label">Message / Notes internes</label>
                <textarea name="message" class="form-control" rows="4" style="resize:vertical;"></textarea>
            </div>
            <div style="display:flex;align-items:center;gap:.5rem;">
                <input type="checkbox" id="consent" name="consent" value="1" style="width:16px;height:16px;">
                <label for="consent" style="font-size:.75rem;opacity:.8;">Consentement RGPD obtenu</label>
            </div>
            <button class="btn-admin" type="submit" style="margin-top:.4rem;">Enregistrer l'inscription</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>