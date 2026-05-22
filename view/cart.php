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
            <div class="cart-inline-actions">
                <button type="button" class="submitBtn" onclick="localStorage.removeItem('voidCart'); window.location.reload();">Clear cart</button>
                <button type="button" id="buyNowBtn" class="submitBtn">Buy now</button>
            </div>
        </div>

        <div class="empty-state" data-cart-empty>
            Your cart is empty. Add a few pieces from the newest releases.
        </div>

        <div class="cart-list" data-cart-list></div>
    </div>

    <aside class="cart-panel cart-summary">
        <p class="cart-note">Cart</p>
        <h3>Total</h3>
        <div class="cart-total"><span data-cart-total>0.00</span> €</div>
        <div class="account-copy cart-summary-copy">
            Shipping and checkout can be wired in next. For now this keeps the shopping flow visible and usable.
        </div>
        <div class="cart-actions-grid">
            <div class="cart-actions-column">
                <a href="all" class="submitBtn">Continue shopping</a>
            </div>
            <div class="cart-actions-column cart-actions-column-right">
                <a href="account" class="submitBtn">Open account</a>
            </div>
        </div>
    </aside>
</section>

<div class="order-warning-modal" id="orderWarningModal" aria-hidden="true">
    <div class="order-warning-backdrop" data-order-warning-close></div>
    <div class="order-warning-dialog" role="dialog" aria-modal="true" aria-labelledby="orderWarningTitle">
        <button type="button" class="popup-close" data-order-warning-close aria-label="Close popup">×</button>
        <p class="eyebrow">Profile required</p>
        <h2 id="orderWarningTitle">Complete your profile before checkout</h2>
        <p class="account-copy" id="orderWarningText">Your account is missing required billing and delivery data.</p>
        <div class="order-warning-list" id="orderWarningList"></div>
        <div class="order-warning-actions">
            <a href="account?open=profilePopup" class="submitBtn" id="fillProfileBtn">Fill data</a>
            <button type="button" class="ghost-btn" data-order-warning-close>Cancel</button>
        </div>
    </div>
</div>

<script>
// Cart purchase handler: collects cart from localStorage and sends to server
function openOrderWarning(payload) {
    var modal = document.getElementById('orderWarningModal');
    var text = document.getElementById('orderWarningText');
    var list = document.getElementById('orderWarningList');
    var link = document.getElementById('fillProfileBtn');
    var missingFields = Array.isArray(payload && payload.missingFields) ? payload.missingFields : [];
    var profileUrl = payload && payload.profileUrl ? payload.profileUrl : 'account?open=profilePopup';

    if (text) {
        text.textContent = payload && payload.error ? payload.error : 'Your account is missing required billing and delivery data.';
    }
    if (list) {
        list.innerHTML = missingFields.length ? '<ul>' + missingFields.map(function(item){ return '<li>' + item + '</li>'; }).join('') + '</ul>' : '';
    }
    if (link) {
        link.setAttribute('href', profileUrl);
    }
    if (modal) {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
    }
}

function closeOrderWarning() {
    var modal = document.getElementById('orderWarningModal');
    if (modal) {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    }
}

document.addEventListener('click', function(event) {
    if (event.target.matches('[data-order-warning-close]')) {
        closeOrderWarning();
    }
});

document.getElementById('buyNowBtn').addEventListener('click', function(){
    var cart = JSON.parse(localStorage.getItem('voidCart') || '[]');
    var total = 0;
    cart.forEach(function(i){ total += (parseFloat(i.price) || 0) * (parseInt(i.qty || i.q || 1, 10) || 1); });
    fetch('order', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ items: cart, total: total })
    }).then(function(r){ return r.text(); }).then(function(text){
        var data = {};
        try { data = JSON.parse(text); } catch (e) {}
        if (data && data.success) {
            alert('Success, now you wait');
            localStorage.removeItem('voidCart');
            window.location.reload();
        } else if (data && data.missingProfile) {
            openOrderWarning(data);
        } else {
            // Show error message in console instead of alert
            console.error((data && data.error) ? data.error : 'You must first fill all profile information fields in your profile.');
            openOrderWarning({error: (data && data.error) ? data.error : 'Please fill all profile information before checkout.'});
        }
    }).catch(function(){
        // Show error message in console instead of alert
        console.error('An error occurred while placing the order.');
        openOrderWarning({error: 'An error occurred while placing the order. Please try again.'});
    });
});
</script>

<?php
$content = ob_get_clean();
include 'view/layout.php';
