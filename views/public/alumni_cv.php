<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section" id="alumni-cv">
    <div class="container">
        <div class="section-title">
            <h2>Modèle de CV Alumni</h2>
            <p>Personnalisez ce modèle (palette IFMAP, interlignage 1.5, sections facultatives).</p>
        </div>

        <article class="card" style="background:#fff;color:#111;padding:2.2cm;border-radius:var(--radius-md);box-shadow:var(--shadow-sm);max-width:900px;margin:0 auto;font-family:'Poppins', system-ui, -apple-system, Arial">
            <header style="border-bottom:1px solid #ddd;padding-bottom:1rem;margin-bottom:1rem">
                <div style="display:flex;align-items:center;gap:1rem;">
                    <img src="https://ifmap.ci/uploads/system/1fb9ea08a27e58c71dc6e639284b74eb.png" alt="IFMAP" style="height:48px">
                    <div>
                        <h1 style="margin:0;color:#0d9488">Nom Prénom</h1>
                        <p>Email • Téléphone • Ville, Pays • LinkedIn/GitHub</p>
                    </div>
                </div>
            </header>
            <section>
                <h3 style="color:#0d9488">Profil</h3>
                <p style="line-height:1.5">Résumé professionnel en 3-4 lignes mettant en avant les compétences clés et réalisations.</p>
            </section>
            <section>
                <h3 style="color:#0d9488">Compétences</h3>
                <ul style="margin:.4rem 0 .8rem 1.2rem;list-style:disc;line-height:1.5">
                    <li>Compétence 1</li>
                    <li>Compétence 2</li>
                    <li>Compétence 3</li>
                </ul>
            </section>
            <section>
                <h3 style="color:#0d9488">Expériences</h3>
                <p style="line-height:1.5"><strong>Entreprise</strong> — Poste (AAAA–AAAA)</p>
                <ul style="margin:.4rem 0 .8rem 1.2rem;list-style:disc;line-height:1.5">
                    <li>Réalisations, métriques et responsabilités majeures.</li>
                </ul>
            </section>
            <section>
                <h3 style="color:#0d9488">Formations</h3>
                <p style="line-height:1.5"><strong>IFMAP</strong> — Programme/Formation (Diplôme, Années)</p>
            </section>
            <section>
                <h3 style="color:#0d9488">Projets</h3>
                <ul style="margin:.4rem 0 .8rem 1.2rem;list-style:disc;line-height:1.5">
                    <li>Projet notable avec lien et résultat.</li>
                </ul>
            </section>
            <section>
                <h3 style="color:#0d9488">Sections facultatives</h3>
                <ul style="margin:.4rem 0 .8rem 1.2rem;list-style:disc;line-height:1.5">
                    <li>Certifications</li>
                    <li>Bénévolat / Associations</li>
                    <li>Langues</li>
                    <li>Centres d’intérêt</li>
                </ul>
            </section>
        </article>

        <p style="margin-top:1rem">
            <a class="btn" href="#" onclick="window.print();return false;" style="display:inline-block;padding:.6rem 1rem;background:var(--color-primary);color:#fff;border-radius:var(--radius-sm);text-decoration:none;">Imprimer / PDF</a>
            <a class="btn" href="<?= base_url('alumni/cv-template/pdf') ?>" style="display:inline-block;padding:.6rem 1rem;background:#0d9488;color:#fff;border-radius:var(--radius-sm);text-decoration:none;margin-left:.5rem;">Exporter PDF (serveur)</a>
            <a class="btn" href="<?= base_url('alumni') ?>" style="display:inline-block;padding:.6rem 1rem;background:#253042;color:#fff;border-radius:var(--radius-sm);text-decoration:none;margin-left:.5rem;">Retour Alumni</a>
        </p>
        <style>
            @media print {
                body {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .admin,
                header,
                footer,
                nav {
                    display: none !important;
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
        <?php if (!empty($dompdf_missing)): ?>
            <div class="notice" style="margin-top:.8rem;padding:.6rem 1rem;border-radius:var(--radius-sm);background:#fff7ed;color:#9a3412;">
                Dompdf n'est pas installé côté serveur. Pour activer l'export PDF, installez Dompdf via Composer.
            </div>
        <?php endif; ?>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>