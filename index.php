<?php

declare(strict_types=1);

$modules = require __DIR__ . '/config/modules.php';
require __DIR__ . '/includes/helpers.php';

$currentModule = resolveCurrentModule($modules);
$currentData = $modules[$currentModule];
$loggedInUser = 'CRM Admin';

$settingsSections = [
    'permissions-users' => ['group' => 'Permissions', 'label' => 'Users'],
    'permissions-module-access' => ['group' => 'Permissions', 'label' => 'Module Access'],
    'standard-module-edit-fields' => ['group' => 'Standard Module', 'label' => 'Edit Fields'],
    'standard-module-picklists' => ['group' => 'Standard Module', 'label' => 'Field - Picklists'],
    'advanced-module-taxes-rates' => ['group' => 'Advanced Module', 'label' => 'Taxes - Rates'],
    'advanced-module-discount-configurations' => ['group' => 'Advanced Module', 'label' => 'Discount Configurations'],
    'labels-searching-filtering' => ['group' => 'Labels, Searching and Filtering', 'label' => 'Labels and Searching'],
    'filters-configuration' => ['group' => 'Labels, Searching and Filtering', 'label' => 'Filters - Configuration'],
    'system-tools-pdf-templates' => ['group' => 'System Tools', 'label' => 'PDF Templates'],
    'system-tools-backup-manager' => ['group' => 'System Tools', 'label' => 'Backup Manager'],
    'system-tools-restore' => ['group' => 'System Tools', 'label' => 'Restore'],
    'system-tools-currencies' => ['group' => 'System Tools', 'label' => 'Currencies'],
    'process-marketing' => ['group' => 'Process', 'label' => 'Marketing'],
    'process-sales' => ['group' => 'Process', 'label' => 'Sales'],
    'process-realization' => ['group' => 'Process', 'label' => 'Realization'],
    'process-finances' => ['group' => 'Process', 'label' => 'Finances'],
    'process-support' => ['group' => 'Process', 'label' => 'Support'],
    'process-workflow' => ['group' => 'Process', 'label' => 'Workflow'],
    'company-details' => ['group' => 'Company', 'label' => 'Company Details'],
];

$currentSetting = isset($_GET['setting']) ? trim((string) $_GET['setting']) : 'permissions-users';
if (!array_key_exists($currentSetting, $settingsSections)) {
    $currentSetting = 'permissions-users';
}

/**
 * @param array<string, array{group:string, label:string}> $settingsSections
 */
function renderSettingsDetails(array $settingsSections, string $currentSetting): void
{
    $activeSection = $settingsSections[$currentSetting];
    $grouped = [];

    foreach ($settingsSections as $key => $section) {
        $grouped[$section['group']][$key] = $section;
    }
    ?>
    <section class="settings-layout" aria-label="Settings layout">
        <aside class="settings-sidebar">
            <h4>Settings Panel</h4>
            <?php foreach ($grouped as $group => $items): ?>
                <div class="settings-group">
                    <h5><?= htmlspecialchars($group, ENT_QUOTES, 'UTF-8') ?></h5>
                    <nav>
                        <?php foreach ($items as $key => $item): ?>
                            <?php $className = $key === $currentSetting ? 'setting-link active' : 'setting-link'; ?>
                            <a
                                class="<?= $className ?>"
                                href="?module=Settings&setting=<?= urlencode($key) ?>"
                                data-setting="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
                            >
                                <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        <?php endforeach; ?>
                    </nav>
                </div>
            <?php endforeach; ?>
        </aside>

        <section class="settings-content" id="settings-content-area" aria-live="polite">
            <header>
                <h3 id="settings-title"><?= htmlspecialchars($activeSection['label'], ENT_QUOTES, 'UTF-8') ?></h3>
                <span id="settings-group" class="settings-badge"><?= htmlspecialchars($activeSection['group'], ENT_QUOTES, 'UTF-8') ?></span>
            </header>
            <p id="settings-description">
                You selected <strong><?= htmlspecialchars($activeSection['label'], ENT_QUOTES, 'UTF-8') ?></strong>.
                Detailed requirements can be added to this section in the next phase.
            </p>
        </section>
    </section>
    <?php
}

