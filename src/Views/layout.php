<?php
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
    
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/svg+xml" href="<?= App::url('assets/favicon.svg') ?>">
    
    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?= App::url('assets/css/style.css') ?>?v=<?= App::VERSION ?>">
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
</body>
</html>
