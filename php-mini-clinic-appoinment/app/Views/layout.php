<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Mini Clinic Appointment') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<nav class="navbar">
    <strong>Mini Clinic</strong>
    <a href="/">Dashboard</a>
    <a href="/patients">Patients</a>
    <a href="/patients/create">Create Patient</a>
    <a href="/appointments">Appointments</a>
    <a href="/appointments/create">Create Appointment</a>
    <a href="/health">Health</a>
</nav>
<main class="container">
    <?php if ($success = flash_get('success')): ?>
        <div class="alert success"><?= e($success) ?></div>
    <?php endif; ?>

    <?php if ($error = flash_get('error')): ?>
        <div class="alert error"><?= e($error) ?></div>
    <?php endif; ?>

    <?= $content ?? '' ?>
</main>
</body>
</html>
