<?php
/**
 * Admin Settings & System Checks View
 */
use App\Config\App;
use App\Core\Security;
?>

<div style="display: flex; flex-wrap: wrap; gap: 2rem; margin-top: 1rem;">
    
    <!-- System Checks Panel -->
    <div class="card" style="flex: 1 1 400px; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 1.5rem;">
        <div style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.25rem;">System Health Checks</h3>
            <p class="text-muted" style="font-size: 0.875rem;">Status of your core integrations and database.</p>
        </div>

        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid var(--color-border);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: color-mix(in srgb, var(--color-primary) 10%, transparent); color: var(--color-primary);">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600;">Database Connection</div>
                        <div style="font-size: 0.8rem; color: var(--color-text-secondary);">MySQL Database</div>
                    </div>
                </div>
                <span style="display: inline-block; padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 600; color: var(--color-success); background: color-mix(in srgb, var(--color-success) 10%, transparent); border-radius: var(--radius-full);">Connected</span>
            </li>

            <li style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid var(--color-border);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: color-mix(in srgb, var(--color-primary) 10%, transparent); color: var(--color-primary);">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600;">Admin Accounts</div>
                        <div style="font-size: 0.8rem; color: var(--color-text-secondary);">Users in database</div>
                    </div>
                </div>
                <div style="font-weight: 600; font-size: 1.1rem; color: var(--color-text-primary);"><?= $userCount ?></div>
            </li>

            <li style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 0;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: color-mix(in srgb, var(--color-primary) 10%, transparent); color: var(--color-primary);">
                        <i class="fa-solid fa-shield-virus"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600;">Google Safe Browsing</div>
                        <div style="font-size: 0.8rem; color: var(--color-text-secondary);">URL Shortener Protection</div>
                    </div>
                </div>
                <?php if ($safeBrowsing): ?>
                    <span style="display: inline-block; padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 600; color: var(--color-success); background: color-mix(in srgb, var(--color-success) 10%, transparent); border-radius: var(--radius-full);">Active</span>
                <?php else: ?>
                    <span style="display: inline-block; padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 600; color: var(--color-warning); background: color-mix(in srgb, var(--color-warning) 10%, transparent); border-radius: var(--radius-full);">Not Configured</span>
                <?php endif; ?>
            </li>
        </ul>
    </div>

    <!-- Password Change Panel -->
    <div class="card" style="flex: 1 1 400px; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 1.5rem;">
        <div style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.25rem;">Change Password</h3>
            <p class="text-muted" style="font-size: 0.875rem;">Update the password for your administrator account.</p>
        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] === 'password_updated'): ?>
            <div style="background: color-mix(in srgb, var(--color-success) 10%, transparent); color: var(--color-success); padding: 0.75rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-size: 0.875rem;">
                <i class="fa-solid fa-circle-check"></i> Password updated successfully.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div style="background: color-mix(in srgb, var(--color-danger) 10%, transparent); color: var(--color-danger); padding: 0.75rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-size: 0.875rem;">
                <i class="fa-solid fa-triangle-exclamation"></i> 
                <?php 
                    if ($_GET['error'] === 'missing_fields') echo 'Please fill out all fields.';
                    elseif ($_GET['error'] === 'invalid_password') echo 'Current password is incorrect.';
                    else echo 'An error occurred.';
                ?>
            </div>
        <?php endif; ?>

        <form action="<?= App::adminUrl('/settings/password') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken() ?>">
            
            <div class="form-group">
                <label class="form-label" for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="form-input" required>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" class="form-input" required minlength="8">
                <div style="font-size: 0.75rem; color: var(--color-text-secondary); margin-top: 0.5rem;">Minimum 8 characters.</div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Update Password</button>
        </form>
    </div>
</div>
