<?php
namespace App\Controllers;

class PasswordHasherController {

    /**
     * Generates a password hash.
     */
    public function hash(): void {
        header('Content-Type: application/json');
        
        $password = $_POST['password'] ?? '';
        $algoType = $_POST['algo'] ?? 'bcrypt';
        
        if (empty($password)) {
            echo json_encode(['success' => false, 'error' => 'Password cannot be empty.']);
            exit;
        }

        $algo = PASSWORD_DEFAULT;
        if ($algoType === 'bcrypt') {
            $algo = PASSWORD_BCRYPT;
        } elseif ($algoType === 'argon2id') {
            if (!defined('PASSWORD_ARGON2ID')) {
                echo json_encode(['success' => false, 'error' => 'Argon2id algorithm is not supported by your PHP installation.']);
                exit;
            }
            $algo = PASSWORD_ARGON2ID;
        }

        try {
            $hash = password_hash($password, $algo);
            if ($hash === false || $hash === null) {
                echo json_encode(['success' => false, 'error' => 'Failed to generate hash.']);
                exit;
            }

            echo json_encode([
                'success' => true,
                'hash' => $hash
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}
