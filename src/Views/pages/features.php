<?php
use App\Core\Security;
use App\Config\App;

// Handle flash messages
$successMessage = $_SESSION['success_message'] ?? null;
$errorMessage = $_SESSION['error_message'] ?? null;

unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

$csrfToken = Security::generateCsrfToken();
?>

<div class="features-container">
    <div class="features-header-section">
        <h1>Community Feature Requests</h1>
        <p>Suggest new tools, request enhancements, and star your favorites. We build what you upvote!</p>
    </div>

    <!-- Alert Messages -->
    <?php if ($successMessage): ?>
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <div><?= htmlspecialchars($successMessage) ?></div>
        </div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="alert alert-danger">
            <i class="fa-solid fa-circle-xmark"></i>
            <div><?= htmlspecialchars($errorMessage) ?></div>
        </div>
    <?php endif; ?>

    <div class="features-layout">
        <!-- Left Side: Request Form -->
        <div class="features-form-sidebar">
            <div class="premium-card">
                <h2><i class="fa-solid fa-lightbulb text-primary"></i> Submit Request</h2>
                <p class="text-sm text-muted" style="margin-bottom: 1.5rem;">Have an idea for a tool? Tell us who you are and describe what it does.</p>
                
                <form action="<?= App::url('/features/add') ?>" method="POST" class="features-submit-form">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    
                    <div class="form-group">
                        <label for="user_name">Your Name</label>
                        <input type="text" id="user_name" name="user_name" placeholder="e.g. John Doe" required maxlength="100">
                    </div>
                    
                    <div class="form-group">
                        <label for="details">Feature Details</label>
                        <textarea id="details" name="details" placeholder="Describe the tool, its inputs, outputs, and how it should work..." rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="fa-solid fa-paper-plane"></i> Submit Request
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Side: Requests List -->
        <div class="features-list-content">
            <!-- Active Requests -->
            <div class="features-list-header flex items-center justify-between">
                <h2>Active Requests (<?= count($activeRequests) ?>)</h2>
                <span class="text-xs text-muted"><i class="fa-solid fa-fire"></i> Sorted by stars</span>
            </div>

            <?php if (empty($activeRequests)): ?>
                <div class="empty-state-card text-center" style="margin-bottom: 2rem;">
                    <div class="empty-state-icon">
                        <i class="fa-regular fa-star"></i>
                    </div>
                    <h3>No active requests</h3>
                    <p class="text-muted">Suggest a feature on the left to get started!</p>
                </div>
            <?php else: ?>
                <div class="features-grid" style="margin-bottom: 3rem;">
                    <?php 
                    $rank = 1;
                    foreach ($activeRequests as $req): 
                        $isStarred = in_array($req['id'], $starredFeatures);
                    ?>
                        <div class="feature-request-card <?= $isStarred ? 'starred' : '' ?>" data-id="<?= $req['id'] ?>">
                            <!-- Ranking Indicator -->
                            <div class="feature-rank-badge">
                                #<?= $rank++ ?>
                            </div>

                            <!-- Card Main Info -->
                            <div class="feature-card-body">
                                <p class="feature-details"><?= nl2br(htmlspecialchars($req['details'])) ?></p>
                                <div class="feature-meta">
                                    <span class="feature-author">
                                        <i class="fa-regular fa-user"></i> Requested by <strong><?= htmlspecialchars($req['user_name']) ?></strong>
                                    </span>
                                    <span class="feature-date">
                                        <i class="fa-regular fa-calendar"></i> <?= date('M d, Y', strtotime($req['created_at'])) ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Star Upvote Section -->
                            <div class="feature-star-action">
                                <button 
                                    type="button" 
                                    class="star-button <?= $isStarred ? 'active' : '' ?>" 
                                    data-id="<?= $req['id'] ?>"
                                    <?= $isStarred ? 'disabled' : '' ?>
                                    aria-label="Star this request"
                                >
                                    <i class="<?= $isStarred ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
                                    <span class="star-count"><?= $req['stars'] ?></span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Already Solved/Implemented Requests -->
            <?php if (!empty($solvedRequests)): ?>
                <div class="features-list-header flex items-center justify-between" style="margin-top: 1.5rem;">
                    <h2>Already Solved (<?= count($solvedRequests) ?>)</h2>
                    <span class="text-xs text-muted" style="color: var(--color-success) !important;"><i class="fa-solid fa-circle-check"></i> Built & Live</span>
                </div>

                <div class="features-grid">
                    <?php foreach ($solvedRequests as $req): ?>
                        <div class="feature-request-card solved-card" style="border-left: 4px solid var(--color-success); opacity: 0.85;">
                            <!-- Checkmark Badge -->
                            <div class="feature-rank-badge" style="background: rgba(16, 185, 129, 0.1); color: var(--color-success); border-color: rgba(16, 185, 129, 0.2);">
                                <i class="fa-solid fa-check"></i>
                            </div>

                            <!-- Card Main Info -->
                            <div class="feature-card-body">
                                <p class="feature-details" style="text-decoration: line-through; color: var(--color-text-secondary);"><?= nl2br(htmlspecialchars($req['details'])) ?></p>
                                <div class="feature-meta">
                                    <span class="feature-author">
                                        <i class="fa-regular fa-user"></i> Requested by <strong><?= htmlspecialchars($req['user_name']) ?></strong>
                                    </span>
                                    <span class="feature-date">
                                        <i class="fa-regular fa-calendar"></i> <?= date('M d, Y', strtotime($req['created_at'])) ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Star Count Display Only -->
                            <div class="feature-star-action">
                                <div class="star-button active" style="background-color: rgba(16, 185, 129, 0.08); border-color: var(--color-success); color: var(--color-success); cursor: default;">
                                    <i class="fa-solid fa-star"></i>
                                    <span class="star-count"><?= $req['stars'] ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- AJAX Star upvote logic -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const starButtons = document.querySelectorAll('.star-button:not([disabled])');
    
    starButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            e.preventDefault();
            const id = button.getAttribute('data-id');
            const card = button.closest('.feature-request-card');
            
            // Disable immediately to prevent multiple rapid clicks
            button.disabled = true;
            
            try {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('csrf_token', '<?= $csrfToken ?>');
                
                const response = await fetch('<?= App::url("/features/star") ?>', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update star count
                    const countSpan = button.querySelector('.star-count');
                    if (countSpan) {
                        countSpan.textContent = data.stars;
                    }
                    
                    // Toggle active classes
                    button.classList.add('active');
                    const icon = button.querySelector('i');
                    if (icon) {
                        icon.className = 'fa-solid fa-star';
                    }
                    
                    if (card) {
                        card.classList.add('starred');
                    }
                    
                    // Success trigger micro-animation
                    button.style.transform = 'scale(1.2) rotate(15deg)';
                    setTimeout(() => {
                        button.style.transform = '';
                    }, 300);
                } else {
                    alert(data.message || 'Failed to star request.');
                    button.disabled = false;
                }
            } catch (error) {
                console.error('Error upvoting feature:', error);
                alert('An error occurred. Please try again.');
                button.disabled = false;
            }
        });
    });
});
</script>
