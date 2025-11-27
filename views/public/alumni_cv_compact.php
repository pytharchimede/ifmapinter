<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section" id="alumni-cv-compact">
    <div class="container">
        <div class="section-title">
            <h2>CV Alumni – Compact A4</h2>
            <p>Version strict 1 page A4 avec palette bicolore (teal + bleu IFMAP).</p>
        </div>

        <article class="card" style="background:#fff;color:#111;padding:2cm;border-radius:var(--radius-md);box-shadow:var(--shadow-sm);max-width:900px;margin:0 auto;font-family:'Poppins', system-ui, -apple-system, Arial">
            <header style="border-bottom:2px solid #0d9488;padding-bottom:.8rem;margin-bottom:1rem;display:flex;align-items:center;gap:1rem">
                <img src="https://ifmap.ci/uploads/system/1fb9ea08a27e58c71dc6e639284b74eb.png" alt="IFMAP" style="height:40px">
                <div>
                    <h1 style="margin:0;color:#0d9488">Nom Prénom</h1>
                    <p style="margin:.2rem 0;color:#1e3a8a">Email • Téléphone • Ville, Pays • LinkedIn/GitHub</p>
                </div>
            </header>
            <section>
                <h3 style="color:#0d9488;margin:0 0 .3rem 0">Profil</h3>
                <p style="line-height:1.45;margin:0 0 .6rem 0">Résumé en 2-3 lignes, impact, missions clés.</p>
            </section>
            <section style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div>
                    <h3 style="color:#0d9488;margin:0 0 .3rem 0">Compétences</h3>
                    <ul style="margin:.2rem 0 .6rem 1.2rem;list-style:disc;line-height:1.45">
                        <li>Compétence 1</li>
                        <li>Compétence 2</li>
                        <li>Compétence 3</li>
                    </ul>
                    <h3 style="color:#0d9488;margin:.6rem 0 .3rem 0">Formations</h3>
                    <p style="line-height:1.45;margin:0 0 .4rem 0"><strong>IFMAP</strong> — Programme/Formation (Diplôme, Années)</p>
                </div>
                <div>
                    <h3 style="color:#0d9488;margin:0 0 .3rem 0">Expériences</h3>
                    <p style="line-height:1.45;margin:0 0 .2rem 0"><strong>Entreprise</strong> — Poste (AAAA–AAAA)</p>
                    <ul style="margin:.2rem 0 .6rem 1.2rem;list-style:disc;line-height:1.45">
                        <li>Réalisations mesurables et responsabilités majeures.</li>
                    </ul>
                    <h3 style="color:#0d9488;margin:.6rem 0 .3rem 0">Projets</h3>
                    <ul style="margin:.2rem 0 .6rem 1.2rem;list-style:disc;line-height:1.45">
                        <li>Projet notable (lien, résultat).</li>
                    </ul>
                </div>
            </section>
            <section>
                <h3 style="color:#0d9488;margin:.6rem 0 .3rem 0">Sections facultatives</h3>
                <ul style="margin:.2rem 0 .6rem 1.2rem;list-style:disc;line-height:1.45">
                    <li>Certifications</li>
                    <li>Bénévolat / Associations</li>
                    <li>Langues</li>
                </ul>
            </section>
            <style>
                @media print {
                    body {
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                    }

                    .card {
                        box-shadow: none !important;
                    }

                    @page {
                        size: A4;
                        margin: 2cm;
                    }
                }
            </style>
        </article>
        <p style="margin-top:1rem">
            <a class="btn" href="#" onclick="window.print();return false;" style="display:inline-block;padding:.6rem 1rem;background:var(--color-primary);color:#fff;border-radius:var(--radius-sm);text-decoration:none;">Imprimer / PDF</a>
            <a class="btn" href="<?= base_url('alumni') ?>" style="display:inline-block;padding:.6rem 1rem;background:#253042;color:#fff;border-radius:var(--radius-sm);text-decoration:none;margin-left:.5rem;">Retour Alumni</a>
        </p>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>