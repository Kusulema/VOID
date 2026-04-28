<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css?v=<?php echo time(); ?>">
    <title>VOID & IRON | Alternative Wear</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<?php
if (!isset($languages) || !is_array($languages)) {
    include_once __DIR__ . '/../inc/languages.php';
}
$languages = isset($languages) && is_array($languages) ? $languages : [];
$langCode = $_SESSION['lang'] ?? 'en';
$langFallback = ['en', 'et', 'ru'];
if (!isset($languages[$langCode])) {
    $langCode = in_array('en', array_keys($languages), true)
        ? 'en'
        : (in_array('et', array_keys($languages), true) ? 'et' : 'ru');
}
$tr = $languages[$langCode] ?? [];
$tt = static function ($key, $fallback = '') use ($tr) {
    return $tr[$key] ?? $fallback;
};
$isHomePage = isset($pageClass) && strpos($pageClass, 'home') !== false;
?>
<body class="industrial-body <?php echo isset($pageClass) ? htmlspecialchars($pageClass) : ''; ?>" data-page="<?php echo isset($pageClass) && strpos($pageClass, 'home') !== false ? 'home' : 'inner'; ?>">
<div class="void-scope">
<nav class="one navbar navbar-expand-lg">
    <div class="container-fluid nav-shell">
        <div class="nav-left-stack">
            <a class="nav-link cult-main brand-link nav-hero" href="./">THE VOID</a>
            <div class="lang-under-brand">
                <a class="lang-link text-glow<?php echo $langCode === 'ru' ? ' active' : ''; ?>" href="?lang=ru">RU</a>
                <a class="lang-link text-glow<?php echo $langCode === 'en' ? ' active' : ''; ?>" href="?lang=en">EN</a>
                <a class="lang-link text-glow<?php echo $langCode === 'et' ? ' active' : ''; ?>" href="?lang=et">ET</a>
            </div>
        </div>

        <div class="collapse navbar-collapse justify-content-center nav-center" id="mainMenu">
            <ul class="topmenu navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-glow nav-home-icon" href="./" aria-label="Home">⌂</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-glow" href="all"><?php echo htmlspecialchars($tt('releases', 'Releases')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-glow" href="reviews"><?php echo htmlspecialchars($tt('reviews', 'Reviews')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-glow" href="category?id=1">MEN (DEAD SOULS)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-glow" href="category?id=2">WOMEN (DARK GRACE)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-glow" href="category?id=3">ACCESSORIES (IRON & BONE)</a>
                </li>
            </ul>
        </div>

        <div class="nav-right-tools">
            <a class="nav-link cult-main brand-link nav-hero join-link" href="registerForm">JOIN THE CLUB</a>
            <div class="nav-meta-icons">
                <a href="account" class="meta-link account-link">
                    <span class="text-glow">ACCOUNT</span>
                    <img src="img/Gemini_Generated_Image_eodwdveodwdveodw.png" alt="Account avatar">
                </a>
                <a href="cart" class="meta-link cart-link">
                    <span class="text-glow">CART</span>
                    <img src="img/cap.png" alt="Cart icon">
                </a>
            </div>
        </div>
    </div>
</nav>

    <section class="main-container">
        <?php if ($isHomePage): ?>
            <?php if (isset($content)) { echo $content; } ?>
        <?php else: ?>
            <div class="container-fluid px-0">
                <div class="divBox industrial-border">
                    <?php
                    if(isset($content)) {
                        echo $content;
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <footer class="industrial-footer text-center mt-5">
        <div class="blood-line"></div>
        <div class="footer-grid">
            <div>
                <h4>Location</h4>
                <p>Tallinn, Estonia</p>
                <p>Online studio / retail</p>
            </div>
            <div>
                <h4>Socials</h4>
                <a href="#" class="inline-link">Instagram</a>
                <a href="#" class="inline-link">TikTok</a>
                <a href="#" class="inline-link">YouTube</a>
            </div>
            <div>
                <h4>Quick links</h4>
                <a href="cart" class="inline-link">Cart</a>
                <a href="account" class="inline-link">Account</a>
                <a href="reviews" class="inline-link">Reviews</a>
            </div>
        </div>
        <p>BLOOD, IRON & SWEAT &copy; 2026 | BUILT BY KIRILL</p>
        <p class="small text-muted">STAY DARK. STAY ROTTEN.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<audio id="terminalClick" src="sounds/click.mp3" preload="auto"></audio>

<div class="blood-snake-layer" aria-hidden="true">
    <div class="blood-snake s1"></div>
    <div class="blood-snake s2"></div>
    <div class="blood-snake s3"></div>
    <div class="blood-snake s4"></div>
    <div class="blood-snake s5"></div>
</div>
<div class="crt-overlay"></div>
<div class="screen-glitch"></div>

<script src="void-effects.js"></script>
</div>
</body>
</html>