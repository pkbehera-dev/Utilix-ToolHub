<?php
if (!defined('SECURE_ACCESS')) {
    http_response_code(403);
    die('Direct access not allowed.');
}
/**
 * Privacy Policy Page
 */
?>
<div class="legal-container">
    <div class="legal-header">
        <h1 class="legal-title">Privacy Policy</h1>
        <p class="legal-subtitle">Last updated: June 03, 2026</p>
    </div>

    <div class="legal-layout">
        <!-- Sidebar Navigation -->
        <aside class="legal-sidebar">
            <nav class="legal-nav">
                <a href="#introduction" class="nav-link active">1. Introduction</a>
                <a href="#information-collect" class="nav-link">2. Info We Collect</a>
                <a href="#information-use" class="nav-link">3. How We Use Info</a>
                <a href="#data-storage" class="nav-link">4. Data Retention</a>
                <a href="#user-rights" class="nav-link">5. Your Rights</a>
                <a href="#cookies" class="nav-link">6. Cookies Policy</a>
                <a href="#contact" class="nav-link">7. Contact Us</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <article class="legal-content">
            <section id="introduction">
                <h2>1. Introduction</h2>
                <p>Welcome to <?= htmlspecialchars(\App\Config\App::siteName()) ?> ("we", "our", or "us"). We are committed to protecting your privacy and ensuring that your personal information is handled in a safe and responsible manner.</p>
                <p>This Privacy Policy outlines how we collect, use, store, and share information when you use our website, tools, and services. By accessing or using <?= htmlspecialchars(\App\Config\App::siteName()) ?>, you consent to the data practices described in this policy.</p>
            </section>

            <section id="information-collect">
                <h2>2. Information We Collect</h2>
                <p>We only collect the minimum amount of information necessary to provide and improve our online utility tools. The types of information we may collect include:</p>
                <ul>
                    <li><strong>Usage Data:</strong> Information about your device, browser, IP address, referral sources, and how you interact with our tools.</li>
                    <li><strong>Tool Inputs:</strong> Content, files, passwords, or data you temporarily upload or paste into our tools to process them. <em>Note: Unless explicitly stated or saved for short periods (like URL shortening), this data is processed in-memory and not stored on our servers.</em></li>
                </ul>
            </section>


            <section id="information-use">
                <h2>3. How We Use Information</h2>
                <p>We use the collected information for various purposes, including to:</p>
                <ul>
                    <li>Provide, operate, and maintain our site and tools.</li>
                    <li>Analyze and monitor usage trends to improve user experience.</li>
                    <li>Generate shortened URLs and track redirect statistics.</li>
                    <li>Detect, prevent, and address technical issues or malicious activity.</li>
                </ul>
            </section>

            <section id="data-storage">
                <h2>4. Data Retention & Security</h2>
                <p>We value the security of your data. We implement industry-standard administrative, physical, and technical measures to protect your information from unauthorized access, disclosure, alteration, or destruction.</p>
                <p>For most online tools (e.g., JSON Formatter, Password Generator), your inputs are processed entirely client-side or in-memory, meaning they are immediately discarded after the request completes. For services that require database storage (e.g., URL Shortener), we retain the records indefinitely or until requested to delete them.</p>
            </section>

            <section id="user-rights">
                <h2>5. Your Rights</h2>
                <p>Depending on your location (such as under the GDPR or CCPA), you may have the following rights regarding your personal information:</p>
                <ul>
                    <li>The right to access the personal information we hold about you.</li>
                    <li>The right to request that we correct any inaccurate information.</li>
                    <li>The right to request the erasure of your personal data.</li>
                    <li>The right to object to or restrict our processing of your data.</li>
                </ul>
                <p>To exercise any of these rights, please contact us using the details provided below.</p>
            </section>

            <section id="cookies">
                <h2>6. Cookies Policy</h2>
                <p>We use cookies and similar tracking technologies to track the activity on our service and hold certain information. Cookies are files with a small amount of data which may include an anonymous unique identifier.</p>
                <p>You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our service.</p>
            </section>

            <section id="contact">
                <h2>7. Contact Us</h2>
                <p>If you have any questions or concerns about this Privacy Policy, please reach out to us:</p>
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
