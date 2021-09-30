<?php
include 'objects/util/configuration.php';
include_once("objects/sesion/sesionManagerImpl.php");
include_once("objects/util/database.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");

$sesionManager = new SesionManagerImpl();
if (!$sesionManager->validar(array('seller','picker'))){
  Database::Connect()->close();
  header("Location: ".$GLOBALS['configuration']['redirect_home']);
  exit;
}
   $publicacionManager = new PublicacionManager();
   $jsonData = json_encode($publicacionManager->getListPublicacionIndex(),JSON_INVALID_UTF8_IGNORE);
   Database::Connect()->close();
   echo $jsonData;
?>
