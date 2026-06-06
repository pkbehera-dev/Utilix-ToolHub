<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h2><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-hourglass-half') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'Countdown Timer') ?></h2>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'Set a countdown timer with quick duration presets and a buzzer alarm.') ?></p>
</div>

<div class="tool-content" style="max-width: 600px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); text-align: center; display: flex; flex-direction: column; align-items: center; gap: 25px;">
    
    <!-- Timer Input Selector (shown when stopped) -->
    <div id="timer-setup" style="display: flex; gap: 10px; justify-content: center; align-items: center; width: 100%; max-width: 400px; margin-bottom: 5px;">
        <div style="display: flex; flex-direction: column; width: 80px;">
            <select id="timer-hours" class="timer-select-box"></select>
            <span style="font-size: 0.75rem; color: var(--color-text-secondary); margin-top: 4px; font-weight: 500;">Hours</span>
        </div>
        <span style="font-size: 1.5rem; font-weight: 700; color: var(--color-text-secondary); padding-bottom: 16px;">:</span>
        <div style="display: flex; flex-direction: column; width: 80px;">
            <select id="timer-minutes" class="timer-select-box"></select>
            <span style="font-size: 0.75rem; color: var(--color-text-secondary); margin-top: 4px; font-weight: 500;">Minutes</span>
        </div>
        <span style="font-size: 1.5rem; font-weight: 700; color: var(--color-text-secondary); padding-bottom: 16px;">:</span>
        <div style="display: flex; flex-direction: column; width: 80px;">
            <select id="timer-seconds" class="timer-select-box"></select>
            <span style="font-size: 0.75rem; color: var(--color-text-secondary); margin-top: 4px; font-weight: 500;">Seconds</span>
        </div>
    </div>

    <!-- Active Countdown Display (shown when running/paused) -->
    <div id="timer-active-wrap" style="display: none; background: var(--color-background); border: 1px solid var(--color-border); padding: 25px; border-radius: var(--radius-lg); width: 100%; max-width: 400px;">
        <div id="timer-countdown" style="font-size: 3.5rem; font-weight: 800; color: var(--color-text-primary); font-family: var(--font-mono); font-variant-numeric: tabular-nums; letter-spacing: -0.05em; line-height: 1;">
            00:00:00
        </div>
        <!-- Progress bar -->
        <div style="width: 100%; height: 6px; background: var(--color-border); border-radius: var(--radius-full); margin-top: 20px; overflow: hidden;">
            <div id="timer-progress-bar" style="width: 100%; height: 100%; background: var(--color-primary); transition: width 1s linear;"></div>
        </div>
    </div>

    <!-- Quick Presets -->
    <div id="presets-container" style="display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; width: 100%; max-width: 400px;">
        <button class="btn btn-secondary preset-btn" data-sec="60">1 Min</button>
        <button class="btn btn-secondary preset-btn" data-sec="300">5 Min</button>
        <button class="btn btn-secondary preset-btn" data-sec="600">10 Min</button>
        <button class="btn btn-secondary preset-btn" data-sec="900">15 Min</button>
        <button class="btn btn-secondary preset-btn" data-sec="1800">30 Min</button>
    </div>

    <!-- Action Controls -->
    <div style="display: flex; gap: 15px; width: 100%; max-width: 400px; justify-content: center;">
        <button id="timer-start-btn" class="btn btn-primary" style="flex: 1.5; height: 46px; font-weight: 600;"><i class="fa-solid fa-play"></i> Start</button>
        <button id="timer-pause-btn" class="btn btn-secondary" style="flex: 1.5; height: 46px; font-weight: 600; display: none;"><i class="fa-solid fa-pause"></i> Pause</button>
        <button id="timer-reset-btn" class="btn btn-danger" style="flex: 1; height: 46px; font-weight: 600;" disabled><i class="fa-solid fa-trash"></i> Cancel</button>
    </div>
</div>

