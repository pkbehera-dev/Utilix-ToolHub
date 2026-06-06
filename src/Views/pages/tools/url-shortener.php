<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h1><i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i> <?= htmlspecialchars($tool['name']) ?></h1>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description']) ?></p>
</div>

<div class="tool-content" style="max-width: 600px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border);">
    <form id="shortener-form">
        <input type="hidden" name="csrf_token" id="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
        
        <div style="margin-bottom: 20px;">
            <label for="long_url" style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text-primary);">Paste your long URL here:</label>
            <input type="url" id="long_url" name="long_url" required placeholder="https://example.com/very/long/url..." style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-size: 1rem;">
        </div>
        
        <div style="margin-bottom: 20px;">
            <label for="alias" style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text-primary);">Custom Alias (Optional):</label>
            <input type="text" id="alias" name="alias" placeholder="my-custom-link" pattern="[a-zA-Z0-9\-]+" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-size: 1rem;">
            <small style="color: var(--color-text-secondary); display: block; margin-top: 4px;">Letters, numbers, and dashes only.</small>
        </div>

        <button type="submit" id="submit-btn" style="width: 100%; padding: 14px; background: var(--color-primary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-size: 1.1rem; font-weight: 600; transition: background-color var(--transition-fast);">
            Shorten URL
        </button>
    </form>

    <div id="result-container" style="display: none; margin-top: 30px; padding: 20px; background: color-mix(in srgb, var(--color-success) 8%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-success) 30%, var(--color-border)); border-radius: var(--radius-md); text-align: center;">
        <h4 style="color: var(--color-success); margin-bottom: 12px; font-weight: 600;">Success! Your short URL is ready:</h4>
        <div style="display: flex; gap: 10px; justify-content: center; align-items: center; flex-wrap: wrap;">
            <input type="text" id="short-url-output" readonly style="padding: 10px 14px; width: 70%; min-width: 200px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 1.05rem; color: var(--color-text-primary); background: var(--color-background);">
            <button id="copy-url-btn" style="padding: 10px 20px; background: var(--color-success); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-weight: 600;"><i class="fa-solid fa-copy"></i> Copy</button>
        </div>
    </div>
    
    <div id="error-container" style="display: none; margin-top: 20px; padding: 15px; background: color-mix(in srgb, var(--color-danger) 10%, var(--color-surface)); color: var(--color-danger); border: 1px solid var(--color-danger); border-radius: var(--radius-md);">
    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 600px; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">Smart Link Protection:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            Short links are ideal for social media bios, emails, or SMS where space is limited. This tool is integrated with the <strong>Google Safe Browsing API</strong> to block phishing, spam, or malware URLs before redirect links are generated. Any short link redirect can be stopped or deactivated at any time, and click history/analytics can be deleted by the creator whenever needed.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('shortener-form');
    const submitBtn = document.getElementById('submit-btn');
    const resultContainer = document.getElementById('result-container');
    const errorContainer = document.getElementById('error-container');
    const shortUrlOutput = document.getElementById('short-url-output');
    const copyUrlBtn = document.getElementById('copy-url-btn');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Shortening...';
        resultContainer.style.display = 'none';
        errorContainer.style.display = 'none';

        const formData = new FormData(form);

        try {
            const response = await fetch('<?= \App\Config\App::url('/api/shorten') ?>', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                shortUrlOutput.value = data.short_url;
                resultContainer.style.display = 'block';
                form.reset();
            } else {
                errorContainer.textContent = data.message || 'An error occurred.';
                errorContainer.style.display = 'block';
            }
        } catch (error) {
            errorContainer.textContent = 'A network error occurred. Please try again.';
            errorContainer.style.display = 'block';
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Shorten URL';
        }
    });

    copyUrlBtn.addEventListener('click', () => {
        shortUrlOutput.select();
        document.execCommand('copy');
        const original = copyUrlBtn.innerHTML;
        copyUrlBtn.innerHTML = '<i class="fa-solid fa-check"></i>';
        setTimeout(() => copyUrlBtn.innerHTML = original, 2000);
    });
});
</script>
