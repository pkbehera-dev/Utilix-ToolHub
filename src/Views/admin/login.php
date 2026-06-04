<?php
use App\Config\App;
use App\Core\Security;
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Login') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= App::url('assets/css/style.css') ?>">
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            background: var(--card-bg);
            padding: 30px;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            background: var(--bg-color);
            color: var(--text-main);
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-size: 1rem;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
        }
        .error-msg {
            color: #ef4444;
            background: #fee2e2;
            padding: 10px;
            border-radius: var(--radius);
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
        [data-theme="dark"] .error-msg {
            background: #7f1d1d;
            color: #fca5a5;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="<?= App::url('/login/submit') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken() ?>">
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-primary">Login</button>
        </form>
    </div>
</body>
</html>
