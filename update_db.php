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

    try {
        $db->exec("CREATE TABLE IF NOT EXISTS feature_requests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_name VARCHAR(100) NOT NULL,
            details TEXT NOT NULL,
            stars INT DEFAULT 0,
            is_solved BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        echo "Ensured feature_requests table exists.\n";
    } catch (PDOException $e) {
        echo "Error creating feature_requests table: " . $e->getMessage() . "\n";
    }

    try {
        $db->exec("ALTER TABLE feature_requests ADD COLUMN is_solved BOOLEAN DEFAULT FALSE");
        echo "Ensured is_solved column exists in feature_requests table.\n";
    } catch (PDOException $e) {
        // Silently catch if column already exists
    }

    try {
        $db->exec("CREATE TABLE IF NOT EXISTS quotes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            quote_text TEXT NOT NULL,
            author VARCHAR(100) NOT NULL,
            category VARCHAR(50) NOT NULL,
            is_user_submitted BOOLEAN DEFAULT FALSE,
            submitted_by VARCHAR(100) NULL,
            is_approved BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        echo "Ensured quotes table exists.\n";

        // Upgrade column if it doesn't exist
        try {
            $db->exec("ALTER TABLE quotes ADD COLUMN is_approved BOOLEAN DEFAULT FALSE");
            echo "Ensured is_approved column exists in quotes table.\n";
            // Make sure default quotes are marked as approved
            $db->exec("UPDATE quotes SET is_approved = 1 WHERE is_user_submitted = 0");
        } catch (PDOException $e) {
            // Silently ignore
        }

        // Seed default quotes if empty
        $count = $db->query("SELECT COUNT(*) FROM quotes")->fetchColumn();
        if ($count == 0) {
            $defaultQuotes = [
                ['The only limit to our realization of tomorrow is our doubts of today.', 'Franklin D. Roosevelt', 'Motivation'],
                ['The purpose of our lives is to be happy.', 'Dalai Lama', 'Life'],
                ['Life is what happens when you are busy making other plans.', 'John Lennon', 'Life'],
                ['Get busy living or get busy dying.', 'Stephen King', 'Life'],
                ['You only live once, but if you do it right, once is enough.', 'Mae West', 'Life'],
                ['Technology is anything that wasn’t around when you were born.', 'Alan Kay', 'Technology'],
                ['Any sufficiently advanced technology is indistinguishable from magic.', 'Arthur C. Clarke', 'Technology'],
                ['The human spirit must prevail over technology.', 'Albert Einstein', 'Technology'],
                ['I have not failed. I’ve just found 10,000 ways that won’t work.', 'Thomas A. Edison', 'Motivation'],
                ['If you want to shine like sun, first burn like sun.', 'A. P. J. Abdul Kalam', 'Motivation'],
                ['The best way to predict the future is to invent it.', 'Alan Kay', 'Inspirational'],
                ['Out of difficulties grow miracles.', 'Jean de La Bruyere', 'Inspirational'],
                ['Do not take life too seriously. You will never get out of it alive.', 'Elbert Hubbard', 'Humor'],
                ['People say nothing is impossible, but I do nothing every day.', 'A. A. Milne', 'Humor']
            ];
            $stmtInsert = $db->prepare("INSERT INTO quotes (quote_text, author, category, is_approved) VALUES (?, ?, ?, 1)");
            foreach ($defaultQuotes as $q) {
                $stmtInsert->execute($q);
            }
            echo "Seeded default quotes.\n";
        }
    } catch (PDOException $e) {
        echo "Error creating/seeding quotes table: " . $e->getMessage() . "\n";
    }

    try {
        $db->exec("CREATE TABLE IF NOT EXISTS feature_votes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            feature_id INT NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            user_agent VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_vote (feature_id, ip_address, user_agent),
            FOREIGN KEY (feature_id) REFERENCES feature_requests(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        echo "Ensured feature_votes table exists.\n";
    } catch (PDOException $e) {
        echo "Error creating feature_votes table: " . $e->getMessage() . "\n";
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
            ],
            [
                'name' => 'Pomodoro Timer',
                'slug' => 'pomodoro-timer',
                'description' => 'A focus timer with customizable work and break intervals to boost productivity.',
                'icon' => 'fa-business-time',
            ],
            [
                'name' => 'Stopwatch',
                'slug' => 'stopwatch',
                'description' => 'A precision stopwatch with split time/lap tracking functionality.',
                'icon' => 'fa-stopwatch',
            ],
            [
                'name' => 'Countdown Timer',
                'slug' => 'timer',
                'description' => 'A customizable countdown timer with alarm sound and quick presets.',
                'icon' => 'fa-hourglass-half',
            ],
            [
                'name' => 'Time Calculator',
                'slug' => 'time-calculator',
                'description' => 'Add or subtract time durations and calculate intervals or differences between clock times.',
                'icon' => 'fa-calculator',
            ],
            [
                'name' => 'Network Speed Test',
                'slug' => 'network-speed-test',
                'description' => 'Measure your download speed, upload speed, latency, and jitter in real-time with an interactive gauge.',
                'icon' => 'fa-gauge-high',
            ]
        ],
        'developer-tools' => [
            [
                'name' => 'Hash Generator',
                'slug' => 'hash-generator',
                'description' => 'Generate cryptographic hashes including MD5, SHA1, and SHA256.',
                'icon' => 'fa-hashtag',
            ],
            [
                'name' => 'Text to ASCII Art',
                'slug' => 'text-to-ascii-art',
                'description' => 'Convert input text into dynamic ASCII art banners with customizable font styles.',
                'icon' => 'fa-font',
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
            ],
            [
                'name' => 'Quote Generator',
                'slug' => 'quote-generator',
                'description' => 'Generate motivational, inspirational, technology, humor, or life quotes, and submit your own.',
                'icon' => 'fa-quote-left',
            ]
        ],
        'text-tools' => [
            [
                'name' => 'Remove Duplicate Lines',
                'slug' => 'remove-duplicate-lines',
                'description' => 'Remove duplicate lines from text blocks or lists instantly.',
                'icon' => 'fa-align-left',
            ],
            [
                'name' => 'Lorem Ipsum Generator',
                'slug' => 'lorem-ipsum-generator',
                'description' => 'Generate custom dummy or placeholder text by paragraphs, sentences, words, or lists.',
                'icon' => 'fa-file-lines',
            ],
            [
                'name' => 'Text Diff Checker',
                'slug' => 'text-diff-checker',
                'description' => 'Compare two text blocks side-by-side or inline to highlight additions, deletions, and differences.',
                'icon' => 'fa-binoculars',
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
                $metaTitle = $t['name'] . ' - UtiliX';
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

    // 4. Update branding references in DB (ToolBox -> UtiliX)
    $db->exec("UPDATE site_settings SET setting_value = 'UtiliX' WHERE setting_key = 'site_name' AND setting_value = 'ToolBox'");
    $db->exec("UPDATE tools SET meta_title = REPLACE(meta_title, ' - ToolBox', ' - UtiliX')");
    echo "Updated database branding references (ToolBox -> UtiliX).\n";

    echo "\nDatabase updated successfully.\n";

} catch (Exception $e) {
    die("Error updating database: " . $e->getMessage());
}
