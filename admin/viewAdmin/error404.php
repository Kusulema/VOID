<?php
ob_start()
?>

<h2>404 Error</h2>

<article>
    <h3>What is a 404 error?</h3>
    <p>The requested URL could not be found on this server.</p>
</article>

<?php
$content = ob_get_clean();
?>

<?php
include "viewAdmin/templates/layout.php";
?>