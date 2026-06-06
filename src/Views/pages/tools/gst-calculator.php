<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h1><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-calculator') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'GST Calculator') ?></h1>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'Calculate Goods and Services Tax (GST) values with CGST and SGST splits.') ?></p>
</div>

<div class="tool-content" style="max-width: 600px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); text-align: left;">
    
    <!-- Calculation Type Selection -->
    <div style="margin-bottom: 20px; display: flex; gap: 10px;">
        <button id="btn-add-gst" style="flex: 1; padding: 12px; border: 1px solid var(--color-primary); background: var(--color-primary); color: white; border-radius: var(--radius-md); cursor: pointer; font-weight: 600; text-align: center;">Add GST</button>
        <button id="btn-remove-gst" style="flex: 1; padding: 12px; border: 1px solid var(--color-border); background: var(--color-background); color: var(--color-text-primary); border-radius: var(--radius-md); cursor: pointer; font-weight: 600; text-align: center;">Remove GST</button>
    </div>

    <!-- Amount input -->
    <div style="margin-bottom: 20px;">
        <label for="amount-input" style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text-primary);">Base Amount (₹)</label>
        <input type="number" id="amount-input" value="1000" min="0" step="any" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-size: 1rem; font-weight: bold;">
    </div>

    <!-- GST Rate Select -->
    <div style="margin-bottom: 25px;">
        <label for="gst-rate" style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text-primary);">GST Tax Rate</label>
        <select id="gst-rate" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-size: 1rem; cursor: pointer; font-weight: 500;">
            <option value="5">5% (Common Utilities)</option>
            <option value="12">12% (Standard Items)</option>
            <option value="18" selected>18% (Services & Tech Products)</option>
            <option value="28">28% (Luxury Items)</option>
        </select>
    </div>

    <!-- Calculations Output -->
    <div style="background: var(--color-background); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 20px; display: flex; flex-direction: column; gap: 14px; font-size: 0.95rem;">
        
        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--color-border); padding-bottom: 8px;">
            <span style="color: var(--color-text-secondary);">Net Amount (Excl. Tax):</span>
            <span id="output-net" style="font-weight: bold; color: var(--color-text-primary);">₹1,000.00</span>
        </div>

        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--color-border); padding-bottom: 8px;">
            <span style="color: var(--color-text-secondary);">CGST (Central Tax):</span>
            <span id="output-cgst" style="font-weight: bold; color: var(--color-text-primary);">₹90.00</span>
        </div>

        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--color-border); padding-bottom: 8px;">
            <span style="color: var(--color-text-secondary);">SGST / UTGST (State Tax):</span>
            <span id="output-sgst" style="font-weight: bold; color: var(--color-text-primary);">₹90.00</span>
        </div>

        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--color-border); padding-bottom: 8px;">
            <span style="color: var(--color-text-secondary); font-weight: 600;">Total Tax Amount:</span>
            <span id="output-tax" style="font-weight: bold; color: var(--color-primary);">₹180.00</span>
        </div>

        <div style="display: flex; justify-content: space-between; padding-top: 4px;">
            <span style="color: var(--color-text-primary); font-weight: 700; font-size: 1.05rem;">Gross Amount (Incl. Tax):</span>
            <span id="output-total" style="font-weight: 800; color: var(--color-success); font-size: 1.15rem;">₹1,180.00</span>
        </div>

    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 600px; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">Tax Calculation Tip:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            Use <strong>Add GST</strong> when calculating the final sale price including tax. Use <strong>Remove GST</strong> if the base cost is already inclusive of tax and you need to isolate the net value of the service/item.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btnAdd = document.getElementById('btn-add-gst');
    const btnRemove = document.getElementById('btn-remove-gst');
    const amountInput = document.getElementById('amount-input');
    const gstRateSelect = document.getElementById('gst-rate');

    const outputNet = document.getElementById('output-net');
    const outputCgst = document.getElementById('output-cgst');
    const outputSgst = document.getElementById('output-sgst');
    const outputTax = document.getElementById('output-tax');
    const outputTotal = document.getElementById('output-total');

    let mode = 'add'; // 'add' or 'remove'

    const formatCurrency = (val) => {
        return '₹' + parseFloat(val).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    };

    const calculate = () => {
        const amount = parseFloat(amountInput.value) || 0;
        const rate = parseFloat(gstRateSelect.value) || 0;

        let net = 0;
        let tax = 0;
        let total = 0;

        if (mode === 'add') {
            net = amount;
            tax = (amount * rate) / 100;
            total = amount + tax;
        } else {
            total = amount;
            net = amount / (1 + (rate / 100));
            tax = total - net;
        }

        const halfTax = tax / 2;

        outputNet.textContent = formatCurrency(net);
        outputCgst.textContent = formatCurrency(halfTax);
        outputSgst.textContent = formatCurrency(halfTax);
        outputTax.textContent = formatCurrency(tax);
        outputTotal.textContent = formatCurrency(total);
    };

    btnAdd.addEventListener('click', () => {
        mode = 'add';
        btnAdd.style.background = 'var(--color-primary)';
        btnAdd.style.color = 'white';
        btnAdd.style.borderColor = 'var(--color-primary)';
        btnRemove.style.background = 'var(--color-background)';
        btnRemove.style.color = 'var(--color-text-primary)';
        btnRemove.style.borderColor = 'var(--color-border)';
        calculate();
    });

    btnRemove.addEventListener('click', () => {
        mode = 'remove';
        btnRemove.style.background = 'var(--color-primary)';
        btnRemove.style.color = 'white';
        btnRemove.style.borderColor = 'var(--color-primary)';
        btnAdd.style.background = 'var(--color-background)';
        btnAdd.style.color = 'var(--color-text-primary)';
        btnAdd.style.borderColor = 'var(--color-border)';
        calculate();
    });

    amountInput.addEventListener('input', calculate);
    gstRateSelect.addEventListener('change', calculate);

    // Initial calc
    calculate();
});
</script>
