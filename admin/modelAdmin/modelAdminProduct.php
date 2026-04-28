<?php
class modelAdminProduct {
    public static function getProductList() {
        $query = "SELECT product.*, category.name, users.username FROM product 
                  JOIN category ON product.category_id = category.id 
                  JOIN users ON product.user_id = users.id 
                  ORDER BY product.id DESC";
        $db = new Database();
        return $db->getAll($query);
    }

    public static function getProductAdd() {
        $test = false;
        if(isset($_POST['save'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price']; // Новое поле
            $idCategory = $_POST['idCategory'];
            $userId = $_SESSION['userId'];

            $image = addslashes(file_get_contents($_FILES['picture']['tmp_name']));

            $sql = "INSERT INTO `product` (`title`, `description`, `price`, `picture`, `category_id`, `user_id`) 
                    VALUES ('$title', '$description', '$price', '$image', '$idCategory', '$userId')";
            $db = new Database();
            $test = $db->executeRun($sql);
        }
        return $test;
    }

    public static function getProductDetail($id) {
        $query = "SELECT * FROM product WHERE id = ".(int)$id;
        $db = new Database();
        return $db->getOne($query);
    }

    public static function getProductEdit($id) {
        $test = false;
        if(isset($_POST['save'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $idCategory = $_POST['idCategory'];

            $db = new Database();
            if($_FILES['picture']['name'] != "") {
                $image = addslashes(file_get_contents($_FILES['picture']['tmp_name']));
                $sql = "UPDATE `product` SET `title`='$title', `description`='$description', `price`='$price', `picture`='$image', `category_id`='$idCategory' WHERE `id`=$id";
            } else {
                $sql = "UPDATE `product` SET `title`='$title', `description`='$description', `price`='$price', `category_id`='$idCategory' WHERE `id`=$id";
            }
            $test = $db->executeRun($sql);
        }
        return $test;
    }
}
?>