<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h2><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-font') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'Text to ASCII Art') ?></h2>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'Convert normal text into decorative ASCII art banners instantly.') ?></p>
</div>

<div class="tool-content" style="display: flex; gap: 20px; flex-wrap: wrap; text-align: left;">
    
    <!-- Controls Panel -->
    <div style="flex: 1; min-width: 300px; background: var(--color-surface); padding: 25px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); display: flex; flex-direction: column; gap: 20px;">
        
        <!-- Input Text -->
        <div>
            <label for="ascii-input" style="display: block; font-size: 0.9rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 8px;">Your Text</label>
            <input type="text" id="ascii-input" value="UTILIX" placeholder="Type here..." style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-weight: 600;" autofocus>
        </div>

        <!-- Font Selector -->
        <div>
            <label for="ascii-font" style="display: block; font-size: 0.9rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 8px;">Select Font Style</label>
            <select id="ascii-font" style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-weight: 600; cursor: pointer;">
                <option value="standard" selected>Standard Block</option>
                <option value="slant">Slant style</option>
                <option value="bubble">Bubble / Rounded</option>
                <option value="shadow">Shadow 3D</option>
            </select>
        </div>

        <!-- Custom Replacement Character -->
        <div>
            <label for="ascii-char" style="display: block; font-size: 0.9rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 8px;">Fill Character (Optional)</label>
            <input type="text" id="ascii-char" placeholder="Default (e.g. #, /, @)" maxlength="1" style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-background); color: var(--color-text-primary); font-weight: 600; text-align: center;">
            <p style="font-size: 0.75rem; color: var(--color-text-secondary); margin-top: 5px;">Overrides the characters used to build the ASCII art.</p>
        </div>
    </div>

    <!-- Output Panel -->
    <div style="flex: 2; min-width: 320px; background: var(--color-surface); padding: 25px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="color: var(--color-text-primary); font-size: 1.1rem; font-weight: 700;">ASCII Art Preview</h3>
            <button id="copy-btn" style="padding: 6px 12px; background: var(--color-background); border: 1px solid var(--color-border); color: var(--color-text-primary); border-radius: var(--radius-md); cursor: pointer; font-weight: 500;"><i class="fa-solid fa-copy"></i> Copy Art</button>
        </div>
        <div style="overflow-x: auto; background: var(--color-background); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 20px;">
            <pre id="ascii-output" style="margin: 0; font-family: var(--font-mono); font-size: 0.85rem; line-height: 1.2; color: var(--color-text-primary); white-space: pre;"></pre>
        </div>
    </div>

