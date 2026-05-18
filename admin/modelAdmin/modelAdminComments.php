<?php
class modelAdminComments {
    public static function getAllComments() {
        $db = new database();
        // ensure approved column exists
        try {
            $rows = $db->getAll('SELECT * FROM comments ORDER BY id DESC');
            return $rows;
        } catch (Throwable $e) {
            return [];
        }
    }

    public static function setApproved($id, $approved) {
        $db = new database();
        if (!$db->hasColumn('comments', 'approved')) {
            $db->executeRun('ALTER TABLE `comments` ADD COLUMN `approved` TINYINT(1) NOT NULL DEFAULT 0');
        }
        $db->executePrepared('UPDATE `comments` SET `approved` = :a WHERE `id` = :id', ['a' => $approved ? 1 : 0, 'id' => (int)$id]);
    }

    public static function deleteComment($id) {
        $db = new database();
        $db->executePrepared('DELETE FROM `comments` WHERE `id` = :id', ['id' => (int)$id]);
    }
}

?>