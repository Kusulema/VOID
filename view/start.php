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
?>
<!-- Full-screen entry hero with looping animated background video from Pexels (CC0 License) -->
<section class="entry-hero" id="home">
	<video class="entry-hero-video" autoplay muted loop playsinline preload="metadata" poster="img/cap.png">
		<source src="https://videos.pexels.com/video-files/5926382/5926382-sd_640_360_25fps.mp4" type="video/mp4">
		<source src="https://videos.pexels.com/video-files/5926382/5926382-hd_1280_720_25fps.mp4" type="video/mp4">
	</video>
	<div class="entry-hero-overlay"></div>
	<div class="entry-hero-content">
		<p class="eyebrow"><?php echo htmlspecialchars($tt('intro_label', 'TERMINAL BOOT / RIPPING STEAM')); ?></p>
		<h1><?php echo htmlspecialchars($tt('intro_title', 'THE VOID')); ?></h1>
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
		<div class="hero-frame"></div>
		<div class="hero-orb hero-orb-a"></div>
		<div class="hero-orb hero-orb-b"></div>
		<div class="hero-card hero-card-top">
			<span>New season</span>
			<strong>Cold silhouettes</strong>
		</div>
		<div class="hero-card hero-card-bottom">
			<span>Selected edit</span>
			<strong>Street / altar / noise</strong>
		</div>
	</div>
</section>

<section class="section-block" id="releases">
	<div class="section-heading">
		<p><?php echo htmlspecialchars($tt('newest_releases', 'Newest releases')); ?></p>
		<h2><?php echo htmlspecialchars($tt('release_title', 'Three products we would put on the front wall')); ?></h2>
	</div>

	<div class="newsContainer release-grid">
		<?php ViewProduct::ProductsByCategory($arr); ?>
	</div>
</section>

<section class="section-block about-section" id="about">
	<div class="section-heading compact">
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

<section class="section-block reviews-section" id="reviews">
	<div class="section-heading compact">
		<p>Reviews</p>
		<h2>Three voices from the void</h2>
	</div>

	<div class="reviews-grid">
		<?php if (!empty($reviews)): ?>
			<?php foreach (array_slice($reviews, 0, 3) as $review): ?>
				<article class="review-card">
					<div class="review-mark">"</div>
					<p><?= htmlspecialchars($review['text']) ?></p>
					<span><?= htmlspecialchars($review['date']) ?></span>
				</article>
			<?php endforeach; ?>
		<?php else: ?>
			<article class="review-card">
				<div class="review-mark">"</div>
				<p>Clean execution, strong mood and a homepage that finally looks like a brand.</p>
				<span>Featured review</span>
			</article>
			<article class="review-card">
				<div class="review-mark">"</div>
				<p>The product cards and the popup give the site a real store feeling.</p>
				<span>Featured review</span>
			</article>
			<article class="review-card">
				<div class="review-mark">"</div>
				<p>Dark, editorial and easy to navigate. That is the right direction.</p>
				<span>Featured review</span>
			</article>
		<?php endif; ?>
	</div>
</section>

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
			<div class="newsletter-figure"></div>
		</div>
	</div>
</div>

<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>