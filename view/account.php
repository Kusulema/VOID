<?php
ob_start();
$pageClass = 'inner-page account-page';
?>

<section class="account-shell">
    <div class="account-panel">
        <p class="eyebrow">Personal cabinet</p>
        <div class="account-hero">
            <h2>Account dashboard</h2>
            <p class="account-copy">A private space for profile info, order shortcuts and saved preferences. This keeps the site looking complete while the backend can be extended later.</p>
        </div>

        <div class="account-grid">
            <div class="about-panel">
                <h3>Profile</h3>
                <p>Username, email, saved addresses and preferred size data live here once authentication is connected.</p>
            </div>
            <div class="about-panel">
                <h3>Orders</h3>
                <p>A compact order history block with status badges can sit here when the checkout flow is ready.</p>
            </div>
            <div class="about-panel">
                <h3>Wishlist</h3>
                <p>Pieces you want to revisit can stay pinned here for later shopping sessions.</p>
            </div>
        </div>
    </div>

    <aside class="account-panel">
        <p class="account-flag">Current status</p>
        <h3>Guest mode</h3>
        <p class="account-copy">No sign-in is wired yet, so this cabinet acts as a polished placeholder with space for login, shipping and order controls.</p>
        <a href="registerForm" class="submitBtn">Create profile</a>
        <a href="cart" class="ghost-btn">View cart</a>
    </aside>
</section>

<?php
$content = ob_get_clean();
include 'view/layout.php';
