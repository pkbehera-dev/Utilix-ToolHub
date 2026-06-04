<div style="background: var(--card-bg); padding: 40px; border-radius: var(--radius); text-align: center; border: 1px solid var(--border-color);">
    <i class="fa-solid fa-person-digging" style="font-size: 4rem; color: var(--primary-color); margin-bottom: 20px;"></i>
    <h2><?= htmlspecialchars($tool['name']) ?></h2>
    <p style="color: var(--text-muted); margin-top: 10px;">This tool is currently under construction and will be available soon.</p>
    <a href="<?= \App\Config\App::url('/') ?>" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: var(--primary-color); color: white; border-radius: var(--radius);">Back to Home</a>
</div>
