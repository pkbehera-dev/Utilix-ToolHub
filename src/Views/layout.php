<?php
if (!defined('SECURE_ACCESS')) {
    http_response_code(403);
    die('Direct access not allowed.');
}
use App\Config\App;
use App\Config\Database;

// Fetch all active tools for the command palette along with their category slugs
$db = Database::getConnection();
$stmtTools = $db->query("
    SELECT t.name, t.slug, t.icon, t.description, c.slug as category_slug 
    FROM tools t
    JOIN categories c ON t.category_id = c.id
    WHERE t.is_active = 1 
    ORDER BY t.name ASC
");
$globalTools = $stmtTools->fetchAll();

// Fetch all categories for filter buttons
$stmtCatsList = $db->query("SELECT name, slug FROM categories ORDER BY sort_order ASC");
$globalCategories = $stmtCatsList->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? App::siteName()) ?></title>
    
    <!-- Meta tags for SEO -->
    <meta name="description" content="<?= htmlspecialchars($metaDescription ?? 'Your digital utility belt, refined. Fast, free, and secure online tools.') ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= htmlspecialchars(App::url($_SERVER['REQUEST_URI'] ?? '')) ?>">
    
    <!-- Open Graph Metadata -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? App::siteName()) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription ?? 'Your digital utility belt, refined. Fast, free, and secure online tools.') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars(App::url($_SERVER['REQUEST_URI'] ?? '')) ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars(App::siteName()) ?>">

    <!-- Structured JSON-LD Schema for Google Crawler -->
    <?php if (isset($isToolPage) && $isToolPage && isset($tool) && !empty($tool['slug'])): ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebApplication",
        "name": <?= json_encode($tool['name']) ?>,
        "url": <?= json_encode(App::url('/tool/' . $tool['slug'])) ?>,
        "description": <?= json_encode($tool['meta_description'] ?? $tool['description']) ?>,
        "applicationCategory": "UtilityApplication",
        "operatingSystem": "All",
        "browserRequirements": "Requires JavaScript. Requires HTML5."
    }
    </script>
    <?php else: ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": <?= json_encode(App::siteName()) ?>,
        "url": <?= json_encode(App::url('/')) ?>,
        "description": "Your digital utility belt, refined. Fast, free, and secure online tools."
    }
    </script>
    <?php endif; ?>

    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/svg+xml" href="<?= App::url('assets/favicon.svg') ?>">
    
    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?= App::url('assets/css/style.css') ?>?v=<?= App::VERSION ?> ">
