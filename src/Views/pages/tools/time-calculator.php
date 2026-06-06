<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h1><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-calculator') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'Time Calculator') ?></h1>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'Add or subtract time durations and calculate intervals or differences between clock times.') ?></p>
</div>

<div class="tool-content" style="max-width: 650px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); text-align: left; display: flex; flex-direction: column; gap: 25px;">
    
    <!-- Calculator Tabs -->
    <div style="display: flex; gap: 8px; background: var(--color-background); padding: 5px; border-radius: var(--radius-md); border: 1px solid var(--color-border); justify-content: space-around;">
        <button class="calc-tab-btn active" data-tab="tab-adder">Duration Adder</button>
        <button class="calc-tab-btn" data-tab="tab-diff">Time Card / Difference</button>
    </div>

    <!-- TAB 1: Duration Adder -->
    <div id="tab-adder" class="calc-tab-content">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--color-text-primary); margin-bottom: 15px;">Add / Subtract Durations</h3>
        <p style="font-size: 0.85rem; color: var(--color-text-secondary); margin-bottom: 20px; line-height: 1.4;">Add multiple rows of time (hours, minutes, seconds) together to calculate the grand total. Ideal for timesheets, video timelines, or audio logs.</p>
        
        <!-- Table List of Rows -->
        <div id="adder-rows" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
            <!-- Initial rows injected via JS -->
        </div>

        <!-- Row Control Buttons -->
        <div style="display: flex; gap: 10px; margin-bottom: 25px;">
            <button id="add-row-btn" class="btn btn-secondary" style="font-size: 0.85rem; height: 36px; padding: 0 12px;"><i class="fa-solid fa-plus"></i> Add Row</button>
            <button id="clear-rows-btn" class="btn btn-danger" style="font-size: 0.85rem; height: 36px; padding: 0 12px; background: color-mix(in srgb, var(--color-danger) 10%, var(--color-surface)); color: var(--color-danger); border: 1px solid var(--color-border);"><i class="fa-solid fa-trash-can"></i> Clear All</button>
        </div>

        <!-- Output Result Panel -->
        <div style="background: var(--color-background); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 20px; display: flex; flex-direction: column; gap: 10px;">
            <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--color-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 5px;">Total Duration</h4>
            <div id="adder-total-formatted" style="font-size: 2.25rem; font-weight: 800; color: var(--color-primary); font-family: var(--font-mono);">0h 00m 00s</div>
            <div id="adder-total-seconds" style="font-size: 0.85rem; color: var(--color-text-secondary);">Total seconds: <strong style="color: var(--color-text-primary);">0</strong></div>
            <div id="adder-total-hours" style="font-size: 0.85rem; color: var(--color-text-secondary);">Decimal hours: <strong style="color: var(--color-text-primary);">0.00 hours</strong></div>
        </div>
    </div>

    <!-- TAB 2: Time Difference / Card -->
    <div id="tab-diff" class="calc-tab-content" style="display: none;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--color-text-primary); margin-bottom: 15px;">Time Card / Difference Calculator</h3>
        <p style="font-size: 0.85rem; color: var(--color-text-secondary); margin-bottom: 20px; line-height: 1.4;">Calculate the total working hours between two clock times (e.g. Start Time to End Time) with optional break time deductions.</p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 15px; margin-bottom: 25px;">
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label for="diff-start" style="font-size: 0.85rem; font-weight: 600; color: var(--color-text-primary);">Start Time</label>
                <input type="time" id="diff-start" value="09:00" style="padding: 10px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-weight: 600;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label for="diff-end" style="font-size: 0.85rem; font-weight: 600; color: var(--color-text-primary);">End Time</label>
                <input type="time" id="diff-end" value="17:00" style="padding: 10px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-weight: 600;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label for="diff-break" style="font-size: 0.85rem; font-weight: 600; color: var(--color-text-primary);">Break Deductions (Mins)</label>
                <input type="number" id="diff-break" min="0" max="480" value="30" style="padding: 10px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-weight: 600;">
            </div>
        </div>

        <!-- Output Result Panel -->
        <div style="background: var(--color-background); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 20px; display: flex; flex-direction: column; gap: 10px;">
            <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--color-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 5px;">Calculated Time Difference</h4>
            <div id="diff-total-formatted" style="font-size: 2.25rem; font-weight: 800; color: var(--color-primary); font-family: var(--font-mono);">7h 30m</div>
            <div id="diff-total-minutes" style="font-size: 0.85rem; color: var(--color-text-secondary);">Total minutes: <strong style="color: var(--color-text-primary);">450</strong></div>
            <div id="diff-total-hours" style="font-size: 0.85rem; color: var(--color-text-secondary);">Decimal hours: <strong style="color: var(--color-text-primary);">7.50 hours</strong></div>
        </div>
    </div>