/**
 * @param array{label:string, icon:string, description:string} $module
 * @param array<string, array{group:string, label:string}> $settingsSections
 */
function renderModuleDetails(string $moduleName, array $module, array $modules, array $settingsSections, string $currentSetting): void
{
    $recommendedActions = [
        "View {$module['label']} records",
        "Create new {$module['label']} entry",
        "Review recent {$module['label']} updates",
    ];

    $sampleFields = [
        'Module Key' => $moduleName,
        'Record Owner' => 'CRM Administrator',
        'Updated At' => date('Y-m-d H:i'),
    ];
    ?>
    <header class="main-header">
        <h2><?= htmlspecialchars($module['label'], ENT_QUOTES, 'UTF-8') ?></h2>
        <span class="badge">Active Module</span>
    </header>

    <section class="module-card">
        <h3><?= htmlspecialchars($module['icon'], ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($module['label'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p><?= htmlspecialchars($module['description'], ENT_QUOTES, 'UTF-8') ?></p>
    </section>

    <?php if ($moduleName === 'Settings'): ?>
        <?php renderSettingsDetails($settingsSections, $currentSetting); ?>
    <?php else: ?>
        <section class="details-layout" aria-label="Selected module details">
            <article class="details-card">
                <h4>Quick actions</h4>
                <ul>
                    <?php foreach ($recommendedActions as $action): ?>
                        <li><?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </article>
            <article class="details-card">
                <h4>Module snapshot</h4>
                <dl>
                    <?php foreach ($sampleFields as $label => $value): ?>
                        <div class="detail-row">
                            <dt><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></dt>
                            <dd><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </article>
        </section>
    <?php endif; ?>

    <section class="module-grid" aria-label="All modules overview">
        <?php foreach ($modules as $name => $moduleData): ?>
            <article class="grid-item">
                <h4><?= htmlspecialchars($moduleData['icon'], ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($moduleData['label'], ENT_QUOTES, 'UTF-8') ?></h4>
                <p><?= htmlspecialchars($moduleData['description'], ENT_QUOTES, 'UTF-8') ?></p>
                <a class="open-module-link" href="?module=<?= urlencode($name) ?>" data-module="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">Open module</a>
            </article>
        <?php endforeach; ?>
    </section>
    <?php
}

if (isset($_GET['partial']) && $_GET['partial'] === '1') {
    renderModuleDetails($currentModule, $currentData, $modules, $settingsSections, $currentSetting);
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRM Base | <?= htmlspecialchars($currentData['label'], ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand">
            <h1>CRM Base</h1>
            <p>YetiForce-like starter</p>
        </div>
        <nav class="module-nav" aria-label="Main modules">
            <?php foreach ($modules as $name => $module): ?>
                <?php $activeClass = $name === $currentModule ? 'module-link active' : 'module-link'; ?>
                <a class="<?= $activeClass ?>" href="?module=<?= urlencode($name) ?>" data-module="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
                    <span class="icon" aria-hidden="true"><?= htmlspecialchars($module['icon'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span><?= htmlspecialchars($module['label'], ENT_QUOTES, 'UTF-8') ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <main class="main-content">
        <header class="app-topbar">
            <div class="topbar-actions">
                <a class="settings-gear open-module-link" href="?module=Settings" data-module="Settings" aria-label="Open settings">⚙️</a>
                <div class="user-menu-wrapper">
                    <button id="user-menu-toggle" class="user-menu-toggle" type="button" aria-haspopup="true" aria-expanded="false">
                        <?= htmlspecialchars($loggedInUser, ENT_QUOTES, 'UTF-8') ?>
                    </button>
                    <div id="user-menu-dropdown" class="user-menu-dropdown" role="menu" aria-label="User menu">
                        <a href="#" role="menuitem">My Preferences</a>
                        <a href="#" role="menuitem">Sign Out</a>
                    </div>
                </div>
            </div>
        </header>

        <section id="module-content" aria-live="polite">
            <?php renderModuleDetails($currentModule, $currentData, $modules, $settingsSections, $currentSetting); ?>
        </section>
    </main>
</div>
<script>
    const contentEl = document.getElementById('module-content');
    const moduleLinks = () => document.querySelectorAll('.module-link, .open-module-link');

    function updateActiveLink(moduleName) {
        document.querySelectorAll('.module-link').forEach((link) => {
            link.classList.toggle('active', link.dataset.module === moduleName);
        });
    }

    function bindSettingLinks() {
        document.querySelectorAll('.setting-link').forEach((link) => {
            link.onclick = (event) => {
                event.preventDefault();
                document.querySelectorAll('.setting-link').forEach((item) => item.classList.remove('active'));
                link.classList.add('active');

                const titleEl = document.getElementById('settings-title');
                const groupEl = document.getElementById('settings-group');
                const descriptionEl = document.getElementById('settings-description');
                const groupTitle = link.closest('.settings-group')?.querySelector('h5')?.textContent || 'Settings';

                if (titleEl && groupEl && descriptionEl) {
                    titleEl.textContent = link.textContent.trim();
                    groupEl.textContent = groupTitle;
                    descriptionEl.innerHTML = `You selected <strong>${link.textContent.trim()}</strong>. Detailed requirements can be added to this section in the next phase.`;
                }

                const url = new URL(window.location.href);
                url.searchParams.set('module', 'Settings');
                url.searchParams.set('setting', link.dataset.setting || 'permissions-users');
                window.history.replaceState({ module: 'Settings' }, '', url.toString());
            };
        });
    }

    async function loadModule(moduleName, pushHistory = true) {
        const current = new URL(window.location.href);
        const setting = current.searchParams.get('setting');
        const settingQuery = moduleName === 'Settings' && setting ? `&setting=${encodeURIComponent(setting)}` : '';
        const url = `?module=${encodeURIComponent(moduleName)}&partial=1${settingQuery}`;
        const response = await fetch(url, { headers: { 'X-Requested-With': 'fetch' } });

        if (!response.ok) {
            throw new Error('Failed to load module details.');
        }

        contentEl.innerHTML = await response.text();
        updateActiveLink(moduleName);
        bindSettingLinks();

        if (pushHistory) {
            const path = moduleName === 'Settings' && setting
                ? `?module=${encodeURIComponent(moduleName)}&setting=${encodeURIComponent(setting)}`
                : `?module=${encodeURIComponent(moduleName)}`;
            window.history.pushState({ module: moduleName }, '', path);
        }

        bindLinks();
    }

    function bindLinks() {
        moduleLinks().forEach((link) => {
            link.onclick = async (event) => {
                event.preventDefault();
                const moduleName = link.dataset.module;
                if (!moduleName) {
                    return;
                }
                try {
                    await loadModule(moduleName, true);
                } catch (error) {
                    window.location.href = link.href;
                }
            };
        });
    }

    window.addEventListener('popstate', async (event) => {
        const moduleName = event.state?.module;
        if (moduleName) {
            try {
                await loadModule(moduleName, false);
            } catch (error) {
                window.location.href = `?module=${encodeURIComponent(moduleName)}`;
            }
        }
    });


    const userMenuToggle = document.getElementById('user-menu-toggle');
    const userMenuDropdown = document.getElementById('user-menu-dropdown');

    if (userMenuToggle && userMenuDropdown) {
        userMenuToggle.addEventListener('click', () => {
            const isOpen = userMenuDropdown.classList.toggle('show');
            userMenuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof Element)) {
                return;
            }
            if (!target.closest('.user-menu-wrapper')) {
                userMenuDropdown.classList.remove('show');
                userMenuToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    bindLinks();
    bindSettingLinks();
</script>
</body>
</html>
