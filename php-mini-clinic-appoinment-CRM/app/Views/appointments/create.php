<h1><?= e($title) ?></h1>
<p style="color:#6b7280;">POST /appointments/store - appointment_code không được trùng, validate server-side, PRG after success.</p>

<div style="display:flex; gap:32px; align-items:flex-start;">
<div style="flex:1;">
<form method="post" action="/appointments/store" class="card form-card" style="position:relative;">
    <?= csrf_field() ?>

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

    <div style="position:absolute; left:-9999px;" aria-hidden="true">
        <input type="text" name="website" tabindex="-1" autocomplete="off" value="">
    </div>

    <button class="btn primary" type="submit">Save Appointment</button>
    <a class="btn" href="/appointments">Back</a>
</form>
</div>

<div class="info-box">
    <h3>Appointment rules</h3>
    <ul style="margin:0; padding-left:20px; line-height:2;">
        <li>&#x2705; appointment_code required + unique</li>
        <li>&#x2705; patient_name required</li>
        <li>&#x2705; patient_email format if entered</li>
        <li>&#x2705; appointment_date required</li>
        <li>&#x2705; status in whitelist</li>
        <li>&#x2705; PRG after success</li>
    </ul>
</div>
</div>
