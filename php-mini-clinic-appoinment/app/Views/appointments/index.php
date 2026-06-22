<?php ob_start(); ?>
<h1>Appointment Management</h1>
<a class="btn primary" href="/appointments/create">+ Create Appointment</a>

<form method="get" action="/appointments" class="toolbar">
    <input type="hidden" name="page" value="1">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search code/name/email">
    <select name="status">
        <option value="">All Status</option>
        <?php foreach (['pending','confirmed','completed','cancelled'] as $s): ?>
            <option value="<?= e($s) ?>" <?= ($status ?? '') === $s ? 'selected' : '' ?>><?= e(ucfirst($s)) ?></option>
        <?php endforeach; ?>
    </select>
    <input type="hidden" name="sort" value="<?= e($sort) ?>">
    <input type="hidden" name="direction" value="<?= e($direction) ?>">
    <button type="submit">Search</button>
</form>

<?php
function sort_link($label, $col, $currentSort, $currentDir, $status, $q) {
    $newDir = ($currentSort === $col && $currentDir === 'asc') ? 'desc' : 'asc';
    $arrow = '';
    if ($currentSort === $col) {
        $arrow = $currentDir === 'asc' ? ' &#9650;' : ' &#9660;';
    }
    $params = ['sort' => $col, 'direction' => $newDir, 'page' => 1];
    if ($q !== '') $params['q'] = $q;
    if ($status !== '') $params['status'] = $status;
    return '<a href="/appointments?' . e(query_string($params)) . '">' . $label . $arrow . '</a>';
}
?>

<table>
<thead>
<tr>
    <th>ID</th>
    <th><?= sort_link('Code', 'appointment_code', $sort, $direction, $status ?? '', $q) ?></th>
    <th><?= sort_link('Patient Name', 'patient_name', $sort, $direction, $status ?? '', $q) ?></th>
    <th>Patient Email</th>
    <th><?= sort_link('Date', 'appointment_date', $sort, $direction, $status ?? '', $q) ?></th>
    <th><?= sort_link('Status', 'status', $sort, $direction, $status ?? '', $q) ?></th>
    <th><?= sort_link('Created at', 'created_at', $sort, $direction, $status ?? '', $q) ?></th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($appointments as $apt): ?>
<tr>
    <td><?= e($apt['id']) ?></td>
    <td><?= e($apt['appointment_code']) ?></td>
    <td><?= e($apt['patient_name']) ?></td>
    <td><?= e($apt['patient_email']) ?></td>
    <td><?= e($apt['appointment_date']) ?></td>
    <td><span class="badge status-<?= e($apt['status']) ?>"><?= e($apt['status']) ?></span></td>
    <td><?= e($apt['created_at']) ?></td>
    <td>
        <a href="/appointments/edit?id=<?= e($apt['id']) ?>">Edit</a>
        <form method="post" action="/appointments/delete" class="inline" onsubmit="return confirm('Delete this appointment?')">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e($apt['id']) ?>">
            <button type="submit" class="link danger">Delete</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<?php if (empty($appointments)): ?>
<tr><td colspan="8">No appointments found.</td></tr>
<?php endif; ?>
</tbody>
</table>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="/appointments?<?= e(query_string(['page' => $page - 1])) ?>">Prev</a>
    <?php endif; ?>
    <span>Page <?= e($page) ?> / <?= e($totalPages) ?></span>
    <?php if ($page < $totalPages): ?>
        <a href="/appointments?<?= e(query_string(['page' => $page + 1])) ?>">Next</a>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$title = 'Appointment Management';
require __DIR__ . '/../layout.php';
