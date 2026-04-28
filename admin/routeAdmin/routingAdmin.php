<?php
$host = explode('?', $_SERVER['REQUEST_URI']) [0];
$num = substr_count($host, '/');
$path = explode('/', $host) [$num];

if ($path == '' OR $path == 'index.php') {
    $response = controllerAdmin::formLoginSite();
}
elseif ($path == 'login') {
    $response = controllerAdmin::loginAction();
}
elseif ($path == 'logout') {
    $response = controllerAdmin::logoutAction();
}
// Работа с товарами
elseif($path == 'productAdmin') {
    $response = controllerAdminProduct::ProductList();
}
elseif($path == 'productAdd') {
    $response = controllerAdminProduct::productAddForm();
}
elseif($path == 'productAddResult') {
    $response = controllerAdminProduct::productAddResult();
}
elseif($path == 'productEdit' && isset($_GET['id'])) {
    $response = controllerAdminProduct::productEditForm($_GET['id']);
}
elseif($path == 'productEditResult' && isset($_GET['id'])) {
    $response = controllerAdminProduct::productEditResult($_GET['id']);
}
elseif($path == 'productDel' && isset($_GET['id'])) {
    $response = controllerAdminProduct::productDeleteForm($_GET['id']);
}
elseif($path == 'productDelResult' && isset($_GET['id'])) {
    $response = controllerAdminProduct::productDeleteResult($_GET['id']);
}
else {
    $response = controllerAdmin::error404();
}
?>