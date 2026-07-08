<?php

$currentPath = '/' . trim(service('uri')->getPath(), '/');
if ($currentPath === '//') {
    $currentPath = '/';
}

$theme = $theme ?? [];
$sidebarBrand = $sidebarBrand ?? [];
$sidebarSections = $sidebarSections ?? [];
$pageActions = $pageActions ?? [];

$sidebarStart = $theme['sidebarStart'] ?? '#0f172a';
$sidebarEnd = $theme['sidebarEnd'] ?? '#1d4ed8';
$accent = $theme['accent'] ?? '#1d4ed8';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Dashboard') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --dashboard-bg: #eef4fb;
            --surface: rgba(255, 255, 255, 0.9);
            --surface-strong: #ffffff;
            --text-strong: #0f172a;
            --text-soft: #64748b;
            --border-soft: rgba(148, 163, 184, 0.18);
            --shadow-soft: 0 20px 55px rgba(15, 23, 42, 0.08);
            --accent: <?= esc($accent) ?>;
            --sidebar-start: <?= esc($sidebarStart) ?>;
            --sidebar-end: <?= esc($sidebarEnd) ?>;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--text-strong);
            background:
                radial-gradient(circle at top left, rgba(29, 78, 216, 0.14), transparent 28%),
                radial-gradient(circle at top right, rgba(124, 58, 237, 0.12), transparent 24%),
                linear-gradient(180deg, #f8fbff 0%, var(--dashboard-bg) 100%);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(148, 163, 184, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.08) 1px, transparent 1px);
            background-size: 34px 34px;
            mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.35), transparent 82%);
            opacity: 0.4;
        }

        .app-shell {
            position: relative;
            z-index: 1;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 286px;
            padding: 24px 20px;
            color: #fff;
            background: linear-gradient(180deg, var(--sidebar-start), var(--sidebar-end));
            box-shadow: 18px 0 50px rgba(15, 23, 42, 0.12);
        }

        .brand-block {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 22px;
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.16);
        }

        .brand-badge {
            width: 54px;
            height: 54px;
            display: grid;
            place-items: center;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.14);
            font-size: 1.15rem;
        }

        .brand-title {
            margin: 0;
            font-size: 1.08rem;
            font-weight: 800;
            letter-spacing: 0.01em;
        }

        .brand-subtitle {
            margin: 4px 0 0;
            font-size: 0.88rem;
            color: rgba(255, 255, 255, 0.72);
        }

        .sidebar-sections {
            max-height: calc(100vh - 120px);
            overflow-y: auto;
            padding-right: 4px;
        }

        .sidebar-section-title {
            margin: 1.35rem 0 0.55rem;
            color: rgba(255, 255, 255, 0.62);
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            padding: 13px 14px;
            color: rgba(255, 255, 255, 0.88);
            text-decoration: none;
            border-radius: 16px;
            border: 1px solid transparent;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.14);
            border-color: rgba(255, 255, 255, 0.16);
            transform: translateX(3px);
        }

        .sidebar-link-icon {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.12);
            flex: 0 0 34px;
        }

        .main-area {
            flex: 1;
            padding: 24px;
        }

        .page-card {
            background: var(--surface);
            backdrop-filter: blur(14px);
            border: 1px solid var(--border-soft);
            border-radius: 28px;
            box-shadow: var(--shadow-soft);
            padding: 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            flex-wrap: wrap;
            padding-bottom: 20px;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--border-soft);
        }

        .page-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--accent);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .page-title {
            margin: 6px 0 10px;
            font-size: clamp(1.6rem, 3vw, 2.5rem);
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .page-subtitle {
            max-width: 68ch;
            margin: 0;
            color: var(--text-soft);
        }

        .page-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            border-radius: 999px;
            padding: 0.72rem 1rem;
            font-weight: 700;
        }

        .btn-primary {
            box-shadow: 0 12px 24px rgba(29, 78, 216, 0.18);
        }

        .mobile-toggle {
            display: none;
        }

        .dashboard-card,
        .table-shell,
        .info-panel {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 24px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        .dashboard-stat {
            position: relative;
            overflow: hidden;
            padding: 22px;
        }

        .dashboard-stat::after {
            content: "";
            position: absolute;
            inset: auto -18px -18px auto;
            width: 96px;
            height: 96px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.38);
            filter: blur(3px);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            display: grid;
            place-items: center;
            border-radius: 18px;
            margin-bottom: 16px;
            background: rgba(255, 255, 255, 0.9);
            font-size: 1.2rem;
        }

        .stat-value {
            margin-bottom: 6px;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
        }

        .stat-label {
            color: var(--text-soft);
        }

        .section-title {
            margin: 28px 0 16px;
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .quick-card {
            display: block;
            padding: 20px;
            color: var(--text-strong);
            text-decoration: none;
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.86);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .quick-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.08);
            border-color: rgba(29, 78, 216, 0.2);
        }

        .quick-card i {
            display: inline-flex;
            width: 46px;
            height: 46px;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            border-radius: 16px;
            font-size: 1.15rem;
            color: var(--accent);
            background: rgba(29, 78, 216, 0.08);
        }

        .table-shell {
            overflow: hidden;
        }

        .table-shell .table {
            margin-bottom: 0;
        }

        .table-shell thead {
            background: #f8fafc;
        }

        .table-shell th,
        .table-shell td {
            padding: 16px;
            vertical-align: middle;
        }

        .badge {
            border-radius: 999px;
            padding: 0.5rem 0.8rem;
            font-weight: 700;
        }

        @media (max-width: 992px) {
            .app-shell {
                display: block;
            }

            .sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 1040;
                transform: translateX(-100%);
                transition: transform 0.25s ease;
                overflow-y: auto;
            }

            .sidebar.is-open {
                transform: translateX(0);
            }

            .main-area {
                padding: 18px;
            }

            .mobile-toggle {
                display: inline-flex;
            }
        }

        @media (max-width: 576px) {
            .main-area {
                padding: 12px;
            }

            .page-card {
                padding: 18px;
                border-radius: 20px;
            }
        }
    </style>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="dashboardSidebar">
        <div class="brand-block">
            <div class="brand-badge">
                <i class="fas <?= esc($sidebarBrand['icon'] ?? 'fa-layer-group') ?>"></i>
            </div>
            <div>
                <p class="brand-title"><?= esc($sidebarBrand['title'] ?? 'Booking Service') ?></p>
                <p class="brand-subtitle"><?= esc($sidebarBrand['subtitle'] ?? '') ?></p>
            </div>
        </div>

        <nav class="sidebar-sections">
            <?php foreach ($sidebarSections as $section) : ?>
                <div class="sidebar-section-title"><?= esc($section['title'] ?? '') ?></div>
                <?php foreach (($section['items'] ?? []) as $item) : ?>
                    <a
                        href="<?= esc($item['href'] ?? '#') ?>"
                        class="sidebar-link <?= ui_nav_active($currentPath, $item['path'] ?? '') ? 'active' : '' ?>"
                    >
                        <span class="sidebar-link-icon">
                            <i class="fas <?= esc($item['icon'] ?? 'fa-circle') ?>"></i>
                        </span>
                        <span><?= esc($item['label'] ?? '') ?></span>
                    </a>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </nav>
    </aside>

    <main class="main-area">
        <div class="page-card">
            <div class="page-header">
                <div>
                    <button
                        type="button"
                        class="btn btn-light mobile-toggle mb-3"
                        data-dashboard-toggle
                        aria-controls="dashboardSidebar"
                        aria-expanded="false"
                    >
                        <i class="fas fa-bars me-2"></i> Menu
                    </button>
                    <div class="page-kicker">
                        <i class="fas fa-sparkles"></i>
                        <span><?= esc($pageKicker ?? 'Control Panel') ?></span>
                    </div>
                    <h1 class="page-title"><?= esc($pageTitle ?? 'Dashboard') ?></h1>
                    <p class="page-subtitle"><?= esc($pageSubtitle ?? '') ?></p>
                </div>

                <?php if (! empty($pageActions)) : ?>
                    <div class="page-actions">
                        <?php foreach ($pageActions as $action) : ?>
                            <a href="<?= esc($action['href'] ?? '#') ?>" class="btn <?= esc($action['class'] ?? 'btn-primary') ?>">
                                <?php if (! empty($action['icon'])) : ?>
                                    <i class="fas <?= esc($action['icon']) ?> me-2"></i>
                                <?php endif; ?>
                                <?= esc($action['label'] ?? '') ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?= $this->renderSection('content') ?>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('dashboardSidebar');
    const toggle = document.querySelector('[data-dashboard-toggle]');

    if (toggle && sidebar) {
        toggle.addEventListener('click', () => {
            const isOpen = sidebar.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }
</script>
</body>
</html>
