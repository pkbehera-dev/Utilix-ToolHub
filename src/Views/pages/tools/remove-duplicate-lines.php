<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h1><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-align-left') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'Remove Duplicate Lines') ?></h1>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'Remove duplicate lines from your text, lists, or code files instantly.') ?></p>
</div>

<div class="tool-content" style="display: flex; gap: 20px; flex-wrap: wrap; text-align: left;">
    
    <!-- Controls & Input Panel -->
    <div style="flex: 1.2; min-width: 320px; background: var(--color-surface); padding: 25px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); display: flex; flex-direction: column; gap: 20px;">
        
        <div>
            <h3 style="margin-bottom: 12px; color: var(--color-text-primary); font-size: 1.1rem; font-weight: 700;">Input Text</h3>
            <textarea id="lines-input" placeholder="Paste your lines of text here..." style="width: 100%; height: 280px; padding: 15px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-family: monospace; font-size: 0.95rem; background: var(--color-background); color: var(--color-text-primary); resize: vertical;"></textarea>
        </div>

        <!-- Options Checkboxes -->
        <div>
            <h4 style="margin-bottom: 10px; color: var(--color-text-primary); font-size: 0.9rem; font-weight: 600;">Processing Options</h4>
            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.9rem; color: var(--color-text-secondary);">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" id="opt-case-sensitive" style="accent-color: var(--color-primary);" checked> Case Sensitive (treat 'Apple' and 'apple' as different)
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" id="opt-trim" style="accent-color: var(--color-primary);" checked> Trim spaces (remove leading & trailing whitespace before comparison)
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" id="opt-remove-empty" style="accent-color: var(--color-primary);" checked> Remove completely empty/blank lines
                </label>
            </div>
        </div>

        <!-- Action Button -->
        <button id="process-btn" style="width: 100%; padding: 14px; background: var(--color-primary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-size: 1.1rem; font-weight: 600;">
            Remove Duplicates
        </button>

    </div>
    
    <!-- Output Panel -->
    <div style="flex: 1; min-width: 300px; background: var(--color-surface); padding: 25px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); display: flex; flex-direction: column; justify-content: space-between;">
        
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="color: var(--color-text-primary); font-size: 1.1rem; font-weight: 700;">Cleaned Text</h3>
                <button id="copy-btn" style="padding: 6px 12px; background: var(--color-background); border: 1px solid var(--color-border); color: var(--color-text-primary); border-radius: var(--radius-md); cursor: pointer; font-weight: 500;"><i class="fa-solid fa-copy"></i> Copy</button>
            </div>
            <textarea id="lines-output" readonly placeholder="Cleaned output will appear here..." style="width: 100%; height: 280px; padding: 15px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-family: monospace; font-size: 0.95rem; background: var(--color-background); color: var(--color-text-primary); resize: vertical;"></textarea>
        </div>

        <!-- Reduction Statistics -->
        <div style="margin-top: 20px; background: var(--color-background); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 15px; font-size: 0.9rem; color: var(--color-text-secondary); display: flex; flex-direction: column; gap: 8px;">
            <div style="display: flex; justify-content: space-between;">
                <span>Original Lines:</span>
                <span id="stat-original" style="font-weight: bold; color: var(--color-text-primary);">0</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Cleaned Lines:</span>
                <span id="stat-cleaned" style="font-weight: bold; color: var(--color-primary);">0</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-top: 1px dashed var(--color-border); padding-top: 8px;">
                <span>Removed Lines:</span>
                <span id="stat-removed" style="font-weight: bold; color: var(--color-success);">0 (0%)</span>
            </div>
        </div>

    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 100%; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">Data Processing Tip:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            This tool operates entirely in your browser. It is extremely useful for sorting <strong>CSV entries</strong>, deduplicating <strong>email lists</strong>, cleaning up <strong>log files</strong>, or sorting code lines. Use the trim spaces option to ensure blank tabs do not prevent detection.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('lines-input');
    const output = document.getElementById('lines-output');
    const processBtn = document.getElementById('process-btn');
    const copyBtn = document.getElementById('copy-btn');

    const optCase = document.getElementById('opt-case-sensitive');
    const optTrim = document.getElementById('opt-trim');
    const optEmpty = document.getElementById('opt-remove-empty');

    const statOriginal = document.getElementById('stat-original');
    const statCleaned = document.getElementById('stat-cleaned');
    const statRemoved = document.getElementById('stat-removed');

    processBtn.addEventListener('click', () => {
        const text = input.value;
        if (!text) {
            output.value = '';
            statOriginal.textContent = '0';
            statCleaned.textContent = '0';
            statRemoved.textContent = '0 (0%)';
            return;
        }

        // Split lines
        const lines = text.split(/\r?\n/);
        const originalCount = lines.length;

        const uniqueLines = [];
        const seen = new Set();

        lines.forEach(line => {
            let processedLine = line;
            
            if (optTrim.checked) {
                processedLine = processedLine.trim();
            }

            // Skip empty if requested
            if (optEmpty.checked && processedLine === '') {
                return;
            }

            // Key mapping based on case option
            const compareKey = optCase.checked ? processedLine : processedLine.toLowerCase();

            if (!seen.has(compareKey)) {
                seen.add(compareKey);
                // Keep original casing in output (and trim if requested)
                uniqueLines.push(optTrim.checked ? processedLine : line);
            }
        });

        const cleanedCount = uniqueLines.length;
        const removedCount = originalCount - cleanedCount;
        const percent = originalCount > 0 ? Math.round((removedCount / originalCount) * 100) : 0;

        // Render output
        output.value = uniqueLines.join('\n');

        // Render stats
        statOriginal.textContent = originalCount.toLocaleString();
        statCleaned.textContent = cleanedCount.toLocaleString();
        statRemoved.textContent = `${removedCount.toLocaleString()} (${percent}%)`;
    });

    copyBtn.addEventListener('click', () => {
        if (!output.value) return;
        navigator.clipboard.writeText(output.value).then(() => {
            const originalText = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
            setTimeout(() => {
                copyBtn.innerHTML = originalText;
            }, 2000);
        });
    });
});
</script>
