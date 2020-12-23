<?php
//HEADER
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."producto/ProductoManager.php");
echo $contenidoStringHeader;
//FIN HEADER

    $publicacionManager = new PublicacionManager();
    $productoManager = new ProductoManager();
    $jsonData = array(
	"perfil"        => $perfil,
        "usuario"       => $GLOBALS['sesionG']['usuario'],
        "nombre"        => $GLOBALS['sesionG']['nombre'],
        "apellido"      => $GLOBALS['sesionG']['apellido'],
        "contacto"      => $GLOBALS['sesionG']['email'],
        "categoria"     => $publicacionManager->getListCategoria(),
        "publicaciones"     => $publicacionManager->getListPublicacionIndex(),
        "categoria_producto" => $productoManager->getListCategoria(),
        "rubro_producto"     => $productoManager->getListRubro()
    );
    $jsonData = json_encode($jsonData);
echo "<script>var jsonData = $jsonData;</script>";
echo $htmlContent;



//FOOTER
//include_once($GLOBALS['configuration']['path_app_admin_objects']."util/footer.php");
//echo $contenidoStringFooter;
//FIN FOOTER
?>
