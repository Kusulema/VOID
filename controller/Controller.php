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

    public static function NewsletterSubscribe() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ./');
            exit;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if (!$email) {
            $newsletterMessage = 'Please enter a valid email address.';
            include_once 'view/answerNewsletter.php';
            return;
        }

        require_once __DIR__ . '/../inc/Mailer.php';
        $subject = 'You have rooted yourself in this dirt.';
        $preheader = 'Rust devours everything except us.';
        $html = '<p><small>' . htmlspecialchars($preheader) . '</small></p>'
            . '<p>Your e-mail has been successfully consumed.</p>'
            . '<p>You are now subscribed to the chronicle of our filth.</p>'
            . '<p>No welcome bonuses for weaklings - only raw, unfiltered content and early access to what others will see too late.</p>'
            . '<p>We will brand your inbox when the next drop is due. Keep your eyes open.</p>'
            . '<p>[ JOIN THE NETWORK ]</p>';

        $sent = Mailer::send($email, $subject, $html, true);
        if ($sent) {
            $newsletterMessage = 'The message has been sent to your email.';
        } else {
            $newsletterMessage = 'Email delivery failed. Configure SMTP in inc/Mailer.php.';
        }

        include_once 'view/answerNewsletter.php';
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
            } elseif ($action === 'remove') {
                // ensure removal only
                $ids = Register::getWishlistIds(Register::getCurrentUser());
                if (in_array((int)$productId, $ids, true)) {
                    Register::toggleWishlistItem($productId);
                }
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

    public static function InsertComment() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && isset($_GET['comment'])) {
            $id = (int)$_GET['id'];
            $text = trim($_GET['comment']);
            if ($text !== '') {
                Comments::insertComment($text, $id);
            }
        }
        $back = $_SERVER['HTTP_REFERER'] ?? './';
        header('Location: ' . $back);
        exit;
    }

    public static function Reviews() {
        $reviews = Comments::getLatestComments(12);
        $pageClass = 'inner-page reviews-page';
        include_once 'view/reviews.php';
    }
    public static function PlaceOrder() {
        // Expect JSON POST with items and total
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            echo json_encode(['success' => false, 'error' => 'Invalid request']);
            exit;
        }

        if (!isset($_SESSION['userId'])) {
            echo json_encode(['success' => false, 'error' => 'You must be signed in to place an order.']);
            exit;
        }

        $currentUser = Register::getCurrentUser();
        list($profileComplete, $profileErrors) = Register::validateProfileForOrder($currentUser ?: []);
        if (!$profileComplete) {
            $missingFields = array_values($profileErrors);
            $missingKeys = array_keys($profileErrors);
            echo json_encode([
                'success' => false,
                'error' => 'Your account profile is missing required data.',
                'missingProfile' => true,
                'missingFields' => $missingFields,
                'profileUrl' => 'account?open=profilePopup&missing=' . rawurlencode(implode(',', $missingKeys)),
            ]);
            exit;
        }

        $items = $input['items'] ?? [];
        $total = $input['total'] ?? 0;

        // build order summary
        $lines = [];
        foreach ($items as $it) {
            $qty = isset($it['qty']) ? (int)$it['qty'] : (isset($it['q']) ? (int)$it['q'] : 1);
            $price = isset($it['price']) ? number_format((float)$it['price'], 2) : '0.00';
            $title = $it['title'] ?? ($it['name'] ?? 'Product');
            $lines[] = $qty . ' x ' . $title . ' @ ' . $price . ' €';
        }

        require_once __DIR__ . '/../inc/Mailer.php';
        $userSubject = '[THE VOID] The fruit is ripe. Your new skin is drying on rusted hooks.';
        $userBody = "We heard your breath. The pact with the Void is sealed with a drop of Lymph.\n\n"
            . "Your order has been accepted. Your new shell has already been torn from the bone, washed in sweat, and dried among rusted industrial gears. Our Sisters are already wrapping it in coarse cloth to protect it from the spreading mold on its way to your Chamber.\n\n"
            . "Flesh Status: Preparing for separation (Order packing).\n"
            . "Breach Coordinates: Your shipping address.\n\n"
            . "Soon we will send you a tracking code - a thread to help you feel your package through the fog. Do not let your Heart wither before it arrives.\n\n"
            . "Stay in the dark. The mold needs warmth.";

        $adminBody = "New order received:\n\n"
            . "Customer: " . ($currentUser['username'] ?? $currentUser['name'] ?? '') . "\n"
            . "Email: " . ($currentUser['email'] ?? '') . "\n"
            . "Address: " . ($currentUser['address'] ?? '') . "\n"
            . "Total: " . number_format((float)$total, 2) . " €\n\n"
            . "Items:\n" . implode("\n", $lines);

        $userMailSent = Mailer::send($currentUser['email'] ?? '', $userSubject, nl2br(htmlspecialchars($userBody)), true);
        $adminMailSent = Mailer::send('admin@void.com', 'New order from ' . ($currentUser['email'] ?? 'unknown'), nl2br(htmlspecialchars($adminBody)), true);

        if ($userMailSent && $adminMailSent) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Email delivery failed. Configure SMTP in inc/Mailer.php.']);
        }
        exit;
    }
    
}
?>