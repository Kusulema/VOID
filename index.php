<?php
session_start();

// LANGUAGE HANDLING: prefer ?lang=, then cookie, finally default to 'en'
if (isset($_GET['lang'])) {
    $allowed = ['en', 'ru', 'et'];
    $lang = in_array($_GET['lang'], $allowed, true) ? $_GET['lang'] : 'en';
    $_SESSION['lang'] = $lang;
    // persist choice in a cookie for requests that lose the querystring
    setcookie('site_lang', $lang, time() + (3600 * 24 * 365), '/');
} elseif (!isset($_SESSION['lang'])) {
    if (!empty($_COOKIE['site_lang']) && in_array($_COOKIE['site_lang'], ['en','ru','et'], true)) {
        $_SESSION['lang'] = $_COOKIE['site_lang'];
    } else {
        $_SESSION['lang'] = 'en';
    }
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