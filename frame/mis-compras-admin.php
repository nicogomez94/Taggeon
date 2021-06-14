<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."producto/ProductoManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."carrito/CarritoManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."seguidores/SeguidoresManager.php");

include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");

$usuarioManager = new UsuarioManagerImpl();
if (($perfil=='seller' || $perfil=='picker') && $usuarioManager->isAdmin()){


    $publicacionManager = new PublicacionManager();
    $productoManager = new ProductoManager();
    $carritoManager = new CarritoManager();
    $seguidoresManager = new SeguidoresManager();

    $tokenMercadoPago = 0;
    if (isset($GLOBALS['sesionG']['tokenMercadoPago']) && $GLOBALS['sesionG']['tokenMercadoPago'] != ''){
        $tokenMercadoPago = 1;
    }
    
    $jsonData = array(
        "tokenMercadoPago" => $tokenMercadoPago,
        "usuario"       => $GLOBALS['sesionG']['usuario'],
        "nombre"        => $GLOBALS['sesionG']['nombre'],
        "apellido"      => $GLOBALS['sesionG']['apellido'],
        "contacto"      => $GLOBALS['sesionG']['email'],
        "categoria"     => $publicacionManager->getListCategoria(),
        "publicaciones"     => $publicacionManager->getListPublicacion(),
        "categoria_producto" => $productoManager->getListCategoria(),
        "rubro_producto"     => $productoManager->getListRubro(),
        "compras"     => $carritoManager->getListComprasAdmin(array()),
        "vendedor"    => $carritoManager->getMsj(),
        "seguidores"     => $seguidoresManager->getListSeguidores(),
        "seguidos"     => $seguidoresManager->getListSeguidos()
        
    );
    $jsonData = json_encode($jsonData);
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
    //HEADER
    echo $contenidoStringHeader;
    //FIN HEADER
    $contenidoString = Template::sostenedor_error("Permiso denegado. Solo usuarios admin pueden acceder.");
    echo $contenidoString;
}
Database::Connect()->close();
exit;

?>
