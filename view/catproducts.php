<?php
ob_start();
?>
<h1>Products by category</h1>
<br>
<div class="newsContainer">
<?php
ViewProduct::ProductsByCategory($arr); // Было ViewNews::NewsByCategory
$content = ob_get_clean();
include_once 'view/layout.php';
?>
</div>