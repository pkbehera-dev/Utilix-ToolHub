<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text-primary); margin-bottom: 0.5rem;">System Overview</h2>
    <p style="color: var(--color-text-secondary); font-size: 0.9rem;">Real-time diagnostics and platform statistics for ToolBox.</p>
</div>

<!-- Main Stats Grid -->
<div class="tools-grid" style="margin-bottom: 3rem;">
    <div class="tool-card" style="display: flex; flex-direction: column; justify-content: space-between; padding: 25px;">
        <div>
            <i class="fa-solid fa-cube" style="color: var(--color-primary); font-size: 1.5rem; margin-bottom: 12px;"></i>
            <h3 style="font-size: 0.95rem; color: var(--color-text-secondary); font-weight: 500; margin-bottom: 5px;">Total Tools</h3>
        </div>
        <p style="font-size: 2.25rem; font-weight: 800; color: var(--color-text-primary); margin: 0; line-height: 1;">
            <?= number_format($stats['total_tools']) ?>
        </p>
    </div>

    <div class="tool-card" style="display: flex; flex-direction: column; justify-content: space-between; padding: 25px;">
        <div>
            <i class="fa-solid fa-circle-check" style="color: var(--color-success); font-size: 1.5rem; margin-bottom: 12px;"></i>
            <h3 style="font-size: 0.95rem; color: var(--color-text-secondary); font-weight: 500; margin-bottom: 5px;">Active Tools</h3>
        </div>
        <p style="font-size: 2.25rem; font-weight: 800; color: var(--color-text-primary); margin: 0; line-height: 1;">
            <?= number_format($stats['active_tools']) ?>
        </p>
    </div>
    
    <div class="tool-card" style="display: flex; flex-direction: column; justify-content: space-between; padding: 25px;">
        <div>
            <i class="fa-solid fa-layer-group" style="color: var(--color-primary); font-size: 1.5rem; margin-bottom: 12px;"></i>
            <h3 style="font-size: 0.95rem; color: var(--color-text-secondary); font-weight: 500; margin-bottom: 5px;">Categories</h3>
        </div>
        <p style="font-size: 2.25rem; font-weight: 800; color: var(--color-text-primary); margin: 0; line-height: 1;">
            <?= number_format($stats['total_categories']) ?>
        </p>
    </div>

    <div class="tool-card" style="display: flex; flex-direction: column; justify-content: space-between; padding: 25px;">
        <div>
            <i class="fa-solid fa-eye" style="color: var(--color-primary); font-size: 1.5rem; margin-bottom: 12px;"></i>
            <h3 style="font-size: 0.95rem; color: var(--color-text-secondary); font-weight: 500; margin-bottom: 5px;">Total Views</h3>
        </div>
        <p style="font-size: 2.25rem; font-weight: 800; color: var(--color-text-primary); margin: 0; line-height: 1;">
            <?= number_format($stats['total_tool_views']) ?>
        </p>
    </div>
</div>

<!-- Diagnostics and Feature overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
    
    <!-- Most Popular Tool Card -->
    <div style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 25px; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--color-text-primary); margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-crown" style="color: var(--color-warning);"></i> Most Popular Tool
            </h3>
            <?php if ($stats['most_popular_tool']): ?>
                <div style="background: var(--color-background); padding: 15px; border-radius: var(--radius-md); border: 1px solid var(--color-border);">
                    <div style="font-weight: 600; color: var(--color-text-primary); margin-bottom: 4px;"><?= htmlspecialchars($stats['most_popular_tool']['name']) ?></div>
                    <div style="font-size: 0.85rem; color: var(--color-text-secondary);">
                        Total views: <strong style="color: var(--color-primary); font-size: 1rem;"><?= number_format($stats['most_popular_tool']['views']) ?></strong>
                    </div>
                </div>
            <?php else: ?>
                <p style="color: var(--color-text-secondary); font-size: 0.9rem;">No data registered yet.</p>
            <?php endif; ?>
        </div>
        
        <!-- Short URLs summary card (kept minor since it's just a feature) -->
        <div style="margin-top: 20px; border-top: 1px solid var(--color-border); padding-top: 15px; font-size: 0.9rem; color: var(--color-text-secondary); display: flex; justify-content: space-between; align-items: center;">
            <span>Short URLs generated:</span>
            <span style="font-weight: 700; color: var(--color-text-primary); background: var(--color-background); padding: 2px 8px; border-radius: 12px; font-size: 0.85rem;">
                <?= number_format($stats['total_short_urls']) ?> links
            </span>
        </div>
    </div>

    <!-- System Diagnostics Card -->
    <div style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 25px;">
        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--color-text-primary); margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-gauge-high" style="color: var(--color-primary);"></i> System Diagnostics
        </h3>
        <div style="display: flex; flex-direction: column; gap: 12px; font-size: 0.9rem; color: var(--color-text-secondary);">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">
                <span>Environment:</span>
                <span style="font-weight: 600; color: var(--color-text-primary);"><?= ucfirst(htmlspecialchars($system['environment'])) ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">
                <span>PHP Version:</span>
                <span style="font-weight: 600; color: var(--color-text-primary);"><?= htmlspecialchars($system['php_version']) ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; padding-bottom: 4px;">
                <span>2FA Protection:</span>
                <?php if ($system['two_fa_active']): ?>
                    <span style="font-weight: 600; color: var(--color-success); display: inline-flex; align-items: center; gap: 4px;">
                        <i class="fa-solid fa-shield-halved"></i> Active
                    </span>
                <?php else: ?>
                    <span style="font-weight: 600; color: var(--color-danger); display: inline-flex; align-items: center; gap: 4px;">
                        <i class="fa-solid fa-triangle-exclamation"></i> Inactive
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
