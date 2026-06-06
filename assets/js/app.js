document.addEventListener('DOMContentLoaded', () => {
    // 1. Theme Toggle Logic
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    const root = document.documentElement;
    
    const savedTheme = localStorage.getItem('theme') || 'light';
    root.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = root.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            root.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
    }

    function updateThemeIcon(theme) {
        if (!themeToggleBtn) return;
        themeToggleBtn.innerHTML = theme === 'dark' ? '<i class="fa-solid fa-sun"></i>' : '<i class="fa-solid fa-moon"></i>';
    }

    // 2. Command Palette Logic
    const cmdBackdrop = document.getElementById('cmd-palette-backdrop');
    const cmdInput = document.getElementById('cmd-input');
    const headerSearchBtn = document.getElementById('header-search-btn');
    const mobileSearchFab = document.getElementById('mobile-search-fab');
    const catButtons = document.querySelectorAll('.cmd-cat-btn');
    
    let activeCategory = 'all';

    function openCommandPalette() {
        if (cmdBackdrop) {
            cmdBackdrop.style.display = 'flex';
            setTimeout(() => {
                cmdInput.focus();
            }, 50);
        }
    }

    function closeCommandPalette() {
        if (cmdBackdrop) {
            cmdBackdrop.style.display = 'none';
            if (cmdInput) cmdInput.value = '';
            
            // Reset active category
            activeCategory = 'all';
            catButtons.forEach(b => {
                if (b.getAttribute('data-category') === 'all') {
                    b.classList.add('active');
                } else {
                    b.classList.remove('active');
                }
            });
            
            filterCommandPalette('');
        }
    }

    if (headerSearchBtn) {
        headerSearchBtn.addEventListener('click', openCommandPalette);
    }

    if (mobileSearchFab) {
        mobileSearchFab.addEventListener('click', openCommandPalette);
    }

    if (cmdBackdrop) {
        // Close when clicking outside the palette
        cmdBackdrop.addEventListener('click', (e) => {
            if (e.target === cmdBackdrop) {
                closeCommandPalette();
            }
        });
    }

    // Category button click handlers
    catButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            catButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            activeCategory = btn.getAttribute('data-category');
            
            // Re-run filter with current search input value
            const term = cmdInput ? cmdInput.value : '';
            filterCommandPalette(term);
        });
    });

    // Hotkeys for Command Palette
    document.addEventListener('keydown', (e) => {
        // Ctrl+K or Cmd+K
        if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K' || e.keyCode === 75 || e.code === 'KeyK')) {
            e.preventDefault();
            e.stopPropagation();
            openCommandPalette();
            return false;
        }
        
        // '/' key (when not in an input)
        if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
            e.preventDefault();
            openCommandPalette();
        }

        // Escape to close
        if (e.key === 'Escape') {
            closeCommandPalette();
        }
    });

    // 3. Command Palette / Search Filtering Logic
    function filterCommandPalette(term) {
        const cmdItems = document.querySelectorAll('.cmd-item');
        if (!cmdItems.length) return;
        
        term = term.toLowerCase();
        
        cmdItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            const category = item.getAttribute('data-category-slug');
            
            const matchesTerm = text.includes(term);
            const matchesCategory = (activeCategory === 'all' || category === activeCategory);
            
            if (matchesTerm && matchesCategory) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    if (cmdInput) {
        cmdInput.addEventListener('input', (e) => {
            filterCommandPalette(e.target.value);
            
            // Also filter homepage grid if on homepage
            filterHomepageGrid(e.target.value);
        });
    }
    
    // 4. Homepage Grid Filtering
    function filterHomepageGrid(term) {
        const toolCards = document.querySelectorAll('.tools-grid .tool-card');
        const categories = document.querySelectorAll('.category-section');

        if (!toolCards.length) return;
        term = term.toLowerCase();

        toolCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const desc = card.querySelector('p').textContent.toLowerCase();
            
            if (title.includes(term) || desc.includes(term)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });

        categories.forEach(cat => {
            let anyVisible = false;
            cat.querySelectorAll('.tool-card').forEach(c => {
                if (c.style.display !== 'none') anyVisible = true;
            });
            cat.style.display = anyVisible ? 'block' : 'none';
        });
    }
    
    // Fallback: If there's an inline search bar on the homepage directly (not the command palette)
    const homepageSearch = document.getElementById('tool-search');
    if (homepageSearch) {
        homepageSearch.addEventListener('input', (e) => filterHomepageGrid(e.target.value));
    }

    // 5. Live Header Clock Widget
    const clockTime = document.getElementById('clock-time');
    const clockDate = document.getElementById('clock-date');

    function updateClock() {
        if (!clockTime || !clockDate) return;
        
        const now = new Date();
        
        // Time format: HH:MM:SS
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        clockTime.textContent = `${hours}:${minutes}:${seconds}`;
        
        // Date format: Mon DD, YYYY (e.g., Jun 05, 2026)
        const options = { month: 'short', day: '2-digit', year: 'numeric' };
        clockDate.textContent = now.toLocaleDateString('en-US', options);
    }

    if (clockTime && clockDate) {
        updateClock();
        setInterval(updateClock, 1000);
    }
});
