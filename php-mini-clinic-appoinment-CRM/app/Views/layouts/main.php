<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Mini Clinic') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
    <script>
    function togglePassword() {
        const input = document.getElementById('password');
        const btn = input.nextElementSibling;
        if (input.type === 'password') {
            input.type = 'text';
            btn.innerHTML = '&#128064;';
        } else {
            input.type = 'password';
            btn.innerHTML = '&#128065;';
        }
    }
    </script>
</head>
<body>
<?php partial('nav'); ?>
<main class="container">
    <?php partial('flash'); ?>
    <?= $content ?? '' ?>
</main>
</body>
</html>
