<?php
ob_start();
$pageClass = 'inner-page account-page';
$isLoggedIn = isset($_SESSION['sessionId']) && !empty($currentUser);
$displayName = '';
$displayEmail = '';
$displayGender = 'Not set';
$displayStatus = 'guest';
$displayLogin = 'guest';
$displayJoined = '—';
$profileMessage = $profileMessage ?? '';
$profileError = $profileError ?? '';

if ($isLoggedIn) {
    $displayName = $currentUser['username'] ?? ($currentUser['name'] ?? ($_SESSION['name'] ?? 'Member'));
    $displayEmail = $currentUser['email'] ?? ($_SESSION['email'] ?? '');
    $displayStatus = $currentUser['status'] ?? ($_SESSION['status'] ?? 'user');
    $displayLogin = $currentUser['login'] ?? ($currentUser['username'] ?? 'guest');
    $displayJoined = $currentUser['registration_date'] ?? '—';
    $rawGender = $currentUser['gender'] ?? ($_SESSION['gender'] ?? 'unspecified');
    $genderMap = [
        'male' => 'Male / Мужской',
        'female' => 'Female / Женский',
        'other' => 'Other / Другое',
        'unspecified' => 'Not set',
    ];
    $displayGender = $genderMap[$rawGender] ?? ucfirst((string) $rawGender);
}
?>

