<?php ob_start(); ?>
<h1>404 - Page Not Found</h1>
<p>The page you are looking for does not exist.</p>
<a class="btn primary" href="/">Back to Dashboard</a>
<?php
$content = ob_get_clean();
$title = '404 Not Found';
require __DIR__ . '/../layout.php';
