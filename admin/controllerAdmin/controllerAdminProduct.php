<?php
class controllerAdminProduct {
    public static function ProductList() {
        controllerAdmin::requireAdmin();
        $arr = modelAdminProduct::getProductList();
        include_once 'viewAdmin/productList.php';
    }

    public static function productAddForm() {
        controllerAdmin::requireAdmin();
        $arr = modelAdminCategory::getCategoryList();
        include_once('viewAdmin/productAddForm.php');
    }

    public static function productAddResult() {
        controllerAdmin::requireAdmin();
        $test = modelAdminProduct::getProductAdd();
        $arr = modelAdminCategory::getCategoryList();
        include_once('viewAdmin/productAddForm.php');
    }

    public static function productEditForm($id) {
        controllerAdmin::requireAdmin();
        $arr = modelAdminCategory::getCategoryList();
        $detail = modelAdminProduct::getProductDetail($id);
        include_once('viewAdmin/productEditForm.php');
    }

    public static function productEditResult($id) {
        controllerAdmin::requireAdmin();
        $test = modelAdminProduct::getProductEdit($id);
        $arr = modelAdminCategory::getCategoryList();
        $detail = modelAdminProduct::getProductDetail($id);
        include_once('viewAdmin/productEditForm.php');
    }

    public static function productDeleteForm($id) {
        controllerAdmin::requireAdmin();
        $detail = modelAdminProduct::getProductDetail($id);
        include_once('viewAdmin/productDeleteForm.php');
    }

    public static function productDeleteResult($id) {
        controllerAdmin::requireAdmin();
        $test = modelAdminProduct::getProductDelete($id);
        header('Location: productAdmin');
    }
}
?>