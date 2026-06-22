<?php ob_start(); ?>
<h1>405 - Method Not Allowed</h1>
<p>The request method is not supported for this page.</p>
<a class="btn primary" href="/">Back to Dashboard</a>
<?php
$content = ob_get_clean();
$title = '405 Method Not Allowed';
require __DIR__ . '/../layout.php';