<style>
.timer-select-box {
    padding: 10px;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    background: var(--color-background);
    color: var(--color-text-primary);
    font-size: 1.25rem;
    font-weight: 700;
    text-align-last: center;
    cursor: pointer;
}
.timer-select-box:focus {
    outline: none;
    border-color: var(--color-primary);
}
.preset-btn {
    padding: 0px 14px;
    height: 32px;
    font-size: 0.8rem;
    border-radius: var(--radius-full);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const hoursSelect = document.getElementById('timer-hours');
    const minutesSelect = document.getElementById('timer-minutes');
    const secondsSelect = document.getElementById('timer-seconds');
    
    const setupWrap = document.getElementById('timer-setup');
    const activeWrap = document.getElementById('timer-active-wrap');
    const display = document.getElementById('timer-countdown');
    const progressBar = document.getElementById('timer-progress-bar');
    const presetsContainer = document.getElementById('presets-container');
    
    const startBtn = document.getElementById('timer-start-btn');
    const pauseBtn = document.getElementById('timer-pause-btn');
    const resetBtn = document.getElementById('timer-reset-btn');

    let totalSeconds = 0;
    let initialSeconds = 0;
    let timerInterval = null;

    // Populate selects
    for (let h = 0; h <= 23; h++) {
        hoursSelect.add(new Option(String(h).padStart(2, '0'), h));
    }
    for (let m = 0; m <= 59; m++) {
        minutesSelect.add(new Option(String(m).padStart(2, '0'), m));
        secondsSelect.add(new Option(String(m).padStart(2, '0'), m));
    }

    // Set defaults
    hoursSelect.value = 0;
    minutesSelect.value = 5;
    secondsSelect.value = 0;

    // Sound generation helper (alarm chime)
    const playAlarmBuzzer = () => {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const now = audioCtx.currentTime;
            
            // Loop a quick chime 4 times
            for (let i = 0; i < 4; i++) {
                const startTime = now + i * 0.4;
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(600, startTime);
                osc.frequency.setValueAtTime(800, startTime + 0.15); // ascending pitch
                
                gain.gain.setValueAtTime(0, startTime);
                gain.gain.linearRampToValueAtTime(0.35, startTime + 0.05);
                gain.gain.exponentialRampToValueAtTime(0.01, startTime + 0.35);
                
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(startTime);
                osc.stop(startTime + 0.35);
            }
        } catch (e) {
            console.error('AudioContext alarm error:', e);
        }
    };

    const formatCountdown = (secs) => {
        const h = Math.floor(secs / 3600);
        const m = Math.floor((secs % 3600) / 60);
        const s = secs % 60;
        return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
    };

    const updateUI = () => {
        display.textContent = formatCountdown(totalSeconds);
        
        // Update progress bar width percentage
        if (initialSeconds > 0) {
            const pct = (totalSeconds / initialSeconds) * 100;
            progressBar.style.width = `${pct}%`;
        } else {
            progressBar.style.width = '0%';
        }
    };

    const startTimer = () => {
        if (timerInterval) return;

        // If starting fresh (not resume)
        if (totalSeconds === 0) {
            const h = parseInt(hoursSelect.value) || 0;
            const m = parseInt(minutesSelect.value) || 0;
            const s = parseInt(secondsSelect.value) || 0;
            
            totalSeconds = (h * 3600) + (m * 60) + s;
            
            if (totalSeconds === 0) {
                alert('Please choose a duration greater than zero.');
                return;
            }
            initialSeconds = totalSeconds;
        }

        // Adjust visible blocks
        setupWrap.style.display = 'none';
        activeWrap.style.display = 'block';
        presetsContainer.style.visibility = 'hidden';
        
        startBtn.style.display = 'none';
        pauseBtn.style.display = 'block';
        resetBtn.disabled = false;

        timerInterval = setInterval(() => {
            if (totalSeconds > 0) {
                totalSeconds--;
                updateUI();
            } else {
                clearInterval(timerInterval);
                timerInterval = null;
                playAlarmBuzzer();
                alert("Time's up!");
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
        totalSeconds = 0;
        initialSeconds = 0;
        
        setupWrap.style.display = 'flex';
        activeWrap.style.display = 'none';
        presetsContainer.style.visibility = 'visible';
        
        startBtn.style.display = 'block';
        pauseBtn.style.display = 'none';
        resetBtn.disabled = true;
    };

    // Preset button triggers
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const secs = parseInt(btn.getAttribute('data-sec')) || 0;
            
            // Set select values to match preset
            hoursSelect.value = Math.floor(secs / 3600);
            minutesSelect.value = Math.floor((secs % 3600) / 60);
            secondsSelect.value = secs % 60;
            
            totalSeconds = secs;
            initialSeconds = secs;
            startTimer();
        });
    });

    startBtn.addEventListener('click', startTimer);
    pauseBtn.addEventListener('click', pauseTimer);
    resetBtn.addEventListener('click', resetTimer);
});
</script>
