<?php include __DIR__ . '/../partials/header.php'; ?>

<section class="section">
    <div class="container">
        <h2>Erreur serveur</h2>
        <?php if (!empty($message)): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php else: ?>
            <p>Une erreur inattendue s'est produite.</p>
        <?php endif; ?>
        <p><a class="btn-primary" href="<?= base_url('/') ?>">Retour à l’accueil</a></p>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>