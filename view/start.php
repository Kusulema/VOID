<?php
ob_start();
$pageClass = 'home-page';
$showNewsletterPopup = true;
if (!isset($languages) || !is_array($languages)) {
	include_once __DIR__ . '/../inc/languages.php';
}
$languages = isset($languages) && is_array($languages) ? $languages : [];
$langCode = $_SESSION['lang'] ?? 'en';
if (!isset($languages[$langCode])) {
	$langCode = isset($languages['en']) ? 'en' : (isset($languages['et']) ? 'et' : 'ru');
}
$tr = $languages[$langCode] ?? [];
$tt = static function ($key, $fallback = '') use ($tr) {
	return $tr[$key] ?? $fallback;
};
if (!isset($arr) || !is_array($arr)) {
	$arr = [];
}
if (!isset($reviews) || !is_array($reviews)) {
	$reviews = [];
}
$reviewDefaults = [
	[
		'text' => 'Clean execution, strong mood and a homepage that finally looks like a brand.',
		'date' => 'Featured review',
		'rating' => 5,
	],
	[
		'text' => 'The product cards and the popup give the site a real store feeling.',
		'date' => 'Featured review',
		'rating' => 4,
	],
	[
		'text' => 'Dark, editorial and easy to navigate. That is the right direction.',
		'date' => 'Featured review',
		'rating' => 5,
	],
];
$reviewCarouselItems = [];
foreach (array_slice($reviews ?? [], 0, 3) as $index => $review) {
	$reviewCarouselItems[] = [
		'text' => $review['text'] ?? '',
		'date' => $review['date'] ?? 'Featured review',
		'rating' => max(1, 5 - ($index % 2)),
	];
}
if (count($reviewCarouselItems) < 3) {
	foreach ($reviewDefaults as $review) {
		$reviewCarouselItems[] = $review;
		if (count($reviewCarouselItems) >= 3) {
			break;
		}
	}
}
$reviewCarouselItems = array_merge($reviewCarouselItems, [
	[
		'text' => 'Chainwork, brutal contrast and a cleaner flow. It feels like the section is alive now.',
		'date' => 'Featured review',
		'rating' => 5,
	],
	[
		'text' => 'The carousel makes the comments feel curated instead of static.',
		'date' => 'Featured review',
		'rating' => 4,
	],
	[
		'text' => 'Fast, dark and readable. The moving cards make the page feel active.',
		'date' => 'Featured review',
		'rating' => 5,
 	],
]);
$reviewCarouselItems = array_slice($reviewCarouselItems, 0, 6);
?>
<!-- Full-screen entry hero with background image from void1 -->
<section class="entry-hero" id="home">
	<div class="entry-hero-bg" style="background-image: url('img/void7.jpg'); background-position: 90% center;"></div>
	<div class="entry-hero-overlay"></div>
	<div class="blood-snake-layer hero-blood-layer" aria-hidden="true">
		<div class="blood-snake s1"></div>
		<div class="blood-snake s2"></div>
		<div class="blood-snake s3"></div>
		<div class="blood-snake s4"></div>
		<div class="blood-snake s5"></div>
	</div>
	<div class="entry-hero-content">
		<p class="eyebrow"><?php echo htmlspecialchars($tt('intro_label', 'TERMINAL BOOT / RIPPING STEAM')); ?></p>
		<h1 class="heartbeat-pulse"><?php echo htmlspecialchars($tt('intro_title', 'THE VOID')); ?></h1>
	</div>
</section>

<!-- Main content hero stays below the cinematic intro screen -->
<section class="hero-section" id="main-hero">
	<div class="hero-copy">
		<p class="eyebrow"><?php echo htmlspecialchars($tt('hero_tag', 'VOID / IRON / BLOOD')); ?></p>
		<h1><?php echo htmlspecialchars($tt('hero_title', 'Fashion for the ones who do not blend in.')); ?></h1>
		<p class="hero-lead">
			<?php echo htmlspecialchars($tt('hero_text', 'Dark essentials, limited releases and a deliberately sharp aesthetic. Built to feel like a label, not a template.')); ?>
		</p>

		<div class="hero-actions">
			<a href="all" class="submitBtn pulse-red"><?php echo htmlspecialchars($tt('shop_drop', 'Shop the drop')); ?></a>
			<a href="#releases" class="ghost-btn pulse-red"><?php echo htmlspecialchars($tt('newest_releases', 'Newest releases')); ?></a>
		</div>

		<div class="hero-stats">
			<div>
				<strong>24/7</strong>
				<span>Online presence</span>
			</div>
			<div>
				<strong>03</strong>
				<span>Fresh drops this week</span>
			</div>
			<div>
				<strong>100%</strong>
				<span>Unfiltered attitude</span>
			</div>
		</div>
	</div>

	<div class="hero-visual">
		<!-- Plain image element without overlays so the photo is unobstructed -->
		<img src="img/void1.jpg" alt="Hero visual" class="hero-visual-img" />
	</div>
</section>

<div class="full-width-divider">
	<img src="img/rustychain.png" alt="Chain divider" class="divider-image" />
</div>

<section class="section-block" id="releases">
	<div class="section-heading section-heading-center">
		<p><?php echo htmlspecialchars($tt('newest_releases', 'Newest releases')); ?></p>
		<h2><?php echo htmlspecialchars($tt('release_title', 'Three products we would put on the front wall')); ?></h2>
	</div>

	<div class="newsContainer release-grid">
		<?php ViewProduct::ProductsByCategory($arr); ?>
	</div>
