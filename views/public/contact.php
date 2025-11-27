<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section" id="contact-page">
    <div class="container">
        <div class="section-title">
            <h2>Contact</h2>
            <p>Écrivez-nous ou discutez instantanément.</p>
        </div>

        <?php if (!empty($success)): ?>
            <div class="notice" style="margin:1rem 0;padding:.8rem 1rem;border-radius:var(--radius-sm);background:#e7f5e9;color:#1f7a3b;"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;align-items:start;">
            <form method="post" action="<?= base_url('contact') ?>" class="card" style="background:var(--color-surface);padding:1rem;border-radius:var(--radius-md);box-shadow:var(--shadow-sm)">
                <?= csrf_field() ?>
                <div style="margin-bottom:.8rem">
                    <label>Nom *</label>
                    <input name="name" required style="width:100%;padding:.6rem;border-radius:var(--radius-sm);border:1px solid var(--color-border);background:var(--color-bg);color:var(--color-text)">
                </div>
                <div style="margin-bottom:.8rem">
                    <label>Email</label>
                    <input name="email" type="email" style="width:100%;padding:.6rem;border-radius:var(--radius-sm);border:1px solid var(--color-border);background:var(--color-bg);color:var(--color-text)">
                </div>
                <div style="margin-bottom:.8rem">
                    <label>Téléphone</label>
                    <input name="phone" style="width:100%;padding:.6rem;border-radius:var(--radius-sm);border:1px solid var(--color-border);background:var(--color-bg);color:var(--color-text)">
                </div>
                <div style="margin-bottom:.8rem">
                    <label>Message *</label>
                    <textarea name="message" rows="5" required style="width:100%;padding:.6rem;border-radius:var(--radius-sm);border:1px solid var(--color-border);background:var(--color-bg);color:var(--color-text)"></textarea>
                </div>
                <button class="btn" type="submit" style="padding:.6rem 1rem;background:var(--color-primary);color:#fff;border:none;border-radius:var(--radius-sm)">Envoyer</button>
            </form>

            <div>
                <div class="card" style="background:var(--color-surface);padding:1rem;border-radius:var(--radius-md);box-shadow:var(--shadow-sm)">
                    <h3>Discussion instantanée</h3>
                    <p>Un widget de chat réel peut être intégré (Tawk.to/Crisp). Renseignez votre identifiant ci-dessous.</p>
                    <div style="margin:.6rem 0">
                        <!-- Tawk.to widget activé -->
                        <script type="text/javascript">
                            var Tawk_API = Tawk_API || {},
                                Tawk_LoadStart = new Date();
                            (function() {
                                var s1 = document.createElement("script"),
                                    s0 = document.getElementsByTagName("script")[0];
                                s1.async = true;
                                s1.src = 'https://embed.tawk.to/6928cda30d02891959544218/1jb3m6i8h';
                                s1.charset = 'UTF-8';
                                s1.setAttribute('crossorigin', '*');
                                s0.parentNode.insertBefore(s1, s0);
                            })();
                        </script>
                        <!-- Crisp chat (optionnel) -->
                        <!--
                        <script type="text/javascript">
                        window.$crisp=[];window.CRISP_WEBSITE_ID="YOUR_WEBSITE_ID";
                        (function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();
                        </script>
                        -->
                    </div>
                    <p style="margin-top:.6rem">
                        <a class="btn" href="https://wa.me/2250700000000" target="_blank" style="display:inline-block;padding:.5rem 1rem;background:#25D366;color:#fff;border-radius:var(--radius-sm);text-decoration:none;">
                            <i class="fa-brands fa-whatsapp"></i> WhatsApp
                        </a>
                        <a class="btn" href="mailto:contact@ifmap.ci" style="display:inline-block;padding:.5rem 1rem;background:#253042;color:#fff;border-radius:var(--radius-sm);text-decoration:none;margin-left:.5rem;">
                            <i class="fa-solid fa-envelope"></i> Email
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>