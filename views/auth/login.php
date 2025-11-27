<?php include __DIR__ . '/../partials/header.php'; ?>

<section class="section">
    <div class="container" style="max-width:480px;">
        <h2>Connexion administrateur</h2>
        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post" action="<?= base_url('/login') ?>" class="card" style="padding:20px;">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="admin@ifmap.ci" />
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required placeholder="••••••••" />
            </div>
            <button class="btn-primary" type="submit">Se connecter</button>
        </form>
    </div>
    <div class="container" style="max-width:480px;margin-top:10px;">
        <p>Compte par défaut: <code>admin@ifmap.ci</code> / <code>admin123</code> (à modifier ensuite)</p>
    </div>

</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>