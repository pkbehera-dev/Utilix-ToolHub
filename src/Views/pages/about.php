<?php
/**
 * About Page
 */
?>
<div class="about-container">
    <div class="about-header text-center">
        <h1 class="about-title">About <?= htmlspecialchars(\App\Config\App::siteName()) ?></h1>
        <p class="about-subtitle">A Personal Project & Open Utility Belt</p>
    </div>

    <div class="about-content">
        <div class="about-section">
            <h2>The Project</h2>
            <p><strong><?= htmlspecialchars(\App\Config\App::siteName()) ?></strong> is a modern online utility platform designed to provide developers and daily web users with a premium, secure suite of tools right in the browser. What started as a personal utility belt has grown into a structured, highly performant web application.</p>
            <p>Unlike massive utility aggregators that are loaded with tracker scripts, intrusive ads, and cookie notices, <?= htmlspecialchars(\App\Config\App::siteName()) ?> is built with a <strong>privacy-first mindset</strong>. Most tools process data entirely client-side, and no sensitive details are ever cached or stored without your explicit consent.</p>
        </div>

        <div class="about-section">
            <h2>Custom Architecture (No Frameworks)</h2>
            <p>To ensure maximum speed, portability, and zero footprint, this site does not rely on heavy frameworks like Laravel or React. Instead, it runs on a bespoke, lightweight MVC system built using:</p>
            <ul class="about-list">
                <li><strong>Backend:</strong> Raw PHP with custom Router, CSRF protection, native TOTP-based 2FA, and rate limiting.</li>
                <li><strong>Database:</strong> Native PDO mapping for fast queries.</li>
                <li><strong>Frontend:</strong> Clean HTML5, Vanilla JavaScript, and native CSS variables for styling and dark mode sync.</li>
            </ul>
        </div>

        <div class="about-section highlight-box">
            <h2>Open for Development & Contributions</h2>
            <p><?= htmlspecialchars(\App\Config\App::siteName()) ?> is an <strong>active personal project</strong> and is completely open-source! We welcome developers, creators, and tinkerers to add new tools, optimize existing code, or refine the responsive design.</p>
            <div class="about-cta flex gap-4 mt-6">
                <a href="https://github.com/pkbehera-dev/ToolBox" target="_blank" class="btn btn-primary flex items-center gap-2">
                    <i class="fa-brands fa-github"></i> View GitHub Repository
                </a>
                <a href="mailto:<?= htmlspecialchars(\App\Config\App::supportEmail()) ?>" class="btn btn-secondary flex items-center gap-2">
                    <i class="fa-solid fa-envelope"></i> Get in Touch
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.about-container {
    max-width: 800px;
    margin: 3rem auto;
    padding: 0 1rem;
}

.about-header {
    margin-bottom: 3rem;
}

.about-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    letter-spacing: -0.02em;
    background: linear-gradient(135deg, var(--color-primary), #6366f1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.about-subtitle {
    color: var(--color-text-secondary);
    font-size: 1.1rem;
}

.about-content {
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    padding: 3rem;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
}

.about-section {
    margin-bottom: 2.5rem;
}

.about-section:last-child {
    margin-bottom: 0;
}

.about-section h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--color-text-primary);
}

.about-section p {
    font-size: 1rem;
    color: var(--color-text-secondary);
    line-height: 1.6;
    margin-bottom: 1.2rem;
}

.about-list {
    padding-left: 1.5rem;
    margin-bottom: 1.5rem;
}

.about-list li {
    font-size: 0.95rem;
    color: var(--color-text-secondary);
    margin-bottom: 0.6rem;
    line-height: 1.5;
}

.highlight-box {
    background: color-mix(in srgb, var(--color-primary) 4%, var(--color-surface));
    border: 1px dashed color-mix(in srgb, var(--color-primary) 30%, var(--color-border));
    padding: 2rem;
    border-radius: var(--radius-md);
    margin-top: 3rem;
}

.highlight-box h2 {
    color: var(--color-primary);
}

.about-cta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.flex {
    display: flex;
}
.gap-4 {
    gap: 1rem;
}
.gap-2 {
    gap: 0.5rem;
}
.items-center {
    align-items: center;
}
.mt-6 {
    margin-top: 1.5rem;
}

@media (max-width: 600px) {
    .about-content {
        padding: 1.5rem;
    }
    .about-cta .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
