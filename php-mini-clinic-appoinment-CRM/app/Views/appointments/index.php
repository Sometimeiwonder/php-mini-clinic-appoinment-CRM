<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h1 style="margin:0;"><?= e($title) ?></h1>
    <a class="btn primary" href="/appointments/create">+ Create Appointment</a>
</div>

<form method="GET" action="/appointments" class="toolbar">
    <label style="font-weight:700;">Search</label>
    <input name="q" value="<?= e($keyword ?? '') ?>" placeholder="Tìm mã lịch hẹn, tên, email" style="flex:1;">
    <select name="status">
        <option value="">All Status</option>
        <?php foreach (['pending','confirmed','completed','cancelled'] as $s): ?>
            <option value="<?= e($s) ?>" <?= ($status ?? '') === $s ? 'selected' : '' ?>><?= e(ucfirst($s)) ?></option>
        <?php endforeach; ?>
    </select>
    <label>Từ:</label>
    <input type="date" name="date_from" value="<?= e($dateFrom ?? '') ?>">
    <label>Đến:</label>
    <input type="date" name="date_to" value="<?= e($dateTo ?? '') ?>">
    <button type="submit" class="btn primary">Filter</button>
</form>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Code</th>
    <th>Patient Name</th>
    <th>Email</th>
    <th>Date</th>
    <th>Status</th>
    <th>Created at</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($appointments as $apt): ?>
<tr>
    <td><?= e((string)$apt['id']) ?></td>
    <td><?= e($apt['appointment_code']) ?></td>
    <td><?= e($apt['patient_name']) ?></td>
    <td><?= e($apt['patient_email'] ?? '') ?></td>
    <td><?= e($apt['appointment_date']) ?></td>
    <td><span class="badge status-<?= e($apt['status']) ?>"><?= e($apt['status']) ?></span></td>
    <td><?= e($apt['created_at']) ?></td>
    <td>
        <a href="/appointments/edit?id=<?= e((string)$apt['id']) ?>">Edit</a>
        <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
        <form method="post" action="/appointments/delete" class="inline" onsubmit="return confirm('Xóa lịch hẹn này?')">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e((string)$apt['id']) ?>">
            <button type="submit" class="link danger">Delete</button>
        </form>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
<?php if (empty($appointments)): ?>
<tr><td colspan="8">Không tìm thấy lịch hẹn nào.</td></tr>
<?php endif; ?>
</tbody>
</table>

<div class="table-footer">
    <span>Showing <?= e((string)min((($page-1)*10)+1, $totalItems)) ?>-<?= e((string)min($page*10, $totalItems)) ?> of <?= e((string)$totalItems) ?> appointments</span>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a class="page-link" href="/appointments?page=<?= e((string)($page - 1)) ?>&q=<?= e($keyword ?? '') ?>&status=<?= e($status ?? '') ?>&date_from=<?= e($dateFrom ?? '') ?>&date_to=<?= e($dateTo ?? '') ?>&sort=<?= e($sort ?? 'created_at') ?>&direction=<?= e($direction ?? 'desc') ?>">Prev</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="page-link <?= $i === $page ? 'active' : '' ?>" href="/appointments?page=<?= e((string)$i) ?>&q=<?= e($keyword ?? '') ?>&status=<?= e($status ?? '') ?>&date_from=<?= e($dateFrom ?? '') ?>&date_to=<?= e($dateTo ?? '') ?>&sort=<?= e($sort ?? 'created_at') ?>&direction=<?= e($direction ?? 'desc') ?>"><?= e((string)$i) ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <a class="page-link" href="/appointments?page=<?= e((string)($page + 1)) ?>&q=<?= e($keyword ?? '') ?>&status=<?= e($status ?? '') ?>&date_from=<?= e($dateFrom ?? '') ?>&date_to=<?= e($dateTo ?? '') ?>&sort=<?= e($sort ?? 'created_at') ?>&direction=<?= e($direction ?? 'desc') ?>">Next</a>
        <?php endif; ?>
    </div>
</div>
