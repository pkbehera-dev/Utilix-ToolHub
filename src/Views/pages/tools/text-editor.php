<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h1><i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i> <?= htmlspecialchars($tool['name']) ?></h1>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description']) ?></p>
</div>

<div class="tool-content" style="background: var(--color-surface); padding: 25px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
    
    <!-- Editor Toolbar -->
    <div class="editor-toolbar" style="display: flex; flex-wrap: wrap; gap: 8px; padding: 10px; background: var(--color-background); border: 1px solid var(--color-border); border-radius: var(--radius-md) var(--radius-md) 0 0; border-bottom: none; align-items: center;">
        
        <!-- Text Styles -->
        <button type="button" class="toolbar-btn" data-command="bold" title="Bold (Ctrl+B)">
            <i class="fa-solid fa-bold"></i>
        </button>
        <button type="button" class="toolbar-btn" data-command="italic" title="Italic (Ctrl+I)">
            <i class="fa-solid fa-italic"></i>
        </button>
        <button type="button" class="toolbar-btn" data-command="underline" title="Underline (Ctrl+U)">
            <i class="fa-solid fa-underline"></i>
        </button>
        <button type="button" class="toolbar-btn" data-command="strikeThrough" title="Strikethrough">
            <i class="fa-solid fa-strikethrough"></i>
        </button>
        
        <span class="toolbar-separator" style="width: 1px; height: 20px; background: var(--color-border); margin: 0 4px;"></span>

        <!-- Headings / Blocks -->
        <button type="button" class="toolbar-btn" data-command="formatBlock" data-value="h1" title="Heading 1">
            H1
        </button>
        <button type="button" class="toolbar-btn" data-command="formatBlock" data-value="h2" title="Heading 2">
            H2
        </button>
        <button type="button" class="toolbar-btn" data-command="formatBlock" data-value="p" title="Paragraph">
            P
        </button>

        <span class="toolbar-separator" style="width: 1px; height: 20px; background: var(--color-border); margin: 0 4px;"></span>

        <!-- Lists -->
        <button type="button" class="toolbar-btn" data-command="insertUnorderedList" title="Bulleted List">
            <i class="fa-solid fa-list-ul"></i>
        </button>
        <button type="button" class="toolbar-btn" data-command="insertOrderedList" title="Numbered List">
            <i class="fa-solid fa-list-ol"></i>
        </button>

        <span class="toolbar-separator" style="width: 1px; height: 20px; background: var(--color-border); margin: 0 4px;"></span>

        <!-- Alignment -->
        <button type="button" class="toolbar-btn" data-command="justifyLeft" title="Align Left">
            <i class="fa-solid fa-align-left"></i>
        </button>
        <button type="button" class="toolbar-btn" data-command="justifyCenter" title="Align Center">
            <i class="fa-solid fa-align-center"></i>
        </button>
        <button type="button" class="toolbar-btn" data-command="justifyRight" title="Align Right">
            <i class="fa-solid fa-align-right"></i>
        </button>

        <span class="toolbar-separator" style="width: 1px; height: 20px; background: var(--color-border); margin: 0 4px;"></span>

        <!-- Actions -->
        <button type="button" class="toolbar-btn" data-command="removeFormat" title="Clear Formatting">
            <i class="fa-solid fa-remove-format"></i>
        </button>
        
        <div style="margin-left: auto; display: flex; gap: 8px;">
            <button type="button" id="btn-copy" class="action-btn" title="Copy Content">
                <i class="fa-solid fa-copy"></i> Copy
            </button>
            <button type="button" id="btn-download-txt" class="action-btn" title="Download as TXT">
                <i class="fa-solid fa-file-lines"></i> TXT
            </button>
            <button type="button" id="btn-download-html" class="action-btn" title="Download as HTML">
                <i class="fa-solid fa-code"></i> HTML
            </button>
            <button type="button" id="btn-clear" class="action-btn danger-btn" title="Clear All">
                <i class="fa-solid fa-trash"></i> Clear
            </button>
        </div>
    </div>

    <!-- The Editable Area -->
    <div id="rich-editor" contenteditable="true" style="width: 100%; min-height: 400px; max-height: 600px; overflow-y: auto; padding: 20px; border: 1px solid var(--color-border); border-radius: 0 0 var(--radius-md) var(--radius-md); font-size: 1rem; font-family: inherit; background: var(--color-background); color: var(--color-text-primary); outline: none; line-height: 1.6;">
        <p>Start writing your content here...</p>
    </div>

    <!-- Info Stats Bar -->
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-top: 15px; padding: 10px; background: var(--color-background); border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 0.9rem; color: var(--color-text-secondary);">
        <div style="display: flex; gap: 20px;">
            <span>Words: <strong id="word-count" style="color: var(--color-primary);">5</strong></span>
            <span>Characters: <strong id="char-count" style="color: var(--color-primary);">38</strong></span>
        </div>
        <div id="editor-status" style="font-style: italic; font-size: 0.85rem; transition: opacity 0.3s;">Saved locally</div>
    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 100%; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">Features of Rich Text Editor:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            This editor supports formatting tools such as bold, italic, lists, and headings. Your changes are automatically saved to your browser's local storage so you won't lose your work if you refresh the page.
        </p>
    </div>
