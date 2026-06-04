<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h2><i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i> <?= htmlspecialchars($tool['name']) ?></h2>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description']) ?></p>
</div>

<div class="tool-content" style="display: flex; gap: 20px; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 300px; background: var(--color-surface); padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--color-border);">
        <h3 style="margin-bottom: 15px; color: var(--color-text-primary);">Input</h3>
        <textarea id="encoder-input" placeholder="Enter text to encode/decode..." style="width: 100%; height: 250px; padding: 15px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 1rem; background: var(--color-background); color: var(--color-text-primary); resize: vertical; font-family: monospace;"></textarea>
        
        <div style="display: flex; gap: 10px; margin-top: 15px;">
            <button id="encode-btn" style="flex: 1; padding: 12px; background: var(--color-primary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-weight: 600;"><i class="fa-solid fa-arrow-right-to-bracket"></i> Encode Base64</button>
            <button id="decode-btn" style="flex: 1; padding: 12px; background: var(--color-text-secondary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-weight: 600;"><i class="fa-solid fa-arrow-right-from-bracket"></i> Decode Base64</button>
        </div>
    </div>
    
    <div style="flex: 1; min-width: 300px; background: var(--color-surface); padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="color: var(--color-text-primary);">Result</h3>
            <button id="copy-btn" style="padding: 6px 12px; background: var(--color-background); border: 1px solid var(--color-border); color: var(--color-text-primary); border-radius: var(--radius-md); cursor: pointer; font-weight: 500;"><i class="fa-solid fa-copy"></i> Copy</button>
        </div>
        <textarea id="encoder-output" readonly placeholder="Result will appear here..." style="width: 100%; height: 250px; padding: 15px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 1rem; background: var(--color-background); color: var(--color-text-primary); resize: vertical; font-family: monospace;"></textarea>
    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 100%; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">When to Use Base64 Encoding:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            Base64 is used to encode binary data (like images or files) into a safe ASCII string format for transmission over text-based protocols (like HTML, email, or JSON APIs) without data corruption. It is <strong>not</strong> a form of encryption or security.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('encoder-input');
    const output = document.getElementById('encoder-output');
    const encodeBtn = document.getElementById('encode-btn');
    const decodeBtn = document.getElementById('decode-btn');
    const copyBtn = document.getElementById('copy-btn');

    // UTF-8 safe encoding
    encodeBtn.addEventListener('click', () => {
        try {
            const text = input.value;
            // Handle utf-8 encoding properly
            const bytes = new TextEncoder().encode(text);
            const binString = Array.from(bytes, (byte) =>
                String.fromCodePoint(byte),
            ).join("");
            output.value = btoa(binString);
        } catch (e) {
            output.value = "Error encoding: " + e.message;
        }
    });

    decodeBtn.addEventListener('click', () => {
        try {
            const base64 = input.value.trim();
            const binString = atob(base64);
            const bytes = Uint8Array.from(binString, (m) => m.codePointAt(0));
            output.value = new TextDecoder().decode(bytes);
        } catch (e) {
            output.value = "Invalid Base64 string.";
        }
    });

    copyBtn.addEventListener('click', () => {
        if (!output.value) return;
        navigator.clipboard.writeText(output.value).then(() => {
            const original = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
            setTimeout(() => copyBtn.innerHTML = original, 2000);
        });
    });
});
</script>
