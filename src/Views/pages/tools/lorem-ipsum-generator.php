<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h2><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-file-lines') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'Lorem Ipsum Generator') ?></h2>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'Generate custom dummy or placeholder text for your designs and layouts.') ?></p>
</div>

<div class="tool-content" style="display: flex; gap: 20px; flex-wrap: wrap; text-align: left;">
    
    <!-- Controls Panel -->
    <div style="flex: 1; min-width: 300px; background: var(--color-surface); padding: 25px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); display: flex; flex-direction: column; gap: 20px;">
        
        <!-- Generate Unit -->
        <div>
            <label for="generate-type" style="display: block; font-size: 0.9rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 8px;">Generate Type</label>
            <select id="generate-type" style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-weight: 600; cursor: pointer;">
                <option value="paragraphs" selected>Paragraphs</option>
                <option value="sentences">Sentences</option>
                <option value="words">Words</option>
                <option value="lists">List Items</option>
            </select>
        </div>

        <!-- Count -->
        <div>
            <label for="generate-count" style="display: block; font-size: 0.9rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 8px;">Count</label>
            <input type="number" id="generate-count" min="1" max="100" value="5" style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-weight: 600;">
        </div>

        <!-- Options -->
        <div>
            <h4 style="margin-bottom: 10px; color: var(--color-text-primary); font-size: 0.9rem; font-weight: 600;">Options</h4>
            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.9rem; color: var(--color-text-secondary);">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" id="opt-start-lorem" style="accent-color: var(--color-primary);" checked> Start with "Lorem ipsum..."
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" id="opt-html-tags" style="accent-color: var(--color-primary);"> Wrap with HTML tags
                </label>
            </div>
        </div>

        <!-- Action Button -->
        <button id="generate-btn" style="width: 100%; padding: 14px; background: var(--color-primary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-size: 1.1rem; font-weight: 600; transition: background-color var(--transition-fast);">
            Generate Text
        </button>

    </div>

    <!-- Output Panel -->
    <div style="flex: 1.5; min-width: 320px; background: var(--color-surface); padding: 25px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="color: var(--color-text-primary); font-size: 1.1rem; font-weight: 700;">Generated Text</h3>
            <button id="copy-btn" style="padding: 6px 12px; background: var(--color-background); border: 1px solid var(--color-border); color: var(--color-text-primary); border-radius: var(--radius-md); cursor: pointer; font-weight: 500;"><i class="fa-solid fa-copy"></i> Copy</button>
        </div>
        <textarea id="output-text" readonly placeholder="Your generated text will appear here..." style="width: 100%; height: 350px; padding: 15px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-family: monospace; font-size: 0.95rem; background: var(--color-background); color: var(--color-text-primary); resize: vertical; line-height: 1.6;"></textarea>
    </div>

