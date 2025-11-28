<?php include __DIR__ . '/../../partials/admin_header.php'; ?>

<section class="section">
    <div class="container">
        <h2>Témoignages (modération)</h2>
        <?php if (!empty($success)): ?><div class="alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if (!empty($error)): ?><div class="alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <p><a href="<?= base_url('/admin') ?>">Retour</a></p>

        <style>
            .cards-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 16px;
            }

            .card {
                background: #fff;
                border: 1px solid #eee;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
                overflow: hidden;
                display: flex;
                flex-direction: column;
            }

            .card-header {
                display: flex;
                gap: 12px;
                padding: 16px;
                align-items: center;
                border-bottom: 1px solid #f0f0f0;
            }

            .avatar {
                width: 56px;
                height: 56px;
                border-radius: 50%;
                object-fit: cover;
                background: #f5f5f5;
            }

            .card-body {
                padding: 16px;
            }

            .card-actions {
                display: flex;
                gap: 8px;
                padding: 12px 16px;
                border-top: 1px solid #f0f0f0;
            }

            .badge {
                font-size: 12px;
                border-radius: 999px;
                padding: 4px 8px;
            }

            .b-pending {
                background: #fff7e6;
                color: #ad6800;
                border: 1px solid #ffe58f;
            }

            .b-approved {
                background: #e7f7ed;
                color: #16794f;
                border: 1px solid #bfe8cf;
            }

            .b-rejected {
                background: #fff1f0;
                color: #a8071a;
                border: 1px solid #ffccc7;
            }

            .btn {
                padding: 8px 12px;
                border-radius: 8px;
                border: 1px solid #ddd;
                background: #fff;
                text-decoration: none;
            }

            .btn-danger {
                border-color: #ffccc7;
                color: #a8071a;
            }
        </style>

        <div class="cards-grid">
            <?php foreach ($items as $it): ?>
                <?php $st = $it['status'] ?? 'pending'; ?>
                <div class="card">
                    <div class="card-header">
                        <img class="avatar" src="<?= htmlspecialchars($it['avatar_url'] ?: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=200&h=200&fit=crop') ?>" alt="">
                        <div style="flex:1;">
                            <strong><?= htmlspecialchars($it['name']) ?></strong>
                            <?php if (!empty($it['role'])): ?><div><small><?= htmlspecialchars($it['role']) ?></small></div><?php endif; ?>
                        </div>
                        <span class="badge <?= $st === 'approved' ? 'b-approved' : ($st === 'rejected' ? 'b-rejected' : 'b-pending') ?>"><?= htmlspecialchars($st) ?></span>
                    </div>
                    <div class="card-body">
                        <p style="margin:0;">“<?= nl2br(htmlspecialchars($it['message'])) ?>”</p>
                    </div>
                    <div class="card-actions">
                        <?php if ($st !== 'approved'): ?>
                            <a class="btn" href="<?= base_url('/admin/testimonials/approve?id=' . (int)$it['id']) ?>">Approuver</a>
                        <?php endif; ?>
                        <?php if ($st !== 'rejected'): ?>
                            <a class="btn" href="<?= base_url('/admin/testimonials/reject?id=' . (int)$it['id']) ?>">Rejeter</a>
                        <?php endif; ?>
                        <a class="btn btn-danger" href="#" data-del-url="<?= base_url('/admin/testimonials/delete?id=' . (int)$it['id']) ?>" onclick="openDeleteModal(this); return false;">Supprimer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="modal-backdrop" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.35);"></div>
        <div id="modal" style="display:none; position:fixed; left:50%; top:50%; transform:translate(-50%, -50%); background:#fff; width:420px; max-width:90vw; border-radius:12px; box-shadow:0 20px 40px rgba(0,0,0,0.2);">
            <div style="padding:20px;">
                <h3 style="margin:0 0 8px;">Confirmer la suppression</h3>
                <p style="margin:0 0 16px; color:#555;">Cette action est irréversible. Voulez-vous supprimer ce témoignage ?</p>
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