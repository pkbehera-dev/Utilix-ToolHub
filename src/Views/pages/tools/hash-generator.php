<div class="tool-header" style="text-align: center; margin-bottom: 30px;">
    <h1><i class="fa-solid <?= htmlspecialchars($tool['icon'] ?? 'fa-hashtag') ?>"></i> <?= htmlspecialchars($tool['name'] ?? 'Hash Generator') ?></h1>
    <p style="color: var(--color-text-secondary);"><?= htmlspecialchars($tool['description'] ?? 'Generate cryptographic hashes (MD5, SHA1, SHA256) from input text.') ?></p>
</div>

<div class="tool-content" style="max-width: 800px; margin: 0 auto; background: var(--color-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--color-border);">
    
    <!-- Input Text -->
    <div style="margin-bottom: 25px;">
        <label for="hash-input" style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text-primary);">Input String</label>
        <textarea id="hash-input" placeholder="Type or paste text to generate hashes..." style="width: 100%; height: 120px; padding: 15px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 1rem; background: var(--color-background); color: var(--color-text-primary); resize: vertical;"></textarea>
    </div>

    <!-- Outputs -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        
        <!-- MD5 -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <label style="font-weight: 600; font-size: 0.9rem; color: var(--color-text-primary);">MD5 Hash</label>
                <button class="copy-hash-btn" data-target="md5-output" style="padding: 4px 10px; font-size: 0.8rem; background: var(--color-background); border: 1px solid var(--color-border); color: var(--color-text-primary); border-radius: var(--radius-md); cursor: pointer;"><i class="fa-solid fa-copy"></i> Copy</button>
            </div>
            <input type="text" id="md5-output" readonly placeholder="MD5 hash will appear here..." style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-family: monospace; background: var(--color-background); color: var(--color-text-primary); font-size: 0.95rem;">
        </div>

        <!-- SHA-1 -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <label style="font-weight: 600; font-size: 0.9rem; color: var(--color-text-primary);">SHA-1 Hash</label>
                <button class="copy-hash-btn" data-target="sha1-output" style="padding: 4px 10px; font-size: 0.8rem; background: var(--color-background); border: 1px solid var(--color-border); color: var(--color-text-primary); border-radius: var(--radius-md); cursor: pointer;"><i class="fa-solid fa-copy"></i> Copy</button>
            </div>
            <input type="text" id="sha1-output" readonly placeholder="SHA-1 hash will appear here..." style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-family: monospace; background: var(--color-background); color: var(--color-text-primary); font-size: 0.95rem;">
        </div>

        <!-- SHA-256 -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <label style="font-weight: 600; font-size: 0.9rem; color: var(--color-text-primary);">SHA-256 Hash</label>
                <button class="copy-hash-btn" data-target="sha256-output" style="padding: 4px 10px; font-size: 0.8rem; background: var(--color-background); border: 1px solid var(--color-border); color: var(--color-text-primary); border-radius: var(--radius-md); cursor: pointer;"><i class="fa-solid fa-copy"></i> Copy</button>
            </div>
            <input type="text" id="sha256-output" readonly placeholder="SHA-256 hash will appear here..." style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-family: monospace; background: var(--color-background); color: var(--color-text-primary); font-size: 0.95rem;">
        </div>

    </div>
</div>

