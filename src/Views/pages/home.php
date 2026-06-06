<section class="hero">
    <h1>Every Tool You Need.<br>Right in your browser.</h1>
    <p>Your digital utility belt, refined. Fast, free, secure, and beautiful.</p>
    
    <!-- Global Usage Stats Badges -->
    <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 1.25rem; margin-bottom: 0.5rem; font-size: 0.85rem; font-weight: 600; color: var(--color-text-secondary); flex-wrap: wrap;">
        <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--color-surface); border: 1px solid var(--color-border); padding: 0.4rem 0.9rem; border-radius: var(--radius-full); box-shadow: var(--shadow-sm);">
            <i class="fa-solid fa-eye" style="color: var(--color-primary);"></i>
            <span><?= number_format($totalViews) ?> Total Views</span>
        </span>
        <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--color-surface); border: 1px solid var(--color-border); padding: 0.4rem 0.9rem; border-radius: var(--radius-full); box-shadow: var(--shadow-sm);">
            <i class="fa-solid fa-clock" style="color: var(--color-success);"></i>
            <span><?= htmlspecialchars($formattedTotalTime) ?> Total Active Time</span>
        </span>
    </div>
    
    <div class="hero-cta-group" style="margin-top: 1.5rem; margin-bottom: 1rem; display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <a href="<?= \App\Config\App::url('/features') ?>" class="btn btn-primary" style="background: linear-gradient(135deg, var(--color-primary), #10B981); border: none; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25); font-weight: 600; padding: 0.75rem 1.5rem; border-radius: var(--radius-md); transition: transform var(--transition-fast);">
            <i class="fa-solid fa-lightbulb"></i> Request a Feature / Upvote
        </a>
        <a href="https://t.me/pkbehera_dev" target="_blank" rel="noopener noreferrer" class="btn btn-secondary" style="border: 1px solid var(--color-border); font-weight: 600; padding: 0.75rem 1.5rem; border-radius: var(--radius-md); transition: transform var(--transition-fast); display: flex; align-items: center; gap: 0.5rem; background: rgba(0, 136, 204, 0.1); border-color: rgba(0, 136, 204, 0.2); color: #0088cc;">
            <i class="fa-brands fa-telegram" style="font-size: 1.15rem;"></i> Join Telegram Community
        </a>
    </div>

    <!-- Mobile/Fallback Search (For smaller screens or if Command Palette is undiscovered) -->
    <div class="quick-search-kbd" style="max-width: 400px; margin-inline: auto; display: flex; align-items: center; justify-content: center; gap: 0.5rem; color: var(--color-text-secondary); font-size: 0.875rem;">
        <kbd style="background: var(--color-surface); padding: 0.25rem 0.5rem; border-radius: 4px; border: 1px solid var(--color-border);">Ctrl</kbd> + <kbd style="background: var(--color-surface); padding: 0.25rem 0.5rem; border-radius: 4px; border: 1px solid var(--color-border);">K</kbd> to Quick Search
    </div>
</section>

<?php if (!empty($popularByViews) || !empty($popularByTime)): ?>
    <section class="category-section" id="cat-popular">
        <div class="category-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <i class="fa-solid fa-fire" style="color: #EF4444;"></i> Popular Tools
            </div>
            
            <!-- Filters Toggle -->
            <div style="display: flex; gap: 0.5rem; background: var(--color-background); border: 1px solid var(--color-border); padding: 0.25rem; border-radius: var(--radius-full);">
                <button id="pop-filter-views" class="btn active-tab" style="font-size: 0.8rem; padding: 0.35rem 1rem; border-radius: var(--radius-full); border: none; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                    <i class="fa-solid fa-eye"></i> Most Viewed
                </button>
                <button id="pop-filter-time" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.35rem 1rem; border-radius: var(--radius-full); border: none; font-weight: 600; cursor: pointer; transition: all 0.2s; background: transparent; color: var(--color-text-secondary);">
                    <i class="fa-solid fa-clock"></i> Most Used
                </button>
            </div>
        </div>

        <!-- Grid 1: Popular by Views -->
        <div class="tools-grid" id="pop-grid-views">
            <?php foreach ($popularByViews as $tool): ?>
                <a href="<?= \App\Config\App::url('/tool/' . $tool['slug']) ?>" class="tool-card">
                    <div class="tool-icon-wrapper">
                        <?php if ($tool['icon']): ?>
                            <i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-cube"></i>
                        <?php endif; ?>
                    </div>
                    <h3><?= htmlspecialchars($tool['name']) ?></h3>
                    <p style="margin-bottom: 1rem; flex-grow: 1;"><?= htmlspecialchars($tool['description'] ?? 'Click to use this tool.') ?></p>
                    <div style="margin-top: auto; display: flex; gap: 0.75rem; font-size: 0.75rem; color: var(--color-text-secondary); opacity: 0.8; padding-top: 0.5rem; border-top: 1px dashed var(--color-border); align-items: center; width: 100%;">
                        <span><i class="fa-solid fa-eye" style="font-size: 0.7rem; margin-right: 0.15rem;"></i> <?= number_format($tool['total_views'] ?? 0) ?> views</span>
                        <span><i class="fa-solid fa-clock" style="font-size: 0.7rem; margin-right: 0.15rem;"></i> 
                            <?php 
                                $s = $tool['total_seconds'] ?? 0;
                                if ($s < 60) {
                                    echo $s . 's spent';
                                } else {
                                    echo round($s / 60) . 'm spent';
                                }
                            ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Grid 2: Popular by Time (Hidden by default) -->
        <div class="tools-grid" id="pop-grid-time" style="display: none;">
            <?php foreach ($popularByTime as $tool): ?>
                <a href="<?= \App\Config\App::url('/tool/' . $tool['slug']) ?>" class="tool-card">
                    <div class="tool-icon-wrapper">
                        <?php if ($tool['icon']): ?>
                            <i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-cube"></i>
                        <?php endif; ?>
                    </div>
                    <h3><?= htmlspecialchars($tool['name']) ?></h3>
                    <p style="margin-bottom: 1rem; flex-grow: 1;"><?= htmlspecialchars($tool['description'] ?? 'Click to use this tool.') ?></p>
                    <div style="margin-top: auto; display: flex; gap: 0.75rem; font-size: 0.75rem; color: var(--color-text-secondary); opacity: 0.8; padding-top: 0.5rem; border-top: 1px dashed var(--color-border); align-items: center; width: 100%;">
                        <span><i class="fa-solid fa-eye" style="font-size: 0.7rem; margin-right: 0.15rem;"></i> <?= number_format($tool['total_views'] ?? 0) ?> views</span>
                        <span><i class="fa-solid fa-clock" style="font-size: 0.7rem; margin-right: 0.15rem;"></i> 
                            <?php 
                                $s = $tool['total_seconds'] ?? 0;
                                if ($s < 60) {
                                    echo $s . 's spent';
                                } else {
                                    echo round($s / 60) . 'm spent';
                                }
                            ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Small Javascript to drive popular filter toggle -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const btnViews = document.getElementById("pop-filter-views");
        const btnTime = document.getElementById("pop-filter-time");
        const gridViews = document.getElementById("pop-grid-views");
        const gridTime = document.getElementById("pop-grid-time");

        if (btnViews && btnTime) {
            btnViews.addEventListener("click", function () {
                btnViews.classList.add("active-tab");
                btnViews.style.background = "";
                btnViews.style.color = "";
                
                btnTime.classList.remove("active-tab");
                btnTime.style.background = "transparent";
                btnTime.style.color = "var(--color-text-secondary)";

                gridViews.style.display = "grid";
                gridTime.style.display = "none";
            });

            btnTime.addEventListener("click", function () {
                btnTime.classList.add("active-tab");
                btnTime.style.background = "";
                btnTime.style.color = "";

                btnViews.classList.remove("active-tab");
                btnViews.style.background = "transparent";
                btnViews.style.color = "var(--color-text-secondary)";

                gridViews.style.display = "none";
                gridTime.style.display = "grid";
            });
        }
    });
    </script>
<?php endif; ?>

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
                        <p style="margin-bottom: 1rem; flex-grow: 1;"><?= htmlspecialchars($tool['description'] ?? 'Click to use this tool.') ?></p>
                        <div style="margin-top: auto; display: flex; gap: 0.75rem; font-size: 0.75rem; color: var(--color-text-secondary); opacity: 0.8; padding-top: 0.5rem; border-top: 1px dashed var(--color-border); align-items: center; width: 100%;">
                            <span><i class="fa-solid fa-eye" style="font-size: 0.7rem; margin-right: 0.15rem;"></i> <?= number_format($tool['total_views'] ?? 0) ?> views</span>
                            <span><i class="fa-solid fa-clock" style="font-size: 0.7rem; margin-right: 0.15rem;"></i> 
                                <?php 
                                    $s = $tool['total_seconds'] ?? 0;
                                    if ($s < 60) {
                                        echo $s . 's spent';
                                    } else {
                                        echo round($s / 60) . 'm spent';
                                    }
                                ?>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endforeach; ?>
