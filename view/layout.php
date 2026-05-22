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
$allowedLangs = ['en','ru','et'];
// URL governs language state. If ?lang is absent, default to English.
$langCode = (!empty($_GET['lang']) && in_array($_GET['lang'], $allowedLangs, true)) ? $_GET['lang'] : 'en';
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
$parsedUrl = parse_url($_SERVER['REQUEST_URI'] ?? './');
$langQuery = [];
if (!empty($parsedUrl['query'])) {
    parse_str($parsedUrl['query'], $langQuery);
}
$langPath = $parsedUrl['path'] ?? './';
$buildLangHref = static function (string $code) use ($langPath, $langQuery): string {
    $query = $langQuery;
    $query['lang'] = $code;
    $queryString = http_build_query($query);
    return $langPath . ($queryString !== '' ? '?' . $queryString : '');
};
$bodyUserId = !empty($_SESSION['userId']) ? (string)$_SESSION['userId'] : '';
?>
<body class="industrial-body <?php echo isset($pageClass) ? htmlspecialchars($pageClass) : ''; ?>" data-page="<?php echo isset($pageClass) && strpos($pageClass, 'home') !== false ? 'home' : 'inner'; ?>" data-user-id="<?php echo htmlspecialchars($bodyUserId); ?>">
<div class="void-scope">
<nav class="one navbar navbar-expand-lg">
    <div class="container-fluid nav-shell">
        <!-- LEFT: Logo + Languages -->
        <div class="nav-left-stack">
            <a class="nav-link cult-main brand-link nav-hero nav-hover-text" href="./">THE VOID</a>
            <div class="lang-under-brand">
                <a class="lang-link text-glow" data-lang="ru" href="<?php echo htmlspecialchars($buildLangHref('ru')); ?>" onclick="(function(e){e.preventDefault();var L=document.querySelectorAll('.lang-link');for(var i=0;i<L.length;i++){L[i].classList.remove('active');}this.classList.add('active');window.location=this.href;})(event)">RU</a>
                <a class="lang-link text-glow" data-lang="en" href="<?php echo htmlspecialchars($buildLangHref('en')); ?>" onclick="(function(e){e.preventDefault();var L=document.querySelectorAll('.lang-link');for(var i=0;i<L.length;i++){L[i].classList.remove('active');}this.classList.add('active');window.location=this.href;})(event)">EN</a>
                <a class="lang-link text-glow" data-lang="et" href="<?php echo htmlspecialchars($buildLangHref('et')); ?>" onclick="(function(e){e.preventDefault();var L=document.querySelectorAll('.lang-link');for(var i=0;i<L.length;i++){L[i].classList.remove('active');}this.classList.add('active');window.location=this.href;})(event)">ET</a>
            </div>
        </div>

        <!-- CENTER: Text Menu Only -->
        <div class="collapse navbar-collapse justify-content-center nav-center" id="mainMenu">
            <ul class="topmenu navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-glow nav-hover-text" href="all"><?php echo htmlspecialchars($tt('releases', 'Releases')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-glow nav-hover-text" href="./#reviews"><?php echo htmlspecialchars($tt('reviews', 'Reviews')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-glow nav-hover-text" href="category?id=1">MEN (DEAD SOULS)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-glow nav-hover-text" href="category?id=2">WOMEN (DARK GRACE)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-glow nav-hover-text" href="category?id=3">ACCESSORIES (IRON & BONE)</a>
                </li>
            </ul>
        </div>

        <!-- RIGHT: Join + Icons (Account | Home | Cart) -->
        <div class="nav-right-stack">
            <?php $joinHref = isset($_SESSION['userId']) && !empty($_SESSION['userId']) ? 'account' : 'registerForm'; ?>
            <a class="nav-link cult-main brand-link nav-hero join-link nav-hover-text" href="<?php echo $joinHref; ?>">JOIN THE CLUB</a>
            <div class="nav-icons-right">
                <a href="account" class="nav-link text-glow nav-icon-item nav-hover-image" title="Account">
                    <img src="img/varrount.png" alt="Account">
                </a>
                <a href="./" class="nav-link text-glow nav-icon-item nav-hover-image" title="Home">
                    <img src="img/vhome.png" alt="Home">
                </a>
                <a href="cart" class="nav-link text-glow nav-icon-item nav-hover-image" title="Cart">
                    <img src="img/vcart.png" alt="Cart">
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

<div class="crt-overlay"></div>
<div class="screen-glitch"></div>

<div class="newsletter-popup auth-popup" id="authRequiredModal" aria-hidden="true">
    <div class="newsletter-backdrop" data-popup-close></div>
    <div class="newsletter-modal auth-modal" role="dialog" aria-modal="true" aria-labelledby="authRequiredTitle">
        <button class="popup-close" type="button" data-popup-close aria-label="Close popup">×</button>
        <div class="newsletter-copy">
            <p class="eyebrow">You must sign in</p>
            <h2 id="authRequiredTitle">Please sign in to continue</h2>
            <p>
                To add items to your wishlist or place orders you must be signed in. Please log in or register to continue.
            </p>
            <div class="card-actions">
                <a class="submitBtn" href="login">Sign in</a>
                <a class="ghost-btn" href="registerForm">Register</a>
            </div>
        </div>
        <div class="newsletter-visual profile-visual auth-visual" aria-hidden="true">
            <div class="profile-photo" style="background-image: url('img/void9.jpg');"></div>
            <div class="profile-chain" style="background-image: url('img/rustychain.png');"></div>
        </div>
    </div>
</div>

<script src="void-effects.js?v=<?php echo time(); ?>"></script>
<script>
// Ensure language buttons reflect server-side language immediately and update on click
(function(){
    try {
        var serverLang = <?php echo json_encode($langCode); ?>;
        var links = document.querySelectorAll('.lang-link');
        links.forEach(function(l){ l.classList.remove('active'); });
        var currentUrl = new URL(window.location.href);
        var activeLang = currentUrl.searchParams.get('lang') || 'en';
        var active = document.querySelector('.lang-link[data-lang="' + activeLang + '"]');
        if (active) active.classList.add('active');
        // Intercept language clicks: set active immediately, then navigate.
        links.forEach(function(l){
            l.addEventListener('click', function(e){
                try { e.preventDefault(); e.stopImmediatePropagation(); } catch (er) {}
                links.forEach(function(x){ x.classList.remove('active'); });
                l.classList.add('active');
                // navigate to href (preserves normal behavior but ensures active state first)
                var href = l.href || l.getAttribute('href');
                if (href) {
                    window.location.href = href;
                }
            }, {passive:false});
        });

        // Preserve the chosen language across internal navigation links
        var currentUrl = new URL(window.location.href);
        var currentLang = currentUrl.searchParams.get('lang') || serverLang || 'en';
        var keepLangOnLinks = function(anchor) {
            if (!anchor || !anchor.getAttribute) return;
            var href = anchor.getAttribute('href');
            if (!href || href.indexOf('javascript:') === 0) return;
            if (anchor.classList.contains('lang-link')) return;
            if (href === '#' || href.indexOf('#') === 0) return;

            var url;
            try {
                url = new URL(href, window.location.href);
            } catch (e) {
                return;
            }

            if (url.origin !== window.location.origin) return;
            url.searchParams.set('lang', currentLang);
            anchor.href = url.toString();
        };

        document.querySelectorAll('a[href]').forEach(keepLangOnLinks);

        document.addEventListener('click', function(e){
            var anchor = e.target.closest && e.target.closest('a[href]');
            if (!anchor) return;
            if (anchor.classList.contains('lang-link')) return;
            var href = anchor.getAttribute('href');
            if (!href || href === '#' || href.indexOf('#') === 0 || href.indexOf('javascript:') === 0) return;
            keepLangOnLinks(anchor);
        }, true);
    } catch (e) {
        // noop
    }
})();
</script>
</div>
</body>
</html>