<?php
/**
 * Admin Feature Requests Management View
 */
use App\Core\Security;
use App\Config\App;

$csrfToken = Security::generateCsrfToken();
?>
<div class="card" style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 1.5rem; margin-top: 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.25rem;">Feature Requests Board</h3>
            <p class="text-muted" style="font-size: 0.875rem;">Manage feature suggestions submitted by the community and mark them solved.</p>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
                <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-text-secondary); font-weight: 600;">
                    <th style="padding: 0.75rem 1rem;">Stars</th>
                    <th style="padding: 0.75rem 1rem;">User</th>
                    <th style="padding: 0.75rem 1rem;">Details</th>
                    <th style="padding: 0.75rem 1rem;">Status</th>
                    <th style="padding: 0.75rem 1rem;">Date</th>
                    <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($features)): ?>
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: var(--color-text-secondary);">No feature requests submitted yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($features as $req): ?>
                        <tr style="border-bottom: 1px solid var(--color-border); transition: background-color var(--transition-fast);">
                            <td style="padding: 1rem; font-weight: bold; font-size: 1.05rem; color: var(--color-warning);">
                                <i class="fa-solid fa-star"></i> <?= number_format($req['stars']) ?>
                            </td>
                            <td style="padding: 1rem; font-weight: 600;"><?= htmlspecialchars($req['user_name']) ?></td>
                            <td style="padding: 1rem; color: var(--color-text-primary); max-width: 300px; word-break: break-word;"><?= nl2br(htmlspecialchars($req['details'])) ?></td>
                            <td style="padding: 1rem;">
                                <?php if ($req['is_solved']): ?>
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: 600; color: var(--color-success); background: color-mix(in srgb, var(--color-success) 10%, transparent); border-radius: var(--radius-full);">Solved</span>
                                <?php else: ?>
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: 600; color: var(--color-warning); background: color-mix(in srgb, var(--color-warning) 10%, transparent); border-radius: var(--radius-full);">Active</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem; color: var(--color-text-secondary); font-size: 0.8rem; white-space: nowrap;">
                                <?= date('Y-m-d H:i', strtotime($req['created_at'])) ?>
                            </td>
                            <td style="padding: 1rem; text-align: right; white-space: nowrap;">
                                <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                    <!-- Solve Toggle Button -->
                                    <form action="<?= App::adminUrl('/features/solve') ?>" method="POST" style="display: inline;">
                                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                        <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                        <?php if ($req['is_solved']): ?>
                                            <input type="hidden" name="solve" value="0">
                                            <button type="submit" class="btn" style="height: 30px; padding: 0 0.75rem; font-size: 0.8rem; background: var(--color-background); border: 1px solid var(--color-border); color: var(--color-text-secondary); display: inline-flex; align-items: center; gap: 0.25rem;">
                                                <i class="fa-solid fa-redo"></i> Mark Active
                                            </button>
                                        <?php else: ?>
                                            <input type="hidden" name="solve" value="1">
                                            <button type="submit" class="btn btn-primary" style="height: 30px; padding: 0 0.75rem; font-size: 0.8rem; background: var(--color-success); color: white; display: inline-flex; align-items: center; gap: 0.25rem;">
                                                <i class="fa-solid fa-check"></i> Mark Solved
                                            </button>
                                        <?php endif; ?>
                                    </form>

                                    <!-- Delete Button -->
                                    <form action="<?= App::adminUrl('/features/delete') ?>" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this feature request?');">
                                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                        <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                        <button type="submit" class="btn btn-secondary" style="height: 30px; padding: 0 0.75rem; font-size: 0.8rem; border-color: var(--color-danger); color: var(--color-danger); display: inline-flex; align-items: center; gap: 0.25rem;">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
