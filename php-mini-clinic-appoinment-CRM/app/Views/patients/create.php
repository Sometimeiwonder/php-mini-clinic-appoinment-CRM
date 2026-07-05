<h1><?= e($title) ?></h1>
<p style="color:#6b7280;">POST /patients/store - email không được trùng, validate server-side, honeypot/rate limit.</p>

<div style="display:flex; gap:32px; align-items:flex-start;">
<div style="flex:1;">
<form method="post" action="/patients/store" class="card form-card" style="position:relative;">
    <?= csrf_field() ?>

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

    <div style="position:absolute; left:-9999px;" aria-hidden="true">
        <input type="text" name="website" tabindex="-1" autocomplete="off" value="">
    </div>

    <button class="btn primary" type="submit">Save Patient</button>
    <a class="btn" href="/patients">Back</a>
</form>
</div>

<div class="info-box">
    <h3>Secure flow</h3>
    <ol style="margin:0; padding-left:20px; line-height:2;">
        <li>Read POST safely</li>
        <li>Server-side validation</li>
        <li>Honeypot/rate limit</li>
        <li>Prepared INSERT</li>
        <li>DuplicateRecordException</li>
        <li>Render friendly error</li>
    </ol>
</div>
</div>
