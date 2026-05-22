<?php
ob_start()
?>
<article>
    <div id="main" class="container">
        <h3>Admin Panel</h3>
        <div class="row">
            <p>Administration dashboard</p>
            <ul>
                <li><a href="index.php?path=productAdmin">Products</a></li>
                <li><a href="index.php?path=commentsAdmin">Comments moderation</a></li>
            </ul>
        </div>
    </div>
</article>

<?php
$content = ob_get_clean();
?>

<?php
include "viewAdmin/templates/layout.php";
?>