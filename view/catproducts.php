<?php
ob_start();
$arr = isset($arr) && is_array($arr) ? $arr : [];
$categoryId = (int)($_GET['id'] ?? 0);
$categoryMeta = [
	1 => [
		'title' => 'MAN',
		'lead' => 'Tailored silhouettes, heavy layers and hard lines cut for the dark procession.',
	],
	2 => [
		'title' => 'WOMEN',
		'lead' => 'Sharp contrast, fragile strength and silhouettes built to glow in the void.',
	],
	3 => [
		'title' => 'ACCESSORIES',
		'lead' => 'Chains, rings and relic pieces that finish the ritual without making a sound.',
	],
];
$currentCategory = $categoryMeta[$categoryId] ?? [
	'title' => 'THE VOID',
	'lead' => 'Industrial essentials and limited drops, gathered into one dark selection.',
];
?>
<section class="section-block all-products-shell">
	<div class="section-heading section-heading-center">
		<p><?= htmlspecialchars($currentCategory['title']) ?></p>
		<h2><?= htmlspecialchars($currentCategory['title']) ?></h2>
		<p class="hero-lead" style="margin-left:auto;margin-right:auto; max-width: 900px;">
			<?= htmlspecialchars($currentCategory['lead']) ?>
		</p>
	</div>
	<div class="newsContainer release-grid">
<?php ViewProduct::ProductsByCategory($arr); // Было ViewNews::NewsByCategory ?>
	</div>
</section>

<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>
</div>