<section class="hero">
    <h1>Every Tool You Need.<br>Right in your browser.</h1>
    <p>Your digital utility belt, refined. Fast, free, secure, and beautiful.</p>
    
    <div class="hero-cta-group" style="margin-top: 1.5rem; margin-bottom: 1rem; display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <a href="<?= \App\Config\App::url('/features') ?>" class="btn btn-primary" style="background: linear-gradient(135deg, var(--color-primary), #10B981); border: none; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25); font-weight: 600; padding: 0.75rem 1.5rem; border-radius: var(--radius-md); transition: transform var(--transition-fast);">
            <i class="fa-solid fa-lightbulb"></i> Request a Feature / Upvote
        </a>
    </div>

    <!-- Mobile/Fallback Search (For smaller screens or if Command Palette is undiscovered) -->
    <div class="quick-search-kbd" style="max-width: 400px; margin-inline: auto; display: flex; align-items: center; justify-content: center; gap: 0.5rem; color: var(--color-text-secondary); font-size: 0.875rem;">
        <kbd style="background: var(--color-surface); padding: 0.25rem 0.5rem; border-radius: 4px; border: 1px solid var(--color-border);">Ctrl</kbd> + <kbd style="background: var(--color-surface); padding: 0.25rem 0.5rem; border-radius: 4px; border: 1px solid var(--color-border);">K</kbd> to Quick Search
    </div>
</section>

<?php if (empty($categories)): ?>
    <div class="tool-card w-full text-center" style="grid-column: 1 / -1; margin-top: 2rem;">
        <div class="tool-icon-wrapper" style="margin-inline: auto;">
            <i class="fa-solid fa-triangle-exclamation" style="color: var(--color-warning);"></i>
        </div>
        <h3>No tools available</h3>
        <p>Please run the database seed to populate categories and tools.</p>
    </div>
<?php endif; ?>

<?php foreach ($categories as $category): ?>
    <?php if (!empty($toolsByCategory[$category['id']])): ?>
        <section class="category-section" id="cat-<?= htmlspecialchars($category['slug']) ?>">
            <div class="category-header">
                <?php if ($category['icon']): ?>
                    <i class="fa-solid <?= htmlspecialchars($category['icon']) ?>" style="color: var(--color-primary)"></i> 
                <?php endif; ?>
                <?= htmlspecialchars($category['name']) ?>
            </div>
            
            <div class="tools-grid">
                <?php foreach ($toolsByCategory[$category['id']] as $tool): ?>
                    <a href="<?= \App\Config\App::url('/tool/' . $tool['slug']) ?>" class="tool-card">
                        <div class="tool-icon-wrapper">
                            <?php if ($tool['icon']): ?>
                                <i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i>
                            <?php else: ?>
                                <i class="fa-solid fa-cube"></i>
                            <?php endif; ?>
                        </div>
                        <h3><?= htmlspecialchars($tool['name']) ?></h3>
                        <p><?= htmlspecialchars($tool['description'] ?? 'Click to use this tool.') ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endforeach; ?>