</div>

<style>
.calc-tab-btn {
    flex: 1;
    padding: 10px;
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    border-radius: var(--radius-sm);
    transition: all var(--transition-fast);
}
.calc-tab-btn:hover {
    color: var(--color-text-primary);
}
.calc-tab-btn.active {
    background: var(--color-surface);
    color: var(--color-primary);
    box-shadow: var(--shadow-sm);
}

/* Durations List Row Style */
.duration-row {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--color-background);
    border: 1px solid var(--color-border);
    padding: 10px;
    border-radius: var(--radius-md);
}
.duration-input {
    width: 100%;
    padding: 6px;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-sm);
    text-align: center;
    font-weight: 600;
    background: var(--color-surface);
    color: var(--color-text-primary);
}
.duration-op-select {
    padding: 6px;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-sm);
    font-weight: 700;
    background: var(--color-surface);
    color: var(--color-text-primary);
    cursor: pointer;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Tab Switching Logic
    const tabs = document.querySelectorAll('.calc-tab-btn');
    const contents = document.querySelectorAll('.calc-tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const targetId = tab.getAttribute('data-tab');
            contents.forEach(c => {
                c.style.display = c.id === targetId ? 'block' : 'none';
            });
        });
    });

    // 2. Duration Adder Logic
    const adderRowsContainer = document.getElementById('adder-rows');
    const addRowBtn = document.getElementById('add-row-btn');
    const clearRowsBtn = document.getElementById('clear-rows-btn');

    const totalFormatted = document.getElementById('adder-total-formatted');
    const totalSecondsText = document.getElementById('adder-total-seconds').querySelector('strong');
    const totalHoursText = document.getElementById('adder-total-hours').querySelector('strong');

    let rowCounter = 0;

    const createDurationRow = (h = 0, m = 0, s = 0, op = '+') => {
        rowCounter++;
        const row = document.createElement('div');
        row.className = 'duration-row';
        row.id = `row-${rowCounter}`;

        row.innerHTML = `
            <select class="duration-op-select row-input">
                <option value="+" ${op === '+' ? 'selected' : ''}>+</option>
                <option value="-" ${op === '-' ? 'selected' : ''}>-</option>
            </select>
            <div style="display: flex; gap: 4px; align-items: center; flex: 1;">
                <input type="number" class="duration-input row-input hours-val" placeholder="H" min="0" value="${h || ''}" style="width: 100%;">
                <span style="font-size: 0.8rem; color: var(--color-text-secondary); font-weight: bold;">h</span>
            </div>
            <div style="display: flex; gap: 4px; align-items: center; flex: 1;">
                <input type="number" class="duration-input row-input mins-val" placeholder="M" min="0" max="59" value="${m || ''}" style="width: 100%;">
                <span style="font-size: 0.8rem; color: var(--color-text-secondary); font-weight: bold;">m</span>
            </div>
            <div style="display: flex; gap: 4px; align-items: center; flex: 1;">
                <input type="number" class="duration-input row-input secs-val" placeholder="S" min="0" max="59" value="${s || ''}" style="width: 100%;">
                <span style="font-size: 0.8rem; color: var(--color-text-secondary); font-weight: bold;">s</span>
            </div>
            <button class="remove-row-btn" style="background: transparent; border: none; cursor: pointer; color: var(--color-text-secondary); padding: 5px; font-size: 0.95rem; transition: color var(--transition-fast);"><i class="fa-solid fa-xmark"></i></button>
        `;

        // Wire change listeners
        row.querySelectorAll('.row-input').forEach(input => {
            input.addEventListener('input', calculateAdderTotal);
        });

        // Wire remove click
        row.querySelector('.remove-row-btn').addEventListener('click', () => {
            row.remove();
            calculateAdderTotal();
        });

        adderRowsContainer.appendChild(row);
    };

    const calculateAdderTotal = () => {
        let grandTotalSecs = 0;
        const rows = adderRowsContainer.querySelectorAll('.duration-row');

        rows.forEach(row => {
            const op = row.querySelector('.duration-op-select').value;
            const h = parseInt(row.querySelector('.hours-val').value) || 0;
            const m = parseInt(row.querySelector('.mins-val').value) || 0;
            const s = parseInt(row.querySelector('.secs-val').value) || 0;

            const rowSecs = (h * 3600) + (m * 60) + s;
            if (op === '+') {
                grandTotalSecs += rowSecs;
            } else {
                grandTotalSecs -= rowSecs;
            }
        });

        const absSecs = Math.abs(grandTotalSecs);
        const sign = grandTotalSecs < 0 ? '-' : '';

        const calcH = Math.floor(absSecs / 3600);
        const calcM = Math.floor((absSecs % 3600) / 60);
        const calcS = absSecs % 60;

        totalFormatted.textContent = `${sign}${calcH}h ${String(calcM).padStart(2, '0')}m ${String(calcS).padStart(2, '0')}s`;
        totalSecondsText.textContent = grandTotalSecs.toLocaleString();
        
        const decimalHours = grandTotalSecs / 3600;
        totalHoursText.textContent = `${decimalHours.toFixed(2)} hours`;
    };

    addRowBtn.addEventListener('click', () => {
        createDurationRow();
    });

    clearRowsBtn.addEventListener('click', () => {
        adderRowsContainer.innerHTML = '';
        createDurationRow();
        calculateAdderTotal();
    });

    // 3. Time Difference Logic
    const startInput = document.getElementById('diff-start');
    const endInput = document.getElementById('diff-end');
    const breakInput = document.getElementById('diff-break');
    
    const diffFormatted = document.getElementById('diff-total-formatted');
    const diffMinutesText = document.getElementById('diff-total-minutes').querySelector('strong');
    const diffHoursText = document.getElementById('diff-total-hours').querySelector('strong');

    const calculateDiffTotal = () => {
        const startVal = startInput.value;
        const endVal = endInput.value;
        const breakMins = parseInt(breakInput.value) || 0;

        if (!startVal || !endVal) return;

        const [startH, startM] = startVal.split(':').map(Number);
        const [endH, endM] = endVal.split(':').map(Number);

        let startMinutesTotal = (startH * 60) + startM;
        let endMinutesTotal = (endH * 60) + endM;

        // If End Time is earlier than Start Time, assume it crosses midnight (adds 24 hours)
        if (endMinutesTotal < startMinutesTotal) {
            endMinutesTotal += 24 * 60;
        }

        let diffMinutes = endMinutesTotal - startMinutesTotal - breakMins;
        if (diffMinutes < 0) diffMinutes = 0;

        const resultH = Math.floor(diffMinutes / 60);
        const resultM = diffMinutes % 60;

        diffFormatted.textContent = `${resultH}h ${String(resultM).padStart(2, '0')}m`;
        diffMinutesText.textContent = diffMinutes.toLocaleString();
        
        const decimalH = diffMinutes / 60;
        diffHoursText.textContent = `${decimalH.toFixed(2)} hours`;
    };

    [startInput, endInput, breakInput].forEach(elem => {
        elem.addEventListener('input', calculateDiffTotal);
    });

    // Inits
    createDurationRow(1, 30, 0, '+');
    createDurationRow(0, 45, 0, '+');
    calculateAdderTotal();
    calculateDiffTotal();
});
</script>
