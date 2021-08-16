<?php
//HEADER
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."producto/ProductoManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."notificaciones/NotificacionesManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."seguidores/SeguidoresManager.php");

echo $contenidoStringHeader;
//FIN HEADER

    $publicacionManager = new PublicacionManager();
    $productoManager = new ProductoManager();
    $seguidoresManager = new SeguidoresManager();

    $tokenMercadoPago = 0;
    if (isset($GLOBALS['sesionG']['tokenMercadoPago']) && $GLOBALS['sesionG']['tokenMercadoPago'] != ''){
        $tokenMercadoPago = 1;
    }
    
    $notificacionesManager = new NotificacionesManager();
    $jsonData = array(
        "notificaciones" => $notificacionesManager->getListNotificaciones(),
        "tokenMercadoPago" => $tokenMercadoPago,
    	"perfil"        => $perfil,
        "usuario"       => $GLOBALS['sesionG']['usuario'],
        "nombre"        => $GLOBALS['sesionG']['nombre'],
        "apellido"      => $GLOBALS['sesionG']['apellido'],
        "contacto"      => $GLOBALS['sesionG']['email'],
        "publicaciones"     => $publicacionManager->getListPublicacionIndex(),
        "categoria_producto" => $productoManager->getListCategoria(),
        "seguidores"     => $seguidoresManager->getListSeguidores(),
        "seguidos"     => $seguidoresManager->getListSeguidos()
    );

    $jsonData = json_encode($jsonData);
echo "<script>var jsonData = $jsonData;</script>";
echo $htmlContent;



//FOOTER
//include_once($GLOBALS['configuration']['path_app_admin_objects']."util/footer.php");
//echo $contenidoStringFooter;
//FIN FOOTER
?>
