<?php
/**
 * Quote Generator Tool View
 */
use App\Core\Security;
use App\Config\App;

$csrfToken = Security::generateCsrfToken();
?>
<div class="tool-container" style="max-width: 850px; margin-inline: auto; padding-top: 2rem;">
    <!-- Tool Header -->
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; background: linear-gradient(135deg, var(--color-primary), #10B981); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Quote Generator
        </h1>
        <p class="text-muted">Generate inspiring quotes from various categories or contribute your own favorite quotes to the community.</p>
    </div>

    <!-- View Switcher Tabs -->
    <div style="display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 2.5rem;">
        <button id="view-generator-btn" class="btn btn-primary view-toggle-btn" style="font-size: 0.9rem; padding: 0.5rem 1.5rem; border-radius: var(--radius-full);">
            <i class="fa-solid fa-shuffle"></i> Random Generator
        </button>
        <button id="view-table-btn" class="btn btn-secondary view-toggle-btn" style="font-size: 0.9rem; padding: 0.5rem 1.5rem; border-radius: var(--radius-full);">
            <i class="fa-solid fa-list"></i> View All Quotes
        </button>
    </div>

    <!-- Category selector (Shared by both views) -->
    <div style="display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 2rem; flex-wrap: wrap;" id="category-tabs">
        <button class="btn btn-secondary active-tab" data-cat="All" style="font-size: 0.85rem; padding: 0.4rem 1rem; border-radius: var(--radius-full);">All Categories</button>
        <button class="btn btn-secondary" data-cat="Motivation" style="font-size: 0.85rem; padding: 0.4rem 1rem; border-radius: var(--radius-full);">Motivation</button>
        <button class="btn btn-secondary" data-cat="Life" style="font-size: 0.85rem; padding: 0.4rem 1rem; border-radius: var(--radius-full);">Life</button>
        <button class="btn btn-secondary" data-cat="Technology" style="font-size: 0.85rem; padding: 0.4rem 1rem; border-radius: var(--radius-full);">Technology</button>
        <button class="btn btn-secondary" data-cat="Inspirational" style="font-size: 0.85rem; padding: 0.4rem 1rem; border-radius: var(--radius-full);">Inspirational</button>
        <button class="btn btn-secondary" data-cat="Humor" style="font-size: 0.85rem; padding: 0.4rem 1rem; border-radius: var(--radius-full);">Humor</button>
    </div>

    <!-- View 1: Random Quote Board -->
    <div id="generator-view" class="premium-card" style="margin-bottom: 3rem; text-align: center; padding: 3rem 2rem; position: relative;">
        <!-- Quote icon indicator -->
        <div style="font-size: 3.5rem; color: var(--color-primary); opacity: 0.15; position: absolute; top: 1.5rem; left: 2rem; font-family: Georgia, serif;">“</div>
        <div style="font-size: 3.5rem; color: var(--color-primary); opacity: 0.15; position: absolute; bottom: 1.5rem; right: 2rem; font-family: Georgia, serif;">”</div>

        <!-- Quote display canvas -->
        <div style="min-height: 150px; display: flex; flex-direction: column; justify-content: center; align-items: center; margin-bottom: 2rem;">
            <p id="quote-display-text" style="font-size: 1.5rem; font-weight: 500; line-height: 1.6; color: var(--color-text-primary); margin-bottom: 1rem; font-style: italic;">
                "Loading quote..."
            </p>
            <p id="quote-display-author" style="font-size: 1.1rem; font-weight: 600; color: var(--color-primary);">
                - Loading...
            </p>
            <!-- User submission credit (small text) -->
            <p id="quote-display-submitted" style="font-size: 0.75rem; color: var(--color-text-secondary); margin-top: 0.5rem; display: none;">
                submitted by: <span id="submitter-name"></span>
            </p>
        </div>

        <button id="generate-btn" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem; background: linear-gradient(135deg, var(--color-primary), #10B981); border: none; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);">
            <i class="fa-solid fa-arrows-rotate"></i> Next Quote
        </button>
    </div>

    <!-- View 2: All Quotes Table View -->
    <div id="table-view" class="premium-card" style="display: none; padding: 2rem; margin-bottom: 3rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 700;">All Quotes</h3>
                <span class="text-xs text-muted" id="table-count" style="background: var(--color-background); border: 1px solid var(--color-border); padding: 0.125rem 0.5rem; border-radius: 4px;">0</span>
            </div>
            
            <input type="text" id="table-search" placeholder="Search quotes, authors, submitters..." style="background: var(--color-background); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 0.5rem 1rem; width: 250px; font-size: 0.9rem; color: var(--color-text-primary); outline: none;">
        </div>

        <div style="overflow-x: auto; max-height: 450px; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;" id="quotes-table">
                <thead>
                    <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-text-secondary); font-weight: 600; position: sticky; top: 0; background: var(--color-surface); z-index: 10;">
                        <th style="padding: 0.75rem 1rem;">Quote</th>
                        <th style="padding: 0.75rem 1rem; width: 150px;">Author</th>
                        <th style="padding: 0.75rem 1rem; width: 120px;">Category</th>
                        <th style="padding: 0.75rem 1rem; width: 130px;">Submitted By</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: var(--color-text-secondary);">Loading quotes...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Submit Quote Card -->
    <div class="premium-card" style="padding: 2rem;">
        <h3 style="font-size: 1.35rem; font-weight: 700; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-feather-pointed" style="color: var(--color-primary);"></i> Submit a Quote
        </h3>
        <p class="text-sm text-muted" style="margin-bottom: 1.5rem;">Share a quote with the community. (Maximum limit of 100 quotes per category).</p>

        <form id="quote-submit-form" class="features-submit-form">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <div class="form-group">
                <label for="quote_category">Category</label>
                <select id="quote_category" name="category" required style="background-color: var(--color-background); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 0.75rem; color: var(--color-text-primary); font-size: 0.95rem;">
                    <option value="" disabled selected>Select a Category</option>
                    <option value="Motivation">Motivation</option>
                    <option value="Life">Life</option>
                    <option value="Technology">Technology</option>
                    <option value="Inspirational">Inspirational</option>
                    <option value="Humor">Humor</option>
                </select>
            </div>

            <div class="form-group">
                <label for="quote_text">Quote Text</label>
                <textarea id="quote_text" name="quote_text" placeholder="Write the quote text here..." rows="3" required></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="quote_author">Author Name</label>
                    <input type="text" id="quote_author" name="author" placeholder="e.g. Albert Einstein" required>
                </div>
                <div class="form-group">
                    <label for="submitted_by">Your Name</label>
                    <input type="text" id="submitted_by" name="submitted_by" placeholder="e.g. John Doe" required>
                </div>
            </div>

            <div id="form-alert" class="alert" style="display: none; margin-top: 1rem; margin-bottom: 0; padding: 0.75rem 1rem;"></div>

            <button type="submit" class="btn btn-primary" style="margin-top: 1rem; align-self: flex-start;">
                <i class="fa-solid fa-paper-plane"></i> Submit Quote
            </button>
        </form>
    </div>
</div>

<style>
#category-tabs .btn {
    border: 1px solid var(--color-border);
    background: var(--color-surface);
    color: var(--color-text-secondary);
    transition: all var(--transition-fast);
}
#category-tabs .btn:hover {
    color: var(--color-primary);
    border-color: var(--color-primary);
}
#category-tabs .btn.active-tab {
    background: var(--color-primary);
    color: #FFFFFF;
    border-color: var(--color-primary);
}
.view-toggle-btn.btn-secondary {
    background: var(--color-surface) !important;
    border: 1px solid var(--color-border) !important;
    color: var(--color-text-secondary) !important;
    transition: all var(--transition-fast);
}
.view-toggle-btn.btn-secondary:hover {
    border-color: var(--color-primary) !important;
    color: var(--color-primary) !important;
}
.view-toggle-btn.btn-primary {
    background: var(--color-primary) !important;
    color: #FFFFFF !important;
    border: 1px solid var(--color-primary) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let currentCategory = 'All';
    let allQuotesList = []; // Caches the full list for table filtering
    
    // Retrieve already viewed quotes from sessionStorage to persist across page reloads
    let viewedQuoteIds = JSON.parse(sessionStorage.getItem('viewed_quotes') || '{}');
    
    const displayText = document.getElementById('quote-display-text');
    const displayAuthor = document.getElementById('quote-display-author');
    const displaySubmitted = document.getElementById('quote-display-submitted');
    const submitterName = document.getElementById('submitter-name');
    const generateBtn = document.getElementById('generate-btn');
    const tabButtons = document.querySelectorAll('#category-tabs .btn');
    
    const viewGeneratorBtn = document.getElementById('view-generator-btn');
    const viewTableBtn = document.getElementById('view-table-btn');
    const generatorView = document.getElementById('generator-view');
    const tableView = document.getElementById('table-view');
    const tableBody = document.getElementById('table-body');
    const tableCount = document.getElementById('table-count');
    const tableSearch = document.getElementById('table-search');

    // 1. Switch View Logic
    viewGeneratorBtn.addEventListener('click', () => {
        viewGeneratorBtn.classList.remove('btn-secondary');
        viewGeneratorBtn.classList.add('btn-primary');
        
        viewTableBtn.classList.remove('btn-primary');
        viewTableBtn.classList.add('btn-secondary');
        
        generatorView.style.display = 'block';
        tableView.style.display = 'none';
    });

    viewTableBtn.addEventListener('click', () => {
        viewTableBtn.classList.remove('btn-primary');
        viewTableBtn.classList.add('btn-secondary');
        
        viewGeneratorBtn.classList.remove('btn-primary');
        viewGeneratorBtn.classList.add('btn-secondary');
        
        viewTableBtn.classList.remove('btn-secondary');
        viewTableBtn.classList.add('btn-primary');
        
        generatorView.style.display = 'none';
        tableView.style.display = 'block';
        loadAllQuotes();
    });
    
    // 2. Fetch a random quote
    async function fetchQuote() {
        generateBtn.disabled = true;
        displayText.textContent = '"Fetching quote..."';
        displayAuthor.textContent = '- Loading...';
        displaySubmitted.style.display = 'none';
        
        if (!viewedQuoteIds[currentCategory]) {
            viewedQuoteIds[currentCategory] = [];
        }
        
        const exclude = viewedQuoteIds[currentCategory].join(',');
        
        try {
            const response = await fetch(`<?= App::url('/api/quotes/generate') ?>?category=${currentCategory}&exclude=${exclude}`);
            const data = await response.json();
            
            if (data.success) {
                displayText.textContent = `"${data.quote.quote_text}"`;
                displayAuthor.textContent = `- ${data.quote.author}`;
                
                // Track this quote ID as viewed
                viewedQuoteIds[currentCategory].push(parseInt(data.quote.id));
                sessionStorage.setItem('viewed_quotes', JSON.stringify(viewedQuoteIds));
                
                // Show submitted by credit in small letters if user submitted
                if (parseInt(data.quote.is_user_submitted) === 1 && data.quote.submitted_by) {
                    submitterName.textContent = data.quote.submitted_by;
                    displaySubmitted.style.display = 'block';
                } else {
                    displaySubmitted.style.display = 'none';
                }
            } else {
                displayText.textContent = "You've read all the quotes in this category! How about submitting one of your favorites below?";
                displayAuthor.innerHTML = `
                    <a href="#quote-submit-form" style="font-size: 0.95rem; text-decoration: underline; margin-right: 0.5rem; color: var(--color-primary); font-weight: 600;">
                        <i class="fa-solid fa-feather-pointed"></i> Submit your quote
                    </a>
                    <span style="color: var(--color-text-secondary); opacity: 0.4;">|</span>
                    <a href="#" id="restart-category" style="font-size: 0.95rem; text-decoration: underline; margin-left: 0.5rem; color: var(--color-text-secondary); font-weight: 500;">
                        <i class="fa-solid fa-arrow-rotate-left"></i> Restart category
                    </a>
                `;
                displaySubmitted.style.display = 'none';
                
                // Setup restart handler
                const restartLink = document.getElementById('restart-category');
                if (restartLink) {
                    restartLink.addEventListener('click', (e) => {
                        e.preventDefault();
                        viewedQuoteIds[currentCategory] = [];
                        sessionStorage.setItem('viewed_quotes', JSON.stringify(viewedQuoteIds));
                        fetchQuote();
                    });
                }
            }
        } catch (error) {
            console.error('Error fetching quote:', error);
            displayText.textContent = '"An error occurred while fetching the quote."';
            displayAuthor.textContent = '';
        } finally {
            generateBtn.disabled = false;
        }
    }

    // 3. Load all approved quotes for the table view
    async function loadAllQuotes() {
        tableBody.innerHTML = '<tr><td colspan="4" style="padding: 2rem; text-align: center; color: var(--color-text-secondary);">Loading quotes...</td></tr>';
        
        try {
            const response = await fetch(`<?= App::url('/api/quotes/all') ?>`);
            const data = await response.json();
            
            if (data.success) {
                allQuotesList = data.quotes;
                filterTable();
            } else {
                tableBody.innerHTML = `<tr><td colspan="4" style="padding: 2rem; text-align: center; color: var(--color-danger);">${data.message}</td></tr>`;
            }
        } catch (error) {
            console.error('Error loading quotes:', error);
            tableBody.innerHTML = '<tr><td colspan="4" style="padding: 2rem; text-align: center; color: var(--color-danger);">An error occurred. Please try again.</td></tr>';
        }
    }

    // Render table rows
    function renderQuotesTable(quotes) {
        tableCount.textContent = quotes.length;
        if (quotes.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="4" style="padding: 2rem; text-align: center; color: var(--color-text-secondary);">No quotes found.</td></tr>';
            return;
        }
        
        let html = '';
        quotes.forEach(q => {
            const submitter = parseInt(q.is_user_submitted) === 1 ? `User: ${q.submitted_by}` : '<span style="opacity: 0.5;">System</span>';
            html += `
                <tr style="border-bottom: 1px solid var(--color-border); transition: background-color var(--transition-fast);">
                    <td style="padding: 1rem; font-style: italic; color: var(--color-text-primary);">"${q.quote_text}"</td>
                    <td style="padding: 1rem; font-weight: 500; white-space: nowrap;">${q.author}</td>
                    <td style="padding: 1rem; white-space: nowrap;">
                        <span style="display: inline-block; padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: 500; background: var(--color-background); border: 1px solid var(--color-border); border-radius: 4px;">
                            ${q.category}
                        </span>
                    </td>
                    <td style="padding: 1rem; color: var(--color-text-secondary); white-space: nowrap;">${submitter}</td>
                </tr>
            `;
        });
        tableBody.innerHTML = html;
    }

    // Filter table by category and search text
    function filterTable() {
        const query = tableSearch ? tableSearch.value.toLowerCase().trim() : '';
        let filtered = allQuotesList;

        // Apply category filter if active
        if (currentCategory !== 'All') {
            filtered = filtered.filter(q => q.category === currentCategory);
        }

        // Apply text query filter if active
        if (query) {
            filtered = filtered.filter(q => {
                const text = q.quote_text.toLowerCase();
                const author = q.author.toLowerCase();
                const submitter = (q.submitted_by || '').toLowerCase();
                const category = q.category.toLowerCase();
                
                return text.includes(query) || author.includes(query) || submitter.includes(query) || category.includes(query);
            });
        }

        renderQuotesTable(filtered);
    }

    if (tableSearch) {
        tableSearch.addEventListener('input', filterTable);
    }
    
    // Handle category filter tabs
    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            tabButtons.forEach(b => b.classList.remove('active-tab'));
            btn.classList.add('active-tab');
            currentCategory = btn.getAttribute('data-cat');
            
            if (generatorView.style.display !== 'none') {
                fetchQuote();
            } else {
                filterTable();
            }
        });
    });
    
    generateBtn.addEventListener('click', fetchQuote);
    
    // Handle form submission
    const form = document.getElementById('quote-submit-form');
    const formAlert = document.getElementById('form-alert');
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        formAlert.style.display = 'none';
        
        const formData = new FormData(form);
        
        try {
            const response = await fetch('<?= App::url("/api/quotes/add") ?>', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            if (data.success) {
                formAlert.className = 'alert alert-success';
                formAlert.innerHTML = `<i class="fa-solid fa-circle-check"></i> <div>${data.message}</div>`;
                formAlert.style.display = 'flex';
                form.reset();
                
                // Refresh table cache if they are in the table view
                if (tableView.style.display !== 'none') {
                    loadAllQuotes();
                }
            } else {
                formAlert.className = 'alert alert-danger';
                formAlert.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> <div>${data.message}</div>`;
                formAlert.style.display = 'flex';
            }
        } catch (error) {
            console.error('Error submitting quote:', error);
            formAlert.className = 'alert alert-danger';
            formAlert.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> <div>An error occurred. Please try again.</div>';
            formAlert.style.display = 'flex';
        }
    });
    
    // Initial fetch
    fetchQuote();
});
</script>
