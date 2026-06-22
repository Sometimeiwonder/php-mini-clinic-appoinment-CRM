<?php ob_start(); ?>
<h1>500 - Server Error</h1>
<p>Sorry, we could not process your request right now.</p>
<a class="btn primary" href="/">Back to Dashboard</a>
<?php
$content = ob_get_clean();
$title = 'Server Error';
require __DIR__ . '/../layout.php';
