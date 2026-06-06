<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h1><i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i> <?= htmlspecialchars($tool['name']) ?></h1>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description']) ?></p>
</div>

<div class="tool-content" style="display: flex; gap: 20px; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 300px; background: var(--color-surface); padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--color-border);">
        <h3 style="margin-bottom: 15px; color: var(--color-text-primary);">Input JSON</h3>
        <textarea id="json-input" placeholder="Paste your raw JSON here..." style="width: 100%; height: 400px; padding: 15px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-family: monospace; font-size: 0.9rem; background: var(--color-background); color: var(--color-text-primary); resize: vertical;"></textarea>
        <button id="format-btn" style="margin-top: 15px; width: 100%; padding: 12px; background: var(--color-primary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-size: 1rem; font-weight: 600;"><i class="fa-solid fa-code"></i> Format JSON</button>
    </div>
    
    <div style="flex: 1; min-width: 300px; background: var(--color-surface); padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="color: var(--color-text-primary);">Formatted Result</h3>
            <button id="copy-btn" style="padding: 6px 12px; background: var(--color-background); border: 1px solid var(--color-border); color: var(--color-text-primary); border-radius: var(--radius-md); cursor: pointer; font-weight: 500;"><i class="fa-solid fa-copy"></i> Copy</button>
        </div>
        <div id="error-msg" style="display: none; background: color-mix(in srgb, var(--color-danger) 10%, var(--color-surface)); color: var(--color-danger); padding: 10px; border: 1px solid var(--color-danger); border-radius: var(--radius-md); margin-bottom: 15px; font-size: 0.9rem;"></div>
        <textarea id="json-output" readonly style="width: 100%; height: 400px; padding: 15px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-family: monospace; font-size: 0.9rem; background: var(--color-background); color: var(--color-text-primary); resize: vertical;"></textarea>
    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 100%; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">About JSON Formatting:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            JSON (JavaScript Object Notation) is a lightweight file and data interchange format. Formatting raw, minified JSON makes it human-readable by expanding keys with spacing and indented nesting (2 or 4 spaces). This tool validates JSON structure during conversion.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const jsonInput = document.getElementById('json-input');
    const jsonOutput = document.getElementById('json-output');
    const formatBtn = document.getElementById('format-btn');
    const copyBtn = document.getElementById('copy-btn');
    const errorMsg = document.getElementById('error-msg');

    formatBtn.addEventListener('click', () => {
        const rawJson = jsonInput.value.trim();
        if (!rawJson) {
            errorMsg.textContent = 'Please enter JSON to format.';
            errorMsg.style.display = 'block';
            jsonOutput.value = '';
            return;
        }

        try {
            const parsed = JSON.parse(rawJson);
            const formatted = JSON.stringify(parsed, null, 4);
            jsonOutput.value = formatted;
            errorMsg.style.display = 'none';
        } catch (e) {
            errorMsg.textContent = 'Invalid JSON: ' + e.message;
            errorMsg.style.display = 'block';
            jsonOutput.value = '';
        }
    });

    copyBtn.addEventListener('click', () => {
        if (!jsonOutput.value) return;
        navigator.clipboard.writeText(jsonOutput.value).then(() => {
            const originalText = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
            setTimeout(() => {
                copyBtn.innerHTML = originalText;
            }, 2000);
        });
    });
});
</script>
