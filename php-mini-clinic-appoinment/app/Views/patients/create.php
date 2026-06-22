<?php ob_start(); ?>
<h1>Create Patient</h1>

<form method="post" action="/patients/store" class="card form-card">
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
        <?php foreach (['male','female','other'] as $gender): ?>
            <option value="<?= e($gender) ?>" <?= ($old['gender'] ?? 'male') === $gender ? 'selected' : '' ?>>
                <?= e($gender) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($errors['gender'])): ?><p class="error"><?= e($errors['gender']) ?></p><?php endif; ?>

    <button class="btn primary" type="submit">Save Patient</button>
    <a class="btn" href="/patients">Back</a>
</form>
<?php
$content = ob_get_clean();
$title = 'Create Patient';
require __DIR__ . '/../layout.php';
