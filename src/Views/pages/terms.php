<?php
if (!defined('SECURE_ACCESS')) {
    http_response_code(403);
    die('Direct access not allowed.');
}
/**
 * Terms of Service Page
 */
?>
<div class="legal-container">
    <div class="legal-header">
        <h1 class="legal-title">Terms of Service</h1>
        <p class="legal-subtitle">Last updated: June 03, 2026</p>
    </div>

    <div class="legal-layout">
        <!-- Sidebar Navigation -->
        <aside class="legal-sidebar">
            <nav class="legal-nav">
                <a href="#acceptance" class="nav-link active">1. Acceptance of Terms</a>
                <a href="#use-license" class="nav-link">2. Use License</a>
                <a href="#user-conduct" class="nav-link">3. User Conduct</a>
                <a href="#disclaimer" class="nav-link">4. Disclaimer</a>
                <a href="#limitations" class="nav-link">5. Limitations</a>
                <a href="#governing-law" class="nav-link">6. Governing Law</a>
                <a href="#modifications" class="nav-link">7. Modifications</a>
                <a href="#contact" class="nav-link">8. Contact Us</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <article class="legal-content">
            <section id="acceptance">
                <h2>1. Acceptance of Terms</h2>
                <p>By accessing and using <?= htmlspecialchars(\App\Config\App::siteName()) ?> ("we", "our", or "us"), you acknowledge that you have read, understood, and agree to be bound by these Terms of Service, along with our Privacy Policy.</p>
                <p>If you do not agree with any part of these terms, you are prohibited from using or accessing this site or any of its tools. We recommend that you print or save a local copy of these terms for your records.</p>
            </section>

            <section id="use-license">
                <h2>2. Use License</h2>
                <p>Permission is granted to temporarily use the online utility tools and resources provided on <?= htmlspecialchars(\App\Config\App::siteName()) ?> for personal, non-commercial, or commercial utility purposes. Under this license, you may not:</p>
                <ul>
                    <li>Modify, copy, or redistribute the source code of the tools without authorization.</li>
                    <li>Use the materials or tools for any unlawful purpose.</li>
                    <li>Attempt to decompile, reverse engineer, or exploit security bugs in any software contained on the website.</li>
                    <li>Remove any copyright or other proprietary notations from the materials.</li>
                </ul>
                <p>This license shall automatically terminate if you violate any of these restrictions and may be terminated by us at any time.</p>
            </section>

            <section id="user-conduct">
                <h2>3. User Conduct</h2>
                <p>You agree to use our website and tools only for lawful purposes and in a way that does not infringe the rights of, restrict, or inhibit anyone else's use and enjoyment of the website. Prohibited behavior includes:</p>
                <ul>
                    <li>Using tools (e.g. URL shortener) to distribute spam, malware, phishing links, or copyrighted material without permission.</li>
                    <li>Automating queries or scraping tool responses in a manner that degrades performance for other users.</li>
                    <li>Impacting the security or integrity of our systems.</li>
                </ul>
            </section>

            <section id="disclaimer">
                <h2>4. Disclaimer</h2>
                <p>The materials and tools on <?= htmlspecialchars(\App\Config\App::siteName()) ?> are provided on an 'as is' basis. We make no warranties, expressed or implied, and hereby disclaim and negate all other warranties including, without limitation, implied warranties of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>
                <p>Further, we do not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the tools or materials on this website or otherwise relating to such materials or on any sites linked to this site.</p>
            </section>

            <section id="limitations">
                <h2>5. Limitations of Liability</h2>
                <p>In no event shall <?= htmlspecialchars(\App\Config\App::siteName()) ?> or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the tools on <?= htmlspecialchars(\App\Config\App::siteName()) ?>, even if we have been notified orally or in writing of the possibility of such damage.</p>
            </section>

            <section id="governing-law">
                <h2>6. Governing Law</h2>
                <p>These terms and conditions are governed by and construed in accordance with the laws of the jurisdiction in which our company is registered, and you irrevocably submit to the exclusive jurisdiction of the courts in that State or location.</p>
            </section>

            <section id="modifications">
                <h2>7. Modifications to Terms</h2>
                <p>We may revise these terms of service for our website at any time without notice. By using this website, you are agreeing to be bound by the then-current version of these Terms of Service.</p>
                <p>We recommend checking this page periodically to remain informed of any changes.</p>
            </section>

            <section id="contact">
                <h2>8. Contact Us</h2>
                <p>If you have any questions or concerns about these Terms of Service, please reach out to us:</p>
                <p><strong>Email:</strong> <?= htmlspecialchars(\App\Config\App::supportEmail()) ?></p>
                <p><strong>Telegram:</strong> <a href="https://t.me/pkbehera_dev" target="_blank" rel="noopener noreferrer">@pkbehera_dev</a></p>
                <p><strong>Address:</strong> Odisha, India</p>
            </section>
        </article>
    </div>
</div>

<style>
.legal-container {
    max-width: 1000px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.legal-header {
    text-align: center;
    margin-bottom: 3rem;
}

.legal-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    letter-spacing: -0.02em;
}

.legal-subtitle {
    color: var(--color-text-secondary);
    font-size: 0.95rem;
}

.legal-layout {
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 3rem;
}

.legal-sidebar {
    position: sticky;
    top: calc(header.height + 2rem);
    align-self: start;
    height: auto;
}

.legal-nav {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.nav-link {
    display: block;
    padding: 0.6rem 1rem;
    color: var(--color-text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: var(--radius-sm);
    transition: all var(--transition-fast);
}

.nav-link:hover {
    color: var(--color-text-primary);
    background-color: var(--color-surface);
}

.nav-link.active {
    color: var(--color-primary);
    background-color: color-mix(in srgb, var(--color-primary) 8%, transparent);
    font-weight: 600;
}

.legal-content {
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
}

.legal-content section {
    margin-bottom: 3rem;
    scroll-margin-top: 100px;
}

.legal-content section:last-child {
    margin-bottom: 0;
}

.legal-content h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--color-text-primary);
    border-bottom: 1px solid var(--color-border);
    padding-bottom: 0.5rem;
}

.legal-content p {
    font-size: 0.975rem;
    color: var(--color-text-secondary);
    line-height: 1.6;
    margin-bottom: 1rem;
}

.legal-content ul {
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;
}

.legal-content li {
    font-size: 0.975rem;
    color: var(--color-text-secondary);
    margin-bottom: 0.5rem;
    line-height: 1.5;
}

.legal-content strong {
    color: var(--color-text-primary);
}

@media (max-width: 768px) {
    .legal-layout {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    .legal-sidebar {
        display: none;
    }
    .legal-content {
        padding: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('.legal-content section');
    const navLinks = document.querySelectorAll('.nav-link');

    // Smooth scroll for nav links
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            if (targetSection) {
                targetSection.scrollIntoView({ behavior: 'smooth' });
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    // Intersection Observer to highlight active section in sidebar
    const observerOptions = {
        root: null,
        rootMargin: '-10% 0px -80% 0px',
        threshold: 0
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                navLinks.forEach(link => {
                    if (link.getAttribute('href') === '#' + id) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                });
            }
        });
    }, observerOptions);

    sections.forEach(section => observer.observe(section));
});
</script>
