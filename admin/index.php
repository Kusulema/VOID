<?php
session_start();
require_once '../inc/Database.php';
require_once 'modelAdmin/modelAdmin.php';
require_once 'modelAdmin/modelAdminCategory.php';
require_once 'modelAdmin/modelAdminProduct.php';
require_once 'modelAdmin/modelAdminComments.php';
require_once 'controllerAdmin/controllerAdmin.php';
require_once 'controllerAdmin/controllerAdminProduct.php';

include 'routeAdmin/routingAdmin.php';

echo $response;