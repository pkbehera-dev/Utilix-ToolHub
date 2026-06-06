<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h1><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-business-time') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'Pomodoro Timer') ?></h1>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'A focus timer with customizable work and break intervals.') ?></p>
</div>

<div class="tool-content" style="max-width: 600px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); text-align: center; display: flex; flex-direction: column; align-items: center; gap: 25px;">
    
    <!-- Mode Tabs -->
    <div style="display: flex; gap: 8px; background: var(--color-background); padding: 5px; border-radius: var(--radius-md); border: 1px solid var(--color-border); width: 100%; max-width: 400px; justify-content: space-around;">
        <button class="pom-tab-btn active" data-mode="work">Work</button>
        <button class="pom-tab-btn" data-mode="short-break">Short Break</button>
        <button class="pom-tab-btn" data-mode="long-break">Long Break</button>
    </div>

    <!-- Visual Timer Ring -->
    <div class="timer-visual-container" style="position: relative; width: 220px; height: 220px;">
        <svg width="220" height="220" viewBox="0 0 220 220" style="transform: rotate(-90deg);">
            <!-- Background circle -->
            <circle cx="110" cy="110" r="95" stroke="var(--color-border)" stroke-width="8" fill="transparent" />
            <!-- Foreground progress circle -->
            <circle id="progress-circle" cx="110" cy="110" r="95" stroke="var(--color-primary)" stroke-width="8" fill="transparent" 
                    stroke-dasharray="596.9" stroke-dashoffset="0" stroke-linecap="round" style="transition: stroke-dashoffset 0.5s linear, stroke var(--transition-fast);" />
        </svg>
        <div style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; line-height: 1;">
            <div id="pomodoro-display" style="font-size: 3rem; font-weight: 800; color: var(--color-text-primary); font-variant-numeric: tabular-nums; letter-spacing: -0.05em; margin-bottom: 5px;">25:00</div>
            <div id="pomodoro-status" style="font-size: 0.75rem; font-weight: 700; color: var(--color-text-secondary); text-transform: uppercase; letter-spacing: 0.1em;">Ready to Focus</div>
        </div>
    </div>

    <!-- Controls -->
    <div style="display: flex; gap: 15px; width: 100%; max-width: 320px; justify-content: center;">
        <button id="pom-start-btn" class="btn btn-primary" style="flex: 1; height: 46px; font-weight: 600;"><i class="fa-solid fa-play"></i> Start</button>
        <button id="pom-pause-btn" class="btn btn-secondary" style="flex: 1; height: 46px; font-weight: 600; display: none;"><i class="fa-solid fa-pause"></i> Pause</button>
        <button id="pom-reset-btn" class="btn btn-secondary" style="width: 50px; height: 46px; padding: 0;"><i class="fa-solid fa-rotate-right"></i></button>
    </div>

    <!-- Duration Settings -->
    <div style="border-top: 1px solid var(--color-border); width: 100%; padding-top: 20px; margin-top: 5px; text-align: left;">
        <h4 style="font-size: 0.9rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 15px; text-align: center;">Custom Intervals (Minutes)</h4>
        <div style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center;">
            <div style="display: flex; flex-direction: column; gap: 5px; width: 90px; text-align: center;">
                <label for="input-work" style="font-size: 0.75rem; color: var(--color-text-secondary); font-weight: 500;">Work</label>
                <input type="number" id="input-work" min="1" max="180" value="25" style="padding: 6px; border: 1px solid var(--color-border); border-radius: var(--radius-md); text-align: center; font-weight: 600; background: var(--color-background); color: var(--color-text-primary);">
            </div>
            <div style="display: flex; flex-direction: column; gap: 5px; width: 90px; text-align: center;">
                <label for="input-short" style="font-size: 0.75rem; color: var(--color-text-secondary); font-weight: 500;">Short Break</label>
                <input type="number" id="input-short" min="1" max="60" value="5" style="padding: 6px; border: 1px solid var(--color-border); border-radius: var(--radius-md); text-align: center; font-weight: 600; background: var(--color-background); color: var(--color-text-primary);">
            </div>
            <div style="display: flex; flex-direction: column; gap: 5px; width: 90px; text-align: center;">
                <label for="input-long" style="font-size: 0.75rem; color: var(--color-text-secondary); font-weight: 500;">Long Break</label>
                <input type="number" id="input-long" min="1" max="120" value="15" style="padding: 6px; border: 1px solid var(--color-border); border-radius: var(--radius-md); text-align: center; font-weight: 600; background: var(--color-background); color: var(--color-text-primary);">
            </div>
        </div>
    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 600px; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">The Pomodoro Technique:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            Focus intently for 25 minutes (Work), then rest for 5 minutes (Short Break). After completing 4 focus cycles, reward yourself with a longer 15-minute rest (Long Break). It dramatically improves cognitive endurance.
        </p>
    </div>
