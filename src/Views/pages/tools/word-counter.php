<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h1><i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i> <?= htmlspecialchars($tool['name']) ?></h1>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description']) ?></p>
</div>

<div class="tool-content" style="background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border);">
    <textarea id="text-input" placeholder="Type or paste your text here..." style="width: 100%; height: 250px; padding: 15px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 1rem; margin-bottom: 20px; font-family: inherit; background: var(--color-background); color: var(--color-text-primary); resize: vertical;"></textarea>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; text-align: center;">
        <div style="background: var(--color-background); padding: 15px; border-radius: var(--radius-md); border: 1px solid var(--color-border);">
            <h4 style="color: var(--color-text-secondary); margin-bottom: 5px; font-size: 0.9rem;">Words</h4>
            <span id="word-count" style="font-size: 2rem; font-weight: bold; color: var(--color-primary);">0</span>
        </div>
        <div style="background: var(--color-background); padding: 15px; border-radius: var(--radius-md); border: 1px solid var(--color-border);">
            <h4 style="color: var(--color-text-secondary); margin-bottom: 5px; font-size: 0.9rem;">Characters</h4>
            <span id="char-count" style="font-size: 2rem; font-weight: bold; color: var(--color-primary);">0</span>
        </div>
        <div style="background: var(--color-background); padding: 15px; border-radius: var(--radius-md); border: 1px solid var(--color-border);">
            <h4 style="color: var(--color-text-secondary); margin-bottom: 5px; font-size: 0.9rem;">Characters (no spaces)</h4>
            <span id="char-nospace-count" style="font-size: 2rem; font-weight: bold; color: var(--color-primary);">0</span>
        </div>
        <div style="background: var(--color-background); padding: 15px; border-radius: var(--radius-md); border: 1px solid var(--color-border);">
            <h4 style="color: var(--color-text-secondary); margin-bottom: 5px; font-size: 0.9rem;">Sentences</h4>
            <span id="sentence-count" style="font-size: 2rem; font-weight: bold; color: var(--color-primary);">0</span>
        </div>
    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 100%; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">Writing Guidelines:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            Use this tool to track limits for articles, tweets (280 chars), metadata tags (typically 60-160 chars), or school assignments. Tracking the character count excluding spaces is helpful for academic submissions.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const textInput = document.getElementById('text-input');
    const wordCount = document.getElementById('word-count');
    const charCount = document.getElementById('char-count');
    const charNoSpaceCount = document.getElementById('char-nospace-count');
    const sentenceCount = document.getElementById('sentence-count');

    textInput.addEventListener('input', () => {
        const text = textInput.value;
        
        charCount.textContent = text.length;
        charNoSpaceCount.textContent = text.replace(/\s+/g, '').length;
        
        const words = text.trim().split(/\s+/).filter(word => word.length > 0);
        wordCount.textContent = words.length;
        
        const sentences = text.split(/[.!?]+/).filter(sentence => sentence.trim().length > 0);
        sentenceCount.textContent = sentences.length;
    });
});
</script>
