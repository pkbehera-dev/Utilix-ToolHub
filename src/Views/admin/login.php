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
    <link rel="icon" type="image/svg+xml" href="<?= App::url('assets/favicon.svg') ?>">
    <link rel="stylesheet" href="<?= App::url('assets/css/style.css') ?>">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
      function onSubmit(token) {
        document.getElementById("login-form").submit();
      }
    </script>
    <style>
        body {
            background-color: var(--color-background);
            font-family: var(--font-sans);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .login-container {
            width: 100%;
            max-width: 420px;
            background: var(--color-surface);
            padding: 2.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--color-border);
            border-top: 4px solid var(--color-primary); /* Premium site theme accent border */
            transition: transform var(--transition-fast);
        }
        .login-container:hover {
            transform: translateY(-2px);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header i {
            font-size: 2.5rem;
            color: var(--color-primary);
            margin-bottom: 0.75rem;
        }
        .login-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-text-primary);
            margin: 0;
        }
        .login-header p {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            margin-top: 0.25rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--color-text-primary);
        }
        .form-input {
            width: 100%;
            height: 42px;
            padding: 0.5rem 0.875rem;
            font-family: var(--font-sans);
            font-size: 0.875rem;
            color: var(--color-text-primary);
            background-color: var(--color-background);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        }
        .form-input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-primary) 15%, transparent);
        }
        .btn-primary {
            width: 100%;
            height: 44px;
            background: var(--color-primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-family: var(--font-sans);
            font-size: 0.95rem;
            font-weight: 600;
            transition: background-color var(--transition-fast), transform var(--transition-fast);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-primary:hover {
            background: var(--color-primary-hover);
        }
        .btn-primary:active {
            transform: scale(0.98);
        }
        .error-msg {
            color: var(--color-danger);
            background: color-mix(in srgb, var(--color-danger) 8%, transparent);
            padding: 0.75rem 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid color-mix(in srgb, var(--color-danger) 15%, transparent);
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            text-decoration: none;
            transition: color var(--transition-fast);
        }
        .back-link:hover {
            color: var(--color-primary);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fa-solid fa-shield-halved"></i>
            <h2>Admin Area</h2>
            <p>Access the <?= App::siteName() ?> Control Panel</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="error-msg">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= App::url('/login/submit') ?>" method="POST" id="login-form">
            <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken() ?>">
            
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" name="username" class="form-input" required autofocus>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            
            <button class="btn-primary g-recaptcha" data-sitekey="<?= htmlspecialchars($_ENV['RECAPTCHA_SITE_KEY'] ?? '') ?>" data-callback="onSubmit">
                <i class="fa-solid fa-sign-in-alt" style="margin-right: 0.5rem;"></i> Authenticate
            </button>
        </form>

        <a href="<?= App::url('/') ?>" class="back-link">
            <i class="fa-solid fa-arrow-left" style="margin-right: 0.25rem;"></i> Back to Homepage
        </a>
    </div>
</body>
</html>
