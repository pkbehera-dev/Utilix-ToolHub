<?php
use App\Config\App;
use App\Core\Security;

// Helper function for sort links
function sortLink($column, $currentSort, $currentOrder, $search) {
    $newOrder = ($currentSort === $column && $currentOrder === 'desc') ? 'asc' : 'desc';
    $url = App::adminUrl("/urls?sort={$column}&order={$newOrder}");
    if (!empty($search)) {
        $url .= "&search=" . urlencode($search);
    }
    
    $icon = '';
    if ($currentSort === $column) {
        $icon = $currentOrder === 'asc' ? '<i class="fa-solid fa-sort-up"></i>' : '<i class="fa-solid fa-sort-down"></i>';
    } else {
        $icon = '<i class="fa-solid fa-sort" style="color: #ccc;"></i>';
    }
    
    return "<a href=\"{$url}\" style=\"color: inherit; text-decoration: none;\">" . ucfirst(str_replace('_', ' ', $column)) . " {$icon}</a>";
}

$currentSort = $_GET['sort'] ?? 'created_at';
$currentOrder = $_GET['order'] ?? 'desc';
$searchQuery = $_GET['search'] ?? '';
?>

<div style="background: var(--card-bg); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color);">
    
    <!-- Top Controls: Search -->
    <div style="display: flex; justify-content: space-between; margin-bottom: 20px; align-items: center;">
        <form method="GET" action="<?= App::adminUrl('/urls') ?>" style="display: flex; gap: 10px;">
            <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search code, url..." style="padding: 8px; border-radius: var(--radius); border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
            <button type="submit" style="padding: 8px 15px; background: var(--primary-color); color: white; border: none; border-radius: var(--radius); cursor: pointer;">Search</button>
            <?php if (!empty($searchQuery)): ?>
                <a href="<?= App::adminUrl('/urls') ?>" style="padding: 8px 15px; background: #6b7280; color: white; text-decoration: none; border-radius: var(--radius);">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Data Table & Mass Action Form -->
    <form method="POST" action="<?= App::adminUrl('/urls/delete') ?>" id="massDeleteForm">
        <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken() ?>">
        
        <div style="margin-bottom: 15px;">
            <button type="submit" onclick="return confirm('Are you sure you want to delete selected URLs?');" style="padding: 8px 15px; background: #ef4444; color: white; border: none; border-radius: var(--radius); cursor: pointer;"><i class="fa-solid fa-trash"></i> Delete Selected</button>
        </div>

        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 10px;"><input type="checkbox" id="selectAll"></th>
                    <th style="padding: 10px;"><?= sortLink('id', $currentSort, $currentOrder, $searchQuery) ?></th>
                    <th style="padding: 10px;"><?= sortLink('short_code', $currentSort, $currentOrder, $searchQuery) ?></th>
                    <th style="padding: 10px;"><?= sortLink('long_url', $currentSort, $currentOrder, $searchQuery) ?></th>
                    <th style="padding: 10px;"><?= sortLink('clicks', $currentSort, $currentOrder, $searchQuery) ?></th>
                    <th style="padding: 10px;"><?= sortLink('created_at', $currentSort, $currentOrder, $searchQuery) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($urls)): ?>
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center; color: var(--text-muted);">No URLs found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($urls as $url): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 10px;">
                                <input type="checkbox" name="url_ids[]" value="<?= $url['id'] ?>" class="row-checkbox">
                            </td>
                            <td style="padding: 10px;"><?= htmlspecialchars($url['id']) ?></td>
                            <td style="padding: 10px;">
                                <a href="<?= App::url('/' . $url['short_code']) ?>" target="_blank" style="font-weight: bold;"><?= htmlspecialchars($url['short_code']) ?></a>
                                <?php if ($url['alias']): ?>
                                    <span style="font-size: 0.8rem; background: var(--bg-color); padding: 2px 6px; border-radius: 4px; margin-left: 5px;">Alias: <?= htmlspecialchars($url['alias']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 10px; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= htmlspecialchars($url['long_url']) ?>">
                                <a href="<?= htmlspecialchars($url['long_url']) ?>" target="_blank" style="color: var(--text-muted);"><?= htmlspecialchars($url['long_url']) ?></a>
                            </td>
                            <td style="padding: 10px;">
                                <span style="background: var(--bg-color); padding: 3px 8px; border-radius: 12px; font-size: 0.9rem;"><?= (int)$url['clicks'] ?></span>
                            </td>
                            <td style="padding: 10px; font-size: 0.9rem; color: var(--text-muted);">
                                <?= date('Y-m-d H:i', strtotime($url['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>

<script>
document.getElementById('selectAll').addEventListener('change', function(e) {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(cb => cb.checked = e.target.checked);
});
</script>
