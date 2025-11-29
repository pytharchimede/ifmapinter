<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="page-hero dark" id="contact">
    <div class="inner">
        <span class="badge-soft">IFMAP</span>
        <h1><?= htmlspecialchars(t('page.contact.title.full')) ?></h1>
        <p class="lead"><?= htmlspecialchars(t('page.contact.hero.lead') ?? 'Échange rapide, accompagnement personnalisé et informations pratiques.') ?></p>
        <div class="actions">
            <a href="#formulaire" class="btn-outline">Formulaire</a>
            <a href="#canaux" class="btn-outline">Canaux</a>
            <a href="#faq" class="btn-outline">FAQ</a>
        </div>
    </div>
</div>
<section class="section section-tight" id="formulaire">
    <div class="container">
        <?php if (!empty($success)): ?>
            <div style="margin:0 0 1.2rem;padding:.8rem 1rem;border-radius:var(--radius-sm);background:#e7f5e9;color:#1f7a3b;box-shadow:var(--shadow-sm);font-size:.9rem;">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        <div class="grid-2" style="gap:2.4rem;align-items:start;">
            <form method="post" action="<?= base_url('contact') ?>" class="card-elevated" style="padding:1.6rem;position:relative;z-index:2;">
                <?= csrf_field() ?>
                <h2 style="margin-top:0;font-size:1.3rem;"><?= htmlspecialchars(t('page.contact.form.title') ?? "Écrire à l'équipe") ?></h2>
                <p style="margin:-.3rem 0 1.2rem;font-size:.85rem;opacity:.75;"><?= htmlspecialchars(t('page.contact.form.subtitle') ?? 'Réponse sous 24h ouvrées.') ?></p>
                <div class="grid-2" style="gap:1rem;">
                    <div>
                        <label style="font-size:.75rem;font-weight:600;letter-spacing:.5px;"><?= htmlspecialchars(t('page.contact.form.name') ?? 'Nom *') ?></label>
                        <input name="name" required class="form-input" autocomplete="name">
                    </div>
                    <div>
                        <label style="font-size:.75rem;font-weight:600;letter-spacing:.5px;"><?= htmlspecialchars(t('page.contact.form.email') ?? 'Email') ?></label>
                        <input name="email" type="email" class="form-input" autocomplete="email">
                    </div>
                </div>
                <div style="margin-top:1rem;">
                    <label style="font-size:.75rem;font-weight:600;letter-spacing:.5px;"><?= htmlspecialchars(t('page.contact.form.phone') ?? 'Téléphone') ?></label>
                    <input name="phone" class="form-input" autocomplete="tel">
                </div>
                <div style="margin-top:1rem;">
                    <label style="font-size:.75rem;font-weight:600;letter-spacing:.5px;"><?= htmlspecialchars(t('page.contact.form.subject') ?? 'Objet') ?></label>
                    <input name="subject" class="form-input" placeholder="Demande d'information" autocomplete="off">
                </div>
                <div style="margin-top:1rem;">
                    <label style="font-size:.75rem;font-weight:600;letter-spacing:.5px;"><?= htmlspecialchars(t('page.contact.form.message') ?? 'Message *') ?></label>
                    <textarea name="message" rows="6" required class="form-textarea" style="resize:vertical;"></textarea>
                </div>
                <div style="display:flex;align-items:center;gap:.8rem;margin-top:1.2rem;flex-wrap:wrap;">
                    <button class="btn-primary" type="submit">Envoyer</button>
                    <small style="font-size:.7rem;opacity:.6;">En soumettant vous acceptez notre politique de confidentialité.</small>
                </div>
            </form>
            <aside style="display:flex;flex-direction:column;gap:1.4rem;position:relative;z-index:1;">
                <div class="card-elevated" id="canaux" style="padding:1.2rem;">
                    <h3 style="margin-top:0;font-size:1.05rem;"><?= htmlspecialchars(t('page.contact.channels.title') ?? 'Canaux Directs') ?></h3>
                    <p style="font-size:.85rem;opacity:.75;"><?= htmlspecialchars(t('page.contact.channels.subtitle') ?? 'Choisissez le canal le plus adapté à votre demande.') ?></p>
                    <div class="contact-channels-grid">
                        <a href="https://wa.me/2250700000000" target="_blank" rel="noopener" class="channel-card" aria-label="WhatsApp conseiller">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#25D366" aria-hidden="true">
                                <path d="M12.04 2c-5.52 0-10 4.48-10 10 0 1.77.47 3.43 1.29 4.86L2 22l5.26-1.38A9.93 9.93 0 0012.04 22c5.52 0 10-4.48 10-10s-4.48-10-10-10zm0 18.2c-1.7 0-3.36-.5-4.78-1.45l-.34-.22-3.12.82.83-3.05-.22-.32A8.18 8.18 0 013.84 12c0-4.52 3.68-8.2 8.2-8.2 4.52 0 8.2 3.68 8.2 8.2s-3.68 8.2-8.2 8.2zm4.52-5.98c-.25-.13-1.47-.72-1.7-.8-.23-.08-.4-.13-.57.13-.17.25-.65.8-.8.97-.15.17-.3.19-.55.06-.25-.13-1.05-.39-2-1.24-.74-.66-1.24-1.47-1.39-1.72-.15-.25-.02-.38.11-.5.12-.12.25-.31.37-.46.13-.15.17-.25.25-.42.08-.17.04-.32-.02-.45-.06-.13-.57-1.37-.78-1.88-.21-.5-.42-.43-.57-.43-.15 0-.32-.02-.49-.02-.17 0-.45.06-.68.32-.23.25-.9.88-.9 2.14 0 1.26.92 2.48 1.05 2.65.13.17 1.81 2.76 4.4 3.87.62.27 1.11.43 1.49.55.62.2 1.18.17 1.62.1.49-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.16-.49-.29z" />
                            </svg>
                            <strong>WhatsApp</strong>
                            <small>Conseiller</small>
                        </a>
                        <a href="mailto:contact@ifmap.ci" class="channel-card" aria-label="Email support">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0055ff" aria-hidden="true">
                                <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4.99l-8 5-8-5V6l8 5 8-5v2.99z" />
                            </svg>
                            <strong>Email</strong>
                            <small>Support</small>
                        </a>
                    </div>
                    <div style="margin-top:1rem;">
                        <div style="display:flex;gap:.6rem;align-items:center;flex-wrap:wrap;">
                            <span class="badge-soft">Tawk.to Live</span>
                            <span class="badge-soft">RDV Présentiel</span>
                            <span class="badge-soft">Orientation</span>
                        </div>
                    </div>
                </div>
                <div class="card-elevated" style="padding:1.2rem;">
                    <h3 style="margin-top:0;font-size:1.05rem;"><?= htmlspecialchars(t('page.contact.location.title') ?? 'Localisation') ?></h3>
                    <p style="font-size:.8rem;line-height:1.4;">IFMAP – Niangon Lubafrique, Yopougon<br>Abidjan, Côte d'Ivoire<br><?= htmlspecialchars(t('page.contact.location.hours') ?? 'Ouvert Lun–Sam : 08h30–18h00') ?></p>
                    <div style="height:220px;border-radius:12px;overflow:hidden;position:relative;background:#eef2f7;">
                        <iframe title="Carte IFMAP" width="100%" height="100%" style="border:0;" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=Niangon+Lubafrique+Yopougon+Abidjan+Cote+d'Ivoire&output=embed"></iframe>
                    </div>
                </div>
                <div class="card-elevated" id="faq" style="padding:1.2rem;">
                    <h3 style="margin-top:0;font-size:1.05rem;"><?= htmlspecialchars(t('page.contact.faq.title') ?? 'FAQ Express') ?></h3>
                    <details style="margin-bottom:.6rem;">
                        <summary style="cursor:pointer;font-size:.85rem;font-weight:600;"><?= htmlspecialchars(t('page.contact.faq.q1') ?? "Comment s'inscrire à un programme ?") ?></summary>
                        <p style="font-size:.75rem;opacity:.8;margin:.4rem 0 0;"><?= htmlspecialchars(t('page.contact.faq.a1') ?? "Choisissez un programme puis remplissez le formulaire d'inscription. Un conseiller vous recontacte.") ?></p>
                    </details>
                    <details style="margin-bottom:.6rem;">
                        <summary style="cursor:pointer;font-size:.85rem;font-weight:600;"><?= htmlspecialchars(t('page.contact.faq.q2') ?? 'Les formations sont-elles certifiantes ?') ?></summary>
                        <p style="font-size:.75rem;opacity:.8;margin:.4rem 0 0;"><?= htmlspecialchars(t('page.contact.faq.a2') ?? "Certaines aboutissent à des certifications; d'autres à des attestations professionnelles.") ?></p>
                    </details>
                    <details>
                        <summary style="cursor:pointer;font-size:.85rem;font-weight:600;"><?= htmlspecialchars(t('page.contact.faq.q3') ?? 'Peut-on devenir partenaire ?') ?></summary>
                        <p style="font-size:.75rem;opacity:.8;margin:.4rem 0 0;"><?= htmlspecialchars(t('page.contact.faq.a3') ?? "Oui : stages, co‑construction de contenus, projets techniques, conférences métiers.") ?></p>
                    </details>
                </div>
            </aside>
        </div>
    </div>
