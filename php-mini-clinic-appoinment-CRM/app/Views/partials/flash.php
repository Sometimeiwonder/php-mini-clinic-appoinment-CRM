<?php if ($msg = get_flash('success')): ?>
    <div class="alert success"><?= e($msg) ?></div>
<?php endif; ?>
<?php if ($msg = get_flash('error')): ?>
    <div class="alert error"><?= e($msg) ?></div>
<?php endif; ?>
