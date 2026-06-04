<?php
/**
 * Admin Categories Management View
 */
?>
<div class="card" style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 1.5rem; margin-top: 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.25rem;">Tool Categories</h3>
            <p class="text-muted" style="font-size: 0.875rem;">A list of categories for sorting website tools.</p>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
                <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-text-secondary); font-weight: 600;">
                    <th style="padding: 0.75rem 1rem;">Category Name</th>
                    <th style="padding: 0.75rem 1rem;">Slug</th>
                    <th style="padding: 0.75rem 1rem;">Icon Class</th>
                    <th style="padding: 0.75rem 1rem;">Tools Count</th>
                    <th style="padding: 0.75rem 1rem;">Sort Order</th>
                    <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: var(--color-text-secondary);">No categories found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                        <tr style="border-bottom: 1px solid var(--color-border); transition: background-color var(--transition-fast);">
                            <td style="padding: 1rem; font-weight: 500; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fa-solid <?= htmlspecialchars($category['icon'] ?? 'fa-folder') ?>" style="color: var(--color-primary);"></i>
                                <?= htmlspecialchars($category['name']) ?>
                            </td>
                            <td style="padding: 1rem; font-family: var(--font-mono); font-size: 0.8rem; color: var(--color-text-secondary);"><?= htmlspecialchars($category['slug']) ?></td>
                            <td style="padding: 1rem; color: var(--color-text-secondary);"><code><?= htmlspecialchars($category['icon'] ?? '') ?></code></td>
                            <td style="padding: 1rem; font-weight: 600;"><?= number_format($category['tool_count']) ?> tools</td>
                            <td style="padding: 1rem; color: var(--color-text-secondary);"><?= (int)$category['sort_order'] ?></td>
                            <td style="padding: 1rem; text-align: right;">
                                <a href="<?= \App\Config\App::adminUrl('/categories/edit/' . $category['id']) ?>" class="btn btn-primary" style="height: 30px; padding: 0 0.75rem; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
