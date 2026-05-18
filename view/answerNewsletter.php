<?php
ob_start();
?>
<div class="container" style="padding:40px 0;">
    <div class="alert alert-info">
        <strong><?php echo htmlspecialchars($newsletterMessage ?? 'Done.'); ?></strong>
        <a href="./">Return to main page</a>
    </div>
</div>
<?php
$content = ob_get_clean();
include "view/layout.php";
?>