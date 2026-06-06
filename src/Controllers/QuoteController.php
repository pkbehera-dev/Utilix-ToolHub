<?php
namespace App\Controllers;

use App\Config\Database;
use App\Core\Security;
use PDO;

class QuoteController {

    /**
     * Get a random quote (optionally filtered by category)
     */
    public function generate(): void {
        header('Content-Type: application/json');

        $category = Security::sanitize($_GET['category'] ?? '');
        $db = Database::getConnection();

        $excludeRaw = $_GET['exclude'] ?? '';
        $exclude = array_filter(array_map('intval', explode(',', $excludeRaw)));

        $query = "SELECT id, quote_text, author, category, is_user_submitted, submitted_by FROM quotes WHERE is_approved = 1 ";
        $params = [];

        if (!empty($category) && $category !== 'All') {
            $query .= "AND category = :category ";
            $params['category'] = $category;
        }

        if (!empty($exclude)) {
            $query .= "AND id NOT IN (" . implode(',', $exclude) . ") ";
        }

        $query .= "ORDER BY RAND() LIMIT 1";

        try {
            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $quote = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($quote) {
                echo json_encode(['success' => true, 'quote' => $quote]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No quotes found in this category.']);
            }
        } catch (\PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }

    /**
     * Submit a quote with a maximum limit of 100 per category
     */
    public function add(): void {
        header('Content-Type: application/json');
        Security::startSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            return;
        }

        // Verify CSRF
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'message' => 'Invalid security token.']);
            return;
        }

        $quoteText = Security::sanitize($_POST['quote_text'] ?? '');
        $author = Security::sanitize($_POST['author'] ?? '');
        $category = Security::sanitize($_POST['category'] ?? '');
        $submittedBy = Security::sanitize($_POST['submitted_by'] ?? '');

        if (empty($quoteText) || empty($author) || empty($category) || empty($submittedBy)) {
            echo json_encode(['success' => false, 'message' => 'All fields (Quote, Author, Category, and Your Name) are required.']);
            return;
        }

        // Validate Category
        $allowedCategories = ['Motivation', 'Life', 'Technology', 'Inspirational', 'Humor'];
        if (!in_array($category, $allowedCategories)) {
            echo json_encode(['success' => false, 'message' => 'Invalid category selected.']);
            return;
        }

        if (strlen($submittedBy) > 100 || strlen($author) > 100) {
            echo json_encode(['success' => false, 'message' => 'Name fields cannot exceed 100 characters.']);
            return;
        }

        try {
            $db = Database::getConnection();

            // Enforce limit of 100 quotes per category
            $stmtCount = $db->prepare("SELECT COUNT(*) FROM quotes WHERE category = :category");
            $stmtCount->execute(['category' => $category]);
            $count = (int)$stmtCount->fetchColumn();

            if ($count >= 100) {
                echo json_encode([
                    'success' => false, 
                    'message' => "The category '{$category}' has reached its maximum limit of 100 quotes. Cannot add more."
                ]);
                return;
            }

            // Insert quote
            $stmtInsert = $db->prepare("INSERT INTO quotes (quote_text, author, category, is_user_submitted, submitted_by) VALUES (:text, :author, :category, 1, :submitted_by)");
            $stmtInsert->execute([
                'text' => $quoteText,
                'author' => $author,
                'category' => $category,
                'submitted_by' => $submittedBy
            ]);

            echo json_encode([
                'success' => true, 
                'message' => 'Thank you! Your quote has been submitted successfully.'
            ]);
        } catch (\PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }

    /**
     * Get all approved quotes (optionally filtered by category)
     */
    public function allApproved(): void {
        header('Content-Type: application/json');

        $category = Security::sanitize($_GET['category'] ?? 'All');
        $db = Database::getConnection();

        $query = "SELECT id, quote_text, author, category, is_user_submitted, submitted_by FROM quotes WHERE is_approved = 1 ";
        $params = [];

        if (!empty($category) && $category !== 'All') {
            $query .= "AND category = :category ";
            $params['category'] = $category;
        }

        $query .= "ORDER BY category ASC, created_at DESC";

        try {
            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'quotes' => $quotes]);
        } catch (\PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
}
