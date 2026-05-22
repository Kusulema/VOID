<?php
class modelAdminProduct {
    private static function ensureTranslationSchema() {
        $db = new Database();
        $columns = [
            'title_en' => 'ALTER TABLE `product` ADD COLUMN `title_en` VARCHAR(255) NULL AFTER `title`',
            'description_en' => 'ALTER TABLE `product` ADD COLUMN `description_en` TEXT NULL AFTER `description`',
            'title_ru' => 'ALTER TABLE `product` ADD COLUMN `title_ru` VARCHAR(255) NULL AFTER `title_en`',
            'description_ru' => 'ALTER TABLE `product` ADD COLUMN `description_ru` TEXT NULL AFTER `description_en`',
            'title_et' => 'ALTER TABLE `product` ADD COLUMN `title_et` VARCHAR(255) NULL AFTER `title_ru`',
            'description_et' => 'ALTER TABLE `product` ADD COLUMN `description_et` TEXT NULL AFTER `description_ru`',
        ];

        foreach ($columns as $column => $sql) {
            try {
                if (!$db->hasColumn('product', $column)) {
                    $db->executeRun($sql);
                }
            } catch (Throwable $e) {
                continue;
            }
        }
    }

    private static function translationValue(string $field, string $suffix): ?string {
        $key = $field . '_' . $suffix;
        if (!isset($_POST[$key])) {
            return null;
        }

        $value = trim((string)$_POST[$key]);
        return $value === '' ? null : $value;
    }

    public static function getProductList() {
        self::ensureTranslationSchema();
        $query = "SELECT product.*, category.name, users.username FROM product 
                  JOIN category ON product.category_id = category.id 
                  JOIN users ON product.user_id = users.id 
                  ORDER BY product.id DESC";
        $db = new Database();
        return $db->getAll($query);
    }

    public static function getProductAdd() {
        self::ensureTranslationSchema();
        $test = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $title = trim((string)($_POST['title'] ?? ''));
            $description = trim((string)($_POST['description'] ?? ''));
            $price = (float)($_POST['price'] ?? 0);
            $idCategory = (int)($_POST['idCategory'] ?? 0);
            $userId = (int)($_SESSION['userId'] ?? 0);

            $image = null;
            if (!empty($_FILES['picture']['tmp_name']) && is_uploaded_file($_FILES['picture']['tmp_name'])) {
                $image = file_get_contents($_FILES['picture']['tmp_name']);
            }

            $sql = 'INSERT INTO `product` (
                `title`, `description`, `price`, `picture`, `category_id`, `user_id`,
                `title_en`, `description_en`, `title_ru`, `description_ru`, `title_et`, `description_et`
            ) VALUES (
                :title, :description, :price, :picture, :category_id, :user_id,
                :title_en, :description_en, :title_ru, :description_ru, :title_et, :description_et
            )';

            $test = $db->executePrepared($sql, [
                ':title' => $title,
                ':description' => $description,
                ':price' => $price,
                ':picture' => $image,
                ':category_id' => $idCategory,
                ':user_id' => $userId,
                ':title_en' => self::translationValue('title', 'en'),
                ':description_en' => self::translationValue('description', 'en'),
                ':title_ru' => self::translationValue('title', 'ru'),
                ':description_ru' => self::translationValue('description', 'ru'),
                ':title_et' => self::translationValue('title', 'et'),
                ':description_et' => self::translationValue('description', 'et'),
            ]);
        }
        return $test;
    }

    public static function getProductDetail($id) {
        self::ensureTranslationSchema();
        $query = "SELECT * FROM product WHERE id = " . (int)$id;
        $db = new Database();
        return $db->getOne($query);
    }

    public static function getProductEdit($id) {
        self::ensureTranslationSchema();
        $test = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $title = trim((string)($_POST['title'] ?? ''));
            $description = trim((string)($_POST['description'] ?? ''));
            $price = (float)($_POST['price'] ?? 0);
            $idCategory = (int)($_POST['idCategory'] ?? 0);

            $params = [
                ':title' => $title,
                ':description' => $description,
                ':price' => $price,
                ':category_id' => $idCategory,
                ':title_en' => self::translationValue('title', 'en'),
                ':description_en' => self::translationValue('description', 'en'),
                ':title_ru' => self::translationValue('title', 'ru'),
                ':description_ru' => self::translationValue('description', 'ru'),
                ':title_et' => self::translationValue('title', 'et'),
                ':description_et' => self::translationValue('description', 'et'),
                ':id' => (int)$id,
            ];

            if (!empty($_FILES['picture']['tmp_name']) && is_uploaded_file($_FILES['picture']['tmp_name'])) {
                $params[':picture'] = file_get_contents($_FILES['picture']['tmp_name']);
                $sql = 'UPDATE `product` SET
                    `title` = :title,
                    `description` = :description,
                    `price` = :price,
                    `picture` = :picture,
                    `category_id` = :category_id,
                    `title_en` = :title_en,
                    `description_en` = :description_en,
                    `title_ru` = :title_ru,
                    `description_ru` = :description_ru,
                    `title_et` = :title_et,
                    `description_et` = :description_et
                    WHERE `id` = :id';
            } else {
                $sql = 'UPDATE `product` SET
                    `title` = :title,
                    `description` = :description,
                    `price` = :price,
                    `category_id` = :category_id,
                    `title_en` = :title_en,
                    `description_en` = :description_en,
                    `title_ru` = :title_ru,
                    `description_ru` = :description_ru,
                    `title_et` = :title_et,
                    `description_et` = :description_et
                    WHERE `id` = :id';
            }

            $test = $db->executePrepared($sql, $params);
        }
        return $test;
    }

    public static function getProductDelete($id) {
        $db = new Database();
        return $db->executePrepared('DELETE FROM `product` WHERE `id` = :id', [':id' => (int)$id]);
    }
}
?>