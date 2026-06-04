<?php
/**
 * Admin Edit Category View
 */
use App\Config\App;
use App\Core\Security;
?>

<div class="card" style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 1.5rem; margin-top: 1rem; max-width: 600px;">
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.25rem;">Edit Category</h3>
            <p class="text-muted" style="font-size: 0.875rem;">Modify metadata for <?= htmlspecialchars($category['name']) ?></p>
        </div>
        <a href="<?= App::adminUrl('/categories') ?>" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.4rem 0.75rem;">Back</a>
    </div>

    <form action="<?= App::adminUrl('/categories/update/' . $category['id']) ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken() ?>">
        
        <div class="form-group">
            <label class="form-label" for="name">Category Name</label>
            <input type="text" id="name" name="name" class="form-input" value="<?= htmlspecialchars($category['name']) ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="slug">URL Slug</label>
            <input type="text" id="slug" name="slug" class="form-input" value="<?= htmlspecialchars($category['slug']) ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="icon">FontAwesome Icon Class</label>
            <input type="text" id="icon" name="icon" class="form-input" value="<?= htmlspecialchars($category['icon'] ?? '') ?>" placeholder="e.g. fa-folder">
        </div>

        <div class="form-group" style="margin-bottom: 2rem;">
            <label class="form-label" for="sort_order">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" class="form-input" value="<?= (int)$category['sort_order'] ?>" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Save Changes</button>
    </form>
</div>
