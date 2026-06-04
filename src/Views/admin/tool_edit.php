<?php
/**
 * Admin Edit Tool View
 */
use App\Config\App;
use App\Core\Security;
?>

<div class="card" style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 1.5rem; margin-top: 1rem; max-width: 600px;">
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.25rem;">Edit Tool</h3>
            <p class="text-muted" style="font-size: 0.875rem;">Modify metadata for <?= htmlspecialchars($tool['name']) ?></p>
        </div>
        <a href="<?= App::adminUrl('/tools') ?>" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.4rem 0.75rem;">Back</a>
    </div>

    <form action="<?= App::adminUrl('/tools/update/' . $tool['id']) ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken() ?>">
        
        <div class="form-group">
            <label class="form-label" for="name">Tool Name</label>
            <input type="text" id="name" name="name" class="form-input" value="<?= htmlspecialchars($tool['name']) ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="slug">URL Slug</label>
            <input type="text" id="slug" name="slug" class="form-input" value="<?= htmlspecialchars($tool['slug']) ?>" required>
            <div style="font-size: 0.75rem; color: var(--color-text-secondary); margin-top: 0.25rem;">This must match the filename in your views/pages/tools folder.</div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">SEO Description</label>
            <textarea id="description" name="description" class="form-input" rows="3"><?= htmlspecialchars($tool['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label" for="icon">FontAwesome Icon Class</label>
            <input type="text" id="icon" name="icon" class="form-input" value="<?= htmlspecialchars($tool['icon'] ?? '') ?>" placeholder="e.g. fa-key">
        </div>

        <div class="form-group">
            <label class="form-label" for="category_id">Category</label>
            <select id="category_id" name="category_id" class="form-input" required>
                <option value="">Select a Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $tool['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
            <input type="checkbox" id="is_active" name="is_active" <?= $tool['is_active'] ? 'checked' : '' ?>>
            <label for="is_active" style="margin: 0; cursor: pointer; font-weight: 500;">Active (Visible on site)</label>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Save Changes</button>
    </form>
</div>