<section class="account-shell">
    <div class="account-panel">
        <p class="eyebrow">Personal cabinet</p>
        <div class="account-hero account-hero-center">
            <h2><?php echo $isLoggedIn ? 'Account dashboard' : 'Guest dashboard'; ?></h2>
            <p class="account-copy"><?php echo $isLoggedIn ? 'A private space for profile info, order shortcuts and saved preferences.' : 'Sign in to unlock profile data, order shortcuts and saved preferences.'; ?></p>
        </div>

        <div class="account-grid">
            <div class="about-panel about-panel-clickable account-feature" role="button" tabindex="0" data-popup-open="profilePopup">
                <p class="eyebrow">PROFILE SETTINGS</p>
                <h3>YOUR PROFILE</h3>
                <p class="account-copy">Manage your delivery address, bank details and account info in one place.</p>
            </div>
            <div class="about-panel about-panel-clickable account-feature" role="button" tabindex="0" data-popup-open="ordersPopup">
                <p class="eyebrow">ORDER HISTORY</p>
                <h3>YOUR ORDERS</h3>
                <p class="account-copy">When purchase data is available, it will show here with totals, dates, shipping address and purchased items.</p>
            </div>
            <div class="about-panel about-panel-clickable account-feature" role="button" tabindex="0" data-popup-open="wishlistPopup">
                <p class="eyebrow">WISHLIST</p>
                <h3>SAVED ITEMS</h3>
                <p class="account-copy">All products you liked are shown here in miniature cards. Click any item to open its product page.</p>
            </div>
        </div>

        <div class="newsletter-popup account-popup" id="profilePopup" aria-hidden="true">
            <div class="newsletter-backdrop" data-popup-close></div>
            <div class="newsletter-modal">
                <button class="popup-close" type="button" data-popup-close aria-label="Close popup">×</button>
                <div class="newsletter-copy">
                    <p class="eyebrow">Profile settings</p>
                    <h2 id="profilePopupLabel">Your profile</h2>
                    <p>Manage your delivery address, bank details and account info in one place.</p>
                    <?php if ($isLoggedIn): ?>
                        <?php if (!empty($profileMessage)): ?><div class="alert alert-success"><?php echo htmlspecialchars($profileMessage); ?></div><?php endif; ?>
                        <?php if (!empty($profileError)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($profileError); ?></div><?php endif; ?>
                        <form method="POST" action="account" class="newsletter-form">
                            <input type="hidden" name="profile_update" value="1">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Name</label>
                                    <input type="text" class="cult-input" value="<?php echo htmlspecialchars($displayName); ?>" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input type="email" class="cult-input" value="<?php echo htmlspecialchars($displayEmail); ?>" disabled>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Gender</label>
                                    <input type="text" class="cult-input" value="<?php echo htmlspecialchars($displayGender); ?>" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Login</label>
                                    <input type="text" class="cult-input" value="<?php echo htmlspecialchars($displayLogin); ?>" disabled>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>City</label>
                                    <input type="text" name="city" class="cult-input" value="<?php echo htmlspecialchars($currentUser['city'] ?? ''); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Country</label>
                                    <input type="text" name="country" class="cult-input" value="<?php echo htmlspecialchars($currentUser['country'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Postcode</label>
                                    <input type="text" name="postcode" class="cult-input" value="<?php echo htmlspecialchars($currentUser['postcode'] ?? ''); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Bank account</label>
                                    <input type="text" name="bank_account" class="cult-input" value="<?php echo htmlspecialchars($currentUser['bank_account'] ?? ''); ?>">
                                </div>
                            </div>
                            <button type="submit" class="submitBtn">Save profile</button>
                        </form>
                    <?php else: ?>
                        <p>Please sign in or create a profile to edit delivery and payment details here.</p>
                        <a href="login" class="submitBtn">Sign in</a>
                        <a href="registerForm" class="ghost-btn">Create profile</a>
                    <?php endif; ?>
                </div>
                <div class="newsletter-visual" aria-hidden="true">
                    <div class="newsletter-figure" style="background-image: url('img/void2.jpg');"></div>
                </div>
            </div>
        </div>

        <div class="newsletter-popup account-popup" id="ordersPopup" aria-hidden="true">
            <div class="newsletter-backdrop" data-popup-close></div>
            <div class="newsletter-modal">
                <button class="popup-close" type="button" data-popup-close aria-label="Close popup">×</button>
                <div class="newsletter-copy">
                    <p class="eyebrow">Order history</p>
                    <h2 id="ordersPopupLabel">Your orders</h2>
                    <p>When purchase data is available, it will show here with totals, dates, shipping address and purchased items.</p>
                    <div class="newsletter-form">
                        <div class="account-copy">No completed orders yet. Your history will appear once checkout is integrated.</div>
                    </div>
                </div>
                <div class="newsletter-visual" aria-hidden="true">
                    <div class="newsletter-figure" style="background-image: url('img/void2.jpg');"></div>
                </div>
            </div>
        </div>

        <div class="newsletter-popup account-popup" id="wishlistPopup" aria-hidden="true">
            <div class="newsletter-backdrop" data-popup-close></div>
            <div class="newsletter-modal">
                <button class="popup-close" type="button" data-popup-close aria-label="Close popup">×</button>
                <div class="newsletter-copy">
                    <p class="eyebrow">Wishlist</p>
                    <h2 id="wishlistPopupLabel">Saved items</h2>
                    <p>All products you liked are shown here in miniature cards. Click any item to open its product page.</p>
                </div>
                <div class="newsletter-visual" aria-hidden="true">
                    <?php if ($isLoggedIn && !empty($wishlistItems)): ?>
                        <div class="wishlist-grid">
                            <?php foreach ($wishlistItems as $wish): ?>
                                <a href="product?id=<?php echo (int)$wish['id']; ?>" class="wishlist-card">
                                    <div class="wishlist-card-image"><img src="data:image/jpeg;base64,<?php echo base64_encode($wish['picture']); ?>" alt="<?php echo htmlspecialchars($wish['title']); ?>"></div>
                                    <div class="wishlist-card-copy">
                                        <h4><?php echo htmlspecialchars($wish['title']); ?></h4>
                                        <p class="price"><?php echo htmlspecialchars($wish['price'] ?? '—'); ?> €</p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="newsletter-form">
                            <div class="account-copy">No wishlist items saved yet. Add products with the heart and return to them later.</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('click', function(event) {
            const openButton = event.target.closest('[data-popup-open]');
            const closeButton = event.target.closest('[data-popup-close]');
            const popupBackdrop = event.target.closest('.newsletter-popup');

            if (openButton) {
                const popupId = openButton.getAttribute('data-popup-open');
                const popup = document.getElementById(popupId);
                if (popup) {
                    popup.classList.add('is-open');
                    popup.setAttribute('aria-hidden', 'false');
                    document.documentElement.classList.add('modal-scroll-lock');
                    document.body.classList.add('modal-scroll-lock');
                }
                return;
            }

            if (closeButton) {
                const popup = closeButton.closest('.newsletter-popup');
                if (popup) {
                    popup.classList.remove('is-open');
                    popup.setAttribute('aria-hidden', 'true');
                    document.documentElement.classList.remove('modal-scroll-lock');
                    document.body.classList.remove('modal-scroll-lock');
                }
                return;
            }

            if (popupBackdrop && event.target === popupBackdrop) {
                popupBackdrop.classList.remove('is-open');
                popupBackdrop.setAttribute('aria-hidden', 'true');
                document.documentElement.classList.remove('modal-scroll-lock');
                document.body.classList.remove('modal-scroll-lock');
            }
        });
        </script>
    </div>

    <aside class="account-panel account-status-panel">
        <p class="account-flag">Current status</p>
        <h3><?php echo $isLoggedIn ? 'Signed in' : 'Guest mode'; ?></h3>
        <?php if ($isLoggedIn): ?>
            <div class="account-info account-info-centered">
                <p><strong>Login:</strong> <?php echo htmlspecialchars($displayLogin); ?></p>
                <p><strong>Status:</strong> Cult Member</p>
                <p><strong>Joined the Club:</strong> <?php echo htmlspecialchars($displayJoined); ?></p>
            </div>
            <div class="account-actions">
                <a href="logout" class="submitBtn">Logout</a>
                <a href="cart" class="ghost-btn">View cart</a>
            </div>
        <?php else: ?>
            <p class="account-copy">No sign-in is active yet. Use the buttons below to log in or create your profile.</p>
            <div class="account-actions">
                <a href="login" class="submitBtn">Login</a>
                <a href="registerForm" class="ghost-btn">Create profile</a>
            </div>
        <?php endif; ?>
    </aside>
</section>

<?php
$content = ob_get_clean();
include 'view/layout.php';
