<?php
namespace App\Controllers;

use App\Config\App;
use App\Config\Database;
use App\Core\Security;
use App\Core\RateLimiter;
use App\Core\GoogleAuthenticator;
use PDO;

class AuthController {
    
    /**
     * Display the login form
     */
    public function login(): void {
        Security::startSession();
        
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . App::adminUrl('/dashboard'));
            exit;
        }

        $pageTitle = 'Admin Login - ' . App::siteName();
        require __DIR__ . '/../Views/admin/login.php';
    }

    /**
     * Handle login submission
     */
    public function authenticate(): void {
        Security::startSession();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        // 1. Rate Limiting check
        if (!RateLimiter::check('login', 10, 1)) {
            $error = "Too many requests. Please try again later.";
            require __DIR__ . '/../Views/admin/login.php';
            return;
        }

        // 2. CSRF check
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $error = "Invalid security token.";
            require __DIR__ . '/../Views/admin/login.php';
            return;
        }

        $username = Security::sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $ip = RateLimiter::getIpAddress();

        if (empty($username) || empty($password)) {
            $error = "Please enter username and password.";
            require __DIR__ . '/../Views/admin/login.php';
            return;
        }

        // 3. Login Attempts Lockout Check (Max 5 attempts / 15 mins)
        $db = Database::getConnection();
        $stmtLock = $db->prepare("SELECT attempts, locked_until FROM login_attempts WHERE ip_address = :ip LIMIT 1");
        $stmtLock->execute(['ip' => $ip]);
        $lock = $stmtLock->fetch();

        if ($lock && $lock['locked_until'] !== null && strtotime($lock['locked_until']) > time()) {
            $minutesLeft = ceil((strtotime($lock['locked_until']) - time()) / 60);
            $error = "Account locked due to too many failed attempts. Try again in {$minutesLeft} minutes.";
            require __DIR__ . '/../Views/admin/login.php';
            return;
        }

        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Success! Reset login attempts
            $db->prepare("DELETE FROM login_attempts WHERE ip_address = :ip")->execute(['ip' => $ip]);

            // Check if 2FA is enabled
            if (!empty($user['totp_secret'])) {
                $_SESSION['pending_2fa_user_id'] = $user['id'];
                header('Location: ' . App::url('/login/2fa'));
                exit;
            }

            // No 2FA, log them in directly
            self::finalizeLogin($user);
        } else {
            // Failed attempt
            self::recordFailedLogin($ip, $lock);
            $error = "Invalid username or password.";
            require __DIR__ . '/../Views/admin/login.php';
        }
    }

    /**
     * Show 2FA Verification Page
     */
    public function show2fa(): void {
        Security::startSession();
        if (empty($_SESSION['pending_2fa_user_id'])) {
            header('Location: ' . App::url('/login'));
            exit;
        }
        $pageTitle = 'Two-Factor Authentication';
        require __DIR__ . '/../Views/admin/2fa.php';
    }

    /**
     * Verify 2FA Code
     */
    public function verify2fa(): void {
        Security::startSession();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['pending_2fa_user_id'])) {
            header('Location: ' . App::url('/login'));
            exit;
        }

        if (!RateLimiter::check('login_2fa', 10, 1)) {
            $error = "Too many requests. Please try again later.";
            require __DIR__ . '/../Views/admin/2fa.php';
            return;
        }

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $error = "Invalid security token.";
            require __DIR__ . '/../Views/admin/2fa.php';
            return;
        }

        $code = $_POST['totp_code'] ?? '';
        
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $_SESSION['pending_2fa_user_id']]);
        $user = $stmt->fetch();

        if ($user && GoogleAuthenticator::verifyCode($user['totp_secret'], $code)) {
            unset($_SESSION['pending_2fa_user_id']);
            self::finalizeLogin($user);
        } else {
            $error = "Invalid authentication code.";
            require __DIR__ . '/../Views/admin/2fa.php';
        }
    }

    /**
     * Finalize session after successful auth
     */
    private static function finalizeLogin(array $user): void {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        session_regenerate_id(true); // Prevent session fixation
        header('Location: ' . App::adminUrl('/dashboard'));
        exit;
    }

    /**
     * Record a failed login attempt for the IP
     */
    private static function recordFailedLogin(string $ip, $lockRecord): void {
        $db = Database::getConnection();
        if (!$lockRecord) {
            $db->prepare("INSERT INTO login_attempts (ip_address, attempts) VALUES (:ip, 1)")->execute(['ip' => $ip]);
        } else {
            $attempts = $lockRecord['attempts'] + 1;
            if ($attempts >= 5) {
                // Lock for 15 minutes
                $db->prepare("UPDATE login_attempts SET attempts = :attempts, locked_until = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE ip_address = :ip")->execute(['attempts' => $attempts, 'ip' => $ip]);
            } else {
                $db->prepare("UPDATE login_attempts SET attempts = :attempts WHERE ip_address = :ip")->execute(['attempts' => $attempts, 'ip' => $ip]);
            }
        }
    }

    /**
     * Handle logout
     */
    public function logout(): void {
        Security::startSession();
        session_unset();
        session_destroy();
        header('Location: ' . App::url('/login'));
        exit;
    }
}
