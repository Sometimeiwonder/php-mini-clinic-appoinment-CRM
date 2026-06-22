<?php ob_start(); ?>
<h1>Patient Management</h1>
<a class="btn primary" href="/patients/create">+ Create Patient</a>

<form method="get" action="/patients" class="toolbar">
    <input type="hidden" name="page" value="1">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search name/email/phone">
    <input type="hidden" name="sort" value="<?= e($sort) ?>">
    <input type="hidden" name="direction" value="<?= e($direction) ?>">
    <button type="submit">Search</button>
</form>

<?php
function patient_sort_link($label, $col, $currentSort, $currentDir, $q) {
    $newDir = ($currentSort === $col && $currentDir === 'asc') ? 'desc' : 'asc';
    $arrow = '';
    if ($currentSort === $col) {
        $arrow = $currentDir === 'asc' ? ' &#9650;' : ' &#9660;';
    }
    $params = ['sort' => $col, 'direction' => $newDir, 'page' => 1];
    if ($q !== '') $params['q'] = $q;
    return '<a href="/patients?' . e(query_string($params)) . '">' . $label . $arrow . '</a>';
}
?>

<table>
<thead>
<tr>
    <th>ID</th>
    <th><?= patient_sort_link('Name', 'name', $sort, $direction, $q) ?></th>
    <th><?= patient_sort_link('Email', 'email', $sort, $direction, $q) ?></th>
    <th><?= patient_sort_link('Phone', 'phone', $sort, $direction, $q) ?></th>
    <th><?= patient_sort_link('Gender', 'gender', $sort, $direction, $q) ?></th>
    <th><?= patient_sort_link('Created at', 'created_at', $sort, $direction, $q) ?></th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($patients as $patient): ?>
<tr>
    <td><?= e($patient['id']) ?></td>
    <td><?= e($patient['name']) ?></td>
    <td><?= e($patient['email']) ?></td>
    <td><?= e($patient['phone']) ?></td>
    <td><span class="badge"><?= e($patient['gender']) ?></span></td>
    <td><?= e($patient['created_at']) ?></td>
    <td>
        <a href="/patients/edit?id=<?= e($patient['id']) ?>">Edit</a>
        <form method="post" action="/patients/delete" class="inline" onsubmit="return confirm('Delete this patient?')">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e($patient['id']) ?>">
            <button type="submit" class="link danger">Delete</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<?php if (empty($patients)): ?>
<tr><td colspan="7">No patients found.</td></tr>
<?php endif; ?>
</tbody>
</table>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="/patients?<?= e(query_string(['page' => $page - 1])) ?>">Prev</a>
    <?php endif; ?>
    <span>Page <?= e($page) ?> / <?= e($totalPages) ?></span>
    <?php if ($page < $totalPages): ?>
        <a href="/patients?<?= e(query_string(['page' => $page + 1])) ?>">Next</a>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$title = 'Patient Management';
require __DIR__ . '/../layout.php';
