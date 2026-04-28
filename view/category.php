<?php ob_start(); ?>

<div class="bios-terminal">

    <div class="bios-header">VOID BIOS v0.13</div>

    <div class="bios-sub">
        INITIALIZING SYSTEM CORE...
    </div>

    <div class="bios-list">
        <?php foreach ($arr as $i => $category): ?>
            <a href="category?id=<?= $category['id'] ?>"
               class="bios-row"
               style="animation-delay: <?= $i * 0.15 ?>s">
                <span class="bios-arrow">></span>
                <span class="bios-name">
                    <?= strtoupper(htmlspecialchars($category['name'])) ?>
                </span>
                <span class="bios-status">[ ONLINE ]</span>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="bios-footer">
        PRESS ENTER TO ACCESS SECTOR
    </div>

</div>

<?php
$content = ob_get_clean();
require 'layout.php';
