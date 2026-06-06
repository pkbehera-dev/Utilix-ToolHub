<?php
require __DIR__ . '/src/Config/Database.php';

try {
    // Load Environment Variables
    require __DIR__ . '/src/Core/DotEnv.php';
    \App\Core\DotEnv::load(__DIR__ . '/.env');
    
    $db = \App\Config\Database::getConnection();

    try {
        // Find Category ID for utilities
        $stmtCat = $db->prepare("SELECT id FROM categories WHERE slug = ?");
        $stmtCat->execute(['utilities']);
        $catId = $stmtCat->fetchColumn();

        if ($catId) {
            $stmtTool = $db->prepare("INSERT IGNORE INTO tools (category_id, name, slug, description, icon, meta_title, meta_description) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtTool->execute([
                $catId,
                'Unit Converter',
                'unit-converter',
                'Convert between various measurement units including length, weight, temperature, area, and volume instantly.',
                'fa-scale-balanced',
                'Unit Converter - UtiliX',
                'Convert between various measurement units including length, weight, temperature, area, and volume instantly.'
            ]);
            echo "Registered tool: Unit Converter under category 'utilities'.\n";
        } else {
            echo "Category 'utilities' not found.\n";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage() . "\n";
    }

    try {
        // Create tool_usage_stats table
        $db->exec("CREATE TABLE IF NOT EXISTS tool_usage_stats (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tool_id INT NOT NULL,
            total_views INT DEFAULT 0,
            recurring_views INT DEFAULT 0,
            total_users INT DEFAULT 0,
            total_seconds BIGINT DEFAULT 0,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (tool_id) REFERENCES tools(id) ON DELETE CASCADE,
            UNIQUE KEY unique_tool (tool_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        echo "Ensured tool_usage_stats table exists.\n";

        // Migrate views from tools table to tool_usage_stats table if stats table is empty
        $countStats = $db->query("SELECT COUNT(*) FROM tool_usage_stats")->fetchColumn();
        if ($countStats == 0) {
            $db->exec("INSERT INTO tool_usage_stats (tool_id, total_views, total_users) 
                       SELECT id, views, views FROM tools WHERE views > 0");
            echo "Migrated historical view counts from tools to tool_usage_stats.\n";
        }
    } catch (PDOException $e) {
        echo "Database migration error: " . $e->getMessage() . "\n";
    }

    echo "Database migration checked. No pending updates.\n";

} catch (Exception $e) {
    die("Error updating database: " . $e->getMessage());
}
