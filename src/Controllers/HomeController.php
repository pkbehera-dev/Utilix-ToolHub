<?php
namespace App\Controllers;

use App\Config\App;
use App\Config\Database;
use PDO;

class HomeController {
    
    /**
     * Display the homepage with categories and tools
     */
    public function index(): void {
        $db = Database::getConnection();
        
        // Fetch active categories
        $stmtCats = $db->query("SELECT * FROM categories ORDER BY sort_order ASC");
        $categories = $stmtCats->fetchAll();
        
        // Fetch active tools with usage statistics
        $stmtTools = $db->query("
            SELECT t.*, 
                   COALESCE(s.total_views, 0) AS total_views, 
                   COALESCE(s.total_seconds, 0) AS total_seconds 
            FROM tools t 
            LEFT JOIN tool_usage_stats s ON t.id = s.tool_id 
            WHERE t.is_active = 1 
            ORDER BY t.category_id ASC, t.name ASC
        ");
        $allTools = $stmtTools->fetchAll();

        // Fetch top 6 tools by views
        $stmtViews = $db->query("
            SELECT t.*, 
                   COALESCE(s.total_views, 0) AS total_views, 
                   COALESCE(s.total_seconds, 0) AS total_seconds 
            FROM tools t 
            LEFT JOIN tool_usage_stats s ON t.id = s.tool_id 
            WHERE t.is_active = 1 
            ORDER BY total_views DESC, t.name ASC 
            LIMIT 6
        ");
        $popularByViews = $stmtViews->fetchAll();

        // Fetch top 6 tools by time spent
        $stmtTime = $db->query("
            SELECT t.*, 
                   COALESCE(s.total_views, 0) AS total_views, 
                   COALESCE(s.total_seconds, 0) AS total_seconds 
            FROM tools t 
            LEFT JOIN tool_usage_stats s ON t.id = s.tool_id 
            WHERE t.is_active = 1 
            ORDER BY total_seconds DESC, t.name ASC 
            LIMIT 6
        ");
        $popularByTime = $stmtTime->fetchAll();
        
        // Group tools by category ID for easier rendering
        $toolsByCategory = [];
        foreach ($allTools as $tool) {
            $toolsByCategory[$tool['category_id']][] = $tool;
        }

        // Fetch site-wide aggregate usage stats
        $stmtTotals = $db->query("
            SELECT SUM(COALESCE(total_views, 0)) AS global_views,
                   SUM(COALESCE(total_seconds, 0)) AS global_seconds
            FROM tool_usage_stats
        ");
        $globalStats = $stmtTotals->fetch();
        $totalViews = (int)($globalStats['global_views'] ?? 0);
        $totalSeconds = (int)($globalStats['global_seconds'] ?? 0);

        // Format global active time spent
        if ($totalSeconds < 60) {
            $formattedTotalTime = $totalSeconds . 's';
        } elseif ($totalSeconds < 3600) {
            $formattedTotalTime = round($totalSeconds / 60) . 'm';
        } else {
            $hours = floor($totalSeconds / 3600);
            $mins = round(($totalSeconds % 3600) / 60);
            if ($mins > 0) {
                $formattedTotalTime = $hours . 'h ' . $mins . 'm';
            } else {
                $formattedTotalTime = $hours . 'h';
            }
        }

        // Passing data to the view via variables
        $pageTitle = App::siteName() . ' - Online Utilities';
        $contentView = 'pages/home'; // Path relative to Views folder
        
        require __DIR__ . '/../Views/layout.php';
    }

    /**
     * Display an individual tool page
     */
    public function tool(string $slug): void {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("SELECT * FROM tools WHERE slug = :slug AND is_active = 1");
        $stmt->execute(['slug' => $slug]);
        $tool = $stmt->fetch();
        
        if (!$tool) {
            http_response_code(404);
            echo "Tool not found.";
            return;
        }
        
        // Update views counter for users (excluding admins)
        \App\Core\Security::startSession();
        $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
        if (!$isAdmin) {
            $toolId = (int)$tool['id'];
            $cookieName = 'visited_tool_' . $toolId;
            if (isset($_COOKIE[$cookieName])) {
                // Recurring view
                $db->prepare("
                    INSERT INTO tool_usage_stats (tool_id, total_views, recurring_views, total_users, total_seconds)
                    VALUES (:tool_id, 1, 1, 0, 0)
                    ON DUPLICATE KEY UPDATE 
                        total_views = total_views + 1,
                        recurring_views = recurring_views + 1
                ")->execute(['tool_id' => $toolId]);
            } else {
                // New unique user view
                setcookie($cookieName, '1', time() + (30 * 24 * 60 * 60), '/'); // 30 days
                $db->prepare("
                    INSERT INTO tool_usage_stats (tool_id, total_views, recurring_views, total_users, total_seconds)
                    VALUES (:tool_id, 1, 0, 1, 0)
                    ON DUPLICATE KEY UPDATE 
                        total_views = total_views + 1,
                        total_users = total_users + 1
                ")->execute(['tool_id' => $toolId]);
            }
        }

        $pageTitle = $tool['meta_title'] ?? ($tool['name'] . ' - ' . App::siteName());
        $metaDescription = $tool['meta_description'] ?? $tool['description'];
        
        $viewFile = 'pages/tools/' . $slug;
        if (file_exists(__DIR__ . '/../Views/' . $viewFile . '.php')) {
            $contentView = $viewFile;
        } else {
            $contentView = 'pages/tool_coming_soon';
        }
        
        $isToolPage = true;
        
        require __DIR__ . '/../Views/layout.php';
    }

    /**
     * Display the Privacy Policy page
     */
    public function privacy(): void {
        $pageTitle = 'Privacy Policy - ' . App::siteName();
        $metaDescription = 'Read the privacy policy for ' . App::siteName() . ' to understand how we handle your data.';
        $contentView = 'pages/privacy';
        require __DIR__ . '/../Views/layout.php';
    }

    /**
     * Display the About page
     */
    public function about(): void {
        $pageTitle = 'About - ' . App::siteName();
        $metaDescription = 'Learn more about ' . App::siteName() . ', an open-source personal utility project.';
        $contentView = 'pages/about';
        require __DIR__ . '/../Views/layout.php';
    }

    /**
     * Display the Terms of Service page
     */
    public function terms(): void {
        $pageTitle = 'Terms of Service - ' . App::siteName();
        $metaDescription = 'Read the terms of service for ' . App::siteName() . ' regarding usage and rules.';
        $contentView = 'pages/terms';
        require __DIR__ . '/../Views/layout.php';
    }
}