<!-- Tips Banner -->
<div class="tips-info-banner" style="max-width: 800px; margin: 25px auto 0 auto; background: color-mix(in srgb, var(--color-primary) 6%, var(--color-surface)); border: 1px solid color-mix(in srgb, var(--color-primary) 20%, var(--color-border)); padding: 18px; border-radius: var(--radius-lg); text-align: left; display: flex; gap: 12px; align-items: flex-start;">
    <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem; margin-top: 2px;"></i>
    <div>
        <h4 style="color: var(--color-text-primary); font-size: 0.95rem; font-weight: 600; margin-bottom: 4px;">Hash Algorithms Overview:</h4>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; line-height: 1.4; margin: 0;">
            Cryptographic hashes are one-way algorithms that turn input data into a fixed-length signature. <strong>MD5</strong> (128-bit) and <strong>SHA-1</strong> (160-bit) are fast but deprecated for secure verification due to collision vulnerabilities; they are still widely used for file integrity checksums. <strong>SHA-256</strong> (256-bit) remains highly secure and is standard for passwords, signatures, and SSL certificates.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('hash-input');
    const md5Out = document.getElementById('md5-output');
    const sha1Out = document.getElementById('sha1-output');
    const sha256Out = document.getElementById('sha256-output');

    // JS MD5 Implementation (Lightweight & zero dependencies)
    function md5(string) {
        function k(e, t) { return (e << t) | (e >>> (32 - t)); }
        function add(e, t) {
            var n = (65535 & e) + (65535 & t);
            return (((e >> 16) + (t >> 16) + (n >> 16)) << 16) | (65535 & n);
        }
        var r, a, s, u, l, c = [],
            f = (string = function(e) {
                for (var t = "", n = -1, r = e.length; ++n < r;) {
                    var o = e.charCodeAt(n), i = n + 1 < r ? e.charCodeAt(n + 1) : 0;
                    55296 <= o && o <= 56319 && 56320 <= i && i <= 57343 && (o = 65536 + ((1023 & o) << 10) + (1023 & i), n++), o <= 127 ? t += String.fromCharCode(o) : o <= 2047 ? t += String.fromCharCode(192 | (o >>> 6) & 31, 128 | 63 & o) : o <= 65535 ? t += String.fromCharCode(224 | (o >>> 12) & 15, 128 | (o >>> 6) & 63, 128 | 63 & o) : o <= 2097151 && (t += String.fromCharCode(240 | (o >>> 18) & 7, 128 | (o >>> 12) & 63, 128 | (o >>> 6) & 63, 128 | 63 & o));
                }
                return t;
            }(string)).length,
            d = [1732584193, -271733879, -1732584194, 271733878],
            p = [
                [0, 7, 1, -680876936], [1, 12, 2, -389564586], [2, 17, 3, 606105819], [3, 22, 4, -1044525330],
                [4, 7, 5, -176418897], [5, 12, 6, 1200080426], [6, 17, 7, -1473231341], [7, 22, 8, -45705983],
                [8, 7, 9, 220023854], [9, 12, 10, -199777123], [10, 17, 11, -12868547], [11, 22, 12, -305641478],
                [12, 7, 13, -165796510], [13, 12, 14, -1926607734], [14, 17, 15, 605148128], [15, 22, 16, -183282452],
                [1, 5, 17, -350595208], [6, 9, 18, -38957127], [11, 14, 19, -1019803690], [0, 20, 20, -187363961],
                [5, 5, 21, -406467532], [10, 9, 22, 1890953168], [15, 14, 23, -405474789], [4, 20, 24, -1504105821],
                [9, 5, 25, -57434055], [14, 9, 26, 1700485571], [3, 14, 27, -1894986606], [8, 20, 28, -1051523],
                [13, 5, 29, -2054922799], [2, 9, 30, 1873313359], [7, 14, 31, -30611744], [12, 20, 32, -1560198380],
                [5, 4, 33, -145523070], [8, 11, 34, 2203062768], [11, 16, 35, -998377323], [14, 23, 36, -32512436],
                [1, 4, 37, -15861034], [4, 11, 38, -2000729546], [7, 16, 39, -5386127], [10, 23, 40, -357617536],
                [13, 4, 41, -1531059523], [0, 11, 42, 1666859590], [3, 16, 43, -1529301670], [6, 23, 44, -14936021],
                [9, 4, 45, -22531933], [12, 11, 46, -1419355405], [15, 16, 47, 1885024883], [2, 23, 48, -48203110],
                [0, 6, 49, -27952123], [7, 10, 50, 1705492771], [14, 15, 51, -1894524888], [5, 21, 52, -1332730302],
                [12, 6, 53, -2051331049], [3, 10, 54, 1871927772], [10, 15, 55, -42492613], [1, 21, 56, -1562281546],
                [8, 6, 57, -182125556], [15, 10, 58, -30611744], [6, 15, 59, -1579812260], [13, 21, 60, 24436028],
                [4, 6, 61, -1263304543], [11, 10, 62, -1813359560], [2, 15, 63, 607979037], [9, 21, 64, -20121404]
            ];
        for (r = 0; r < f; r++) c[r >> 2] |= (255 & string.charCodeAt(r)) << (r % 4 * 8);
        for (c[f >> 2] |= 128 << (f % 4 * 8), c[16 * (f + 8 >> 6) + 14] = 8 * f, r = 0; r < c.length; r += 16) {
            var g = d.slice(0);
            for (a = 0; a < 64; a++) {
                var h = p[a];
                s = g[3 - a % 4],
                u = g[1 - a % 4],
                l = g[2 - a % 4],
                g[1 - a % 4] = add(g[1 - a % 4], k(add(g[a % 4], add(a < 16 ? (u & l) | (~u & s) : a < 32 ? (u & s) | (l & ~s) : u ^ l ^ s, add(h[3], c[16 * (r >> 4) + h[0]]))), h[1]));
            }
            for (a = 0; a < 4; a++) d[a] = add(d[a], g[a]);
        }
        for (r = 0, string = ""; r < 32; r++) string += ((d[r >> 3] >> (r % 8 * 4 + 4)) & 15).toString(16) + ((d[r >> 3] >> (r % 8 * 4)) & 15).toString(16);
        return string;
    }

    // Web Crypto API helpers (for SHA-1 and SHA-256)
    async function shaDigest(string, algo) {
        const msgUint8 = new TextEncoder().encode(string);
        const hashBuffer = await window.crypto.subtle.digest(algo, msgUint8);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    }

    async function updateHashes() {
        const text = input.value;
        
        // Generate MD5
        md5Out.value = md5(text);

        // Generate SHA-1
        try {
            sha1Out.value = await shaDigest(text, 'SHA-1');
        } catch (e) {
            sha1Out.value = 'Calculation error.';
        }

        // Generate SHA-256
        try {
            sha256Out.value = await shaDigest(text, 'SHA-256');
        } catch (e) {
            sha256Out.value = 'Calculation error.';
        }
    }

    // Run on keypress/input change
    input.addEventListener('input', updateHashes);

    // Initial run
    updateHashes();

    // Copy buttons logic
    const copyBtns = document.querySelectorAll('.copy-hash-btn');
    copyBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            if (!targetInput.value) return;

            navigator.clipboard.writeText(targetInput.value).then(() => {
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                }, 1500);
            });
        });
    });
});
</script>
