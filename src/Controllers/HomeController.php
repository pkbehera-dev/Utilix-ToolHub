<?php
namespace App\Controllers;

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
        
        // Fetch active tools
        $stmtTools = $db->query("SELECT * FROM tools WHERE is_active = 1 ORDER BY category_id ASC, name ASC");
        $allTools = $stmtTools->fetchAll();
        
        // Group tools by category ID for easier rendering
        $toolsByCategory = [];
        foreach ($allTools as $tool) {
            $toolsByCategory[$tool['category_id']][] = $tool;
        }

        // Passing data to the view via variables
        $pageTitle = 'ToolBox - Online Utilities';
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
        
        // Update views counter (optional, can be done asynchronously for performance)
        $db->prepare("UPDATE tools SET views = views + 1 WHERE id = :id")->execute(['id' => $tool['id']]);

        $pageTitle = $tool['meta_title'] ?? ($tool['name'] . ' - ToolBox');
        $metaDescription = $tool['meta_description'] ?? $tool['description'];
        
        $viewFile = 'pages/tools/' . $slug;
        if (file_exists(__DIR__ . '/../Views/' . $viewFile . '.php')) {
            $contentView = $viewFile;
        } else {
            $contentView = 'pages/tool_coming_soon';
        }
        
        require __DIR__ . '/../Views/layout.php';
    }

    /**
     * Display the Privacy Policy page
     */
    public function privacy(): void {
        $pageTitle = 'Privacy Policy - ToolBox';
        $metaDescription = 'Read the privacy policy for ToolBox to understand how we handle your data.';
        $contentView = 'pages/privacy';
        require __DIR__ . '/../Views/layout.php';
    }

    /**
     * Display the About page
     */
    public function about(): void {
        $pageTitle = 'About - ToolBox';
        $metaDescription = 'Learn more about ToolBox, an open-source personal utility project.';
        $contentView = 'pages/about';
        require __DIR__ . '/../Views/layout.php';
    }

    /**
     * Display the Terms of Service page
     */
    public function terms(): void {
        $pageTitle = 'Terms of Service - ToolBox';
        $metaDescription = 'Read the terms of service for ToolBox regarding usage and rules.';
        $contentView = 'pages/terms';
        require __DIR__ . '/../Views/layout.php';
    }
}

