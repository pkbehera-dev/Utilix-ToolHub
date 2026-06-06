<?php
use App\Config\App;
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin - ' . App::siteName()) ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/svg+xml" href="<?= App::url('assets/favicon.svg') ?>">
    <link rel="stylesheet" href="<?= App::url('assets/css/style.css') ?>?v=<?= App::VERSION ?>">
    <style>
        /* Admin specific styles */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: var(--card-bg);
            border-right: 1px solid var(--border-color);
            padding: 20px 0;
        }
        .sidebar-brand {
            padding: 0 20px 20px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 20px;
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        .sidebar-nav ul {
            list-style: none;
        }
        .sidebar-nav li a {
            display: block;
            padding: 10px 20px;
            color: var(--text-main);
            text-decoration: none;
            transition: background 0.2s;
        }
        .sidebar-nav li a:hover {
            background: var(--bg-color);
            color: var(--primary-color);
        }
        .sidebar-nav li a i {
            margin-right: 10px;
            width: 20px;
        }
        .admin-content {
            flex: 1;
            padding: 20px 40px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        /* Responsive Admin Layout */
        @media (max-width: 768px) {
            .admin-layout {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
                padding: 10px 0;
            }
            .sidebar-brand {
                padding: 0 20px 10px;
                margin-bottom: 10px;
            }
            .sidebar-nav ul {
                display: flex;
                flex-wrap: wrap;
            }
            .sidebar-nav li {
                flex: 1 1 50%;
            }
            .admin-content {
                padding: 15px;
            }
            .admin-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <i class="fa-solid fa-toolbox"></i> <?= App::siteName() ?> Admin
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="<?= App::adminUrl('/dashboard') ?>"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
                    <li><a href="<?= App::adminUrl('/tools') ?>"><i class="fa-solid fa-wrench"></i> Manage Tools</a></li>
                    <li><a href="<?= App::adminUrl('/categories') ?>"><i class="fa-solid fa-tags"></i> Categories</a></li>
                    <li><a href="<?= App::adminUrl('/urls') ?>"><i class="fa-solid fa-link"></i> URL Shortener</a></li>
                    <li><a href="<?= App::adminUrl('/features') ?>"><i class="fa-solid fa-lightbulb"></i> Feature Requests</a></li>
                    <li><a href="<?= App::adminUrl('/quotes') ?>"><i class="fa-solid fa-quote-left"></i> Manage Quotes</a></li>
                    <li><a href="<?= App::adminUrl('/settings') ?>"><i class="fa-solid fa-gear"></i> Settings</a></li>
                    <li><a href="<?= App::url('/logout') ?>"><i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
                    <li><a href="<?= App::url('/') ?>" target="_blank"><i class="fa-solid fa-external-link-alt"></i> Visit Site</a></li>
                </ul>
            </nav>
        </aside>

        <main class="admin-content">
            <header class="admin-header">
                <h2><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h2>
                <div class="user-info">
                    Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>
                </div>
            </header>
            
            <?php 
                if (isset($contentView) && file_exists(__DIR__ . '/../' . $contentView . '.php')) {
                    require __DIR__ . '/../' . $contentView . '.php';
                } else {
                    echo "<p>View not found.</p>";
                }
            ?>
        </main>
    </div>

</body>
</html>