</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 100%; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">ASCII Banners:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            ASCII banners are widely used in source code comments, build terminal greetings (MOTD), README files, and logs. For best alignment, always display the copied art in a **monospaced font** environment.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('ascii-input');
    const fontSelect = document.getElementById('ascii-font');
    const fillCharInput = document.getElementById('ascii-char');
    const output = document.getElementById('ascii-output');
    const copyBtn = document.getElementById('copy-btn');

    // Mini FIGlet font definitions for A-Z
    const fonts = {
        standard: {
            height: 5,
            charMap: {
                'A': ["  ▲  ", " ▲ ▲ ", "▲▲▲▲▲", "▲   ▲", "▲   ▲"],
                'B': ["████ ", "█   █", "████ ", "█   █", "████ "],
                'C': [" ████", "█    ", "█    ", "█    ", " ████"],
                'D': ["████ ", "█   █", "█   █", "█   █", "████ "],
                'E': ["█████", "█    ", "████ ", "█    ", "█████"],
                'F': ["█████", "█    ", "████ ", "█    ", "█    "],
                'G': [" ████", "█    ", "█  ██", "█   █", " ████"],
                'H': ["█   █", "█   █", "█████", "█   █", "█   █"],
                'I': ["█████", "  █  ", "  █  ", "  █  ", "█████"],
                'J': [" ████", "   █ ", "   █ ", "█  █ ", " ██  "],
                'K': ["█   █", "█  █ ", "███  ", "█  █ ", "█   █"],
                'L': ["█    ", "█    ", "█    ", "█    ", "█████"],
                'M': ["█   █", "██ ██", "█ █ █", "█   █", "█   █"],
                'N': ["█   █", "██  █", "█ █ █", "█  ██", "█   █"],
                'O': [" ███ ", "█   █", "█   █", "█   █", " ███ "],
                'P': ["████ ", "█   █", "████ ", "█    ", "█    "],
                'Q': [" ███ ", "█   █", "█ █ █", "█  █ ", " ██ █"],
                'R': ["████ ", "█   █", "████ ", "█  █ ", "█   █"],
                'S': [" ████", "█    ", " ███ ", "    █", "████ "],
                'T': ["█████", "  █  ", "  █  ", "  █  ", "  █  "],
                'U': ["█   █", "█   █", "█   █", "█   █", " ███ "],
                'V': ["█   █", "█   █", " █ █ ", " █ █ ", "  █  "],
                'W': ["█   █", "█   █", "█ █ █", "██ ██", "█   █"],
                'X': ["█   █", " █ █ ", "  █  ", " █ █ ", "█   █"],
                'Y': ["█   █", " █ █ ", "  █  ", "  █  ", "  █  "],
                'Z': ["█████", "   █ ", "  █  ", " █   ", "█████"],
                ' ': ["     ", "     ", "     ", "     ", "     "],
                '?': [" ███ ", "    █", "  ██ ", "     ", "  █  "],
                '!': ["  █  ", "  █  ", "  █  ", "     ", "  █  "],
                '.': ["     ", "     ", "     ", "     ", "  █  "]
            }
        },
        slant: {
            height: 5,
            charMap: {
                'A': ["   /|   ", "  / |   ", " / /| | ", "/_/__ | ", "    |/  "],
                'B': [" ___  ", "/ _ \\ ", "\\_  / ", "/ _ \\ ", "\\___/ "],
                'C': ["  ____ ", " / ___|", "| |    ", "| |___ ", " \\____|"],
                'D': [" ____  ", "|  _ \\ ", "| | | |", "| |_| |", "|____/ "],
                'E': [" _____ ", "| ____|", "|  _|  ", "| |___ ", "|_____|"],
                'F': [" _____ ", "|  ___|", "| |_   ", "|  _|  ", "|_|    "],
                'G': ["  ____ ", " / ___|", "| |  _ ", "| |_| |", " \\____|"],
                'H': [" _   _ ", "| | | |", "| |_| |", "|  _  |", "|_| |_|"],
                'I': [" ___ ", "|_ _|", " | | ", " | | ", "|___|"],
                'J': ["     _", "    | |", " _  | |", "| |_| |", " \\___/ "],
                'K': [" _  __", "| |/ /", "| ' / ", "| . \\ ", "|_|\\_\\"],
                'L': [" _     ", "| |    ", "| |    ", "| |___ ", "|_____|"],
                'M': [" __  __ ", "|  \\/  |", "| |\\/| |", "| |  | |", "|_|  |_|"],
                'N': [" _   _ ", "| \\ | |", "|  \\| |", "| |\\  |", "|_| \\_|"],
                'O': ["  ___  ", " / _ \\ ", "| | | |", "| |_| |", " \\___/ "],
                'P': [" ____  ", "|  _ \\ ", "| |_) |", "|  __/ ", "|_|    "],
                'Q': ["  ___  ", " / _ \\ ", "| | | |", "| |_| |", " \\__\\\\\\"],
                'R': [" ____  ", "|  _ \\ ", "| |_) |", "|  _ < ", "|_| \\_\\"],
                'S': [" ____  ", "/ ___| ", "\\___ \\ ", " ___) |", "|____/ "],
                'T': [" _____ ", "|_   _|", "  | |  ", "  | |  ", "  |_|  "],
                'U': [" _   _ ", "| | | |", "| | | |", "| |_| |", " \\___/ "],
                'V': [" _   _ ", "| | | |", "| | | |", " \\ V / ", "  \\_/  "],
                'W': [" _      __", "| | /| / /", "| |/ |/ / ", "|__/__|   ", "  |/|/    "],
                'X': ["__  __", "\\ \\/ /", " \\  / ", " /  \\ ", "/_/\\_\\"],
                'Y': ["__   __", "\\ \\ / /", " \\ V / ", "  | |  ", "  |_|  "],
                'Z': [" _____ ", "|__  / ", "  / /  ", " / /_  ", "/____| "],
                ' ': ["    ", "    ", "    ", "    ", "    "],
                '?': [" ___ ", "/ _ \\", "  _/ ", " |_| ", " (_) "],
                '!': [" _ ", "| |", "| |", " |_|", " (_)"],
                '.': ["   ", "   ", "   ", " _ ", "(_)" ]
            }
        },
        bubble: {
            height: 5,
            charMap: {
                'A': ["  Ⓞ  ", " Ⓞ Ⓞ ", "ⓄⓄⓄⓄⓄ", "Ⓞ   Ⓞ", "Ⓞ   Ⓞ"],
                'B': ["ⓄⓄⓄ  ", "Ⓞ  Ⓞ ", "ⓄⓄⓄ  ", "Ⓞ  Ⓞ ", "ⓄⓄⓄ  "],
                'C': [" ⓄⓄⓄ ", "Ⓞ    ", "Ⓞ    ", "Ⓞ    ", " ⓄⓄⓄ "],
                'D': ["ⓄⓄⓄ  ", "Ⓞ  Ⓞ ", "Ⓞ  Ⓞ ", "Ⓞ  Ⓞ ", "ⓄⓄⓄ  "],
                'E': ["ⓄⓄⓄⓄⓄ", "Ⓞ    ", "ⓄⓄⓄ  ", "Ⓞ    ", "ⓄⓄⓄⓄⓄ"],
                'F': ["ⓄⓄⓄⓄⓄ", "Ⓞ    ", "ⓄⓄⓄ  ", "Ⓞ    ", "Ⓞ    "],
                'G': [" ⓄⓄⓄ ", "Ⓞ    ", "Ⓞ ⓄⓄ ", "Ⓞ  Ⓞ ", " ⓄⓄⓄ "],
                'H': ["Ⓞ   Ⓞ", "Ⓞ   Ⓞ", "ⓄⓄⓄⓄⓄ", "Ⓞ   Ⓞ", "Ⓞ   Ⓞ"],
                'I': ["ⓄⓄⓄⓄⓄ", "  Ⓞ  ", "  Ⓞ  ", "  Ⓞ  ", "ⓄⓄⓄⓄⓄ"],
                'J': ["  ⓄⓄⓄ", "    Ⓞ", "    Ⓞ", "Ⓞ   Ⓞ", " ⓄⓄⓄ "],
                'K': ["Ⓞ   Ⓞ", "Ⓞ  Ⓞ ", "ⓄⓄⓄ  ", "Ⓞ  Ⓞ ", "Ⓞ   Ⓞ"],
                'L': ["Ⓞ    ", "Ⓞ    ", "Ⓞ    ", "Ⓞ    ", "ⓄⓄⓄⓄⓄ"],
                'M': ["Ⓞ   Ⓞ", "ⓄⓄ ⓄⓄ", "Ⓞ Ⓞ Ⓞ", "Ⓞ   Ⓞ", "Ⓞ   Ⓞ"],
                'N': ["Ⓞ   Ⓞ", "ⓄⓄ  Ⓞ", "Ⓞ Ⓞ Ⓞ", "Ⓞ  ⓄⓄ", "Ⓞ   Ⓞ"],
                'O': [" ⓄⓄⓄ ", "Ⓞ   Ⓞ", "Ⓞ   Ⓞ", "Ⓞ   Ⓞ", " ⓄⓄⓄ "],
                'P': ["ⓄⓄⓄ  ", "Ⓞ  Ⓞ ", "ⓄⓄⓄ  ", "Ⓞ    ", "Ⓞ    "],
                'Q': [" ⓄⓄⓄ ", "Ⓞ   Ⓞ", "Ⓞ Ⓞ Ⓞ", "Ⓞ  Ⓞ ", " ⓄⓄⓄⓄ"],
                'R': ["ⓄⓄⓄ  ", "Ⓞ  Ⓞ ", "ⓄⓄⓄ  ", "Ⓞ  Ⓞ ", "Ⓞ   Ⓞ"],
                'S': [" ⓄⓄⓄ ", "Ⓞ    ", " ⓄⓄⓄ ", "    Ⓞ", "ⓄⓄⓄ  "],
                'T': ["ⓄⓄⓄⓄⓄ", "  Ⓞ  ", "  Ⓞ  ", "  Ⓞ  ", "  Ⓞ  "],
                'U': ["Ⓞ   Ⓞ", "Ⓞ   Ⓞ", "Ⓞ   Ⓞ", "Ⓞ   Ⓞ", " ⓄⓄⓄ "],
                'V': ["Ⓞ   Ⓞ", "Ⓞ   Ⓞ", " Ⓞ Ⓞ ", " Ⓞ Ⓞ ", "  Ⓞ  "],
                'W': ["Ⓞ   Ⓞ", "Ⓞ   Ⓞ", "Ⓞ Ⓞ Ⓞ", "ⓄⓄ ⓄⓄ", "Ⓞ   Ⓞ"],
                'X': ["Ⓞ   Ⓞ", " Ⓞ Ⓞ ", "  Ⓞ  ", " Ⓞ Ⓞ ", "Ⓞ   Ⓞ"],
                'Y': ["Ⓞ   Ⓞ", " Ⓞ Ⓞ ", "  Ⓞ  ", "  Ⓞ  ", "  Ⓞ  "],
                'Z': ["ⓄⓄⓄⓄⓄ", "   Ⓞ ", "  Ⓞ  ", " Ⓞ   ", "ⓄⓄⓄⓄⓄ"],
                ' ': ["     ", "     ", "     ", "     ", "     "],
                '?': [" ⓄⓄⓄ ", "    Ⓞ", "  ⓄⓄ ", "     ", "  Ⓞ  "],
                '!': ["  Ⓞ  ", "  Ⓞ  ", "  Ⓞ  ", "     ", "  Ⓞ  "],
                '.': ["     ", "     ", "     ", "     ", "  Ⓞ  "]
            }
        },
        shadow: {
            height: 5,
            charMap: {
                'A': [" ▄▄▄▄ ", "█    █", "██████", "█    █", "█    █"],
                'B': ["█████▄", "█    █", "█████▀", "█    █", "█████▀"],
                'C': [" ▄████", "█     ", "█     ", "█     ", " ▀████"],
                'D': ["█████▄", "█    █", "█    █", "█    █", "█████▀"],
                'E': ["██████", "█     ", "█████ ", "█     ", "██████"],
                'F': ["██████", "█     ", "█████ ", "█     ", "█     "],
                'G': [" ▄████", "█     ", "█   ██", "█    █", " ▀████"],
                'H': ["█    █", "█    █", "██████", "█    █", "█    █"],
                'I': ["██████", "  ██  ", "  ██  ", "  ██  ", "██████"],
                'J': ["  ████", "    ██", "    ██", "█   ██", " ████ "],
                'K': ["█   ██", "█  ██ ", "████  ", "█  ██ ", "█   ██"],
                'L': ["█     ", "█     ", "█     ", "█     ", "██████"],
                'M': ["█    █", "██  ██", "█ ██ █", "█    █", "█    █"],
                'N': ["█    █", "██   █", "█ █  █", "█  █ █", "█    █"],
                'O': [" ▄██▄ ", "█    █", "█    █", "█    █", " ▀██▀ "],
                'P': ["█████▄", "█    █", "█████▀", "█     ", "█     "],
                'Q': [" ▄██▄ ", "█    █", "█ █  █", "█  █▄▀", " ▀██ █"],
                'R': ["█████▄", "█    █", "█████▀", "█   █ ", "█    █"],
                'S': [" ▄████", "█     ", " ▀██▄ ", "    ██", "█████▀"],
                'T': ["██████", "  ██  ", "  ██  ", "  ██  ", "  ██  "],
                'U': ["█    █", "█    █", "█    █", "█    █", " ▀██▀ "],
                'V': ["█    █", "█    █", " █  █ ", " █  █ ", "  ██  "],
                'W': ["█    █", "█    █", "█ █  █", "██ ███", "█    █"],
                'X': ["█    █", " █  █ ", "  ██  ", " █  █ ", "█    █"],
                'Y': ["█    █", " █  █ ", "  ██  ", "  ██  ", "  ██  "],
                'Z': ["██████", "   ██ ", "  ██  ", " ██   ", "██████"],
                ' ': ["     ", "     ", "     ", "     ", "     "],
                '?': [" ▄██▄ ", "    ██", "   ██ ", "      ", "  ██  "],
                '!': ["  ██  ", "  ██  ", "  ██  ", "      ", "  ██  "],
                '.': ["      ", "      ", "      ", "      ", "  ██  "]
            }
        }
    };

    const generateAscii = () => {
        const text = input.value.toUpperCase();
        const selectedFontName = fontSelect.value;
        const font = fonts[selectedFontName] || fonts.standard;
        const overrideChar = fillCharInput.value;

        if (!text) {
            output.textContent = "";
            return;
        }

        let lines = Array(font.height).fill("");

        for (let i = 0; i < text.length; i++) {
            const char = text[i];
            const art = font.charMap[char] || font.charMap['?'];
            
            for (let lineIndex = 0; lineIndex < font.height; lineIndex++) {
                let segment = art[lineIndex];
                
                // If override fill character specified
                if (overrideChar) {
                    // Replace all non-space characters with the override character
                    segment = segment.replace(/[^ ]/g, overrideChar);
                }
                
                lines[lineIndex] += segment + "  "; // add space between characters
            }
        }

        output.textContent = lines.join("\n");
    };

    // Listeners
    input.addEventListener('input', generateAscii);
    fontSelect.addEventListener('change', generateAscii);
    fillCharInput.addEventListener('input', generateAscii);

    copyBtn.addEventListener('click', () => {
        if (!output.textContent) return;
        navigator.clipboard.writeText(output.textContent).then(() => {
            const originalText = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
            setTimeout(() => {
                copyBtn.innerHTML = originalText;
            }, 2000);
        });
    });

    // Initial load
    generateAscii();
});
</script>
