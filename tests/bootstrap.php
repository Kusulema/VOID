<?php
declare(strict_types=1);

define('VOID_TEST_MODE', true);

$root = dirname(__DIR__);
chdir($root);

$includePath = get_include_path();
$includePath .= PATH_SEPARATOR . $root;
$includePath .= PATH_SEPARATOR . $root . DIRECTORY_SEPARATOR . 'admin';
set_include_path($includePath);

$autoload = $root . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
if (is_file($autoload)) {
    require_once $autoload;
}

if (!class_exists(\PHPUnit\Framework\TestCase::class)) {
    require_once __DIR__ . '/Support/PHPUnitStub.php';
}

require_once __DIR__ . '/Support/MockDatabase.php';
require_once __DIR__ . '/Support/VoidTestCase.php';

require_once $root . '/model/Register.php';
require_once $root . '/model/Comments.php';
require_once $root . '/model/Product.php';

require_once $root . '/admin/modelAdmin/modelAdmin.php';
require_once $root . '/admin/modelAdmin/modelAdminCategory.php';
require_once $root . '/admin/modelAdmin/modelAdminComments.php';
require_once $root . '/admin/modelAdmin/modelAdminProduct.php';

require_once $root . '/controller/Controller.php';
require_once $root . '/admin/controllerAdmin/controllerAdmin.php';
require_once $root . '/admin/controllerAdmin/controllerAdminProduct.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

Database::reset();