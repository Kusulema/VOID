<?php
ob_start();
?>
<br>
<?php
ViewProduct::ReadProduct($product); // Было ViewNews::ReadNews($n)

echo "<br>";
// Если ты убрал комментарии, эту строку можно удалить:
// Controller::Comments($_GET['id']); 
?>

<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>