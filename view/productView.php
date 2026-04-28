<?php
class ViewProduct { // Переименовали класс
public static function ProductsByCategory($arr) {
    foreach($arr as $value) {
        $price = isset($value['price']) ? $value['price'] : '';
        echo '<article class="newsBox release-card" data-card-link data-href="product?id=' . $value['id'] . '">';
        echo '<div class="release-image-wrap"><img src="data:image/jpeg;base64,'.base64_encode( $value['picture'] ).'" alt="'.htmlspecialchars($value['title']).'" /></div>';
        echo "<h2>".htmlspecialchars($value['title'])."</h2>";
        if ($price !== '') {
            echo "<div class='release-price'>".htmlspecialchars($price)." €</div>";
        }
        echo "<div class='card-actions'>";
        echo "<a href='product?id=".$value['id']."' class='readMore'>DETAILS</a>";
        echo "<button type='button' class='submitBtn add-to-cart' data-add-to-cart data-id='".$value['id']."' data-title='".htmlspecialchars($value['title'], ENT_QUOTES)."' data-price='".htmlspecialchars($price, ENT_QUOTES)."'>ADD TO CART</button>";
        echo "</div></article>";
    }
}

    public static function ReadProduct($n) {
        $price = isset($n['price']) ? $n['price'] : '';
        $description = isset($n['description']) ? $n['description'] : (isset($n['text']) ? $n['text'] : '');
        echo "<div class='product-detail-shell'>";
        echo "<div class='product-detail-visual'><img src='data:image/jpeg;base64,".base64_encode( $n['picture'] )."' alt='".htmlspecialchars($n['title'])."' /></div>";
        echo "<div class='product-detail-copy'>";
        echo "<p class='eyebrow'>Featured drop</p>";
        echo "<h2>".htmlspecialchars($n['title'])."</h2>";
        if ($price !== '') {
            echo "<h3 class='release-price'>".htmlspecialchars($price)." €</h3>";
        }
        echo "<p>".htmlspecialchars($description)."</p>";
        echo "<div class='card-actions'>";
        echo "<button type='button' class='submitBtn add-to-cart' data-add-to-cart data-id='".$n['id']."' data-title='".htmlspecialchars($n['title'], ENT_QUOTES)."' data-price='".htmlspecialchars($price, ENT_QUOTES)."'>ADD TO CART</button>";
        echo "<a href='cart' class='ghost-btn'>Go to cart</a>";
        echo "</div></div></div>";
    }
}
?>