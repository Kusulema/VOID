<?php
class Controller {
    public static function StartSite() {
        $arr = Product::getLast3Products();
        $reviews = Comments::getLatestComments(3);
        $pageClass = 'home-page';
        $showNewsletterPopup = true;
        include_once 'view/start.php';
    }

    public static function AllCategory() {
        $arr = Category::getAllCategory();
        include_once 'view/category.php';
    }
    

    public static function AllProducts() {
        $arr = Product::getAllProducts();
        include_once 'view/allproducts.php'; 
    }

    public static function ProductByCatID($id) {
        $arr = Product::getProductsByCategoryID($id);
        include_once 'view/catproducts.php';
    }

    public static function ProductByID($id) {
        $product = Product::getProductByID($id);
        include_once 'view/productDetail.php';
    }

    public static function error404() {
        include_once 'view/error404.php';
    }

    // Методы регистрации (которые вызывали ошибку)
    public static function registerForm() {
        include_once 'view/formRegister.php';
    }

    public static function registerUser() {
        $result = Register::registerUser();
        include_once 'view/answerRegister.php';
    }

    public static function Cart() {
        $pageClass = 'inner-page cart-page';
        include_once 'view/cart.php';
    }

    public static function Account() {
        $pageClass = 'inner-page account-page';
        include_once 'view/account.php';
    }

    public static function Reviews() {
        $reviews = Comments::getLatestComments(12);
        $pageClass = 'inner-page reviews-page';
        include_once 'view/reviews.php';
    }
    
}
?>