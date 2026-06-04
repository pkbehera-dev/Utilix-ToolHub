<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h2><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-dice') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'Dice Roller') ?></h2>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'Roll one or multiple virtual dice for games or decisions.') ?></p>
</div>

<div class="tool-content" style="max-width: 600px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); text-align: center;">
    
    <!-- Controls -->
    <div style="display: flex; justify-content: center; align-items: center; gap: 15px; margin-bottom: 30px;">
        <label for="dice-count" style="font-weight: 500; color: var(--color-text-primary);">Number of Dice:</label>
        <select id="dice-count" style="padding: 8px 16px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); cursor: pointer; font-weight: 600;">
            <option value="1" selected>1 Die</option>
            <option value="2">2 Dice</option>
            <option value="3">3 Dice</option>
            <option value="4">4 Dice</option>
            <option value="5">5 Dice</option>
        </select>
    </div>

    <!-- Dice Tray / Rolling Area -->
    <div id="dice-tray" style="display: flex; gap: 15px; justify-content: center; align-items: center; min-height: 140px; margin-bottom: 30px; flex-wrap: wrap; padding: 15px; background: var(--color-background); border-radius: var(--radius-md); border: 1px dashed var(--color-border); perspective: 600px;">
        <!-- Dice will be injected here dynamically -->
    </div>

    <!-- Sum Output -->
    <div id="roll-total" style="font-size: 1.35rem; font-weight: 700; color: var(--color-text-primary); margin-bottom: 25px; min-height: 28px;">
        Click Roll to Start
    </div>

    <!-- Roll Button -->
    <button id="roll-btn" style="width: 100%; padding: 14px; background: var(--color-primary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-size: 1.1rem; font-weight: 600; transition: background-color var(--transition-fast);">
        <i class="fa-solid fa-dice"></i> Roll Dice
    </button>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 600px; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">True Dice Randomness:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            A standard die has a 1 in 6 probability for each face. This roller utilizes <strong>Web Cryptography APIs</strong> for entropy to prevent sequential seed patterns, making it perfect for tabletop board games.
        </p>
    </div>
</div>

<style>
.die-container {
    width: 60px;
    height: 60px;
    perspective: 400px;
    margin: 0;
}

.cube {
    width: 100%;
    height: 100%;
    position: relative;
    transform-style: preserve-3d;
    transition: transform 1.2s cubic-bezier(0.15, 0.85, 0.35, 1.15);
}

.cube-face {
    position: absolute;
    width: 60px;
    height: 60px;
    background: var(--color-surface);
    border: 2px solid var(--color-text-primary);
    border-radius: 12px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(3, 1fr);
    padding: 6px;
    box-sizing: border-box;
    box-shadow: inset 0 0 8px rgba(0,0,0,0.1), 0 4px 10px rgba(0,0,0,0.15);
}

.pip {
    background: var(--color-primary);
    border-radius: 50%;
    width: 8px;
    height: 8px;
    margin: auto;
    box-shadow: 0 0 4px var(--color-primary);
}

/* Positioning pips within the 3x3 grid */
.pip-top-left { grid-area: 1 / 1; }
.pip-top-right { grid-area: 1 / 3; }
.pip-mid-left { grid-area: 2 / 1; }
.pip-center { grid-area: 2 / 2; }
.pip-mid-right { grid-area: 2 / 3; }
.pip-bottom-left { grid-area: 3 / 1; }
.pip-bottom-right { grid-area: 3 / 3; }

/* Face transforms for a 60px cube (translateZ is 30px) */
.face-1 { transform: rotateY(0deg) translateZ(30px); }
.face-6 { transform: rotateY(180deg) translateZ(30px); }
.face-2 { transform: rotateY(90deg) translateZ(30px); }
.face-5 { transform: rotateY(-90deg) translateZ(30px); }
.face-3 { transform: rotateX(90deg) translateZ(30px); }
.face-4 { transform: rotateX(-90deg) translateZ(30px); }

/* Container shaking animation */
@keyframes die-bounce {
    0% { transform: translateY(0) scale(1); }
    100% { transform: translateY(-20px) scale(1.05) rotate(5deg); }
}

