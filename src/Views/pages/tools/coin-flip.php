<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h2><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-circle-dot') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'Coin Flip') ?></h2>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'Flip a virtual coin to make random decisions.') ?></p>
</div>

<div class="tool-content" style="max-width: 500px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); text-align: center;">
    
    <!-- Coin Visual -->
    <div style="position: relative; width: 140px; height: 140px; margin: 30px auto; perspective: 1000px;">
        <div id="coin-container" style="width: 100%; height: 100%; position: absolute; transform-style: preserve-3d; transition: transform 1.5s ease-out; transform: rotateY(0deg);">
            <!-- Heads Side -->
            <div style="position: absolute; width: 100%; height: 100%; border-radius: 50%; backface-visibility: hidden; background: linear-gradient(135deg, #f59e0b, #d97706); border: 4px solid #b45309; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 800; box-shadow: 0 8px 24px rgba(217, 119, 6, 0.3);">
                HEADS
            </div>
            <!-- Tails Side (rotated 180 deg) -->
            <div style="position: absolute; width: 100%; height: 100%; border-radius: 50%; backface-visibility: hidden; background: linear-gradient(135deg, #94a3b8, #64748b); border: 4px solid #475569; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 800; transform: rotateY(180deg); box-shadow: 0 8px 24px rgba(100, 116, 139, 0.3);">
                TAILS
            </div>
        </div>
    </div>

    <!-- Announcement Output -->
    <div id="flip-announcement" style="font-size: 1.5rem; font-weight: 700; color: var(--color-text-primary); margin: 20px 0; min-height: 36px;">
        Click Flip to Start
    </div>

    <!-- Flip Button -->
    <button id="flip-btn" style="width: 100%; padding: 14px; background: var(--color-primary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-size: 1.1rem; font-weight: 600; margin-bottom: 25px; transition: background-color var(--transition-fast);">
        Flip Coin
    </button>

    <!-- Simple Stats Log -->
    <div style="display: flex; gap: 15px; border-top: 1px solid var(--color-border); padding-top: 20px; justify-content: center; font-size: 0.95rem; color: var(--color-text-secondary);">
        <div>Heads: <strong id="stats-heads" style="color: var(--color-text-primary);">0</strong></div>
        <div style="width: 1px; background: var(--color-border); height: 18px;"></div>
        <div>Tails: <strong id="stats-tails" style="color: var(--color-text-primary);">0</strong></div>
    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 500px; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">True Coin Flip Statistics:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            A coin flip is a classic 50-50 binary chance model. This virtual coin uses <strong>cryptographic entropy</strong> from the browser to determine outcomes, preventing math cycle bias.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const coin = document.getElementById('coin-container');
    const button = document.getElementById('flip-btn');
    const announcement = document.getElementById('flip-announcement');
    const statsHeads = document.getElementById('stats-heads');
    const statsTails = document.getElementById('stats-tails');

    let headsCount = 0;
    let tailsCount = 0;
    let currentRotation = 0;

    button.addEventListener('click', () => {
        button.disabled = true;
        announcement.textContent = "Flipping...";

        // Cryptographically secure 0 or 1
        const array = new Uint8Array(1);
        window.crypto.getRandomValues(array);
        const result = array[0] % 2; // 0 for Heads, 1 for Tails

        // Spin calculation
        const minimumSpins = 4; // Spanning at least 4 full loops (1440 degrees)
        const targetSideDegrees = result === 0 ? 0 : 180;
        
        // Accumulate rotation so the coin always spins forward
        currentRotation += (minimumSpins * 360) + targetSideDegrees - (currentRotation % 360);
        coin.style.transform = `rotateY(${currentRotation}deg)`;

        setTimeout(() => {
            if (result === 0) {
                headsCount++;
                statsHeads.textContent = headsCount;
                announcement.textContent = "HEADS!";
            } else {
                tailsCount++;
                statsTails.textContent = tailsCount;
                announcement.textContent = "TAILS!";
            }
            button.disabled = false;
        }, 1500); // Must match transition duration
    });
});
</script>
