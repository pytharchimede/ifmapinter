<!DOCTYPE html>
<html lang="<?= htmlspecialchars(resolve_lang()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'IFMAP' ?></title>

    <!-- CSS -->
    <?php $cssV = @filemtime(__DIR__ . '/../../assets/css/style.css') ?: time(); ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css?v=' . $cssV) ?>">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

</head>

<body>

    <!-- ================= HEADER ================= -->
    <header id="header">
        <div class="container nav">
            <div class="logo">
                <a href="<?= base_url() ?>">
                    <?php $settings = null;
                    try {
                        $settings = db()->query('SELECT logo_url, platform_url, newsletter_url FROM settings WHERE id=1')->fetch();
                    } catch (Throwable $e) {
                    } ?>
                    <img src="<?= htmlspecialchars($settings['logo_url'] ?? 'https://ifmap.ci/uploads/system/1fb9ea08a27e58c71dc6e639284b74eb.png') ?>" alt="IFMAP Logo" style="max-height:48px;">
                </a>
            </div>

            <nav>
                <ul class="menu">
                    <li><a href="<?= base_url('institut') ?>"><?= htmlspecialchars(t('nav.institut')) ?></a></li>
                    <li><a href="<?= base_url('programmes') ?>"><?= htmlspecialchars(t('nav.programmes')) ?></a></li>
                    <li><a href="<?= base_url('formations') ?>"><?= htmlspecialchars(t('nav.formations')) ?></a></li>
                    <li><a href="<?= base_url('centres') ?>"><?= htmlspecialchars(t('nav.centres')) ?></a></li>
                    <li><a href="<?= base_url('campus') ?>"><?= htmlspecialchars(t('nav.campus')) ?></a></li>
                    <li><a href="<?= base_url('partenaires') ?>"><?= htmlspecialchars(t('nav.partners')) ?></a></li>
                    <li><a href="<?= base_url('actualites') ?>"><?= htmlspecialchars(t('nav.news')) ?></a></li>
                    <li><a href="<?= base_url('alumni') ?>"><?= htmlspecialchars(t('nav.alumni')) ?></a></li>
                    <li><a href="<?= base_url('contact') ?>"><?= htmlspecialchars(t('nav.contact')) ?></a></li>
                    <li><a href="<?= base_url('galerie') ?>"><?= htmlspecialchars(t('nav.gallery')) ?></a></li>
                </ul>
                <div class="actions" style="display:flex; align-items:center; gap:1rem;">
                    <button class="theme-toggle" aria-label="Basculer le thème">🌓</button>
                    <?php if (!empty($settings['platform_url'])): ?>
                        <a class="btn-outline" href="<?= htmlspecialchars($settings['platform_url']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars(t('btn.login')) ?></a>
                    <?php endif; ?>
                    <?php if (!empty($settings['newsletter_url'])): ?>
                        <a class="btn-outline" href="<?= htmlspecialchars($settings['newsletter_url']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars(t('btn.newsletter')) ?></a>
                    <?php endif; ?>
                    <!-- Language selector -->
                    <?php
                    $langs = [];
                    try {
                        $langs = db()->query("SELECT code,name,flag,flag_url FROM languages WHERE enabled=1 ORDER BY name ASC")->fetchAll();
                    } catch (Throwable $e) {
                    }
                    if (!$langs || count($langs) === 0) {
                        $langs = [
                            ['code' => 'fr', 'name' => 'Français', 'flag' => 'fr', 'flag_url' => null],
                            ['code' => 'en', 'name' => 'English', 'flag' => 'gb', 'flag_url' => 'https://flagcdn.com/24x18/gb.png'],
                        ];
                    }
                    $curLang = resolve_lang();
                    ?>
                    <div class="lang-select" style="display:flex;align-items:center;gap:.4rem;">
                        <div class="dropdown" style="position:relative;">
                            <button class="btn-outline" style="display:flex;align-items:center;gap:.4rem;padding:.3rem .5rem;min-width:auto;">
                                <?php
                                $curBase = strtolower(explode('-', $curLang)[0]);
                                $curFlagUrl = null;
                                foreach ($langs as $l) {
                                    if (strtolower($l['code']) === strtolower($curLang)) {
                                        $curFlagUrl = $l['flag_url'] ?? null;
                                        $curBase = strtolower($l['flag'] ?: $curBase);
                                        break;
                                    }
                                }
                                ?>
                                <img src="<?= htmlspecialchars($curFlagUrl ?: ('https://flagcdn.com/24x18/' . $curBase . '.png')) ?>" alt="<?= htmlspecialchars($curLang) ?>" style="width:18px;height:auto;border-radius:2px;" />
                                <span><?= strtoupper($curLang) ?></span>
                                <i class="fa fa-chevron-down" aria-hidden="true"></i>
                            </button>
                            <ul class="dropdown-menu" style="position:absolute;right:0;top:calc(100% + 6px);background:#fff;border:1px solid #ddd;border-radius:8px;min-width:160px;box-shadow:0 8px 24px rgba(0,0,0,.12);padding:.4rem;display:none;z-index:1000;">
                                <?php foreach ($langs as $l): ?>
                                    <li style="list-style:none;">
                                        <a style="display:flex;gap:.5rem;align-items:center;padding:.3rem .4rem;white-space:nowrap;" href="<?= base_url('/lang?l=' . urlencode($l['code']) . '&back=' . urlencode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH))) ?>">
                                            <?php $flagCode = strtolower($l['flag'] ? $l['flag'] : explode('-', $l['code'])[0]); ?>
                                            <img src="<?= htmlspecialchars(($l['flag_url'] ?? '') !== '' ? $l['flag_url'] : ('https://flagcdn.com/24x18/' . $flagCode . '.png')) ?>" alt="<?= htmlspecialchars($l['code']) ?>" style="width:18px;height:auto;border-radius:2px;" />
                                            <span><?= htmlspecialchars($l['name']) ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <script>
                        (function() {
                            var btn = document.currentScript.parentElement.querySelector('.dropdown button');
                            var menu = document.currentScript.parentElement.querySelector('.dropdown-menu');
                            btn.addEventListener('click', function(e) {
                                e.preventDefault();
                                menu.style.display = (menu.style.display === 'block' ? 'none' : 'block');
                            });
                            document.addEventListener('click', function(e) {
                                if (!btn.contains(e.target) && !menu.contains(e.target)) {
                                    menu.style.display = 'none';
                                }
                            });
                        })();
                    </script>
                    <div class="toggle" id="menu-toggle"><i class="fa fa-bars"></i></div>
                </div>
            </nav>
        </div>
    </header>