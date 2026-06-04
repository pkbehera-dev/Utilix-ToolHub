<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h2><i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i> <?= htmlspecialchars($tool['name']) ?></h2>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description']) ?></p>
</div>

<div class="tool-content" style="max-width: 800px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border);">
    <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
        <button id="btn-upper" class="btn-action" style="flex: 1; padding: 10px; cursor: pointer; border-radius: var(--radius-md); border: 1px solid var(--color-primary); background: transparent; color: var(--color-primary); font-weight: 600;">UPPERCASE</button>
        <button id="btn-lower" class="btn-action" style="flex: 1; padding: 10px; cursor: pointer; border-radius: var(--radius-md); border: 1px solid var(--color-primary); background: transparent; color: var(--color-primary); font-weight: 600;">lowercase</button>
        <button id="btn-title" class="btn-action" style="flex: 1; padding: 10px; cursor: pointer; border-radius: var(--radius-md); border: 1px solid var(--color-primary); background: transparent; color: var(--color-primary); font-weight: 600;">Title Case</button>
        <button id="btn-sentence" class="btn-action" style="flex: 1; padding: 10px; cursor: pointer; border-radius: var(--radius-md); border: 1px solid var(--color-primary); background: transparent; color: var(--color-primary); font-weight: 600;">Sentence case</button>
    </div>

    <textarea id="text-input" placeholder="Enter text here to convert..." style="width: 100%; height: 300px; padding: 15px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 1rem; background: var(--color-background); color: var(--color-text-primary); resize: vertical; font-family: inherit;"></textarea>
    
    <div style="margin-top: 15px; text-align: right;">
        <button id="copy-btn" style="padding: 12px 24px; background: var(--color-primary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-weight: 600;"><i class="fa-solid fa-copy"></i> Copy to Clipboard</button>
    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 800px; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">Case Selection Guide:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            Use <strong>Title Case</strong> for titles and headings, <strong>Sentence Case</strong> for standard descriptions and paragraphs, and <strong>UPPERCASE</strong> / <strong>lowercase</strong> for database key formatting or style manipulation.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('text-input');
    const copyBtn = document.getElementById('copy-btn');

    document.getElementById('btn-upper').addEventListener('click', () => {
        input.value = input.value.toUpperCase();
    });

    document.getElementById('btn-lower').addEventListener('click', () => {
        input.value = input.value.toLowerCase();
    });

    document.getElementById('btn-title').addEventListener('click', () => {
        input.value = input.value.toLowerCase().split(' ').map(word => {
            return word.charAt(0).toUpperCase() + word.slice(1);
        }).join(' ');
    });

    document.getElementById('btn-sentence').addEventListener('click', () => {
        input.value = input.value.toLowerCase().replace(/(^\s*\w|[.!?]\s*\w)/g, c => c.toUpperCase());
    });

    copyBtn.addEventListener('click', () => {
        if (!input.value) return;
        navigator.clipboard.writeText(input.value).then(() => {
            const original = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
            setTimeout(() => copyBtn.innerHTML = original, 2000);
        });
    });
});
</script>
