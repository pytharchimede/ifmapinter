<?php include __DIR__ . '/../../partials/admin_header.php'; ?>

<section class="section">
    <div class="container">
        <h2>Partenaires</h2>
        <p>
            <a class="btn-primary" href="<?= base_url('/admin/partners/create') ?>">Nouveau partenaire</a>
            · <a href="<?= base_url('/admin') ?>">Retour</a>
        </p>

        <style>
            .cards-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 16px;
            }

            .card {
                background: #fff;
                border: 1px solid #e5e5e5;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
                overflow: hidden;
                display: flex;
                flex-direction: column;
                transform: translateY(0);
                transition: transform 180ms ease, box-shadow 180ms ease;
            }

            .card:hover {
                transform: translateY(-4px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            }

            .card {
                animation: cardFade 240ms ease-out;
            }

            @keyframes cardFade {
                from {
                    opacity: 0;
                    transform: translateY(6px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .card-header {
                padding: 16px;
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .card-logo {
                width: 64px;
                height: 64px;
                border-radius: 10px;
                object-fit: contain;
                background: #fafafa;
                border: 1px dashed #ddd;
            }

            .card-title {
                font-weight: 600;
                font-size: 16px;
                margin: 0;
            }

            .badge {
                display: inline-block;
                padding: 4px 8px;
                border-radius: 999px;
                font-size: 12px;
            }

            .badge-on {
                background: #e7f7ed;
                color: #16794f;
                border: 1px solid #bfe8cf;
            }

            .badge-off {
                background: #fff1f0;
                color: #a8071a;
                border: 1px solid #ffccc7;
            }

            .card-actions {
                display: flex;
                gap: 8px;
                padding: 12px 16px;
                border-top: 1px solid #f0f0f0;
            }

            .btn {
                padding: 8px 12px;
                border-radius: 8px;
                border: 1px solid #ddd;
                background: #fff;
                cursor: pointer;
                text-decoration: none;
            }

            .btn:hover {
                background: #f8f8f8;
            }

            .btn-danger {
                border-color: #ffccc7;
                color: #a8071a;
            }

            .btn-primary {
                background: #1677ff;
                color: #fff;
                border-color: #1677ff;
            }

            .btn-primary:hover {
                background: #165ad9;
            }
        </style>

        <div class="cards-grid">
            <?php foreach ($items as $it): ?>
                <div class="card">
                    <div class="card-header">
                        <img class="card-logo" src="<?= htmlspecialchars($it['logo_url'] ?: '') ?>" alt="<?= htmlspecialchars($it['name']) ?>">
                        <div style="flex:1;">
                            <p class="card-title"><?= htmlspecialchars($it['name']) ?></p>
                            <?php $enabled = (int)($it['enabled'] ?? 1) === 1; ?>
                            <span class="badge <?= $enabled ? 'badge-on' : 'badge-off' ?>"><?= $enabled ? 'Actif' : 'Masqué' ?></span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <a class="btn" href="<?= base_url('/admin/partners/edit?id=' . (int)$it['id']) ?>">Modifier</a>
                        <a class="btn" href="<?= base_url('/admin/partners/toggle?id=' . (int)$it['id']) ?>"><?= $enabled ? 'Masquer' : 'Activer' ?></a>
                        <a class="btn btn-danger" href="#" data-del-url="<?= base_url('/admin/partners/delete?id=' . (int)$it['id']) ?>" onclick="openDeleteModal(this); return false;">Supprimer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="modal-backdrop" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.35);"></div>
        <div id="modal" style="display:none; position:fixed; left:50%; top:50%; transform:translate(-50%, -50%); background:#fff; width:420px; max-width:90vw; border-radius:12px; box-shadow:0 20px 40px rgba(0,0,0,0.2);">
            <div style="padding:20px;">
                <h3 style="margin:0 0 8px;">Confirmer la suppression</h3>
                <p style="margin:0 0 16px; color:#555;">Cette action est irréversible. Voulez-vous supprimer ce partenaire ?</p>
                <div style="display:flex; gap:8px; justify-content:flex-end;">
                    <button class="btn" onclick="closeDeleteModal()">Annuler</button>
                    <a id="modal-confirm" class="btn btn-danger" href="#">Supprimer</a>
                </div>
            </div>
        </div>
        <script>
            let _modalUrl = null;

            function openDeleteModal(el) {
                _modalUrl = el.getAttribute('data-del-url');
                document.getElementById('modal-confirm').setAttribute('href', _modalUrl);
                document.getElementById('modal-backdrop').style.display = 'block';
                document.getElementById('modal').style.display = 'block';
            }

            function closeDeleteModal() {
                document.getElementById('modal-backdrop').style.display = 'none';
                document.getElementById('modal').style.display = 'none';
            }
            document.getElementById('modal-backdrop').addEventListener('click', closeDeleteModal);
        </script>
    </div>
</section>

<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>