</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 100%; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">Why use Lorem Ipsum?</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            Lorem Ipsum is dummy text used by designers to focus attention on the layout and typography without being distracted by readable content. Using HTML tags wrapping is perfect for pasting directly into code editors.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const typeSelect = document.getElementById('generate-type');
    const countInput = document.getElementById('generate-count');
    const optStartLorem = document.getElementById('opt-start-lorem');
    const optHtmlTags = document.getElementById('opt-html-tags');
    const generateBtn = document.getElementById('generate-btn');
    const copyBtn = document.getElementById('copy-btn');
    const outputText = document.getElementById('output-text');

    const standardLorem = [
        "lorem", "ipsum", "dolor", "sit", "amet", "consectetur", "adipiscing", "elit", 
        "sed", "do", "eiusmod", "tempor", "incididunt", "ut", "labore", "et", "dolore", 
        "magna", "aliqua", "ut", "enim", "ad", "minim", "veniam", "quis", "nostrud", 
        "exercitation", "ullamco", "laboris", "nisi", "ut", "aliquip", "ex", "ea", 
        "commodo", "consequat", "duis", "aute", "irure", "dolor", "in", "reprehenderit", 
        "in", "voluptate", "velit", "esse", "cillum", "dolore", "eu", "fugiat", "nulla", 
        "pariatur", "excepteur", "sint", "occaecat", "cupidatat", "non", "proident", 
        "sunt", "in", "culpa", "qui", "officia", "deserunt", "mollit", "anim", "id", 
        "est", "laborum"
    ];

    const extraWords = [
        "at", "vero", "eos", "et", "accusamus", "et", "iusto", "odio", 
        "dignissimos", "ducimus", "qui", "blanditiis", "praesentium", "voluptatum", 
        "deleniti", "atque", "corrupti", "quos", "dolores", "et", "quas", "molestias", 
        "excepturi", "sint", "obcaecati", "cupiditate", "non", "provident", "similique", 
        "sunt", "in", "culpa", "qui", "officia", "deserunt", "mollitia", "animi", "id", 
        "est", "laborum", "et", "dolorum", "fuga", "harum", "quidem", "rerum", "facilis", 
        "est", "et", "expedita", "distinctio", "nam", "libero", "tempore", "cum", "soluta", 
        "nobis", "est", "eligendi", "optio", "cumque", "nihil", "impediet", "quo", "minus", 
        "id", "quod", "maxime", "placeat", "facere", "possimus", "omnis", "voluptas", 
        "assumenda", "est", "omnis", "dolor", "repellendus"
    ];

    const allWords = standardLorem.concat(extraWords);

    const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);

    const generateWords = (count, startWithLorem) => {
        let resultWords = [];
        if (startWithLorem) {
            resultWords = standardLorem.slice(0, count);
            while (resultWords.length < count) {
                const randWord = allWords[Math.floor(Math.random() * allWords.length)];
                resultWords.push(randWord);
            }
        } else {
            for (let i = 0; i < count; i++) {
                const randWord = allWords[Math.floor(Math.random() * allWords.length)];
                resultWords.push(randWord);
            }
        }
        return resultWords;
    };

    const generateSentence = (startWithLorem = false) => {
        const len = Math.floor(Math.random() * 6) + 8; // 8 to 13 words
        const sentenceWords = generateWords(len, startWithLorem);
        return capitalize(sentenceWords.join(' ')) + '.';
    };

    const generateParagraph = (startWithLorem = false) => {
        const sentenceCount = Math.floor(Math.random() * 3) + 4; // 4 to 6 sentences
        const sentences = [];
        for (let i = 0; i < sentenceCount; i++) {
            sentences.push(generateSentence(i === 0 && startWithLorem));
        }
        return sentences.join(' ');
    };

    const generateLorem = () => {
        const type = typeSelect.value;
        let count = parseInt(countInput.value) || 1;
        if (count < 1) count = 1;
        if (count > 100) count = 100;

        const startWithLorem = optStartLorem.checked;
        const wrapHtml = optHtmlTags.checked;

        let output = "";

        if (type === 'words') {
            const wordsList = generateWords(count, startWithLorem);
            const rawText = wordsList.join(' ');
            output = wrapHtml ? `<span>${rawText}</span>` : rawText;
        } 
        else if (type === 'sentences') {
            const sentencesList = [];
            for (let i = 0; i < count; i++) {
                sentencesList.push(generateSentence(i === 0 && startWithLorem));
            }
            if (wrapHtml) {
                output = sentencesList.map(s => `<span>${s}</span>`).join('\n');
            } else {
                output = sentencesList.join(' ');
            }
        } 
        else if (type === 'paragraphs') {
            const paragraphsList = [];
            for (let i = 0; i < count; i++) {
                paragraphsList.push(generateParagraph(i === 0 && startWithLorem));
            }
            if (wrapHtml) {
                output = paragraphsList.map(p => `<p>${p}</p>`).join('\n\n');
            } else {
                output = paragraphsList.join('\n\n');
            }
        } 
        else if (type === 'lists') {
            const listItems = [];
            for (let i = 0; i < count; i++) {
                const s = generateSentence(i === 0 && startWithLorem);
                listItems.push(s.slice(0, -1)); // Remove the period at the end of sentence
            }
            if (wrapHtml) {
                output = `<ul>\n` + listItems.map(li => `  <li>${li}</li>`).join('\n') + `\n</ul>`;
            } else {
                output = listItems.map(li => `• ${li}`).join('\n');
            }
        }

        outputText.value = output;
    };

    // Auto regenerate on control changes
    [typeSelect, optStartLorem, optHtmlTags].forEach(el => {
        el.addEventListener('change', generateLorem);
    });

    countInput.addEventListener('input', () => {
        let val = parseInt(countInput.value);
        if (isNaN(val) || val < 1) val = 1;
        if (val > 100) val = 100;
        countInput.value = val;
        generateLorem();
    });

    generateBtn.addEventListener('click', generateLorem);

    copyBtn.addEventListener('click', () => {
        if (!outputText.value) return;
        navigator.clipboard.writeText(outputText.value).then(() => {
            const originalText = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
            setTimeout(() => {
                copyBtn.innerHTML = originalText;
            }, 2000);
        });
    });

    // Generate initial on load
    generateLorem();
});
</script>
