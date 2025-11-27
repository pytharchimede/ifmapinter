<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section" id="campus-page">
    <div class="container">
        <div class="section-title">
            <h2>Campus</h2>
            <p>Un projet en cours de réalisation : espaces modernes, laboratoires et lieux de vie.</p>
            <span class="badge" style="display:inline-block;padding:.4rem .8rem;background:var(--color-primary);color:#fff;border-radius:999px;font-size:.85rem;">Projet en cours</span>
        </div>

        <div class="grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">
            <div class="card"><img loading="lazy" src="https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=800&q=60" alt="Centre technique" style="width:100%;border-radius:var(--radius-md)">
                <p style="margin-top:.5rem">Centre technique</p>
            </div>
            <div class="card"><img loading="lazy" src="https://images.unsplash.com/photo-1509395062183-67c5ad6faff9?auto=format&fit=crop&w=800&q=60" alt="Atelier solaire" style="width:100%;border-radius:var(--radius-md)">
                <p style="margin-top:.5rem">Atelier solaire</p>
            </div>
            <div class="card"><img loading="lazy" src="https://images.unsplash.com/photo-1498079022511-d15614cb1c02?auto=format&fit=crop&w=800&q=60" alt="Bâtiment principal" style="width:100%;border-radius:var(--radius-md)">
                <p style="margin-top:.5rem">Bâtiment principal</p>
            </div>
        </div>

        <div class="info" style="margin-top:2rem">
            <h3>Phases du projet</h3>
            <ul>
                <li>Phase 1: études et conception</li>
                <li>Phase 2: construction des premiers modules</li>
                <li>Phase 3: déploiement des équipements pédagogiques</li>
            </ul>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>