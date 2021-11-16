<?php
include 'objects/util/configuration.php';
include_once("objects/sesion/sesionManagerImpl.php");
include_once("objects/util/database.php");
include_once("objects/notificaciones/NotificacionesManager.php");
$sesionManager = new SesionManagerImpl();
if (!$sesionManager->validar(array('seller','picker'))){
  Database::Connect()->close();
  header("Location: ".$GLOBALS['configuration']['redirect_home']);
  exit;
}
   $objPrincipalManager = new NotificacionesManager();
   $jsonData = json_encode($objPrincipalManager->getListNotificaciones(),JSON_INVALID_UTF8_IGNORE);
   Database::Connect()->close();
   echo $jsonData;
  exit;
