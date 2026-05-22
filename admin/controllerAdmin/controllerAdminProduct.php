<?php
/**
 * Admin controller for product management (CRUD operations).
 * Requires administrator privileges for all actions.
 */
class controllerAdminProduct {
    /**
     * Show product list in admin area.
     */
    public static function ProductList() {
        controllerAdmin::requireAdmin();
        $arr = modelAdminProduct::getProductList();
        include_once 'viewAdmin/productList.php';
    }

    /**
     * Show add-product form (GET).
     */
    public static function productAddForm() {
        controllerAdmin::requireAdmin();
        $arr = modelAdminCategory::getCategoryList();
        include_once('viewAdmin/productAddForm.php');
    }

    /**
     * Handle add-product POST and show result or form with errors.
     */
    public static function productAddResult() {
        controllerAdmin::requireAdmin();
        $test = modelAdminProduct::getProductAdd();
        $arr = modelAdminCategory::getCategoryList();
        include_once('viewAdmin/productAddForm.php');
    }

    /**
     * Show edit form for product with id.
     * @param int $id
     */
    public static function productEditForm($id) {
        controllerAdmin::requireAdmin();
        $arr = modelAdminCategory::getCategoryList();
        $detail = modelAdminProduct::getProductDetail($id);
        include_once('viewAdmin/productEditForm.php');
    }

    /**
     * Handle edit-product POST and show form with result or errors.
     * @param int $id
     */
    public static function productEditResult($id) {
        controllerAdmin::requireAdmin();
        $test = modelAdminProduct::getProductEdit($id);
        $arr = modelAdminCategory::getCategoryList();
        $detail = modelAdminProduct::getProductDetail($id);
        include_once('viewAdmin/productEditForm.php');
    }

    /**
     * Show delete confirmation for a product.
     * @param int $id
     */
    public static function productDeleteForm($id) {
        controllerAdmin::requireAdmin();
        $detail = modelAdminProduct::getProductDetail($id);
        include_once('viewAdmin/productDeleteForm.php');
    }

    /**
     * Handle deletion of product and redirect to product list.
     * @param int $id
     */
    public static function productDeleteResult($id) {
        controllerAdmin::requireAdmin();
        $test = modelAdminProduct::getProductDelete($id);

        if (defined('VOID_TEST_MODE') && VOID_TEST_MODE) {
            return ['redirect' => 'productAdmin', 'deleted' => (bool)$test];
        }

        header('Location: productAdmin');
        exit;
    }
}
?>