<?php
/**
 * Main application controller — handles public site routes and actions.
 *
 * Methods are static and primarily include controllers for pages,
 * forms and AJAX endpoints such as placing orders or inserting comments.
 */
class Controller {
    /**
     * Show home page with latest products and recent comments.
     * Loads `view/start.php`.
     */
    public static function StartSite() {
        $currentUser = isset($_SESSION['userId']) ? Register::getCurrentUser() : null;
        $arr = Product::getLastProducts($currentUser);
        $reviews = Comments::getLatestComments(6);
        $pageClass = 'home-page';
        $showNewsletterPopup = true;
        include_once 'view/start.php';
    }

    /**
     * Show list of all categories.
     */
    public static function AllCategory() {
        $arr = Category::getAllCategory();
        include_once 'view/category.php';
    }
    

    /**
     * Show list of all products. Passes current user to product loader
     * so personalized data (wishlist flags) can be resolved.
     */
    public static function AllProducts() {
        $currentUser = isset($_SESSION['userId']) ? Register::getCurrentUser() : null;
        $arr = Product::getAllProducts($currentUser);
        include_once 'view/allproducts.php'; 
    }

    /**
     * Show products filtered by category id.
     * @param int $id Category identifier
     */
    public static function ProductByCatID($id) {
        $arr = Product::getProductsByCategoryID($id);
        include_once 'view/catproducts.php';
    }

    /**
     * Show detail page for a single product by id.
     * @param int $id Product identifier
     */
    public static function ProductByID($id) {
        $product = Product::getProductByID($id);
        $comments = Comments::getCommentByNewsID($id);
        include_once 'view/productDetail.php';
    }

    /**
     * Render 404 error page.
     */
    public static function error404() {
        include_once 'view/error404.php';
    }

    // Registration methods
    /**
     * Show registration form.
     */
    public static function registerForm() {
        include_once 'view/formRegister.php';
    }

    /**
     * Handle registration POST and display result.
     */
    public static function registerUser() {
        $result = Register::registerUser();
        include_once 'view/answerRegister.php';
    }

    /**
     * Subscribe a user to the newsletter (expects POST with `email`).
     * Validates email and sends a mail using `inc/Mailer.php`.
     */
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

    /**
     * Handle login form (GET shows form, POST attempts authentication).
     */
    public static function loginAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = Register::loginUser();
            if ($result[0] === true) {
                $currentUser = Register::getCurrentUser();
                // Redirect to 'next' if provided
                $next = trim((string)($_GET['next'] ?? ''));
                if ($next !== '') {
                    header('Location: ' . $next);
                    exit;
                }
                $pageClass = 'inner-page account-page';
                include_once 'view/account.php';
                return;
            }