</head>
<body>

    <!-- Header -->
    <header>
        <div class="container justify-between flex items-center">
            <a href="<?= App::url('/') ?>" class="logo">
                <i class="fa-solid fa-layer-group"></i> <?= App::siteName() ?>
            </a>
            
            <!-- Global Search / Command Palette Trigger -->
            <button class="header-search" id="header-search-btn" aria-label="Search tools">
                <i class="fa-solid fa-search"></i>
                Search for tools...
                <span class="shortcut">Ctrl K</span>
            </button>
            
            <div class="flex items-center gap-4">
                <!-- Live Clock & Date Widget -->
                <div class="header-clock" id="header-clock">
                    <i class="fa-regular fa-clock"></i>
                    <span id="clock-time">00:00:00</span>
                    <span class="clock-divider">|</span>
                    <span id="clock-date">Jan 01, 2026</span>
                </div>



                <button class="btn-icon" id="theme-toggle-btn" aria-label="Toggle Theme">
                    <i class="fa-solid fa-moon"></i>
                </button>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= App::adminUrl('/dashboard') ?>" class="btn btn-secondary">Dashboard</a>
                <?php else: ?>
                    <a href="<?= App::url('/login') ?>" class="btn btn-secondary">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content Injection -->
    <main class="container">
        <?php 
            if (isset($contentView) && file_exists(__DIR__ . '/' . $contentView . '.php')) {
                require __DIR__ . '/' . $contentView . '.php';
            } else {
                echo "<p class='text-center'>View not found.</p>";
            }
        ?>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container footer-inner">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-layer-group" style="color: var(--color-primary)"></i>
                <span class="font-semibold text-text-primary"><?= App::siteName() ?></span> &copy; <?= date('Y') ?>
            </div>
            
            <div class="footer-links">
                <a href="<?= App::url('/features') ?>">Community Features</a>
                <a href="<?= App::url('/about') ?>">About</a>
                <a href="<?= App::url('/privacy') ?>">Privacy Policy</a>
                <a href="<?= App::url('/terms') ?>">Terms of Service</a>
            </div>
        </div>
    </footer>

    <!-- Command Palette Overlay -->
    <div class="cmd-palette-backdrop" id="cmd-palette-backdrop">
        <div class="cmd-palette">
            <div class="cmd-input-wrap">
                <i class="fa-solid fa-search"></i>
                <input type="text" id="cmd-input" class="cmd-input" placeholder="Search for tools or commands..." autocomplete="off">
            </div>
            
            <!-- Category Filters Inside Search Overlay -->
            <div class="cmd-categories">
                <button class="cmd-cat-btn active" data-category="all">All Categories</button>
                <?php foreach ($globalCategories as $gCat): ?>
                    <button class="cmd-cat-btn" data-category="<?= htmlspecialchars($gCat['slug']) ?>">
                        <?= htmlspecialchars($gCat['name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="cmd-results" id="cmd-results">
                <?php foreach ($globalTools as $gTool): ?>
                    <a href="<?= App::url('/tool/' . $gTool['slug']) ?>" class="cmd-item" data-category-slug="<?= htmlspecialchars($gTool['category_slug']) ?>">
                        <i class="fa-solid <?= htmlspecialchars($gTool['icon'] ?? 'fa-cube') ?>"></i>
                        <div>
                            <div class="font-medium text-sm"><?= htmlspecialchars($gTool['name']) ?></div>
                            <div class="text-xs text-muted"><?= htmlspecialchars(substr($gTool['description'], 0, 50)) ?>...</div>
                        </div>
                    </a>
                <?php endforeach; ?>
                <a href="<?= App::adminUrl('/dashboard') ?>" class="cmd-item" data-category-slug="admin">
                    <i class="fa-solid fa-gauge"></i>
                    <div>
                        <div class="font-medium text-sm">Admin Dashboard</div>
                        <div class="text-xs text-muted">Manage site settings and users</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Search Floating Action Button -->
    <button class="mobile-search-fab" id="mobile-search-fab" aria-label="Search tools">
        <i class="fa-solid fa-search"></i>
    </button>



    <!-- Scripts -->
    <script src="<?= App::url('assets/js/app.js') ?>?v=<?= App::VERSION ?>"></script>

    <?php if (isset($isToolPage) && $isToolPage && isset($tool) && !empty($tool['slug']) && !(isset($_SESSION['role']) && $_SESSION['role'] === 'admin')): ?>
    <script>
    (function() {
        const toolSlug = <?= json_encode($tool['slug']) ?>;
        let activeTime = 0;
        let lastActive = performance.now();
        let isFocus = true;
        let idleTimer = null;
        const idleTimeout = 60000; // 60 seconds

        function trackActiveTime() {
            if (isFocus) {
                const now = performance.now();
                activeTime += (now - lastActive) / 1000;
                lastActive = now;
            }
        }

        function resetIdleTimer() {
            if (!isFocus) {
                lastActive = performance.now();
                isFocus = true;
            }
            clearTimeout(idleTimer);
            idleTimer = setTimeout(() => {
                trackActiveTime();
                isFocus = false;
            }, idleTimeout);
        }

        window.addEventListener('focus', () => {
            lastActive = performance.now();
            isFocus = true;
            resetIdleTimer();
        });

        window.addEventListener('blur', () => {
            trackActiveTime();
            isFocus = false;
        });

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                trackActiveTime();
                isFocus = false;
                sendStats();
            } else {
                lastActive = performance.now();
                isFocus = true;
                resetIdleTimer();
            }
        });

        const activityEvents = ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'];
        activityEvents.forEach(evt => {
            document.addEventListener(evt, resetIdleTimer, { passive: true });
        });

        function sendStats() {
            trackActiveTime();
            const roundedSeconds = Math.round(activeTime);
            if (roundedSeconds > 0) {
                const formData = new FormData();
                formData.append('tool_slug', toolSlug);
                formData.append('seconds', roundedSeconds);
                
                if (navigator.sendBeacon) {
                    navigator.sendBeacon('<?= \App\Config\App::url('/api/stats/track-time') ?>', formData);
                } else {
                    fetch('<?= \App\Config\App::url('/api/stats/track-time') ?>', {
                        method: 'POST',
                        body: formData,
                        keepalive: true
                    });
                }
                activeTime = 0;
            }
        }



        // Periodically sync time stats every 10 seconds
        setInterval(sendStats, 10000);

        window.addEventListener('pagehide', sendStats);
        resetIdleTimer();
    })();
    </script>
    <?php endif; ?>
</body>
</html>
