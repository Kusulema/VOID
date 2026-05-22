<?php
ob_start();
?>
<br>
<?php
ViewProduct::ReadProduct($product); // Было ViewNews::ReadNews($n)

echo "<br>";
?>

<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>