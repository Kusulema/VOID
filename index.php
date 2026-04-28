<?php
session_start();

// Логика смены языка
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'et'; // По умолчанию эстонский
}

include_once 'inc/Database.php';
include_once 'inc/languages.php'; // Подключаем переводы

// Подключаем модели (переименовал News в Product)
require 'model/Category.php';
require 'model/Product.php'; 
require 'model/Comments.php';
require 'model/Register.php';

include_once 'view/productView.php'; // Здесь функции вывода товаров
include_once 'view/comments.php';

include_once 'controller/Controller.php';
include_once 'route/routing.php';

echo $response;
?>