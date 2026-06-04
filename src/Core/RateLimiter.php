<?php
namespace App\Core;

use App\Config\Database;

class RateLimiter {
    
    /**
     * Check if the current IP has exceeded the limit for a specific endpoint.
     * 
     * @param string $endpoint The name of the endpoint (e.g., 'login', 'url_shorten')
     * @param int $limit Max number of requests allowed
     * @param int $windowMinutes Time window in minutes
     * @return bool True if allowed, false if limit exceeded
     */
    public static function check(string $endpoint, int $limit = 10, int $windowMinutes = 1): bool {
        $db = Database::getConnection();
        $ip = self::getIpAddress();
        
        // Count recent requests
        $stmt = $db->prepare("
            SELECT COUNT(id) 
            FROM rate_limits 
            WHERE ip_address = :ip 
            AND endpoint = :endpoint 
            AND request_time > (NOW() - INTERVAL :window MINUTE)
        ");
        $stmt->execute([
            'ip' => $ip,
            'endpoint' => $endpoint,
            'window' => $windowMinutes
        ]);
        
        $requests = (int) $stmt->fetchColumn();
        
        if ($requests >= $limit) {
            return false;
        }
        
        // Log this request
        $stmtLog = $db->prepare("INSERT INTO rate_limits (ip_address, endpoint) VALUES (:ip, :endpoint)");
        $stmtLog->execute([
            'ip' => $ip,
            'endpoint' => $endpoint
        ]);
        
        return true;
    }

    /**
     * Get real IP address of the client
     */
    public static function getIpAddress(): string {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }
        // Take the first IP if there are multiple
        return trim(explode(',', $ip)[0]);
    }
}