</div>

<style>
.pom-tab-btn {
    flex: 1;
    padding: 8px 12px;
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    border-radius: var(--radius-sm);
    transition: all var(--transition-fast);
}
.pom-tab-btn:hover {
    color: var(--color-text-primary);
}
.pom-tab-btn.active {
    background: var(--color-surface);
    color: var(--color-primary);
    box-shadow: var(--shadow-sm);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Mode Durations (Seconds)
    const durations = {
        work: 25 * 60,
        'short-break': 5 * 60,
        'long-break': 15 * 60
    };

    let activeMode = 'work';
    let timeLeft = durations[activeMode];
    let totalTime = durations[activeMode];
    let timerInterval = null;

    const display = document.getElementById('pomodoro-display');
    const statusText = document.getElementById('pomodoro-status');
    const progressCircle = document.getElementById('progress-circle');
    
    const startBtn = document.getElementById('pom-start-btn');
    const pauseBtn = document.getElementById('pom-pause-btn');
    const resetBtn = document.getElementById('pom-reset-btn');

    const inputWork = document.getElementById('input-work');
    const inputShort = document.getElementById('input-short');
    const inputLong = document.getElementById('input-long');
    const tabs = document.querySelectorAll('.pom-tab-btn');

    // Circular progress stroke length (2 * Math.PI * r = 2 * 3.14159 * 95 = 596.90)
    const circleCircumference = 596.90;

    // Web Audio Sound Generator
    const playAlarmSound = () => {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            
            // Beep 1
            const osc1 = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(880, audioCtx.currentTime); // A5 note
            gainNode.gain.setValueAtTime(0.3, audioCtx.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.5);
            
            osc1.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            
            osc1.start();
            osc1.stop(audioCtx.currentTime + 0.5);

            // Beep 2 (delayed)
            setTimeout(() => {
                const osc2 = audioCtx.createOscillator();
                const gain2 = audioCtx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(1200, audioCtx.currentTime);
                gain2.gain.setValueAtTime(0.3, audioCtx.currentTime);
                gain2.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.6);
                
                osc2.connect(gain2);
                gain2.connect(audioCtx.destination);
                osc2.start();
                osc2.stop(audioCtx.currentTime + 0.6);
            }, 300);
        } catch (e) {
            console.error('AudioContext error:', e);
        }
    };

    const updateUI = () => {
        // Update Time display
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        display.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

        // Update Circular Progress
        const percentLeft = timeLeft / totalTime;
        const offset = circleCircumference * (1 - percentLeft);
        progressCircle.style.strokeDashoffset = offset;

        // Change progress color based on mode
        if (activeMode === 'work') {
            progressCircle.style.stroke = 'var(--color-primary)';
            statusText.textContent = timerInterval ? 'Focusing...' : 'Ready to Focus';
        } else {
            progressCircle.style.stroke = 'var(--color-success)';
            statusText.textContent = timerInterval ? 'Resting...' : 'Taking a Break';
        }
    };

    const startTimer = () => {
        if (timerInterval) return;

        startBtn.style.display = 'none';
        pauseBtn.style.display = 'block';

        timerInterval = setInterval(() => {
            if (timeLeft > 0) {
                timeLeft--;
                updateUI();
            } else {
                clearInterval(timerInterval);
                timerInterval = null;
                playAlarmSound();
                alert(`${activeMode === 'work' ? 'Work session' : 'Break session'} finished!`);
                resetTimer();
            }
        }, 1000);
        updateUI();
    };

    const pauseTimer = () => {
        clearInterval(timerInterval);
        timerInterval = null;
        startBtn.style.display = 'block';
        pauseBtn.style.display = 'none';
        updateUI();
    };

    const resetTimer = () => {
        pauseTimer();
        timeLeft = durations[activeMode];
        totalTime = durations[activeMode];
        updateUI();
    };

    const syncCustomDurations = () => {
        durations.work = (parseInt(inputWork.value) || 25) * 60;
        durations['short-break'] = (parseInt(inputShort.value) || 5) * 60;
        durations['long-break'] = (parseInt(inputLong.value) || 15) * 60;
        
        timeLeft = durations[activeMode];
        totalTime = durations[activeMode];
        updateUI();
    };

    // Mode selection
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            activeMode = tab.getAttribute('data-mode');
            resetTimer();
        });
    });

    // Control Listeners
    startBtn.addEventListener('click', startTimer);
    pauseBtn.addEventListener('click', pauseTimer);
    resetBtn.addEventListener('click', resetTimer);

    // Custom Input Listeners
    inputWork.addEventListener('change', syncCustomDurations);
    inputShort.addEventListener('change', syncCustomDurations);
    inputLong.addEventListener('change', syncCustomDurations);

    // Initial load
    updateUI();
});
</script>
