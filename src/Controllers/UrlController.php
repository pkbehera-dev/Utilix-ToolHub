<?php
namespace App\Controllers;

use App\Config\App;
use App\Config\Database;
use App\Core\Security;
use PDO;

class UrlController {
    
    /**
     * API Endpoint to shorten a URL
     */
    public function shorten(): void {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            return;
        }

        // Verify CSRF
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'message' => 'Invalid security token.']);
            return;
        }

        // Rate Limiting check (max 10 requests / minute)
        if (!\App\Core\RateLimiter::check('url_shorten', 10, 1)) {
            echo json_encode(['success' => false, 'message' => 'Rate limit exceeded. Please try again later.']);
            return;
        }

        $longUrl = filter_var($_POST['long_url'] ?? '', FILTER_VALIDATE_URL);
        $alias = Security::sanitize($_POST['alias'] ?? '');

        if (!$longUrl) {
            echo json_encode(['success' => false, 'message' => 'Please provide a valid URL.']);
            return;
        }

        // Google Safe Browsing Check
        $apiKey = $_ENV['SAFE_BROWSING_API_KEY'] ?? getenv('SAFE_BROWSING_API_KEY') ?: '';
        if (!empty($apiKey)) {
            $isSafe = $this->checkSafeBrowsing($longUrl, $apiKey);
            if (!$isSafe) {
                echo json_encode(['success' => false, 'message' => 'This URL has been flagged as unsafe (malware/phishing) and cannot be shortened.']);
                return;
            }
        }

        try {
            $db = Database::getConnection();

            // If custom alias provided, check if it exists
            $shortCode = '';
            if (!empty($alias)) {
                if (!preg_match('/^[a-zA-Z0-9-]+$/', $alias)) {
                    echo json_encode(['success' => false, 'message' => 'Alias can only contain letters, numbers, and dashes.']);
                    return;
                }
                
                $stmt = $db->prepare("SELECT id FROM short_urls WHERE short_code = :alias_code OR alias = :alias_val");
                $stmt->execute(['alias_code' => $alias, 'alias_val' => $alias]);
                if ($stmt->fetch()) {
                    echo json_encode(['success' => false, 'message' => 'That alias is already taken.']);
                    return;
                }
                $shortCode = $alias;
                $aliasValue = $alias; // Store as alias
            } else {
                // Generate a random 6-character short code
                $shortCode = $this->generateUniqueCode($db);
                $aliasValue = null;
            }

            Security::startSession();
            $userId = $_SESSION['user_id'] ?? null;

            $stmt = $db->prepare("INSERT INTO short_urls (user_id, long_url, short_code, alias) VALUES (:user_id, :long_url, :short_code, :alias)");
            $inserted = $stmt->execute([
                'user_id' => $userId,
                'long_url' => $longUrl,
                'short_code' => $shortCode,
                'alias' => $aliasValue
            ]);

            if ($inserted) {
                echo json_encode([
                    'success' => true,
                    'short_url' => App::url('/' . $shortCode)
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to generate short URL.']);
            }
        } catch (\PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle Redirecting short codes to long URLs
     */
    public function redirect(string $shortCode): void {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("SELECT id, long_url FROM short_urls WHERE short_code = :code_short OR alias = :code_alias");
        $stmt->execute(['code_short' => $shortCode, 'code_alias' => $shortCode]);
        $url = $stmt->fetch();

        if ($url) {
            // Record analytics
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
            $referrer = $_SERVER['HTTP_REFERER'] ?? null;
            
            $stmtLog = $db->prepare("INSERT INTO url_analytics (short_url_id, ip_address, user_agent, referrer) VALUES (:id, :ip, :ua, :ref)");
            $stmtLog->execute([
                'id' => $url['id'],
                'ip' => $ip,
                'ua' => $userAgent,
                'ref' => $referrer
            ]);

            // Perform redirect
            header("Location: " . $url['long_url'], true, 301);
            exit;
        } else {
            http_response_code(404);
            $pageTitle = 'Link Not Found - ToolBox';
            $metaDescription = 'The short URL you are trying to visit is wrong or has been removed.';
            $contentView = 'pages/url_not_found';
            require __DIR__ . '/../Views/layout.php';
            exit;
        }
    }

    private function generateUniqueCode(PDO $db, int $length = 6): string {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($characters) - 1;
        
        while (true) {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[random_int(0, $max)];
            }
            
            $stmt = $db->prepare("SELECT id, long_url FROM short_urls WHERE short_code = :code_short OR alias = :code_alias");
            $stmt->execute(['code_short' => $code, 'code_alias' => $code]);
            if (!$stmt->fetch()) {
                return $code;
            }
        }
    }

    /**
     * Check if a URL is safe using Google Safe Browsing API
     * Returns true if safe, false if malware/phishing
     */
    private function checkSafeBrowsing(string $url, string $apiKey): bool {
        $apiUrl = "https://safebrowsing.googleapis.com/v4/threatMatches:find?key=" . $apiKey;
        
        $payload = json_encode([
            "client" => [
                "clientId" => "toolbox-app",
                "clientVersion" => "1.0.0"
            ],
            "threatInfo" => [
                "threatTypes" => ["MALWARE", "SOCIAL_ENGINEERING", "UNWANTED_SOFTWARE", "POTENTIALLY_HARMFUL_APPLICATION"],
                "platformTypes" => ["ANY_PLATFORM"],
                "threatEntryTypes" => ["URL"],
                "threatEntries" => [
                    ["url" => $url]
                ]
            ]
        ]);
        
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response) {
            $data = json_decode($response, true);
            // If matches array exists, a threat was found
            if (!empty($data['matches'])) {
                return false;
            }
        }
        
        return true;
    }
}

