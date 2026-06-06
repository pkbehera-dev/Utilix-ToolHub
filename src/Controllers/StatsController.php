<?php
namespace App\Controllers;

use App\Config\Database;
use App\Core\Security;

class StatsController {

    /**
     * Handle the POST beacon containing spent time.
     */
    public function trackTime(): void {
        Security::startSession();

        // Track only users, not admins
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Skipped tracking for admin']);
            exit;
        }

        // Retrieve parameters from $_POST (standard FormData) or raw JSON POST body
        $rawInput = file_get_contents('php://input');
        $jsonData = json_decode($rawInput, true) ?: [];

        $toolSlug = Security::sanitize($_POST['tool_slug'] ?? $jsonData['tool_slug'] ?? '');
        $seconds = (int)($_POST['seconds'] ?? $jsonData['seconds'] ?? 0);

        if (empty($toolSlug) || $seconds <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing parameter']);
            exit;
        }

        // Limit maximum added seconds per single packet to prevent abuse (e.g. max 1 hour)
        if ($seconds > 3600) {
            $seconds = 3600;
        }

        try {
            $db = Database::getConnection();

            // Resolve tool slug to ID
            $stmtTool = $db->prepare("SELECT id FROM tools WHERE slug = :slug LIMIT 1");
            $stmtTool->execute(['slug' => $toolSlug]);
            $toolId = $stmtTool->fetchColumn();

            if ($toolId) {
                // Upsert spent time using distinct named parameters to prevent PDO duplicate parameter mapping exceptions
                $stmtUpsert = $db->prepare("
                    INSERT INTO tool_usage_stats (tool_id, total_views, recurring_views, total_users, total_seconds)
                    VALUES (:tool_id, 0, 0, 0, :seconds_insert)
                    ON DUPLICATE KEY UPDATE 
                        total_seconds = total_seconds + :seconds_update
                ");
                $stmtUpsert->execute([
                    'tool_id' => $toolId,
                    'seconds_insert' => $seconds,
                    'seconds_update' => $seconds
                ]);

                http_response_code(200);
                echo json_encode(['success' => true]);
                exit;
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Tool not found']);
                exit;
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }
}
