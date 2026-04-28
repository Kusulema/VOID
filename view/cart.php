<?php
ob_start();
$pageClass = 'inner-page cart-page';
?>

<section class="cart-shell">
    <div class="cart-panel">
        <p class="eyebrow">Cart</p>
        <div class="cart-hero">
            <h2>Your selection</h2>
            <p class="account-copy">A lightweight client-side cart with a real visual shell. Add products from the release cards and they will appear here instantly.</p>
        </div>

        <div class="empty-state" data-cart-empty>
            Your cart is empty. Add a few pieces from the newest releases.
        </div>

        <div class="cart-list" data-cart-list></div>
    </div>

    <aside class="cart-panel cart-summary">
        <p class="cart-note">Quick summary</p>
        <h3>Total</h3>
        <div class="cart-total"><span data-cart-total>0.00</span> €</div>
        <div class="account-copy">
            Shipping and checkout can be wired in next. For now this keeps the shopping flow visible and usable.
        </div>
        <a href="all" class="submitBtn">Continue shopping</a>
        <a href="account" class="ghost-btn">Open account</a>
        <button type="button" class="submitBtn" onclick="localStorage.removeItem('voidCart'); window.location.reload();">Clear cart</button>
    </aside>
</section>

<?php
$content = ob_get_clean();
include 'view/layout.php';
