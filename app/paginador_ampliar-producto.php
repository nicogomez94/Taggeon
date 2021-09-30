<?php
include 'objects/util/configuration.php';
include_once("objects/sesion/sesionManagerImpl.php");
include_once("objects/util/database.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."producto/ProductoManager.php");

$sesionManager = new SesionManagerImpl();
if (!$sesionManager->validar(array('seller','picker'))){
  Database::Connect()->close();
  header("Location: ".$GLOBALS['configuration']['redirect_home']);
  exit;
}
   $objManager = new ProductoManager();
   $jsonData = json_encode($objManager->getListProducto(),JSON_INVALID_UTF8_IGNORE);
   Database::Connect()->close();
   echo $jsonData;
?>
