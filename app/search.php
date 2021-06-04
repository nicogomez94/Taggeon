<?php
include_once("/var/www/html/app/objects/util/configuration.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesionManagerImpl.php");

include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."producto/ProductoManager.php");

    $publicacionManager = new PublicacionManager();
    $productoManager = new ProductoManager();
    
    $jsonData = array(
        "categoria"     => $publicacionManager->getListCategoria(),
        "publicaciones"     => $publicacionManager->searchIndex($_POST),
        "categoria_producto" => $productoManager->getListCategoria(),
        "rubro_producto"     => $productoManager->getListRubro()
    );
    $jsonData = json_encode($jsonData);
echo $jsonData;



//FOOTER
//include_once($GLOBALS['configuration']['path_app_admin_objects']."util/footer.php");
//echo $contenidoStringFooter;
//FIN FOOTER
?>

