<?php
include 'objects/util/configuration.php';
include_once("objects/sesion/sesionManagerImpl.php");
include_once("objects/util/database.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."carrito/CarritoManager.php");

$sesionManager = new SesionManagerImpl();
if (!$sesionManager->validar(array('seller','picker'))){
  Database::Connect()->close();
  header("Location: ".$GLOBALS['configuration']['redirect_home']);
  exit;
}
   $objManager = new CarritoManager();
   $jsonData = json_encode($objManager->getListVentas(array()),JSON_INVALID_UTF8_IGNORE);
   Database::Connect()->close();
   echo $jsonData;
?>
