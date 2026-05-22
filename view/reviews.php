<?php
ob_start();
$pageClass = 'inner-page reviews-page';
?>

<section class="reviews-shell">
    <div class="reviews-list">
        <div class="section-heading compact">
            <p>Reviews</p>
            <h2>Real comments, styled as a proper section</h2>
        </div>

        <div class="reviews-grid">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <article class="review-card">
                        <div class="review-mark" aria-hidden="true">@#$%&</div>
                        <p><?= htmlspecialchars($review['text']) ?></p>
                        <div class="review-rating" aria-hidden="true">
                            <img src="img/skull.png" alt="">
                            <img src="img/skull.png" alt="">
                            <img src="img/skull.png" alt="">
                            <img src="img/skull.png" alt="">
                            <img src="img/skull.png" alt="">
                        </div>
                        <span><?= htmlspecialchars($review['date']) ?></span>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <article class="review-card">
                    <div class="review-mark" aria-hidden="true">@#$%&</div>
                    <p>No comments yet. The section is ready for live feedback once the comment flow is expanded.</p>
                    <div class="review-rating" aria-hidden="true">
                        <img src="img/skull.png" alt="">
                        <img src="img/skull.png" alt="">
                        <img src="img/skull.png" alt="">
                        <img src="img/skull.png" alt="">
                        <img src="img/skull.png" alt="">
                    </div>
                    <span>Empty state</span>
                </article>
            <?php endif; ?>
        </div>
    </div>

    <aside class="cart-panel">
        <p class="cart-note">Navigation</p>
        <h3>Go deeper</h3>
        <p class="account-copy">This page can later connect to product-specific review forms or moderation tools.</p>
        <a href="./" class="submitBtn">Back home</a>
        <a href="all" class="ghost-btn">Browse releases</a>
    </aside>
</section>

<?php
$content = ob_get_clean();
include 'view/layout.php';
