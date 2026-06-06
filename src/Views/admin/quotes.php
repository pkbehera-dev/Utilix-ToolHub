<?php
/**
 * Admin Quotes Management View
 */
use App\Core\Security;
use App\Config\App;

$csrfToken = Security::generateCsrfToken();
$currentCat = $_GET['category'] ?? 'All';
?>
<div class="card" style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 1.5rem; margin-top: 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.25rem;">Manage Quotes</h3>
            <p class="text-muted" style="font-size: 0.875rem;">Approve user-submitted quotes or perform mass operations.</p>
        </div>
        
        <!-- Category Filter Tabs -->
        <div style="display: flex; gap: 0.25rem; flex-wrap: wrap; background: var(--color-background); padding: 0.25rem; border-radius: var(--radius-md); border: 1px solid var(--color-border);">
            <?php 
            $cats = ['All', 'Motivation', 'Life', 'Technology', 'Inspirational', 'Humor'];
            foreach ($cats as $cat): 
                $isActive = ($currentCat === $cat);
            ?>
                <a href="<?= App::adminUrl('/quotes?category=' . urlencode($cat)) ?>" 
                   style="padding: 0.35rem 0.75rem; font-size: 0.8rem; font-weight: 500; border-radius: 4px; text-decoration: none; display: inline-block; transition: all var(--transition-fast);
                          <?= $isActive ? 'background: var(--color-primary); color: white;' : 'color: var(--color-text-secondary);' ?>">
                    <?= htmlspecialchars($cat) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Mass Actions Bar -->
    <div id="mass-action-bar" style="display: none; align-items: center; justify-content: space-between; background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.15); border-radius: var(--radius-md); padding: 0.75rem 1rem; margin-bottom: 1.5rem;">
        <span style="font-size: 0.9rem; font-weight: 500; color: var(--color-danger);">
            <i class="fa-solid fa-square-check"></i> <span id="selected-count">0</span> quotes selected
        </span>
        <button type="button" id="mass-delete-btn" class="btn" style="background: var(--color-danger); color: white; padding: 0.35rem 1rem; font-size: 0.8rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 0.25rem;">
            <i class="fa-solid fa-trash"></i> Delete Selected
        </button>
    </div>

    <form id="quotes-form" action="<?= App::adminUrl('/quotes/delete?category=' . urlencode($currentCat)) ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-text-secondary); font-weight: 600;">
                        <th style="padding: 0.75rem 1rem; width: 40px; text-align: center;">
                            <input type="checkbox" id="select-all" style="transform: scale(1.1); cursor: pointer;">
                        </th>
                        <th style="padding: 0.75rem 1rem;">Quote</th>
                        <th style="padding: 0.75rem 1rem;">Author</th>
                        <th style="padding: 0.75rem 1rem;">Submitted By</th>
                        <th style="padding: 0.75rem 1rem;">Category</th>
                        <th style="padding: 0.75rem 1rem;">Status</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($quotes)): ?>
                        <tr>
                            <td colspan="7" style="padding: 2rem; text-align: center; color: var(--color-text-secondary);">No quotes found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($quotes as $q): ?>
                            <tr style="border-bottom: 1px solid var(--color-border); transition: background-color var(--transition-fast);">
                                <td style="padding: 1rem; text-align: center;">
                                    <input type="checkbox" name="quote_ids[]" value="<?= $q['id'] ?>" class="quote-checkbox" style="transform: scale(1.1); cursor: pointer;">
                                </td>
                                <td style="padding: 1rem; font-style: italic; color: var(--color-text-primary); max-width: 300px; word-break: break-word;">
                                    "<?= htmlspecialchars($q['quote_text']) ?>"
                                </td>
                                <td style="padding: 1rem; font-weight: 500; white-space: nowrap;"><?= htmlspecialchars($q['author']) ?></td>
                                <td style="padding: 1rem; color: var(--color-text-secondary);">
                                    <?php if ($q['is_user_submitted']): ?>
                                        <span class="text-sm">User: <strong><?= htmlspecialchars($q['submitted_by'] ?? 'Anonymous') ?></strong></span>
                                    <?php else: ?>
                                        <span style="font-size: 0.75rem; color: var(--color-text-secondary); opacity: 0.6;">System Seed</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: 500; background: var(--color-background); border: 1px solid var(--color-border); border-radius: 4px;">
                                        <?= htmlspecialchars($q['category']) ?>
                                    </span>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php if ($q['is_approved']): ?>
                                        <span style="display: inline-block; padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: 600; color: var(--color-success); background: color-mix(in srgb, var(--color-success) 10%, transparent); border-radius: var(--radius-full);">Approved</span>
                                    <?php else: ?>
                                        <span style="display: inline-block; padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: 600; color: var(--color-warning); background: color-mix(in srgb, var(--color-warning) 10%, transparent); border-radius: var(--radius-full);">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem; text-align: right; white-space: nowrap;">
                                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem; align-items: center;">
                                        <!-- Toggle Approve Button -->
                                        <button type="submit" 
                                                formaction="<?= App::adminUrl('/quotes/approve?category=' . urlencode($currentCat)) ?>" 
                                                name="id" 
                                                value="<?= $q['id'] ?>"
                                                class="btn"
                                                style="height: 30px; padding: 0 0.75rem; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.25rem;
                                                       <?= $q['is_approved'] ? 'background: var(--color-background); border: 1px solid var(--color-border); color: var(--color-text-secondary);' 
                                                                             : 'background: var(--color-success); color: white;' ?>">
                                            <input type="hidden" name="approve" value="<?= $q['is_approved'] ? 0 : 1 ?>" class="approve-val">
                                            <?php if ($q['is_approved']): ?>
                                                <i class="fa-solid fa-xmark"></i> Unapprove
                                            <?php else: ?>
                                                <i class="fa-solid fa-check"></i> Approve
                                            <?php endif; ?>
                                        </button>
                                        
                                        <!-- Single Delete Button -->
                                        <button type="submit" 
                                                formaction="<?= App::adminUrl('/quotes/delete?category=' . urlencode($currentCat)) ?>" 
                                                name="id" 
                                                value="<?= $q['id'] ?>"
                                                class="btn btn-secondary" 
                                                style="height: 30px; padding: 0 0.75rem; font-size: 0.8rem; border-color: var(--color-danger); color: var(--color-danger);"
                                                onclick="return confirm('Are you sure you want to delete this quote?');">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.quote-checkbox');
    const massBar = document.getElementById('mass-action-bar');
    const selectedCount = document.getElementById('selected-count');
    const massDeleteBtn = document.getElementById('mass-delete-btn');
    const form = document.getElementById('quotes-form');

    // Update approve value dynamically before button submission
    document.querySelectorAll('button[name="id"]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const approveValInput = btn.querySelector('.approve-val');
            if (approveValInput) {
                // Create a temporary hidden input to submit the 'approve' status alongside 'id'
                const hiddenApprove = document.createElement('input');
                hiddenApprove.type = 'hidden';
                hiddenApprove.name = 'approve';
                hiddenApprove.value = approveValInput.value;
                form.appendChild(hiddenApprove);
            }
        });
    });

    function updateMassActionBar() {
        const checkedCount = document.querySelectorAll('.quote-checkbox:checked').length;
        if (checkedCount > 0) {
            massBar.style.display = 'flex';
            selectedCount.textContent = checkedCount;
        } else {
            massBar.style.display = 'none';
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', () => {
            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateMassActionBar();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            if (!cb.checked) {
                selectAll.checked = false;
            } else if (document.querySelectorAll('.quote-checkbox:checked').length === checkboxes.length) {
                selectAll.checked = true;
            }
            updateMassActionBar();
        });
    });

    if (massDeleteBtn) {
        massDeleteBtn.addEventListener('click', () => {
            if (confirm('Are you sure you want to delete the selected quotes?')) {
                form.action = '<?= App::adminUrl("/quotes/delete?category=" . urlencode($currentCat)) ?>';
                form.submit();
            }
        });
    }
});
</script>
