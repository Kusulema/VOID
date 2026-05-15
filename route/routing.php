<?php
// Вычислить маршрут из адресной строки
$host = explode('?', $_SERVER['REQUEST_URI']) [0];
$num = substr_count($host, '/');
$path = explode('/', $host) [$num];

if($path == '' OR $path == 'index' OR $path == 'index.php') {
    $response = Controller::StartSite();
}
elseif($path == 'all') {
    // ИЗМЕНЕНО: теперь вызываем AllProducts
    $response = Controller::AllProducts();
}
elseif($path == 'category' && isset($_GET['id'])) {
    // ИЗМЕНЕНО: теперь вызываем ProductByCatID
    $response = Controller::ProductByCatID($_GET['id']);
}
elseif($path == 'product' && isset($_GET['id'])) { // Путь тоже лучше назвать product
    $response = Controller::ProductByID($_GET['id']);
}
// Если в старых ссылках осталось 'news', пусть тоже ведет на товар
elseif($path == 'news' && isset($_GET['id'])) {
    $response = Controller::ProductByID($_GET['id']);
}
elseif ($path == 'registerForm') {
    $response = Controller::registerForm();
}
elseif ($path == 'registerAnswer') {
    $response = Controller::registerUser();
}
elseif ($path == 'login') {
    $response = Controller::loginAction();
}
elseif ($path == 'logout') {
    $response = Controller::logoutAction();
}
elseif ($path == 'allCategories') {
    $response = Controller::AllCategory(); // Используем уже существующий метод
}
elseif($path == 'cart' || $path == 'basket') {
    $response = Controller::Cart();
}
elseif($path == 'wishlist' && isset($_GET['id'])) {
    $response = Controller::WishlistAction((int)$_GET['id'], $_GET['action'] ?? 'toggle');
}
elseif($path == 'account' || $path == 'cabinet') {
    $response = Controller::Account();
}
elseif($path == 'reviews') {
    $response = Controller::Reviews();
}
else {
    $response = Controller::error404();
}
?>