<?php
require __DIR__ . '/src/Config/Database.php';

try {
    // Load Environment Variables
    require __DIR__ . '/src/Core/DotEnv.php';
    \App\Core\DotEnv::load(__DIR__ . '/.env');
    
    $db = \App\Config\Database::getConnection();

    // Add database update queries here
    try {
        // Find Category ID for text-tools
        $stmtCat = $db->prepare("SELECT id FROM categories WHERE slug = ?");
        $stmtCat->execute(['text-tools']);
        $catId = $stmtCat->fetchColumn();

        if ($catId) {
            $stmtTool = $db->prepare("INSERT IGNORE INTO tools (category_id, name, slug, description, icon, meta_title, meta_description) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtTool->execute([
                $catId,
                'Text Editor',
                'text-editor',
                'A fully-featured rich text and plain text editor with HTML export, local auto-save, formatting tools, and real-time word/character count.',
                'fa-file-signature',
                'Online Text Editor - UtiliX',
                'Write, edit, format, and download your text or HTML documents online with our free fully-featured rich text editor.'
            ]);
            echo "Registered tool: Text Editor under category 'text-tools'.\n";
        } else {
            echo "Category 'text-tools' not found.\n";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage() . "\n";
    }

    try {
        // Find Category ID for developer-tools
        $stmtCat = $db->prepare("SELECT id FROM categories WHERE slug = ?");
        $stmtCat->execute(['developer-tools']);
        $catId = $stmtCat->fetchColumn();

        if ($catId) {
            $stmtTool = $db->prepare("INSERT IGNORE INTO tools (category_id, name, slug, description, icon, meta_title, meta_description) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtTool->execute([
                $catId,
                'Password Hasher',
                'password-hasher',
                'Securely hash and verify passwords using Bcrypt, Argon2id, or Argon2i algorithms with custom parameters.',
                'fa-key',
                'Bcrypt & Argon2 Password Hasher - UtiliX',
                'Securely hash your passwords using modern cryptographic algorithms including Bcrypt, Argon2id, and Argon2i with custom parameters.'
            ]);
            echo "Registered tool: Password Hasher under category 'developer-tools'.\n";
        } else {
            echo "Category 'developer-tools' not found.\n";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage() . "\n";
    }

    echo "Database migration checked. No pending updates.\n";

} catch (Exception $e) {
    die("Error updating database: " . $e->getMessage());
}
