<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h2><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-cake-candles') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'Age Calculator') ?></h2>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'Calculate your exact age in years, months, and days.') ?></p>
</div>

<div class="tool-content" style="max-width: 600px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border);">
    
    <!-- Inputs Grid -->
    <div style="display: grid; grid-template-columns: 1fr; gap: 20px; margin-bottom: 25px; text-align: left;">
        <div>
            <label for="dob-input" style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text-primary);">Date of Birth</label>
            <input type="date" id="dob-input" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-size: 1rem;">
        </div>
        <div>
            <label for="target-date-input" style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text-primary);">Age at the Date of</label>
            <input type="date" id="target-date-input" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-size: 1rem;">
        </div>
    </div>

    <!-- Generate Button -->
    <button id="calculate-btn" style="width: 100%; padding: 14px; background: var(--color-primary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-size: 1.1rem; font-weight: 600; margin-bottom: 25px;">
        Calculate Age
    </button>

    <!-- Results Section -->
    <div id="age-result" style="display: none; text-align: left; animation: fadeIn 0.4s ease-out;">
        <div style="background: var(--color-background); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 20px; text-align: center; margin-bottom: 20px;">
            <h3 style="font-size: 0.95rem; color: var(--color-text-secondary); margin-bottom: 8px; font-weight: 500;">Result Age</h3>
            <span id="primary-age" style="font-size: 2.25rem; font-weight: 800; color: var(--color-primary);">0 Years</span>
        </div>

        <h4 style="font-size: 1rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 12px; border-bottom: 1px solid var(--color-border); padding-bottom: 6px;">Detailed Breakdown</h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; font-size: 0.95rem;">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--color-border); padding-bottom: 6px; color: var(--color-text-secondary);">
                <span>Total Months:</span>
                <span id="total-months" style="font-weight: 600; color: var(--color-text-primary);">0</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--color-border); padding-bottom: 6px; color: var(--color-text-secondary);">
                <span>Total Weeks:</span>
                <span id="total-weeks" style="font-weight: 600; color: var(--color-text-primary);">0</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--color-border); padding-bottom: 6px; color: var(--color-text-secondary);">
                <span>Total Days:</span>
                <span id="total-days" style="font-weight: 600; color: var(--color-text-primary);">0</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--color-border); padding-bottom: 6px; color: var(--color-text-secondary);">
                <span>Total Hours:</span>
                <span id="total-hours" style="font-weight: 600; color: var(--color-text-primary);">0</span>
            </div>
        </div>

        <!-- Next Birthday -->
        <div id="birthday-countdown" style="background: color-mix(in srgb, var(--color-success) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-success) 20%, var(--color-border)); padding: 15px; border-radius: var(--radius-md); display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-gift" style="color: var(--color-success); font-size: 1.2rem;"></i>
            <span style="font-size: 0.9rem; color: var(--color-text-secondary);">Next birthday in: <strong id="countdown-val" style="color: var(--color-text-primary);">0 Months and 0 Days</strong></span>
        </div>
    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 600px; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">Usage Tips:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            This tool handles leap years, varying month lengths, and exact date offsets. Perfect for calculating <strong>contract durations</strong>, verifying <strong>eligibility limits</strong>, or planning birthday milestones.
        </p>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const dobInput = document.getElementById('dob-input');
    const targetInput = document.getElementById('target-date-input');
    const calculateBtn = document.getElementById('calculate-btn');
    const resultBox = document.getElementById('age-result');

    const primaryAge = document.getElementById('primary-age');
    const totalMonths = document.getElementById('total-months');
    const totalWeeks = document.getElementById('total-weeks');
    const totalDays = document.getElementById('total-days');
    const totalHours = document.getElementById('total-hours');
    const countdownVal = document.getElementById('countdown-val');

    // Default target date is today
    const todayStr = new Date().toISOString().split('T')[0];
    targetInput.value = todayStr;

    calculateBtn.addEventListener('click', () => {
        const dobVal = dobInput.value;
        const targetVal = targetInput.value;

        if (!dobVal || !targetVal) {
            alert('Please select both your Date of Birth and the Target Date.');
            return;
        }

        const dob = new Date(dobVal);
        const target = new Date(targetVal);

        if (dob > target) {
            alert('Date of birth cannot be later than the target comparison date.');
            return;
        }

        // Calculate differences
        let years = target.getFullYear() - dob.getFullYear();
        let months = target.getMonth() - dob.getMonth();
        let days = target.getDate() - dob.getDate();

        if (days < 0) {
            months--;
            // Find length of previous month
            const prevMonthDate = new Date(target.getFullYear(), target.getMonth(), 0);
            days += prevMonthDate.getDate();
        }

        if (months < 0) {
            years--;
            months += 12;
        }

        // Output primary text
        primaryAge.textContent = `${years} Years, ${months} Months, ${days} Days`;

        // Detailed conversions
        const diffTime = Math.abs(target - dob);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const diffWeeks = Math.floor(diffDays / 7);
        const totalMonthCount = (years * 12) + months;
        const totalHourCount = diffDays * 24;

        totalMonths.textContent = totalMonthCount.toLocaleString();
        totalWeeks.textContent = diffWeeks.toLocaleString();
        totalDays.textContent = diffDays.toLocaleString();
        totalHours.textContent = totalHourCount.toLocaleString();

        // Calculate countdown to next birthday
        const nextBirthday = new Date(target.getFullYear(), dob.getMonth(), dob.getDate());
        if (target > nextBirthday) {
            nextBirthday.setFullYear(target.getFullYear() + 1);
        }
        
        let nextDiffTime = Math.abs(nextBirthday - target);
        let nextDiffDays = Math.ceil(nextDiffTime / (1000 * 60 * 60 * 24));
        
        if (nextDiffDays === 365 || nextDiffDays === 366 || nextDiffDays === 0) {
            countdownVal.textContent = "Happy Birthday! Today is the day 🎉";
        } else {
            let nextMonths = Math.floor(nextDiffDays / 30.4375);
            let nextDays = Math.round(nextDiffDays % 30.4375);
            countdownVal.textContent = `${nextMonths} Months and ${nextDays} Days`;
        }

        resultBox.style.display = 'block';
    });
});
</script>
