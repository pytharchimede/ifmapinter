<?php include __DIR__ . '/../partials/admin_header.php'; ?>
<div class="admin-main">
    <div class="admin-hero">
        <h1><?= htmlspecialchars($title) ?></h1>
    </div>
    <div style="display:grid;gap:1rem;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));margin-bottom:1.5rem;">
        <div class="admin-card" style="padding:1rem;background:#1f2940;border:1px solid #334155;border-radius:12px;">
            <h3 style="margin:0 0 .35rem;font-size:.85rem;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8;font-weight:600;">Événements</h3>
            <p style="margin:0;font-size:1.8rem;font-weight:700; color:#38bdf8;"><?= (int)$totalEvents ?></p>
        </div>
        <div class="admin-card" style="padding:1rem;background:#1f2940;border:1px solid #334155;border-radius:12px;">
            <h3 style="margin:0 0 .35rem;font-size:.85rem;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8;font-weight:600;">Inscriptions confirmées</h3>
            <p style="margin:0;font-size:1.8rem;font-weight:700; color:#16a34a;"><?= (int)$confirmedRegs ?></p>
        </div>
        <div class="admin-card" style="padding:1rem;background:#1f2940;border:1px solid #334155;border-radius:12px;">
            <h3 style="margin:0 0 .35rem;font-size:.85rem;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8;font-weight:600;">Places restantes</h3>
            <p style="margin:0;font-size:1.8rem;font-weight:700; color:#f59e0b;"><?= (int)$remainingPlaces ?></p>
        </div>
        <div class="admin-card" style="padding:1rem;background:#1f2940;border:1px solid #334155;border-radius:12px;">
            <h3 style="margin:0 0 .35rem;font-size:.85rem;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8;font-weight:600;">Témoignages en attente</h3>
            <p style="margin:0;font-size:1.8rem;font-weight:700; color:#ef4444;"><?= (int)$pendingTestimonials ?></p>
        </div>
        <div class="admin-card" style="padding:1rem;background:#1f2940;border:1px solid #334155;border-radius:12px;">
            <h3 style="margin:0 0 .35rem;font-size:.85rem;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8;font-weight:600;">Actualités publiées</h3>
            <p style="margin:0;font-size:1.8rem;font-weight:700; color:#6366f1;"><?= (int)$publishedNews ?></p>
        </div>
    </div>
    <div class="admin-card" style="padding:1rem;background:#1f2940;border:1px solid #334155;border-radius:12px;">
        <h2 style="margin:0 0 .75rem;font-size:1rem;color:#fff;">Actions rapides</h2>
        <div style="display:flex;flex-wrap:wrap;gap:.6rem;">
            <a class="btn" href="<?= base_url('admin/events/create') ?>">Créer événement</a>
            <a class="btn" href="<?= base_url('admin/events/registrations') ?>">Voir inscriptions</a>
            <a class="btn" href="<?= base_url('admin/testimonials') ?>">Modérer témoignages</a>
            <a class="btn" href="<?= base_url('admin/news/create') ?>">Nouvelle actualité</a>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../partials/admin_footer.php'; ?>