<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h1><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-heart-pulse') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'BMI Calculator') ?></h1>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'Calculate Body Mass Index (BMI) and determine weight classification.') ?></p>
</div>

<div class="tool-content" style="max-width: 600px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); text-align: left;">
    
    <!-- Unit Selector -->
    <div style="margin-bottom: 20px; display: flex; gap: 10px;">
        <button id="btn-metric" style="flex: 1; padding: 12px; border: 1px solid var(--color-primary); background: var(--color-primary); color: white; border-radius: var(--radius-md); cursor: pointer; font-weight: 600; text-align: center;">Metric (kg/cm)</button>
        <button id="btn-imperial" style="flex: 1; padding: 12px; border: 1px solid var(--color-border); background: var(--color-background); color: var(--color-text-primary); border-radius: var(--radius-md); cursor: pointer; font-weight: 600; text-align: center;">Imperial (lbs/inches)</button>
    </div>

    <!-- Inputs Group -->
    <div style="margin-bottom: 25px; display: flex; flex-direction: column; gap: 18px;">
        
        <!-- Weight Input -->
        <div>
            <label for="weight-input" style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text-primary);">Weight (<span id="weight-unit">kg</span>)</label>
            <input type="number" id="weight-input" value="70" min="1" step="any" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-size: 1rem; font-weight: bold;">
        </div>

        <!-- Height Input -->
        <div>
            <label for="height-input" style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text-primary);">Height (<span id="height-unit">cm</span>)</label>
            <input type="number" id="height-input" value="175" min="1" step="any" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-size: 1rem; font-weight: bold;">
        </div>

    </div>

    <!-- Generate Button -->
    <button id="calc-btn" style="width: 100%; padding: 14px; background: var(--color-primary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-size: 1.1rem; font-weight: 600; margin-bottom: 25px;">
        Calculate BMI
    </button>

    <!-- Results -->
    <div id="bmi-result" style="display: none; animation: fadeIn 0.4s ease-out; border-top: 1px solid var(--color-border); padding-top: 20px;">
        <div style="display: flex; gap: 20px; align-items: center; background: var(--color-background); border: 1px solid var(--color-border); padding: 20px; border-radius: var(--radius-md); margin-bottom: 20px; flex-wrap: wrap;">
            
            <div style="flex: 1; text-align: center; min-width: 120px;">
                <span style="font-size: 0.85rem; color: var(--color-text-secondary); display: block; margin-bottom: 4px;">Your BMI</span>
                <span id="bmi-value" style="font-size: 2.5rem; font-weight: 800; color: var(--color-primary);">22.9</span>
            </div>

            <div style="flex: 2; min-width: 180px;">
                <span style="font-size: 0.85rem; color: var(--color-text-secondary); display: block; margin-bottom: 4px;">Classification</span>
                <span id="bmi-class" style="font-size: 1.35rem; font-weight: 700; color: var(--color-success);">Normal Weight</span>
                <p id="bmi-desc" style="font-size: 0.85rem; color: var(--color-text-secondary); margin-top: 6px; line-height: 1.4;">A healthy BMI range is between 18.5 and 24.9.</p>
            </div>

        </div>

        <!-- Visual Scale Meter -->
        <div>
            <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--color-text-secondary); margin-bottom: 8px;">
                <span>Underweight (&lt;18.5)</span>
                <span>Normal (18.5-25)</span>
                <span>Overweight (25-30)</span>
                <span>Obese (&gt;30)</span>
            </div>
            <div style="height: 12px; background: #e5e7eb; border-radius: 6px; position: relative; overflow: visible; display: flex;">
                <div style="width: 18.5%; background: #3b82f6; border-top-left-radius: 6px; border-bottom-left-radius: 6px;"></div>
                <div style="width: 25%; background: #10b981;"></div>
                <div style="width: 16.5%; background: #f59e0b;"></div>
                <div style="width: 40%; background: #ef4444; border-top-right-radius: 6px; border-bottom-right-radius: 6px;"></div>
                
                <!-- Meter Indicator -->
                <div id="bmi-indicator" style="position: absolute; top: -6px; left: 50%; width: 4px; height: 24px; background: var(--color-text-primary); border-radius: 2px; box-shadow: 0 0 4px rgba(0,0,0,0.3); transition: left 0.5s ease-in-out;">
                    <div style="width: 10px; height: 10px; background: var(--color-text-primary); border-radius: 50%; position: absolute; top: -6px; left: -3px;"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 600px; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">Health Information Disclaimer:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            Body Mass Index (BMI) is a general sizing helper derived from the mass and height of an individual. BMI is <strong>not</strong> a direct diagnostic tool of body fatness or overall metabolic health, as it does not distinguish muscle mass from fat storage.
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
    const btnMetric = document.getElementById('btn-metric');
    const btnImperial = document.getElementById('btn-imperial');
    const weightInput = document.getElementById('weight-input');
    const heightInput = document.getElementById('height-input');
    const weightUnit = document.getElementById('weight-unit');
    const heightUnit = document.getElementById('height-unit');
    const calcBtn = document.getElementById('calc-btn');
    const resultBox = document.getElementById('bmi-result');

    const bmiValue = document.getElementById('bmi-value');
    const bmiClass = document.getElementById('bmi-class');
    const bmiDesc = document.getElementById('bmi-desc');
    const bmiIndicator = document.getElementById('bmi-indicator');

    let unitSystem = 'metric'; // 'metric' or 'imperial'

    btnMetric.addEventListener('click', () => {
        unitSystem = 'metric';
        btnMetric.style.background = 'var(--color-primary)';
        btnMetric.style.color = 'white';
        btnMetric.style.borderColor = 'var(--color-primary)';
        btnImperial.style.background = 'var(--color-background)';
        btnImperial.style.color = 'var(--color-text-primary)';
        btnImperial.style.borderColor = 'var(--color-border)';
        
        weightUnit.textContent = 'kg';
        heightUnit.textContent = 'cm';
        weightInput.value = '70';
        heightInput.value = '175';
        resultBox.style.display = 'none';
    });

    btnImperial.addEventListener('click', () => {
        unitSystem = 'imperial';
        btnImperial.style.background = 'var(--color-primary)';
        btnImperial.style.color = 'white';
        btnImperial.style.borderColor = 'var(--color-primary)';
        btnMetric.style.background = 'var(--color-background)';
        btnMetric.style.color = 'var(--color-text-primary)';
        btnMetric.style.borderColor = 'var(--color-border)';
        
        weightUnit.textContent = 'lbs';
        heightUnit.textContent = 'inches';
        weightInput.value = '150';
        heightInput.value = '68';
        resultBox.style.display = 'none';
    });

    calcBtn.addEventListener('click', () => {
        const weight = parseFloat(weightInput.value) || 0;
        const height = parseFloat(heightInput.value) || 0;

        if (weight <= 0 || height <= 0) {
            alert('Please enter positive numbers for weight and height.');
            return;
        }

        let bmi = 0;
        if (unitSystem === 'metric') {
            const heightInMeters = height / 100;
            bmi = weight / (heightInMeters * heightInMeters);
        } else {
            // Imperial: (weight (lbs) / height (in)^2) * 703
            bmi = (weight / (height * height)) * 703;
        }

        const bmiFormatted = bmi.toFixed(1);
        bmiValue.textContent = bmiFormatted;

        // Classifications
        let classification = '';
        let descText = '';
        let colorClass = 'var(--color-success)';
        let percentOffset = 0;

        // Map percent position for gauge
        // Underweight limit 18.5: map to 0%-18.5%
        // Normal 18.5-25: map to 18.5%-43.5%
        // Overweight 25-30: map to 43.5%-60%
        // Obese 30-40+: map to 60%-100%
        if (bmi < 18.5) {
            classification = 'Underweight';
            descText = 'You have a lower body weight than recommended. Consult with a healthcare professional.';
            colorClass = '#3b82f6';
            percentOffset = (bmi / 18.5) * 18.5;
        } else if (bmi >= 18.5 && bmi < 25) {
            classification = 'Normal Weight';
            descText = 'Congratulations! You are within a healthy, recommended body weight range.';
            colorClass = '#10b981';
            percentOffset = 18.5 + (((bmi - 18.5) / 6.5) * 25);
        } else if (bmi >= 25 && bmi < 30) {
            classification = 'Overweight';
            descText = 'Your weight is slightly higher than recommended. Focus on nutrition and physical activity.';
            colorClass = '#f59e0b';
            percentOffset = 43.5 + (((bmi - 25) / 5) * 16.5);
        } else {
            classification = 'Obese';
            descText = 'You have a higher body mass classification than recommended. Consider consults for health advice.';
            colorClass = '#ef4444';
            // Scale between 30 and 40
            const relativeObese = Math.min(bmi, 40) - 30;
            percentOffset = 60 + ((relativeObese / 10) * 40);
        }

        bmiClass.textContent = classification;
        bmiClass.style.color = colorClass;
        bmiDesc.textContent = descText;

        // Position indicators
        bmiIndicator.style.left = `calc(${Math.min(Math.max(percentOffset, 1), 99)}% - 2px)`;

        resultBox.style.display = 'block';
    });
});
</script>
