<?php include __DIR__ . '/../../partials/admin_header.php'; ?>
<style>
    /* Palette sombre cohérente */
    .analytics-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem
    }

    .analytics-cards .metric {
        background: var(--admin-surface);
        border: 1px solid var(--admin-border);
        border-radius: 14px;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: .35rem;
        box-shadow: var(--shadow)
    }

    .analytics-cards .metric h3 {
        margin: 0;
        font-size: .9rem;
        font-weight: 600;
        color: var(--admin-muted);
        text-transform: uppercase;
        letter-spacing: .06em
    }

    .analytics-cards .metric p {
        margin: 0;
        font-size: 1.7rem;
        font-weight: 700;
        color: var(--admin-text)
    }

    .analytics-panels {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 1.5rem
    }

    .analytics-panels .panel {
        background: var(--admin-surface);
        border: 1px solid var(--admin-border);
        border-radius: 14px;
        padding: 1rem;
        box-shadow: var(--shadow);
        overflow: hidden
    }

    .analytics-panels h2 {
        margin: 0 0 .8rem 0;
        font-size: 1.05rem;
        font-weight: 600;
        color: #fff
    }

    .bar-bg {
        background: #1f2937;
        border-radius: 4px;
        overflow: hidden
    }

    .bar-fill {
        height: 8px;
        background: linear-gradient(90deg, #4b7bec, #2563eb);
    }

    .bar-fill.country {
        background: linear-gradient(90deg, #20bf6b, #0fbf9e)
    }

    .daily-chart,
    .hourly-chart {
        display: flex;
        align-items: flex-end;
        gap: 6px;
        height: 140px
    }

    .daily-chart .col,
    .hourly-chart .col {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center
    }

    .daily-chart .bar,
    .hourly-chart .bar {
        width: 100%;
        background: #1f2937;
        border-radius: 4px;
        display: flex;
        align-items: flex-end
    }

    .daily-chart .bar span,
    .hourly-chart .bar span {
        display: block;
        width: 100%;
        border-radius: 4px 4px 0 0;
        background: #8854d0
    }

    .hourly-chart .bar span {
        background: #fa8231
    }

    .analytics-panels small {
        color: var(--admin-muted)
    }

    .recent-panel table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 6px;
        font-size: 12px
    }

    .recent-panel th {
        color: var(--admin-muted);
        text-align: left;
        font-weight: 600;
        padding: 6px 8px
    }

    .recent-panel td {
        background: #0f1524;
        border: 1px solid #1f2940;
        color: var(--admin-text);
        padding: 6px 8px;
        border-radius: 8px
    }

    /* Assurer que les tableaux ne débordent pas des panneaux */
    .analytics-panels .panel table {
        table-layout: auto;
        width: 100%;
        max-width: 100%;
    }

    .analytics-panels .panel th,
    .analytics-panels .panel td {
        word-break: break-word;
        overflow-wrap: anywhere;
        vertical-align: top;
    }

    .analytics-panels .panel .path-cell {
        max-width: 100%;
    }

    @media(max-width:900px) {
        .analytics-panels {
            grid-template-columns: 1fr
        }
    }
</style>
<div class="admin-content">
    <h1 style="margin-top:0;display:flex;align-items:center;justify-content:space-between;gap:1rem;">
        <span><?= htmlspecialchars($title) ?></span>
        <a class="btn" href="<?= base_url(
                                    'admin/analytics/visits/export.csv?start=' . urlencode($start ?? '')
                                        . '&end=' . urlencode($end ?? '')
                                        . '&preset=' . urlencode($preset ?? '')
                                        . '&path=' . urlencode($q ?? '')
                                        . '&country=' . urlencode($country ?? '')
                                        . '&ip=' . urlencode($ip ?? '')
                                        . '&ua=' . urlencode($ua ?? '')
                                        . '&ref=' . urlencode($ref ?? '')
                                        . '&hfrom=' . urlencode($hfrom ?? '')
                                        . '&hto=' . urlencode($hto ?? '')
                                        . '&include_admin=' . (isset($include_admin) && $include_admin ? '1' : '0')
                                ) ?>">Exporter CSV</a>
    </h1>

    <form method="get" action="<?= base_url('admin/analytics/visits') ?>" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.6rem;margin:0 0 1rem 0;align-items:end">
        <div class="field">
            <label for="preset">Période</label>
            <select id="preset" class="form-control" name="preset">
                <option value="">-- Personnalisé --</option>
                <option value="today" <?= isset($preset) && $preset === 'today' ? 'selected' : '' ?>>Aujourd'hui</option>
                <option value="7d" <?= isset($preset) && $preset === '7d' ? 'selected' : '' ?>>7 derniers jours</option>
                <option value="30d" <?= isset($preset) && $preset === '30d' ? 'selected' : '' ?>>30 derniers jours</option>
                <option value="90d" <?= isset($preset) && $preset === '90d' ? 'selected' : '' ?>>90 derniers jours</option>
            </select>
        </div>
        <div class="field">
            <label for="start">Début</label>
            <input id="start" class="form-control" type="date" name="start" value="<?= htmlspecialchars($start ?? '') ?>" />
        </div>
        <div class="field">
            <label for="end">Fin</label>
            <input id="end" class="form-control" type="date" name="end" value="<?= htmlspecialchars($end ?? '') ?>" />
        </div>
        <div class="field">
            <label for="path">Chemin contient</label>
            <input id="path" class="form-control" type="text" name="path" placeholder="/centres, /formations ..." value="<?= htmlspecialchars($q ?? '') ?>" />
        </div>
        <div class="field">
            <label for="country">Pays (code)</label>
            <input id="country" class="form-control" type="text" name="country" placeholder="FR, CI, US ..." value="<?= htmlspecialchars($country ?? '') ?>" />
        </div>
        <div class="field">
            <label for="ip">IP contient</label>
            <input id="ip" class="form-control" type="text" name="ip" placeholder="192.168..." value="<?= htmlspecialchars($ip ?? '') ?>" />
        </div>
        <div class="field">
            <label for="ua">User-Agent contient</label>
            <input id="ua" class="form-control" type="text" name="ua" placeholder="Chrome, Mobile..." value="<?= htmlspecialchars($ua ?? '') ?>" />
        </div>
        <div class="field">
            <label for="ref">Référent contient</label>
            <input id="ref" class="form-control" type="text" name="ref" placeholder="google, facebook..." value="<?= htmlspecialchars($ref ?? '') ?>" />
        </div>
        <div class="field">
            <label for="hfrom">Heure de</label>
            <input id="hfrom" class="form-control" type="number" min="0" max="23" name="hfrom" value="<?= isset($hfrom) && $hfrom !== null ? (int)$hfrom : '' ?>" />
        </div>
        <div class="field">
            <label for="hto">Heure à</label>
            <input id="hto" class="form-control" type="number" min="0" max="23" name="hto" value="<?= isset($hto) && $hto !== null ? (int)$hto : '' ?>" />
        </div>
        <div class="field" style="display:flex;gap:.5rem;align-items:center;">
            <input id="include_admin" type="checkbox" name="include_admin" value="1" <?= isset($include_admin) && $include_admin ? 'checked' : '' ?> />
            <label for="include_admin" style="margin:0;">Inclure pages admin</label>
        </div>
        <div>
            <button class="btn-admin" type="submit">Filtrer</button>
        </div>
    </form>
    <div class="analytics-cards">
        <div class="metric">
            <h3>Total visites</h3>
            <p><?= (int)$totalVisits ?></p>
        </div>
        <div class="metric">
            <h3>Visiteurs (approx.)</h3>
            <p><?= (int)$uniqueVisitors ?></p>
        </div>
        <div class="metric">
            <h3>Pages distinctes</h3>
            <p><?= count($topPaths) ?> / 10</p>
        </div>
        <div class="metric">
            <h3>Pays distincts</h3>
            <p><?= count($topCountries) ?> / 10</p>
        </div>
    </div>

    <div class="analytics-panels">
        <div class="panel">
            <h2>Top pages</h2>
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left;">Chemin</th>
                        <th style="text-align:right;">Visites</th>
                        <th style="width:40%;">%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $maxP = 0;
                    foreach ($topPaths as $p) {
                        if ($p['c'] > $maxP) $maxP = $p['c'];
                    } ?>
                    <?php foreach ($topPaths as $row): $pct = $totalVisits > 0 ? round(($row['c'] / $totalVisits) * 100, 1) : 0;
                        $bar = $maxP > 0 ? ($row['c'] / $maxP) * 100 : 0; ?>
                        <tr>
                            <td class="path-cell" style="padding:4px 6px;" title="<?= htmlspecialchars($row['path']) ?>"><?= htmlspecialchars($row['path']) ?></td>
                            <td style="text-align:right;padding:4px 6px;font-weight:600;"><?= (int)$row['c'] ?></td>
                            <td style="padding:4px 6px;">
                                <div class="bar-bg">
                                    <div class="bar-fill" style="width:<?= $bar ?>%;"></div>
                                </div>
                                <small><?= $pct ?>%</small>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="panel">
            <h2>Top pays</h2>
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left;">Pays</th>
                        <th style="text-align:right;">Visites</th>
                        <th style="width:40%;">%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $maxC = 0;
                    foreach ($topCountries as $c) {
                        if ($c['c'] > $maxC) $maxC = $c['c'];
                    } ?>
                    <?php foreach ($topCountries as $row): $pct = $totalVisits > 0 ? round(($row['c'] / $totalVisits) * 100, 1) : 0;
                        $bar = $maxC > 0 ? ($row['c'] / $maxC) * 100 : 0; ?>
                        <tr>
                            <td style="padding:4px 6px;"><?= htmlspecialchars($row['country']) ?></td>
                            <td style="text-align:right;padding:4px 6px;font-weight:600;"><?= (int)$row['c'] ?></td>
                            <td style="padding:4px 6px;">
                                <div class="bar-bg">
                                    <div class="bar-fill country" style="width:<?= $bar ?>%;"></div>
                                </div>
                                <small><?= $pct ?>%</small>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="analytics-panels" style="margin-top:2rem;">
        <div class="panel">
            <h2>Derniers 14 jours</h2>
            <?php $maxD = 0;
            foreach ($dailyLast14 as $d) {
                if ($d['c'] > $maxD) $maxD = $d['c'];
            } ?>
            <div class="daily-chart">
                <?php foreach ($dailyLast14 as $d): $h = $maxD > 0 ? (($d['c'] / $maxD) * 100) : 0; ?>
                    <div class="col">
                        <div class="bar"><span style="height:<?= $h ?>%"></span></div>
                        <small style="font-size:10px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars(substr($d['d'], 5)) ?></small>
                        <small style="font-size:10px;"><?= (int)$d['c'] ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="panel">
            <h2>Heures (Aujourd'hui)</h2>
            <?php $maxH = 0;
            foreach ($hourlyToday as $h) {
                if ($h['c'] > $maxH) $maxH = $h['c'];
            } ?>
            <div class="hourly-chart">
                <?php foreach ($hourlyToday as $h): $perc = $maxH > 0 ? (($h['c'] / $maxH) * 100) : 0; ?>
                    <div class="col">
                        <div class="bar"><span style="height:<?= $perc ?>%"></span></div>
                        <small style="font-size:10px;"><?= (int)$h['h'] ?>h</small>
                        <small style="font-size:10px;"><?= (int)$h['c'] ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="panel recent-panel" style="margin-top:2rem;">
        <h2>Dernières visites (50)</h2>
        <table>
            <thead>
                <tr>
                    <th>Heure</th>
                    <th>Path</th>
                    <th>IP</th>
                    <th>UA</th>
                    <th>Réf.</th>
                    <th>Pays</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent as $v): ?>
                    <tr>
                        <td><?= htmlspecialchars(substr($v['created_at'], 11)) ?></td>
                        <td><?= htmlspecialchars($v['path']) ?></td>
                        <td><?= htmlspecialchars($v['ip']) ?></td>
                        <td title="<?= htmlspecialchars($v['user_agent']) ?>"><?= htmlspecialchars(substr($v['user_agent'], 0, 40)) ?></td>
                        <td title="<?= htmlspecialchars($v['referrer']) ?>"><?= htmlspecialchars(substr($v['referrer'], 0, 40)) ?></td>
                        <td><?= htmlspecialchars($v['country']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../partials/admin_footer.php'; ?>