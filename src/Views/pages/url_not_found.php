<div class="url-error-container" style="max-width: 500px; margin: 4rem auto; text-align: center; padding: 2rem;">
    <div class="url-error-card" style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 3rem; box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);">
        
        <!-- Icon -->
        <div class="url-error-icon-wrapper" style="width: 80px; height: 80px; background: color-mix(in srgb, var(--color-danger) 8%, var(--color-surface)); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem auto;">
            <i class="fa-solid fa-link-slash" style="color: var(--color-danger); font-size: 2.25rem;"></i>
        </div>

        <!-- Heading -->
        <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--color-text-primary); margin-bottom: 1rem; letter-spacing: -0.02em;">Link is Wrong or Removed</h2>
        
        <!-- Description -->
        <p style="color: var(--color-text-secondary); font-size: 0.95rem; line-height: 1.6; margin-bottom: 2.5rem;">
            The short URL you are trying to access is incorrect, expired, or has been deleted by Admin.
        </p>

        <!-- CTA Buttons -->
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="<?= \App\Config\App::url('/tool/url-shortener') ?>" class="btn btn-primary" style="display: block; padding: 12px; font-weight: 600; text-align: center; text-decoration: none; border-radius: var(--radius-md);">
                Create Your Own Short Link
            </a>
            <a href="<?= \App\Config\App::url('/') ?>" class="btn btn-secondary" style="display: block; padding: 12px; font-weight: 600; text-align: center; text-decoration: none; border-radius: var(--radius-md);">
                Return to Homepage
            </a>
        </div>
    </div>
</div>
