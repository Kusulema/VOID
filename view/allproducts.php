<?php
ob_start();
$pageClass = 'inner-page all-products-page';
if (!isset($arr) || !is_array($arr)) {
	$arr = [];
}
?>
<section class="section-block all-products-shell">
	<div class="section-heading section-heading-center">
		<p>Catalog</p>
		<h2>THE VOID</h2>
		<p class="hero-lead" style="margin-left:auto;margin-right:auto;">The full collection, centered like the main hero and built for the same visual weight.</p>
	</div>
	<div class="newsContainer release-grid">
		<?php ViewProduct::ProductsByCategory($arr); // Было ViewNews::AllNews ?>
	</div>
</section>
<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>