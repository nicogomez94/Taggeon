<?php
include_once("/var/www/html/app/objects/util/configuration.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesionManagerImpl.php");

include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."producto/ProductoManager.php");

    $publicacionManager = new PublicacionManager();
    $productoManager = new ProductoManager();
    
    $jsonData = array(
        "escena"     => $publicacionManager->getListEscena(),
        "escena2"     => $publicacionManager->getListEscena2(),
        "publicaciones"     => $publicacionManager->searchIndex($_POST),
        "categoria_producto" => $productoManager->getListCategoria()
    );
Database::Connect()->close();
    $jsonData = json_encode($jsonData,JSON_INVALID_UTF8_IGNORE);
echo $jsonData;



//FOOTER
//include_once($GLOBALS['configuration']['path_app_admin_objects']."util/footer.php");
//echo $contenidoStringFooter;
//FIN FOOTER
?>