</div>

<style>
.toolbar-btn {
    background: transparent;
    border: 1px solid transparent;
    color: var(--color-text-primary);
    padding: 6px 12px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    transition: all 0.2s ease;
}

.toolbar-btn:hover {
    background: color-mix(in srgb, var(--color-primary) 10%, var(--color-surface));
    border-color: var(--color-border);
    color: var(--color-primary);
}

.toolbar-btn.active {
    background: var(--color-primary);
    color: #fff;
    border-color: var(--color-primary);
}

.action-btn {
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    color: var(--color-text-primary);
    padding: 6px 12px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.action-btn:hover {
    background: var(--color-background);
    border-color: var(--color-primary);
    color: var(--color-primary);
}

.danger-btn:hover {
    border-color: #ef4444;
    color: #ef4444;
}

/* Custom styling inside the rich editor */
#rich-editor h1 {
    font-size: 1.8rem;
    margin-top: 10px;
    margin-bottom: 10px;
    font-weight: 700;
}
#rich-editor h2 {
    font-size: 1.4rem;
    margin-top: 10px;
    margin-bottom: 10px;
    font-weight: 600;
}
#rich-editor p {
    margin-bottom: 12px;
}
#rich-editor ul, #rich-editor ol {
    margin-bottom: 12px;
    padding-left: 25px;
}
#rich-editor ul {
    list-style-type: disc;
}
#rich-editor ol {
    list-style-type: decimal;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const editor = document.getElementById('rich-editor');
    const wordCount = document.getElementById('word-count');
    const charCount = document.getElementById('char-count');
    const statusEl = document.getElementById('editor-status');

    // Load from local storage
    const savedContent = localStorage.getItem('utilix_text_editor_content');
    if (savedContent) {
        editor.innerHTML = savedContent;
    }

    // Execute Formatting Commands
    document.querySelectorAll('.toolbar-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const command = btn.getAttribute('data-command');
            const value = btn.getAttribute('data-value') || null;
            
            document.execCommand(command, false, value);
            editor.focus();
            updateStatsAndSave();
        });
    });

    // Handle standard keys/formatting state
    editor.addEventListener('keyup', updateStatsAndSave);
    editor.addEventListener('mouseup', updateStatsAndSave);
    editor.addEventListener('input', updateStatsAndSave);

    function updateStatsAndSave() {
        const text = editor.innerText || '';
        
        // Count characters
        charCount.textContent = text.length;

        // Count words
        const words = text.trim().split(/\s+/).filter(w => w.length > 0);
        wordCount.textContent = words.length;

        // Save content locally
        localStorage.setItem('utilix_text_editor_content', editor.innerHTML);
        
        statusEl.textContent = 'Auto-saved';
        statusEl.style.opacity = '1';
        
        setTimeout(() => {
            if (statusEl.textContent === 'Auto-saved') {
                statusEl.textContent = 'Saved locally';
            }
        }, 1500);
    }

    // Initial stats
    updateStatsAndSave();

    // Copy Content
    document.getElementById('btn-copy').addEventListener('click', () => {
        const text = editor.innerText;
        navigator.clipboard.writeText(text).then(() => {
            const originalText = document.getElementById('btn-copy').innerHTML;
            document.getElementById('btn-copy').innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
            setTimeout(() => {
                document.getElementById('btn-copy').innerHTML = originalText;
            }, 1500);
        });
    });

    // Download TXT
    document.getElementById('btn-download-txt').addEventListener('click', () => {
        const text = editor.innerText;
        const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'edited-text.txt';
        a.click();
        URL.revokeObjectURL(url);
    });

    // Download HTML
    document.getElementById('btn-download-html').addEventListener('click', () => {
        const html = editor.innerHTML;
        const fullHtml = `<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edited Document</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; max-width: 800px; margin: 0 auto; color: #333; }
        h1 { font-size: 2em; margin-bottom: 0.5em; }
        h2 { font-size: 1.5em; margin-bottom: 0.5em; }
        p { margin-bottom: 1em; }
    </style>
</head>
<body>
    ${html}
</body>
</html>`;
        const blob = new Blob([fullHtml], { type: 'text/html;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'edited-document.html';
        a.click();
        URL.revokeObjectURL(url);
    });

    // Clear content
    document.getElementById('btn-clear').addEventListener('click', () => {
        if (confirm('Are you sure you want to clear the editor?')) {
            editor.innerHTML = '<p><br></p>';
            updateStatsAndSave();
        }
    });
});
</script>
