<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section" id="event-register">
    <div class="container" style="max-width:760px;">
        <div class="section-title">
            <h2><?= htmlspecialchars($event['title'] ?? 'Inscription Événement') ?></h2>
            <?php if (!empty($event['event_date'])): ?>
                <p style="font-size:.95rem; color:#555;">Date: <?= htmlspecialchars(date('d/m/Y H:i', strtotime($event['event_date']))) ?></p>
            <?php endif; ?>
        </div>
        <?php if (!empty($error)): ?><div class="alert-error" style="margin-bottom:12px;"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if (!empty($success)): ?><div class="alert-success" style="margin-bottom:12px;"><?= htmlspecialchars($success) ?></div><?php endif; ?>

        <form method="post" action="<?= base_url('evenements/inscription') ?>" class="reg-form" style="background:#fff;border:1px solid #e2e8f0;padding:20px;border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,.05);">
            <?= csrf_field() ?>
            <input type="hidden" name="event_id" value="<?= (int)($event['id'] ?? 0) ?>">
            <div class="form-row" style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div>
                    <label style="font-weight:600;">Nom *</label>
                    <input type="text" name="name" required class="form-control" style="width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:10px;">
                </div>
                <div>
                    <label style="font-weight:600;">Email</label>
                    <input type="email" name="email" class="form-control" style="width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:10px;">
                </div>
            </div>
            <div style="margin-top:14px;">
                <label style="font-weight:600;">Téléphone</label>
                <input type="text" name="phone" class="form-control" style="width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:10px;">
            </div>
            <div style="margin-top:14px;">
                <label style="font-weight:600;">Message</label>
                <textarea name="message" rows="4" class="form-control" style="width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:10px;resize:vertical;"></textarea>
            </div>
            <div style="margin-top:14px; display:flex; align-items:flex-start; gap:8px;">
                <input type="checkbox" id="consent" name="consent" value="1" style="margin-top:4px;">
                <label for="consent" style="font-size:.85rem; line-height:1.3; color:#475569;">J’accepte que mes données soient utilisées pour la gestion de mon inscription conformément à la politique de confidentialité.</label>
            </div>
            <div style="margin-top:18px;display:flex;align-items:center;gap:12px;">
                <button type="submit" class="btn-primary" style="padding:10px 18px;border-radius:10px;font-weight:600;">Envoyer</button>
                <a href="<?= base_url() ?>#evenements" class="btn-outline" style="padding:10px 18px;border-radius:10px;">Retour</a>
            </div>
            <p style="margin-top:10px;font-size:12px;color:#64748b;">Nous ne partageons pas vos informations avec des tiers sans consentement. Vous pouvez demander la suppression de vos données à tout moment.</p>
        </form>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>