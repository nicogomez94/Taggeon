<?php
include 'objects/util/configuration.php';
include_once("objects/sesion/sesionManagerImpl.php");
include_once("objects/util/database.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");

    $publicacionManager = new PublicacionManager();
   $jsonData = json_encode($publicacionManager->getListPublicacionIndex());
   Database::Connect()->close();
   echo $jsonData;
?>
