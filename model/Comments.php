<?php
class Comments {
    private static function ensureModerationSchema() {
        $db = new Database();
        try {
            $db->executeRun('CREATE TABLE IF NOT EXISTS `comments` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `news_id` int(11) NOT NULL,
                `text` varchar(500) NOT NULL,
                `date` datetime NOT NULL,
                `user_id` int(11) NULL,
                `approved` tinyint(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        } catch (Throwable $e) {
            return;
        }

        try {
            if (!$db->hasColumn('comments', 'user_id')) {
                $db->executeRun('ALTER TABLE `comments` ADD COLUMN `user_id` INT NULL');
            }
            if (!$db->hasColumn('comments', 'approved')) {
                $db->executeRun('ALTER TABLE `comments` ADD COLUMN `approved` TINYINT(1) NOT NULL DEFAULT 0');
                $db->executeRun('UPDATE `comments` SET `approved` = 0 WHERE `approved` IS NULL');
            }
        } catch (Throwable $e) {
            return;
        }
    }

    public static function getLatestComments($limit = 3) {
        self::ensureModerationSchema();
        $limit = (int)$limit;
        $query = "SELECT * FROM comments WHERE approved = 1 ORDER BY id DESC LIMIT " . $limit;

        try {
            $db = new Database();
            return $db->getAll($query);
        } catch (Throwable $e) {
            return [];
        }
    }

    public static function insertComment($c, $id) {
        // Only allow logged-in users to post comments
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['userId'])) return false;

        self::ensureModerationSchema();
        $db = new Database();

        $userId = (int)$_SESSION['userId'];
        $res = $db->executePrepared('INSERT INTO `comments` (`news_id`, `text`, `user_id`, `date`, `approved`) VALUES (:news, :text, :user, CURRENT_TIMESTAMP, 0)', [':news' => $id, ':text' => $c, ':user' => $userId]);
        return $res;
    }

    public static function getCommentByNewsID($id) {
        self::ensureModerationSchema();
        $query = "SELECT * FROM comments WHERE news_id=".(string)$id." AND approved = 1 ORDER BY id DESC";
        $db = new Database();
        $arr = $db->getAll($query);
        return $arr;
    }

    public static function getCommentsCountByNewsID($id) {
        self::ensureModerationSchema();
        $query = "SELECT count(id) as 'count' FROM comments WHERE news_id=".(string)$id." AND approved = 1";
        $db = new Database();
        $c = $db->getOne($query);
        return $c;
    }
}