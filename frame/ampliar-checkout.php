<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
#include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");
#include_once($GLOBALS['configuration']['path_app_admin_objects']."producto/ProductoManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."carrito/CarritoManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."seguidores/SeguidoresManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");



if ($perfil=='seller' || $perfil=='picker'){

    $publicacionManager = new PublicacionManager();
    $productoManager = new ProductoManager();
    $carritoManager = new CarritoManager();
    $seguidoresManager = new SeguidoresManager();
    $usuarioManager = new UsuarioManagerImpl();


    $tokenMercadoPago = 0;
    if (isset($GLOBALS['sesionG']['tokenMercadoPago']) && $GLOBALS['sesionG']['tokenMercadoPago'] != ''){
        $tokenMercadoPago = 1;
    }
    
    $jsonData = array(
        "usuario_envio" => $usuarioManager->getUsuarioBySesion(),
        "tokenMercadoPago" => $tokenMercadoPago,
        "usuario"       => $GLOBALS['sesionG']['usuario'],
        "nombre"        => $GLOBALS['sesionG']['nombre'],
        "apellido"      => $GLOBALS['sesionG']['apellido'],
        "contacto"      => $GLOBALS['sesionG']['email'],
        #"publicaciones"     => $publicacionManager->getListPublicacion(),
        #"categoria_producto" => $productoManager->getListCategoria(),
        "carrito"     => $carritoManager->getListCarrito2(),
        "seguidores"     => $seguidoresManager->getListSeguidores(),
        "seguidos"     => $seguidoresManager->getListSeguidos()
        
    );
    $jsonData = json_encode($jsonData,JSON_INVALID_UTF8_IGNORE);
    $menuperfil = '';
    $idEditar = isset($_GET["id"]) ? $_GET["id"] : '';
    $contenido = new Template($nameTemplate);
	$contenido->asigna_variables(array(
            "json" => $jsonData,
            "usuario" => $GLOBALS['sesionG']['usuario'],
            "nombre" => $GLOBALS['sesionG']['nombre']." ".$GLOBALS['sesionG']['apellido'],
            "apellido" => $GLOBALS['sesionG']['apellido'],
            "contacto" => $GLOBALS['sesionG']['email'],
            "url_editar" => "/editar-usuario.html",
	    "id"          => $idEditar,
            "foto-perfil" => $fotoPerfil //fotoPerfil definida en header.php
			));
    $contenidoString = $contenido->muestra();
    //HEADER
    echo $contenidoStringHeader;
    //FIN HEADER
	echo $contenidoString;
}else{
    header("Location: ".$GLOBALS['configuration']['redirect_home']);
}
Database::Connect()->close();
exit;

?>
