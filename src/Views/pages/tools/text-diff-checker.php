<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h2><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-binoculars') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'Text Diff Checker') ?></h2>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'Compare two text blocks side-by-side or inline to highlight additions, deletions, and differences.') ?></p>
</div>

<div class="tool-content" style="max-width: 100%; display: flex; flex-direction: column; gap: 20px; text-align: left;">
    
    <!-- Input Side-by-Side Panel -->
    <div style="display: flex; gap: 20px; flex-wrap: wrap; width: 100%;">
        <!-- Original Text Panel -->
        <div style="flex: 1; min-width: 300px; background: var(--color-surface); padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); display: flex; flex-direction: column; gap: 10px;">
            <label for="text-original" style="font-weight: 700; color: var(--color-text-primary); font-size: 0.95rem;">Original Text</label>
            <textarea id="text-original" placeholder="Paste original text here..." style="width: 100%; height: 220px; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-family: monospace; font-size: 0.9rem; background: var(--color-background); color: var(--color-text-primary); resize: vertical;"></textarea>
        </div>
        <!-- Modified Text Panel -->
        <div style="flex: 1; min-width: 300px; background: var(--color-surface); padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); display: flex; flex-direction: column; gap: 10px;">
            <label for="text-modified" style="font-weight: 700; color: var(--color-text-primary); font-size: 0.95rem;">Modified / Changed Text</label>
            <textarea id="text-modified" placeholder="Paste modified text here..." style="width: 100%; height: 220px; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-family: monospace; font-size: 0.9rem; background: var(--color-background); color: var(--color-text-primary); resize: vertical;"></textarea>
        </div>
    </div>

    <!-- Options and Compare Button -->
    <div style="background: var(--color-surface); padding: 15px 25px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 15px;">
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
            <!-- View Mode selector -->
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 0.9rem; font-weight: 600; color: var(--color-text-primary);">View Mode:</span>
                <div style="display: flex; gap: 4px; background: var(--color-background); padding: 4px; border-radius: var(--radius-md); border: 1px solid var(--color-border);">
                    <button id="btn-view-split" class="diff-toggle-btn active">Split</button>
                    <button id="btn-view-inline" class="diff-toggle-btn">Unified</button>
                </div>
            </div>

            <!-- Case sensitivity toggle -->
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.9rem; color: var(--color-text-secondary); font-weight: 500;">
                <input type="checkbox" id="diff-case" style="accent-color: var(--color-primary);" checked> Case Sensitive
            </label>
        </div>

        <button id="compare-btn" class="btn btn-primary" style="padding: 0 24px; height: 42px; font-weight: 600;">
            Find Differences
        </button>
    </div>

    <!-- Differences Output Panel -->
    <div id="diff-output-wrap" style="display: none; background: var(--color-surface); padding: 25px; border-radius: var(--radius-lg); border: 1px solid var(--color-border);">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--color-text-primary); margin-bottom: 15px;">Comparison Results</h3>
        
        <!-- Split Mode View -->
        <div id="diff-split-view" style="display: flex; gap: 15px; width: 100%; overflow-x: auto;">
            <!-- Original Line numbers + Text -->
            <div style="flex: 1; min-width: 280px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); font-family: var(--font-mono); font-size: 0.85rem; line-height: 1.5; padding: 15px; white-space: pre;">
                <h4 style="font-family: var(--font-sans); font-size: 0.8rem; font-weight: 700; color: var(--color-text-secondary); text-transform: uppercase; margin-bottom: 10px; border-bottom: 1px solid var(--color-border); padding-bottom: 6px;">Original</h4>
                <div id="split-original-lines" class="diff-code-wrapper"></div>
            </div>
            <!-- Modified Line numbers + Text -->
            <div style="flex: 1; min-width: 280px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); font-family: var(--font-mono); font-size: 0.85rem; line-height: 1.5; padding: 15px; white-space: pre;">
                <h4 style="font-family: var(--font-sans); font-size: 0.8rem; font-weight: 700; color: var(--color-text-secondary); text-transform: uppercase; margin-bottom: 10px; border-bottom: 1px solid var(--color-border); padding-bottom: 6px;">Modified</h4>
                <div id="split-modified-lines" class="diff-code-wrapper"></div>
            </div>
        </div>

        <!-- Unified Mode View -->
        <div id="diff-inline-view" style="display: none; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); font-family: var(--font-mono); font-size: 0.85rem; line-height: 1.5; padding: 15px; white-space: pre; overflow-x: auto;">
            <h4 style="font-family: var(--font-sans); font-size: 0.8rem; font-weight: 700; color: var(--color-text-secondary); text-transform: uppercase; margin-bottom: 10px; border-bottom: 1px solid var(--color-border); padding-bottom: 6px;">Unified Diff</h4>
            <div id="inline-lines" class="diff-code-wrapper"></div>
        </div>
    </div>

