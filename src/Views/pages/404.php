<?php
/**
 * 404 Page Not Found
 */
?>
<div class="error-container">
    <div class="error-glow"></div>
    <div class="error-content">
        <span class="error-badge">404 Error</span>
        <h1 class="error-title">Lost in Space</h1>
        <p class="error-text">The page you're looking for doesn't exist or has been moved. Use the options below to find your way back.</p>
        
        <div class="error-actions">
            <a href="<?= \App\Config\App::url('/') ?>" class="btn btn-primary">
                <i class="fa-solid fa-house"></i> Back to Homepage
            </a>
            <button onclick="document.getElementById('header-search-btn').click();" class="btn btn-secondary">
                <i class="fa-solid fa-magnifying-glass"></i> Search Tools
            </button>
        </div>
        
        <div class="error-suggestions-grid">
            <a href="<?= \App\Config\App::url('/tool/password-generator') ?>" class="suggestion-card">
                <div class="suggestion-icon"><i class="fa-solid fa-key"></i></div>
                <div>
                    <h4>Password Generator</h4>
                    <p>Create secure, random passwords instantly.</p>
                </div>
            </a>
            <a href="<?= \App\Config\App::url('/tool/base64-encoder-decoder') ?>" class="suggestion-card">
                <div class="suggestion-icon"><i class="fa-solid fa-code"></i></div>
                <div>
                    <h4>Base64 Encoder/Decoder</h4>
                    <p>Encode or decode text to/from Base64.</p>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
.error-container {
    position: relative;
    max-width: 650px;
    margin: 5rem auto;
    padding: 3rem 2rem;
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    text-align: center;
    overflow: hidden;
    box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.05);
}

[data-theme="dark"] .error-container {
    box-shadow: 0 20px 45px -15px rgba(0, 0, 0, 0.3);
}

.error-glow {
    position: absolute;
    top: -50px;
    left: 50%;
    transform: translateX(-50%);
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, color-mix(in srgb, var(--color-primary) 15%, transparent) 0%, transparent 70%);
    pointer-events: none;
    z-index: 1;
}

.error-content {
    position: relative;
    z-index: 2;
}

.error-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-primary);
    background: color-mix(in srgb, var(--color-primary) 10%, transparent);
    border-radius: var(--radius-full);
    margin-bottom: 1.5rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.error-title {
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
}

.error-text {
    font-size: 1.125rem;
    color: var(--color-text-secondary);
    margin-bottom: 2rem;
    line-height: 1.6;
}

.error-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 3rem;
}

.error-suggestions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    text-align: left;
    border-top: 1px solid var(--color-border);
    padding-top: 2rem;
}

.suggestion-card {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: var(--color-background);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    text-decoration: none;
    color: inherit;
    transition: transform var(--transition-fast), border-color var(--transition-fast), box-shadow var(--transition-fast);
}

.suggestion-card:hover {
    transform: translateY(-2px);
    border-color: var(--color-primary);
    color: inherit;
    box-shadow: var(--shadow-sm);
}

.suggestion-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    min-width: 2.5rem;
    border-radius: var(--radius-sm);
    background: color-mix(in srgb, var(--color-primary) 10%, transparent);
    color: var(--color-primary);
    font-size: 1.125rem;
}

.suggestion-card h4 {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--color-text-primary);
}

.suggestion-card p {
    font-size: 0.8rem;
    color: var(--color-text-secondary);
    line-height: 1.4;
}

@media (max-width: 600px) {
    .error-actions {
        flex-direction: column;
    }
    .error-suggestions-grid {
        grid-template-columns: 1fr;
    }
}
</style>