.die-shaking {
    animation: die-bounce 0.15s infinite alternate ease-in-out;
}

.die-container:nth-child(2n).die-shaking {
    animation-delay: 0.05s;
    animation-duration: 0.12s;
}

.die-container:nth-child(3n).die-shaking {
    animation-delay: 0.08s;
    animation-duration: 0.18s;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const diceCountSelect = document.getElementById('dice-count');
    const diceTray = document.getElementById('dice-tray');
    const totalDisplay = document.getElementById('roll-total');
    const rollButton = document.getElementById('roll-btn');

    // Secure generation helper
    const getSecureRandomDieFace = () => {
        const array = new Uint8Array(1);
        window.crypto.getRandomValues(array);
        return (array[0] % 6) + 1;
    };

    const createDieCube = () => {
        const container = document.createElement('div');
        container.className = 'die-container';
        
        const cube = document.createElement('div');
        cube.className = 'cube';
        
        const faces = [
            { num: 1, pips: ['center'] },
            { num: 2, pips: ['top-left', 'bottom-right'] },
            { num: 3, pips: ['top-left', 'center', 'bottom-right'] },
            { num: 4, pips: ['top-left', 'top-right', 'bottom-left', 'bottom-right'] },
            { num: 5, pips: ['top-left', 'top-right', 'center', 'bottom-left', 'bottom-right'] },
            { num: 6, pips: ['top-left', 'top-right', 'mid-left', 'mid-right', 'bottom-left', 'bottom-right'] }
        ];
        
        faces.forEach(face => {
            const faceDiv = document.createElement('div');
            faceDiv.className = `cube-face face-${face.num}`;
            face.pips.forEach(pipClass => {
                const pip = document.createElement('div');
                pip.className = `pip pip-${pipClass}`;
                faceDiv.appendChild(pip);
            });
            cube.appendChild(faceDiv);
        });
        
        container.appendChild(cube);
        return { container, cube };
    };

    // Store references to cube objects
    let diceCubes = [];

    const renderDicePlaceholders = () => {
        const count = parseInt(diceCountSelect.value);
        diceTray.innerHTML = '';
        totalDisplay.textContent = 'Click Roll to Start';
        diceCubes = [];
        
        for (let i = 0; i < count; i++) {
            const { container, cube } = createDieCube();
            diceTray.appendChild(container);
            diceCubes.push({ container, cube });
        }
    };

    // Base target rotations to display a specific face on top
    const faceRotations = {
        1: { x: 0, y: 0 },
        2: { x: 0, y: -90 },
        3: { x: -90, y: 0 },
        4: { x: 90, y: 0 },
        5: { x: 0, y: 90 },
        6: { x: 0, y: 180 }
    };

    const rollDice = () => {
        rollButton.disabled = true;
        totalDisplay.textContent = 'Rolling...';
        
        let totalSum = 0;
        const duration = 1200; // Match transition duration (1.2s)

        diceCubes.forEach((die) => {
            // Start shake animation on container
            die.container.classList.add('die-shaking');

            // Generate secure random face
            const result = getSecureRandomDieFace();
            totalSum += result;

            // Generate random spins
            const dirX = Math.random() < 0.5 ? 1 : -1;
            const dirY = Math.random() < 0.5 ? 1 : -1;
            const spinsX = dirX * (Math.floor(Math.random() * 3) + 3) * 360; // 3 to 5 full spins
            const spinsY = dirY * (Math.floor(Math.random() * 3) + 3) * 360;

            const targetX = faceRotations[result].x + spinsX;
            const targetY = faceRotations[result].y + spinsY;

            // Apply spin transformation
            die.cube.style.transform = `rotateX(${targetX}deg) rotateY(${targetY}deg)`;
        });

        // Stop shaking and update total sum display
        setTimeout(() => {
            diceCubes.forEach(die => {
                die.container.classList.remove('die-shaking');
            });
            totalDisplay.textContent = `Total Sum: ${totalSum}`;
            rollButton.disabled = false;
        }, duration);
    };

    diceCountSelect.addEventListener('change', renderDicePlaceholders);
    rollButton.addEventListener('click', rollDice);

    // Initial render
    renderDicePlaceholders();
});
</script>
