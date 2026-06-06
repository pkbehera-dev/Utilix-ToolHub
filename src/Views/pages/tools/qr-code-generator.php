<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h1><i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i> <?= htmlspecialchars($tool['name']) ?></h1>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description']) ?></p>
</div>

<div class="tool-content" style="max-width: 500px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); text-align: center;">
    <input type="text" id="qr-input" placeholder="Enter text or URL here..." style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-size: 1rem;">
    
    <button id="generate-btn" style="padding: 12px 25px; background: var(--color-primary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-size: 1rem; margin-bottom: 25px; font-weight: 600;">Generate QR Code</button>
    
    <div id="qr-result" style="display: none; padding: 20px; background: var(--color-background); border-radius: var(--radius-md); border: 1px solid var(--color-border); flex-direction: column; align-items: center; justify-content: center;">
        <div style="background: white; padding: 15px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
            <img id="qr-image" src="" alt="QR Code" style="max-width: 100%; height: auto;">
        </div>
        <a id="download-btn" href="#" download="qrcode.png" style="margin-top: 20px; padding: 10px 20px; background: var(--color-primary); color: white; text-decoration: none; border-radius: var(--radius-md); font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;"><i class="fa-solid fa-download"></i> Download</a>
    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 500px; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">QR Code Scanning Tip:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            QR Codes are high-contrast matrix barcodes that link to websites, Wi-Fi networks, contact files (vCards), or plain text. Make sure your input text is not too long to maintain clean grid lines for faster mobile camera scanning.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const qrInput = document.getElementById('qr-input');
    const generateBtn = document.getElementById('generate-btn');
    const qrResult = document.getElementById('qr-result');
    const qrImage = document.getElementById('qr-image');
    const downloadBtn = document.getElementById('download-btn');
    
    // Initial state
    qrResult.style.display = 'none';

    generateBtn.addEventListener('click', () => {
        const text = qrInput.value.trim();
        if (!text) {
            alert('Please enter some text or a URL.');
            return;
        }

        generateBtn.innerHTML = 'Generating...';
        generateBtn.disabled = true;

        // Use the goqr.me API for quick generation
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(text)}`;
        
        qrImage.onload = () => {
            qrResult.style.display = 'flex';
            generateBtn.innerHTML = 'Generate QR Code';
            generateBtn.disabled = false;
        };
        
        qrImage.onerror = () => {
            alert('Failed to generate QR Code. Please try again.');
            generateBtn.innerHTML = 'Generate QR Code';
            generateBtn.disabled = false;
        };

        qrImage.src = qrUrl;
        downloadBtn.href = qrUrl;
    });
});
</script>
