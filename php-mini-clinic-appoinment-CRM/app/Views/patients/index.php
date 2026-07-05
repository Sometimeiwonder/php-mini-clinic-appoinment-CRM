<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h1 style="margin:0;"><?= e($title) ?></h1>
    <a class="btn primary" href="/patients/create">+ Create Patient</a>
</div>

<form method="GET" action="/patients" class="toolbar">
    <label style="font-weight:700;">Search</label>
    <input name="q" value="<?= e($keyword ?? '') ?>" placeholder="Tìm theo tên, email, SĐT" style="flex:1;">
    <span style="color:#6b7280;">Sort: <?= e($sort ?? 'created_at') ?> <?= e(strtoupper($direction ?? 'DESC')) ?> | safe whitelist</span>
    <button type="submit" class="btn primary">Filter</button>
</form>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Gender</th>
    <th>Created at</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($patients as $patient): ?>
<tr>
    <td><?= e((string)$patient['id']) ?></td>
    <td><?= e($patient['name']) ?></td>
    <td><?= e($patient['email']) ?></td>
    <td><?= e($patient['phone'] ?? '') ?></td>
    <td><span class="badge"><?= e($patient['gender']) ?></span></td>
    <td><?= e($patient['created_at']) ?></td>
    <td>
        <a href="/patients/edit?id=<?= e((string)$patient['id']) ?>">Edit</a>
        <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
        <form method="post" action="/patients/delete" class="inline" onsubmit="return confirm('Xóa bệnh nhân này?')">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e((string)$patient['id']) ?>">
            <button type="submit" class="link danger">Delete</button>
        </form>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
<?php if (empty($patients)): ?>
<tr><td colspan="7">Không tìm thấy bệnh nhân nào.</td></tr>
<?php endif; ?>
</tbody>
</table>

<div class="table-footer">
    <span>Showing <?= e((string)min((($page-1)*10)+1, $totalItems)) ?>-<?= e((string)min($page*10, $totalItems)) ?> of <?= e((string)$totalItems) ?> patients</span>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a class="page-link" href="/patients?page=<?= e((string)($page - 1)) ?>&q=<?= e($keyword ?? '') ?>&sort=<?= e($sort ?? 'created_at') ?>&direction=<?= e($direction ?? 'desc') ?>">Prev</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="page-link <?= $i === $page ? 'active' : '' ?>" href="/patients?page=<?= e((string)$i) ?>&q=<?= e($keyword ?? '') ?>&sort=<?= e($sort ?? 'created_at') ?>&direction=<?= e($direction ?? 'desc') ?>"><?= e((string)$i) ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <a class="page-link" href="/patients?page=<?= e((string)($page + 1)) ?>&q=<?= e($keyword ?? '') ?>&sort=<?= e($sort ?? 'created_at') ?>&direction=<?= e($direction ?? 'desc') ?>">Next</a>
        <?php endif; ?>
    </div>
</div>
