<?php
class controllerAdmin {
    public static function requireAdmin() {
        if (empty($_SESSION['sessionId']) || ($_SESSION['status'] ?? '') !== 'admin') {
            header('Location: index.php');
            exit;
        }
    }

    public static function formLoginSite() {
        if (!empty($_SESSION['sessionId']) && ($_SESSION['status'] ?? '') === 'admin') {
            include_once('viewAdmin/startAdmin.php');
            return;
        }
        include_once('viewAdmin/formLogin.php');
    }
    //форма авторизации админа
    public static function loginAction() {
        $logIn = modelAdmin::userAuthentication();
        if(isset($logIn) and $logIn==true) {
            include_once('viewAdmin/startAdmin.php');
        }
        else {
            $_SESSION['errorString'] = 'Incorrect email or password';
            include_once('viewAdmin/formLogin.php');
        }
    }
    //выход из админ панели
    public static function logoutAction() {
        modelAdmin::userLogout();
        include_once('viewAdmin/formLogin.php');
    }
    //страница error
    public static function error404() {
        include_once('viewAdmin/error404.php');
    }
    public static function commentsList() {
        self::requireAdmin();
        include_once('modelAdmin/modelAdminComments.php');
        $arr = modelAdminComments::getAllComments();
        include_once('viewAdmin/commentsList.php');
    }

    public static function commentAction() {
        self::requireAdmin();
        include_once('modelAdmin/modelAdminComments.php');
        $id = $_GET['id'] ?? null;
        $action = $_GET['action'] ?? '';
        if ($id) {
            if ($action === 'approve') {
                modelAdminComments::setApproved($id, true);
            } elseif ($action === 'deny') {
                modelAdminComments::setApproved($id, false);
            } elseif ($action === 'delete') {
                modelAdminComments::deleteComment($id);
            }
        }
        header('Location: index.php');
        exit;
    }
}