<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFMAP – Connexion</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="admin">
    <div class="login-wrap">
        <div class="login-hero">
            <div class="inner">
                <h1>Bienvenue sur IFMAP Admin</h1>
                <p>Gérez le site en toute simplicité dans une interface moderne et sécurisée.</p>
            </div>
        </div>
        <div class="login-form">
            <form method="post" action="<?= base_url('login') ?>" class="form-box">
                <h2>Connexion</h2>
                <?= csrf_field() ?>
                <?php if (!empty($error)): ?>
                    <p style="color:#fca5a5; margin:.3rem 0;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="admin@ifmap.ci" required>
                </div>
                <div class="field">
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <div class="actions">
                    <span class="help">Compte par défaut: admin@ifmap.ci / admin123</span>
                    <button type="submit" class="btn-admin">Se connecter</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>