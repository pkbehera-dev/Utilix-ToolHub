<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h1><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-dice') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'Random Number Generator') ?></h1>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'Generate a random number within a chosen range with customizable animations.') ?></p>
</div>

<div class="tool-content" style="max-width: 500px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); text-align: center;">
    
    <!-- Large Number Display -->
    <div class="rng-display-wrapper" style="position: relative; overflow: hidden; margin-bottom: 25px;">
        <div id="rng-display" style="font-size: 3.5rem; font-weight: 800; padding: 30px 20px; background: var(--color-background); border: 1px solid var(--color-border); border-radius: var(--radius-md); color: var(--color-primary); min-height: 120px; display: flex; align-items: center; justify-content: center; transition: all var(--transition-fast);">
            0
        </div>
    </div>

    <!-- Inputs -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; text-align: left;">
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-text-primary);">Minimum Value</label>
            <input type="number" id="rng-min" value="1" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-size: 1rem; font-weight: bold;">
        </div>
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-text-primary);">Maximum Value</label>
            <input type="number" id="rng-max" value="100" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-size: 1rem; font-weight: bold;">
        </div>
    </div>

    <!-- Generate Button -->
    <button id="rng-btn" style="width: 100%; padding: 14px; background: var(--color-primary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-size: 1.1rem; font-weight: 600; transition: background-color var(--transition-fast); display: flex; align-items: center; justify-content: center; gap: 8px;">
        <i class="fa-solid fa-sync" id="rng-icon"></i> Generate Number
    </button>
</div>

<!-- Security Info Banner -->
<div class="security-info-banner" style="max-width: 500px; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-success) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-success) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-shield-halved" style="color: var(--color-success); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">True CSPRNG Cryptography</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            This tool uses `window.crypto.getRandomValues()` for entropy, providing cryptographically secure random distribution unlike standard insecure pseudo-random mathematical algorithms.
        </p>
    </div>
</div>

<style>
@keyframes rng-pulse {
    0% { transform: scale(1); filter: brightness(1); }
    50% { transform: scale(1.05); filter: brightness(1.2); }
    100% { transform: scale(1); filter: brightness(1); }
}

@keyframes spin-icon {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.rng-animating {
    animation: rng-pulse 150ms infinite alternate;
}

.spinning {
    animation: spin-icon 0.8s infinite linear;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const minInput = document.getElementById('rng-min');
    const maxInput = document.getElementById('rng-max');
    const display = document.getElementById('rng-display');
    const button = document.getElementById('rng-btn');
    const icon = document.getElementById('rng-icon');

    // Secure generation helper
    const getSecureRandomInt = (min, max) => {
        const range = max - min + 1;
        if (range <= 0) return min;

        // Choose appropriate typed array
        let randVal;
        const array = new Uint32Array(1);
        window.crypto.getRandomValues(array);
        
        // Map Uint32 max down cleanly
        const maxUint32 = 4294967295;
        const limit = maxUint32 - (maxUint32 % range);
        
        // Prevent modulo bias
        while (array[0] >= limit) {
            window.crypto.getRandomValues(array);
        }
        
        return min + (array[0] % range);
    };

    button.addEventListener('click', () => {
        const min = parseInt(minInput.value);
        const max = parseInt(maxInput.value);

        if (isNaN(min) || isNaN(max)) {
            alert('Please enter valid numeric minimum and maximum ranges.');
            return;
        }

        if (min > max) {
            alert('Minimum value cannot be greater than the maximum value.');
            return;
        }

        button.disabled = true;
        display.classList.add('rng-animating');
        icon.classList.add('spinning');

        let iterations = 0;
        const maxIterations = 15;
        const intervalTime = 60; // ms

        // Rolling animation
        const interval = setInterval(() => {
            // Display temporary random numbers within range
            const tempVal = Math.floor(Math.random() * (max - min + 1)) + min;
            display.textContent = tempVal;
            
            iterations++;
            if (iterations >= maxIterations) {
                clearInterval(interval);
                
                // Fetch actual cryptographically secure random number
                const finalSecureValue = getSecureRandomInt(min, max);
                display.textContent = finalSecureValue;
                
                // Clear animations and reset button
                display.classList.remove('rng-animating');
                icon.classList.remove('spinning');
                button.disabled = false;
                
                // Add quick single pop effect
                display.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    display.style.transform = 'scale(1)';
                }, 150);
            }
        }, intervalTime);
    });
});
</script>
