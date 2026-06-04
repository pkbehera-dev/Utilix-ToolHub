<?php
namespace App\Config;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    /**
     * Get the PDO database connection instance (Singleton)
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $db   = $_ENV['DB_NAME'] ?? 'toolbox';
            $user = $_ENV['DB_USER'] ?? 'root';
            $pass = $_ENV['DB_PASS'] ?? '';
            $char = $_ENV['DB_CHAR'] ?? 'utf8mb4';

            $dsn = "mysql:host={$host};dbname={$db};charset={$char}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays by default
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // In production, log this error instead of displaying it directly
                error_log("Database connection failed: " . $e->getMessage());
                die("A database error occurred. Please try again later.");
            }
        }

        return self::$instance;
    }
}
