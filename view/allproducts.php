<?php
ob_start();
?>
<h1>Все товары</h1>
<br>
<div class="newsContainer">
<?php
ViewProduct::ProductsByCategory($arr); // Было ViewNews::AllNews
$content = ob_get_clean();
include_once 'view/layout.php';
?>
</div>