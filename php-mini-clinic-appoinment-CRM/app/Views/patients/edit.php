<h1>Edit Patient</h1>
<p style="color:#6b7280;">GET lấy dữ liệu hiện tại theo id, POST /patients/update validate rồi redirect về danh sách.</p>

<div class="card form-card" style="max-width:720px;">
    <h3 style="margin-top:0;">Patient #<?= e((string)($old['id'] ?? '')) ?></h3>

    <form method="post" action="/patients/update">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= e((string)($old['id'] ?? '')) ?>">

        <label>Name</label>
        <input type="text" name="name" value="<?= e($old['name'] ?? '') ?>">
        <?php if (!empty($errors['name'])): ?><p class="error"><?= e($errors['name']) ?></p><?php endif; ?>

        <label>Email</label>
        <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>">
        <?php if (!empty($errors['email'])): ?><p class="error"><?= e($errors['email']) ?></p><?php endif; ?>

        <label>Phone</label>
        <input type="text" name="phone" value="<?= e($old['phone'] ?? '') ?>">

        <label>Gender</label>
        <select name="gender">
            <?php foreach (['male','female','other'] as $g): ?>
                <option value="<?= e($g) ?>" <?= ($old['gender'] ?? 'male') === $g ? 'selected' : '' ?>><?= e($g) ?></option>
            <?php endforeach; ?>
        </select>

        <div style="display:flex; gap:12px; margin-top:16px;">
            <button class="btn primary" type="submit">Update</button>
            <a class="btn danger" href="/patients" onclick="return confirm('Xóa bệnh nhân này?')">Delete</a>
        </div>
    </form>
</div>