</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 100%; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">Understanding Diff Highlights:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            Lines highlighted in <strong style="color: var(--color-danger);">Red</strong> represent text that has been removed or modified. Lines highlighted in <strong style="color: var(--color-success);">Green</strong> represent text additions. Split mode allows side-by-side code reviews while Unified mode displays changes chronologically in a single feed.
        </p>
    </div>
</div>

<style>
.diff-toggle-btn {
    padding: 6px 12px;
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    border-radius: var(--radius-sm);
    transition: all var(--transition-fast);
}
.diff-toggle-btn:hover {
    color: var(--color-text-primary);
}
.diff-toggle-btn.active {
    background: var(--color-surface);
    color: var(--color-primary);
    box-shadow: var(--shadow-sm);
}

/* Diff Rendering Styles */
.diff-code-wrapper {
    display: flex;
    flex-direction: column;
}
.diff-line {
    display: flex;
    width: 100%;
    min-height: 20px;
    padding: 0 4px;
}
.diff-line-num {
    width: 35px;
    color: var(--color-text-secondary);
    user-select: none;
    text-align: right;
    padding-right: 12px;
    border-right: 1px solid var(--color-border);
    margin-right: 12px;
    opacity: 0.6;
}
.diff-line-content {
    flex: 1;
    white-space: pre-wrap;
    word-break: break-all;
}

