<?php
class ViewComments{
    public static function CommentsForm($newsId = 0){
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['userId'])) {
            echo '<p>Please <a href="login">sign in</a> to leave a comment.</p>';
            return;
        }
        $newsId = (int)$newsId;
        echo '<form method="POST" action="insertcomment">
        <input type="hidden" name="id" value="'.htmlspecialchars((string)$newsId).'">
            Write your comment: <input type="text" name="comment">
        <input class="submitBtn" type="submit" value="Send"> </form>';
    }

    public static function CommentsByNews($arr) {
        if($arr!=null) {
            echo '<table id="ctable"><th class=commentTitle>Kommentaar</th><th class=dateTitle>Kuupaev</th>';
            foreach($arr as $value) {
                echo '<tr><td class="comment">'.$value['text'].'</td><td class="date">'.$value['date'].'</td></tr>';
            }
            echo '</table>';
        }
    }

    public static function CommentsCountWithAncor($value) {
        if ($value['count']>0) {
            echo '<b><a href="#ctable"/> ('.$value['count'].') </a></b>';
        }
    }

    public static function CommentsCount($value) {
        if ($value['count']>0) {
            echo '<b><font color="red">('.$value['count'].') </font></b>';
        }
    }
}