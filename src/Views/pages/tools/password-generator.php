<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h2><i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i> <?= htmlspecialchars($tool['name']) ?></h2>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description']) ?></p>
</div>

<div class="tool-content" style="max-width: 500px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); text-align: center;">
    <div style="font-size: 2rem; padding: 20px; background: var(--color-background); border: 1px solid var(--color-border); border-radius: var(--radius-md); margin-bottom: 20px; word-break: break-all; font-family: monospace; color: var(--color-primary); font-weight: bold;" id="password-output">
        Click Generate
    </div>
    
    <div style="margin-bottom: 20px; text-align: left;">
        <label style="display: block; margin-bottom: 10px; color: var(--color-text-primary); font-weight: 500;">Length: <span id="length-val" style="font-weight: bold; color: var(--color-primary);">16</span></label>
        <input type="range" id="pw-length" min="8" max="64" value="16" style="width: 100%; accent-color: var(--color-primary);">
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 25px; text-align: left; color: var(--color-text-secondary); font-size: 0.95rem;">
        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;"><input type="checkbox" id="inc-uppercase" style="accent-color: var(--color-primary);" checked> Uppercase (A-Z)</label>
        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;"><input type="checkbox" id="inc-lowercase" style="accent-color: var(--color-primary);" checked> Lowercase (a-z)</label>
        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;"><input type="checkbox" id="inc-numbers" style="accent-color: var(--color-primary);" checked> Numbers (0-9)</label>
        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;"><input type="checkbox" id="inc-symbols" style="accent-color: var(--color-primary);" checked> Symbols (!@#$)</label>
    </div>

    <div style="display: flex; gap: 12px;">
        <button id="generate-btn" style="flex: 1; padding: 12px; background: var(--color-primary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-size: 1rem; font-weight: 600;">Generate</button>
        <button id="copy-btn" style="padding: 12px 20px; background: var(--color-background); color: var(--color-text-primary); border: 1px solid var(--color-border); border-radius: var(--radius-md); cursor: pointer; font-weight: 500; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-copy"></i> Copy</button>
    </div>
</div>

<!-- Security Info Banner -->
<div class="security-info-banner" style="max-width: 500px; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-success) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-success) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-shield-halved" style="color: var(--color-success); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">Cryptographically Secure & Private</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            Passwords are generated completely client-side in your browser using the industry-standard <strong>CSPRNG (Web Crypto API)</strong>. Your passwords are never sent over the internet or stored on our servers.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const pwOutput = document.getElementById('password-output');
    const lengthSlider = document.getElementById('pw-length');
    const lengthVal = document.getElementById('length-val');
    const generateBtn = document.getElementById('generate-btn');
    const copyBtn = document.getElementById('copy-btn');
    
    const chkUpper = document.getElementById('inc-uppercase');
    const chkLower = document.getElementById('inc-lowercase');
    const chkNums = document.getElementById('inc-numbers');
    const chkSyms = document.getElementById('inc-symbols');

    const chars = {
        upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        lower: 'abcdefghijklmnopqrstuvwxyz',
        nums: '0123456789',
        syms: '!@#$%^&*()_+~`|}{[]:;?><,./-='
    };

    lengthSlider.addEventListener('input', (e) => {
        lengthVal.textContent = e.target.value;
    });

    const generatePassword = () => {
        let charPool = '';
        if (chkUpper.checked) charPool += chars.upper;
        if (chkLower.checked) charPool += chars.lower;
        if (chkNums.checked) charPool += chars.nums;
        if (chkSyms.checked) charPool += chars.syms;

        if (charPool === '') {
            pwOutput.textContent = 'Select at least one option!';
            return;
        }

        const length = parseInt(lengthSlider.value);
        let password = '';
        const array = new Uint32Array(length);
        window.crypto.getRandomValues(array);

        for (let i = 0; i < length; i++) {
            password += charPool[array[i] % charPool.length];
        }

        pwOutput.textContent = password;
    };

    generateBtn.addEventListener('click', generatePassword);
    
    // Generate one on load
    generatePassword();

    copyBtn.addEventListener('click', () => {
        if (pwOutput.textContent === 'Select at least one option!') return;
        navigator.clipboard.writeText(pwOutput.textContent).then(() => {
            const original = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fa-solid fa-check"></i>';
            setTimeout(() => copyBtn.innerHTML = original, 2000);
        });
    });
});
</script>
