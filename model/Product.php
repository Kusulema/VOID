<?php
/**
 * Product model - data access and business logic related to products.
 * Handles multi-language fields, selection and simple recommendation sorting.
 */
class Product {
    /**
     * Columns needed for product lists without loading the BLOB image.
     * Includes the image because catalog and homepage cards render uploaded pictures.
     */
    private static function listColumns(): string {
        return 'id, title, title_en, title_ru, title_et, description, description_en, description_ru, description_et, price, category_id, user_id, picture';
    }

    /**
     * Determine current language for product titles/descriptions.
     * @return string 'en'|'ru'|'et'
     */
    private static function currentLang(): string {
        $lang = $_SESSION['lang'] ?? 'en';
        return in_array($lang, ['en', 'ru', 'et'], true) ? $lang : 'en';
    }

    /**
     * Map language-specific title/description into generic keys `title` and `description`.
     * @param array $product
     * @return array
     */
    private static function applyLanguage(array $product): array {
        $lang = self::currentLang();
        $titleKey = 'title_' . $lang;
        $descriptionKey = 'description_' . $lang;

        if (!empty($product[$titleKey])) {
            $product['title'] = $product[$titleKey];
        }
        if (!empty($product[$descriptionKey])) {
            $product['description'] = $product[$descriptionKey];
        }

        return $product;
    }

    /**
     * Return the last N products (after language mapping and user personalization).
     * @param array|null $currentUser
     * @param int $limit
     * @return array
     */
    public static function getLastProducts($currentUser = null, $limit = 3) {
        $all = self::getAllProducts($currentUser);
        return array_slice($all, 0, $limit);
    }

    /**
     * Load all products from database and apply language mapping.
     * If $currentUser is provided, sort products with a simple personalization algorithm.
     * @param array|null $currentUser
     * @return array
     */
    public static function getAllProducts($currentUser = null) {
        $query = 'SELECT ' . self::listColumns() . ' FROM product ORDER BY id DESC';
        $db = new Database();
        $products = array_map([self::class, 'applyLanguage'], $db->getAll($query));

        if ($currentUser === null) {
            return $products;
        }

        return self::sortProductsForUser($products, $currentUser);
    }

    /**
     * Get products for a specific category id.
     * @param int $id
     * @return array
     */
    public static function getProductsByCategoryID($id) {
        $query = 'SELECT ' . self::listColumns() . ' FROM product WHERE category_id=' . (int)$id . ' ORDER BY id DESC';
        $db = new Database();
        return array_map([self::class, 'applyLanguage'], $db->getAll($query));
    }

    /**
     * Load a single product by id.
     * @param int $id
     * @return array|null
     */
    public static function getProductByID($id) {
        $query = "SELECT * FROM product WHERE id=".(int)$id;
        $db = new Database();
        $product = $db->getOne($query);
        return $product ? self::applyLanguage($product) : $product;
    }

    /**
     * Load products by an array of ids preserving input order.
     * Uses prepared statements to avoid SQL injection.
     * @param int[] $ids
     * @return array
     */
    public static function getProductsByIds(array $ids) {
        if (count($ids) === 0) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = 'SELECT ' . self::listColumns() . " FROM product WHERE id IN ($placeholders) ORDER BY FIELD(id, $placeholders)";
        $db = new Database();
        $params = array_merge($ids, $ids);
        return array_map([self::class, 'applyLanguage'], $db->getAllPrepared($query, $params));
    }

    /**
     * Very small personalization routine: prefers products by user's gender
     * or by categories contained in user's wishlist (accessory priority).
     * @param array $products
     * @param array $currentUser
     * @return array
     */
    private static function sortProductsForUser(array $products, array $currentUser): array {
        $gender = strtolower(trim($currentUser['gender'] ?? 'unspecified'));
        $wishlist = Register::getWishlistIds($currentUser);

        $preferredCategory = 0;
        $genderCategory = 0;
        if ($gender === 'male') {
            $genderCategory = 1;
        } elseif ($gender === 'female') {
            $genderCategory = 2;
        }

        $wishlistProducts = self::getProductsByIds($wishlist);
        $hasAccessoryWishlist = false;
        foreach ($wishlistProducts as $prod) {
            if ((int)($prod['category_id'] ?? 0) === 3) {
                $hasAccessoryWishlist = true;
                break;
            }
        }

        if ($hasAccessoryWishlist) {
            $preferredCategory = 3;
        } elseif ($genderCategory > 0) {
            $preferredCategory = $genderCategory;
        }

        usort($products, function ($a, $b) use ($genderCategory, $preferredCategory) {
            $scoreA = self::scoreProductForUser($a, $genderCategory, $preferredCategory);
            $scoreB = self::scoreProductForUser($b, $genderCategory, $preferredCategory);
            if ($scoreA === $scoreB) {
                return (($b['id'] ?? 0) <=> ($a['id'] ?? 0));
            }
            return $scoreA <=> $scoreB;
        });

        return $products;
    }

    /**
     * Scoring helper used to sort products for a user.
     * Lower score = higher ranking.
     */
    private static function scoreProductForUser(array $product, int $genderCategory, int $preferredCategory): int {
        $categoryId = (int)($product['category_id'] ?? 0);

        if ($preferredCategory > 0 && $categoryId === $preferredCategory) {
            return 0;
        }
        if ($genderCategory > 0 && $categoryId === $genderCategory) {
            return 1;
        }
        if ($categoryId === 3) {
            return 2;
        }
        return 3;
    }
}
?>