            $errorString = $result[1] ?? 'Incorrect email or password';
        }

        include_once 'view/formLogin.php';
    }

    /**
     * Log out current user and show login form.
     */
    public static function logoutAction() {
        Register::logoutUser();
        include_once 'view/formLogin.php';
    }

    /**
     * Display shopping cart page.
     */
    public static function Cart() {
        $pageClass = 'inner-page cart-page';
        include_once 'view/cart.php';
    }

    /**
     * Toggle or remove item from user's wishlist. Redirects back.
     * @param int $productId
     * @param string $action 'toggle' or 'remove'
     */
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

    /**
     * Show and update account/profile page. Also loads wishlist items.
     */
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

    /**
     * Insert a comment provided via POST or GET (minimal anti-spam checks).
     */
    public static function InsertComment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['comment'])) {
            $id = (int)$_POST['id'];
            $text = trim($_POST['comment']);
            if ($text !== '') {
                Comments::insertComment($text, $id);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && isset($_GET['comment'])) {
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

    /**
     * List recent reviews/comments.
     */
    public static function Reviews() {
        $reviews = Comments::getLatestComments(12);
        $pageClass = 'inner-page reviews-page';
        include_once 'view/reviews.php';
    }
    /**
     * AJAX endpoint to place an order. Expects JSON payload with items and total.
     * Validates profile completeness and sends emails to user and admin.
     */
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

        // Ensure the logged-in user's profile contains required payment/shipping
        // fields before proceeding. This delegates validation logic to the
        // Register model so the same rules apply in other contexts.
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

        // Build human-readable order lines. Accepts either 'qty' or legacy 'q'
        // keys and tolerates different item key names for title/price.
        $lines = [];
        foreach ($items as $it) {
            $qty = isset($it['qty']) ? (int)$it['qty'] : (isset($it['q']) ? (int)$it['q'] : 1);
            $price = isset($it['price']) ? number_format((float)$it['price'], 2) : '0.00';
            $title = $it['title'] ?? ($it['name'] ?? 'Product');
            $lines[] = $qty . ' x ' . $title . ' @ ' . $price . ' €';
        }

        // Prepare email notifications. Mailer::bootstrapEnv attempts to load
        // environment helpers (phpdotenv) so CI or deployed instances can
        // override SMTP settings without code changes.
        require_once __DIR__ . '/../inc/Mailer.php';
        Mailer::bootstrapEnv();
        $userSubject = '[THE VOID] The fruit is ripe. Your new skin is drying on rusted hooks.';
        $orderLinesHtml = '<ul>'; foreach ($lines as $ln) { $orderLinesHtml .= '<li>' . htmlspecialchars($ln) . '</li>'; } $orderLinesHtml .= '</ul>';

        $userBody = "We heard your breath. The pact with the Void is sealed with a drop of Lymph.\n\n"
            . "Your order has been accepted and is being prepared. Below is the summary of items you ordered:\n\n"
            . implode("\n", $lines) . "\n\n"
            . "Order total: " . number_format((float)$total, 2) . " €\n\n"
            . "Flesh Status: Preparing for separation (Order packing).\n"
            . "Breach Coordinates: " . ($currentUser['address'] ?? 'No address provided') . "\n\n"
            . "Soon we will send you a tracking code.\n\n"
            . "Stay in the dark. The mold needs warmth.";

        $adminBody = "New order received:\n\n"
            . "Customer: " . ($currentUser['username'] ?? $currentUser['name'] ?? '') . "\n"
            . "Email: " . ($currentUser['email'] ?? '') . "\n"
            . "Address: " . ($currentUser['address'] ?? '') . "\n"
            . "Total: " . number_format((float)$total, 2) . " €\n\n"
            . "Items:\n" . implode("\n", $lines);

        $userMailSent = false;
        if (!empty($currentUser['email'])) {
            $userMailSent = Mailer::send($currentUser['email'], $userSubject, nl2br($userBody), true);
        }
        // Determine where admin notifications should be sent. Prefer a
        // dedicated VOID_MAIL_ORDER_TO env var, fall back to VOID_MAIL_ADMIN_TO
        // and finally to a safe default. This allows deployment-specific
        // routing without code edits.
        $orderNotifyTo = (getenv('VOID_MAIL_ORDER_TO') ?: ($_ENV['VOID_MAIL_ORDER_TO'] ?? '') ?: ($_SERVER['VOID_MAIL_ORDER_TO'] ?? ''));
        if ($orderNotifyTo === '') {
            $orderNotifyTo = (getenv('VOID_MAIL_ADMIN_TO') ?: ($_ENV['VOID_MAIL_ADMIN_TO'] ?? '') ?: ($_SERVER['VOID_MAIL_ADMIN_TO'] ?? '') ?: 'admin@void.com');
        }
        $adminMailSent = Mailer::send($orderNotifyTo, 'New order from ' . ($currentUser['email'] ?? 'unknown'), nl2br(htmlspecialchars($adminBody)), true);

        if ($userMailSent && $adminMailSent) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Email delivery failed. Configure SMTP in inc/Mailer.php.']);
        }
        exit;
    }
    
}
?>