</section>
<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="cta-banner">
            <h3><?= htmlspecialchars(t('page.contact.cta.title') ?? "Besoin d'une présentation détaillée ?") ?></h3>
            <p><?= htmlspecialchars(t('page.contact.cta.subtitle') ?? 'Organisons une session découverte (visio ou présentiel) avec nos équipes pédagogiques.') ?></p>
            <div class="actions">
                <a class="btn-outline" href="<?= base_url('institut') ?>"><?= htmlspecialchars(t('page.contact.cta.btn.institut') ?? "Découvrir l'Institut") ?></a>
                <a class="btn-outline" href="<?= base_url('programmes') ?>"><?= htmlspecialchars(t('page.contact.cta.btn.programmes') ?? 'Programmes') ?></a>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
<script>
    // Validation front simple
    (function() {
        const form = document.querySelector('#formulaire form');
        if (!form) return;
        const fields = ['name', 'message'];
        const emailF = form.querySelector('input[name=email]');
        const subjectF = form.querySelector('input[name=subject]');
        const msgF = form.querySelector('textarea[name=message]');
        const errBox = document.createElement('div');
        errBox.style.cssText = 'margin-top:1rem;font-size:.75rem;color:#a8071a;background:#fff1f0;border:1px solid #ffa39e;padding:.5rem .7rem;border-radius:6px;display:none';
        form.appendChild(errBox);
        form.addEventListener('submit', e => {
            let errors = [];
            const name = form.querySelector('input[name=name]').value.trim();
            if (!name) errors.push('Nom requis');
            const msg = msgF.value.trim();
            if (msg.length < 10) errors.push('Message trop court (≥10 caractères)');
            if (emailF.value.trim() && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(emailF.value.trim())) errors.push('Email invalide');
            if (subjectF.value.length > 0 && subjectF.value.length < 3) errors.push('Objet trop court');
            if (errors.length) {
                e.preventDefault();
                errBox.innerHTML = errors.map(x => '<div>' + x + '</div>').join('');
                errBox.style.display = 'block';
                return;
            }
        });
    })();
</script>