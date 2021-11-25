<?php
include 'objects/util/configuration.php';
include_once("objects/sesion/sesionManagerImpl.php");
include_once("objects/util/database.php");
include_once("objects/comentarioproducto/ComentarioproductoManager.php");
$sesionManager = new SesionManagerImpl();
if (!$sesionManager->validar(array('seller','picker'))){
  Database::Connect()->close();
  header("Location: ".$GLOBALS['configuration']['redirect_home']);
  exit;
}
   $objPrincipalManager = new ComentarioproductoManager();
   $jsonData = json_encode($objPrincipalManager->getListComentarioproducto(),JSON_INVALID_UTF8_IGNORE);
   Database::Connect()->close();
   echo $jsonData;
  exit;
