<?php include __DIR__ . '/../../partials/header.php'; ?>
<section class="section">
    <div class="container" style="max-width:520px;">
        <h2>Changer le mot de passe</h2>
        <p><a href="<?= base_url('/admin') ?>">Retour</a></p>
        <?php if (!empty($error)): ?>
            <p style="color:#c00;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p style="color:#090;"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <form method="post" action="<?= base_url('/admin/password') ?>" class="card" style="padding:20px;">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Mot de passe actuel</label>
                <input type="password" name="current_password" required />
            </div>
            <div class="form-group">
                <label>Nouveau mot de passe</label>
                <input type="password" name="new_password" required minlength="6" />
            </div>
            <div class="form-group">
                <label>Confirmer nouveau mot de passe</label>
                <input type="password" name="confirm_password" required minlength="6" />
            </div>
            <button class="btn-primary" type="submit">Mettre à jour</button>
        </form>
    </div>
</section>
<?php include __DIR__ . '/../../partials/footer.php'; ?>