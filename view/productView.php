<?php
class ViewProduct { // Переименовали класс
private static function renderProductImage(array $value, string $title): void {
    if (!empty($value['picture'])) {
        echo '<div class="release-image-wrap"><img src="data:image/jpeg;base64,' . base64_encode($value['picture']) . '" alt="' . htmlspecialchars($title) . '" /></div>';
        return;
    }

    echo '<div class="release-image-wrap"><img src="img/void1.jpg" alt="' . htmlspecialchars($title) . '" /></div>';
}

public static function ProductsByCategory($arr) {
    $wishlistIds = [];
    if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['wishlist'])) {
        $decoded = json_decode($_SESSION['wishlist'], true);
        if (is_array($decoded)) {
            $wishlistIds = array_map('intval', $decoded);
        }
    }

    foreach($arr as $value) {
        $price = isset($value['price']) ? $value['price'] : '';
        $title = $value['title'] ?? '';
        $isFavorite = in_array((int)$value['id'], $wishlistIds, true);
        $heart = $isFavorite ? '♥' : '♡';
        $heartClass = $isFavorite ? 'wishlist-heart active' : 'wishlist-heart';

        echo '<article class="newsBox release-card" data-card-link data-href="product?id=' . $value['id'] . '">';
    self::renderProductImage($value, $title);
        echo "<h2>".htmlspecialchars($title)."</h2>";
        if ($price !== '') {
            echo "<div class='release-price'>".htmlspecialchars($price)." €</div>";
        }
        echo "<div class='card-actions'>";
        echo "<a href='wishlist?id=".$value['id']."' class='".$heartClass."' title='Toggle wishlist'>".$heart."</a>";
        echo "<a href='product?id=".$value['id']."' class='readMore'>DETAILS</a>";
        echo "<button type='button' class='submitBtn add-to-cart' data-add-to-cart data-id='".$value['id']."' data-title='".htmlspecialchars($title, ENT_QUOTES)."' data-price='".htmlspecialchars($price, ENT_QUOTES)."'>ADD TO CART</button>";
        echo "</div></article>";
    }
}

    public static function ReadProduct($n) {
        $price = isset($n['price']) ? $n['price'] : '';
        $title = $n['title'] ?? '';
        $description = isset($n['description']) ? $n['description'] : (isset($n['text']) ? $n['text'] : '');
        $wishlistIds = [];
        if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['wishlist'])) {
            $decoded = json_decode($_SESSION['wishlist'], true);
            if (is_array($decoded)) {
                $wishlistIds = array_map('intval', $decoded);
            }
        }
        $isFavorite = in_array((int)$n['id'], $wishlistIds, true);
        $heart = $isFavorite ? '♥' : '♡';
        $heartClass = $isFavorite ? 'wishlist-heart active' : 'wishlist-heart';

        echo "<div class='product-detail-shell'>";
        if (!empty($n['picture'])) {
            echo "<div class='product-detail-visual'><img src='data:image/jpeg;base64,".base64_encode( $n['picture'] )."' alt='".htmlspecialchars($title)."' /></div>";
        } else {
            echo "<div class='product-detail-visual'><img src='img/void1.jpg' alt='".htmlspecialchars($title)."' /></div>";
        }
        echo "<div class='product-detail-copy'>";
        echo "<p class='eyebrow'>Featured drop</p>";
        echo "<h2>".htmlspecialchars($title)."</h2>";
        if ($price !== '') {
            echo "<h3 class='release-price'>".htmlspecialchars($price)." €</h3>";
        }
        echo "<p>".htmlspecialchars($description)."</p>";
        echo "<div class='card-actions'>";
        echo "<a href='wishlist?id=".$n['id']."' class='".$heartClass."' title='Toggle wishlist'>".$heart."</a>";
        echo "<button type='button' class='submitBtn add-to-cart' data-add-to-cart data-id='".$n['id']."' data-title='".htmlspecialchars($title, ENT_QUOTES)."' data-price='".htmlspecialchars($price, ENT_QUOTES)."'>ADD TO CART</button>";
        echo "<a href='cart' class='ghost-btn'>Go to cart</a>";
        echo "</div></div></div>";
    }
}
?>