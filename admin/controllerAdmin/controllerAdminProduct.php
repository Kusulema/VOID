<?php
class controllerAdminProduct {
    public static function ProductList() {
        $arr = modelAdminProduct::getProductList();
        include_once 'viewAdmin/productList.php';
    }

    public static function productAddForm() {
        $arr = modelAdminCategory::getCategoryList();
        include_once('viewAdmin/productAddForm.php');
    }

    public static function productAddResult() {
        $test = modelAdminProduct::getProductAdd();
        $arr = modelAdminCategory::getCategoryList();
        include_once('viewAdmin/productAddForm.php');
    }

    public static function productEditForm($id) {
        $arr = modelAdminCategory::getCategoryList();
        $detail = modelAdminProduct::getProductDetail($id);
        include_once('viewAdmin/productEditForm.php');
    }

    public static function productEditResult($id) {
        $test = modelAdminProduct::getProductEdit($id);
        $arr = modelAdminCategory::getCategoryList();
        $detail = modelAdminProduct::getProductDetail($id);
        include_once('viewAdmin/productEditForm.php');
    }

    public static function productDeleteForm($id) {
        $detail = modelAdminProduct::getProductDetail($id);
        include_once('viewAdmin/productDeleteForm.php');
    }

    public static function productDeleteResult($id) {
        $test = modelAdminProduct::getProductDelete($id);
        header('Location: productAdmin');
    }
}
?>