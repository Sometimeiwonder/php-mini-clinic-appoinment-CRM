<h1>Edit Appointment</h1>
<p style="color:#6b7280;">GET lấy dữ liệu hiện tại theo id, POST /appointments/update validate rồi redirect về danh sách.</p>

<div class="card form-card" style="max-width:720px;">
    <h3 style="margin-top:0;">Appointment #<?= e((string)($old['id'] ?? '')) ?></h3>

    <form method="post" action="/appointments/update">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= e((string)($old['id'] ?? '')) ?>">

        <label>Appointment code</label>
        <input type="text" name="appointment_code" value="<?= e($old['appointment_code'] ?? '') ?>">
        <?php if (!empty($errors['appointment_code'])): ?><p class="error"><?= e($errors['appointment_code']) ?></p><?php endif; ?>

        <label>Patient name</label>
        <input type="text" name="patient_name" value="<?= e($old['patient_name'] ?? '') ?>">
        <?php if (!empty($errors['patient_name'])): ?><p class="error"><?= e($errors['patient_name']) ?></p><?php endif; ?>

        <label>Patient email</label>
        <input type="email" name="patient_email" value="<?= e($old['patient_email'] ?? '') ?>">
        <?php if (!empty($errors['patient_email'])): ?><p class="error"><?= e($errors['patient_email']) ?></p><?php endif; ?>

        <label>Appointment date</label>
        <input type="date" name="appointment_date" value="<?= e($old['appointment_date'] ?? '') ?>">
        <?php if (!empty($errors['appointment_date'])): ?><p class="error"><?= e($errors['appointment_date']) ?></p><?php endif; ?>

        <label>Status</label>
        <select name="status">
            <?php foreach (['pending','confirmed','completed','cancelled'] as $s): ?>
                <option value="<?= e($s) ?>" <?= ($old['status'] ?? 'pending') === $s ? 'selected' : '' ?>><?= e($s) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Note</label>
        <textarea name="note"><?= e($old['note'] ?? '') ?></textarea>

        <div style="display:flex; gap:12px; margin-top:16px;">
            <button class="btn primary" type="submit">Update</button>
            <a class="btn danger" href="/appointments" onclick="return confirm('Xóa lịch hẹn này?')">Delete</a>
        </div>
    </form>
</div>
