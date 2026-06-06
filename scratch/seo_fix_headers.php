<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SEO Heading Fixer</title>
    <style>
        body { font-family: sans-serif; padding: 2rem; background: #fafafa; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #eee; }
        h1 { color: #3b82f6; font-size: 1.5rem; margin-top: 0; }
        .log-item { padding: 0.5rem; border-bottom: 1px solid #f0f0f0; font-family: monospace; font-size: 0.9rem; }
        .success-summary { margin-top: 1.5rem; padding: 1rem; background: #ecfdf5; border: 1px solid #10b981; color: #065f46; border-radius: 6px; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h1>SEO Heading Upgrade Log (H2 &rarr; H1)</h1>
    <?php
    $toolsDir = __DIR__ . '/../src/Views/pages/tools';
    if (!is_dir($toolsDir)) {
        echo "<div class='log-item' style='color: red;'>Error: Tools directory not found at $toolsDir</div>";
        exit;
    }

    $files = glob($toolsDir . '/*.php');
    $count = 0;

    foreach ($files as $file) {
        $content = file_get_contents($file);
        
        // Convert first occurrence of <h2>/</h2> to <h1>/</h1>
        $newContent = preg_replace('/<h2>/', '<h1>', $content, 1);
        $newContent = preg_replace('/<\/h2>/', '</h1>', $newContent, 1);
        
        if ($newContent !== $content) {
            file_put_contents($file, $newContent);
            echo "<div class='log-item'>&check; Updated: " . htmlspecialchars(basename($file)) . "</div>";
            $count++;
        }
    }
    ?>
    <div class="success-summary">
        Successfully upgraded <?php echo $count; ?> template files!
    </div>
</div>
</body>
</html>
