<?php
namespace App\Controllers;

use App\Config\App;
use App\Config\Database;
use App\Core\Security;
use PDO;

class AdminController {
    
    public function __construct() {
        Security::startSession();
        
        // Basic Middleware to check if user is logged in and is admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . App::url('/login'));
            exit;
        }
    }

    /**
     * Admin Dashboard Overview
     */
    public function dashboard(): void {
        $db = Database::getConnection();
        
        // Fetch stats
        $stats = [
            'total_tools' => $db->query("SELECT COUNT(*) FROM tools")->fetchColumn(),
            'active_tools' => $db->query("SELECT COUNT(*) FROM tools WHERE is_active = 1")->fetchColumn(),
            'total_categories' => $db->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
            'total_tool_views' => $db->query("SELECT SUM(views) FROM tools")->fetchColumn() ?: 0,
            'most_popular_tool' => $db->query("SELECT name, views FROM tools ORDER BY views DESC LIMIT 1")->fetch(),
            'total_short_urls' => $db->query("SELECT COUNT(*) FROM short_urls")->fetchColumn()
        ];
        
        $totpSecret = $db->query("SELECT totp_secret FROM users WHERE id = " . intval($_SESSION['user_id']))->fetchColumn();
        $system = [
            'php_version' => phpversion(),
            'two_fa_active' => !empty($totpSecret),
            'environment' => App::env()
        ];
        
        $pageTitle = 'Dashboard - ' . App::siteName() . ' Admin';
        $contentView = 'admin/dashboard';
        
        require __DIR__ . '/../Views/admin/layout.php';
    }

    /**
     * URL Shortener Management (List, Search, Filter, Sort)
     */
    public function urls(): void {
        $db = Database::getConnection();
        
        // Search & Filter & Sort Inputs
        $search = $_GET['search'] ?? '';
        $sort = $_GET['sort'] ?? 'created_at';
        $order = $_GET['order'] ?? 'desc';
        
        // Allowed sort columns for security
        $allowedSorts = ['id', 'short_code', 'long_url', 'created_at', 'clicks'];
        if (!in_array($sort, $allowedSorts)) $sort = 'created_at';
        $order = ($order === 'asc') ? 'ASC' : 'DESC';

        // Base Query with a LEFT JOIN to get click counts from url_analytics
        $sql = "SELECT s.*, COUNT(a.id) as clicks 
                FROM short_urls s 
                LEFT JOIN url_analytics a ON s.id = a.short_url_id ";
        $params = [];

        if (!empty($search)) {
            $sql .= "WHERE s.short_code LIKE :search OR s.long_url LIKE :search OR s.alias LIKE :search ";
            $params['search'] = "%{$search}%";
        }

        $sql .= "GROUP BY s.id ORDER BY {$sort} {$order}";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $urls = $stmt->fetchAll();

        $pageTitle = 'Manage URL Shortener - Admin';
        $contentView = 'admin/urls';
        
        require __DIR__ . '/../Views/admin/layout.php';
    }

    /**
     * Mass Delete URLs
     */
    public function deleteUrls(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        // CSRF verification
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF token");
        }

        $ids = $_POST['url_ids'] ?? [];
        if (!empty($ids) && is_array($ids)) {
            $db = Database::getConnection();
            
            // Sanitize IDs to integers
            $ids = array_map('intval', $ids);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            
            $stmt = $db->prepare("DELETE FROM short_urls WHERE id IN ($placeholders)");
            $stmt->execute($ids);
        }

        header('Location: ' . App::adminUrl('/urls'));
        exit;
    }

    /**
     * Manage Tools (List and Stats)
     */
    public function tools(): void {
        $db = Database::getConnection();
        
        $stmt = $db->query("
            SELECT t.*, c.name as category_name 
            FROM tools t 
            JOIN categories c ON t.category_id = c.id 
            ORDER BY t.category_id ASC, t.name ASC
        ");
        $tools = $stmt->fetchAll();
        
        $pageTitle = 'Manage Tools - Admin';
        $contentView = 'admin/tools';
        
        require __DIR__ . '/../Views/admin/layout.php';
    }

    public function editTool(string $id): void {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM tools WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $tool = $stmt->fetch();

        if (!$tool) {
            header('Location: ' . App::adminUrl('/tools'));
            exit;
        }

        $categories = $db->query("SELECT id, name FROM categories ORDER BY sort_order ASC")->fetchAll();

        $pageTitle = 'Edit Tool - Admin';
        $contentView = 'admin/tool_edit';
        require __DIR__ . '/../Views/admin/layout.php';
    }

    public function updateTool(string $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) die("Invalid security token.");

        $name = Security::sanitize($_POST['name'] ?? '');
        $slug = Security::sanitize($_POST['slug'] ?? '');
        $description = Security::sanitize($_POST['description'] ?? '');
        $icon = Security::sanitize($_POST['icon'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($name && $slug && $categoryId) {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE tools SET name = :name, slug = :slug, description = :desc, icon = :icon, category_id = :cat, is_active = :active WHERE id = :id");
            $stmt->execute([
                'name' => $name,
                'slug' => $slug,
                'desc' => $description,
                'icon' => $icon,
                'cat' => $categoryId,
                'active' => $isActive,
                'id' => $id
            ]);
        }
        header('Location: ' . App::adminUrl('/tools'));
        exit;
    }

    /**
     * Manage Categories
     */
    public function categories(): void {
        $db = Database::getConnection();
        
        $stmt = $db->query("
            SELECT c.*, COUNT(t.id) as tool_count 
            FROM categories c 
            LEFT JOIN tools t ON c.id = t.category_id 
            GROUP BY c.id 
            ORDER BY c.sort_order ASC
        ");
        $categories = $stmt->fetchAll();
        
        $pageTitle = 'Manage Categories - Admin';
        $contentView = 'admin/categories';
        
        require __DIR__ . '/../Views/admin/layout.php';
    }

    public function editCategory(string $id): void {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $category = $stmt->fetch();

        if (!$category) {
            header('Location: ' . App::adminUrl('/categories'));
            exit;
        }

        $pageTitle = 'Edit Category - Admin';
        $contentView = 'admin/category_edit';
        require __DIR__ . '/../Views/admin/layout.php';
    }

    public function updateCategory(string $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) die("Invalid security token.");

        $name = Security::sanitize($_POST['name'] ?? '');
        $slug = Security::sanitize($_POST['slug'] ?? '');
        $icon = Security::sanitize($_POST['icon'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        if ($name && $slug) {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE categories SET name = :name, slug = :slug, icon = :icon, sort_order = :sort WHERE id = :id");
            $stmt->execute(['name' => $name, 'slug' => $slug, 'icon' => $icon, 'sort' => $sortOrder, 'id' => $id]);
        }
        header('Location: ' . App::adminUrl('/categories'));
        exit;
    }

    /**
     * Admin Settings & System Checks
     */
    public function settings(): void {
        $db = Database::getConnection();
        
        // System checks
        $userCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $safeBrowsing = !empty($_ENV['SAFE_BROWSING_API_KEY']) || !empty(getenv('SAFE_BROWSING_API_KEY'));
        
        // Check DB connection (if we reached here, it's connected, but we'll flag it)
        $dbStatus = true;

        $pageTitle = 'Settings & System Checks - Admin';
        $contentView = 'admin/settings';
        
        require __DIR__ . '/../Views/admin/layout.php';
    }

    /**
     * Update Admin Password
     */
    public function updatePassword(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("Invalid security token.");
        }

        $currentPass = $_POST['current_password'] ?? '';
        $newPass = $_POST['new_password'] ?? '';
        
        if (empty($currentPass) || empty($newPass)) {
            // Error handling could be improved with session flash messages
            header('Location: ' . App::adminUrl('/settings?error=missing_fields'));
            exit;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, password_hash FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch();

        if ($user && password_verify($currentPass, $user['password_hash'])) {
            $newHash = password_hash($newPass, PASSWORD_DEFAULT);
            $update = $db->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
            $update->execute(['hash' => $newHash, 'id' => $_SESSION['user_id']]);
            
            header('Location: ' . App::adminUrl('/settings?success=password_updated'));
        } else {
            header('Location: ' . App::adminUrl('/settings?error=invalid_password'));
        }
        exit;
    }

    /**
     * List and manage Feature Requests
     */
    public function features(): void {
        $db = Database::getConnection();
        
        $stmt = $db->query("SELECT * FROM feature_requests ORDER BY is_solved ASC, stars DESC, created_at DESC");
        $features = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pageTitle = 'Manage Feature Requests - Admin';
        $contentView = 'admin/features';
        
        require __DIR__ . '/../Views/admin/layout.php';
    }

    /**
     * Mark a feature request as solved or active
     */
    public function solveFeature(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) die("Invalid security token.");

        $id = (int)($_POST['id'] ?? 0);
        $solve = (int)($_POST['solve'] ?? 0);

        if ($id > 0) {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE feature_requests SET is_solved = :solved WHERE id = :id");
            $stmt->execute([
                'solved' => $solve,
                'id' => $id
            ]);
        }

        header('Location: ' . App::adminUrl('/features'));
        exit;
    }

    /**
     * Delete a feature request
     */
    public function deleteFeature(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) die("Invalid security token.");

        $id = (int)($_POST['id'] ?? 0);

        if ($id > 0) {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM feature_requests WHERE id = :id");
            $stmt->execute(['id' => $id]);
        }

        header('Location: ' . App::adminUrl('/features'));
        exit;
    }

    /**
     * List and manage Quote submissions
     */
    public function quotes(): void {
        $db = Database::getConnection();
        
        $category = $_GET['category'] ?? 'All';
        
        $query = "SELECT * FROM quotes ";
        $params = [];
        
        if (!empty($category) && $category !== 'All') {
            $query .= "WHERE category = :category ";
            $params['category'] = $category;
        }
        
        $query .= "ORDER BY is_approved ASC, created_at DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pageTitle = 'Manage Quotes - Admin';
        $contentView = 'admin/quotes';
        
        require __DIR__ . '/../Views/admin/layout.php';
    }

    /**
     * Toggle approval status of a quote
     */
    public function approveQuote(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) die("Invalid security token.");

        $id = (int)($_POST['id'] ?? 0);
        $approve = (int)($_POST['approve'] ?? 0);

        if ($id > 0) {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE quotes SET is_approved = :approved WHERE id = :id");
            $stmt->execute([
                'approved' => $approve,
                'id' => $id
            ]);
        }

        $category = Security::sanitize($_GET['category'] ?? 'All');
        header('Location: ' . App::adminUrl('/quotes?category=' . urlencode($category)));
        exit;
    }

    /**
     * Delete one or multiple quotes
     */
    public function deleteQuotes(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) die("Invalid security token.");

        $ids = $_POST['quote_ids'] ?? [];
        // Single delete check
        if (empty($ids) && isset($_POST['id'])) {
            $ids = [$_POST['id']];
        }

        if (!empty($ids) && is_array($ids)) {
            $db = Database::getConnection();
            $ids = array_map('intval', $ids);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            
            $stmt = $db->prepare("DELETE FROM quotes WHERE id IN ($placeholders)");
            $stmt->execute($ids);
        }

        $category = Security::sanitize($_GET['category'] ?? 'All');
        header('Location: ' . App::adminUrl('/quotes?category=' . urlencode($category)));
        exit;
    }
}


