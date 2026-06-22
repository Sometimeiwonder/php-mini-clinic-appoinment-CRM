<?php ob_start(); ?>
<h1>Appointment Management</h1>
<a class="btn primary" href="/appointments/create">+ Create Appointment</a>

<form method="get" action="/appointments" class="toolbar">
    <input type="hidden" name="page" value="1">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search code/name/email">
    <input type="hidden" name="sort" value="<?= e($sort) ?>">
    <input type="hidden" name="direction" value="<?= e($direction) ?>">
    <button type="submit">Search</button>
</form>

<table>
<thead>
<tr>
    <th>ID</th>
    <th><a href="/appointments?<?= e(query_string(['sort' => 'appointment_code'])) ?>">Code</a></th>
    <th>Patient Name</th>
    <th>Patient Email</th>
    <th><a href="/appointments?<?= e(query_string(['sort' => 'appointment_date'])) ?>">Date</a></th>
    <th>Status</th>
    <th><a href="/appointments?<?= e(query_string(['sort' => 'created_at'])) ?>">Created at</a></th>
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
