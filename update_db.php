<?php
require __DIR__ . '/src/Config/Database.php';

try {
    // Load Environment Variables
    require __DIR__ . '/src/Core/DotEnv.php';
    \App\Core\DotEnv::load(__DIR__ . '/.env');
    
    $db = \App\Config\Database::getConnection();

    // 1. Database Table & Column Alterations
    try {
        $db->exec("ALTER TABLE short_urls ADD COLUMN alias VARCHAR(50) UNIQUE NULL AFTER short_code");
        echo "Ensured alias column exists in short_urls table.\n";
    } catch (PDOException $e) {
        // Silently catch if column already exists
    }

    // 2. Clean up retired tools
    $retiredSlugs = ['percentage-calculator', 'discount-calculator', 'slug-generator', 'uuid-generator'];
    $placeholders = implode(',', array_fill(0, count($retiredSlugs), '?'));
    $stmtDel = $db->prepare("DELETE FROM tools WHERE slug IN ($placeholders)");
    $stmtDel->execute($retiredSlugs);
    echo "Removed retired tools from database.\n";

    // 3. Define and Register New Tools
    $newTools = [
        'utilities' => [
            [
                'name' => 'Random Number Generator',
                'slug' => 'random-number-generator',
                'description' => 'Generate a random number within a chosen range with customizable animations.',
                'icon' => 'fa-dice',
            ]
        ],
        'developer-tools' => [
            [
                'name' => 'Hash Generator',
                'slug' => 'hash-generator',
                'description' => 'Generate cryptographic hashes including MD5, SHA1, and SHA256.',
                'icon' => 'fa-hashtag',
            ]
        ],
        'calculators' => [
            [
                'name' => 'Age Calculator',
                'slug' => 'age-calculator',
                'description' => 'Calculate your exact age in years, months, and days with breakdown stats.',
                'icon' => 'fa-cake-candles',
            ],
            [
                'name' => 'GST Calculator',
                'slug' => 'gst-calculator',
                'description' => 'Calculate Goods and Services Tax (GST) adding or removing values with CGST/SGST splits.',
                'icon' => 'fa-calculator',
            ],
            [
                'name' => 'BMI Calculator',
                'slug' => 'bmi-calculator',
                'description' => 'Calculate Body Mass Index (BMI) and determine healthy weight classifications.',
                'icon' => 'fa-heart-pulse',
            ]
        ],
        'fun-tools' => [
            [
                'name' => 'Coin Flip',
                'slug' => 'coin-flip',
                'description' => 'Flip a virtual coin to make random binary decisions.',
                'icon' => 'fa-circle-dot',
            ],
            [
                'name' => 'Dice Roller',
                'slug' => 'dice-roller',
                'description' => 'Roll one or multiple virtual 3D dice for games and decisions.',
                'icon' => 'fa-dice',
            ]
        ],
        'text-tools' => [
            [
                'name' => 'Remove Duplicate Lines',
                'slug' => 'remove-duplicate-lines',
                'description' => 'Remove duplicate lines from text blocks or lists instantly.',
                'icon' => 'fa-align-left',
            ]
        ]
    ];

    $stmtCat = $db->prepare("SELECT id FROM categories WHERE slug = ?");
    $stmtTool = $db->prepare("INSERT IGNORE INTO tools (category_id, name, slug, description, icon, meta_title, meta_description) VALUES (?, ?, ?, ?, ?, ?, ?)");

    foreach ($newTools as $catSlug => $tools) {
        $stmtCat->execute([$catSlug]);
        $catId = $stmtCat->fetchColumn();
        
        if ($catId) {
            foreach ($tools as $t) {
                $metaTitle = $t['name'] . ' - ToolBox';
                $metaDesc = $t['description'];
                
                $stmtTool->execute([
                    $catId,
                    $t['name'],
                    $t['slug'],
                    $t['description'],
                    $t['icon'],
                    $metaTitle,
                    $metaDesc
                ]);
                echo "Registered tool: {$t['name']} under category '{$catSlug}'.\n";
            }
        }
    }

    echo "\nDatabase updated successfully.\n";

} catch (Exception $e) {
    die("Error updating database: " . $e->getMessage());
}
