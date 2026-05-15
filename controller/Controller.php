<?php
class Controller {
    public static function StartSite() {
        $currentUser = isset($_SESSION['userId']) ? Register::getCurrentUser() : null;
        $arr = Product::getLastProducts($currentUser);
        $reviews = Comments::getLatestComments(6);
        $pageClass = 'home-page';
        $showNewsletterPopup = true;
        include_once 'view/start.php';
    }

    public static function AllCategory() {
        $arr = Category::getAllCategory();
        include_once 'view/category.php';
    }
    

    public static function AllProducts() {
        $currentUser = isset($_SESSION['userId']) ? Register::getCurrentUser() : null;
        $arr = Product::getAllProducts($currentUser);
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

    public static function loginAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = Register::loginUser();
            if ($result[0] === true) {
                $currentUser = Register::getCurrentUser();
                $pageClass = 'inner-page account-page';
                include_once 'view/account.php';
                return;
            }

            $errorString = $result[1] ?? 'Неправильный email или пароль';
        }

        include_once 'view/formLogin.php';
    }

    public static function logoutAction() {
        Register::logoutUser();
        include_once 'view/formLogin.php';
    }

    public static function Cart() {
        $pageClass = 'inner-page cart-page';
        include_once 'view/cart.php';
    }

    public static function WishlistAction($productId, $action = 'toggle') {
        if (isset($_SESSION['userId'])) {
            if ($action === 'toggle') {
                Register::toggleWishlistItem($productId);
            }
        }
        $back = $_SERVER['HTTP_REFERER'] ?? 'account';
        header('Location: ' . $back);
        exit;
    }

    public static function Account() {
        $profileMessage = '';
        $profileError = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profile_update'])) {
            $result = Register::updateProfile();
            if ($result[0] === true) {
                $profileMessage = $result[1] ?? 'Profile saved successfully.';
            } else {
                $profileError = $result[1] ?? 'Unable to save profile information.';
            }
        }

        $currentUser = Register::getCurrentUser();
        $wishlistItems = [];
        if ($currentUser) {
            $wishlistIds = Register::getWishlistIds($currentUser);
            if ($wishlistIds) {
                $wishlistItems = Product::getProductsByIds($wishlistIds);
            }
        }
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