/* Highlights */
.diff-added {
    background-color: rgba(16, 185, 129, 0.15) !important;
    border-left: 3px solid var(--color-success);
}
.diff-removed {
    background-color: rgba(239, 68, 68, 0.15) !important;
    border-left: 3px solid var(--color-danger);
}
.diff-empty {
    background-color: rgba(0, 0, 0, 0.02);
    opacity: 0.3;
}
.char-add {
    background-color: rgba(16, 185, 129, 0.35);
    border-radius: 2px;
}
.char-del {
    background-color: rgba(239, 68, 68, 0.35);
    text-decoration: line-through;
    border-radius: 2px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const textOriginal = document.getElementById('text-original');
    const textModified = document.getElementById('text-modified');
    const btnSplit = document.getElementById('btn-view-split');
    const btnInline = document.getElementById('btn-view-inline');
    const optCase = document.getElementById('diff-case');
    const compareBtn = document.getElementById('compare-btn');

    const outputWrap = document.getElementById('diff-output-wrap');
    const splitView = document.getElementById('diff-split-view');
    const inlineView = document.getElementById('diff-inline-view');
    
    const splitOriginalLines = document.getElementById('split-original-lines');
    const splitModifiedLines = document.getElementById('split-modified-lines');
    const inlineLines = document.getElementById('inline-lines');

    let viewMode = 'split'; // 'split' or 'inline'

    // Simple line-level LCS alignment algorithm
    const diffLines = (origLines, modLines, caseSensitive) => {
        const dp = Array(origLines.length + 1).fill(0).map(() => Array(modLines.length + 1).fill(0));

        const getCompareVal = (str) => caseSensitive ? str : str.toLowerCase();

        for (let i = 1; i <= origLines.length; i++) {
            for (let j = 1; j <= modLines.length; j++) {
                if (getCompareVal(origLines[i-1]) === getCompareVal(modLines[j-1])) {
                    dp[i][j] = dp[i-1][j-1] + 1;
                } else {
                    dp[i][j] = Math.max(dp[i-1][j], dp[i][j-1]);
                }
            }
        }

        // Backtrack to assemble alignment list
        let i = origLines.length;
        let j = modLines.length;
        const alignments = [];

        while (i > 0 || j > 0) {
            if (i > 0 && j > 0 && getCompareVal(origLines[i-1]) === getCompareVal(modLines[j-1])) {
                alignments.push({
                    type: 'unchanged',
                    origLine: origLines[i-1],
                    modLine: modLines[j-1],
                    origNum: i,
                    modNum: j
                });
                i--;
                j--;
            } else if (j > 0 && (i === 0 || dp[i][j-1] >= dp[i-1][j])) {
                alignments.push({
                    type: 'added',
                    origLine: null,
                    modLine: modLines[j-1],
                    origNum: null,
                    modNum: j
                });
                j--;
            } else {
                alignments.push({
                    type: 'removed',
                    origLine: origLines[i-1],
                    modLine: null,
                    origNum: i,
                    modNum: null
                });
                i--;
            }
        }

        return alignments.reverse();
    };

    // Calculate word/character level differences inside a line
    const highlightCharDiff = (origStr, modStr) => {
        // Simple word-by-word differences to keep it clean and readable
        const origWords = origStr.split(/(\s+)/);
        const modWords = modStr.split(/(\s+)/);
        
        // Find LCS of words
        const dp = Array(origWords.length + 1).fill(0).map(() => Array(modWords.length + 1).fill(0));
        for (let i = 1; i <= origWords.length; i++) {
            for (let j = 1; j <= modWords.length; j++) {
                if (origWords[i-1] === modWords[j-1]) {
                    dp[i][j] = dp[i-1][j-1] + 1;
                } else {
                    dp[i][j] = Math.max(dp[i-1][j], dp[i][j-1]);
                }
            }
        }

        let i = origWords.length;
        let j = modWords.length;
        const origHTML = [];
        const modHTML = [];

        while (i > 0 || j > 0) {
            if (i > 0 && j > 0 && origWords[i-1] === modWords[j-1]) {
                const w = escapeHtml(origWords[i-1]);
                origHTML.push(w);
                modHTML.push(w);
                i--;
                j--;
            } else if (j > 0 && (i === 0 || dp[i][j-1] >= dp[i-1][j])) {
                modHTML.push(`<span class="char-add">${escapeHtml(modWords[j-1])}</span>`);
                j--;
            } else {
                origHTML.push(`<span class="char-del">${escapeHtml(origWords[i-1])}</span>`);
                i--;
            }
        }

        return {
            origHTML: origHTML.reverse().join(''),
            modHTML: modHTML.reverse().join('')
        };
    };

    const escapeHtml = (text) => {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    };

    const renderDiffs = () => {
        const origText = textOriginal.value;
        const modText = textModified.value;
        const caseSensitive = optCase.checked;

        const origLines = origText.split(/\r?\n/);
        const modLines = modText.split(/\r?\n/);

        const diffResult = diffLines(origLines, modLines, caseSensitive);

        splitOriginalLines.innerHTML = '';
        splitModifiedLines.innerHTML = '';
        inlineLines.innerHTML = '';

        let inlineIndex = 1;

        diffResult.forEach(item => {
            // Render Split View
            const origRow = document.createElement('div');
            const modRow = document.createElement('div');

            origRow.className = 'diff-line';
            modRow.className = 'diff-line';

            // Inline View row
            const inlineRow = document.createElement('div');
            inlineRow.className = 'diff-line';

            if (item.type === 'unchanged') {
                const escaped = escapeHtml(item.origLine);
                
                origRow.innerHTML = `<span class="diff-line-num">${item.origNum}</span><span class="diff-line-content">${escaped}</span>`;
                modRow.innerHTML = `<span class="diff-line-num">${item.modNum}</span><span class="diff-line-content">${escaped}</span>`;

                inlineRow.innerHTML = `<span class="diff-line-num">${item.origNum}</span><span class="diff-line-content">${escaped}</span>`;
            } 
            else if (item.type === 'removed') {
                origRow.classList.add('diff-removed');
                modRow.classList.add('diff-empty');

                const escaped = escapeHtml(item.origLine);
                origRow.innerHTML = `<span class="diff-line-num">${item.origNum}</span><span class="diff-line-content">- ${escaped}</span>`;
                modRow.innerHTML = `<span class="diff-line-num"> </span><span class="diff-line-content"></span>`;

                inlineRow.classList.add('diff-removed');
                inlineRow.innerHTML = `<span class="diff-line-num">${item.origNum}</span><span class="diff-line-content">- ${escaped}</span>`;
            } 
            else if (item.type === 'added') {
                origRow.classList.add('diff-empty');
                modRow.classList.add('diff-added');

                const escaped = escapeHtml(item.modLine);
                origRow.innerHTML = `<span class="diff-line-num"> </span><span class="diff-line-content"></span>`;
                modRow.innerHTML = `<span class="diff-line-num">${item.modNum}</span><span class="diff-line-content">+ ${escaped}</span>`;

                inlineRow.classList.add('diff-added');
                inlineRow.innerHTML = `<span class="diff-line-num">${item.modNum}</span><span class="diff-line-content">+ ${escaped}</span>`;
            }

            splitOriginalLines.appendChild(origRow);
            splitModifiedLines.appendChild(modRow);
            inlineLines.appendChild(inlineRow);
        });

        // Loop over split view and apply inline character highlights to adjacent deletions/additions
        // to make changes stand out even more!
        const origElements = splitOriginalLines.querySelectorAll('.diff-line');
        const modElements = splitModifiedLines.querySelectorAll('.diff-line');

        for (let k = 0; k < Math.min(origElements.length, modElements.length); k++) {
            if (origElements[k].classList.contains('diff-removed') && modElements[k].classList.contains('diff-added')) {
                // Get the raw text of both
                const origVal = origLines[diffResult[k].origNum - 1] || '';
                const modVal = modLines[diffResult[k].modNum - 1] || '';

                const highlight = highlightCharDiff(origVal, modVal);

                origElements[k].querySelector('.diff-line-content').innerHTML = '- ' + highlight.origHTML;
                modElements[k].querySelector('.diff-line-content').innerHTML = '+ ' + highlight.modHTML;
            }
        }

        outputWrap.style.display = 'block';
    };

    // View Switching
    btnSplit.addEventListener('click', () => {
        btnSplit.classList.add('active');
        btnInline.classList.remove('active');
        splitView.style.display = 'flex';
        inlineView.style.display = 'none';
        viewMode = 'split';
    });

    btnInline.addEventListener('click', () => {
        btnInline.classList.add('active');
        btnSplit.classList.remove('active');
        splitView.style.display = 'none';
        inlineView.style.display = 'block';
        viewMode = 'inline';
    });

    compareBtn.addEventListener('click', renderDiffs);

    // Initial default diff
    textOriginal.value = "Hello World\nThis is a simple text comparison.\nDifferent lines are highlighted.\nHave a nice day!";
    textModified.value = "Hello brand new World\nThis is a simple text comparison.\nSome different lines are highlighted beautifully.\nHave a nice day!";
    renderDiffs();
});
</script>
