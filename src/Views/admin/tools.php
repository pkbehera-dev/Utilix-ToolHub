<?php
/**
 * Admin Tools Management View
 */
?>
<div class="card" style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 1.5rem; margin-top: 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.25rem;">Active Tools</h3>
            <p class="text-muted" style="font-size: 0.875rem;">A list of all utility tools configured on your website.</p>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
                <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-text-secondary); font-weight: 600;">
                    <th style="padding: 0.75rem 1rem;">Tool Name</th>
                    <th style="padding: 0.75rem 1rem;">Category</th>
                    <th style="padding: 0.75rem 1rem;">Slug</th>
                    <th style="padding: 0.75rem 1rem;">Status</th>
                    <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tools)): ?>
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: var(--color-text-secondary);">No tools found in database.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tools as $tool): ?>
                        <tr style="border-bottom: 1px solid var(--color-border); transition: background-color var(--transition-fast);">
                            <td style="padding: 1rem; font-weight: 500; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-cube') ?>" style="color: var(--color-primary);"></i>
                                <?= htmlspecialchars($tool['name']) ?>
                            </td>
                            <td style="padding: 1rem; color: var(--color-text-secondary);"><?= htmlspecialchars($tool['category_name']) ?></td>
                            <td style="padding: 1rem; font-family: var(--font-mono); font-size: 0.8rem; color: var(--color-text-secondary);">/tool/<?= htmlspecialchars($tool['slug']) ?></td>
                            <td style="padding: 1rem;">
                                <?php if ($tool['is_active']): ?>
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: 600; color: var(--color-success); background: color-mix(in srgb, var(--color-success) 10%, transparent); border-radius: var(--radius-full);">Active</span>
                                <?php else: ?>
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: 600; color: var(--color-danger); background: color-mix(in srgb, var(--color-danger) 10%, transparent); border-radius: var(--radius-full);">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem; text-align: right; display: flex; justify-content: flex-end; gap: 0.5rem;">
                                <a href="<?= \App\Config\App::adminUrl('/tools/edit/' . $tool['id']) ?>" class="btn btn-primary" style="height: 30px; padding: 0 0.75rem; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                                <a href="<?= \App\Config\App::url('/tool/' . $tool['slug']) ?>" target="_blank" class="btn btn-secondary" style="height: 30px; padding: 0 0.75rem; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <i class="fa-solid fa-external-link"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
