<?php
namespace App\Controllers;

use App\Config\App;
use App\Config\Database;
use App\Core\Security;
use PDO;

class FeatureController {
    
    /**
     * Display the feature requests page
     */
    public function index(): void {
        Security::startSession();
        $db = Database::getConnection();

        // Fetch active feature requests ranked by stars (highest stars first)
        $stmtActive = $db->query("SELECT * FROM feature_requests WHERE is_solved = 0 ORDER BY stars DESC, created_at DESC");
        $activeRequests = $stmtActive->fetchAll(PDO::FETCH_ASSOC);

        // Fetch solved feature requests ranked by stars
        $stmtSolved = $db->query("SELECT * FROM feature_requests WHERE is_solved = 1 ORDER BY stars DESC, created_at DESC");
        $solvedRequests = $stmtSolved->fetchAll(PDO::FETCH_ASSOC);

        // Get starred features list for this IP and User Agent from DB
        $ip = \App\Core\RateLimiter::getIpAddress();
        $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

        $stmtUserVotes = $db->prepare("SELECT feature_id FROM feature_votes WHERE ip_address = :ip AND user_agent = :ua");
        $stmtUserVotes->execute(['ip' => $ip, 'ua' => $ua]);
        $dbStarred = $stmtUserVotes->fetchAll(PDO::FETCH_COLUMN);

        // Merge with session starred list for maximum robustness
        $sessionStarred = $_SESSION['starred_features'] ?? [];
        $starredFeatures = array_unique(array_merge($dbStarred, $sessionStarred));

        $pageTitle = 'Community Feature Requests - ' . App::siteName();
        $metaDescription = 'Suggest new tools or features and upvote requests from the community.';
        $contentView = 'pages/features';
        
        require __DIR__ . '/../Views/layout.php';
    }

    /**
     * Handle submitting a new feature request
     */
    public function add(): void {
        Security::startSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . App::url('/features'));
            exit;
        }

        // Verify CSRF
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error_message'] = 'Security token validation failed. Please try again.';
            header('Location: ' . App::url('/features'));
            exit;
        }

        $userName = Security::sanitize($_POST['user_name'] ?? '');
        $details = Security::sanitize($_POST['details'] ?? '');

        if (empty($userName) || empty($details)) {
            $_SESSION['error_message'] = 'Both user name and feature details are required.';
            header('Location: ' . App::url('/features'));
            exit;
        }

        if (strlen($userName) > 100) {
            $_SESSION['error_message'] = 'User name cannot exceed 100 characters.';
            header('Location: ' . App::url('/features'));
            exit;
        }

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO feature_requests (user_name, details, stars) VALUES (:user_name, :details, 0)");
            $stmt->execute([
                'user_name' => $userName,
                'details' => $details
            ]);

            $_SESSION['success_message'] = 'Feature request submitted successfully!';
        } catch (\PDOException $e) {
            $_SESSION['error_message'] = 'Database error: ' . $e->getMessage();
        }

        header('Location: ' . App::url('/features'));
        exit;
    }

    /**
     * Handle upvoting (starring) a feature request via AJAX / Fetch API
     */
    public function star(): void {
        header('Content-Type: application/json');
        Security::startSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            return;
        }

        // Verify CSRF
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'message' => 'Security token verification failed.']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid request ID.']);
            return;
        }

        // Initialize starred_features array in session
        if (!isset($_SESSION['starred_features'])) {
            $_SESSION['starred_features'] = [];
        }

        // Check session fallback first
        if (in_array($id, $_SESSION['starred_features'])) {
            echo json_encode(['success' => false, 'message' => 'You have already upvoted this feature request.']);
            return;
        }

        $ip = \App\Core\RateLimiter::getIpAddress();
        $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

        try {
            $db = Database::getConnection();

            // Check database to see if this IP + User Agent has already voted for this request
            $stmtCheckVote = $db->prepare("SELECT id FROM feature_votes WHERE feature_id = :feature_id AND ip_address = :ip AND user_agent = :ua");
            $stmtCheckVote->execute([
                'feature_id' => $id,
                'ip' => $ip,
                'ua' => $ua
            ]);

            if ($stmtCheckVote->fetch()) {
                // Also save to session for future calls
                if (!in_array($id, $_SESSION['starred_features'])) {
                    $_SESSION['starred_features'][] = $id;
                }
                echo json_encode(['success' => false, 'message' => 'You have already upvoted this feature request.']);
                return;
            }
            
            // Check if request exists
            $stmtCheck = $db->prepare("SELECT id, stars FROM feature_requests WHERE id = :id");
            $stmtCheck->execute(['id' => $id]);
            $request = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$request) {
                echo json_encode(['success' => false, 'message' => 'Feature request not found.']);
                return;
            }

            // Start transaction
            $db->beginTransaction();

            // Insert vote record
            $stmtInsertVote = $db->prepare("INSERT INTO feature_votes (feature_id, ip_address, user_agent) VALUES (:feature_id, :ip, :ua)");
            $stmtInsertVote->execute([
                'feature_id' => $id,
                'ip' => $ip,
                'ua' => $ua
            ]);

            // Increment stars
            $newStars = (int)$request['stars'] + 1;
            $stmtUpdate = $db->prepare("UPDATE feature_requests SET stars = :stars WHERE id = :id");
            $stmtUpdate->execute([
                'stars' => $newStars,
                'id' => $id
            ]);

            $db->commit();

            // Save to session
            $_SESSION['starred_features'][] = $id;

            echo json_encode([
                'success' => true,
                'stars' => $newStars,
                'message' => 'Upvote registered successfully!'
            ]);
        } catch (\PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            
            // Check if error was due to unique constraint duplicate entry
            if ($e->getCode() == 23000) {
                echo json_encode(['success' => false, 'message' => 'You have already upvoted this feature request.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            }
        }
    }
}
