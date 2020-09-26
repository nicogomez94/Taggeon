<?php
include 'objects/util/configuration.php';
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesionManagerImpl.php");
$sesionManager = new SesionManagerImpl();
$sesionManager->cerrar();
Database::Connect()->close();
header("Location: /");
exit;
?>
