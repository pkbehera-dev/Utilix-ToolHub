<?php
use App\Config\App;
use App\Core\Security;
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? '2FA - ' . App::siteName()) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= App::url('assets/css/style.css') ?>?v=<?= App::VERSION ?>">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: var(--color-background);
        }
        .login-card {
            background: var(--color-surface);
            padding: 2.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 400px;
            border: 1px solid var(--color-border);
            text-align: center;
        }
        .login-card h2 {
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
        }
        .login-card p {
            color: var(--color-text-secondary);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        .error-msg {
            background: color-mix(in srgb, var(--color-danger) 10%, transparent);
            color: var(--color-danger);
            padding: 0.75rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            text-align: left;
        }
        .form-group {
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div style="font-size: 3rem; color: var(--color-primary); margin-bottom: 1rem;">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h2>Two-Factor Authentication</h2>
        <p>Open Google Authenticator and enter the 6-digit code.</p>

        <?php if (!empty($error)): ?>
            <div class="error-msg"><i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="<?= App::url('/login/2fa/submit') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken() ?>">
            
            <div class="form-group">
                <input type="text" id="totp_code" name="totp_code" class="form-input" placeholder="000000" autocomplete="off" autofocus required style="text-align: center; font-size: 1.5rem; letter-spacing: 0.25rem;">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem;">Verify Code</button>
        </form>
        
        <div style="margin-top: 1.5rem;">
            <a href="<?= App::url('/login') ?>" class="text-sm">Cancel</a>
        </div>
    </div>

</body>
</html>
