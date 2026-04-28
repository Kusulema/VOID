<?php
class Product {
    public static function getLast3Products() {
        $query = "SELECT * FROM product ORDER BY id DESC LIMIT 3";
        $db = new Database();
        return $db->getAll($query);
    }

    public static function getAllProducts() {
        $query = "SELECT * FROM product ORDER BY id DESC";
        $db = new Database();
        return $db->getAll($query);
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
}
?>