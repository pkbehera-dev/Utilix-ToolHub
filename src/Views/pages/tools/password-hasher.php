<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h1><i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i> <?= htmlspecialchars($tool['name']) ?></h1>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description']) ?></p>
</div>

<div class="tool-content" style="background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); box-shadow: 0 4px 20px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto;">
    <form id="form-hash" onsubmit="event.preventDefault();">
        <!-- Input Password -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.95rem;">Enter Plain Text Password</label>
            <input type="text" id="hash-password" placeholder="Type password here..." required style="width: 100%; padding: 12px 15px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-size: 1rem; outline: none;">
        </div>

        <!-- Select Algorithm -->
        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.95rem;">Choose Hashing Algorithm</label>
            <select id="hash-algo" name="algo" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-size: 1rem; outline: none; cursor: pointer;">
                <option value="bcrypt">Bcrypt</option>
                <option value="argon2id" <?= !defined('PASSWORD_ARGON2ID') ? 'disabled' : '' ?>>Argon2 (Argon2id)</option>
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" id="btn-generate-hash" class="btn btn-primary" style="width: 100%; padding: 12px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: var(--color-primary); color: #fff; border: none; border-radius: var(--radius-md); cursor: pointer; transition: all 0.2s; font-size: 1rem;">
            <i class="fa-solid fa-arrows-rotate" id="hash-spinner" style="display: none; animation: spin 1s linear infinite;"></i>
            <span id="btn-hash-text">Generate Hash</span>
        </button>
    </form>

    <!-- Hash Result Output -->
    <div id="hash-result-box" style="margin-top: 30px; display: none;">
        <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.95rem; color: var(--color-text-primary);">Generated Hash</label>
        <div style="display: flex; gap: 10px;">
            <input type="text" id="hash-output" readonly style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-primary); font-family: monospace; font-size: 0.95rem; font-weight: 600;" onclick="this.select();">
            <button type="button" id="btn-copy-hash" style="padding: 12px 18px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-surface); color: var(--color-text-primary); cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
                <i class="fa-solid fa-copy"></i> Copy
            </button>
        </div>
    </div>
</div>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const formHash = document.getElementById('form-hash');
    const btnGenerate = document.getElementById('btn-generate-hash');
    const btnHashText = document.getElementById('btn-hash-text');
    const hashSpinner = document.getElementById('hash-spinner');
    const hashResultBox = document.getElementById('hash-result-box');
    const hashOutput = document.getElementById('hash-output');

    formHash.addEventListener('submit', (e) => {
        e.preventDefault();
        
        btnGenerate.disabled = true;
        hashSpinner.style.display = 'inline-block';
        btnHashText.textContent = 'Hashing...';
        hashResultBox.style.display = 'none';

        const formData = new FormData();
        formData.append('password', document.getElementById('hash-password').value);
        formData.append('algo', document.getElementById('hash-algo').value);

        fetch('<?= \App\Config\App::url('/api/password-hasher/hash') ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            btnGenerate.disabled = false;
            hashSpinner.style.display = 'none';
            btnHashText.textContent = 'Generate Hash';

            if (data.success) {
                hashOutput.value = data.hash;
                hashResultBox.style.display = 'block';
            } else {
                alert(data.error || 'Failed to generate hash.');
            }
        })
        .catch(err => {
            btnGenerate.disabled = false;
            hashSpinner.style.display = 'none';
            btnHashText.textContent = 'Generate Hash';
            alert('An error occurred. Please try again.');
        });
    });

    const btnCopyHash = document.getElementById('btn-copy-hash');
    btnCopyHash.addEventListener('click', () => {
        navigator.clipboard.writeText(hashOutput.value).then(() => {
            const originalHTML = btnCopyHash.innerHTML;
            btnCopyHash.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
            setTimeout(() => {
                btnCopyHash.innerHTML = originalHTML;
            }, 1500);
        });
    });
});
</script>
