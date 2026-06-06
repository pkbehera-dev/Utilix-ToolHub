<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h2><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-stopwatch') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'Stopwatch') ?></h2>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'Measure elapsed time with precision millisecond tracking and lap logging.') ?></p>
</div>

<div class="tool-content" style="max-width: 600px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); text-align: center; display: flex; flex-direction: column; align-items: center; gap: 25px;">
    
    <!-- Time Display -->
    <div style="background: var(--color-background); border: 1px solid var(--color-border); padding: 25px 15px; border-radius: var(--radius-lg); width: 100%; max-width: 420px; box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.02);">
        <div id="stopwatch-display" style="font-size: 4rem; font-weight: 800; color: var(--color-text-primary); font-family: var(--font-mono); font-variant-numeric: tabular-nums; letter-spacing: -0.05em; line-height: 1;">
            00:00.00
        </div>
    </div>

    <!-- Controls -->
    <div style="display: flex; gap: 15px; width: 100%; max-width: 420px;">
        <button id="sw-start-btn" class="btn btn-primary" style="flex: 1.2; height: 46px; font-weight: 600;">
            <i class="fa-solid fa-play"></i> Start
        </button>
        <button id="sw-pause-btn" class="btn btn-secondary" style="flex: 1.2; height: 46px; font-weight: 600; display: none;">
            <i class="fa-solid fa-pause"></i> Pause
        </button>
        <button id="sw-lap-btn" class="btn btn-secondary" style="flex: 1; height: 46px; font-weight: 600;" disabled>
            <i class="fa-solid fa-stopwatch"></i> Lap
        </button>
        <button id="sw-reset-btn" class="btn btn-danger" style="flex: 1; height: 46px; font-weight: 600;">
            <i class="fa-solid fa-arrow-rotate-left"></i> Reset
        </button>
    </div>

    <!-- Laps Table -->
    <div id="laps-container" style="width: 100%; max-width: 420px; display: none; border-top: 1px solid var(--color-border); padding-top: 20px;">
        <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--color-text-primary); margin-bottom: 12px; text-align: left;">Recorded Laps</h4>
        <div style="max-height: 200px; overflow-y: auto; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background);">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--color-border); background: var(--color-surface); color: var(--color-text-secondary); font-weight: 600;">
                        <th style="padding: 10px 15px;">Lap</th>
                        <th style="padding: 10px 15px;">Split Time</th>
                        <th style="padding: 10px 15px;">Total Time</th>
                    </tr>
                </thead>
                <tbody id="laps-tbody">
                    <!-- Dynamic laps -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 600px; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">Lap and Split Times:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            A **Lap** measures individual segments of an event (time elapsed since the last lap was clicked). **Split** time records the total elapsed time from the start line up to that exact moment.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let startTime = 0;
    let elapsedTime = 0;
    let timerInterval = null;
    let laps = [];

    const display = document.getElementById('stopwatch-display');
    const startBtn = document.getElementById('sw-start-btn');
    const pauseBtn = document.getElementById('sw-pause-btn');
    const lapBtn = document.getElementById('sw-lap-btn');
    const resetBtn = document.getElementById('sw-reset-btn');
    const lapsContainer = document.getElementById('laps-container');
    const lapsTbody = document.getElementById('laps-tbody');

    const formatTime = (timeMs) => {
        const totalSeconds = timeMs / 1000;
        const minutes = Math.floor(totalSeconds / 60);
        const seconds = Math.floor(totalSeconds % 60);
        const hundredths = Math.floor((timeMs % 1000) / 10);

        return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}.${String(hundredths).padStart(2, '0')}`;
    };

    const updateDisplay = () => {
        display.textContent = formatTime(elapsedTime);
    };

    const startStopwatch = () => {
        startTime = Date.now() - elapsedTime;
        timerInterval = setInterval(() => {
            elapsedTime = Date.now() - startTime;
            updateDisplay();
        }, 10);

        startBtn.style.display = 'none';
        pauseBtn.style.display = 'block';
        lapBtn.disabled = false;
    };

    const pauseStopwatch = () => {
        clearInterval(timerInterval);
        timerInterval = null;
        
        startBtn.style.display = 'block';
        pauseBtn.style.display = 'none';
        lapBtn.disabled = true;
    };

    const resetStopwatch = () => {
        pauseStopwatch();
        elapsedTime = 0;
        laps = [];
        updateDisplay();
        
        lapsContainer.style.display = 'none';
        lapsTbody.innerHTML = '';
        lapBtn.disabled = true;
    };

    const recordLap = () => {
        if (!timerInterval) return;

        const totalLaps = laps.length;
        const currentTotal = elapsedTime;
        const lastTotal = totalLaps > 0 ? laps[totalLaps - 1].totalTime : 0;
        const lapDifference = currentTotal - lastTotal;

        laps.push({
            lapNum: totalLaps + 1,
            lapTime: lapDifference,
            totalTime: currentTotal
        });

        // Render Lap Row
        const tr = document.createElement('tr');
        tr.style.borderBottom = '1px solid var(--color-border)';
        tr.innerHTML = `
            <td style="padding: 8px 15px; font-weight: bold;">#${totalLaps + 1}</td>
            <td style="padding: 8px 15px; font-family: var(--font-mono);">${formatTime(lapDifference)}</td>
            <td style="padding: 8px 15px; font-family: var(--font-mono); color: var(--color-text-secondary);">${formatTime(currentTotal)}</td>
        `;

        lapsTbody.insertBefore(tr, lapsTbody.firstChild);
        lapsContainer.style.display = 'block';
    };

    startBtn.addEventListener('click', startStopwatch);
    pauseBtn.addEventListener('click', pauseStopwatch);
    resetBtn.addEventListener('click', resetStopwatch);
    lapBtn.addEventListener('click', recordLap);

    updateDisplay();
});
</script>
