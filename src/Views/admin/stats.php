<?php
/**
 * Admin Usage Analytics View
 */
?>
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text-primary); margin-bottom: 0.5rem;">Usage Analytics</h2>
    <p style="color: var(--color-text-secondary); font-size: 0.9rem;">Monitor visitor interaction metrics, recurring traffic, and user engagement times on each tool.</p>
</div>

<!-- Analytics Overview Metrics Cards -->
<div class="tools-grid" style="margin-bottom: 2.5rem;">
    <!-- Total Views Card -->
    <div class="tool-card" style="display: flex; flex-direction: column; justify-content: space-between; padding: 20px;">
        <div>
            <i class="fa-solid fa-eye" style="color: var(--color-primary); font-size: 1.5rem; margin-bottom: 12px;"></i>
            <h3 style="font-size: 0.95rem; color: var(--color-text-secondary); font-weight: 500; margin-bottom: 5px;">Total Views</h3>
        </div>
        <p style="font-size: 2rem; font-weight: 800; color: var(--color-text-primary); margin: 0; line-height: 1;">
            <?= number_format($siteStats['total_views']) ?>
        </p>
    </div>

    <!-- Unique Visitors (Total Users) -->
    <div class="tool-card" style="display: flex; flex-direction: column; justify-content: space-between; padding: 20px;">
        <div>
            <i class="fa-solid fa-users" style="color: var(--color-success); font-size: 1.5rem; margin-bottom: 12px;"></i>
            <h3 style="font-size: 0.95rem; color: var(--color-text-secondary); font-weight: 500; margin-bottom: 5px;">Unique Visitors</h3>
        </div>
        <p style="font-size: 2rem; font-weight: 800; color: var(--color-text-primary); margin: 0; line-height: 1;">
            <?= number_format($siteStats['total_users']) ?>
        </p>
    </div>

    <!-- Returning Visitors (Recurring Views) -->
    <div class="tool-card" style="display: flex; flex-direction: column; justify-content: space-between; padding: 20px;">
        <div>
            <i class="fa-solid fa-arrow-rotate-left" style="color: var(--color-warning); font-size: 1.5rem; margin-bottom: 12px;"></i>
            <h3 style="font-size: 0.95rem; color: var(--color-text-secondary); font-weight: 500; margin-bottom: 5px;">Recurring Views</h3>
        </div>
        <p style="font-size: 2rem; font-weight: 800; color: var(--color-text-primary); margin: 0; line-height: 1;">
            <?= number_format($siteStats['total_recurring']) ?>
            <span style="font-size: 0.8rem; font-weight: 500; color: var(--color-text-secondary); margin-left: 4px;">
                (<?= $siteStats['total_views'] > 0 ? round(($siteStats['total_recurring'] / $siteStats['total_views']) * 100, 1) : 0 ?>%)
            </span>
        </p>
    </div>

    <!-- Total Active Time spent -->
    <div class="tool-card" style="display: flex; flex-direction: column; justify-content: space-between; padding: 20px;">
        <div>
            <i class="fa-solid fa-hourglass-high" style="color: var(--color-primary); font-size: 1.5rem; margin-bottom: 12px;"></i>
            <h3 style="font-size: 0.95rem; color: var(--color-text-secondary); font-weight: 500; margin-bottom: 5px;">Total View Time</h3>
        </div>
        <p style="font-size: 1.6rem; font-weight: 800; color: var(--color-text-primary); margin: 0; line-height: 1.2;">
            <?php
                $sec = $siteStats['total_seconds'];
                if ($sec < 60) {
                    echo $sec . 's';
                } elseif ($sec < 3600) {
                    echo round($sec / 60, 1) . 'm';
                } else {
                    echo round($sec / 3600, 1) . 'h';
                }
            ?>
        </p>
    </div>
</div>

<!-- Detailed Table Board -->
<div class="card" style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 1.5rem;">
    <div style="margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.25rem;">Analytics Breakdown by Tool</h3>
        <p class="text-muted" style="font-size: 0.875rem;">Engagement and traffic details recorded for user sessions (excluding admin logs).</p>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
                <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-text-secondary); font-weight: 600;">
                    <th style="padding: 0.75rem 1rem;">Tool Name</th>
                    <th style="padding: 0.75rem 1rem;">Category</th>
                    <th style="padding: 0.75rem 1rem; text-align: center;">Total Views</th>
                    <th style="padding: 0.75rem 1rem; text-align: center;">Unique Visitors</th>
                    <th style="padding: 0.75rem 1rem; text-align: center;">Recurring Views</th>
                    <th style="padding: 0.75rem 1rem; text-align: center;">Total Spent Time</th>
                    <th style="padding: 0.75rem 1rem; text-align: right;">Avg. Session Duration</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($analytics)): ?>
                    <tr>
                        <td colspan="7" style="padding: 2rem; text-align: center; color: var(--color-text-secondary);">No analytics logs found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($analytics as $row): ?>
                        <tr style="border-bottom: 1px solid var(--color-border); transition: background-color var(--transition-fast);">
                            <td style="padding: 1rem; font-weight: 500;">
                                <?= htmlspecialchars($row['name']) ?>
                                <span class="text-muted" style="display: block; font-size: 0.75rem; font-family: monospace;">/tool/<?= htmlspecialchars($row['slug']) ?></span>
                            </td>
                            <td style="padding: 1rem; color: var(--color-text-secondary);"><?= htmlspecialchars($row['category_name']) ?></td>
                            <td style="padding: 1rem; text-align: center; font-weight: 600;"><?= number_format($row['total_views']) ?></td>
                            <td style="padding: 1rem; text-align: center; color: var(--color-text-secondary);"><?= number_format($row['total_users']) ?></td>
                            <td style="padding: 1rem; text-align: center; color: var(--color-text-secondary);">
                                <?= number_format($row['recurring_views']) ?>
                                <?php if ($row['total_views'] > 0): ?>
                                    <span style="font-size: 0.75rem; opacity: 0.7;">(<?= round(($row['recurring_views'] / $row['total_views']) * 100) ?>%)</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <?php
                                    $s = $row['total_seconds'];
                                    if ($s < 60) {
                                        echo $s . 's';
                                    } elseif ($s < 3600) {
                                        echo round($s / 60, 1) . 'm';
                                    } else {
                                        echo round($s / 3600, 1) . 'h';
                                    }
                                ?>
                            </td>
                            <td style="padding: 1rem; text-align: right; font-weight: 600; color: var(--color-primary);">
                                <?php
                                    // Average session duration: total_seconds / total_views
                                    if ($row['total_views'] > 0) {
                                        $avg = $row['total_seconds'] / $row['total_views'];
                                        if ($avg < 60) {
                                            echo round($avg, 1) . 's';
                                        } else {
                                            echo round($avg / 60, 1) . 'm';
                                        }
                                    } else {
                                        echo '0s';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