</section>

<div class="full-width-image" style="height: 50vh; background-image: url('img/void6.jpg'); background-position: center top; background-attachment: fixed;">
	<div class="hero-overlay" style="position: absolute; inset: 0; background: radial-gradient(ellipse at center, rgba(0,0,0,0) 0%, rgba(0,0,0,0.4) 100%);"></div>
</div>

<section class="section-block about-section" id="about">
	<div class="section-heading compact section-heading-center">
		<p><?php echo htmlspecialchars($tt('about', 'About us')); ?></p>
		<h2><?php echo htmlspecialchars($tt('about_title', 'We build a darker retail world with a cleaner structure.')); ?></h2>
	</div>

	<div class="about-grid">
		<div class="about-panel">
			<h3>Brand direction</h3>
			<p>
				Sharp cuts, heavy contrast and a heavy editorial mood. The site now feels
				like a proper brand space, not a product dump.
			</p>
		</div>
		<div class="about-panel">
			<h3>Local energy</h3>
			<p>
				We keep the experience direct: hero, drops, story, reviews, footer and the
				support sections people actually expect.
			</p>
		</div>
		<div class="about-panel">
			<h3>What changed</h3>
			<p>
				Clickable cards, cart flow, personal cabinet, better scroll sections and a
				stronger visual effect system across the landing page.
			</p>
		</div>
	</div>
</section>

<div class="full-width-divider">
	<img src="img/rustychain.png" alt="Chain divider" class="divider-image" />
</div>

<section class="section-block reviews-section" id="reviews">
	<div class="section-heading compact section-heading-center">
		<p>Reviews</p>
		<h2>Three voices that keep moving</h2>
	</div>

	<div class="review-carousel-shell">
		<button type="button" class="review-carousel-arrow review-carousel-arrow-left" data-review-prev aria-label="Previous reviews">&#10094;</button>
		<div class="review-carousel-viewport" data-review-carousel>
			<div class="review-carousel-track">
				<?php foreach ($reviewCarouselItems as $review): ?>
					<article class="review-card review-carousel-card">
						<div class="review-mark"><img src="img/skull.png" alt="" aria-hidden="true"></div>
						<p><?= htmlspecialchars($review['text']) ?></p>
						<div class="review-rating" aria-label="Rating <?= (int)$review['rating']; ?> out of 5">
							<?php for ($skull = 1; $skull <= 5; $skull++): ?>
								<img src="img/skull.png" alt="" class="review-rating-skull<?= $skull <= (int)$review['rating'] ? ' is-active' : ''; ?>">
							<?php endfor; ?>
						</div>
						<span><?= htmlspecialchars($review['date']) ?></span>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
		<button type="button" class="review-carousel-arrow review-carousel-arrow-right" data-review-next aria-label="Next reviews">&#10095;</button>
	</div>

	<form class="review-compose" data-review-form>
		<div class="review-compose-head review-compose-head-center">
			<p class="cart-note">Your review</p>
			<h3>Leave feedback</h3>
		</div>
		<textarea data-review-text rows="4" maxlength="280" placeholder="Write your review here..." required></textarea>
		<div class="review-rating-picker" data-review-rating-picker>
			<input type="hidden" value="5" data-review-rating>
			<button type="button" class="review-rating-choice is-selected" data-rating-value="1" aria-label="Rate 1 skull"><img src="img/skull.png" alt=""></button>
			<button type="button" class="review-rating-choice is-selected" data-rating-value="2" aria-label="Rate 2 skulls"><img src="img/skull.png" alt=""></button>
			<button type="button" class="review-rating-choice is-selected" data-rating-value="3" aria-label="Rate 3 skulls"><img src="img/skull.png" alt=""></button>
			<button type="button" class="review-rating-choice is-selected" data-rating-value="4" aria-label="Rate 4 skulls"><img src="img/skull.png" alt=""></button>
			<button type="button" class="review-rating-choice is-selected" data-rating-value="5" aria-label="Rate 5 skulls"><img src="img/skull.png" alt=""></button>
		</div>
		<button type="submit" class="submitBtn review-submit">Post review</button>
	</form>
</section>

<div class="full-width-image" style="height: 50vh; background-image: url('img/void2.jpg'); background-position: center; background-attachment: fixed;">
	<div class="hero-overlay" style="position: absolute; inset: 0; background: radial-gradient(ellipse at center, rgba(0,0,0,0) 0%, rgba(0,0,0,0.4) 100%);"></div>
</div>

<div class="newsletter-popup" id="newsletterPopup" aria-hidden="true">
	<div class="newsletter-backdrop" data-popup-close></div>
	<div class="newsletter-modal" role="dialog" aria-modal="true" aria-labelledby="newsletterTitle">
		<button class="popup-close" type="button" data-popup-close aria-label="Close popup">×</button>
		<div class="newsletter-copy">
			<p class="eyebrow">Stay in the loop</p>
			<h2 id="newsletterTitle">15% off your first order</h2>
			<p>
				Early access to new drops, private releases and limited edits.
			</p>

			<form class="newsletter-form" method="post" action="registerForm">
				<input type="email" name="email" placeholder="Email" required>
				<button type="submit" class="submitBtn">Sign up</button>
			</form>
		</div>
		<div class="newsletter-visual" aria-hidden="true">
			<div class="newsletter-figure" style="background-image: url('img/void2.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
		</div>
	</div>
</div>

<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>