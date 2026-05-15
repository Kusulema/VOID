<?php
class Product {
    public static function getLastProducts($currentUser = null, $limit = 3) {
        $all = self::getAllProducts($currentUser);
        return array_slice($all, 0, $limit);
    }

    public static function getAllProducts($currentUser = null) {
        $query = "SELECT * FROM product ORDER BY id DESC";
        $db = new Database();
        $products = $db->getAll($query);

        if ($currentUser === null) {
            return $products;
        }

        return self::sortProductsForUser($products, $currentUser);
    }

    public static function getProductsByCategoryID($id) {
        $query = "SELECT * FROM product WHERE category_id=".(int)$id." ORDER BY id DESC";
        $db = new Database();
        return $db->getAll($query);
    }

    public static function getProductByID($id) {
        $query = "SELECT * FROM product WHERE id=".(int)$id;
        $db = new Database();
        return $db->getOne($query);
    }

    public static function getProductsByIds(array $ids) {
        if (count($ids) === 0) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "SELECT * FROM product WHERE id IN ($placeholders) ORDER BY FIELD(id, $placeholders)";
        $db = new Database();
        $params = array_merge($ids, $ids);
        return $db->getAllPrepared($query, $params);
    }

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