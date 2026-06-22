<?php ob_start(); ?>
<h1>403 - Forbidden</h1>
<p>Your request could not be verified. Please try again.</p>
<a class="btn primary" href="/">Back to Dashboard</a>
<?php
$content = ob_get_clean();
$title = '403 Forbidden';
require __DIR__ . '/../layout.php';
