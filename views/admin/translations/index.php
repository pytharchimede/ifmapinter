<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<style>
    .table-trans {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 6px;
    }

    .table-trans th {
        color: var(--admin-muted);
        text-align: left;
        padding: 6px 8px;
    }

    .table-trans td {
        background: #0f1524;
        border: 1px solid #1f2940;
        color: var(--admin-text);
        padding: 6px 8px;
        border-radius: 8px;
    }

    .lang-pill {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: #15243a;
        border: 1px solid #213052;
        color: #cde;
        padding: .2rem .5rem;
        border-radius: 999px;
        font-size: .85rem;
    }

    .lang-pill img {
        width: 18px;
        height: auto;
        border-radius: 2px;
    }
</style>
<div class="admin-content">
    <h1 style="margin-top:0;display:flex;align-items:center;justify-content:space-between;gap:1rem;">
        <span><?= htmlspecialchars($title) ?></span>
        <div style="display:flex;gap:.6rem;align-items:center;">
            <a class="btn" href="<?= base_url('admin/translations/export.csv?lang=' . urlencode($lang)) ?>">Exporter CSV</a>
            <form method="post" action="<?= base_url('admin/translations/import.csv') ?>" enctype="multipart/form-data" style="display:flex;gap:.4rem;align-items:center;">
                <?= csrf_field() ?>
                <input type="hidden" name="lang" value="<?= htmlspecialchars($lang) ?>" />
                <input type="file" name="file" accept=".csv" />
                <button class="btn-admin" type="submit">Importer CSV</button>
            </form>
        </div>
    </h1>

    <form method="get" action="<?= base_url('admin/translations') ?>" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.6rem;margin:0 0 1rem 0;align-items:end">
        <div class="field">
            <label for="lang">Langue</label>
            <select id="lang" class="form-control" name="lang">
                <?php foreach (($langs ?? []) as $l): ?>
                    <option value="<?= htmlspecialchars($l['code']) ?>" <?= $lang === $l['code'] ? 'selected' : '' ?>><?= htmlspecialchars($l['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="q">Clé contient</label>
            <input id="q" class="form-control" type="text" name="q" value="<?= htmlspecialchars($q ?? '') ?>" />
        </div>
        <div>
            <button class="btn-admin" type="submit">Filtrer</button>
        </div>
    </form>

    <div class="panel" style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:14px;padding:1rem;">
        <h2 style="display:flex;align-items:center;gap:.5rem;">
            <span>Pack de traduction</span>
            <span class="lang-pill">
                <?php
                // Trouver la langue sélectionnée depuis la liste et utiliser son flag_url si disponible
                $selected = null;
                if (!empty($langs)) {
                    foreach ($langs as $l) {
                        if (!empty($l['code']) && $l['code'] === $lang) {
                            $selected = $l;
                            break;
                        }
                    }
                }
                $flagUrl = '';
                if (!empty($selected) && !empty($selected['flag_url'])) {
                    $flagUrl = $selected['flag_url'];
                } else {
                    $flagCode = strtolower(explode('-', $lang)[0]);
                    $flagUrl = 'https://flagcdn.com/24x18/' . htmlspecialchars($flagCode) . '.png';
                }
                ?>
                <img src="<?= htmlspecialchars($flagUrl) ?>" alt="<?= htmlspecialchars($lang) ?>" />
                <strong><?= strtoupper($lang) ?></strong>
            </span>
        </h2>
        <form method="post" action="<?= base_url('admin/translations/create') ?>" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:.6rem;margin:0 0 1rem 0;align-items:end">
            <?= csrf_field() ?>
            <input type="hidden" name="lang" value="<?= htmlspecialchars($lang) ?>" />
            <div class="field">
                <label for="key">Clé</label>
                <input id="key" class="form-control" type="text" name="key" placeholder="ex: nav.home" />
            </div>
            <div class="field" style="grid-column: span 2;">
                <label for="value">Texte</label>
                <textarea id="value" class="form-control" name="value" rows="2" placeholder="Texte traduit"></textarea>
            </div>
            <div>
                <button class="btn-admin" type="submit">Ajouter / Mettre à jour</button>
            </div>
        </form>

        <table class="table-trans">
            <thead>
                <tr>
                    <th>Clé</th>
                    <th>Texte</th>
                    <th>MAJ</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($rows ?? []) as $r): ?>
                    <tr>
                        <td style="min-width:200px;"><?= htmlspecialchars($r['key']) ?></td>
                        <td>
                            <form method="post" action="<?= base_url('admin/translations/update') ?>" style="display:flex;gap:.4rem;align-items:center;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>" />
                                <input type="hidden" name="lang" value="<?= htmlspecialchars($lang) ?>" />
                                <textarea name="value" class="form-control" rows="2" style="width:100%;min-width:360px;"><?= htmlspecialchars($r['value']) ?></textarea>
                                <button class="btn-admin" type="submit">Enregistrer</button>
                            </form>
                        </td>
                        <td><small style="color:var(--admin-muted)"><?= htmlspecialchars($r['updated_at']) ?></small></td>
                        <td>
                            <a class="btn" href="<?= base_url('admin/translations/delete?id=' . (int)$r['id'] . '&lang=' . urlencode($lang)) ?>">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>